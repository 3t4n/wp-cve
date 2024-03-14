jQuery( function($) {

	tinymce.PluginManager.add( 'social_rocket_shortcode_tweet', function( editor, url ) {

		editor.addButton( 'social_rocket_shortcode_tweet', {
			icon:    'social-rocket fab fa-twitter',
			tooltip: socialRocketAdmin.i18n.tinymce_tweet_tooltip,
			onclick: function() {

				var displayStyles = [];
				$.each( socialRocketAdmin.tweet_settings.saved_settings, function( key, value ) {
					displayStyles.push({
						text:  value.name,
						value: key,
					});
				});
				if ( displayStyles.length === 0 ) {
					displayStyles.push({
						text:  'Default',
						value: '0',
						id:    'social_rocket_tweet_style_default'
					});
				}
				
				editor.windowManager.open({
					id:       'social-rocket-tweet-dialog',
					title:    socialRocketAdmin.i18n.tinymce_tweet_header_title,
					minWidth: 750,
					buttons:  [
						{
							text:      socialRocketAdmin.i18n.tinymce_tweet_buttons_add,
							classes:   'primary abs-layout-item',
							minWidth:  130,
							onclick:   'submit'
						},
						{
							text:      socialRocketAdmin.i18n.tinymce_tweet_buttons_cancel,
							onclick:   'close'
						}
					],
					body:     [
						{
							type:      'textbox',
							id:        'social_rocket_tweet_dialog_quote',
							name:      'social_rocket_tweet_dialog_quote',
							label:     socialRocketAdmin.i18n.tinymce_tweet_label_quote,
							multiline: true,
							minWidth:  400,
							minHeight: 100
						},
						{
							type:      'textbox',
							id:        'social_rocket_tweet_dialog_tweet',
							name:      'social_rocket_tweet_dialog_tweet',
							label:     socialRocketAdmin.i18n.tinymce_tweet_label_tweet,
							multiline: true,
							minWidth:  400,
							minHeight: 100
						},
						{
							type:      'checkbox',
							id:        'social_rocket_tweet_include_url',
							name:      'social_rocket_tweet_include_url',
							label:     socialRocketAdmin.i18n.tinymce_tweet_label_include_url,
							text:      socialRocketAdmin.i18n.tinymce_tweet_desc_include_url,
							checked:   socialRocketAdmin.tweet_settings.include_url
						},
						{
							type:      'checkbox',
							id:        'social_rocket_tweet_include_via',
							name:      'social_rocket_tweet_include_via',
							label:     socialRocketAdmin.i18n.tinymce_tweet_label_include_via,
							text:      socialRocketAdmin.i18n.tinymce_tweet_desc_include_via,
							checked:   socialRocketAdmin.tweet_settings.include_via
						},
						{
							type:      'listbox',
							id:        'social_rocket_tweet_style',
							name:      'social_rocket_tweet_style',
							label:     socialRocketAdmin.i18n.tinymce_tweet_style,
							values:    displayStyles
						},
						{
							type:      'textbox',
							id:        'social_rocket_tweet_custom_url',
							name:      'social_rocket_tweet_custom_url',
							label:     socialRocketAdmin.i18n.tinymce_tweet_custom_url,
						},
						{
							type:      'textbox',
							id:        'social_rocket_tweet_custom_via',
							name:      'social_rocket_tweet_custom_via',
							label:     socialRocketAdmin.i18n.tinymce_tweet_custom_via,
						},
						{
							type:      'textbox',
							id:        'social_rocket_tweet_style_css_class',
							name:      'social_rocket_tweet_style_css_class',
							label:     socialRocketAdmin.i18n.tinymce_tweet_style_css_class,
						}
						/* TODO: idea for possible future use
						,{
							type   : 'colorbox', // colorpicker plugin MUST be included for this to work
							id: 'social_rocket_tweet_style_text_color',
							name   : 'social_rocket_tweet_style_text_color',
							label  : 'Text Color',
							onaction: createColorPickAction(),
						},
						{
							type   : 'colorbox',
							id: 'social_rocket_tweet_style_background_color',
							name   : 'social_rocket_tweet_style_background_color',
							label  : 'Background Color',
							onaction: createColorPickAction(),
						},
						{
							type   : 'colorbox',
							id: 'social_rocket_tweet_style_accent_color',
							name   : 'social_rocket_tweet_style_accent_color',
							label  : 'Accent Color',
							onaction: createColorPickAction(),
						}
						*/
					],
					onPostRender: function() {
						$( '#social-rocket-tweet-dialog-title' ).prepend( '<i class="mce-i-social-rocket fas fa-rocket"></i>' );
					},
					onsubmit:     function(e) {

						var shortcode = '';

						if( e.data.social_rocket_tweet_dialog_tweet ) {

							// Open shortcode
							shortcode = '[socialrocket-tweet';

							// Add the quote
							shortcode += ' quote="' + e.data.social_rocket_tweet_dialog_quote.split( '"' ).join( '__quot__' ) + '"';

							// Add the tweet
							shortcode += ' tweet="' + e.data.social_rocket_tweet_dialog_tweet.split( '"' ).join( '__quot__' ) + '"';

							// Add include_url
							if ( socialRocketAdmin.tweet_settings.include_url && ! e.data.social_rocket_tweet_include_url ) {
								// global setting = true, local checkbox = false
								shortcode += ' include_url="false"';
							} else if ( ! socialRocketAdmin.tweet_settings.include_url && e.data.social_rocket_tweet_include_url ) {
								// global setting = false, local checkbox = true
								shortcode += ' include_url="true"';
							}

							// Add include_via
							if ( socialRocketAdmin.tweet_settings.include_via && ! e.data.social_rocket_tweet_include_via ) {
								// global setting = true, local checkbox = false
								shortcode += ' include_via="false"';
							} else if ( ! socialRocketAdmin.tweet_settings.include_via && e.data.social_rocket_tweet_include_via ) {
								// global setting = false, local checkbox = true
								shortcode += ' include_via="true"';
							}
							
							// Add style if set
							if ( e.data.social_rocket_tweet_style != 0 ) {
								shortcode += ' style_id="' + e.data.social_rocket_tweet_style + '"';
							}
							
							// Add class if set
							if ( e.data.social_rocket_tweet_style_css_class ) {
								shortcode += ' add_class="' + e.data.social_rocket_tweet_style_css_class + '"';
							}
							
							// Add URL if set
							if ( e.data.social_rocket_tweet_custom_url ) {
								shortcode += ' url="' + e.data.social_rocket_tweet_custom_url + '"';
							}
							
							// Add via if set
							if ( e.data.social_rocket_tweet_custom_via ) {
								shortcode += ' via="' + e.data.social_rocket_tweet_custom_via + '"';
							}

							// Close shortcode
							shortcode += ']';

						}

						if( shortcode ) {
							editor.insertContent( shortcode );
						}

					}
				});

				var $tweet             = $('#social_rocket_tweet_dialog_tweet');
				var $tweet_wrapper	   = $tweet.closest('.mce-formitem');
				var $sample_permalink  = $('#sample-permalink');
				var initial_char_count = 280;
				var include_url        = socialRocketAdmin.tweet_settings.include_url;
				var include_via        = socialRocketAdmin.tweet_settings.include_via;
				var url_length		   = get_url_length();
				var via_length 		   = get_via_length();
				
				$tweet.after('<p id="social_rocket_tweet_length"><em>Characters remaining: <span>' + get_char_count() + '</span></em></p>');
				$tweet.keyup( function() {
					$('#social_rocket_tweet_length span').html( get_char_count() );
				});
				
				$tweet_wrapper.height( $tweet_wrapper.height() + 25 );
				$tweet_wrapper.nextAll('.mce-formitem').each( function() {
					$(this).css( 'top', parseInt( $(this).css('top'), 10) + 25 );
				});
				
				$('#social-rocket-tweet-dialog-body').height( $('#social-rocket-tweet-dialog-body').height() + 25 );

				$('#social_rocket_tweet_include_url').click( function() {
					if( $(this).attr('aria-checked') == "true" ) { // this is the value from *before* this click
						include_url = false;
					} else {
						include_url = true;
					}
					$tweet.trigger('keyup');
				});
				
				$('#social_rocket_tweet_custom_url').keyup( function() {
					$tweet.trigger('keyup');
				});

				$('#social_rocket_tweet_include_via').click( function() {
					if( $(this).attr('aria-checked') == "true" ) { // this is the value from *before* this click
						include_via = false;
					} else {
						include_via = true;
					}
					$tweet.trigger('keyup');
				});
				
				$('#social_rocket_tweet_custom_via').keyup( function() {
					$tweet.trigger('keyup');
				});

				function get_url_length() {
					url_length = 0;
					if ( include_url ) {
						url_length = $sample_permalink.text().length;
						if ( $('#social_rocket_tweet_custom_url').val().length ) {
							url_length = $('#social_rocket_tweet_custom_url').val().length;
						}
					}
					return url_length;
				}
				
				function get_via_length() {
					via_length = 0;
					if ( include_via ) {
						via_length = socialRocketAdmin.tweet_settings.via_username.length;
						if ( $('#social_rocket_tweet_custom_via').val().length ) {
							via_length = $('#social_rocket_tweet_custom_via').val().length;
						}
					}
					return via_length;
				}
				
				function get_char_count() {
					url_length = get_url_length();
					via_length = get_via_length();
					return initial_char_count - url_length - via_length - $tweet.val().length;
				}

			}
		});
	});
	
	function createColorPickAction() {
		var editor = tinymce.activeEditor;
		var colorPickerCallback = editor.settings.color_picker_callback;
		if (colorPickerCallback) {
			return function() {
				var self = this;
				colorPickerCallback.call(
					editor,
					function(value) {
						self.value(value).fire('change');
					},
					self.value()
				);
			};
		}
	}

});
