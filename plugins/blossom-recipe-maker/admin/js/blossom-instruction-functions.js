jQuery( document ).ready(
	function($) {

		var ins = $( '#br_recipe_instructions tr.br_instructions span.br_instructions_delete' ).children().length;

		if (ins == 1) {
			$( '#br_recipe_instructions tr.br_instructions:first' ).find( 'span.br_instructions_delete' ).hide();
		}

		/**
		 * When Add an instruction button is clicked, clones the last row elements.
		 Also provides the proper ID and name attributes so that the information can be serialized.
		 */

		$( '#br-add-instructions' ).on(
			'click',
			function(e){
				e.preventDefault();
				addInstruction();
			}
		);

		$( '#br-add-instructions-heading' ).on(
			'click',
			function(e){
				e.preventDefault();
				addInstructionHeading();
			}
		);

		$( '.br-btn-wrap' ).click(
			function(e) {

				e.preventDefault();
				var add   = $( this );
				var image = wp.media(
					{
						title: 'Upload Image',
						// mutiple: true if you want to upload multiple files at once
						multiple: false
					}
				).open()
				.on(
					'select',
					function(e){
						// This will return the selected image from the Media Uploader, the result is an object
						var uploaded_image = image.state().get( 'selection' ).first();
						// We convert uploaded_image to a JSON object to make accessing it easier
						// Output to the console uploaded_image
						var image_url = uploaded_image.toJSON().url;
						// Let's assign the url value to the input field
						add.find( '.br_instructions_thumbnail' ).attr( 'src',image_url );
						add.siblings( 'input.br_instructions_image' ).val( uploaded_image.toJSON().id );
						add.find( 'input.br_instructions_add_image' ).val( RecipeInstructions.change_image ).trigger( 'change' );
						add.parents( '.add-instruction-image' ).addClass( 'has-image' );

						if (add.parents( '.add-instruction-image' ).hasClass( 'has-image' )) {
							add.parents( '.add-instruction-image' ).find( '.br_instructions_add_btn' ).hide();
							add.parents( '.add-instruction-image' ).find( '.br_instructions_remove_image' ).show();
						} else {
							add.parents( '.add-instruction-image' ).find( '.br_instructions_add_btn' ).show();
							add.parents( '.add-instruction-image' ).find( '.br_instructions_remove_image' ).hide();
						}
					}
				);
			}
		);

		$( '.br_instructions_remove_image' ).on(
			'click',
			function(e) {
				e.preventDefault();

				var button = jQuery( this );

				button.parent().find( 'input.br_instructions_image' ).val( '' ).trigger( 'change' );
				button.parent().find( 'img.br_instructions_thumbnail' ).attr( 'src', '' );
				button.parent().find( 'input.br_instructions_add_image' ).val( RecipeInstructions.add_image ).trigger( 'change' );
				button.parents( '.add-instruction-image' ).removeClass( 'has-image' );
				button.parent().find( 'a.br_instructions_remove_image' ).hide();
				button.parent().find( 'a.br_instructions_add_btn' ).show();

			}
		);

		$( '.br_instructions_delete' ).on(
			'click',
			function(){

				var instruction = $( '#br_recipe_instructions tr.br_instructions span.br_instructions_delete' ).children().length;

				if (instruction != 1) {
					var remove = confirm( RecipeInstructions.delete_warning );

					if (remove == true) {
						jQuery( this ).parents( 'tr' ).remove();
					}

					if (instruction == 2) {

						$( '#br_recipe_instructions tr.br_instructions:first' ).find( 'span.br_instructions_delete' ).hide();

					}

				}

			}
		);

		$( document ).on(
			'click',
			'.br_instructions_heading_delete',
			function(event)
			{

					event.preventDefault();
					var heading_delete = confirm( RecipeInstructions.heading_delete_warning );

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

		$( '#br-recipe-instructions tbody' ).sortable(
			{
				opacity: 0.6,
				handle: '.br_instructions_sort_handle'

			}
		);

		function addInstruction()
		{
			var instCount         = jQuery( '#br_recipe_instructions tr.br_instructions' ).length;
			var headCount         = jQuery( '#br_recipe_instructions tr.br_instructions_heading' ).length;
			var no_instructions   = instCount + headCount;
			var clone_instruction = jQuery( '#br_recipe_instructions tr.br_instructions:last' ).clone( true );

			clone_instruction
			.insertAfter( '#br_recipe_instructions tr:last' )
			.find( 'textarea' ).val( '' )
			.attr(
				'name',
				function(index, name) {
					return name.replace( /(\d+)/, no_instructions );
				}
			)
				.attr(
					'id',
					function(index, id) {
						return id.replace( /(\d+)/, no_instructions );
					}
				);

				clone_instruction
				.find( 'td.add-instruction-image' ).removeClass( 'has-image' );

				clone_instruction
				.find( '.br_instructions_image' ).val( '' );

				clone_instruction
				.find( '.br_instructions_add_image' ).val( RecipeInstructions.add_image ).trigger( 'change' );

				clone_instruction
				.find( '.br_instructions_thumbnail' ).attr( 'src', '' );

				clone_instruction
				.find( '.br_instructions_remove_image' ).hide();
				clone_instruction
				.find( '.br_instructions_add_btn' ).show();

				clone_instruction
				.find( '.br_instructions_image' )
				.attr(
					'name',
					function(index, name) {
						return name.replace( /(\d+)/, no_instructions );
					}
				);

				clone_instruction.find( 'span.br_instructions_delete' ).show();

				jQuery( '#br_recipe_instructions tr.br_instructions:first' ).find( 'span.br_instructions_delete' ).show();

				jQuery( '#br_recipe_instructions tr:last .br_instructions_description' ).focus();
		}

		function addInstructionHeading()
		{
			var inputElement, iInputCount;

			/* First, count the number of input fields that already exist. This is how we're going to
			 * implement the name and ID attributes of the element.
			 */
			var instCount = jQuery( '#br_recipe_instructions tr.br_instructions' ).length;
			var headCount = jQuery( '#br_recipe_instructions tr.br_instructions_heading' ).length;
			iInputCount   = instCount + headCount;

			var last_row = jQuery( '#br_recipe_instructions tr:last' );

			// Next, create the actual input element and then return it to the caller
			inputElement =

			'<tr class="br_instructions_heading"><td class="br_instructions_sort_handle"><i class="fas fa-arrows-alt"></i></td><td colspan="4"><input name="br_recipe[instructions][' + iInputCount + '][heading]" id="br_recipe_instructions_heading_' + iInputCount + '" class="br_recipe_instructions_heading" type="text" placeholder="Section Heading" value=""></td><td><span class="br_instructions_heading_delete"><i class="fas fa-trash"></i></span></td></tr>';

			jQuery( inputElement ).insertAfter( last_row );

		}

	}
);
