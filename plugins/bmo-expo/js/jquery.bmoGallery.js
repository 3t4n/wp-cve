(function($){
	/**
	 * @param options array
	 **/
	var BMoGallery = function(gallery_obj,options){//definiere die Attribute
	  
		  this.$gallery_obj = $(gallery_obj); //das initalisierte HTML Obj
		  this.gallery_js_obj;        		  //das zugehörige initialisierte JS Objekt des Gallerytypes
		  this.$bmo_the_gallery_thumb_area;
		  this.$bmo_the_gallery_thumbs;
		  this.currentImg = 0;
		  this.numberOfImgs = 0;
		  
		  this.options = $.extend({/*...weitere optionen -> key:'default'*/
			   
			  duration: 			'slow',				//default animation duration
			  gallery_width:		600,				//default width
			  gallery_height:		400,				//default height
			  thumbs_width:			100,				//default thumb width
			  thumbs_height:		100,				//default thumb height
			  /*...*/
			  
			  /*options für scrollgallery*/
			  sG_start:				1,					//erstes Bild, startet bei 1
			  sG_caption:			1,					//show captions
			  sG_thumbPosition:		'left',				//top, bottom, none, left, right
			  sG_images:			true,				//show images?
			  sG_loop:				true,				//beim letzten Bild wieder nach vorne springen?
			  sG_loopThumbs:		true,				//beim letzten Thumb wieder nach vorne springen?
			  sG_clickable:			true,				//Clickable images
			  sG_opacity:			40,					//opacity of thumbs
			  sG_area:				200,				//px Breite des Bereichs in der Mitte der thumb area in dem nicht gescrollt wird, wenn Maus da, wird dort nicht gescrollt.
			  sG_scrollSpeed:		2,					//scroll speed, sollte >0 sein
			  sG_autoScroll:		false,				//autoscroll, mind. mehr thumbs als breite
			  sG_aS_stopOnOver:		true,				//stoppe autoscroll bei over
			  sG_diashowDelay:		null,				//time in seconds to activate diashow, default null
			  sG_followImages:		true,				//thumbs follow images
			  sG_responsive:		true,				//make width responsive. Gallery width is relative to parent elements width
			  
			  /*options für scrollLightboxGallery*/
			  slG_caption:			1,					//show captions
			  slG_vertical:			false,
			  slG_loopThumbs:		true,				//beim letzten Thumb wieder nach vorne springen?
			  slG_opacity:			40,					//opacity of thumbs
			  slG_area:				200,				//px Breite des Bereichs in der Mitte der thumb area in dem nicht gescrollt wird, wenn Maus da, wird dort nicht gescrollt.
			  slG_scrollSpeed:		2,					//scroll speed, sollte >0 sein
			  slG_autoScroll:		false,				//autoscroll, mind. mehr thumbs als breite
			  slG_aS_stopOnOver:	true,				//stoppe autoscroll bei over
			  slG_responsive:		true,				//make width responsive. Gallery width is relative to parent elements width
			  slG_relType:			'lightbox[{id}]',	//rel text, {id} will be replaced by the gallery type and id
			  slG_useLightbox: 		true,				//Open images with the colorbox script by <a href="http://www.jacklmoore.com/" target="_blank">Jack Moore</a>.
			  slG_lightbox_text:	"image {current} of {total}", //Colorbox text: {current} and {total} will be replaced
			  slG_lightbox_opacity: 85,				//Colorbox background opacity
			  slG_lightbox_slideshow: false,			//Use colorbox slideshow?
			  slG_lightbox_speed: 	2500				//Colorbox slideshow delay
			  
			  
			  
		  }, options);
		
		  if(this.options.isInitalized!=true){
			   this.initGallery();
			   this.options.isInitalized = true;
		  }
	};
	  
	  
	BMoGallery.prototype = {
		
		constructor: BMoGallery,
		
		initGallery:function(){ // init alle gallerien
			
			//set variables
			this.$bmo_the_gallery_thumb_area = this.$gallery_obj.find('.bmo_the_gallery_thumb_area');
			this.$bmo_the_gallery_thumbs = this.$bmo_the_gallery_thumb_area.find('.bmo_the_gallery_thumbs');
			this.numberOfImgs = this.$bmo_the_gallery_thumbs.find('.bmo_the_gallery_image').length;
			
			//hide for loading
			this.$gallery_obj.hide();
			
			//load needed scripts
			$(this).bmo_getScript_Helper($.data(document.body,"BMo_Expo_Path")+"js/css_inliner/jquery.css_inliner.js", function() {//$.data(document.body,"BMo_Expo_Path") stores the path, if not it returns ""
				
			//init gallery type
				var type = "noGallery";
				var classarray = this.$gallery_obj.attr('class').split(" ");
				if(classarray.length>1){
					classarray = jQuery.grep(classarray, function(value) {
					  return value != 'bmo_the_gallery';
					});
					type = classarray[0];//nehme erste klasse
				}
				this.$gallery_obj.data('type',type);// jedes Objekt hat in data einige private variablen gesetzt: type - der typ dieser Galerie.
				switch(type){	
					case 'bmo_scrollGallery':
						$(this).bmo_getScript_Helper($.data(document.body,"BMo_Expo_Path")+"js/jquery.bmoGallery.scrollGallery.js",function() {
							this.gallery_js_obj = new BMo_scrollGallery(this);//neue instanz der scrollgallery
							//init structure
							this.makeGalleryHTMLStructure();
							this.setHTMLEvents();//click, close etc.
							this.showGallery();
						}.bind(this));
						
					break;
					case 'bmo_scrollLightboxGallery':
						$(this).bmo_getScript_Helper($.data(document.body,"BMo_Expo_Path")+"js/jquery.bmoGallery.scrollLightboxGallery.js",function() {
							this.gallery_js_obj = new BMo_scrollLightboxGallery(this);//neue instanz der ScrollLightboxGallery
							//init structure
							this.makeGalleryHTMLStructure();
							this.setHTMLEvents();//click, close etc.
							this.showGallery();
						}.bind(this));
					break;
					default: //noGallery
						console.log("Type of Gallery: "+this.$gallery_obj+" not found!");//sonst nix oder so
				}
				
			}.bind(this));
		},
		next:function(){
			if(this.gallery_js_obj.next)
				this.gallery_js_obj.next();
		},
		
		prev:function(){
			if(this.gallery_js_obj.prev)
				this.gallery_js_obj.prev();
		},
		
		goTo:function(index, duration){//duration ist optionaler parameter
			if(this.gallery_js_obj.goTo){
				if(!duration) duration= this.options.duration;
				this.gallery_js_obj.goTo(index, duration);
			}
		},
		
		makeGalleryHTMLStructure:function(){
			if(this.gallery_js_obj.makeGalleryHTMLStructure)
				this.gallery_js_obj.makeGalleryHTMLStructure();
		},
		
		setHTMLEvents:function(){
			if(this.gallery_js_obj.setHTMLEvents)
				this.gallery_js_obj.setHTMLEvents();
		},
		
		showGallery:function(){
			if(this.gallery_js_obj.showGallery)//optionaler Aufruf der showGallery() funktion nur falls this.gallery_js_obj die auch hat. 
				this.gallery_js_obj.showGallery();
			this.$gallery_obj.show();
		}
		//, evtl. weitere globale Funktionen
		
	};
	
	$.fn.bmoGallery = function (option) {//bereitstellen der .bmoGallery(option); möglichkeit
		return this.each(function (index) {
			var $this = $(this),
		    	options = typeof option == 'object' && option;
			new BMoGallery(this, options);
		});
		
		/*old:
		 return this.each(function (index) {
		  var $this = $(this)
			, data = $this.data('bmoGallery')
			, options = typeof option == 'object' && option;
		  if (!data) $this.data('bmoGallery', (data = new BMoGallery(this, index+1 ,options)));
		  if (typeof option == 'string') data[option]();
		});*/
	 };
	 
	 $.fn.bmoGallery.Constructor = BMoGallery;
	
	
	//globale Helper
	/*Get Script Queue*/
	var glob_arr_loadedScripts = [];
	var glob_arr_queue_getScript = [];
	var glob_loadScript_queue_isRunning = false;
	
	$.fn.bmo_getScript_Helper = function(filename,success_callback){	//helper - load scripts only one time
		glob_arr_queue_getScript.push({
			'filename':filename,
			'callback':success_callback
		});
		loadScript_next_in_queue();
	};
	
	var loadScript_next_in_queue = function(){
		if(glob_arr_queue_getScript.length>0){
			if(!glob_loadScript_queue_isRunning){
				glob_loadScript_queue_isRunning = true;
				var queued_obj = glob_arr_queue_getScript.shift();
			
				if(glob_arr_loadedScripts[queued_obj.filename]){
					queued_obj.callback();
					glob_loadScript_queue_isRunning = false;
					loadScript_next_in_queue();
				}else{
					$.getScript(queued_obj.filename).done(function(data, textStatus) {
						glob_arr_loadedScripts[queued_obj.filename] = true;
					    queued_obj.callback();
						glob_loadScript_queue_isRunning = false;
						loadScript_next_in_queue();
					}.bind(this)).fail(function(jqxhr, settings, exception) {
						console.log("Load script:"+jqxhr+" "+settings+" "+exception);
						glob_loadScript_queue_isRunning = false;
						loadScript_next_in_queue();
					});
				}
			}
		}else{
			glob_loadScript_queue_isRunning = false;
		}
	};
	
})(jQuery); 


// .bind(this) Compatibility for old browsers https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/bind?redirectlocale=en-US&redirectslug=JavaScript%2FReference%2FGlobal_Objects%2FFunction%2Fbind
if (!Function.prototype.bind) {
  Function.prototype.bind = function (oThis) {
    if (typeof this !== "function") {
      // closest thing possible to the ECMAScript 5 internal IsCallable function
      throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
    }

    var aArgs = Array.prototype.slice.call(arguments, 1), 
        fToBind = this, 
        fNOP = function () {},
        fBound = function () {
          return fToBind.apply(this instanceof fNOP && oThis
                                 ? this
                                 : oThis,
                               aArgs.concat(Array.prototype.slice.call(arguments)));
        };

    fNOP.prototype = this.prototype;
    fBound.prototype = new fNOP();

    return fBound;
  };
}