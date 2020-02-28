<?php
/**
*      Media Object & Media Collection Admin Functions
*
*      @group explo_video
*/

/**
 *     helper function for explo_video_settings_form
 * @param  Array   $list        configuration of form list - entity type, bundle, etc.
 * @param  Array   $form
 * @param  Array   $form_state
 * @return Array                form array for list input
 */
function explo_video_form_object_list($list, $form, $form_state) {
  //  blank row
  $row = array(
    'value' => '',
    'weight' => 0,
    'remove' => 'Remove',
  );
  //  get name
  $name = $list['name'];
  //  get existing rows
  $existing = (isset($form_state['input'][$name])) ?
    $form_state['input'][$name]
    : variable_get($name, array($row));

  $form_object_list = array(
    '#type' => 'container',
    '#tree' => TRUE,
    '#prefix' => '<div id="' . $name .'">',
    '#suffix' => '</div>',
  );
  $form_object_list['rows'] = array(
    '#title' => t(ucwords(str_replace('_', ' ', substr($name, 15)))),
    '#type' => 'fieldset',
    '#tree' => TRUE,
    '#prefix' => '<table id="' . $name .'_table">',
    '#suffix' => '</table>',
  );
  $i = 0;
  foreach ($existing['rows'] as $key => $video) {
    $form_object_list['rows'][] = array(
      '#prefix' => '<tr class="' . $name .'_row draggable">',
      '#suffix' => '</tr>',
      'value' => array(
        '#type' => 'textfield',
        '#default_value' => $video['value'],
        '#autocomplete_path' => "admin/content/video/settings/autocomplete/".$list['entity']."/".$list['bundle']."/".$list['prop'],
        '#prefix' => '<td>',
        '#suffix' => '</td>',
      ),
      'weight' => array(
        '#type' => 'weight',
        '#title' => t('Weight'),
        '#default_value' => $i,
        '#delta' => 10,
        '#title_display' => 'invisible',
        '#prefix' => '<td>',
        '#suffix' => '</td>',
        '#attributes' => array('class' => array($name . '_weight')),
      ),
      'remove' => array(
        '#type' => 'submit',
        '#name' => 'remove_video-' . $name,
        '#executes_submit_callback' => FALSE,
        '#value' => t('Remove'),
        '#href' => '',
        '#ajax' => array(
          'callback' => 'explo_video_form_object_list_ajax',
          'wrapper' => $name,
        ),
        '#prefix' => '<td>',
        '#suffix' => '</td>',
      ),
    );
    $i++;
  }
  drupal_add_tabledrag($name . '_table', 'order', 'sibling', $name . '_weight');

  return $form_object_list;
}

function explo_video_form_object_list_ajax($form, $form_state) {
  $name = $form_state['triggering_element']['#ajax']['wrapper'];
  return $form['tab-' . $name][$name];
}

/**
 *     Responsible for explo video settings form.
 * @param   Array  $form         form structure
 * @param   Array  &$form_state  form state
 * @return  Array
 */
function explo_video_settings_form($form, &$form_state) {
  $form = array();

  $form['video_settings_form'] = array(
    '#type' => 'vertical_tabs',
  );

  /*
   *   Set up our vertical tabs and the lists that correspond
   */
  $form_object_lists = array(
    'video_settings_video_home_banners' => array(
      'entity' => 'node',
      'bundle' => 'media_object',
      'prop' => 'title',
    ),
    'video_settings_popular_videos' => array(
      'entity' => 'node',
      'bundle' => 'media_object',
      'prop' => 'title',
    ),
    'video_settings_featured_collections' => array(
      'entity' => 'taxonomy_term',
      'bundle' => 'media_series',
      'prop' => 'name',
    ),
    'video_settings_featured_categories' => array(
      'entity' => 'taxonomy_term',
      'bundle' => 'subject',
      'prop' => 'name',
    ),
    'video_settings_homepage_featured_video' => array(
      'entity' => 'node',
      'bundle' => 'media_object',
      'prop' => 'title',
    ),
  );
  foreach ($form_object_lists as $key => $list) {
      $list['name'] = $list_name = $key;
      $nice_name = ucwords(str_replace('_', ' ', substr($list_name, 15)));
      $form['tab-' . $list_name] = array(
      '#type' => 'fieldset',
      '#title' => t($nice_name),
      '#collapsible' => TRUE,
      '#group' => 'video_settings_form',
    );
    $form['tab-' . $list_name][$list_name] = explo_video_form_object_list($list, $form, $form_state);
    $form['tab-' . $list_name]['add_video_' . $list_name] = array(
      '#type' => 'submit',
      '#name' => 'add_video-' . $list_name,
      '#executes_submit_callback' => FALSE,
      '#value' => t('Add'),
      '#href' => '',
      '#ajax' => array(
        'callback' => 'explo_video_form_object_list_ajax',
        'wrapper' => $list_name,
      ),
    );
  }

  /*
   *    All our non-list inputs go here.
   */
  $tiname = 'video_settings_featured_collections_text';
  $form['tab-video_settings_featured_collections'][$tiname] = array(
    '#type' => 'textarea',
    '#title' => t('Video Collections description text'),
    '#description' => t('This text will appear on the featured collections page.'),
    '#default_value' => (isset($form_state['input'][$tiname])) ? $form_state['input'][$tiname] : variable_get($tiname, ''),
  );
  // banner previews/edit links
  $banner_list = variable_get('video_settings_video_home_banners', array());
  if (isset($banner_list['rows'])) {
    $form['tab-video_settings_video_home_banners']['video_settings_video_home_banners']['images'] = array(
      '#type' => 'item',
      '#title' => t('Banner Images'),
    );
    $images_table = array(
      'header' => array('Preview Image', 'Action'),
      'rows' => array(),
      'attributes' => array(),
      'caption' => '',
      'colgroups' => array(),
    );
    //  get nodes
    $nids = array();
    foreach ($banner_list['rows'] as $key => $row) {
      $end = strpos($row['value'], "]");
      $nids[] = intval(substr($row['value'], 1, $end));
    }
    $banner_nodes = node_load_multiple($nids);

    $banners = array();
    foreach ($banner_nodes as $key => $mo) {
      $mo_wrapper = entity_metadata_wrapper('node', $mo);
      $series = $mo_wrapper->field_media_series[0]->value();
      $image = $mo_wrapper->field_video_home_banner_image->value();
      $image['path'] = $image['uri'];
      $image['style_name'] = 'video_banner_thumbnail';
      $rendered_image = theme_image_style($image);
      $edit_link = l('Edit Media Object', '/node/' . $mo->nid . '/edit/');
      $images_table['rows'][] = array($rendered_image, $edit_link);
    }

    $form['tab-video_settings_video_home_banners']['video_settings_video_home_banners']['images']['#markup'] = theme_table($images_table);
  }



  $form['save_settings'] = array(
    '#type' => 'submit',
    '#name' => 'save_all_settings',
    '#executes_submit_callback' => FALSE,
    '#value' => t('Save Settings'),
    '#href' => '',
    '#ajax' => array(
      'callback' => 'explo_video_ajax_save_settings',
      'wrapper' => 'console',
    ),
  );

  return $form;
}
/**
 *    Validate settings
 * @param  Array $form          Form array
 * @param  Array &$form_state   Form state
 * @return [type]              [description]
 */
function explo_video_settings_form_validate($form, &$form_state) {
  /*
   *       Popular Videos
   */
  //  ADD
  if (strpos($form_state['triggering_element']['#name'], 'add_video-') !== FALSE) {
    $tab = substr($form_state['triggering_element']['#name'], 10);
    $form_state['input'][$tab]['rows'][] = array(
      'value' => '',
      'weight' => 0,
      'remove' => 'Remove',
    );
    $form_state['rebuild'] = TRUE;
  }
  //  REMOVE
  if (strpos($form_state['triggering_element']['#name'], 'remove_video-') !== FALSE) {
    $tab = substr($form_state['triggering_element']['#name'], 13);
    //  get cordinality to remove
    $i = $form_state['triggering_element']['#parents'][2];
    //  remove and rekey
    unset($form_state['input'][$tab]['rows'][$i]);
    $form_state['input'][$tab]['rows'] = array_values($form_state['input'][$tab]['rows']);
    unset($form_state['values'][$tab]['rows'][$i]);
    $form_state['values'][$tab]['rows'] = array_values($form_state['values'][$tab]['rows']);
    //  mark for rebuild
    $form_state['rebuild'] = TRUE;
  }
}

function explo_video_settings_form_submit($form, &$form_state) {
  foreach ($_POST as $key => $value) {
    if (strpos($key, 'video_settings') !== FALSE) {
      variable_set($key, $value);
    }
  }
}

function explo_video_settings_ajax_autocomplete($entity = 'node', $bundle = 'media_object', $prop = 'title', $text = '') {
  // Select rows that match the string
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', $entity)
    ->entityCondition('bundle', $bundle)
    ->propertyCondition($prop, '%' . db_like($text) . '%', 'like')
    ->range(0, 10);

  $result = $query->execute();
  $matches = array();
  if (isset($result[$entity])) {
    $keys = array_keys($result[$entity]);
    $items = entity_load($entity, $keys);
    foreach ($items as $id => $row) {
      $name = check_plain($row->{$prop});
      $value = "[$id] $name";
      $matches[$value] = $name;
    }
  }

  // return for JS
  drupal_json_output($matches);
}

/**
 *    Save settings to variable table
 */
function explo_video_ajax_save_settings($form, &$form_state) {
  explo_video_settings_form_submit($form, $form_state);

  $message = 'Save successful.';

  $js = "<script type='text/javascript'>
    (function ($) {
      $('span.tabledrag-changed').remove();
      $('.tabledrag-changed-warning').remove();
      $('tr.drag-previous').removeClass('drag-previous');
    })(jQuery);
  </script>
  ";

  return "<div id='console' class='clearfix'><div class='messages status'>" . $message . "</div></div>" . $js;
}

