( function ( $ ) {
	// Global variable
	var reload_check = false;
	var publish_button_click = false;
		// Set interval on publish button click
		add_publish_button_click = setInterval(function() {
			// Get editor button
			$publish_button = $( '.edit-post-header__settings .editor-post-publish-button' );
			
			if ( $publish_button && ! publish_button_click ) {
				
				publish_button_click = true;
				// Click publish button
				$publish_button.on('click', function() {

					// Set reloader interval
					var reloader = setInterval(function() {
						
						if ( ! reload_check ) {
							reload_check = true;
						}
							
						// Gutenburg core editor HOOKS.
						postsaving = wp.data.select('core/editor').isSavingPost();
						autosaving = wp.data.select('core/editor').isAutosavingPost();
						success = wp.data.select('core/editor').didPostSaveRequestSucceed();
						// End
						// Check post saving status.
						if ( postsaving || autosaving || ! success ) {
							classic_reload_check = false;
							return;
						}
						// Clear iterval
						clearInterval( reloader );
						// Get metabox value
						var SelectPostTypeLabel = $.trim( $( '#post-type-select option:selected' ).text() ) || '';
						var SelectPostType = $.trim( $( '#post-type-select option:selected' ).val() ) || '';
						var SameTaxonomy = $.trim( $( '#same_taxonomy option:selected' ).val() ) || '' ;
						var SelectedLabel = $.trim( $( '#post-type-display' ).text() ) || '';

						if ( $( '#post-type-display' ).text() == SelectPostTypeLabel ) return;

						// If check value is not empty.
						if ( SelectPostType !='' || SameTaxonomy !='' ) {
							// Sweet alert box.
							swal({
								title:'Post Transfer To "' + SelectPostTypeLabel + '"',
								icon: 'success',
								button: 'Click View Post',
								closeOnClickOutside: false,
								closeOnEsc: false,
							}).then( function() {
								window.location.href = window.location.href;
							} );
						}
					}, 1000);

				} );

			}

		}, 500);
} )( jQuery );