(function( $ ) {
	'use strict';

	$(
		function() {

			// configuring math.js

			math.config(
				{
					number: 'number'
				}
			);

			$( ".br_adjust-recipe-servings" ).bind(
				'keyup mouseup',
				function () {
					var new_servings = ($( this ).val());
					new_servings     = math.number( new_servings );
					// console.log('New Servings', new_servings);

					var post = $( this ).data( 'post' );

					var original_servings = $( this ).data( 'original' );
					original_servings     = math.number( original_servings );
					// console.log('Original Servings', original_servings);

					$( '.ingredient_quantity[data-post=' + post + ']' ).each(
						function( index ) {

							var quantity = $( this ).data( 'original' );

							if ( ! isFinite( quantity )) {
								quantity = math.fraction( quantity );
								// console.log('Fractional Quantity', math.format(quantity, {fraction: 'ratio'}));

								var new_quantity = math.eval( (quantity / original_servings) * new_servings );
								// console.log('New Decimal', new_quantity);

								new_quantity = math.fraction( new_quantity );
								// console.log('New Fractional', new_quantity);

								if ((new_quantity.n == new_quantity.d) || (new_quantity.d == 1)) {

									jQuery( this ).text( new_quantity.n );
									// console.log('New Quantity =', jQuery(this).text());

								} else if (new_quantity.n > new_quantity.d) {
									var i           = parseInt( new_quantity.n / new_quantity.d );
									new_quantity.n -= i * new_quantity.d;
									jQuery( this ).text( i + ' ' + ' ' + new_quantity.n + '/' + new_quantity.d );
									// console.log('New Quantity >', jQuery(this).text());
								} else {
									jQuery( this ).text( math.format( new_quantity, {fraction: 'ratio'} ) );
									// console.log('New Quantity', jQuery(this).text());

								}
							} else {
								quantity = math.number( quantity );
								// console.log('Numeric Quantity', quantity);

								var new_quantity = math.eval( new_servings * (quantity / original_servings) );
								new_quantity     = math.format( new_quantity, {fraction: 'decimal', precision: 4} );
								jQuery( this ).text( new_quantity );
								// console.log('New Quantity', jQuery(this).text());

							}

						}
					);

				}
			);

		}
	);

})( jQuery );
