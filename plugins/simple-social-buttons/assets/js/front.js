(function ($) {

    /**
     * Copyright 2012, Digital Fusion
     * Licensed under the MIT license.
     * http://teamdf.com/jquery-plugins/license/
     *
     * @author Sam Sehnert
     * @desc A small plugin that checks whether elements are within
     *     the user visible viewport of a web browser.
     *     only accounts for vertical position, not horizontal.
     */

    $.fn.visible = function (partial) {

        var $t = $(this),
            $w = $(window),
            viewTop = $w.scrollTop(),
            viewBottom = viewTop + $w.height(),
            _top = $t.offset().top,
            _bottom = _top + $t.height(),
            compareTop = partial === true ? _bottom : _top,
            compareBottom = partial === true ? _top : _bottom;

        return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

    };

})(jQuery);
// IIFE - Immediately Invoked Function Expression

var ssbPlugin = ssbPlugin || {};


(function ($, window, document) {
  'use strict';


  function absint($int) {
    return parseInt($int, 10);
  }


  var ssbPostData = {};
  ssbPlugin.fetchFacebookShares = function() {
    /**
    * Run all the API calls
    */

    $.when(
      $.get('https://graph.facebook.com/?fields=og_object{likes.summary(true).limit(0)},share&id=' + ssb_post_url),
      $.get('https://graph.facebook.com/?fields=og_object{likes.summary(true).limit(0)},share&id=' + ssb_alternate_post_url )
    )
    .then(function(a, b) {
      if('undefined' !== typeof a[0].share) {
        var f1 = absint(a[0].share.share_count);
        var f2 = absint(a[0].share.comment_count);
        if('undefined' !== typeof a[0].og_object){
          var f3 = absint(a[0].og_object.likes.summary.total_count);
        } else {
          var f3 = 0;
        }
        var fShares = f1 + f2 + f3;
        if(ssb_alternate_post_url) {
          if (typeof b[0].share !== 'undefined') {
            var f4 = absint(b[0].share.share_count);
            var f5 = absint(b[0].share.comment_count);
          } else {
            var f4 = 0, f5 = 0;
          }
          if (typeof b[0].og_object !== 'undefined') {
            var f6 = absint(b[0].og_object.likes.summary.total_count);
          } else {
            var f6 = 0
          }
          var fShares2 = f4 + f5 + f6;
          if (fShares !== fShares2) {
            fShares = fShares + fShares2;
          }
        }

          ssbPostData = {
              action: 'ssb_facebook_shares_update',
							security: SSB.fb_share_nonce,
              post_id: ssb_post_id,
              share_counts: fShares
          };

          $.post( SSB.ajax_url, ssbPostData );
      }
    });
  }


    // Listen for the jQuery ready event on the document
    $(function () {

        // The DOM is ready!
        if ($('div[class*="simplesocialbuttons-float"]').length > 0) {
            $('body').addClass('body_has_simplesocialbuttons');
        }

    });

    $(window).load(function () {
        var allMods = $(".simplesocialbuttons_inline");

        // Already visible modules
        allMods.each(function (i, el) {
            var el = $(el);
            if (el.visible(true)) {
                el.addClass('simplesocialbuttons-inline-in');
            }
        });

        $(window).scroll(function (event) {

            allMods.each(function (i, el) {
                var el = $(el);
                if (el.visible(true)) {
                    el.addClass('simplesocialbuttons-inline-in');
                }
            });

        });
        // $('.simplesocialbuttons_inline').addClass('simplesocialbuttons-inline-in');
        var sidebarwidth = $('div[class*="simplesocialbuttons-float"]>a:first-child').outerWidth(true);
        $('div[class*="simplesocialbuttons-float"]').css('width', sidebarwidth + 'px');
        $('.simplesocialbuttons.ssb_counter-activate:not(.simplesocial-round-txt):not(.simplesocial-round-icon):not(.simplesocial-simple-icons) button:not(.simplesocial-viber-share):not(.simplesocial-whatsapp-share):not(.simplesocial-msng-share):not(.simplesocial-email-share):not(.simplesocial-print-share):not(.simplesocial-linkedin-share)').each(function(){
			var el = $(this);
			setTimeout(function(){
				var $elWidth = el.children('.ssb_counter').innerWidth();
				el.css('padding-right', $elWidth + 10);
			}, 100);
        });
        // var sidebaroffset = $(window).width() -  1100;
        // if($('.simplesocialbuttons-float-left-post').length>0){
        // 	$('.simplesocialbuttons-float-left-post').css('left', sidebaroffset/2+'px');
        //  	$('.simplesocialbuttons-float-left-post').removeClass('float-touched-sidebar');
        // }
        // if($('.simplesocialbuttons-float-right-post').length>0){
        // 	$('.simplesocialbuttons-float-right-post').css('right', sidebaroffset/2+'px');
        //  	$('.simplesocialbuttons-float-left-post').removeClass('float-touched-sidebar');
        //  	$('.simplesocialbuttons-float-right-post').removeClass('float-touched-sidebar');
        // }
        // if(sidebaroffset/2 <= 50){
        //  	$('.simplesocialbuttons-float-left-post').addClass('float-touched-sidebar');
        //  	$('.simplesocialbuttons-float-right-post').addClass('float-touched-sidebar');
        // }
    });
    // $(window).on('resize',function(){
    // 	var sidebaroffset = $(window).width() -  1100;
    // 	if($('.simplesocialbuttons-float-left-post').length>0){
    // 		$('.simplesocialbuttons-float-left-post').css('left', sidebaroffset/2+'px');
    // 	 	$('.simplesocialbuttons-float-left-post').removeClass('float-touched-sidebar');
    // 	}
    // 	if($('.simplesocialbuttons-float-right-post').length>0){
    // 		$('.simplesocialbuttons-float-right-post').css('right', sidebaroffset/2+'px');
    // 	 	$('.simplesocialbuttons-float-left-post').removeClass('float-touched-sidebar');
    // 	}
    // 	if(sidebaroffset/2 <= 50){
    // 	 	$('.simplesocialbuttons-float-left-post').addClass('float-touched-sidebar');
    // 	}
    // });


    // $(window).on('scroll', function(){
    // 	if($('.simplesocialbuttons-float-left-post').length>0){
    // 		if($(window).scrollTop() >= 500){
    // 			$('.simplesocialbuttons-float-left-post').addClass('float-in')
    // 		}else{
    // 			$('.simplesocialbuttons-float-left-post').removeClass('float-in')
    // 		}
    // 	}
    // 	if($('.simplesocialbuttons-float-right-post').length>0){
    // 		if($(window).scrollTop() >= 500){
    // 			$('.simplesocialbuttons-float-right-post').addClass('float-in')
    // 		}else{
    // 			$('.simplesocialbuttons-float-right-post').removeClass('float-in')
    // 		}
    // 	}
    // })

}(window.jQuery, window, document));

function docLoadedFun(){
  var hideSidebarButton = '<span class="ssb-hide-floating-bar"> <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 370.814 370.814"><path d="M292.92 24.848L268.781 0 77.895 185.401l190.886 185.413 24.139-24.853-165.282-160.56"></path></svg> </span>';
  if(document.querySelector('div[class*="simplesocialbuttons-float"]')){
  document.querySelector('div[class*="simplesocialbuttons-float"]').insertAdjacentHTML( 'beforeend', hideSidebarButton )
  document.querySelector('.ssb-hide-floating-bar').addEventListener( 'click' , toggleSidebarButtons)
}
}
document.addEventListener( 'DOMContentLoaded', docLoadedFun );

function toggleSidebarButtons(){
  var leftSidebar = document.querySelector('div[class*="simplesocialbuttons-float"]');
  leftSidebar.classList.toggle('ssb-hide-float-buttons')
}