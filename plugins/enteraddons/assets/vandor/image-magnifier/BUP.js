(function ($) {
// jQuery BUP
$.fn.BUP = function (brother, beishu) {
  console.log(this); // this $()jQuery
  var $element = this;
  var $className = $element.attr("class");
  var $class1, $brother;
  // If the target element is not an image
  if (!$element.is("img")) {
    console.log(
      "%c Blowup.js Error: " + "%cTarget element is not an image.",
      "background: #FCEBB6; color: #F07818; font-size: 17px; font-weight: bold;",
      "background: #FCEBB6; color: #F07818; font-size: 17px;"
    );
    return;
  }

  // Constants
  var $IMAGE_URL = $element.attr("src");
  var $brotherSrc = $("." + brother).attr("src");
  var $IMAGE_WIDTH = $element.width();
  var $IMAGE_HEIGHT = $element.height();
  var NATIVE_IMG = new Image();
  NATIVE_IMG.src = $element.attr("src");
  //NATIVE_IMG.width = "900";
  //NATIVE_IMG.height = "900";

  // Default attributes
  var defaults = {
    round: true,
    width: 200,
    height: 200,
    background: "#FFF",
    shadow: "0 8px 17px 0 rgba(0, 0, 0, 1)",
    border: "6px solid #FFF",
    cursor: true,
    zIndex: 999999,
  };

  // Update defaults with custom attributes
  var $options = defaults;

  // Modify target image
  $element.on("dragstart", function (e) {
    e.preventDefault();
  });
  //
  $element.css("cursor", $options.cursor ? "crosshair" : "none");

  // Create magnification lens element
  var lens = document.createElement("div");
  lens.id = "" + $className + "BlowupLens";

  // Attack the element to the body
  $("body").append(lens);

  // Updates styles
  $blowupLens = $("#" + lens.id);

  $blowupLens.css({
    position: "absolute",
    visibility: "hidden",
    "pointer-events": "none",
    zIndex: $options.zIndex,
    width: $options.width,
    height: $options.height,
    border: $options.border,
    background: $options.background,
    "border-radius": $options.round ? "50%" : "none",
    "box-shadow": $options.shadow,
    "background-repeat": "no-repeat",
  });

  // Show magnification lens
  $element.mouseenter(function () {
    $class1 = $element.attr("class");
    $blowupLens = $("#" + $class1 + "BlowupLens");
    $blowupLens.css("visibility", "visible");
    $brother = $("#" + brother + "BlowupLens");
    $brother.css("visibility", "visible");
  });

  // Mouse motion on image
  $element.mousemove(function (e) {
    /*$blowupLens = $("#"+$class1+"BlowupLens");
    	$brother = $("#"+brother+"BlowupLens");*/
    // Lens position coordinates
    var lensX = e.pageX - $options.width / 2;
    var lensY = e.pageY - $options.height / 2;
    // Relative coordinates of image
    // relX, relY
    var relX = e.offsetX;
    var relY = e.offsetY; //
    // console.log(relX);
    // console.log(relY);
    // Zoomed image coordinates
    var zoomX = -Math.floor(
      (relX / $element.width()) * NATIVE_IMG.width - $options.width / 2
    );

    var zoomY = -Math.floor(
      (relY / $element.height()) * NATIVE_IMG.height - $options.height / 2
    );
    //console.log(relX+"/"+$element.width()+"*"+ NATIVE_IMG.width );
    //console.log(zoomX);
    //console.log(zoomY);
    var bgSize;
    switch (beishu) {
      case 0.5:
        zoomX = zoomX + zoomX * beishu;
        zoomY = zoomY + zoomY * beishu;
        bgSize = 400 * 2.5;
        break;
      case 1.5:
        zoomX = zoomX + zoomX * beishu;
        zoomY = zoomY + zoomY * beishu;
        bgSize = 400 * 3.5;
        break;
      default:
        bgSize = NATIVE_IMG.width;
    }

    document.onmousewheel = function (e) {
      e = event || window.event;
      if (e.wheelDelta === 120) {
        zoomX = zoomX + zoomX * 0.5;
        zoomY = zoomY + zoomY * 0.5;
        bgSize = 400 * 2.5;
        console.log("Q");
      }
      if (e.wheelDelta === -120) {
        console.log("Hou");
      }
    };

    // Apply styles to lens
    $blowupLens.css({
      left: lensX,
      top: lensY,
      "background-image": "url(" + $IMAGE_URL + ")",
      "background-position-x": zoomX,
      "background-position-y": zoomY,
      "background-size": bgSize,
    });
  });

  $element.mouseleave(function () {
    document.onmousewheel = null;
    /*$blowupLens = $("#"+$class1+"BlowupLens");*/
    $blowupLens.css("visibility", "hidden");
    /*$brother = $("#"+brother+"BlowupLens");*/
    //$brother.css("visibility", "hidden");
  });
};

})(jQuery)
