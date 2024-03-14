(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 */
	$(
		function() {

			// Configuring OwlCarousel
			$( '.feat-images-container' ).owlCarousel(
				{

					nav     : true,
					navText : [
					"<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"
					],
					items   : 1,
					autoplay: false,
					slideSpeed: 300,
					paginationSpeed: 400,
					center  : true,
					loop    : false,
					dots	: false,
					// autoHeight:true

				}
			);

			// Handling Ingredients Checked & Progress Bar
			$( 'input:checkbox' ).removeAttr( 'checked' );

			$( '.ingredients_checkcounter' ).click(
				function() {

					var count      = 0;
					var checkboxes = 0;
					var checked    = $( this ).is( ":checked" );

					var post          = $( this ).data( 'post' );
					var checked_ingre = $( this ).data( 'ingre' );

					if (checked) {
						  $( this ).siblings( "#ingredient_" + checked_ingre ).css( 'text-decoration','line-through' );
					}

					if ( ! checked) {
						 $( this ).siblings( "#ingredient_" + checked_ingre ).css( "text-decoration", "none" );
					}

					checkboxes = $( 'input:checkbox.ingredients_checkcounter[data-post=' + post + ']' ).length;

					count = $( 'input:checkbox.ingredients_checkcounter[data-post=' + post + ']:checked' ).length;

					$( '.ingredient_checked[data-post=' + post + ']' ).text( count );

					var percentage = parseInt( ((count / checkboxes) * 100),10 );
					$( '.ingredient-progressbar-bar[data-post=' + post + ']' ).progressbar(
						{
							value: percentage
						}
					);

				}
			);

			// Handling Instructions Checked & Progress Bar

			$( '.instructions_checkcounter' ).click(
				function() {

					var index                  = 0;
					var instruction_checkboxes = 0;
					var checked                = $( this ).is( ':checked' );

					var post          = $( this ).data( 'post' );
					var checked_index = $( this ).data( 'count' );

					if (checked) {
						  $( this ).siblings( "#step_" + checked_index ).css( 'text-decoration','line-through' );
					}

					if ( ! checked) {
						 $( this ).siblings( "#step_" + checked_index ).css( 'text-decoration','none' );
					}

					instruction_checkboxes = $( 'input:checkbox.instructions_checkcounter[data-post=' + post + ']' ).length;

					index = $( 'input:checkbox.instructions_checkcounter[data-post=' + post + ']:checked' ).length;

					$( '.instructions_checked[data-post=' + post + ']' ).text( index );

					var percentage = parseInt( ((index / instruction_checkboxes) * 100),10 );
					$( '.instruction-progressbar-bar[data-post=' + post + ']' ).progressbar(
						{
							value: percentage
						}
					);

				}
			);

		}
	);

	 /* When the window is loaded:
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

})( jQuery );
