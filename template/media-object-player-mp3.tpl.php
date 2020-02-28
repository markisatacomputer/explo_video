<?php
/**
* Media Object Player - Generic HTML5
*
* @group explo_video_theme
*
* @see https://developer.mozilla.org/en-US/docs/Learn/HTML/Multimedia_and_embedding/Video_and_audio_content
* @see template_preprocess()
* @see template_preprocess_node()
*
*/
?>
<div id="<?php print $playerId; ?>" class="<?php print $classes; ?> media-format-<?php print $format; ?>">
  <?php if ($format == 'audio'): ?>
  <div class="highlight_img">
    <img src="<?php print $thumb; ?>" />
    <img class="play-icon" src="/<?php print  drupal_get_path('module', 'explo_video'); ?>/images/audio.svg">
  </div>
  <?php endif; ?>
  <<?php print $format; ?> class="html5-player"
    preload="auto"
    poster="<?php print $thumb; ?>"<?php if ($_GET['autoplay']): ?>
    autoplay<?php endif; ?>
    controls>
      <?php foreach ($mediaSources as $type => $media_source): ?>
      <source src="<?php print $media_source; ?>">
      <source src="<?php print $media_source; ?>" type="<?php print $type; ?>">
      <?php endforeach ?>
      <p>Your browser doesn't support HTML5 <?php print $format; ?>. Here is a <a href="<?php print $mediaId; ?>">link to the <?php print $format; ?></a> instead.</p>
  </<?php print $format; ?>>
</div>
