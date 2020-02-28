videojs.plugin("liveError", function(options) {
  var bcPlayer, timeout;

  bcPlayer = this;

  //  brightcove player config
  bcPlayer.errors({
    'errors': {
      '4': {
        'headline': 'This live event is not currently available.  <p id="replace_me"></p>',
        'type': 'MEDIA_UNAVAILABLE',
      }
    }
  });

  bcPlayer.on("error", function(err) {
    var errNo,
    duration,
    bcmap,
    starttime,
    starttimeUTC,
    starttimePretty,
    now,
    delay;

    //  program start times
    bcmap = function(id) {
      switch(id) {
        case '5524093293001':
          return '2017-08-21T17:00:00+00:00';
        case '5524093298001':
          return '2017-08-21T17:00:00+00:00';
        case '5524081324001':
          return '2017-08-21T16:00:00+00:00';
        case '5524080240001':
          return '2017-08-21T16:15:00+00:00';
        case '5524073077001':
          return '2017-08-21T16:15:00+00:00';
        default:
          return 'nope';//return '2017-08-21T17:00:00+00:00';
      }
    }

    //  If Error Code 4 and no duration
    errNo = bcPlayer.error().code;
    duration = bcPlayer.duration();
    if (errNo == '4' && isNaN(duration)) {
      //  Get our time values
      starttime = bcmap(bcPlayer.mediainfo.id);
      starttimeUTC = moment(starttime).valueOf();
      starttimePretty = moment(starttime).local().format('dddd MMMM D, YYYY, h:mm a') + ' ' + moment.tz(moment.tz.guess()).zoneAbbr();
      now = moment().valueOf();
      delay = starttimeUTC - now;

      //  Check to make sure the program starts in the future
      if (delay > 0) {
        delay = delay - 60000;
        if (delay < 0) { delay = 5000; }
        // update error dialog message and reload - 60sec before program start
        timeout = window.setTimeout(function(){
          document.getElementById('replace_me').innerHTML = "Attempting to reload automatically now...";
          window.location.reload(true);
        }, delay);
      }

      //  Add more message
      document.getElementById('replace_me').innerHTML = "It will begin on " + starttimePretty + ".  Please reload the page then.";

      // show overlay image
      bcPlayer.addClass("you-are-early");
    } else {
      // hide overlay image
      bcPlayer.removeClass("you-are-early");
    }
  });
  bcPlayer.on("loadstart", function(err) {
    bcPlayer.removeClass("you-are-early");
    if (timeout) {
      window.clearTimeout(timeout);
    }
  });
});
