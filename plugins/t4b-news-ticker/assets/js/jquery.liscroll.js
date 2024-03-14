/*!
 * liScroll 1.1 updated by @davetayls
 * 
 * 2007-2009 Gian Carlo Mingati
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Modified by @realwebcare v1.2.8
 * T4B News Ticker v1.2.9 - 23 November, 2023
 * by @realwebcare - https://www.realwebcare.com/
 */
(function($){
	$.fn.liScroll = function(settings) {
	    
		settings = $.extend({
	        travelocity: 0.05,
	        showControls: false
	    }, settings);

		return this.each(function() {
			var strip = this,
				$strip = $(strip);
	
			$strip.addClass("newsticker")
			$stripItems = $strip.find("li");
			
			var stripWidth = 0,
				// $mask = $strip.wrap("<div class='ticker-mask'></div>"),
				$tickercontainer = $('.tickercontainer'),
				// $tickercontainer = $strip.parent().wrap("<div class='t4bScroll-container'></div>").parent(),
				paused = false,
				containerWidth = $strip.parent().parent().width(); //a.k.a. 'mask' width
	
			var currentItemIndex = function() {
				var index = 0,
					currentLeft = parseInt($strip.css("left")),
					accumulatedWidth = 0;
					
				if (currentLeft > 0) {
					return 0;
				} else {
					$strip.find("li").each(function(i) {
						if (currentLeft == (0 - accumulatedWidth)) {
							index = i;
							return false;
						}
						accumulatedWidth += $(this).width();
						if (currentLeft > (0 - accumulatedWidth)) {
							index = i;
							return false;
						}
						return true;
					});
				}
				return index;
			};

			var next = function() {
	            pause();
	            var index = currentItemIndex();
	            if (index >= $stripItems.length - 1) {
	                $strip.css("left", "0px");
	            } else {
	                var $itm = $stripItems.eq(index + 1);
	                $strip.css("left", (0 - $itm.position().left) + "px");
	            }
	        };
	        var pause = function() {
	            $strip.stop();
	            $tickercontainer
	                .removeClass("t4bScroll-playing")
	                .addClass("t4bScroll-paused");
	            paused = true;
	        };
	        var previous = function() {
	            pause();
	            var index = currentItemIndex(), 
					$itm = null;
					
	            if (index == 0) {
	                $itm = $stripItems.eq($stripItems.length - 1);
	            } else {
	                $itm = $stripItems.eq(index - 1);
	            }
	            $strip.css("left", (0 - $itm.position().left) + "px");
	        };
	        var play = function() {
	            var offset = $strip.offset(),
					residualSpace = offset.left + stripWidth,
					residualTime = residualSpace / settings.travelocity;
					
	            scrollnews(residualSpace, residualTime);
	            $tickercontainer
	                .addClass("t4bScroll-playing")
	                .removeClass("t4bScroll-paused");
	            paused = false;
	        };
	        var togglePlay = function() {
	            if (paused) {
	                play();
	            } else {
	                pause();
	            }
				updatePlayButton();
	        };

			var updatePlayButton = function() {
				if (paused) {
					$pause.text("Play");
				} else {
					$pause.text("Pause");
				}
			};
	
	        if (settings.showControls) {
				var $controlsContainer = $('<div class="t4bScroll-controls"></div>')
					.appendTo($tickercontainer);

				var $prev = $('<div class="t4bScroll-prev">&lt;</div>')
	                .appendTo($controlsContainer)
	                .click(function() {
						previous.call($strip);
					});

				var $pause = $('<div class="t4bScroll-role">Pause</div>')
	                .appendTo($controlsContainer)
	                .click(function() {
						togglePlay.call($strip);
					});

				var $next = $('<div class="t4bScroll-next">&gt;</div>')
	                .appendTo($controlsContainer)
	                .click(function() {
						next.call($strip);
					});

				updatePlayButton(); // Initialize the play button text
	        }
			
			// calculate full width
			$strip.width(10000); // temporary width to prevent inline elements wrapping to initial width of ul
			$stripItems.each(function(i) {
				stripWidth += $(this).outerWidth(true);
			});

			if (settings.showControls) {
				/* const controlWidth = $controlsContainer.outerWidth(true);
				stripWidth -= controlWidth; */
				$('.ticker-news .tickercontainer .ticker-mask').css('width', '92%');
			}

			$strip.width(stripWidth * $stripItems.length);

			/*thanks to Scott Waye*/
			var totalTravel = stripWidth + containerWidth,
				defTiming = totalTravel / settings.travelocity;
	
			function scrollnews(spazio, tempo) {
				$strip.animate(
					{ left: '-=' + spazio }, 
					tempo, 
					"linear", 
					function() { 
						$strip.css("left", containerWidth); 
						scrollnews(totalTravel, defTiming); 
					}
				);
			}
			scrollnews(totalTravel, defTiming);
	        $tickercontainer.addClass("t4bScroll-playing");
			/* On Mouse Over */
			$strip.hover(
				function() {
					$(this).stop();
				},
				function() {
					if (!paused) {
						var offset = $(this).offset();
						var residualSpace = offset.left + stripWidth;
						var residualTime = residualSpace/settings.travelocity;
						scrollnews(residualSpace, residualTime);
					}
				}
			);

			$stripItems.hover(
				function() {
					if (paused) {
						var offset = $(this).offset();
						var residualSpace = offset.left + stripWidth;
						var residualTime = residualSpace / settings.travelocity;
						scrollnews(residualSpace, residualTime);
					}
				},
				function() {
					if (paused) {
						pause();
					}
				}
			);
		});
	};
})(jQuery);