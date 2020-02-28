<?php
/**
*      Media Object Generic
*
*      @group explo_video_media_object
*
*/
class MediaObject {

  public $id;
  public $mediaId;
  public $playerId;
  public $source;
  public $format;
  public $thumb;
  public $title;
  public $titleShort;
  public $description;
  public $path;
  public $canonical;
  protected $node;
  protected $service;
  protected $token;

  function __construct($node) {
    $this->id = $node->nid;
    $this->path = '/' . drupal_get_path_alias('node/'. $node->nid);
    $this->canonical =  'https://' .$_SERVER['SERVER_NAME'] . $this->path;
    $this->node = entity_metadata_wrapper('node', $node);
    $this->source = $this->node->field_select_media_source->value();
    $this->format = $this->node->field_media_format->name->value();
    $this->format = strtolower(explode(',', $this->format)[0]);
    $this->playerId = uniqid($this->source . '-');
    $this->title = $this->node->field_html_title->value();
    $this->titleShort = (!empty(trim($this->node->field_short_title->value()))) ? $this->node->field_short_title->value() : $this->title;
    $this->description = trim(strip_tags(str_replace(array("\r\n", "\r", "\n"),"", $this->node->field_short_descriptiion->value()['safe_value'])));


    //  ID by source
    if (empty($this->source)) watchdog('explo_video', "Malformed MediaObject: No media source chosen.  '$this->title'  ($this->id)", array(), WATCHDOG_ERROR);
    else {
      $source_map = explo_video_get_sources();
      $id_field = $source_map[$this->source];
      $this->mediaId = $this->node->{$id_field}->value();

      //  trim string ids
      if (is_string($this->mediaId)) $this->mediaId = trim($this->mediaId);

      //  log empty media id
      if (empty($this->mediaId)) watchdog('explo_video', "Malformed MediaObject: source Id chosen.  '$this->title'  ($this->id)", array(), WATCHDOG_ERROR);
    }

  }

  /**
   * Return render array for themed media object player.
   *
   * @return array
   */
  public function getPlayer() {
    if (empty($this->source)) {
      return "<div class='warning'><p>Unfortunately, the media you're looking for does not have a source configured.  Sorry about that.  Try checking back later.</p></div>";
    }

    $vars = $this->getMeta();
    return theme('explo_media_object_player_' . $this->source, $vars);
  }

  /**
   * Return render array for themed media object thumb.
   *
   * @return array
   */
  public function getThumb($image_style='') {
    //  Get alt attrubute

    if (strlen($this->description) > 120) {
      $alt = wordwrap($this->description, 120);
      $alt = substr($alt, 0, strpos($alt, "\n")) . "...";
    }

    $vars = array(
      'image_url' => $this->thumb,
      'icon_url' => '/'. drupal_get_path('module', 'explo_video') . '/images/' . $this->format . '.svg',
      'path' => $this->path,
      'classes' => array($this->source),
      'title' => htmlentities($this->title),
      'alt' => htmlentities($alt),
    );
    if ($image_style) $vars['image_style'] = $image_style;

    return theme('explo_media_object_thumb', $vars);
  }

  /**
   * Return render array for themed media object thumb which will control a player returned from getPlayer method.
   *
   * @return array
   */
  public function getThumbControl($options = array()) {
    //  Get defaults
    $vars = array(
      'image_url' => $this->thumb,
      'classes' => array($this->source),
      'title' => $this->titleShort,
      'meta' => $this->getMeta(),
    );
    //  Override with options
    foreach ($options as $key => $value) {
      switch ($key) {
        case 'classes':
          $vars['classes'] = array_merge($options['classes'], $vars['classes']);
          break;
        case 'icon':
          $vars['icon_url'] = '/'. drupal_get_path('module', 'explo_video') . '/images/' . $this->format . '.svg';
          break;

        default:
          $vars[$key] = $value;
          break;
      }
    }

    return theme('explo_media_object_thumb_control', $vars);
  }

  public function getMeta() {
    $attrs = get_object_vars($this);
    unset($attrs['node']);
    unset($attrs['service']);
    unset($attrs['token']);

    return $attrs;
  }

  protected function getThumbUrl() {
    $thumbUrl = FALSE;

    //  default still from pod image
    $file = $this->node->field_pod_image->value();
    if (isset($file['uri'])) {
      $thumbUrl = file_create_url($file['uri']);
    }
    //  fallback WMD image
    if ($thumbUrl === FALSE) {
      $files = $this->node->field_wmd_photo_loc_lrg->value();
      if (count($files)>0 && $files[0]['uri']){
        $thumbUrl = file_create_url($files[0]['uri']);
      }
    }

    return $thumbUrl;
  }

  protected function saveThumb($thumbUrl) {
    //  make sure directory exists
    $dir = 'public://media-object/';
    $ready = file_prepare_directory($dir, FILE_CREATE_DIRECTORY);
    //  get thumb url from brightcove and download image
    if ($ready) {
      $result = drupal_http_request($thumbUrl);
      $types  = array('image/jpeg', 'image/png', 'image/gif');
      if ($result->data && $result->code != 400 && $result->code != 500 && in_array($result->headers['content-type'], $types)) {
        //  extract filename
        $url = explode('/', $thumbUrl);
        $filename = $url[count($url)-1];
        $filename = explode('?', $filename);
        $filename = $filename[0];
        //  save image
        $filepath = $dir . $filename;
        $pod_image = file_save_data($result->data, $filepath);
        //  save media object node
        if ($pod_image !== FALSE) {
          $this->node->field_pod_image->file->set($pod_image);
          $this->node->save();
          watchdog('explo_video', 'saved still (%still) to pod image.  <pre>' . var_export($pod_image, true) . '</pre>', array('%still' => $thumbUrl), WATCHDOG_NOTICE, 'link');
          return $pod_image;
        }
      }
    }
    return FALSE;
  }

  /**
   * Get secrets from Pantheon.io hiding place
   *     see https://pantheon.io/docs/private-paths/
   *         https://github.com/pantheon-systems/terminus-secrets-plugin
   *
   * @param  Array $secret_keys The keys of the secret vars to return
   * @return Array
   */
  protected function getSecrets($secret_keys) {
    $json = &drupal_static(__FUNCTION__);

    if (!isset($secrets)) {
      $json_text = file_get_contents('sites/default/files/private/secrets.json');
      $json = json_decode($json_text, true);
    }

    //  Only include specified secrets
    $secret = array();
    foreach ($json as $key => $value) {
      if (in_array($key, $secret_keys)) {
        $secrets[$key] = $value;
      }
    }

    return $secrets;
  }

  /**
   * Get Oauth token from service
   *
   * @return string    Or false on error
   */
  protected function getToken() {
    $token = $this->token;

    if (!isset($token)) {
      if ($cache = cache_get($this->source . '_media_service_token')) {
        $token = $cache->data;
      }
      else {
        $client_id = $this->service['client_id'];
        $secret = $this->service['secret'];
        $auth_url = $this->service['auth_url'];
        $auth_head = base64_encode("$client_id:$secret");
        $res = drupal_http_request($auth_url, array(
          'method' => 'POST',
          'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => "Basic $auth_head",
          ),
        ));
        //  log errors
        if (isset($res->error)) {
          watchdog('media_object', '->getToken: error    <pre>' . var_export($res, true) . '</pre>');
          return false;
        }

        $data = json_decode($res->data);

        if ($data->access_token) {
          $token = $data->access_token;
          cache_set($this->source . '_media_service_token', $token, 'cache', strtotime('+'.($data->expires_in - 5).' seconds'));
        }
      }
    }

    return $token;
  }

  /**
   * Send request to associated service
   *
   * @param  string $endpoint
   * @return object            json data
   */
  protected function serviceRequest($endpoint) {
    $token = $this->getToken();
    $res = drupal_http_request($endpoint, array(
      'headers' => array(
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Authorization' => "Bearer $token",
      ),
    ));

    //  log errors
    if (isset($res->error)) {
      watchdog('media_object', '->serviceRequest: error    <pre>' . var_export($res, true) . '</pre>');
      return false;
    }

    return json_decode($res->data);
  }

}
