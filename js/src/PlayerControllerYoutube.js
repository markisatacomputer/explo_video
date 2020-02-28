/**
 *  Media Object Youtube Player Controller
 *
 * @module explo_video
 */

class PlayerControllerYoutube extends PlayerController {
  getPlayer () {
    var self = this;

    var promise = new Promise( (resolve, reject) => {
      var tag = document.createElement('script');
      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      var player;
      window['onYouTubeIframeAPIReady'] = function () {
        player = new YT.Player(self.elId, {
          videoId: self.mediaId,
          playerVars: {
            rel: 0,
            modestbranding: 1
          },
          events: {
            'onReady': function () {
              resolve(player);
            },
          }
        });
      }

    });

    return promise;
  }

  play() {
    if (typeof this.player.playVideo == 'function') this.player.playVideo();
  }

  pause() {
    if (typeof this.player.pauseVideo == 'function') this.player.pauseVideo();
  }

  updateSource(media) {
    var self = this;
    var promise = new Promise( (resolve, reject) => {
      self.player.cueVideoById(media.mediaId);
      resolve(media.mediaId);
    });

    return promise;
  }
}
