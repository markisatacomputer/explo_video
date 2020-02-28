<?php
/**
 * @file
 * Contains a 'media object' field entity handler to display media object thumb.
 *
 * @see  classes/MediaObject.php
 * @ingroup views_field_handlers
 */
class explo_video_handler_thumb extends views_handler_field_entity {

  function option_definition() {
    $options = parent::option_definition();
    $options['link'] = array('default' => FALSE, 'bool' => TRUE);
    $options['autoplay'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  function options_form(&$form, &$form_state) {
    $form['link'] = array(
      '#title' => t('Link this thumb to it\'s video page.'),
      '#description' => t("Enable to override this field's links."),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['link']),
    );
    $form['autoplay'] = array(
      '#title' => t('Enable autoplay in link to video page'),
      '#description' => t("This appends a url variable ?autoplay=true."),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['autoplay']),
    );

    parent::options_form($form, $form_state);
  }

  function render_thumb($node, $values) {
    if ($media = explo_video_load($node)) {
      return $media->getThumb();
    }

    return "nope";
  }

  function render($values) {
    if ($entity = $this->get_value($values)) {
      return $this->render_thumb($entity, $values);
    }
  }

}
