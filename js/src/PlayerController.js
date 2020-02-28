/**
 *  Media Object Player Control - default player is html5 media element
 *
 * @module explo_video
 *
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/Apps/Fundamentals/Audio_and_video_delivery
 * @see https://developer.mozilla.org/en-US/docs/Web/Guide/Events/Media_events
 *
 *
 */

class PlayerController {

  constructor (elId, mediaId) {
    var self = this;
    this.elId = elId;
    this.mediaId = mediaId;
    this.getPlayer().then( (player) => {
      this.player = player;
      var ready = document.createEvent('Event');
      ready.initEvent('playerReady', true, true);
      document.getElementById(elId).dispatchEvent(ready);
    });
  }

  getPlayer () {
    var self = this;
    var promise = new Promise( (resolve, reject) => {
      var player = document.getElementById(self.elId).querySelector('.html5-player');
      resolve(player);
    });

    return promise;
  }

  play() {
    if (typeof this.player.play == 'function') this.player.play();
  }

  pause() {
    if (typeof this.player.pause == 'function') this.player.pause();
  }

  update(media) {
    var self = this;

    if (this.mediaId !== media.mediaId) {
      this.updateSource(media).then((mediaId) => {
        self.mediaId = mediaId;
        self.play();
      },
      (err) => { throw(err); });
    } else {
      this.play();
    }
  }

  updateSource(media) {
    var self = this;

    var promise = new Promise( (resolve, reject) => {
      //  update highlight image
      document.getElementById(self.elId).querySelector('.highlight_img img:not(.icon)').setAttribute('src', media.thumb);
      //  update container class
      var format_classname = /media-format-\w+/g;
      document.getElementById(self.elId).className.replace(format_classname, 'media-format-'+media.format);
      //  remove previous media element
      self.player.remove();
      //  add new media element
      var el = document.createElement(media.format);
      el.setAttribute('class', 'html5-player');
      el.setAttribute('preload', 'auto');
      el.setAttribute('poster', media.thumb);
      el.setAttribute('autoplay', 'true');
      el.setAttribute('controls', '');
      Object.keys(media.mediaSources).forEach((key) => {
        var source_el = document.createElement('source');
        source_el.setAttribute('type', key);
        source_el.setAttribute('src', media.mediaSources[key]);
        el.appendChild(source_el);
      });
      document.getElementById(self.elId).appendChild(el);
      //  update player
      self.getPlayer().then( (player) => {
        self.player = player;
        resolve(media.mediaId);
      });

    });

    return promise;
  }
}
