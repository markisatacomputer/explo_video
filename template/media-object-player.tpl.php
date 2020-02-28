 <?php

/**
* Media Object
* View mode: "addon"
*
* @see node.tpl.php -> for full description and variable list.
* @see template_preprocess()
* @see template_preprocess_node()
* @see template_process()
*/

?>

<div class='<?php print $classes; ?>'>
  <?php print render($title_prefix); ?>
  <?php print render($title_suffix); ?>
  <?php print render($media_player); ?>
</div>

<?php print $js_controller_tags; ?>
<script type='text/javascript'>
(function ($) {

  Drupal.behaviors.mediaObjectPlayer<?php print $meta['id']; ?>Behavior = {
    attach: function (context, config) {

      //  Load media object
      if (typeof(window.mediaObjects) == 'undefined') window.mediaObjects = {};
      if (typeof(window.mediaObjects[<?php print $meta['id']; ?>]) === 'undefined') {
        window.mediaObjects[<?php print $meta['id']; ?>] = <?php print json_encode($meta, JSON_PRETTY_PRINT); ?>;
      }

      //  Load Player
      if (typeof(window.mediaObjectPlayers) === 'undefined') window.mediaObjectPlayers = {};
      if (typeof(window.mediaObjectPlayers['<?php print $meta['playerId']; ?>']) === 'undefined') {
        window.mediaObjectPlayers['<?php print $meta['playerId']; ?>'] = new <?php print $js_controller_name; ?>('<?php print $meta['playerId']; ?>', '<?php print $meta['mediaId']; ?>');
      }
    }
  };

  //  Autoplay
  $(document).ready( function() {

    var regex = new RegExp('[\\?&]autoplay=([^&#]*)');
    var results = regex.exec(location.search);
    if (results !== null) {
      $('#<?php print $meta['playerId']; ?>').parent().bind( 'playerReady', function (event) {
        window.mediaObjectPlayers['<?php print $meta['playerId']; ?>'].play();
      });
    }

  });

})(jQuery);
</script>


<?php if (isset($annotations_switch)) print $annotations_switch; ?>
<?php if (isset($annotations)) print $annotations; ?>
