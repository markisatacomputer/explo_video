<?php
/**
*      Media Object Youtube
*
*      @group explo_video_media_object
*
*   see https://developer.vimeo.com/api/authentication
*       https://developer.vimeo.com/api/oembed/videos
*       https://oembed.com/
*/
class MediaObjectYoutube extends MediaObject {

  function __construct($node) {
    parent::__construct($node);

    $this->thumb = $this->getThumbUrl();
  }

}
