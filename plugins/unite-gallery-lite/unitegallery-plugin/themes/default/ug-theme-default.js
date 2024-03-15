
if(typeof g_ugFunctions != "undefined")
	g_ugFunctions.registerTheme("default");
else 
	jQuery(document).ready(function(){g_ugFunctions.registerTheme("default")});


/**
 * Default gallery theme
 */
function UGTheme_default(){

	var t = this;
	var g_gallery = new UniteGalleryMain(), g_objGallery, g_objects, g_objWrapper; 
	var g_objButtonFullscreen, g_objButtonPlay, g_objButtonHidePanel;
	var g_objSlider, g_objPanel, g_objStripPanel, g_objTextPanel;
	var g_functions = new UGFunctions();
	
	//theme options
	var g_options = {
			theme_load_slider:true,					//this option for debugging only
			theme_load_panel:true,					//this option for debugging only
			theme_enable_fullscreen_button: true,	//show, hide the theme fullscreen button. The position in the theme is constant
			theme_enable_play_button: true,			//show, hide the theme play button. The position in the theme is constant
			theme_enable_hidepanel_button: true,	//show, hide the hidepanel button
			theme_enable_text_panel: true,			//enable the panel text panel. 
			
			theme_text_padding_left: 20,			//left padding of the text in the textpanel
			theme_text_padding_right: 5,			//right paddin of the text in the textpanel
			theme_text_align: "left",				//left, center, right - the align of the text in the textpanel
			theme_text_type: "description",			//title, description, both - text that will be shown on the text panel, title or description or both
			
			theme_hide_panel_under_width: 480,		//hide panel under certain browser width, if null, don't hide
			
			strippanel_enable_handle : false, //hide strippanel tip in this theme
	};
	
	
	//default item options:
	var g_defaults = {
		
		//slider options:
		slider_controls_always_on: true,
		slider_zoompanel_align_vert: "top",
		slider_zoompanel_offset_vert: 12,
		
		//textpanel options: 
		slider_textpanel_padding_top: 0,
		slider_textpanel_enable_title: false,
		slider_textpanel_enable_description: true,
		slider_vertical_scroll_ondrag: true,
		
		//strippanel options
		strippanel_background_color:"#232323",
		strippanel_padding_top:10
	};
	
		
	//options that could not be changed by user
	var g_mustOptions = {
		
		slider_enable_text_panel: false,
		slider_enable_play_button:false,
		slider_enable_fullscreen_button: false,
		
		//text panel options
		slider_enable_text_panel: false,		
		slider_textpanel_height: 50,
		slider_textpanel_align:"top",
	};
	
	
	var g_temp = {
		isPanelHidden: false
	};
	
	
	/**
	 * init the theme
	 */
	function initTheme(gallery, customOptions){
		
		g_gallery = gallery;
		
		g_options = jQuery.extend(g_options, g_defaults);
		g_options = jQuery.extend(g_options, customOptions);
		g_options = jQuery.extend(g_options, g_mustOptions);
		
		modifyOptions();
		
		//set gallery options
		g_gallery.setOptions(g_options);
		
		//include gallery elements
		if(g_options.theme_load_panel == true){
			g_objStripPanel = new UGStripPanel();
			g_objStripPanel.init(gallery, g_options);
		}
		
		if(g_options.theme_load_slider == true)
			g_gallery.initSlider(g_options);
		
		g_objects = gallery.getObjects();
				
		//get some objects for local use
		g_objGallery = jQuery(gallery);		
		g_objWrapper = g_objects.g_objWrapper;
		
		if(g_options.theme_load_slider == true)
			g_objSlider = g_objects.g_objSlider;
		
		//init text panel
		if(g_options.theme_enable_text_panel == true){
			g_objTextPanel = new UGTextPanel();
			g_objTextPanel.init(g_gallery, g_options, "slider");
		}
		
	}
	
	
	/**
	 * run the theme
	 */
	function runTheme(){
		
		setHtml();
		
		initAndPlaceElements();
				
		initEvents();
	}
	
	
	/**
	 * modify some options before implimenting
	 */
	function modifyOptions(){
		
		var moreOptions = {
				slider_textpanel_css_title:{},						//additional css of the title
				slider_textpanel_css_description:{}					//additional css of the description
		};
		
		g_options = jQuery.extend(moreOptions, g_options);
		
		g_options.slider_textpanel_css_title["text-align"] = g_options.theme_text_align;
		g_options.slider_textpanel_css_description["text-align"] = g_options.theme_text_align;
		
		switch(g_options.theme_text_type){
			case "title":
				g_options.slider_textpanel_enable_title = true;
				g_options.slider_textpanel_enable_description = false;				
			break;
			case "both":
				g_options.slider_textpanel_enable_title = true;
				g_options.slider_textpanel_enable_description = true;
			break;
			default:
			case "description":		//the description is the default
		}
				
	}
	

	
	/**
	 * set gallery html elements
	 */
	function setHtml(){
		
		//add html elements
		g_objWrapper.addClass("ug-theme-default");
		
		var htmlAdd = "";
		
		//add panel
		htmlAdd += "<div class='ug-theme-panel'>";
		
		var classButtonFullscreen = 'ug-default-button-fullscreen';
		var classButtonPlay = 'ug-default-button-play';
		var svgButtonPlay = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16"><g transform="translate(0, 0)"><path fill="#ffffff" d="M14,7.999c0-0.326-0.159-0.632-0.427-0.819l-10-7C3.269-0.034,2.869-0.058,2.538,0.112C2.207,0.285,2,0.626,2,0.999v14.001c0,0.373,0.207,0.715,0.538,0.887c0.331,0.17,0.73,0.146,1.035-0.068l10-7C13.841,8.633,14,8.327,14,8.001C14,8,14,8,14,7.999C14,8,14,8,14,7.999z"></path></g></svg>';
		var classCaptureButtonFullscreen = '.ug-default-button-fullscreen';
		var svgFullscreenButton = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_23_20)"><path d="M9.86046 1.41395L11.6837 3.23721L9.86046 5.06047C9.56279 5.35814 9.56279 5.80465 9.86046 6.10233C10.1581 6.4 10.6046 6.4 10.9023 6.10233L12.7256 4.27907L14.5488 6.10233C14.6977 6.25116 14.9209 6.25116 15.0698 6.10233C15.1442 6.02791 15.1814 5.95349 15.1814 5.87907L16 0.409302C16.0372 0.186047 15.8512 0 15.6651 0C15.6279 0 15.6279 0 15.5907 0L10.0465 0.781395C9.86046 0.818605 9.71163 1.00465 9.74884 1.1907C9.74884 1.30233 9.78604 1.37674 9.86046 1.41395Z" fill="white"/><path d="M15.2186 10.0465C15.1814 9.86046 14.9954 9.71163 14.8093 9.74884C14.7349 9.74884 14.6605 9.78604 14.5861 9.86046L12.7628 11.6837L10.9396 9.86046C10.6419 9.56279 10.1954 9.56279 9.89769 9.86046C9.60002 10.1581 9.60002 10.6046 9.89769 10.9023L11.7209 12.7256L9.89769 14.5488C9.74886 14.6977 9.74886 14.9209 9.89769 15.0698C9.97211 15.1442 10.0465 15.1814 10.121 15.1814L15.5907 16C15.7768 16.0372 16 15.8884 16 15.7023C16 15.6651 16 15.6279 16 15.5907L15.2186 10.0465Z" fill="white"/><path d="M6.17675 14.5861L4.35349 12.7628L6.17675 10.9396C6.47442 10.6419 6.47442 10.1954 6.17675 9.89769C5.87907 9.60002 5.43256 9.60002 5.13489 9.89769L3.31163 11.7209L1.48837 9.89769C1.33954 9.74886 1.11628 9.74886 0.967444 9.89769C0.893026 9.97211 0.855817 10.0465 0.855817 10.121L2.64684e-06 15.5907C-0.0372067 15.7768 0.111631 16 0.297677 16C0.372096 16 0.372096 16 0.409305 16L5.95349 15.2186C6.13954 15.1814 6.28837 14.9954 6.25117 14.8093C6.25117 14.6977 6.21396 14.6233 6.17675 14.5861Z" fill="white"/><path d="M5.09767 6.17677C5.39535 6.47444 5.84186 6.47444 6.13953 6.17677C6.43721 5.87909 6.43721 5.43258 6.13953 5.13491L4.31628 3.31165L6.13953 1.48839C6.28837 1.33956 6.28837 1.1163 6.13953 0.967464C6.06512 0.893045 5.9907 0.855836 5.91628 0.855836L0.409302 2.21019e-05C0.223256 -0.0371872 0.0372093 0.11165 0 0.334906C0 0.372115 0 0.372115 0 0.409324L0.781395 5.95351C0.818605 6.13956 1.00465 6.28839 1.1907 6.25118C1.26512 6.25118 1.33953 6.21398 1.41395 6.13956L3.23721 4.3163L5.09767 6.17677Z" fill="white"/></g><defs><clipPath id="clip0_23_20"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>';
		var classCaptureButtonPlay = '.ug-default-button-play';
		
		
		if(!g_objTextPanel){	//take the buttons from default theme
			classButtonFullscreen = 'ug-default-button-fullscreen-single';
			classButtonPlay = 'ug-default-button-play-single';
			classCaptureButtonFullscreen = '.ug-default-button-fullscreen-single';
			classCaptureButtonPlay = '.ug-default-button-play-single';
		}
		
		//add fullscreen button to the panel
		if(g_options.theme_enable_fullscreen_button == true)
			htmlAdd += "<div class='"+classButtonFullscreen+"'>" + svgFullscreenButton + "</div>";
		
		//add play button to the panel
		if(g_options.theme_enable_play_button == true)
			htmlAdd += "<div class='"+classButtonPlay+"'>" + svgButtonPlay + "</div>";
		
		//add hide panel button
		var svgHidePanel = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="12px" viewBox="0 0 12 12"><g stroke-width="2" transform="translate(0, 0)"><polyline points="0.5 3.5 6 9.5 11.5 3.5" fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></polyline></g></svg>';

		if(g_options.theme_enable_hidepanel_button)
			htmlAdd += "<div class='ug-default-button-hidepanel'><div class='ug-default-button-hidepanel-bg'></div> <div class='ug-default-button-hidepanel-tip'>" + svgHidePanel + "</div></div>";
		
		htmlAdd += "</div>";
		
		g_objWrapper.append(htmlAdd);
		
		//set elements
		g_objPanel = g_objWrapper.children(".ug-theme-panel");
		
		if(g_options.theme_enable_fullscreen_button == true)
			g_objButtonFullscreen = g_objPanel.children(classCaptureButtonFullscreen);
		
		if(g_options.theme_enable_play_button == true)
			g_objButtonPlay = g_objPanel.children(classCaptureButtonPlay);

		if(g_options.theme_enable_hidepanel_button == true)
			g_objButtonHidePanel = g_objPanel.children(".ug-default-button-hidepanel");
		
		//set html strip panel to the panel
		g_objStripPanel.setHtml(g_objPanel);
		
		//set text panel html to the panel
		if(g_objTextPanel)
			g_objTextPanel.appendHTML(g_objPanel);
		
		//set slider html
		if(g_objSlider)
			g_objSlider.setHtml();
		
	}
	
	
	/**
	 * init all the theme's elements and set them to their places 
	 * according gallery's dimentions.
	 * this function should work on resize too.
	 */
	function initAndPlaceElements(){
		
		//create and place thumbs panel:
		if(g_options.theme_load_panel){
			initPanel();
			placePanel();
		}
		
		//place the slider
		if(g_objSlider){	
			placeSlider();
			g_objSlider.run();
		}
		
	}
	
	
	/**
	 * init size of the thumbs panel
	 */
	function initPanel(){
		
		var objGallerySize = g_gallery.getSize();
		var galleryWidth = objGallerySize.width;	
		
		//init srip panel width
		g_objStripPanel.setOrientation("bottom");
		g_objStripPanel.setWidth(galleryWidth);
		g_objStripPanel.run();
		
		//set panel size
		var objStripPanelSize = g_objStripPanel.getSize();		
		var panelHeight = objStripPanelSize.height;
		
		if(g_objTextPanel){
			panelHeight += g_mustOptions.slider_textpanel_height;
			
			if(g_objButtonHidePanel){
				var hideButtonHeight = g_objButtonHidePanel.outerHeight();
				panelHeight += hideButtonHeight;
			}		
		}
		else{	
			var maxButtonsHeight = 0;
			
			if(g_objButtonHidePanel)
				maxButtonsHeight = Math.max(g_objButtonHidePanel.outerHeight(), maxButtonsHeight);
			
			if(g_objButtonFullscreen)
				maxButtonsHeight = Math.max(g_objButtonFullscreen.outerHeight(), maxButtonsHeight);
			
			if(g_objButtonPlay)
				maxButtonsHeight = Math.max(g_objButtonPlay.outerHeight(), maxButtonsHeight);
			
			panelHeight += maxButtonsHeight;
		
		}
		
		g_functions.setElementSize(g_objPanel, galleryWidth, panelHeight);
		
		//position strip panel
		var stripPanelElement = g_objStripPanel.getElement();
			g_functions.placeElement(stripPanelElement, "left", "bottom");
		
		//init hide panel button
		if(g_objButtonHidePanel){
			var buttonTip = g_objButtonHidePanel.children(".ug-default-button-hidepanel-tip");
			g_functions.placeElement(buttonTip, "center", "middle");
			
			//set opacity and bg color from the text panel			
			if(g_objTextPanel){				
				var objHideButtonBG = g_objButtonHidePanel.children(".ug-default-button-hidepanel-bg");
				
				var hidePanelOpacity = g_objTextPanel.getOption("textpanel_bg_opacity");				
				objHideButtonBG.fadeTo(0, hidePanelOpacity);

				var bgColor = g_objTextPanel.getOption("textpanel_bg_color");				
				objHideButtonBG.css({"background-color":bgColor});
			}
			
		}
		
		//position buttons on the text panel:
		var paddingPlayButton = 0;
		var panelButtonsOffsetY = 0;
		if(g_objButtonHidePanel){
			panelButtonsOffsetY = hideButtonHeight;
		}
		
		if(g_objButtonFullscreen){
			g_functions.placeElement(g_objButtonFullscreen, "right", "top",0 , panelButtonsOffsetY);
			paddingPlayButton = g_objButtonFullscreen.outerWidth();
		}
		
		if(g_objButtonPlay){
			var buttonPlayOffsetY = panelButtonsOffsetY;
			if(!g_objTextPanel)
				buttonPlayOffsetY++; 
				
			g_functions.placeElement(g_objButtonPlay, "right", "top", paddingPlayButton, buttonPlayOffsetY);			
			paddingPlayButton += g_objButtonPlay.outerWidth();
		}
		
		//run the text panel
		if(g_objTextPanel){
			
			var textPanelOptions = {};
			textPanelOptions.slider_textpanel_padding_right = g_options.theme_text_padding_right + paddingPlayButton;
			textPanelOptions.slider_textpanel_padding_left = g_options.theme_text_padding_left;					
			
			if(g_objButtonHidePanel){
				textPanelOptions.slider_textpanel_margin = hideButtonHeight;
			}
			
			g_objTextPanel.setOptions(textPanelOptions);
			
			g_objTextPanel.positionPanel();			
			g_objTextPanel.run();
		}
		
		//place hide panel button
		if(g_objButtonHidePanel){
						
			if(g_objTextPanel)		//place at the beginning of hte panel
				g_functions.placeElement(g_objButtonHidePanel,"left", "top");
			
			else{		//place above the strip panel
				var stripPanelHeight = stripPanelElement.outerHeight();
				g_functions.placeElement(g_objButtonHidePanel,"left", "bottom", 0, stripPanelHeight);
			}
		}
		
	}
	
	
	/**
	 * place thumbs panel according the settings
	 */
	function placePanel(){
		
		if(g_temp.isPanelHidden || isPanelNeedToHide() == true){
			
			//place panel hidden			
			var newPanelPosY = getHiddenPanelPosition();
			g_functions.placeElement(g_objPanel, 0, newPanelPosY);
			g_temp.isPanelHidden = true;
		
		}else		//place panel normal
			g_functions.placeElement(g_objPanel, 0, "bottom");
	
		
	} 
	
	
	/**
	 * place the slider according the thumbs panel size and position
	 */
	function placeSlider(){
		
		 var sliderTop = 0;
		 var sliderLeft = 0;
		 var galleryHeight = g_gallery.getHeight();
		 var sliderHeight = galleryHeight;
		 
		 if(g_objStripPanel && isPanelHidden() == false){
			 var panelSize = g_objStripPanel.getSize();
			 sliderHeight = galleryHeight - panelSize.height;
		 }
		 
		 var sliderWidth = g_gallery.getWidth();
		 
		 //set parent container the panel
		 g_objSlider.setSize(sliderWidth, sliderHeight);
		 g_objSlider.setPosition(sliderLeft, sliderTop);		
	}
	
	
	/**
	 * check if need to hide the panel according the options.
	 */
	function isPanelNeedToHide(){
		
		if(!g_options.theme_hide_panel_under_width)
			return(false);
		
		var windowWidth = jQuery(window).width();
		var hidePanelValue = g_options.theme_hide_panel_under_width;
		
		if(windowWidth <= hidePanelValue)
			return(true);
			
		return(false);
	}
	
	/**
	 * check if need to hide or show panel according the theme_hide_panel_under_width option
	 */
	function checkHidePanel(){
		
		//check hide panel:
		if(!g_options.theme_hide_panel_under_width)
			return(false);
		
			var needToHide = isPanelNeedToHide();
			
			if(needToHide == true)
				hidePanel(true);
			else
				showPanel(true);
	}
	
	
	/**
	 * on gallery size change - resize the theme.
	 */
	function onSizeChange(){
		
		initAndPlaceElements();
		
		checkHidePanel();
	}
	
	
	/**
	 * get if the panel is hidden
	 */
	function isPanelHidden(){
		
		return(g_temp.isPanelHidden);
	}
	
	
	/**
	 * place panel with some animation
	 */
	function placePanelAnimation(panelY, functionOnComplete){
		
		var objCss  = {top: panelY + "px"};
		
		g_objPanel.stop(true).animate(objCss ,{
			duration: 300,
			easing: "easeInOutQuad",
			queue: false,
			complete: function(){
				if(functionOnComplete)
					functionOnComplete();
			}
		});
		
	}

	
	/**
	 * get position of the hidden panel
	 */
	function getHiddenPanelPosition(){
		
		var galleryHeight = g_objWrapper.height();
		var newPanelPosY = galleryHeight;
		if(g_objButtonHidePanel){
			var objButtonSize = g_functions.getElementSize(g_objButtonHidePanel);
			newPanelPosY -= objButtonSize.bottom;
		}
		
		return(newPanelPosY);
	}
	
	
	/**
	 * hide the panel
	 */
	function hidePanel(noAnimation){
		
		if(!noAnimation)
			var noAnimation = false;
		
		if(isPanelHidden() == true)
			return(false);
				
		var newPanelPosY = getHiddenPanelPosition();
		
		if(noAnimation == true)
			g_functions.placeElement(g_objPanel, 0, newPanelPosY);
		else
			placePanelAnimation(newPanelPosY, placeSlider); 
		
		if(g_objButtonHidePanel)
			g_objButtonHidePanel.addClass("ug-button-hidden-mode");
		
		g_temp.isPanelHidden = true;
		
	}
	
	
	/**
	 * show the panel
	 */
	function showPanel(noAnimation){
		
		if(!noAnimation)
			var noAnimation = false;
		
		if(isPanelHidden() == false)
			return(false);
		
		var galleryHeight = g_objWrapper.height();
		var panelHeight = g_objPanel.outerHeight();
		
		var newPanelPosY = galleryHeight - panelHeight;
		
		if(noAnimation == true)
			g_functions.placeElement(g_objPanel, 0, newPanelPosY);
		else
			placePanelAnimation(newPanelPosY, placeSlider);
		
		if(g_objButtonHidePanel)
			g_objButtonHidePanel.removeClass("ug-button-hidden-mode");
		
		g_temp.isPanelHidden = false;
	}
	
	
	/**
	 * on hide panel click
	 */
	function onHidePanelClick(event){
		
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		if(g_functions.validateClickTouchstartEvent(event.type) == false)
			return(true);
		
		if(isPanelHidden() == true)
			showPanel();
		else
			hidePanel();
	}

	/**
	 * before items request: hide items, show preloader
	 */
	function onBeforeReqestItems(){
	
		g_gallery.showDisabledOverlay();
	
	}
	
	
	/**
	 * init buttons functionality and events
	 */
	function initEvents(){
						
		g_objGallery.on(g_gallery.events.SIZE_CHANGE,onSizeChange);
		g_objGallery.on(g_gallery.events.GALLERY_BEFORE_REQUEST_ITEMS, onBeforeReqestItems);
		
		//set the panel buttons
		if(g_objButtonPlay){
			g_functions.addClassOnHover(g_objButtonPlay, "ug-button-hover");
			g_gallery.setPlayButton(g_objButtonPlay);
		}
		
		//init fullscreen button
		if(g_objButtonFullscreen){
			g_functions.addClassOnHover(g_objButtonFullscreen, "ug-button-hover");
			g_gallery.setFullScreenToggleButton(g_objButtonFullscreen);
		}
		
		//init hide panel button
		if(g_objButtonHidePanel){
			g_functions.setButtonMobileReady(g_objButtonHidePanel);
			g_functions.addClassOnHover(g_objButtonHidePanel, "ug-button-hover");
			g_objButtonHidePanel.on("click touchstart", onHidePanelClick);
		}
		
		//on gallery media player events, bring the element to front
		g_objGallery.on(g_gallery.events.SLIDER_ACTION_START, function(){
			
			//set slider to front
			g_objPanel.css("z-index","1");
			g_objSlider.getElement().css("z-index","11");
		});
		
		g_objGallery.on(g_gallery.events.SLIDER_ACTION_END, function(){
			
			//set the panel to front
			g_objPanel.css("z-index","11");
			g_objSlider.getElement().css("z-index","1");
		});
		
	}
	
	/**
	 * destroy the gallery events and objects
	 */
	this.destroy = function(){
		
		g_objGallery.off(g_gallery.events.SIZE_CHANGE);
		g_objGallery.off(g_gallery.events.GALLERY_BEFORE_REQUEST_ITEMS);
		
		//set the panel buttons
		if(g_objButtonPlay)
			g_gallery.destroyPlayButton(g_objButtonPlay);
		
		//init fullscreen button
		if(g_objButtonFullscreen)
			g_gallery.destroyFullscreenButton(g_objButtonFullscreen);
			
		//init hide panel button
		if(g_objButtonHidePanel)
			g_functions.destroyButton(g_objButtonHidePanel);
		
		g_objGallery.off(g_gallery.events.SLIDER_ACTION_START);
		g_objGallery.off(g_gallery.events.SLIDER_ACTION_END);
		
		if(g_objSlider)
			g_objSlider.destroy();
		
		if(g_objStripPanel)
			g_objStripPanel.destroy();
		
		if(g_objTextPanel)
			g_objTextPanel.destroy();
		
	}
	
	
	/**
	 * run the theme setting
	 */
	this.run = function(){
		
		runTheme();
	}
	
	
	/**
	 * init 
	 */
	this.init = function(gallery, customOptions){
		initTheme(gallery, customOptions);
	}
	
}
