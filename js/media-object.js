/**
 *  Media Object Thumb Control Behavior
 *
 * @module explo_video
 */

(function ($) {

  Drupal.behaviors.mediaObjectBehavior = {
    attach: function (context, config) {
      var settings = config['explo_video'];

      //  Store Media
      Object.keys(settings).forEach( function (key) {
        if (key.indexOf('media-') === 0) {
          var media = settings[key];
          if (typeof(window.mediaObjects) === 'undefined') window.mediaObjects = {};
          if (typeof(window.mediaObjects[media.id]) === 'undefined') window.mediaObjects[media.id] = media;
        }
      });

    }
  };

})(jQuery);
