/**
 * Theme Default
 */

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.themes = vimeotheque.themes || {};

;(function( exports, $ ){	

    themeDefault = function(){
    	var lists = $('.cvm-vim-playlist.default:not(.loaded)').VimeoPlaylist();
		
		$.each( lists, function(i, list){
			$(this).find('.playlist-visibility').on( 'click', function(e){
				e.preventDefault();
				if( $(this).hasClass('collapse') ){
					$(this).removeClass('collapse').addClass('expand');
					$(list).find('.cvm-playlist').slideUp();
				}else{
					$(this).removeClass('expand').addClass('collapse');
					$(list).find('.cvm-playlist').slideDown();
				}
			})

			if( $(list).is( '.left, .right' ) ){
				var playlist = $(list).find('.cvm-playlist-wrap'),
					videoPlayer = $(list).find( '.vimeotheque-player' ),
					c = $(list).attr('class');

				var f = function(){
					var totalWidth = $(list).outerWidth(),
						playerWidth = $(videoPlayer).outerWidth(),
					    playlistWidth = $(playlist).find('.cvm-playlist').outerWidth();
					    
					if( totalWidth < playerWidth + playlistWidth || playlistWidth < 300 ){
						$(list).removeClass('left right');
					}else{
						var h = $(videoPlayer).outerHeight();
						$(playlist).css({height:h});
						$(list).addClass(c);
					}
				}

				f();	
				$(window).resize(f);				
			}
			
            $(this).addClass('loaded');
		});
    }

    exports.themeDefault = themeDefault;
    
}( vimeotheque.themes, jQuery ));

;(function($){	
	$(document).ready(function(){	
		vimeotheque.themes.themeDefault();
	});	
}( jQuery ));