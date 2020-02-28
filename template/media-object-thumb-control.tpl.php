<?php
/**
* Media Object Thumbnail Control
*
* @group explo_video_theme
*
* @see template_preprocess()
* @see template_preprocess_node()
* @see template_process()
*/
?>
<div id="<?php print $meta['source'].'-'.$meta['id']; ?>"
     data-media-id="<?php print $meta['mediaId']; ?>"
     data-player-id="<?php print $meta['playerId']; ?>"
     class="<?php print $classes; ?>">

  <div class="image">
    <?php if ($image_url): ?>
      <img src='<?php print $image_url; ?>' title="<?php print $title; ?>" alt="<?php print $alt; ?>" />
    <?php endif; ?>
    <?php if ($icon_url): ?>
      <img class='play-icon' src='<?php print $icon_url; ?>' />
    <?php endif; ?>
  </div>

<?php if ($title): ?>
  <div class="title">
    <?php print $title; ?>
  </div>
<?php endif; ?>
</div>
