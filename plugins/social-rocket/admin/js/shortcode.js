jQuery( function($) {

	tinymce.PluginManager.add( 'social_rocket_shortcode', function( editor, url ) {
	
		editor.addButton( 'social_rocket_shortcode', {
			title: 'Social Rocket',
			icon: 'social-rocket fas fa-rocket',
			onclick: function() {
			
				/* TODO: idea for future use
				var networks = [{
					text: 'Default',
					value: '',
				}];
				$.each( socialRocketAdmin.inline_networks, function( key, value ) {
					networks.push({
						text: value.name,
						value: key,
					});
				});
				*/
	
				editor.windowManager.open( {
					title: socialRocketAdmin.i18n.shortcode_inline_header_title,
					body: [
						{
							type:      'textbox',
							id:        'social_rocket_shortcode_inline_heading',
							name:      'social_rocket_shortcode_inline_heading',
							label:     socialRocketAdmin.i18n.shortcode_inline_heading,
						},
						{
							type:      'textbox',
							id:        'social_rocket_shortcode_inline_networks',
							name:      'social_rocket_shortcode_inline_networks',
							label:     socialRocketAdmin.i18n.shortcode_inline_networks,
						},
						{
							type:      'label',
							onPostRender: function() {
								this.getEl().innerHTML =
								'<span style="float:right;font-style:italic;">Comma separated list of social networks (e.g. "facebook,twitter,google_plus"). Leave blank to use default setting.</span>';
							},
							text: ''
						},
						/* TODO: idea for future use
						{
							type: 'selectbox',
							name: 'buttons',
							classes: 'buttons',
							label: 'Buttons to Include:',
							options: networks,
							settings: { multiple: true } // this doesn't work
						},
						*/
						{
							type:      'checkbox',
							id:        'social_rocket_shortcode_inline_show_counts',
							name:      'social_rocket_shortcode_inline_show_counts',
							label:     socialRocketAdmin.i18n.shortcode_inline_show_counts,
							checked:   true
						},
						{
							type:      'checkbox',
							id:        'social_rocket_shortcode_inline_show_total',
							name:      'social_rocket_shortcode_inline_show_total',
							label:     socialRocketAdmin.i18n.shortcode_inline_show_total,
							checked:   true
						},
						{
							type:      'listbox',
							name:      'override',
							label:     'Should the buttons share this post or a different one?',
							values: [
								{ text: 'This Post', value: 'false' },
								{ text: 'A Different Post', value: 'true' }
							],
							onselect: function( v ) {
								if ( this.value() == 'true' ) {
									jQuery( '.mce-postid' ).parent().parent().slideDown();
								} else {
									jQuery( '.mce-postid' ).parent().parent().slideUp();
								}
							}
						},
						{
							type:      'textbox',
							name:      'postID',
							classes:   'postid',
							label:     'The ID of the post or page to share:'
						},
					],
					onPostRender: function() {
						jQuery( '.mce-postid' ).parent().parent().slideUp();
						jQuery( '.mce-title' ).prepend( '<i class="mce-i-social-rocket fas fa-rocket"></i>' );
					},
					onsubmit: function( e ) {
					
						var shortcode = '';
						
						// Open shortcode
						shortcode = '[socialrocket';
						
						// Add heading if set
						if ( e.data.social_rocket_shortcode_inline_heading > '' ) {
							shortcode += ' heading="' + e.data.social_rocket_shortcode_inline_heading + '"';
						}
						
						// Networks
						if ( e.data.social_rocket_shortcode_inline_networks > '' ) {
							shortcode += ' networks="' + e.data.social_rocket_shortcode_inline_networks + '"';
						}
						
						// Show Counts?
						if ( e.data.social_rocket_shortcode_inline_show_counts ) {
							shortcode += ' show_counts="true"';
						} else {
							shortcode += ' show_counts="false"';
						}
						
						// Show Total?
						if ( e.data.social_rocket_shortcode_inline_show_total ) {
							shortcode += ' show_total="true"';
						} else {
							shortcode += ' show_total="false"';
						}
						
						// override?
						if ( e.data.override == 'true' && e.data.postID != '' ) {
							shortcode += ' id="' + e.data.postID + '"';
						}
						
						// Close shortcode
						shortcode += ']';

						if( shortcode ) {
							editor.insertContent( shortcode );
						}
						
					}
				});
			}
		});
	});

});
