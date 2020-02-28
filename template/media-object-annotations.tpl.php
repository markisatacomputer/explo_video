<?php if(count($annotations) > 0): ?>
<div class="annotations-container grid-100 grid-parent">
  <ul>
  <?php foreach ($annotations as $a): ?>
    <li id="<?php print $a['id']; ?> " class="media-object-annotation">
     
      <!-- <div class="grid-100 grid-parent"> -->
       <!--  <div class="annotation-title"> -->
         <!--  <p>• --> <?php print $a['title'] ?> (<?php print $a['display']; ?>) <!-- </p> -->

            <!-- <span class="timecode"> ( -->
        <!-- <button  data-cuepoint="<?php //print $a['cuepoint']; ?> ) " >
          <?php //print $a['display']; ?>
         </button>  -->
      <!-- </span></p> -->
       <!--  </div> -->
      <?php if($a['description']): ?>
      <div class="annotation-description">
        <p><?php print $a['description'] ?></p>
      </div>
      <?php endif; ?>
    </li>
  <?php endforeach; ?> </ul>
</div>

<script type="text/javascript" src="https://players.brightcove.net/videojs-overlay/2/videojs-overlay.min.js"></script>

<script type="text/javascript">
//  Find and wrap jQ
if ( typeof(jQuery111) == 'undefined' ) jQuery111 = jQuery;
jQuery111( document ).ready(function( $ ) {
  var pid = $('.bc5player[data-video-id=<?php print $bcid; ?>]').attr('id');
  var overlayConfig = {
    content: '',
    overlays: <?php print $overlays; ?>
  }

  videojs(pid).ready(function(){
    var BCPlayer = this;

    //  Add overlay
    BCPlayer.overlay(overlayConfig);

    //  Annotation buttons
    $('.timecode button').click(function(e){
      var cuepoint = $(this).attr('data-cuepoint');
      BCPlayer.currentTime(Number(cuepoint));
      BCPlayer.play();
    });

  });
});
</script>

<style type="text/css">

  .bc5player .vjs-overlay {
    display: none;
  }
  .bc5player.annotations-on .vjs-overlay {
    display: inherit;
    font-size: 160%;
    font-family: "ff-unit-web", Arial, sans-serif; 
    line-height:140%;
    font-weight: 500;
    text-shadow: 2px 1px 3px black;
    background-color: rgba(0, 109, 217, 0.50);
  }

  .video-js .vjs-overlay{color:#fff;position:absolute;text-align:center}
  .video-js .vjs-overlay-no-background{max-width:66%}
  .video-js .vjs-overlay-background{background-color:#646464;background-color:rgba(255,255,255,0.4);border-radius:3px;padding:10px 8px;width:66%}
  .video-js .vjs-overlay-top-left{top:5px;left:5px}
  .video-js .vjs-overlay-top{left:50%;margin-left:-16.5%;top:5px}
  .video-js .vjs-overlay-top-right{right:5px;top:5px}
  .video-js .vjs-overlay-right{right:5px;top:50%;transform:translateY(-50%)}
  .video-js .vjs-overlay-bottom-right{bottom:3.5em;right:5px}
  .video-js .vjs-overlay-bottom{bottom:3.5em;left:50%;margin-left:-33%}
  .video-js .vjs-overlay-bottom-left{bottom:3.5em;left:5px}
  .video-js .vjs-overlay-left{left:5px;top:50%;transform:translateY(-50%)}
  .video-js .vjs-overlay-center{left:50%;margin-left:-16.5%;top:50%;transform:translateY(-50%)}
  .video-js .vjs-no-flex .vjs-overlay-left,.video-js .vjs-no-flex .vjs-overlay-center,
  .video-js .vjs-no-flex .vjs-overlay-right{margin-top:-15px}

  .bc5player.annotations-on .vjs-text-track-display {
    bottom: 0;
  }
</style>

<?php else: ?>
<!--  Video ANNOTATIONS are Empty -->
<?php endif; ?>
