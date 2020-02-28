<?php
/**
* Media Object Thumbnail
*
* @group explo_video_theme
*
* @see template_preprocess()
* @see template_preprocess_node()
* @see template_process()
*/
?>
<div class="explo-video-thumb <?php print $classes ?>">
  <a href='<?php print $path  . '?autoplay=true'; ?>'>
    <img src='<?php print $image_url; ?>' title="<?php print $title; ?>" alt="<?php print $alt; ?>" />
    <div class='play-icon'>
      <img src='<?php print $icon_url; ?>' />
    </div>
  </a>
</div>
