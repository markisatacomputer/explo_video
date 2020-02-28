// Collapsible Annotations Toggle
function togleOpenCloseAnnotation(element,annID) {
  var caption = element.innerHTML;
  if (caption === "read more") {
    document.getElementById(annID).style.display = "block";
    element.innerHTML = "collapse";
  } else {
    document.getElementById(annID).style.display = "none";
    element.innerHTML = "read more";
  }
}

// On Cuepoint Event
function cueToHTML(cue) {
  // var elemnts = document.querySelectorAll('#annotation-container .annotation');
  // for (var i = 0; i < elemnts.length; i++) {
  //  if(elemnts[i].getAttribute('id') === cue) elemnts[i].classList.add("annotation-hot");
  //  else elemnts[i].classList.remove("annotation-hot");
  // }
}

// Click the Annotation
function HTMLtoCue(time,cue) {
  var hms = time;
  var a = hms.split(':');
  var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
  currentSeekTime = seconds;
  currentCue = cue;

  // if the video is not playing, start it and function calls itself again
  if(bc5Player !== undefined) {
    bc5Player.currentTime(seconds);
    bc5Player.play();
  //  cueToHTML(currentCue);
  }
}

function playpointChanged(pos) {
  // var time = bc5Player.currentTime();
  // var index = 0;
  // var end = Object.keys(points).length;
  // for(index = 0; index < end; index++) {
  //  if(points[index].time < time) {
  //    if((index+1) === end) {
  //      cueToHTML(points[index].metadata);
  //      return false;
  //    }
  //  } else if(points[index].time > time) {
  //    cueToHTML(points[index - 1].metadata);
  //    return false;
  //  }
  // }
}
