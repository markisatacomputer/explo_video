<?php
/**
*      Media Object Vimeo
*
*      @group explo_video_media_object
*
*   see https://developer.vimeo.com/api/authentication
*       https://developer.vimeo.com/api/oembed/videos
*       https://oembed.com/
*/
class MediaObjectVimeo extends MediaObject {

  public $player;

  function __construct($node) {
    //  Vimeo specific service values first
    $secrets = $this->getSecrets(array(
      'vimeo_client_id',
      'vimeo_client_secret'
    ));
    $this->service = array(
      'auth_url' => 'https://api.vimeo.com/oauth/authorize/client?grant_type=client_credentials',
      'client_id' => $secrets['vimeo_client_id'],
      'secret' => $secrets['vimeo_client_secret'],
    );

    parent::__construct($node);
    $embedData = $this->getOEmbed();
    $this->player = $embedData->html;
    $this->thumb = $this->getThumbUrl();

  }

  protected function getThumbUrl() {
    $thumbUrl = parent::getThumbUrl();

    if ($thumbUrl === FALSE) {
      $embedData = $this->getOEmbed();
      $thumbUrl = $embedData->thumbnail_url;
      $this->saveThumb($thumbUrl);
    }

    return $thumbUrl;
  }

  protected function getOEmbed() {
    $embedData = cache_get('vimeo-oembed-'.$this->id, false);
    if (!$embedData) {
      $vid = $this->mediaId;
      $playerId = $this->playerId;
      $height = $this->height;
      $width = $this->width;
      $video_data_url = "https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/$vid&player_id=$playerId&width=$width&height=$height";
      $embedData = $this->serviceRequest($video_data_url);
      if ($embedData) {
        cache_set('vimeo-oembed-'.$this->id, $video_data, 'cache');
      }
    }

    return $embedData;
  }

}
