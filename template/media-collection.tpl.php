<?php
/**
* Media Colection
*
* @group explo_video_theme
*
* @see node.tpl.php -> for full description and variable list.
* @see template_preprocess()
* @see template_preprocess_node()
* @see template_process()
*/
?>

  <div id="media-collection-container">
    <div id='media-collection-players'>
      <!-- START Players -->
      <?php $count = 0; foreach($players as $k => $p): ?>
        <div style="<?php if($count > 0) {print 'visibility:hidden;height:0;opacity:0;';} ?>">
          <?php print render($p); $count++; ?>
        </div>
      <?php endforeach; ?>
      <!-- END Players -->
    </div>

    <div id='media-collection-playlist'>
      <div class="playlist-container">
        <!-- START Playlist menu -->
        <?php foreach($thumbs as $m): ?>
          <?php print render($m); ?>
        <?php endforeach; ?>
        <!-- END Playlist menu -->
      </div>
    </div>
  </div>
