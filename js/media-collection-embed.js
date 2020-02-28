window.onmessage = (e) => {
  //  maintain height
  if (e.data.hasOwnProperty("frameHeight") && e.data.hasOwnProperty("id")) {
    document.getElementById(e.data.id).style.height = `${e.data.frameHeight}px`;
  }
  //  pass on query vars
  if (e.data.hasOwnProperty("query") && e.data.hasOwnProperty("id")) {

  }
};