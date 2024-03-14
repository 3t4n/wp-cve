jQuery(document).ready(function($){

    jQuery.fn.slideFadeToggle = function(speed, easing, callback) {
      return this.animate({opacity: 'toggle', height: 'toggle'}, speed, easing, callback);
    };

    $('.show_captions').click( function() {
        $(this).parents('.flexslider').find(".flex-caption").each(function (i) {
        if (this.style.display == "none") {
          $(this).show().animate({opacity:1}, 500);
        } else {
          $(this).animate({opacity:0}, 500, function(){$(this).hide();});
        }
      });

        $(this).text($(this).text() == 'Caption' ? 'Hide caption' : 'Caption');
        return false;
    });
    $('.show_thumbnails').click( function() {
        $(this).parents('.flexslider').find('ul.gpp_slideshow_thumbnails').slideFadeToggle();
        $(this).text($(this).text() == 'Hide thumbnails' ? 'Show thumbnails' : 'Hide thumbnails');
        return false;
    });

});

// Disables right click on images
// $(function() {
    // $('img').bind("contextmenu", function(e) {
       // e.preventDefault();
    // });
// });