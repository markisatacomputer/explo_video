/**
 *  Media Object Soundcloud Player Controller
 *
 * @module explo_video
 * @see https://developers.soundcloud.com/docs/api/html5-widget
 * @see https://developers.soundcloud.com/blog/html5-widget-api
 */

class PlayerControllerSoundcloud extends PlayerController {

  getPlayer () {
    var self = this;

    var promise = new Promise( (resolve, reject) => {
      var el = document.getElementById(self.elId).querySelector('iframe');
      var player = SC.Widget(el);
      player.bind(SC.Widget.Events.READY, function() {
        resolve(player);
      });
    });

    return promise;
  }

  updateSource(media) {
    var self = this;
    var promise = new Promise( (resolve, reject) => {
      self.player.load("https://api.soundcloud.com/tracks/"+media.mediaId, {
        show_artwork: true,
        hide_related: false,
        show_comments: true,
        show_reposts: false,
        visual: true,
        auto_play: false,
        callback: () => {
          resolve(media.mediaId);
        }
      });
    });

    return promise;
  }
}
