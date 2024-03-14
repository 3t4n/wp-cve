/* Die einzelnen Klassen für Gallerie Typen.
 * Diese haben immer die folgenden Methoden, welche von der oben Klasse BMoGallery aufgerufen werden.
 * next()
 * prev()
 * goTo(index, duration)
 * makeGalleryHTMLStructure
 * setHTMLEvents
 *...
 
 
 * BMo_scrollLightboxGallery requires a lightbox script like fancybox or lightbox
*/
var BMo_scrollLightboxGallery; //global declaraiton

(function($){
	BMo_scrollLightboxGallery = function(parentObj){
		 this.gallery = parentObj;// das Vaterobj der Überklasse
		 this.$gallery_obj = this.gallery.$gallery_obj;//html Obj
		 this.$bmo_the_gallery_thumb_area = this.gallery.$bmo_the_gallery_thumb_area;//html Obj
		 this.$bmo_the_gallery_thumbs = this.gallery.$bmo_the_gallery_thumbs;//html Obj
		 
		 /*spezielle attribute*/
		 this.isVertical = false;
		 this.numOfClones = 0;
		 
		 /*init*/
		 if(this.isInitalized!=true){
			   this.initGallery();
			   this.isInitalized = true;
		 }
	};
	
	BMo_scrollLightboxGallery.prototype = {
		
		constructor: BMo_scrollLightboxGallery,
		
		initGallery:function(){
		},
		
		makeGalleryHTMLStructure:function(){
			  if(this.gallery.options.slG_vertical) {
					this.$gallery_obj.addClass('vertical');
					this.isVertical = true;
			  }
			  //build html und inline css
			  if(this.gallery.options.slG_arrowsThumbs&&!this.gallery.options.slG_autoScroll){
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
			  
			  var that = this;
			  var slG_caption = this.gallery.options.slG_caption;
			  this.$bmo_the_gallery_thumbs.find('.bmo_the_gallery_image').each(function(index){
				  $(this).find('a').attr('rel',that.gallery.options.slG_relType.replace(/{id}/, that.gallery.$gallery_obj.attr('id')));//set rel attribut
				  if(slG_caption==true)
				  	$(this).find('a').attr('title',$(this).find('div.bmo_the_gallery_caption').html());//set title
				  $(this).data('index',index);//set index data
			  });
			  
			  //set styles inline after all html elements are initalized
			  this.$gallery_obj.inlineCSS($.data(document.body,"BMo_Expo_Path"));
			
		},
		
		setHTMLEvents:function(){
			if(this.gallery.options.slG_arrowsThumbs){
				this.$bmo_the_gallery_thumb_area.find('.bmo_the_gallery_thumb_arrows').click(function(e){
					e.preventDefault();
					return false;
				});
			}
			
			var that = this;
			//thumb events
			
			this.$bmo_the_gallery_thumbs.find('a').each(function(){
				$(this).click(function(e){
					that.gallery.goTo($(this).parent('.bmo_the_gallery_image').data('index'));
				}.bind(this));
			});
			
			
			//thumb area scroll events:
			this.scrollStatus_direction = null;
			var scrollerInterval;
			if(!this.gallery.options.slG_autoScroll){ 
				//mouseposition
				this.$bmo_the_gallery_thumb_area.mousemove(function(e){
					var parentOffset = $(this).parent().offset(); 
					var x = e.pageX - parentOffset.left;
					var y = e.pageY - parentOffset.top;
					
					var area = Number(that.gallery.options.slG_area);
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
				this.gallery.options.slG_loopThumbs = true;
				
				scrollerInterval = setInterval(function() {
					that.scrollThumbs();
				},20);
				if(this.gallery.options.slG_aS_stopOnOver){
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
				this.gallery.options.slG_arrowsThumbs = false;
			}
			
			//mobile events
			if(this.gallery.options.slG_enableSwipeMode){
				var is_touch_device = 'ontouchstart' in document.documentElement;
				if(is_touch_device){
					$(this).bmo_getScript_Helper($.data(document.body,"BMo_Expo_Path")+"js/mobile/jquery.hammer.min.js", function() {
						 //thumbs
						 if(!this.gallery.options.slG_autoScroll){ 
						 	this.$bmo_the_gallery_thumb_area.unbind('mousemove');
							this.$bmo_the_gallery_thumb_area.unbind('hover');
							if(that.isVertical)
								this.$bmo_the_gallery_thumb_area.css('overflow-y','auto');//vertical version
							else
								this.$bmo_the_gallery_thumb_area.css('overflow-x','auto');
						 }
						
							
						$(document).on('cbox_open', function(){
							Hammer(document.body).on("swipeleft", $.colorbox.prev);
							Hammer(document.body).on("swiperight", $.colorbox.next);
						});
						$(document).on('cbox_closed', function(){
						    Hammer(document.body).off("swipeleft", $.colorbox.prev);
						    Hammer(document.body).off("swiperight", $.colorbox.next);
						});
						
						 
					}.bind(this));
				}
			}
			
			//unbind mousewheel
			this.$bmo_the_gallery_thumb_area.bind("mousewheel", function() {return false;});
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
		
		
		goTo:function(index, duration){//duration ist optional und hat hier keine auswirkung
			this.gallery.currentImg = index;
			this.setThumbs(index);
		},
		
		showGallery:function(){
			
			this.initScrollLightboxGallery();
			//change css to match options.
			if(this.gallery.options.slG_responsive){
				if(this.isVertical){
					this.resizeGallery(this.gallery.options.thumbs_width);
				}else{
					this.resizeGallery(this.$gallery_obj.width());
				}
			}else{
				this.resizeGallery(this.gallery.options.gallery_width);
			}
			
			//load lightbox if necessary
			if(this.gallery.options.slG_useLightbox){
				$(this).bmo_getScript_Helper($.data(document.body,"BMo_Expo_Path")+"js/colorbox/jquery.colorbox-min.js", function() {
					 //set event on thumbs
					this.$bmo_the_gallery_thumbs.find('.bmo_the_gallery_image a').colorbox({
						maxWidth:"80%",
						maxHeight:"80%",
						fixed:true,
						current: this.gallery.options.slG_lightbox_text.replace("'","").replace("'",""),
						opacity: this.gallery.options.slG_lightbox_opacity/100,
						slideshow: this.gallery.options.slG_lightbox_slideshow,
						slideshowSpeed: this.gallery.options.slG_lightbox_speed
					});
					 
				}.bind(this));
			}
			
		},
		
		initScrollLightboxGallery:function(){
			//scrollGallery spezifisch, nachdem HTMl da ist.
			
			//Thumb opacity
			if(this.gallery.options.slG_opacity<100&&this.gallery.options.slG_opacity>=0){
				this.setThumbOpacity();
			}
			
			//resize on window resize
			if(this.gallery.options.slG_responsive){
				$(window).resize(function() {
					if(this.isVertical){
						this.resizeGallery(this.gallery.options.thumbs_width);
					}else{
						this.resizeGallery(this.$gallery_obj.width());
					}
					this.gallery.goTo(0,20);
				}.bind(this));
			}
		},
		
		
		setThumbs:function(index){//after scroll to img
			this.setThumbOpacity();
		},
		
		setThumbOpacity:function(){
			if(this.gallery.options.slG_opacity<100&&this.gallery.options.slG_opacity>=0){
				var that = this;
				this.$bmo_the_gallery_thumbs.find('.bmo_the_gallery_image').each(function(){
					if($(this).data('index')!=that.gallery.currentImg){
						$(this).find('img').css('opacity',Number(that.gallery.options.slG_opacity)/100);
					}else{
						$(this).find('img').css('opacity',1).addClass('active');
					}
				});
			}
		},
						
		resizeGallery:function(gallery_width){//new width and height
			var thumb_width = thumb_width_withBMP  = this.gallery.options.thumbs_width;
			var thumb_height= thumb_height_withBMP = this.gallery.options.thumbs_height;
			var numOfThumbs = 0;//!=numberOfImgs bei clone
			//change the size
			if(this.isVertical){
				this.$bmo_the_gallery_thumb_area.css('width',thumb_width+'px').css('height',this.gallery.options.gallery_height+'px');
				this.manageClones(thumb_width,this.gallery.options.gallery_height);
				
			}else{
				this.$bmo_the_gallery_thumb_area.css('width',gallery_width+'px').css('height',thumb_height+'px');
				this.manageClones(gallery_width,thumb_height);
			}
			this.$bmo_the_gallery_thumbs.find('div.bmo_the_gallery_image').each(function(){
				$(this).css('width',thumb_width+'px').css('height',thumb_height+'px').css('line-height',thumb_height+'px');
				$(this).find('a').css('line-height',thumb_height+'px');
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
		}, 
		
		scrollThumbs:function(){//wird per intervall aufgerufen und scrollt thumbs
			if(this.scrollStatus_direction!=null){
				var
				speed	   = Number(this.gallery.options.slG_scrollSpeed),				
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
						 if (this.gallery.options.slG_loopThumbs){
							if(top >= 0) {top = -1 * (height/(this.numOfClones+1));}
						 }else{
							 if(top >= 0)
							 	newTop = 0;
						 }
						  newTop = top + speed;
					break;
					case 'down':
						 if (this.gallery.options.slG_loopThumbs){
							 if(top <= -1 * (height/(this.numOfClones+1))) {top = 0;}
						 }else{
							 if(top <= -1 * (height-min_height))
								speed = 0;
						 }
						 newTop = top - speed;
					break;
					case 'left':
						 if (this.gallery.options.slG_loopThumbs){
							 if(left >= 0) {left = -1 * (width/(this.numOfClones+1));}
						 }else{
							if(left >= 0)
								speed = 0;
						 }
						 newLeft = left + speed;
					break;
					case 'right':
						 if (this.gallery.options.slG_loopThumbs){
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
			if(this.gallery.options.slG_loopThumbs){
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
					var $new_clones = clone_Source.clone(true,true).data('isClone',true).appendTo(this.$bmo_the_gallery_thumbs);	
					if(this.gallery.options.slG_useLightbox){//if colorbox, rename rel for clones
						$new_clones.find("a").attr("rel", $new_clones.find("a").attr("rel").replace("]","_"+clone+"]"));
					}
				}
				//console.log(this.numOfClones);
				
				
				
			}
		}
	};
	
})(jQuery); 