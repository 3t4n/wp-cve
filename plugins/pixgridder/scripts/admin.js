(function($){

"use strict";

/* $.values: get or set all of the name/value pairs from child input controls   
 * @argument data {array} If included, will populate all child controls.
 * @returns element if data was provided, or array of values if not
*/

var PIXGRIDDER = window.PIXGRIDDER || {};

/********************************
*
*	Navsidebar menu
*
********************************/
PIXGRIDDER.adminNav = function() {
	if ( typeof pagenow == 'undefined' || pagenow=='toplevel_page_pixgridder_admin' )
		return;

	var $form = $('#pixgridder_form'),
		$spinner = $('#spinner'),
		$visible = $('#pixgridder-visible'),
		$button = $('#pixgridder_submit'),
		$thank = $('#pixgridder-thank');

	$form.on('submit',function() {
		var data = $form.serialize();

			$button.text('Wait please...');

			$.post(ajaxurl, data)
				.success(function(html) {
					$visible.slideUp(400).animate({opacity: 0},{queue: false, duration: '400'});
					$thank.fadeIn();
				})
				.error(function() { console.log('no'); });

		return false;
	});
};

PIXGRIDDER.init = function(){
	PIXGRIDDER.adminNav();
};

$(function(){
	PIXGRIDDER.init();
});

})(jQuery);