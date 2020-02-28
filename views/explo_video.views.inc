<?php
/**
 * @file
 * Views definitions for 'example'
 */

/**
 * Implements hook_views_data().
 */
function explo_video_views_data() {
  $data = array();

  // thumb
  $data['views_entity_node']['explo_video_thumb'] = array(
    'field' => array(
      'title' => t('Thumb'),
      'help' => t('The media object thumb.'),
      'handler' => 'explo_video_handler_thumb',
    ),
    'group' => t('Media Object'),
  );
  // thumb control
  $data['views_entity_node']['thumb_control'] = array(
    'field' => array(
      'title' => t('Thumb controller'),
      'help' => t('A media object thumbnail that controls a player.'),
      'handler' => 'explo_video_handler_thumb_control',
    ),
    'group' => t('Media Object'),
  );
  // thumb
  $data['views_entity_node']['player'] = array(
    'field' => array(
      'title' => t('Player'),
      'help' => t('The media object player.'),
      'handler' => 'explo_video_handler_player',
    ),
    'group' => t('Media Object'),
  );
  return $data;
}