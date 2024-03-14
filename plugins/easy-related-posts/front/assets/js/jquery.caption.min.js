/*!
 * caption.js | easily and semantically add captions to your images
 * http://captionjs.com
 *
 * Copyright 2013-2014, Eric Magnuson
 * Released under the MIT license
 * https://github.com/jquery/jquery/blob/master/MIT-LICENSE.txt
 *
 * v0.9.5
 * Date: 2014-03-29
 */
(function(b,a,c){b.fn.captionjs=function(e){var f={class_name:"captionjs",schema:true,mode:"default",debug_mode:false,force_dimensions:false};var d=b.extend(f,e||{});return this.each(function(){if(d.debug_mode){console.log("caption.js | Starting.")}var l=b(this),m=l.data("caption")?l.data("caption"):l.attr("alt"),h=l.wrap('<figure class="'+d.class_name+'"/>').after("<figcaption/>").parent(),k=l.next("figcaption").html(m),g,i;if(m===""){k.remove()}if(d.debug_mode){console.log("caption.js | Caption: "+m)}if(d.force_dimensions){if(d.debug_mode){console.log("caption.js | Forcing dimensions with a clone.")}var j=h.clone().css({position:"absolute",left:"-9999px"}).appendTo("body");g=b("img",j).outerWidth(),i=b("figcaption",j).css("width",g).outerHeight();j.remove()}else{g=l.outerWidth();i=k.outerHeight()}h.width("100%");if(d.schema===true){h.attr({itemscope:"itemscope",itemtype:"http://schema.org/Photograph"});k.attr("itemprop","name");l.attr("itemprop","image")}if(d.mode==="stacked"){h.addClass("stacked");k.css({"margin-bottom":"0",bottom:"0",})}if(d.mode==="animated"){h.addClass("animated");k.css({"margin-bottom":"0",bottom:-i,})}if(d.mode==="hide"){h.addClass("hide");k.css({"margin-bottom":i,bottom:-i,})}})}})(jQuery,window);