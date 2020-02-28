/**
 *  Media Object Brightcove Player Controller
 *
 * @module explo_video
 */
class PlayerControllerBrightcove extends PlayerController {
  getPlayer () {
    var self = this;
    var promise = new Promise( (resolve, reject) => {
      videojs.getPlayer(self.elId).ready( function() {
        resolve(this);
      });
    });

    return promise;
  }

  updateSource(media) {
    var self = this;
    var promise = new Promise( (resolve, reject) => {
      self.player.catalog.getVideo(media.mediaId, function(error, video) {
        if (error) { reject(error); }
        else {
          self.player.catalog.load(video);
          self.player.social({
            "title": media.shortTitle,
            "description": media.description,
            "url": media.canonical,
            "deeplinking": false,
            "services": {
              "facebook": true,
              "google": false,
              "twitter": true,
              "tumblr": true,
              "pinterest": false,
              "linkedin": false
            }
          });
          self.player.ready(() => {
            resolve(video.id);
          });
        }
      });
    });

    return promise;
  }
}
