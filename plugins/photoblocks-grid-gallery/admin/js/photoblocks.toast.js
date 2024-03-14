var PBToast = null;

(function( $ ) {
  "use strict";

  function make(color, text) {
    var $toast = $("<div class='pb-toast pb-toast-" + color + "'></div>");
    $toast.html(text);    
    $toast.appendTo("body");
    $toast.fadeTo(250, 1);

    setTimeout(function () {
      $toast.fadeOut(function () {
        $toast.remove();
      });      
    }, 3000);
  }

  PBToast = function () {
    return {
      confirm: function (text) {
        make('green', text);
      },
      error: function (text) {
        make('red', text);
      }
    }
  }();

})(jQuery);
