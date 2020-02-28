/**
 *  Media Object Vimeo Player Controller
 *
 * @module explo_video
 */

class PlayerControllerVimeo extends PlayerController {
  getPlayer () {
    var self = this;

    var promise = new Promise( (resolve, reject) => {
      var player = new Vimeo.Player(self.elId);
      player.on('loaded', function() {
        resolve(player);
      });
    });

    return promise;
  }

  updateSource(media) {
    return this.player.loadVideo(media.mediaId);
  }
}
