/**
 *  Media Object Thumb Control Behavior
 *
 * @module explo_video
 *
 */

(function ($) {

  Drupal.behaviors.mediaObjectThumbControllerBehavior = {
    attach: function (context, config) {

      //
      $(document, context).ready( function() {
        $('.explo-media-object-thumb-control').click( function (event) {
          var id = this.getAttribute('id').split('-')[1];
          var mediaId = this.getAttribute('data-media-id');
          var playerId = this.getAttribute('data-player-id');
          var media = window.mediaObjects[id];
          var player = window.mediaObjectPlayers[playerId];

          //  Pause all and load/play new selection
          if (typeof(player) !== 'undefined') {
            //  Pause all players
            Object.keys(window.mediaObjectPlayers).forEach( function (key) {
              window.mediaObjectPlayers[key].pause();
            });
            //  Load/Play new selection
            player.update(media);
          }
        });
      });

    }
  };

})(jQuery);
