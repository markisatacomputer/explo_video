<?php
/**
*      Media Object Generic Audio
*
*      @group explo_video_media_object
*
*      NOTE:  This media object type is themed in a way that it could store any html5 video/audio asset and theme it correctly.  In the future this could be labelled in the admin interface in a different way so that's this is obvious.
*
*/
class MediaObjectMp3 extends MediaObject {

  public $mediaSources;

  function __construct($node) {
    parent::__construct($node);

    $this->mediaSources = array($this->format.'/mp3' => $this->mediaId);
    $this->thumb = $this->getThumbUrl();

  }

}
