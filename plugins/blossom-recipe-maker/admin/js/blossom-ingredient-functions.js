jQuery( document ).ready(
	function($) {

		var ingre = $( '#br_recipe_ingredients tr.br_ingredients span.br_ingredients_delete' ).children().length;

		if (ingre == 1) {
			$( '#br_recipe_ingredients tr.br_ingredients:first' ).find( 'span.br_ingredients_delete' ).hide();
		}

		/**
		 * When Add an ingredient button is clicked, clones the last row elements.
		 Also provides the proper ID and name attributes so that the information can be serialized.
		 */

		$( '#br-add-ingredients' ).on(
			'click',
			function(e){
				e.preventDefault();
				addIngredient();
			}
		);

		$( '#br-add-ingredients-heading' ).on(
			'click',
			function(e){
				e.preventDefault();
				addIngredientHeading();
			}
		);

		$( document ).on(
			'click',
			'.br_ingredients_heading_delete',
			function(event)
			{

					event.preventDefault();
					var heading_delete = confirm( RecipeIngredients.heading_delete_warning );

				if ( heading_delete == true) {
					$( this ).parents( 'tr' ).animate(
						{ opacity: 0 },
						200,
						function() {
							$( this ).remove();

						}
					);

				}

			}
		);

		$( '.br_ingredients_delete' ).on(
			'click',
			function(){

				var ingredient = $( '#br_recipe_ingredients tr.br_ingredients span.br_ingredients_delete' ).children().length;

				if (ingredient != 1) {
					var ingre_delete = confirm( RecipeIngredients.delete_warning );

					if ( ingre_delete == true) {
						jQuery( this ).parents( 'tr' ).remove();
					}

					if (ingredient == 2) {
						$( '#br_recipe_ingredients tr.br_ingredients:first' ).find( 'span.br_ingredients_delete' ).hide();
					}
				}

			}
		);

		$( '#br_recipe_ingredients tbody' ).sortable(
			{
				opacity: 0.6,
				handle: '.br_ingredients_sort_handle'

			}
		);

		function addIngredient()
		{
			var no_ingredients  = jQuery( '#br_recipe_ingredients tr.br_ingredients' ).length;
			var headCount       = jQuery( '#br_recipe_ingredients tr.br_ingredients_heading' ).length;
			var indexCount      = no_ingredients + headCount;
			var last_row        = jQuery( '#br_recipe_ingredients tr:last' );
			var last_ingredient = jQuery( '#br_recipe_ingredients tr.br_ingredients:last' );

			var clone_ingredient = last_ingredient.clone( true );

			clone_ingredient
			.insertAfter( last_row )
			.find( 'input, select' ).val( '' )
			.attr(
				'name',
				function(index, name) {
					return name.replace( /(\d+)/, indexCount );
				}
			)
				.attr(
					'id',
					function(index, id) {
						return id.replace( /(\d+)/, indexCount );
					}
				);

				clone_ingredient.find( 'span.br_ingredients_delete' ).show();

				jQuery( '#br_recipe_ingredients tr.br_ingredients:first' ).find( 'span.br_ingredients_delete' ).show();

				jQuery( '#br_recipe_ingredients tr:last .br_ingredients_quantity' ).focus();

		}

		function addIngredientHeading()
		{
			var inputElement, iInputCount;

			/* First, count the number of input fields that already exist. This is how we're going to
			 * implement the name and ID attributes of the element.
			 */
			ingreCount  = jQuery( '#br_recipe_ingredients tr.br_ingredients' ).length;
			headCount   = jQuery( '#br_recipe_ingredients tr.br_ingredients_heading' ).length;
			iInputCount = ingreCount + headCount;

			var last_row = jQuery( '#br_recipe_ingredients tr:last' );

			// Next, create the actual input element and then return it to the caller
			inputElement =

			'<tr class="br_ingredients_heading"><td class="br_ingredients_sort_handle"><i class="fas fa-arrows-alt"></i></td><td colspan="4"><input name="br_recipe[ingredient][' + iInputCount + '][heading]" id="br_recipe_ingredient_heading_' + iInputCount + '" class="br_recipe_ingredient_heading" type="text" placeholder="Section Heading" value=""></td><td><span class="br_ingredients_heading_delete"><i class="fas fa-trash"></i></span></td></tr>';

			jQuery( inputElement ).insertAfter( last_row );

		}

	}
);
