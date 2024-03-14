window.onload = function() {
  if (!isZbPlayerFlashEnabled()) {
    // replace current player code with standard audio
    var elements = document.getElementsByClassName("zbPlayerNative");
    var index;
    for (index = 0; index < elements.length; ++index) {
        elements[index].style.display = "inline";
    }
    // hide current flash player
    var flash = document.getElementsByClassName('zbPlayerFlash');
    for (index = 0; index < flash.length; ++index) {
        flash[index].style.display = "none";
    }
  }
};

function isZbPlayerFlashEnabled() {
  var hasFlash = false;
  try {
    var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
    if(fo) hasFlash = true;
  } catch(e) {
    if(navigator.mimeTypes ["application/x-shockwave-flash"] != undefined) hasFlash = true;
  }
  return hasFlash;
}