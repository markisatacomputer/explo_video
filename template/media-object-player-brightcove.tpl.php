<?php
/**
* Media Object Player - Brightcove
*
* @group explo_video_theme
*
* @see template_preprocess()
* @see template_preprocess_node()
* @see template_process()
*/
?>
<div class='<?php print $classes; ?>'>
  <video
    id='<?php print $playerId; ?>'
    class='bc5player video-js'
    data-player='<?php print $bcPlayerId; ?>'
    data-video-id='<?php print $mediaId; ?>'
    data-account='<?php print $accountId; ?>'
    data-embed='default'
    playsinline
    style="width: 100%;height: 100%"
    controls>
  </video>
</div>
<script src='//players.brightcove.net/<?php print $accountId; ?>/<?php print $bcPlayerId; ?>_default/index.min.js'></script>
<script type='text/javascript'>

  if ( typeof(jQuery111) == 'undefined' ) jQuery111 = jQuery;
  jQuery111( document ).ready(function( $ ) {

    //| SHARE V.3
    var options = {
      'title': '<?php print htmlentities($title, ENT_QUOTES); ?>',
      'description': '<?php print htmlentities($description, ENT_QUOTES); ?>',
      'url': '<?php print $canonical; ?>',
      'services': {
        'facebook': true,
        'google': false,
        'twitter': true,
        'tumblr': true,
        'pinterest': false,
        'linkedin': false
      }
    };
    videojs('<?php print $playerId; ?>').social(options);

    <?php if ($projection): ?>
    //  No fullscreen on IOS for 360 vids please
    if (videojs.browser.IS_IOS ) {
      var fullScreenElement = document.getElementsByClassName("vjs-fullscreen-control")[0];
      fullScreenElement.parentNode.removeChild(fullScreenElement);
    }
    <?php endif; ?>

    <?php if ($livestream): ?>
    //  No fullscreen on IOS for 360 vids please
    videojs.getPlayer('<?php print $playerId; ?>').ready( function() {
        var player = this;
        var ModalDialog = videojs.getComponent("ModalDialog");

        //  Add custom modal to player
        var el = document.createElement("div");
        el.innerHTML =
        "<div id='streaming-error'>"
        +"<h1>Live Stream Coming Soon</h1>"
        +"<p>This live broadcast will be available on <strong><?php print date('l F j, Y', $airdate); ?></strong>.</p>"
        +"<p>If you're seeing this message accompanied by the \"LIVE NOW\" indicator below, please reload the page in your browser as the video player has timed out.</p>"
        +"</div>";
        var options = {
          content: el
        };
        var modal = new ModalDialog(player, options);
        player.addChild(modal);

        //  Show custom modal message when error 4 and no duration
        player.on("error", function (err) {
          var errNo = player.error().code;
          var duration = player.duration();
          if ((errNo == "4" && isNaN(duration)) || errNo == "-2" ) {
            player.errorDisplay.hide();
            modal.open();
          }
        })

        //  Show default error message when
        modal.on("modalclose", function() {
          player.errorDisplay.show();
        });

      });
    <?php endif; ?>
  });
</script>
