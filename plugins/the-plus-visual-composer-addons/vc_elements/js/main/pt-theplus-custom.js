/*--------- animated svg js -----------*/
( function ( $ ) {	
	'use strict';
	$.fn.pt_plus_animated_svg = function() {
		return this.each(function() {
			var $self = $(this);
			var data_id=$self.data("id");
			var data_duration=$self.data("duration");
			var data_type=$self.data("type");
			var data_stroke=$self.data("stroke");
			var data_fill_color=$self.data("fill_color");
			new Vivus(data_id, {type: data_type, duration: data_duration,forceRender:true,start: 'inViewport',onReady: function (myVivus) {
					var c=myVivus.el.childNodes;
					if(data_stroke!=''){
						for (var i = 0; i < c.length; i++) {
							$(c[i]).attr("fill", data_fill_color);
							$(c[i]).attr("stroke",data_stroke);
							var child=c[i];
							var pchildern=child.children;
							if(pchildern != undefined){
							for(var j=0; j < pchildern.length; j++){
								$(pchildern[j]).attr("fill", data_fill_color);
								$(pchildern[j]).attr("stroke",data_stroke);
							}
							}
						}
					}
			} });
		});
	};
$(window).load(function() {
	setTimeout(function(){
		$('.pt_plus_animated_svg').pt_plus_animated_svg();
}, 100);
});
} ( jQuery ) );
/*--------- animated svg js -----------*/
/*-theservice coutdown-----------*/
/*count down js*/
( function ( $ ) { 
	'use strict';
$(document).ready(function () {
	$('.pt_plus_countdown').each(function () {
		var timer1 = $(this).attr("data-timer");
		var res = timer1.split("-");
		$(this).downCount({
			date: res[1]+"/"+res[0]+"/"+res[2]+" 12:00:00",
			offset: +1
		});
	});
});	
} ( jQuery ) );
/*-theservice coutdown-----------*/


/*---pie Chart*/
( function ( $ ) { 
		'use strict';
		$( document ).ready(function() {
			var elements = document.querySelectorAll('.pt-plus-piechart');
			Array.prototype.slice.apply(elements).forEach(function(el) {
				var $el = jQuery(el);
				//$el.circleProgress({value: 0});
				new Waypoint({
					element: el,
					handler: function() {
						if(!$el.hasClass("done-progress")){
						setTimeout(function(){
							$el.circleProgress({
								value: $el.data('value'),
								emptyFill: $el.data("emptyfill"),
								startAngle: -Math.PI/4*2,
							});
							//  this.destroy();
						}, 800);
						$el.addClass("done-progress");
						}
					},
					offset: '80%'
				});
			});
		});
		$(window).on("load resize scroll", function(){
			$(".pt-plus-peicharts").each( function(){
				var height=$('canvas',this).outerHeight();
				var width=$('canvas',this).outerWidth();
				$(".pt-plus-circle",this).css("height",height+'px');
				$(".pt-plus-circle",this).css("width",width+'px');
			});
		});
	} ( jQuery ) );	
/*---pie Chart*/
/*------ heading animation--------*/
jQuery(document).ready(function($){
	"use strict";
	//set animation timing
	var animationDelay = 2500,
	//loading bar effect
	barAnimationDelay = 3800,
	barWaiting = barAnimationDelay - 3000, //3000 is the duration of the transition on the loading bar - set in the scss/css file
	//letters effect
	lettersDelay = 50,
	//type effect
	typeLettersDelay = 150,
	selectionDuration = 500,
	typeAnimationDelay = selectionDuration + 800,
	//clip effect 
	revealDuration = 600,
	revealAnimationDelay = 1500;
	
	pt_plus_initHeadline();
	
	
	function pt_plus_initHeadline() {
		//insert <i> element for each letter of a changing word
		singleLetters($('.pt-plus-cd-headline.letters').find('b'));
		//initialise headline animation
		animateHeadline($('.pt-plus-cd-headline'));
	}
	
	function singleLetters($words) {
		$words.each(function(){
		var i;
			var word = $(this),
			letters = word.text().split(''),
			selected = word.hasClass('is-visible');
			for (i in letters) {
				if(word.parents('.rotate-2').length > 0) letters[i] = '<em>' + letters[i] + '</em>';
				letters[i] = (selected) ? '<i class="in">' + letters[i] + '</i>': '<i>' + letters[i] + '</i>';
			}
		    var newLetters = letters.join('');
		    word.html(newLetters).css('opacity', 1);
		});
	}
	
	function animateHeadline($headlines) {
		var duration = animationDelay;
		$headlines.each(function(){
			var headline = $(this);
			
			if(headline.hasClass('loading-bar')) {
				duration = barAnimationDelay;
				setTimeout(function(){ headline.find('.cd-words-wrapper').addClass('is-loading') }, barWaiting);
				} else if (headline.hasClass('clip')){
				var spanWrapper = headline.find('.cd-words-wrapper'),
				newWidth = spanWrapper.width() + 10
				spanWrapper.css('width', newWidth);
				} else if (!headline.hasClass('type') ) {
				//assign to .cd-words-wrapper the width of its longest word
				var words = headline.find('.cd-words-wrapper b'),
				width = 0;
				words.each(function(){
					var wordWidth = $(this).width();
				    if (wordWidth > width) width = wordWidth;
				});
				headline.find('.cd-words-wrapper').css('width', width);
			};
			
			//trigger animation
			setTimeout(function(){ hideWord( headline.find('.is-visible').eq(0) ) }, duration);
		});
	}
	
	function hideWord($word) {
		var nextWord = takeNext($word);
		
		if($word.parents('.pt-plus-cd-headline').hasClass('type')) {
			var parentSpan = $word.parent('.cd-words-wrapper');
			parentSpan.addClass('selected').removeClass('waiting');	
			setTimeout(function(){ 
				parentSpan.removeClass('selected'); 
				$word.removeClass('is-visible').addClass('is-hidden').children('i').removeClass('in').addClass('out');
			}, selectionDuration);
			setTimeout(function(){ showWord(nextWord, typeLettersDelay) }, typeAnimationDelay);
			
			} else if($word.parents('.pt-plus-cd-headline').hasClass('letters')) {
			var bool = ($word.children('i').length >= nextWord.children('i').length) ? true : false;
			hideLetter($word.find('i').eq(0), $word, bool, lettersDelay);
			showLetter(nextWord.find('i').eq(0), nextWord, bool, lettersDelay);
			
			} else if ($word.parents('.pt-plus-cd-headline').hasClass('loading-bar')){
			$word.parents('.cd-words-wrapper').removeClass('is-loading');
			switchWord($word, nextWord);
			setTimeout(function(){ hideWord(nextWord) }, barAnimationDelay);
			setTimeout(function(){ $word.parents('.cd-words-wrapper').addClass('is-loading') }, barWaiting);
			
			} else {
			switchWord($word, nextWord);
			setTimeout(function(){ hideWord(nextWord) }, animationDelay);
		}
	}
	
	function showWord($word, $duration) {
		if($word.parents('.pt-plus-cd-headline').hasClass('type')) {
			showLetter($word.find('i').eq(0), $word, false, $duration);
			$word.addClass('is-visible').removeClass('is-hidden');
			
		} 
	}
	
	function hideLetter($letter, $word, $bool, $duration) {
		$letter.removeClass('in').addClass('out');
		
		if(!$letter.is(':last-child')) {
		 	setTimeout(function(){ hideLetter($letter.next(), $word, $bool, $duration); }, $duration);  
			} else if($bool) { 
		 	setTimeout(function(){ hideWord(takeNext($word)) }, animationDelay);
		}
		
		if($letter.is(':last-child') && $('html').hasClass('no-csstransitions')) {
			var nextWord = takeNext($word);
			switchWord($word, nextWord);
		} 
	}
	
	function showLetter($letter, $word, $bool, $duration) {
		$letter.addClass('in').removeClass('out');
		
		if(!$letter.is(':last-child')) { 
			setTimeout(function(){ showLetter($letter.next(), $word, $bool, $duration); }, $duration); 
			} else { 
			if($word.parents('.pt-plus-cd-headline').hasClass('type')) { setTimeout(function(){ $word.parents('.cd-words-wrapper').addClass('waiting'); }, 200);}
			if(!$bool) { setTimeout(function(){ hideWord($word) }, animationDelay) }
		}
	}
	
	function takeNext($word) {
		return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
	}
	
	function takePrev($word) {
		return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
	}
	
	function switchWord($oldWord, $newWord) {
		$oldWord.removeClass('is-visible').addClass('is-hidden');
		$newWord.removeClass('is-hidden').addClass('is-visible');
	}
});
/*----header animation element--------*/
/*-video post ! fluidvids.js v2.4.1 | (c) 2014 @toddmotto | https://github.com/toddmotto/fluidvids */
!function(e,t){"function"==typeof define&&define.amd?define(t):"object"==typeof exports?module.exports=t:e.fluidvids=t()}(this,function(){"use strict";function e(e){return new RegExp("^(https?:)?//(?:"+d.players.join("|")+").*$","i").test(e)}function t(e,t){return parseInt(e,10)/parseInt(t,10)*100+"%"}function i(i){if((e(i.src)||e(i.data))&&!i.getAttribute("data-fluidvids")){var n=document.createElement("div");i.parentNode.insertBefore(n,i),i.className+=(i.className?" ":"")+"fluidvids-item",i.setAttribute("data-fluidvids","loaded"),n.className+="fluidvids",n.style.paddingTop=t(i.height,i.width),n.appendChild(i)}}function n(){var e=document.createElement("div");e.innerHTML="<p>x</p><style>"+o+"</style>",r.appendChild(e.childNodes[1])}var d={selector:["iframe","object"],players:["www.youtube.com","player.vimeo.com"]},o=[".fluidvids {","width: 100%; max-width: 100%; position: relative;","}",".fluidvids-item {","position: absolute; top: 0px; left: 0px; width: 100%; height: 100%;","}"].join(""),r=document.head||document.getElementsByTagName("head")[0];return d.render=function(){for(var e=document.querySelectorAll(d.selector.join()),t=e.length;t--;)i(e[t])},d.init=function(e){for(var t in e)d[t]=e[t];d.render(),n()},d});
(function($){
	"use strict";
	var initFluidVids = function() {
		fluidvids.init({ selector: ['iframe:not(.pt-plus-bg-video)'],players: ['www.youtube.com', 'player.vimeo.com']})
	};
	$(window).on('load', initFluidVids);
	$('body').on('post-load', initFluidVids);
})(jQuery);
/*-video post ----*/
/*-the plus video--*/

! function(e) {
    "use strict";

    function t(t) {
        var a = t.find("video"),
            n = t.find(".ts-video-lazyload");
        if (t.is("[data-grow]") && t.css("max-width", "none"), t.find(".ts-video-title, .ts-video-description, .ts-video-play-btn, .ts-video-thumbnail").addClass("ts-video-hidden"), n.length) {
            var i = n.data();
            e("<iframe></iframe>").attr(i).insertAfter(n)
        }
        a.length && a.get(0).play()
    }

    function a() {
        e(".ts-video-wrapper[data-inview-lazyload]").one("inview", function(a, n) {
            n && t(e(this))
        })
    }
    e(document).on("click", '[data-mode="lazyload"] .ts-video-play-btn', function(a) {
        a.preventDefault(), t(e(this).closest(".ts-video-wrapper"))
    }), a(), e(document).ajaxComplete(function() {
        a()
    }), e(document).on("lity:open", function() {
        /*e("*").not(".lity, .lity-wrap, .lity-close").filter(function() {
            return "fixed" === e(this).css("position")
        }).addClass("ts-video-hidden").attr("data-hidden-fixed", "true")*/
    }), e(document).on("lity:ready", function(t, a) {
        var n = a.element(),
            i = n.find("video"),
            r = n.find(".ts-video-lazyload");
        if (e(".lity-wrap").attr("id", "ts-video"), r.length) e("<iframe></iframe>").attr(r.data()).insertAfter(r);
        i.length && i.get(0).play()
    }), e(document).on("lity:close", function(t, a) {
        a.element().find("video").length && a.element().find("video").get(0).pause(), e(".ts-video-lity-container .pt-plus-video-frame").remove(), e("[data-hidden-fixed]").removeClass("ts-video-hidden")
    }), e(document).ready(function() {
        e(".ts-video-lightbox-link").off()
    })
}(jQuery);


/*-the plus video--*/
/*- contact form-----------*/
( function ( $ ) { 
 'use strict';
	$(document).ready(function() {
		$('.pt_plus_cf7_styles').each(function(){
			$('body').addClass("pt_plus_cf7_form");
			var style=$(this).data("style");
			var radio_checkbox=$(this).data("style-radio-checkbox");
			var svg_path='';
			var line_svg='';
			if(style=='style-3'){
				svg_path='<svg class="graphic graphic--style-3" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none"><path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0" stroke-width="1"></path></svg>';
			}
			if(style=='style-11'){
			 line_svg='<svg class="graphic graphic--style-11" width="100%" height="100%" viewBox="0 0 404 77" preserveAspectRatio="none"><path d="m0,0l404,0l0,77l-404,0l0,-77z" stroke-width="1.5"></path></svg>';
			}
			var i=1;
			$(".wpcf7-form-control.wpcf7-text, .wpcf7-form-control.wpcf7-number, .wpcf7-form-control.wpcf7-date, .wpcf7-form-control.wpcf7-textarea, .wpcf7-form-control.wpcf7-select",this).each(function(){
				var placeholder_name = $(this).attr('placeholder');
				
				if($(this).hasClass("wpcf7-select")){
					placeholder_name=$("option:first-child",this).text();
				}
				$(this).parents(".wpcf7-form-control-wrap").append('<label class="input__label input__label--'+style+'" for="'+style+'-cf-input-'+i+'">'+line_svg+'<span class="input__label-content input__label-content--'+style+'" data-content="'+placeholder_name+'">'+placeholder_name+'</span></label>'+svg_path);
				$(this).attr('placeholder','');
				$(this).attr('id',style+'-cf-input-'+i);
				$(this).addClass('input__field input__field--'+style);
				$(this).parents(".wpcf7-form-control-wrap").addClass('input--'+style);
				i++;
			});
			$(".wpcf7-form-control.wpcf7-select",this).each(function(){
				$(this).parents(".wpcf7-form-control-wrap").addClass("input--filled");
			});
			$(".wpcf7-form-control.wpcf7-radio .wpcf7-list-item",this).each(function(){
				var text_val=$(this).find('.wpcf7-list-item-label').text();
				$(this).find('.wpcf7-list-item-label').remove();
				var label_Tags=$('input[type="radio"]',this);
					if ( label_Tags.parent().is( 'label' )) {
						label_Tags.unwrap();
						}
				var radio_name=$(this).find('input[type="radio"]').attr('name');
				$(this).append('<label class="input__radio_btn" for="'+radio_name+i+'">'+text_val+'<div class="toggle-button__icon"></div></label>');
				$(this).find('input[type="radio"]').attr('id',radio_name+i);
				
				$(this).find('input[type="radio"]').addClass("input-radio-check");
				$(this).parents(".wpcf7-form-control-wrap").addClass(radio_checkbox);
				i++;
			});
			$(".wpcf7-form-control.wpcf7-checkbox .wpcf7-list-item",this).each(function(){
				var text_val=$(this).find('.wpcf7-list-item-label').text();
				$(this).find('.wpcf7-list-item-label').remove();
				var label_Tags=$('input[type="checkbox"]',this);
					if ( label_Tags.parent().is( 'label' )) {
						label_Tags.unwrap();
					}
				$(this).append('<label class="input__checkbox_btn" for="'+radio_checkbox+i+'">'+text_val+'<div class="toggle-button__icon"></div></label>');
				$(this).find('input[type="checkbox"]').attr('id',radio_checkbox+i);
				
				$(this).find('input[type="checkbox"]').addClass("input-checkbox-check");
				$(this).parents(".wpcf7-form-control-wrap").addClass(radio_checkbox);
				i++;
			});
			$(".wpcf7-form-control-wrap input[type='file']",this).each(function(){
			var file_name=$(this).attr('name');
				$(this).attr('id',file_name+i);
				$(this).attr('data-multiple-caption',"{count} files selected");
				$(this).parents(".wpcf7-form-control-wrap").append('<label class="input__file_btn" for="'+file_name+i+'"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg><span>Choose a file…</span></label>');
				$(this).parents(".wpcf7-form-control-wrap").addClass("cf7-style-file");
				i++;
			});
		});	
	
		$("input.wpcf7-form-control,textarea.wpcf7-form-control").focus(function() {
		  $(this).parents(".wpcf7-form-control-wrap").addClass("input--filled");
		});
	  
		$('input.wpcf7-form-control,textarea.wpcf7-form-control').blur(function(){
			if( !$(this).val() ) {
				  $(this).parents(".wpcf7-form-control-wrap").removeClass('input--filled');
			}
		});
		$('.wpcf7-form-control-wrap select').on('change',function(){
				var select_val=$(this).find(':selected').val();
			if(select_val!=''){
				$(this).parents(".wpcf7-form-control-wrap").addClass("input--filled");
			}
		});
		$(".wpcf7-form-control.wpcf7-textarea.input__field--style-9").each(function(){
			var height_textarea=$(this).outerHeight();
			$("head").append('<style >.pt_plus_cf7_styles .wpcf7-textarea.input__field--style-9 + .input__label--style-9::before{height:'+height_textarea+'px;}</style>');
		});
		$(".pt_plus_cf7_styles .wpcf7-form-control-wrap.input--style-12").each(function(){
			var height_textarea=$(this).outerHeight();
			$("head").append('<style >.pt_plus_cf7_form .pt_plus_cf7_styles .wpcf7-textarea.input__field--style-12{height:'+height_textarea+'px;}</style>');
		});
		$(window).load(function(){
			$(".pt_plus_cf7_styles").find(".minimal-form-input").removeClass("minimal-form-input");
		});
	});	
	$(document).on('load resize',function(){
		$(".wpcf7-form-control.wpcf7-textarea.input__field--style-9").each(function(){
			var height_textarea=$(this).outerHeight();
			$("head").append('<style >.pt_plus_cf7_styles .wpcf7-textarea.input__field--style-9 + .input__label--style-9::before{height:'+height_textarea+'px;}</style>');
		});
		$(".pt_plus_cf7_styles .wpcf7-form-control-wrap.input--style-12").each(function(){
			var height_textarea=$(this).outerHeight();
			$("head").append('<style >.pt_plus_cf7_form .pt_plus_cf7_styles .wpcf7-textarea.input__field--style-12{height:'+height_textarea+'px;}</style>');
		});
	});
} ( jQuery ) );

'use strict';
;( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.wpcf7-form-control.wpcf7-file' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label='';
		var labelVal='';
		var i=0;
		input.addEventListener( 'change', function( e )
		{
		label  = input.nextElementSibling;
		if(i==0){
			labelVal = label.innerHTML;
		}
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
			i++;
		});
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));
/*- contact form-----------*/
/*---------------------------vc columns video----------------------------*/

(function($) {
	$.fn.pt_plus_VideoBgInit = function() {
		return this.each(function() {
			var $self = $(this),
				ratio = 1.778,
				pWidth = $self.parent().width(),
				pHeight = $self.parent().height(),
				selfWidth,
				selfHeight;
			var setSizes = function() {
				if(pWidth / ratio < pHeight) {
					selfWidth = Math.ceil(pHeight * ratio);
					selfHeight = pHeight;
					$self.css({
						'width': selfWidth,
						'height': selfHeight
					});
				} else {
					selfWidth = pWidth;
					selfHeight = Math.ceil(pWidth / ratio);
					$self.css({
						'width': selfWidth,
						'height': selfHeight
					});
				}
			};				
			setSizes();
			$(window).on('resize', setSizes);
		});
	};

	$(window).load(function() {
	setTimeout(function(){
		$('.columns-video-bg video, .columns-video-bg .columns-bg-frame').pt_plus_VideoBgInit();
$('.self-hosted-videos').each(function() {
var $self=$(this);
$self[0].play();
});
}, 100);
      if($('.columns-youtube-bg').length > 0) {
		var tag = document.createElement('script');

		tag.src = "//www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		
		var players = {};
		
		window.onYouTubeIframeAPIReady = function() {
			$('.columns-youtube-bg iframe').each(function() {
				var $self = $(this),
					id = $self.attr('id');
					players[id] = new YT.Player(id, {   
					       playerVars: {autoplay:1},    
						events: {
						   onReady: function(e) {
						   if($self.data('muted') && $self.data('muted') == '1') {
						      e.target.mute();
						   }
						      e.target.playVideo();
						   }
						}
					});
				
			});
		};
		
	}
	if($('.columns-vimeo-bg').length > 0) {
	
		$(document).ready(function() {
			$('.columns-vimeo-bg iframe').each(function() {
				var $self = $(this);
					
				if (window.addEventListener) {
					window.addEventListener('message', onMessageReceived, false);
				} else {
					window.attachEvent('onmessage', onMessageReceived, false);
				}
		
				function onMessageReceived(e) {
					var data = JSON.parse(e.data);
					
					switch (data.event) {
						case 'ready':
							$self[0].contentWindow.postMessage('{"method":"play", "value":1}','*');
							if($self.data('muted') && $self.data('muted') == '1') {
								$self[0].contentWindow.postMessage('{"method":"setVolume", "value":0}','*');
							}
							break;
					}
				}
			});
		});
	}
	});
})(jQuery);
/*---------------------------vc columns video----------------------------*/