<?php
/**
* Media Object Player - Vimeo
*
* @group explo_video_theme
*
* @see template_preprocess()
* @see template_preprocess_node()
* @see template_process()
*/
?>
<div id="<?php print $playerId; ?>" class="<?php print $classes; ?>">
  <?php print render($player); ?>
</div>

<script src="https://player.vimeo.com/api/player.js"></script>

<?php if ($_GET['autoplay']): ?>
<script>
  var iframe = document.querySelector('#<?php print $playerId; ?> iframe');
  var player = new Vimeo.Player(iframe);

  player.on('loaded', function() {
    player.play();
  });

</script>
<?php endif; ?>
