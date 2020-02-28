/**
 *  Media Object FLV Player Controller
 *
 * @module explo_video
 *
 * @see http://blog.deconcept.com/swfobject/
 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/embed
 *
 * @note  Because we can't control playback, we will reload flv on pause(), play() will simply be ignored.
 */

class PlayerControllerFlv extends PlayerController {

  getPlayer () {
    var self = this;

    var promise = new Promise( (resolve, reject) => {
      var swf, el, dirname, filename, thumb;
      el = document.getElementById(self.elId);
      dirname = el.getAttribute('data-dirname');
      filename = el.getAttribute('data-filename');
      thumb = el.getAttribute('data-thumb');
      swf = new SWFObject("/sites/all/libraries/tv/flvplayer.swf",filename,"100%","100%","7","#000");
      swf.addParam("allowfullscreen","true");
      swf.addVariable("file",dirname);
      swf.addVariable("image",thumb);
      swf.addVariable("id",filename);
      swf.addVariable("overstretch","fit");
      swf.write(self.elId);
      resolve(swf);
    });

    return promise;
  }

  pause() {
    this.getPlayer().then((player) => {
      self.player = player;
    });
  }

  updateSource(media) {
    var self = this;

    var el = document.getElementById(self.elId);
    el.setAttribute('data-dirname', media.dirname);
    el.setAttribute('data-filename', media.filename);
    el.setAttribute('data-thumb', media.thumb);
    this.mediaId = media.mediaId;

    var promise = new Promise( (resolve, reject) => {
      self.getPlayer().then(() => {
        resolve(media.mediaId);
      });
    });

    return promise;
  }
}
