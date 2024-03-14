(function ($) {
    "use strict";

	function elementor_strecth_column() {
		var $column, $container;
		if (jQuery('.elementor-stretch-column-left').length > 0) {
			
			jQuery('.elementor-stretch-column-left').each(function () {

				$column = jQuery(this);
				$container = $column.parents('.elementor-container');

				if ($container.length === 0) {
					return;
				}

				$container = Math.ceil($container.offset().left);
				var tempUpdated = $container;
				$column.find('div:eq(0)').css({'margin-left': -tempUpdated + "px", 'width': 'calc( 100% + ' + tempUpdated + 'px )'});

			});
			
		}
		
		if (jQuery('.elementor-stretch-column-right').length > 0) {
			
			jQuery('.elementor-stretch-column-right').each(function () {

				$column = jQuery(this);
				$container = $column.parents('.elementor-container');

				if ($container.length === 0) {
					return;
				}

				$container = Math.ceil($container.offset().left);
				var tempUpdated = $container;
				$column.find('div:eq(0)').css({'margin-right': -tempUpdated + "px", 'width': 'calc( 100% + ' + tempUpdated + 'px )'});

			});

		}
	}
	
	$(document).ready(elementor_strecth_column);
	$(window).resize(elementor_strecth_column);
})(jQuery);