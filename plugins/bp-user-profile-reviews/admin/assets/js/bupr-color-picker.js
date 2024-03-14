jQuery( document ).ready(
	function(){
		/* call color picker function for wp admin */
		(function (jQuery) {
			jQuery(
				function () {
					jQuery( '.bupr-admin-color-picker' ).wpColorPicker();
				}
			);
		}(jQuery));
	}
);
