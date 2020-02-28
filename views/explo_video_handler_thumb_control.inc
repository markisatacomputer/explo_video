<?php
/**
 * @file
 * Contains a 'media object' field entity handler to display media object thumb that can control a player.
 *
 * @see  classes/MediaObject.php
 * @ingroup views_field_handlers
 */
class explo_video_handler_thumb_control extends views_handler_field_entity {

  function render_thumb($node, $values) {
    if ($media = explo_video_load($node)) {
      return $media->getThumbControl();
    }

    return "";
  }

  function render($values) {
    if ($entity = $this->get_value($values)) {
      return $this->render_thumb($entity, $values);
    }
  }

}
