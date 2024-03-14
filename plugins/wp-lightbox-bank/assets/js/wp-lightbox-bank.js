/** ==========================================================
 
 * jquery lightGallery.js v1.1.2
 * http://sachinchoolur.github.io/lightGallery/
 * Released under the Apache License - http://opensource.org/licenses/Apache-2.0  ---- FREE ----
 
 =========================================================/**/
;
(function ($) {
   "use strict";
   $.fn.lightGallery = function (options) {
      var defaults = {
         mode: 'slide',
         useCSS: true,
         easing: 'ease', //'cubic-bezier(0.25, 0, 0.25, 1)',//
         speed: 1000,
         closable: false,
         loop: true,
         auto: false,
         pause: 4000,
         preload: 1, //number of preload slides. will exicute only after the current slide is fully loaded. ex:// you clicked on 4th image and if preload = 1 then 3rd slide and 5th slide will be loaded in the background after the 4th slide is fully loaded.. if preload is 2 then 2nd 3rd 5th 6th slides will be preloaded.. ... ...
         escKey: true,
         rel: false,
         lang: {
            allPhotos: 'All photos'
         },
         exThumbImage: false,
         index: false,
         thumbnail: false,
         caption: true,
         captionLink: false,
         desc: true,
         controls: true,
         hideControlOnEnd: false,
         mobileSrc: false,
         mobileSrcMaxWidth: 640,
         //touch
         swipeThreshold: 50,
         vimeoColor: 'CCCCCC',
         videoAutoplay: true,
         videoMaxWidth: 855,
         disableOther: true,
         errorMessage: "Image cannot be loaded. Make sure the path is correct and image exist.",
         dynamic: false,
         //callbacks
         dynamicEl: [],
         onOpen: function () {},
         onSlideBefore: function () {},
         onSlideAfter: function () {},
         onSlideNext: function () {},
         onSlidePrev: function () {},
         onBeforeClose: function () {},
         onCloseAfter: function () {}
      },
              el = $(this),
              $children,
              index,
              lightGalleryOn = false,
              html = '<div id="lightGallery-outer"><div id="lightGallery-Gallery"><div id="lightGallery-slider"></div><a id="lightGallery-close" class="close"></a></div></div>',
              isTouch = document.createTouch !== undefined || ('ontouchstart' in window) || ('onmsgesturechange' in window) || navigator.msMaxTouchPoints,
              url_array = [], item, img, complete, $gallery, $galleryCont, $slider, $slide, $prev, $next, prevIndex, $thumb_cont, $thumb, windowWidth, interval, usingThumb = false,
              aTiming = false,
              aSpeed = false;
      var settings = $.extend(true, {}, defaults, options);
      var lightGallery = {
         init: function () {
            el.each(function () {
               var $this = $(this);
               if (settings.disableOther == true)
               {
                  jQuery.fn.prettyPhoto = function () {
                     return this;
                  };
                  jQuery.fn.fancybox = function () {
                     return this;
                  };
                  jQuery.fn.fancyZoom = function () {
                     return this;
                  };
                  jQuery.fn.colorbox = function () {
                     return this;
                  };
               }
               if (settings.dynamic == true) {
                  $children = settings.dynamicEl;
                  index = 0;
                  prevIndex = index;
                  setUp.init(index);
               } else {
                  $children = $(this);

                  $($this).each(function (index)
                  {
                     if ($(this).is("a"))
                     {
                        url_array.push($(this).attr("href"));
                     } else
                     {
                        url_array.push($(this).find("a").attr("href"));
                     }
                  });

                  $children.click(function (e) {
                     if (settings.rel == true && $this.data('rel')) {
                        var rel = $this.data('rel');
                        $children = $('[data-rel="' + rel + '"]').children();
                     } else {
                        $children = $this;
                        if ($children.is("a"))
                        {
                           var imageSource = $children.attr("href");
                        } else
                        {
                           var imageSource = $children.find("a").attr("href");
                        }
                     }
                     e.preventDefault();
                     e.stopPropagation();

                     index = $.inArray(imageSource, url_array);
                     if (index < 0) {
                        index = 0;
                     }
                     prevIndex = index;
                     setUp.init(index);

                  });
               }
            });
         },
      };
      var setUp = {
         init: function () {
            this.start();
            this.build();
         },
         start: function () {
            this.structure();
            this.getWidth();
            this.closeSlide();
         },
         build: function () {
            this.addCaption();
            this.addDesc(); //description
            this.slideTo();
            this.keyPress();
            if (settings.index) {

               this.slide(settings.index);
            } else {

               this.slide(index);
            }
            this.touch();
            this.enableTouch();

            setTimeout(function () {
               $gallery.addClass('opacity');
            }, 50);
         },
         structure: function () {
            $('body').append(html).addClass('lightGallery');
            $galleryCont = $('#lightGallery-outer');
            $gallery = $('#lightGallery-Gallery');
            $slider = $gallery.find('#lightGallery-slider');

            var slideList = '';
            if (settings.dynamic == true) {
               for (var i = 0; i < settings.dynamicEl.length; i++) {
                  slideList += '<div class="lightGallery-slide"></div>';
               }
            } else {
               $.each(url_array, function (index, value) {
                  slideList += '<div class="lightGallery-slide"></div>';
               });
            }
            $slider.append(slideList);
            $slide = $gallery.find('.lightGallery-slide');
         },
         closeSlide: function () {
            var $this = this;
            if (settings.closable) {
               $('.lightGallery-slide')
                       .on('click', function (event) {
                          //console.log(event.target);
                          if ($(event.target).is('.lightGallery-slide')) {
                             $this.destroy();
                          }
                       })
                       ;
            }
            $('#lightGallery-close').bind('click touchend', function () {
               $this.destroy();
            });
         },
         getWidth: function () {
            var resizeWindow = function () {
               windowWidth = $(window).width();
            };
            $(window).bind('resize.lightGallery', resizeWindow());
         },
         doCss: function () {
            var support = function () {
               var transition = ['transition', 'MozTransition', 'WebkitTransition', 'OTransition', 'msTransition', 'KhtmlTransition'];
               var root = document.documentElement;
               for (var i = 0; i < transition.length; i++) {
                  if (transition[i] in root.style) {
                     //cssPrefix = transition[i].replace('Transition', '').toLowerCase();
                     //cssPrefix == 'transition' ? cssPrefix = 'transition' : cssPrefix = ('-'+cssPrefix+'-transition');
                     return true;
                  }
               }
            };
            if (settings.useCSS && support()) {
               return true;
            }
            return false;
         },
         enableTouch: function () {
            var $this = this;
            if (isTouch) {
               var startCoords = {},
                       endCoords = {};
               $('body').on('touchstart.lightGallery', function (e) {
                  endCoords = e.originalEvent.targetTouches[0];
                  startCoords.pageX = e.originalEvent.targetTouches[0].pageX;
                  startCoords.pageY = e.originalEvent.targetTouches[0].pageY;
               });
               $('body').on('touchmove.lightGallery', function (e) {
                  var orig = e.originalEvent;
                  endCoords = orig.targetTouches[0];
                  e.preventDefault();
               });
               $('body').on('touchend.lightGallery', function (e) {
                  var distance = endCoords.pageX - startCoords.pageX,
                          swipeThreshold = settings.swipeThreshold;
                  if (distance >= swipeThreshold) {
                     $this.prevSlide();
                     clearInterval(interval);
                  } else if (distance <= -swipeThreshold) {
                     $this.nextSlide();
                     clearInterval(interval);
                  }
               });
            }
         },
         touch: function () {
            var xStart, xEnd;
            var $this = this;
            $('.lightGallery').bind('mousedown', function (e) {
               e.stopPropagation();
               e.preventDefault();
               xStart = e.pageX;
            });
            $('.lightGallery').bind('mouseup', function (e) {
               e.stopPropagation();
               e.preventDefault();
               xEnd = e.pageX;
               if (xEnd - xStart > 20) {
                  $this.prevSlide();
               } else if (xStart - xEnd > 20) {
                  $this.nextSlide();
               }
            });
         },
         isVideo: function (src) {
            var youtube = src.match(/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i);
            var vimeo = src.match(/\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i);
            if (youtube || vimeo) {
               return true;
            }
         },
         loadVideo: function (src, _id) {
            var youtube = src.match(/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i);
            var vimeo = src.match(/\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i);
            var video = '';
            var a = '';
            if (youtube) {
               if (settings.videoAutoplay === true && lightGalleryOn === false) {
                  a = '?autoplay=1&rel=0&wmode=opaque';
               } else {
                  a = '?wmode=opaque';
               }
               video = '<iframe id="video' + _id + '" width="560" height="315" src="//www.youtube.com/embed/' + youtube[1] + a + '" frameborder="0" allowfullscreen></iframe>';
            } else if (vimeo) {
               if (settings.videoAutoplay === true && lightGalleryOn === false) {
                  a = 'autoplay=1&amp;';
               } else {
                  a = '';
               }
               video = '<iframe id="video' + _id + '" width="560" height="315"  src="http://player.vimeo.com/video/' + vimeo[1] + '?' + a + 'byline=0&amp;portrait=0&amp;color=' + settings.vimeoColor + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
            }
            return '<div class="video_cont" style="max-width:' + settings.videoMaxWidth + 'px !important;"><div class="video">' + video + '</div></div>';
         },
         loadContent: function (index, rec) {
            var $this = this;

            var i, j,
                    l = url_array.length - index;
            var src;
            if (settings.preload > url_array.length) {
               settings.preload = url_array.length;
            }
            if (settings.mobileSrc === true && windowWidth <= settings.mobileSrcMaxWidth) {
               if (settings.dynamic == true) {
                  src = settings.dynamicEl[index]['mobileSrc'];
               } else {
                  src = $children.eq(index).attr('data-responsive-src');
               }
            } else {
               if (settings.dynamic == true) {
                  src = settings.dynamicEl[index]['src'];
               } else {
                  src = url_array[index];
               }
            }
            if (!$this.isVideo(src)) {
               if (!$slide.eq(index).hasClass('loaded')) {
                  $slide.eq(index).prepend('<img src="' + src + '" />');
                  $slide.eq(index).addClass('loaded');
               }
               if (rec === false) {
                  complete = false;
                  if ($slide.eq(index).find('img')[0].complete) {
                     complete = true;
                  }
                  if (!complete) {
                     $slide.eq(index).find('img').on('error', function () {
                        alert(settings.errorMessage);
                        $this.destroy();
                     });
                     $slide.eq(index).find('img').on('load error', function () {

                        var newIndex = index;
                        for (var k = 0; k <= settings.preload; k++) {
                           if (k >= url_array.length - index) {
                              break;
                           }
                           $this.loadContent(newIndex + k, true);
                        }
                        for (var h = 0; h <= settings.preload; h++) {
                           if (newIndex - h < 0) {
                              break;
                           }
                           $this.loadContent(newIndex - h, true);
                        }
                     });
                  } else {
                     var newIndex = index;
                     for (var k = 0; k <= settings.preload; k++) {
                        if (k >= url_array.length - index) {
                           break;
                        }
                        $this.loadContent(newIndex + k, true);
                     }
                     for (var h = 0; h <= settings.preload; h++) {
                        if (newIndex - h < 0) {
                           break;
                        }
                        $this.loadContent(newIndex - h, true);
                     }
                  }
               }
            } else {
               if (!$slide.eq(index).hasClass('loaded')) {
                  if (rec === false && lightGalleryOn === true && settings.preload === 0) {
                     setTimeout(function () {
                        $slide.eq(index).prepend($this.loadVideo(src, index));
                     }, settings.speed);
                  } else {
                     $slide.eq(index).prepend($this.loadVideo(src, index));
                  }
                  $slide.eq(index).addClass('loaded');

               }

               if (rec === false) {
                  complete = false;
                  if ($slide.eq(index).find('iframe')[0].complete) {
                     complete = true;
                  }
                  if (!complete) {
                     $slide.eq(index).find('iframe').on('error', function () {
                        alert(settings.errorMessage);
                        $this.destroy();
                     });
                     $slide.eq(index).find('iframe').on('load error', function () {

                        var newIndex = index;
                        for (var k = 0; k <= settings.preload; k++) {
                           if (k >= url_array.length - index) {
                              break;
                           }
                           $this.loadContent(newIndex + k, true);
                        }
                        for (var h = 0; h <= settings.preload; h++) {
                           $this.loadContent(newIndex - h, true);
                        }
                     });
                  } else {
                     var newIndex = index;
                     for (var k = 0; k <= settings.preload; k++) {
                        $this.loadContent(newIndex + k, true);
                     }
                     for (var h = 0; h <= settings.preload; h++) {
                        $this.loadContent(newIndex - h, true);
                     }
                  }
               }


            }
         },
         addCaption: function () {
            if (settings.caption === true) {
               var i, title = false;

               for (i = 0; i < url_array.length; i++) {
                  if (settings.dynamic == true) {
                     title = settings.dynamicEl[i]['caption'];
                  } else {
                     item = jQuery("a[href='" + url_array[i] + "']");
                     img = item.find("img");
                     title = (item.attr("data-title") ? item.attr("data-title") : (img.attr("alt") ? img.attr("alt") : ""));
                  }
                  if (settings.captionLink === true) {
                     var link = null;
                     if (settings.dynamic == true) {
                        link = settings.dynamicEl[i]['link'];
                     } else {
                        link = $children.eq(i).attr('data-link');
                     }
                     if (typeof link !== 'undefined' && link !== '') {
                        link = link
                     } else {
                        link = '#'
                     }
                     if (title != "undefined")
                     {
                        if (title != "")
                        {
                           $slide.eq(i).append('<div class="info group"><a href="' + link + '" class="title">' + title + '</a></div>');
                        }
                     }
                  } else {
                     if (typeof title != 'undefined' || title != null) {
                        if (title != "undefined")
                        {
                           if (title != "")
                           {
                              $slide.eq(i).append('<div class="info group"><span class="title">' + title + '</span></div>');
                           }
                        }
                     }
                  }
               }
            }
         },
         addDesc: function () {
            if (settings.desc === true) {
               var i, description = false;
               for (i = 0; i < url_array.length; i++) {
                  if (settings.dynamic == true) {
                     description = settings.dynamicEl[i]['desc'];
                  } else {
                     item = jQuery("a[href='" + url_array[i] + "']");
                     description = item.attr("data-desc");
                     if (typeof description == 'undefined' || description == null) {
                        var descContainerId = jQuery("IMG", item).attr("aria-describedby");
                        if (typeof descContainerId != 'undefined' && descContainerId != null) {
                           description = jQuery("#" + descContainerId).text();
                        }
                     }
                  }
                  if (typeof description != 'undefined' && description != null) {
                     if (settings.caption === false) {
                        $slide.eq(i).append('<div class="info group"><span class="desc">' + description + '</span></div>');
                     } else {
                        $slide.eq(i).find('.info').append('<span class="desc">' + description + '</span>');
                     }
                  }
               }
            }
         },
         slideTo: function () {
            var $this = this;
            if (settings.controls === true && url_array.length > 1) {
               $gallery.append('<div id="lightGallery-action"><a id="lightGallery-prev"></a><a id="lightGallery-next"></a></div>');
               $prev = $gallery.find('#lightGallery-prev');
               $next = $gallery.find('#lightGallery-next');
               $prev.bind('click', function () {
                  $this.prevSlide();
                  clearInterval(interval);
               });
               $next.bind('click', function () {
                  $this.nextSlide();
                  clearInterval(interval);
               });
            }
         },
         keyPress: function () {
            var $this = this;
            $(window).bind('keyup.lightGallery', function (e) {
               e.preventDefault();
               e.stopPropagation();
               if (e.keyCode === 37) {
                  $this.prevSlide();
                  clearInterval(interval);
               }
               if (e.keyCode === 38 && settings.thumbnail === true) {
                  if (!$thumb_cont.hasClass('open')) {
                     if ($this.doCss() && settings.mode === 'slide') {
                        $slide.eq(index).prevAll().removeClass('nextSlide').addClass('prevSlide');
                        $slide.eq(index).nextAll().removeClass('prevSlide').addClass('nextSlide');
                     }
                     $thumb_cont.addClass('open');
                  }
               } else if (e.keyCode === 39) {
                  $this.nextSlide();
                  clearInterval(interval);
               }
               if (e.keyCode === 40 && settings.thumbnail === true) {
                  if ($thumb_cont.hasClass('open')) {
                     $thumb_cont.removeClass('open');
                  }
               } else if (settings.escKey === true && e.keyCode === 27) {
                  if (settings.thumbnail === true && $thumb_cont.hasClass('open')) {
                     $thumb_cont.removeClass('open');
                  } else {
                     $this.destroy();
                  }
               }
            });
         },
         nextSlide: function () {
            var $this = this;
            index = $slide.index($slide.eq(prevIndex));
            $this.disableVideo(index);
            if (index + 1 < url_array.length) {
               index++;
               $this.slide(index);
            } else {
               if (settings.loop) {
                  index = 0;
                  $this.slide(index);
               } else if (settings.mode === 'fade' && settings.thumbnail === true && url_array.length > 1) {
                  $thumb_cont.addClass('open');
               }
            }
            settings.onSlideNext.call(this);
         },
         prevSlide: function () {
            var $this = this;
            index = $slide.index($slide.eq(prevIndex));
            $this.disableVideo(index);
            if (index > 0) {
               index--;
               $this.slide(index);
            } else {
               if (settings.loop) {
                  index = url_array.length - 1;
                  $this.slide(index);
               } else if (settings.mode === 'fade' && settings.thumbnail === true && url_array.length > 1) {
                  $thumb_cont.addClass('open');
               }
            }
            settings.onSlidePrev.call(this);
         },
         disableVideo: function (index)
         {
            var $this = this;
            var src;
            if (settings.mobileSrc === true && windowWidth <= settings.mobileSrcMaxWidth) {
               if (settings.dynamic == true) {
                  src = settings.dynamicEl[index]['mobileSrc'];
               } else {
                  src = $children.eq(index).attr('data-responsive-src');
               }
            } else {
               if (settings.dynamic == true) {
                  src = settings.dynamicEl[index]['src'];
               } else {
                  src = url_array[index];
               }
            }

            if ($this.isVideo(src))
            {
               var youtube = src.match(/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i);
               var vimeo = src.match(/\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i);
               var video = '';
               var a = '';
               var video_src = "";
               if (youtube) {
                  if (settings.videoAutoplay === true && lightGalleryOn === false) {
                     a = '?rel=0&wmode=opaque';
                  } else {
                     a = '?wmode=opaque';
                  }
                  video_src = "//www.youtube.com/embed/" + youtube[1] + a;
               } else if (vimeo) {
                  video_src = "http://player.vimeo.com/video/" + vimeo[1] + "?byline=0&amp;portrait=0&amp;color=" + settings.vimeoColor;
               }
               $slide.eq(index).find("iframe").attr("src", video_src);
            }
         },
         slide: function (index) {
            this.loadContent(index, false);
            if (lightGalleryOn) {
               if (!$slider.hasClass('on')) {
                  $slider.addClass('on');
               }
               if (this.doCss() && settings.speed !== '') {
                  if (!$slider.hasClass('speed')) {
                     $slider.addClass('speed');
                  }
                  if (aSpeed === false) {
                     $slider.css('transition-duration', settings.speed + 'ms');
                     aSpeed = true;
                  }
               }
               if (this.doCss() && settings.easing !== '') {
                  if (!$slider.hasClass('timing')) {
                     $slider.addClass('timing');
                  }
                  if (aTiming === false) {
                     $slider.css('transition-timing-function', settings.easing);
                     aTiming = true;
                  }
               }
               settings.onSlideBefore.call(this);
            }
            if (settings.mode === 'slide') {
               var isiPad = navigator.userAgent.match(/iPad/i) != null;
               if (this.doCss() && !$slider.hasClass('slide') && !isiPad) {
                  $slider.addClass('slide');
               } else if (this.doCss() && !$slider.hasClass('useLeft') && isiPad) {
                  $slider.addClass('useLeft');
               }
               /*                  if(this.doCss()){
                $slider.css({ 'transform' : 'translate3d('+(-index*100)+'%, 0px, 0px)' });
                }*/
               if (!this.doCss() && !lightGalleryOn) {
                  $slider.css({
                     left: (-index * 100) + '%'
                  });
                  //$slide.eq(index).css('transition','none');
               } else if (!this.doCss() && lightGalleryOn) {
                  $slider.animate({
                     left: (-index * 100) + '%'
                  }, settings.speed, settings.easing);
               }
            } else if (settings.mode === 'fade') {
               if (this.doCss() && !$slider.hasClass('fadeM')) {
                  $slider.addClass('fadeM');
               } else if (!this.doCss() && !$slider.hasClass('animate')) {
                  $slider.addClass('animate');
               }
               if (!this.doCss() && !lightGalleryOn) {
                  $slide.fadeOut(100);
                  $slide.eq(index).fadeIn(100);
               } else if (!this.doCss() && lightGalleryOn) {
                  $slide.eq(prevIndex).fadeOut(settings.speed, settings.easing);
                  $slide.eq(index).fadeIn(settings.speed, settings.easing);
               }
            }
            if (index + 1 >= url_array.length && settings.auto && settings.loop === false) {
               clearInterval(interval);
            }
            $slide.eq(prevIndex).removeClass('current');
            $slide.eq(index).addClass('current');
            if (this.doCss() && settings.mode === 'slide') {
               if (usingThumb === false) {
                  $('.prevSlide').removeClass('prevSlide');
                  $('.nextSlide').removeClass('nextSlide');
                  $slide.eq(index - 1).addClass('prevSlide');
                  $slide.eq(index + 1).addClass('nextSlide');
               } else {
                  $slide.eq(index).prevAll().removeClass('nextSlide').addClass('prevSlide');
                  $slide.eq(index).nextAll().removeClass('prevSlide').addClass('nextSlide');
               }
            }
            if (settings.thumbnail === true && url_array.length > 1) {
               $thumb.removeClass('active');
               $thumb.eq(index).addClass('active');
            }
            if (settings.controls && settings.hideControlOnEnd && settings.loop === false && url_array.length > 1) {
               var l = url_array.length;
               l = parseInt(l) - 1;
               if (index === 0) {
                  $prev.addClass('disabled');
                  $next.removeClass('disabled');
               } else if (index === l) {
                  $prev.removeClass('disabled');
                  $next.addClass('disabled');
               } else {
                  $prev.add($next).removeClass('disabled');
               }
            }
            prevIndex = index;
            lightGalleryOn === false ? settings.onOpen.call(this) : settings.onSlideAfter.call(this);
            lightGalleryOn = true;
            usingThumb = false;
         },
         destroy: function () {
            settings.onBeforeClose.call(this);
            lightGalleryOn = false;
            aTiming = false;
            aSpeed = false;
            usingThumb = false;
            clearInterval(interval);
            $('.lightGallery').off('mousedown mouseup');
            $('body').off('touchstart.lightGallery touchmove.lightGallery touchend.lightGallery');
            $(window).off('resize.lightGallery keyup.lightGallery');
            $gallery.addClass('fadeM');
            setTimeout(function () {
               $galleryCont.remove();
               $('body').removeClass('lightGallery');
            }, 500);
            settings.onCloseAfter.call(this);
         }
      };
      lightGallery.init();
      return this;
   };
}(jQuery));