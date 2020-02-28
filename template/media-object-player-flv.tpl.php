<?php
/**
* Media Object Player - FLV
*
* @group explo_video_theme
*
* @see http://blog.deconcept.com/swfobject/
* @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/embed
* @see template_preprocess()
* @see template_preprocess_node()
*
*/
?>
<div id="<?php print $playerId; ?>"
    class="<?php print $classes; ?>"
    data-dirname="<?php print $dirname; ?>"
    data-filename="<?php print $filename; ?>"
    data-thumb="<?php print $thumb; ?>">
</div>
