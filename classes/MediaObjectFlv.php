<?php
/**
*      Media Object FLV
*
*      @group explo_video_media_object
*
*/
class MediaObjectFlv extends MediaObject {

  public $dirname;
  public $filename;

  function __construct($node) {
    parent::__construct($node);

    $this->mediaId = $this->mediaId[0];
    $flv = pathinfo($this->mediaId);

    $this->dirname = $flv['dirname'];
    $this->filename = $flv['filename'];
    $this->thumb = $this->getThumbUrl();
  }

  protected function getThumbUrl() {
    $thumbUrl = parent::getThumbUrl();

    if ($thumbUrl === FALSE) {
      $files = $this->node->field_wmd_photo_loc_lrg->value();
      if (count($files)>0 && $files[0]['uri']){
        $thumbUrl = file_create_url($files[0]['uri']);
      }
    }

    return $thumbUrl;
  }

}
