<?php
/**
*      Media Object Brightcove
*
*      @group explo_video_media_object
*
*   uses CMS API & OAuth API
*     https://support.brightcove.com/overview-oauth-api-v4#Get_Client_Credentials
*/
class MediaObjectBrightcove extends MediaObject {
  public $accountId = '979328832001';
  public $projection = false;
  public $livestream = false;
  public $airdate;
  public $bcPlayerId;

  function __construct($node) {
    //  Brightcove specific service values first
    $secrets = $this->getSecrets(array(
      'brightcove_cms_client_id',
      'brightcove_cms_secret'
    ));
    $this->service = array(
      'auth_url' => 'https://oauth.brightcove.com/v4/access_token?grant_type=client_credentials',
      'client_id' => $secrets['brightcove_cms_client_id'],
      'secret' => $secrets['brightcove_cms_secret'],
    );

    parent::__construct($node);
    $this->airdate = $this->node->field_webcast_date->value();
    $this->thumb = $this->getThumbUrl();
    $this->bcPlayerId = $this->getPlayerId();
    $this->getMediaProjection();
    $this->getLiveStream();

  }

  protected function getThumbUrl() {
    $thumbUrl = parent::getThumbUrl();

    if ($thumbUrl === FALSE) {
      $data = $this->getCMSAPIRequest('');
      if ($data && isset($data->images->poster)) {
        foreach ($data->images->poster->sources as $src) {
          if (strpos($src->src, 'https') !== FALSE) {
            $thumbUrl = $src->src;
            $this->saveThumb($thumbUrl);
          }
        }
      }
    }

    return $thumbUrl;
  }

  protected function getMediaProjection() {
    $data = $this->getCMSAPIRequest('');
    if ($data && isset($data->projection) && !empty($data->projection)) {
      $this->projection = $data->projection;
    }
  }

  protected function getLiveStream() {
    $data = $this->getCMSAPIRequest('');
    if ($data && isset($data->custom_fields->islivestream) && !empty($data->custom_fields->islivestream)) {
      $this->livestream = true;
    }
  }

  protected function getCMSAPIRequest($endpoint) {
    $dataid = "bccms-$endpoint-".$this->id;
    $data = &drupal_static($dataid);

    if (!isset($data)) {
      if ($cache = cache_get($dataid, false)) {
        $data = $cache->data;
      } else {
        $vid = $this->mediaId;
        $aid = $this->accountId;
        $data_url = "https://cms.api.brightcove.com/v1/accounts/$aid/videos/$vid";
        if (!empty($endpoint)) $data_url .= "/$endpoint";
        $data = $this->serviceRequest($data_url);
        cache_set($dataid, $data, 'cache');
      }
    }

    return $data;
  }

  protected function getPlayerId() {
    $player_id = $this->node->field_player_id->value();

    switch($player_id) {
      case 'explo default':
        $pid = 'NJgjituzjl';
        break;

      default:
        $pid = $player_id;
        break;
    }

    return $pid;
  }

}
