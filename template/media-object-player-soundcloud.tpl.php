<?php
/**
* Media Object Player - Soundcloud
*
* @group explo_video_theme
*
* @see template_preprocess()
* @see https://developers.soundcloud.com/docs/api/html5-widget
* @see https://developers.soundcloud.com/blog/html5-widget-api
*/
?>
<div id="<?php print $playerId; ?>" class="<?php print $classes; ?>">
  <iframe width="100%" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/<?php print $mediaId; ?>&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true&amp;show_artwork=true">
  </iframe>
</div>

<script src="https://w.soundcloud.com/player/api.js"></script>
