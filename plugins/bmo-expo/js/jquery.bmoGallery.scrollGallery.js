/* Die einzelnen Klassen für Gallerie Typen.
 * Diese haben immer die folgenden Methoden, welche von der oben Klasse BMoGallery aufgerufen werden.
 * next()
 * prev()
 * goTo(index, duration)
 * makeGalleryHTMLStructure
 * setHTMLEvents
 *...
*/
var BMo_scrollGallery; //global declaraiton

(function($){
	BMo_scrollGallery = function(parentObj){
		 this.gallery = parentObj;// das Vaterobj der Überklasse
		 this.$gallery_obj = this.gallery.$gallery_obj;//html Obj
		 this.$bmo_the_gallery_thumb_area = this.gallery.$bmo_the_gallery_thumb_area;//html Obj
		 this.$bmo_the_gallery_thumbs = this.gallery.$bmo_the_gallery_thumbs;//html Obj
		 
		 /*spezielle attribute*/
		 this.$bmo_the_gallery_image_area = null;
		 this.$bmo_the_gallery_images = null;
		 this.isAnimating = false;
		 this.isVertical = false;
		 this.followImg = false;
		 this.numOfClones = 0;
		 
		  /*init*/
		 if(this.isInitalized!=true){
			   this.initGallery();
			   this.isInitalized = true;
		 }
	};
	
	BMo_scrollGallery.prototype = {
		
		constructor: BMo_scrollGallery,
		
		initGallery:function(){
		},
		
		makeGalleryHTMLStructure:function(){
			if(this.gallery.options.sG_images){		
				//build html und inline css
				var $bmo_the_gallery_image_area = this.$bmo_the_gallery_image_area = $('<div/>', {
					class: 'bmo_the_gallery_image_area'
				});
				switch(this.gallery.options.sG_thumbPosition) {
					case 'top':
						 this.$bmo_the_gallery_image_area.appendTo(this.$gallery_obj);
						 this.isVertical = false;
					break;
					case 'bottom':
						 this.$bmo_the_gallery_image_area.prependTo(this.$gallery_obj);
						  this.isVertical = false;
					break;
					case 'left':
						 this.$gallery_obj.addClass('vertical');
						 this.$bmo_the_gallery_image_area.appendTo(this.$gallery_obj);
						 this.isVertical = true;
					break;
					case 'right':
					 	 this.$gallery_obj.addClass('vertical');
						 this.$bmo_the_gallery_image_area.prependTo(this.$gallery_obj);
						 this.isVertical = true;
					break;
					
					default:
						this.$bmo_the_gallery_image_area.appendTo(this.$gallery_obj);
				}
				
				var $bmo_the_gallery_images = this.$bmo_the_gallery_images = $('<div/>', {
					class: 'bmo_the_gallery_images'
				}).appendTo(this.$bmo_the_gallery_image_area);
				var sG_caption=this.gallery.options.sG_caption;
				this.$bmo_the_gallery_thumbs.find('.bmo_the_gallery_image').each(function(index){
					var $bmo_the_gallery_image = $('<div/>', {
						class: 'bmo_the_gallery_image'
					}).appendTo($bmo_the_gallery_images);
					var $img = $('<img/>', {
						src: $(this).find('a').attr('href'),
						alt: $(this).find('img').attr('alt'),
						title: $(this).find('img').attr('title')
					}).appendTo($bmo_the_gallery_image);
					if($(this).find('div.bmo_the_gallery_caption').html()!=""&&sG_caption==true){
						var $caption = $('<div/>', {
							class: 'bmo_the_gallery_caption'
						}).appendTo($bmo_the_gallery_image);
						var $caption_text = $('<div/>', {
							class: 'bmo_the_gallery_caption_text',
							html: $(this).find('div.bmo_the_gallery_caption').html()
						}).appendTo($caption);
					}
					
					//store the index number of the image and thumb
					$bmo_the_gallery_image.data('index',index);
					$(this).data('index',index);
				});
				
				if(this.gallery.options.sG_arrows){
					var $bmo_the_gallery_image_arrows_prev = this.$bmo_the_gallery_image_arrows_prev = $('<a/>', {
						class: 'bmo_the_gallery_image_arrows arrow_prev', href: '#prev'
					}).appendTo(this.$bmo_the_gallery_image_area);
					var $bmo_the_gallery_image_arrows_next = this.$bmo_the_gallery_image_arrows_next = $('<a/>', {
						class: 'bmo_the_gallery_image_arrows arrow_next', href: '#next'
					}).appendTo(this.$bmo_the_gallery_image_area);
				}
				if(this.gallery.options.sG_arrowsThumbs&&!this.gallery.options.sG_autoScroll){
					if(this.isVertical){
						var $bmo_the_gallery_thumb_arrows_prev = this.$bmo_the_gallery_thumb_arrows_prev = $('<a/>', {
							class: 'bmo_the_gallery_thumb_arrows arrow_up', href: '#tup'
						}).appendTo(this.$bmo_the_gallery_thumb_area);
						var $bmo_the_gallery_thumb_arrows_next = this.$bmo_the_gallery_thumb_arrows_next = $('<a/>', {
							class: 'bmo_the_gallery_thumb_arrows arrow_down', href: '#tdown'
						}).appendTo(this.$bmo_the_gallery_thumb_area);
					}else{
						var $bmo_the_gallery_thumb_arrows_prev = this.$bmo_the_gallery_thumb_arrows_prev = $('<a/>', {
							class: 'bmo_the_gallery_thumb_arrows arrow_prev', href: '#tprev',
						}).appendTo(this.$bmo_the_gallery_thumb_area);
						var $bmo_the_gallery_thumb_arrows_next = this.$bmo_the_gallery_thumb_arrows_next = $('<a/>', {
							class: 'bmo_the_gallery_thumb_arrows arrow_next', href: '#tnext'
						}).appendTo(this.$bmo_the_gallery_thumb_area);
					}
				}
				
			}else{//if no images
				switch(this.gallery.options.sG_thumbPosition) {
					case 'left':
						 this.$gallery_obj.addClass('vertical');
						 this.isVertical = true;
					break;
					case 'right':
					 	 this.$gallery_obj.addClass('vertical');
						 this.isVertical = true;
					break;
				}
			}
			//set styles inline after all html elements are initalized
			this.$gallery_obj.inlineCSS($.data(document.body,"BMo_Expo_Path"));
			
		},
		
		setHTMLEvents:function(){
			//main img event
			if(this.gallery.options.sG_clickable&&this.gallery.options.sG_images){
				if(this.gallery.options.sG_arrows){
					this.$bmo_the_gallery_image_arrows_prev.click(function(e){
						e.preventDefault();
						this.gallery.prev();
						return false;
					}.bind(this));
					this.$bmo_the_gallery_image_arrows_next.click(function(e){
						e.preventDefault();
						this.gallery.next();
						return false;
					}.bind(this));
				}else{			
					this.$bmo_the_gallery_image_area.click(function(e){
						e.preventDefault();
						this.gallery.next();
						return false;
					}.bind(this));
				}
			}
			if(this.gallery.options.sG_arrowsThumbs){
				this.$bmo_the_gallery_thumb_area.find('.bmo_the_gallery_thumb_arrows').click(function(e){
					e.preventDefault();
					return false;
				});
			}
			
			var that = this;
			//thumb events
			if(this.gallery.options.sG_images){
				this.$bmo_the_gallery_thumbs.find('a').each(function(){
					$(this).click(function(e){
						 e.preventDefault();
						 that.followImg=false;
						 that.gallery.goTo($(this).parent('.bmo_the_gallery_image').data('index'));
						 return false;
					}.bind(this));
				});
			}
			
			//thumb area scroll events:
			this.scrollStatus_direction = null;
			var scrollerInterval;
			if(!this.gallery.options.sG_autoScroll){ 
				//mouseposition
				this.$bmo_the_gallery_thumb_area.mousemove(function(e){
					var parentOffset = $(this).parent().offset(); 
					var x = e.pageX - parentOffset.left;
					var y = e.pageY - parentOffset.top;
					
					var area = Number(that.gallery.options.sG_area);
					if(that.isVertical&&(that.$bmo_the_gallery_thumb_area.height()-area)<0||!that.isVertical&&(that.$bmo_the_gallery_thumb_area.width()-area<0)){
						var area = 30;
					}
					
					that.scrollStatus_direction = null;
					if(that.isVertical){
						if(y<=(that.$bmo_the_gallery_thumb_area.height()-area)/2){
							that.scrollStatus_direction = "up";
						}
						if(y>=(that.$bmo_the_gallery_thumb_area.height()-(that.$bmo_the_gallery_thumb_area.height()-area)/2)){
							that.scrollStatus_direction = "down";
						}
					}else{
						if(x<=(that.$bmo_the_gallery_thumb_area.width()-area)/2){
							that.scrollStatus_direction = "left";
						}
						if(x>=(that.$bmo_the_gallery_thumb_area.width()-(that.$bmo_the_gallery_thumb_area.width()-area)/2)){
							that.scrollStatus_direction = "right";
						}
					}
				});
				//start / stop scrolling
				this.$bmo_the_gallery_thumb_area.hover(function(e){
					scrollerInterval = setInterval(function() {
						that.scrollThumbs();
					},20);
				},function(e) {
					if (!scrollerInterval) {return;}
					clearInterval(scrollerInterval);
					that.scrollStatus_direction = null;
				});
			}else{//autoscroll
				if(this.isVertical)
					this.scrollStatus_direction = "down";
				else
					this.scrollStatus_direction = "right";
				this.gallery.options.sG_loopThumbs = true;
				
				scrollerInterval = setInterval(function() {
					that.scrollThumbs();
				},20);
				if(this.gallery.options.sG_aS_stopOnOver){
					this.$bmo_the_gallery_thumb_area.hover(function(e){
						if (!scrollerInterval) {return;}
						clearInterval(scrollerInterval);
						that.scrollStatus_direction = null;
					},function(e) {
						if(that.isVertical)
							that.scrollStatus_direction = "down";
						else
							that.scrollStatus_direction = "right";
						
						scrollerInterval = setInterval(function() {
							that.scrollThumbs();
						},20);
					});
				}
				this.gallery.options.sG_arrowsThumbs = false;
			}
			
			//mobile events
			if(this.gallery.options.sG_enableSwipeMode){
				var is_touch_device = 'ontouchstart' in document.documentElement;
				if(is_touch_device){
					$(this).bmo_getScript_Helper($.data(document.body,"BMo_Expo_Path")+"js/mobile/jquery.hammer.min.js", function() {
						 if(this.gallery.options.sG_images){
							 //console.log(textStatus+" mobile js"); //success
							 this.$bmo_the_gallery_image_area.unbind('click');//remove click event
							 this.gallery.options.sG_clickable = false;
							 
							 Hammer(this.$bmo_the_gallery_image_area).on("swipeleft", function(e) {
								this.gallery.prev();
							 }.bind(this));
							 Hammer(this.$bmo_the_gallery_image_area).on("swiperight", function(e) {
								this.gallery.next();
							 }.bind(this));
						 }
						 //thumbs
						 if(!this.gallery.options.sG_autoScroll){ 
						 	this.$bmo_the_gallery_thumb_area.unbind('mousemove');
							this.$bmo_the_gallery_thumb_area.unbind('hover');
							if(that.isVertical)
								this.$bmo_the_gallery_thumb_area.css('overflow-y','auto');//vertical version
							else
								this.$bmo_the_gallery_thumb_area.css('overflow-x','auto');
						 }
						 
					}.bind(this));
				}
			}
			
			//unbind mousewheel
			this.$bmo_the_gallery_thumb_area.bind("mousewheel", function() {return false;});
			if(this.gallery.options.sG_images){
				this.$bmo_the_gallery_image_area.bind("mousewheel", function() {return false;});
			}
		},
		
		next:function(){
			if(this.gallery.currentImg+1<this.gallery.numberOfImgs)
				this.gallery.goTo(this.gallery.currentImg+1);
			else
				if(this.gallery.options.sG_loop)
					this.gallery.goTo(0);
				else
					this.gallery.goTo(this.gallery.numberOfImgs-1);
		},
		
		prev:function(){
			if(this.gallery.currentImg-1>=0)
				this.gallery.goTo(this.gallery.currentImg-1);
			else
				if(this.gallery.options.sG_loop)
					this.gallery.goTo(this.gallery.numberOfImgs-1);
				else
					this.gallery.goTo(0);
		},
		
		/* Scroll to a img. Das erste Bild ist index=0 */
		goTo:function(index, duration){//duration ist optional
			
			if(!this.gallery.options.sG_images)
				return;
			
			if(this.isAnimating)
			  return;
		
			if(index < 0 || index >= this.gallery.numberOfImgs)
			  return;
				
			if(index == this.gallery.currentImg)
			  return;
		   
			this.isAnimating = true;  
			var self = this;
			if(!duration) duration= this.gallery.options.duration;
			
			 this.$bmo_the_gallery_images.animate({
			 	left: (-(index * this.$bmo_the_gallery_image_area.width())) + 'px'
			 }, duration, function(){//complete
				this.gallery.currentImg = index;
				this.isAnimating = false;
				this.setThumbs(index);
			}.bind(this));
		},
		
		showGallery:function(){
			
			this.initScrollGallery();
			//change css to match options.
			if(this.gallery.options.sG_responsive){
				if(this.isVertical){
					this.resizeGallery(this.$gallery_obj.width()-this.$bmo_the_gallery_thumb_area.outerWidth(true)-1);//zur sicherheit -1px für FF
				}else{
					this.resizeGallery(this.$gallery_obj.width());
				}
			}else{
				this.resizeGallery(this.gallery.options.gallery_width);
			}
			
			//
			if(this.gallery.options.sG_start>1){
				this.gallery.goTo(this.gallery.options.sG_start-1,10);
			}
		},
		
		initScrollGallery:function(){
			//scrollGallery spezifisch, nachdem HTMl da ist.
			
			//Thumb opacity
			if(this.gallery.options.sG_opacity<100&&this.gallery.options.sG_opacity>=0){
				this.setThumbOpacity();
			}
			
			//follow images
			if(this.gallery.options.sG_followImages){
				this.followImg=true;
			}
			
			//diashow
			if(this.gallery.options.sG_diashowDelay!=null&&this.gallery.options.sG_diashowDelay>=1){
				this.gallery.options.sG_loop = true;
				var that = this;
				clearInterval(this.diashow);
				this.diashow = setInterval(function() {
					that.gallery.next();
				},this.gallery.options.sG_diashowDelay*1000);
			}
			
			//resize on window resize
			if(this.gallery.options.sG_responsive){
				$(window).resize(function() {
					if(this.isVertical){
						this.resizeGallery(this.$gallery_obj.width()-this.$bmo_the_gallery_thumb_area.outerWidth(true)-1);//zur sicherheit -1px für FF
					}else{
						this.resizeGallery(this.$gallery_obj.width());
					}
					this.gallery.goTo(0,20);
				}.bind(this));
			}
			
			//hide thumbs
			if(this.gallery.options.sG_thumbPosition == "none"){
				this.$bmo_the_gallery_thumb_area.hide();
			}
			
		},
		
		
		setThumbs:function(index){//after scroll to img
			this.setThumbOpacity();
			if(this.gallery.options.sG_followImages)
				this.scrollToThumb(index);
		},
		
		setThumbOpacity:function(){
			if(this.gallery.options.sG_opacity<100&&this.gallery.options.sG_opacity>=0){
				var that = this;
				this.$bmo_the_gallery_thumbs.find('.bmo_the_gallery_image').each(function(){
					if($(this).data('index')!=that.gallery.currentImg){
						$(this).find('img').css('opacity',Number(that.gallery.options.sG_opacity)/100);
					}else{
						$(this).find('img').css('opacity',1).addClass('active');
					}
				});
			}
		},
				
		scrollToThumb: function(index){//for following thumbs
			if(!this.gallery.options.sG_autoScroll){//not if autoscroll is active
				if(this.followImg!=false){//falls click über thumbs kommt ist followImg == false
					if(this.isVertical){
						var thumb_height_withBMP = this.$bmo_the_gallery_thumbs.find('div.bmo_the_gallery_image').first().outerHeight(true), //mit padding border und margin				
							newTop	 	= -(index * thumb_height_withBMP),
							min_height   = this.$bmo_the_gallery_thumb_area.height(),
							height		= this.$bmo_the_gallery_thumbs.height();
						if(newTop>=-1*(height-min_height)){
							this.$bmo_the_gallery_thumbs.animate({
								top: (newTop) + 'px'
							 }, 'slow', function(){//complete
							}.bind(this));
						}
					}else{
						var thumb_width_withBMP = this.$bmo_the_gallery_thumbs.find('div.bmo_the_gallery_image').first().outerWidth(true), //mit padding border und margin				
							newLeft 	= -(index * thumb_width_withBMP),
							min_width   = this.$bmo_the_gallery_thumb_area.width(),
							width		= this.$bmo_the_gallery_thumbs.width();
						if(newLeft>=-1*(width-min_width)){
							this.$bmo_the_gallery_thumbs.animate({
								left: (newLeft) + 'px'
							 }, 'slow', function(){//complete
							}.bind(this));
						}
					}
				}
				this.followImg=true;
			}
		},
				
		resizeGallery:function(gallery_width){//new width and height
			if(gallery_width<=0)
				gallery_width = this.gallery.options.gallery_width;
			//thumbs
			var thumb_width = thumb_width_withBMP  = this.gallery.options.thumbs_width;
			var thumb_height= thumb_height_withBMP = this.gallery.options.thumbs_height;
			var numOfThumbs = 0;//!=numberOfImgs bei clone
			//resize height passend zur neuen width: 
			//console.log("w: "+gallery_width+" h: "+gallery_height+ "wo: "+this.gallery.options.gallery_width+" ho: "+this.gallery.options.gallery_height);
			var gallery_height = Math.round(gallery_width/this.gallery.options.gallery_width*this.gallery.options.gallery_height);
			//change the size
			if(this.isVertical){
				this.$bmo_the_gallery_thumb_area.css('width',thumb_width+'px').css('height',gallery_height+'px');
				this.manageClones(thumb_width,gallery_height);
			}else{
				this.$bmo_the_gallery_thumb_area.css('width',gallery_width+'px').css('height',thumb_height+'px');
				this.manageClones(gallery_width,thumb_height);
			}
			this.$bmo_the_gallery_thumbs.find('div.bmo_the_gallery_image').each(function(){
				$(this).css('width',thumb_width+'px').css('height',thumb_height+'px').css('line-height',thumb_height+'px');
				if($.browser.mozilla) $(this).css('line-height',(thumb_height-16)+'px'); //FF bug, todo beobachten und evtl. ändern.
				$(this).find('a').css('line-height',thumb_height+'px');
				if($.browser.mozilla) $(this).find('a').css('line-height',(thumb_height-16)+'px'); //FF bug, todo beobachten und evtl. ändern.
				thumb_width_withBMP  = $(this).outerWidth(true); //mit padding border und margin
				thumb_height_withBMP = $(this).outerHeight(true);
				numOfThumbs++;
			});
			if(this.isVertical){
				this.$bmo_the_gallery_thumbs.css('height',(thumb_height_withBMP * numOfThumbs)+'px');
				this.$bmo_the_gallery_thumbs.css('width', thumb_width + 'px');
			}else{
				this.$bmo_the_gallery_thumbs.css('width',(thumb_width_withBMP * numOfThumbs)+'px');
				this.$bmo_the_gallery_thumbs.css('height',thumb_height +'px');
			}
			if(this.gallery.options.sG_images){
				if(this.gallery.options.sG_responsive){
					if(this.isVertical){
						gallery_width = this.$gallery_obj.width()-this.$bmo_the_gallery_thumb_area.outerWidth(true)-1;//falls die größe der thumbs geändert wurde, muss das neu berechnet werden
					}
				}
				var image_width_withBMP = gallery_width;
				this.$bmo_the_gallery_image_area.css('width',gallery_width+'px').css('height',gallery_height+'px');
				this.$bmo_the_gallery_images.find('div.bmo_the_gallery_image').each(function(){
					$(this).css('width',gallery_width+'px').css('height',gallery_height+'px').css('line-height',gallery_height+'px'); 
					image_width_withBMP = $(this).outerWidth(true); //mit padding border und margin
				});
				this.$bmo_the_gallery_images.css('width',((image_width_withBMP * this.gallery.numberOfImgs)+100)+'px').css('height',gallery_height+'px');//+100 einfach so, zur Sicherheit für IE
			}
		}, 
		
		scrollThumbs:function(){//wird per intervall aufgerufen und scrollt thumbs
			if(this.scrollStatus_direction!=null){
				var
				speed	   = Number(this.gallery.options.sG_scrollSpeed),				
				left       = Number((this.$bmo_the_gallery_thumbs.css('left').replace(/[^-\d\.]/g, '')||0)),
				top        = Number((this.$bmo_the_gallery_thumbs.css('top').replace(/[^-\d\.]/g, '') ||0)),
				min_height = this.$bmo_the_gallery_thumb_area.height(),
				min_width  = this.$bmo_the_gallery_thumb_area.width(),
				height     = this.$bmo_the_gallery_thumbs.height(),
				width      = this.$bmo_the_gallery_thumbs.width(),
				newLeft	   = left
				newTop	   = top;
				
				switch(this.scrollStatus_direction) {
					case 'up':
						 if (this.gallery.options.sG_loopThumbs){
							if(top >= 0) {top = -1 * (height/(this.numOfClones+1));}
						 }else{
							 if(top >= 0)
							 	newTop = 0;
						 }
						  newTop = top + speed;
					break;
					case 'down':
						 if (this.gallery.options.sG_loopThumbs){
							 if(top <= -1 * (height/(this.numOfClones+1))) {top = 0;}
						 }else{
							 if(top <= -1 * (height-min_height))
								speed = 0;
						 }
						 newTop = top - speed;
					break;
					case 'left':
						 if (this.gallery.options.sG_loopThumbs){
							 if(left >= 0) {left = -1 * (width/(this.numOfClones+1));}
						 }else{
							if(left >= 0)
								speed = 0;
						 }
						 newLeft = left + speed;
					break;
					case 'right':
						 if (this.gallery.options.sG_loopThumbs){
							 if(left <= -1 * (width/(this.numOfClones+1))) {left = 0;}
						 }else{
							 if(left <= -1 * (width-min_width))
							 	speed = 0;
						 }
						 newLeft = left - speed;
					break;
				 }
				 
				 this.$bmo_the_gallery_thumbs.css('top', newTop  + 'px');
				 this.$bmo_the_gallery_thumbs.css('left',newLeft + 'px');
				 
			}
		},
		
		manageClones:function(new_area_width,new_area_height){//prüfe ob clones erstellt werden müssen
			if(this.gallery.options.sG_loopThumbs){
				//delete clones
				this.$bmo_the_gallery_thumbs.children().each(function(){
					if($(this).data('isClone')==true){
						$(this).remove();
					}
				});
				var clone_Source = this.$bmo_the_gallery_thumbs.children(); //alle die keine clone sind
				//create clones
				this.numOfClones=1;
				if(this.isVertical){
					var all_thumbs_height = 0;
					clone_Source.each(function(){
						all_thumbs_height+=$(this).outerHeight(true);
					});
					this.numOfClones = Math.max(Math.ceil(new_area_height/all_thumbs_height),1);
				}else{
					var all_thumbs_width = 0;
					clone_Source.each(function(){
						all_thumbs_width+=$(this).outerWidth(true);
					});
					this.numOfClones = Math.max(Math.ceil(new_area_width/all_thumbs_width),1);
				}
				for(var clone=1;clone<=this.numOfClones;clone++){		
					clone_Source.clone(true,true).data('isClone',true).appendTo(this.$bmo_the_gallery_thumbs);	
				}
				//console.log(this.numOfClones);
			}
		}
	};
	
})(jQuery);