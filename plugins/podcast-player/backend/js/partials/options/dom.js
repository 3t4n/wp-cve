class DomManipulation {

	/**
	 * Manage podcast player header elements.
	 * 
	 * @since 3.3
	 * 
	 * @param {string} id Podcast player ID. 
	 */
	constructor() {
		jQuery('#pp-options-module-help .pp-docs-hide').hide();
		jQuery('#pp-options-module-help .pp-docs-title').on('click', function(){
			jQuery(this).toggleClass("pp-toggle");
			jQuery(this).next().slideToggle("fast");
		});

		jQuery('#pp-options-module-toolkit .pp-toolkit-content').hide();
		jQuery('#pp-options-module-toolkit .pp-toolkit-title').on('click', function(){
			jQuery(this).toggleClass("pp-toggle");
			jQuery(this).next().slideToggle("fast");
		});

		jQuery('.pp-hidden-settings').hide();
		jQuery('.pp-hidden-settings-title').on('click', function(){
			jQuery(this).toggleClass("pp-toggle");
			jQuery(this).next().slideToggle("fast");
		});
	}
}

export default DomManipulation;
