<!--  BEGIN Annotations Switch: Media Object <?php print $bcid; ?>  -->
<div class="media-object-annotation-switch-wrapper">

  <div class="media-object-annotation-switch">
    <input id="switch-me-<?php print $bcid; ?>" type="checkbox" data-video-id="<?php print $bcid; ?>">
    <label for="switch-me-<?php print $bcid; ?>" class="option">
      <span class="active-state"> On</span>
      <i class="icon"></i>
      <span class="inactive-state">  Off</span>
    </label>
  </div>
  <div class="media-object-annotation-switch-label"><p><?php print $switch_label; ?></p></div>

  <script type="text/javascript">
  //  Find and wrap jQ
  if ( typeof(jQuery111) == 'undefined' ) jQuery111 = jQuery;
  jQuery111( document ).ready(function( $ ) {
    var pid = $('.bc5player[data-video-id=<?php print $bcid; ?>]').attr('id');

    videojs(pid).ready(function(){
      var BCPlayer = this;

      //  Show/Hide Annotations
      $('.media-object-annotation-switch input[data-video-id=<?php print $bcid; ?>]').click(function(e){
        if (this.checked) {
          $('#'+pid).addClass('annotations-on');
        } else {
          $('#'+pid).removeClass('annotations-on');
        }
      });

    });
  });
  </script>
</div>
<!--  END Annotations Switch: Media Object <?php print $bcid; ?>  -->
