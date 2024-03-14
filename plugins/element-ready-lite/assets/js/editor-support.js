;(function (elementor, $, window) {
	"use strict";
    
	function addCustomCss(css, view) {
		
		var model = view.getEditModel(),
			customCSS = model.get('settings').get('er_custom_css');
			
		if (customCSS) {
			css += customCSS.replace(/selector/g, '.elementor-element.elementor-element-' + view.model.id);
		}
		return css;
	}

	function addPageCustomCss() {
		var customCSS = elementor.settings.page.model.get('er_custom_css');
	    console.log(customCSS);
		if (customCSS) {
			customCSS = customCSS.replace(/selector/g, elementor.config.settings.page.cssWrapperSelector);
			elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
		}
	}

	elementor.hooks.addFilter('editor/style/styleText', addCustomCss);
	elementor.settings.page.model.on('change', addPageCustomCss);
	elementor.on('preview:loaded', addPageCustomCss);
	elementor.settings.page.addChangeCallback( 'er_custom_css', addPageCustomCss );

	
})(elementor, jQuery, window);