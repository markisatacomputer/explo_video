<?php
/**
*      Media Object Embed Shortcode functions
*
*      @group explo_video
*
*      See https://api.drupal.org/api/drupal/modules%21filter%21filter.api.php/7.x
*          https://api.drupal.org/api/drupal/modules%21filter%21filter.api.php/function/hook_filter_info/7.x
*
*/

/**
 * Implements hook_filter_info().
 */
function explo_video_filter_info() {
  $filters['media_player_shortcodes'] = array(
    'title' => t('Media Player Shortcodes'),
    'description' => t('Allows editors to enter shortcodes to embed media objects'),
    'process callback' => 'explo_video_media_player_filter_process',
    'tips callback' => 'explo_video_media_player_filter_tips',
    'cache' => TRUE,
    'weight' => 0,
  );
  return $filters;
}

/**
 * Scans text for explo video shortcodes and sends matches to explo_video_media_player_filter_replace_shortcode
 *
 * @param  String $text      the text in which to look for shortcodes
 * @param  [type] $filter
 * @param  [type] $format
 * @param  [type] $langcode
 * @param  [type] $cache
 * @param  [type] $cache_id
 * @return String
 *   rendered html or empty string
 */
function explo_video_media_player_filter_process($text, $filter, $format, $langcode, $cache, $cache_id) {
  $pattern = '/\[media(-annotated|-annotations|-annotations-switch)*:([0-9]+?)(:([^\]]+))*\]/';
  $text = preg_replace_callback($pattern, 'explo_video_media_player_filter_replace_shortcode', $text);
  return $text;
}

/**
 * Replaces explo video shortcode with rendered content
 *
 * @param  Array $matches
 * @return String           themed output or boolean FALSE
 */
function explo_video_media_player_filter_replace_shortcode($matches) {
  if ($matches[1] == '-annotated') {
    $theme_function = 'explo_media_object_player_annotated';
  } else if ($matches[1] == '-annotations') {
    $theme_function = 'explo_media_object_annotations';
  } else if ($matches[1] == '-annotations-switch') {
    $theme_function = 'explo_media_object_annotations_switch';
  } else {
    $theme_function = 'explo_media_object_player';
  }

  //  If somehow someone has entered the node id of the page we're on already, a never ending loop will happen.  Let's avoid this.
  if (array_shift(explode('?', $_GET['q'])) == "node/" . $matches[2] ) return false;

  //  make sure node is a media object before we try to theme
  $node = node_load($matches[2]);
  if ($node->type == 'media_object') {
    $vars = array(
      'node' => $node,
      'view_mode' => 'default',
    );
    //  Add annotations switch
    if (isset($matches[4]) && !empty($matches[4])) $vars['switch_label'] = $matches[4];

    //  Render Player
    $player = theme($theme_function, $vars);

    //  Ensure Styles
    $path = drupal_get_path('module', 'explo_video');
    $styles = "<link href='/$path/css/media-object-player.css' rel='stylesheet'>\r";

    return "$styles\r<div class='embedded-explo-media-object-player'>$player</div>";
  }

  return false;
}
