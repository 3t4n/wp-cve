/*
 * Velocity
 * http://wordpress.org/plugins/velocity/
 * https://connekthq.com/plugins/velocity/
 *
 * Copyright 2017 Connekt Media - https://connekthq.com
 * Free to use under the GPLv2 license.
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Author: Darren Cooney
 * Twitter: @KaptonKaos
 */
 
var velocity = velocity || {};

(function($){   
   
   
   /*
   *  velocity.playMedia
   *  Play media function
   *
   *  @since 1.0
   */ 
   
   velocity.playMedia = function(el, type, id, options, event, parent, target){ 
           
      // Youtube
      var src = "//youtube.com/embed/" + id + "?rel=0&amp;vq=hd1080";	   
	   
	   // Vimeo
	   if(type === 'vimeo'){
   	   src = "//player.vimeo.com/video/" + id;   
	   }		 
	   
	   // Twitch
	   if(type === 'twitch'){
		   src = "//player.twitch.tv/?channel=" + id;
	   }  
	   
	   // Soundcloud
	   if(type === 'soundcloud'){
		   var soundcloud_type = 'tracks';
		   
		   if(el.data('soundcloud-type')){
			   soundcloud_type = el.data('soundcloud-type');
		   }
		   
	      src = "//w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/"+ soundcloud_type +"/" + id +"&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false&amp;visual=true";
	   }
	   
	   
	   var player_options = (options) ? '&amp;'+options : '';
	    	
	    	
	   el.animate({'opacity':0}, 250, function(){		   
		   
		   // Append iframe
		   $('<iframe />', {
		    	id: id,
			 	src: src,
			 	allowfullscreen: "1",
			 	frameborder: "0",
			 	width: "1600"	    
			}).appendTo(target); 
			
			
			el.remove(); // Remove Preview
					
		
			// Autostart Media Embeds
			// Wrap is setTimeout to dslightly delay iframe init.
			setTimeout(function() {	
   				
				var velocity_iframe = document.getElementById(id);
				
	   		if(type === 'vimeo'){
	      		velocity_iframe.src += "?autoplay=1&title=0&byline=0" + player_options;
	   		}
	   		else if(type === 'soundcloud'){
				   velocity_iframe.src += "&auto_play=true" + player_options;       		
	   		}
	   		else{
				   velocity_iframe.src += "&autoplay=1" + player_options;      		
	   		}
				target.fitVidsInline();
				
				if(event){ // Send to google analytics

   				var events = event.split(':'); // split event shortcode
   				
   				if(events.length > 2){ // confirm array contains Category, Action and Label
      				
      				var gaCategory = events[0],
      				    gaAction = events[1],
      				    gaLabel = events[2];
                      
                  //console.log(gaCategory, gaAction, gaLabel);
                      
      				// Monster Insights
                  if (typeof __gaTracker !== 'undefined' && $.isFunction(__gaTracker)) {
                     __gaTracker('send', 'event', gaCategory, gaAction, gaLabel);
                  }
      				
      				// Standard Google Analytics
   	   			if (typeof ga !== 'undefined' && $.isFunction(ga)) { // Check that ga func exists
                     ga('send', 'event', gaCategory, gaAction, gaLabel);
   					}
					
					}
				}
				
				// Reset height on parent container
				parent.css('height', 'auto');
				
			}, 1);
			
	   });	   
   };   
   
   // Image click
   $(document).on('click', '.velocity-embed a', function(e){
	   e.preventDefault();
	   var el = $(this),
	   	 type = el.data('video-type').toLowerCase(),
	   	 id = el.data('video-id'),
	   	 options = el.data('video-options'),
	   	 event = el.data('event'),
	   	 parent = el.parent('.velocity-embed'),
	   	 target = $('.velocity-target', parent);
	   	 
	   parent.css('height', parent.height());
	   velocity.playMedia(el, type, id, options, event, parent, target);		
   });
   
   
   
   /*!
   * FitVids 1.1
   *
   * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
   * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
   * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
   *
   */
   
   $.fn.fitVidsInline = function( options ) {
    var settings = {
      customSelector: null,
      ignore: null
    };

    if(!document.getElementById('fit-vids-style')) {
      // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
      var head = document.head || document.getElementsByTagName('head')[0];
      var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
      var div = document.createElement("div");
      div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
      head.appendChild(div.childNodes[1]);
    }

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        'iframe[src*="player.vimeo.com"]',
        'iframe[src*="youtube.com"]',
        'iframe[src*="player.twitch.tv"]',
        'iframe[src*="youtube-nocookie.com"]',
        'iframe[src*="w.soundcloud.com"]',
        'iframe[src*="kickstarter.com"][src*="video.html"]',
        'object',
        'embed'
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var ignoreList = '.fitvidsignore';

      if(settings.ignore) {
        ignoreList = ignoreList + ', ' + settings.ignore;
      }

      var $allVideos = $(this).find(selectors.join(','));
      $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
      $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

      $allVideos.each(function(count){
        var $this = $(this);
        if($this.parents(ignoreList).length > 0) {
          return; // Disable FitVids on this video.
        }
        if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
        {
          $this.attr('height', 9);
          $this.attr('width', 16);
        }
        var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
            width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
            aspectRatio = height / width;
        if(!$this.attr('id')){
          var videoID = 'fitvid' + count;
          $this.attr('id', videoID);
        }
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
        $this.removeAttr('height').removeAttr('width');
      });
    });
  };
   
   
   
		
})(jQuery);