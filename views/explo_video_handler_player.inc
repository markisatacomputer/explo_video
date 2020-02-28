<?php
/**
 * @file
 * Contains a 'media object' field entity handler to display media object player.
 *
 * @see  classes/MediaObject.php
 * @ingroup views_field_handlers
 */
class explo_video_handler_player extends views_handler_field_entity {

  function render_player($node, $values) {
    if ($media = explo_video_load($node)) {
      return $media->getPlayer();
    }

    return "";
  }

  function render($values) {
    if ($entity = $this->get_value($values)) {
      return $this->render_player($entity, $values);
    }
  }

}
