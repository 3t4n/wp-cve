jQuery(
	function($){

		// Set all variables to be used in scope
		var frame;

		// ADD IMAGE LINK
		$( document ).on(
			'click',
			'a#recipe_add_image_button',
			function(event)
			{

					event.preventDefault();

					// If the media frame already exists, reopen it.
				if ( frame ) {
					frame.close();
				}

					// Create a new media frame
					frame = wp.media(
						{
							title: $( this ).data( 'uploader-title' ),
							button: {
								text: $( this ).data( 'uploader-button-text' ),
							},
							multiple: true  // Set to true to allow multiple files to be selected
						}
					);

					// When an image is selected in the media frame...
					frame.on(
						'select',
						function()
						{
								var listIndex = $( '#recipe_feat_img_gallery_list li' ).index( $( '#recipe_feat_img_gallery_list li:last' ) ),
								selection     = frame.state().get( 'selection' );

								selection.map(
									function(attachment, i) {
										attachment = attachment.toJSON(),
										index      = listIndex + (i + 1);

										$( '#recipe_feat_img_gallery_list' )
										.append( '<li><input type="hidden" name="br_recipe_gallery[' + index + ']" value="' + attachment.id + '"><img class="recipe_image_preview" src="' + attachment.url + '"><div class="br-gallery-btn-wrap"><a class="recipe_change_image_button" href="javascript:void(0);" data-uploader-title=' + RecipeGallery.change_image + ' data-uploader-button-text=' + RecipeGallery.change_image + '><i class="fas fa-pencil-alt"></i></a><a class="recipe_remove_image_button" href="javascript:void(0);"><i class="fas fa-times"></i></a></div></li>' );
									}
								);

						}
					);

					SortImages();

					// Finally, open the modal on click
					frame.open();
			}
		);

		// Change IMAGE LINK
		$( document ).on(
			'click',
			'a.recipe_change_image_button',
			function(event)
			{

					event.preventDefault();

					var img = $( this );

					// If the media frame already exists, reopen it.
				if ( frame ) {
					frame.close();
				}

					// Create a new media frame
					frame = wp.media(
						{

							title: $( this ).data( 'uploader-title' ),
							button: {
								text: $( this ).data( 'uploader-button-text' ),

							},
							multiple: false  // Set to true to allow multiple files to be selected
						}
					);

					// When an image is selected in the media frame...
					frame.on(
						'select',
						function()
						{

								attachment = frame.state().get( 'selection' ).first().toJSON();
								img.parent().siblings( 'input:hidden' ).attr( 'value', attachment.id );
								img.parent().siblings( 'img.recipe_image_preview' ).attr( 'src', attachment.url );

						}
					);

					// Finally, open the modal on click
					frame.open();
			}
		);

		// DElete Image
		$( document ).on(
			'click',
			'a.recipe_remove_image_button',
			function(event)
			{

					event.preventDefault();

					var image_delete = confirm( RecipeGallery.delete_warning );

				if ( image_delete == true) {
					$( this ).parents( 'li' ).animate(
						{ opacity: 0 },
						200,
						function() {
							$( this ).remove();
							resetImagesIndex();
						}
					);
				}

			}
		);

		function resetImagesIndex()
		{

			$( '#recipe_feat_img_gallery_list li' ).each(
				function(i) {

					$( this ).find( 'input:hidden' ).attr( 'name', 'br_recipe_gallery[' + i + ']' );

				}
			);
		}

		function SortImages()
		{

			$( '#recipe_feat_img_gallery_list' ).sortable(
				{
					opacity: 0.6,
					stop: function() {
						resetImagesIndex();
					}
				}
			);
		}

		SortImages();

	}
);
