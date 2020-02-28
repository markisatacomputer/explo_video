<?php
/**
*      Media Object & Media Collection Theme Functions
*
*      @group explo_video
*/

/**
 * Implements hook_theme().
 */
function explo_video_theme($existing, $type, $theme, $path) {
  $theme = array(
    'explo_video_subjects_page' => array(
      'variables' => array('title' => 'Subjects', 'subjects' => array()),
      'template' => 'video-subjects-page',
      'path' => $path . '/template',
    ),
    'explo_video_collections_page' => array(
      'variables' => array('title' => 'Collections', 'collections' => array()),
      'template' => 'video-collections-page',
      'path' => $path . '/template',
    ),
    'explo_video_collection' => array(
      'variables' => array('collection' => NULL),
      'template' => 'media-collection',
      'path' => $path . '/template',
    ),
    'explo_media_object_thumb' => array(
      'variables' => array('image_url' => NULL, 'icon_url' => drupal_get_path('module', 'explo_video') . '/images/play.svg', 'image_style' => 'pod_small_wide_260_x_146', 'path' => NULL),
      'template' => 'media-object-thumb',
      'path' => $path . '/template',
    ),
    'explo_media_object_thumb_control' => array(
      'variables' => array('image_url' => NULL, 'meta' => array()),
      'template' => 'media-object-thumb-control',
      'path' => $path . '/template',
    ),
    'explo_media_object_player' => array(
      'variables' => array('node' => NULL),
      'template' => 'media-object-player',
      'path' => $path . '/template',
    ),
    'explo_media_object_player_annotated' => array(
      'variables' => array('node' => NULL),
      'template' => 'media-object-player',
      'path' => $path . '/template',
    ),
    'explo_media_object_annotations' => array(
      'variables' => array('node' => NULL),
      'template' => 'media-object-annotations',
      'path' => $path . '/template',
    ),
    'explo_media_object_attribution' => array(
      'variables' => array('node' => NULL),
      'file' => 'theme.inc',
    ),
    'explo_media_object_annotations_switch' => array(
      'variables' => array('node' => NULL, 'switch_label' => 'Annotations'),
      'template' => 'media-object-annotations-switch',
      'path' => $path . '/template',
    ),
  );
  //  Add player templates for all media object sources
  $players = explo_video_get_sources();
  foreach ($players as $source => $field) {
    $theme["explo_media_object_player_$source"] = array(
      'variables' => array('node' => NULL),
      'template' => "media-object-player-$source",
      'path' => $path . '/template',
    );
  }

  return $theme;
}

function explo_video_subjects_page() {
  return theme('explo_video_subjects_page');
}

function explo_video_collections_page() {
  return theme('explo_video_collections_page');
}

/**
 *
 * @param  [Array] $vars      Media Object Node & other template vars
 * @return [Array]            Processed template vars
 */
function template_preprocess_explo_media_object_thumb (&$vars) {
  //  Image Style
  $img_url = $vars['image_url'];
  $img_uri = explode('/files/', $img_url);
  $img_uri = file_build_uri(array_pop($img_uri));
  if ($img_url !== false && $vars['image_style'] && file_valid_uri($img_uri) && file_exists($img_uri)) {
    $vars['image_url'] = image_style_url($vars['image_style'], $img_uri);
  }

  $vars['classes_array'] = array_merge($vars['classes_array'], $vars['classes']);

  //  CSS
  drupal_add_css(drupal_get_path('module', 'explo_video') . '/css/media-object-thumb.css');
}

/**
 *  Preprocess template for media object thumbs that control media object players.
 *
 * @param  array $vars      Media Object Node & other template vars
 * @return array            Processed template vars
 */
function template_preprocess_explo_media_object_thumb_control (&$vars) {
  //  Fallback for videos without thumb url
  if ($vars['image_url'] === false && empty($vars['icon_url'])) {
    $vars['image_url'] =  '/'. drupal_get_path('module', 'explo_video') . '/images/' . $vars['meta']['format'] . '.svg';
    $vars['image_style'] = false;
  }

  //  We do the same as above thumb
  template_preprocess_explo_media_object_thumb($vars);

  //  JS
  drupal_add_js(drupal_get_path('module', 'explo_video') . '/js/media-object-thumb-control.js');
}

/**
 *
 * @param  [Array] $vars      Media Object Node & other template vars
 * @return [Array]            Processed template vars
 */
function template_preprocess_explo_media_object_player (&$vars) {
  //  Add contextual links
  if (user_access('edit own media_object content', $GLOBALS['user'])) {
    $contextual = array(
      'elements' => array(
        '#contextual_links' => array(
          'node_edit' => array(
            'node',
            array($vars['node']->nid),
          ),
        ),
      ),
    );
    contextual_preprocess($contextual, 'node');
    $vars = array_merge_recursive($vars, $contextual);
  }

  //  load media
  $media = explo_video_load($vars['node']);
  //  render player
  $vars['media_player'] = $media->getPlayer();
  //  save source
  $vars['source'] = $media->source;
  $vars['classes_array'][] = 'media-source-'.$media->source;
  //  add meta
  $vars['meta'] = $media->getMeta();

  //  CSS
  drupal_add_css(drupal_get_path('module', 'explo_video') . '/css/media-object-player.css');

  //  JS
  $path = drupal_get_path('module', 'explo_video');
  $vars['js_controller_tags'] = "<script type='text/javascript' src='/$path/js/promises-polyfill.min.js'></script>\r";
  $vars['js_controller_tags'] .= "<script type='text/javascript' src='/$path/js/PlayerController.min.js'></script>\r";

  $js_controller_path = "$path/js/PlayerController".ucfirst($media->source).".min.js";
  $vars['js_controller_name'] = "PlayerController";
  if (file_exists($js_controller_path)) {
    $vars['js_controller_tags'] .= "<script type='text/javascript' src='/$js_controller_path'></script>\r";
    $vars['js_controller_name'] .= ucfirst($media->source);
  }
}

/**
 *
 * @param  [Array] $vars      Media Object Node & other template vars
 * @return [Array]            Processed template vars
 */
function template_preprocess_explo_media_object_player_annotated (&$vars) {
  //| Default Preprocess
  template_preprocess_explo_media_object_player($vars);
  //| Add annotations
  $vars['annotations'] = theme('explo_media_object_annotations', $vars);
  $vars['annotations_switch'] = theme('explo_media_object_annotations_switch', $vars);
}

/**
 *
 * @param  [Array] $vars      Media Object Node & other template vars
 * @return [Array]            Processed template vars
 */
function template_preprocess_explo_media_object_annotations (&$vars) {
  //| Wrap node
  $node = entity_metadata_wrapper('node', $vars['node']);

  $vars['bcid'] = $node->field_brightcove_id->value();
  //| Get Annotations
  $annotations = array();
  $overlays = array();
  foreach($node->field_brightcove_annotations->value() as $i => $fc) {
    //|  Timing
    $cuepoint = $node->field_brightcove_annotations[$i]->field_cuepoint_time->value();
    $cuepoint = explode('-', $cuepoint);
    $cuepoint[0] = explo_video_cuepoint_to_seconds($cuepoint[0]);
    $cuepoint[1] = isset($cuepoint[1]) ? explo_video_cuepoint_to_seconds($cuepoint[1]) : $cuepoint[0]+5;
    //|  Annotations for html layout
    $annotations[] = array(
      'title' => $node->field_brightcove_annotations[$i]->field_html_title->value(),
      'id' => $node->field_brightcove_annotations[$i]->field_cuepoint_id->value(),
      'cuepoint' => $cuepoint[0],
      'description' => $node->field_brightcove_annotations[$i]->field_medium_description->value(),
      'display' => $node->field_brightcove_annotations[$i]->field_cuepoint_display_time->value(),
    );
    //|  Annotations for js config
    $overlays[] = (object) array(
      'align' => 'bottom',
      'start' => $cuepoint[0],
      'end' => $cuepoint[1],
      'content' => $node->field_brightcove_annotations[$i]->field_html_title->value(),
    );
  }

  if (count($annotations) > 0) {
    $vars['annotations'] = $annotations;
    $vars['overlays'] = json_encode($overlays);
  }
}

/**
 *
 * @param  [Array] $vars      Media Object Node & other template vars
 * @return [Array]            Processed template vars
 */
function template_preprocess_explo_media_object_annotations_switch (&$vars) {
  //| Wrap node
  $node = entity_metadata_wrapper('node', $vars['node']);

  $vars['bcid'] = $node->field_brightcove_id->value();
}

/**
 * Media Object - ATTRIBUTION
 *
 * @param  Array &$vars   'node' => [Object - media object node],
 * @return String          Styled output
 */
function theme_explo_media_object_attribution (&$vars) {
  //| Wrap node
  $node = entity_metadata_wrapper('node', $vars['node']);

  $output = '';

  //    author
  $author = $node->field_created_by->value();
  if (isset($author->nid) && $author->nid !== "9501") {
    $output .= "<div class='attribution author'>Created by $author->title.</div>";
  }

  //    copyright & license
  if (isset($node->field_copyright_holder) && isset($node->field_property_license)) {
    $copyright_holder = $node->field_copyright_holder->value();
    $license_term = $node->field_property_license->value();
    $year = $node->field_copyright_year->value();
    $license = empty($license_term->field_license_url) ? $license_term->name : l($node->field_property_license->name->value(), $node->field_property_license->field_license_url->value());

    if ($copyright_holder->nid !== "9501" || $license_term->tid !== "1922") {
      $output .= "<div class='attribution copyright'>&copy; $year $copyright_holder->title. $license.</div>";
    }
  }

  return $output;
}

/**
 * Implements template_preprocess_HOOK().
 */
function template_preprocess_explo_video_subjects_page(&$vars) {
  if (module_exists('pathauto')) require_once drupal_get_path('module', 'pathauto') . '/pathauto.inc';
  else $pathauto == false;

  //  Set up vars for FEATURED CATEGORIES list
  $terms = taxonomy_get_tree(33, 0, 1,true);
  $category_list = array();
  foreach ($terms as $key => $term) {
    $twrap = entity_metadata_wrapper('taxonomy_term', $term);
    $image = $twrap->field_pod_image->value();
    $view = views_get_view('video_categories');
    $view->set_display('count_block');
    $view->set_arguments(array($term->tid));
    $view->execute();
    $slug = ($pathauto !== FALSE) ? pathauto_cleanstring($term->name) : str_replace('&','and',strtolower(str_replace(' ', '-', $term->name)));
    $category_list[] = array(
      'name' => $term->name,
      'link' => "/search/subject/$slug/type/media_object",
      'image' => file_create_url($image['uri']),
      'count' => $view->result[0]->nid,
    );
  }
    $vars['subjects'] = $category_list;
}

/**
 * Implements template_preprocess_HOOK().
 */
function template_preprocess_explo_video_collections_page(&$vars) {
  $collections = variable_get('video_settings_featured_collections', array());
  if (isset($collections['rows'])) {
    $tids = array();
    foreach ($collections['rows'] as $key => $row) {
      if (count($tids) < 12) {
        $end = strpos($row['value'], "]");
        $tids[] = intval(substr($row['value'], 1, $end));
      }
    }
    $terms = taxonomy_term_load_multiple($tids);
    $collection_list = array();
    foreach ($terms as $key => $term) {
      $twrap = entity_metadata_wrapper('taxonomy_term', $term);
      $image = $twrap->field_pod_image->value();
      $collection_list[] = array(
        'name' => $term->name,
        'link' => drupal_get_path_alias('taxonomy/term/' . $term->tid),
        'image' => file_create_url($image['uri']),
        'description' => '<p>' . explo_video_theme_truncate_desc($term->description) . '</p>',
      );
    }
    $vars['collections'] = $collection_list;
  }
  $vars['all_collections'] = views_embed_view('video_collections', 'all_collections_block');
  $vars['description'] = variable_get('video_settings_featured_collections_text', '');
}

/**
 *
 * @param  [Array] $collection   Media Object Nodes
 * @return [Array]               An array of processed Media Objects
 */
function template_preprocess_explo_video_collection (&$vars) {
  //  Init vars
  //  -  Players
  $vars['players'] = array();
  //  -  JS settings
  $players = array();
  //  -  Media Objects
  $vars['thumbs'] = array();


  //  If we are returning to a video make sure this is the first player
  if($_GET['media']) {
    foreach ($vars['collection'] as $i => $node) {
      if ($vars['collection'][$i]->nid == $_GET['media']) {
        $media = explo_video_load($node);
        $source = $media->source;
        $vars['players'][$source] = theme('explo_media_object_player', array('node' => $node));
        $players[$source] = $media->playerId;
        break;
      }
    }
  }

  //  Process all Media Objects - add player and source
  foreach ($vars['collection'] as $i => $node) {
    $media = explo_video_load($node);
    $source = $media->source;

    //  Highlight current player thumb
    $thumb_options = array();
    if ( ($_GET['media'] && $vars['collection'][$i]->nid == $_GET['media'])
          || ($i=== 0 && !isset($_GET['media'])) ) $thumb_options['classes'] = array('playlist-item-hot');

    //  Add player and thumb.
    if (!isset($vars['players'][$source])) {
      $vars['players'][$source] = theme('explo_media_object_player', array('node' => $node));
      $players[$source] = $media->playerId;
      $vars['thumbs'][] = $media->getThumbControl($thumb_options);
    //  Add only thumb - override playerId
    } else {
      $thumb_options['meta'] = $media->getMeta();
      $thumb_options['meta']['playerId'] = $players[$source];
      $vars['thumbs'][] = $media->getThumbControl($thumb_options);
    }
  }

  //  JS & CSS
  drupal_add_js(drupal_get_path('module', 'explo_video') . '/js/media-collection-control.js');
  drupal_add_css(drupal_get_path('module', 'explo_video') . '/css/media-collection.css');
}


/**
 * Take video cue string and convert to seconds integer value.
 *
 * @param  string $cuepoint
 * @return int
 */
function explo_video_cuepoint_to_seconds($cuepoint) {
  $cuepoint = explode(':', $cuepoint);
  return (intval($cuepoint[0]) * 3600) + (intval($cuepoint[1]) * 60) + intval($cuepoint[2]);
}
