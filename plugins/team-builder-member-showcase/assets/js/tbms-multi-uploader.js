jQuery(
	function(jQuery) {
		var file_frame_template,
		awl_plugin_template = {
			ul: '',
			init: function() {
				this.ul = jQuery( '.plugin-template-mediabox' );
				this.ul.sortable(
					{
						placeholder: '',
						revert: true,
					}
				);

				/**
				 * Add Slide Callback Funtion
				 */
				jQuery( '#team_column_button' ).on(
					'click',
					function(event) {
						event.preventDefault();
						if (file_frame_template) {
							file_frame_template.open();
							return;
						}
						file_frame_template = wp.media.frames.file_frame_template = wp.media(
							{
								multiple: true
							}
						);

						file_frame_template.on(
							'select',
							function() {
								var images = file_frame_template.state().get( 'selection' ).toJSON(),
								length     = images.length;
								for (var i = 0; i < length; i++) {
									awl_plugin_template.get_thumbnail( images[i]['id'] );
								}
							}
						);
						file_frame_template.open();
					}
				);

				/**
				 * Delete Slide Callback Function
				 */
				this.ul.on(
					'click',
					'#team_column_delete',
					function() {
						if (confirm( 'Are sure to delete this team member?' )) {
							jQuery( "#" + this.value ).remove();
						}
						return false;
					}
				);

				/**
				 * Delete All Slides Callback Function
				 */
				jQuery( '#team_column_delete_all' ).on(
					'click',
					function() {
						if (confirm( 'Are sure to delete all team members?' )) {
							awl_plugin_template.ul.empty();
						}
						return false;
					}
				);

			},
			get_thumbnail: function(id, cb) {
				cb       = cb || function() {
				};
				var data = {
					action: 'tbms_add_member_li',
					slideId: id
				};
				jQuery.post(
					ajaxurl,
					data,
					function(response) {
						awl_plugin_template.ul.append( response );
						cb();
					}
				);
			}
		};

		awl_plugin_template.init();
	}
);
