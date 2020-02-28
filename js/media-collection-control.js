/**
 *  Media Collection Control Behavior
 *
 * @module explo_video
 *
 */
(function ($) {
  Drupal.behaviors.mediaCollectionControllerBehavior = {
    attach: function (context, config) {

      $(document, context).ready( function() {

        //  Show/Hide players
        var players = $('#media-collection-players .explo-media-object-player');
        if (players.length>1) {
          $('.explo-media-object-thumb-control').click( function (event) {
            var source = this.getAttribute('id').split('-')[0];
            var player = $('#media-collection-players .explo-media-object-player .explo-media-object-player-'+source).parent().parent();
            if ($(player).css('visibility') == 'hidden') {
              $('#media-collection-players .explo-media-object-player').parent().css('visibility', 'hidden').css('height', '0').css('opacity', '0');
              $(player).css('visibility', 'visible').css('visibility', 'visible').css('height', 'auto').css('opacity', '1');
            }
          });
        }

        //  Update browser URL + Contextual Link
        $('.explo-media-object-thumb-control').click( function (event) {
          var source = this.getAttribute('id').split('-')[0];
          var nid = this.getAttribute('id').split('-')[1];
          //   -- URL
          var newurl;
          if (history.pushState) {
            newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?media=' + nid;
            if (window.history.state == null) window.history.pushState({path:newurl},'',newurl);
            else if (newurl !== window.history.state.path) window.history.pushState({path:newurl},'',newurl);
          }
          //   -- Contextual links
          $('#media-collection-players .explo-media-object-player.media-source-'+source+' .contextual-links-wrapper ul li a').each( function () {
            var link = $(this).attr('href');
            var regex = /\/node\/[0-9]+\//;
            var newlink = link.replace(regex, '/node/'+nid+'/');
            $(this).attr('href', newlink);
          });

          //  Update playlist highlight
          $('.playlist-container .explo-media-object-thumb-control').removeClass('playlist-item-hot');
          $(this).addClass('playlist-item-hot');

        });

        //  Listen to window.history state changes
        window.onpopstate = function(event) {
          var nid = event.state.path.split('?')[1].split('=')[1];
          var source = window.mediaObjects[nid].source;
          document.getElementById(source+'-'+nid).click();
        };

      });

    }
  };
})(jQuery);
