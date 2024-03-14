var intel_admin_setup = (function( $ ) {
	'use strict';


	var ths = {};
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function(){
		var $this;
		// check if goal list table exists
		if ($('#goal-list-table').length) {
			$('#goal-add-btn').on('click', ths.goal_add);
		}
	});

	ths.goal_add = function () {
		var i, $row;
		for (i = 1; i <= 20; i++) {
			$row = $('.row-' + i);
			if ($row.hasClass('row-hide')) {
				$row.removeClass('row-hide');
				$row.addClass('row-show');
				break;
			}
		}
		return true;
	}

})( jQuery );
