jQuery( document ).ready(
	function ($) {
		var initial_theme = $( '#theme' ).val();

		$( '#theme' ).on(
			'change',
			function () {
				if ($( this ).val() != initial_theme) {
					$( '#canvas_theme_warning' ).show();
					$( '#canvas_theme_link' ).hide();
				} else {
					$( '#canvas_theme_warning' ).hide();
					$( '#canvas_theme_link' ).show();
				}
			}
		)

		$( '#canvas_different_theme' ).on(
			'change',
			function () {
				if ($( this ).is( ':checked' )) {
					$( '#theme_choice_block' ).show();
				} else {
					$( '#theme_choice_block' ).hide();
				}
			}
		)

		$( '.canvas-other-options-checkbox' ).on(
			'change',
			function () {
				if ($( this ).is( ':checked' )) {
					$( '.canvas-other-options' ).show();
				} else {
					$( '.canvas-other-options' ).hide();
				}
			}
		)

		$( '#canvas_push_log_enable' ).on(
			'change',
			function () {
				if ($( this ).is( ':checked' )) {
					$( '#canvas_push_log_name_block' ).show();
				} else {
					$( '#canvas_push_log_name_block' ).hide();
				}
			}
		)

		// auto save is theme enabled and name
		$( '#canvas_different_theme, #theme' ).on(
			'change',
			function () {
				var data = {
					action: 'canvas_save_theme',
					use: $( '#canvas_different_theme' ).is( ':checked' ) ? 1 : 0,
					theme: $( '#theme' ).val()
				};
				$.post(
					ajaxurl,
					data,
					function (response) {
						if ( 'Ok' == response) {
							$( '#canvas_theme_warning' ).hide();
							$( "#form_editor" ).trigger( 'setClean.areYouSure' );
							$.notify( 'Updated', { position:'top right', className: 'canvas-success' } );
							$( '#canvas_theme_link' ).show();
						}
					}
				);
			}
		)

		$( ".canvas-chosen-select" ).chosen();

		$( '#form_editor' ).areYouSure();

		/* Push Notifications */
		if ($( '#canvas_bp_private_messages' ).length) {
			$( '.canvas-other-options-checkbox' ).trigger( 'change' );
		}

		/* Manual notifications */

		var canvasLimitChars = function (txtMsg, CharLength, indicator) {
			chars          = txtMsg.value.length;
			var chars_left = CharLength - chars;
			document.getElementById( indicator ).innerHTML = chars_left + " character" + (chars_left != 1 ? 's' : '') + " left.";
			if (chars > CharLength) {
				txtMsg.value                                   = txtMsg.value.substring( 0, CharLength );
				document.getElementById( indicator ).innerHTML = "0 characters left.";
			}
		}

		var canvasCheckDuplicateNotification = function () {
			var data      = {
				action: 'canvas_notification_check_duplicate',
				msg: $( "#canvas__sns-notification-text-area" ).val(),
				data_id: $( "#canvas_notification_data_id" ).val(),
				post_id: $( "#canvas__sns-post-search" ).val(),
				url: $( "#canvas__sns-notification-url" ).val(),
				os: $( "#canvas__sns-send-to-platforms:checked" ).val(),
			};
			var duplicate = false;
			$.ajax(
				{
					url: ajaxurl,
					data: data,
					type: 'POST',
					async: false,
					success: function (response) {
						if ($.trim( response ).length > 0) {
							duplicate = true;
						}
					}
				}
			);
			return duplicate;
		};

		var isUrlValid = function (url) {
			return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test( url );
		}

		var canvasValidateNotification = function () {
			var errors = [];

			var message = $.trim( $( "#canvas__sns-notification-text-area" ).val() );
			if (message.length === 0) {
				errors.push( 'Message cannot be blank' );
			}

			var attach = $( "#canvas_notification_data_id" ).val();
			if (attach === 'custom') {
				var customPostID = $.trim( $( "#canvas_post_id" ).val() );
				if (customPostID.length === 0) {
					errors.push( 'Custom ID cannot be blank' );
				} else if ( ! $.isNumeric( customPostID )) {
					errors.push( 'Custom ID must be a number' );
				}
			}
			if (attach === 'url') {
				var customUrl = $.trim( $( "#canvas_url" ).val() );
				if (customUrl.length === 0) {
					errors.push( 'URL cannot be blank' );
				} else if ( ! isUrlValid( customUrl )) {
					errors.push( 'You must enter a valid URL' );
				}
			}

			if (errors.length > 0) {
				$( "#error-message" ).html( errors.join( "<br/>" ) ).show();
				return false;
			} else {
				$( "#error-message" ).hide();
				return true;
			}
		};

		var canvasLoadNotificationHistory = function () {
			var data = {
				action: 'canvas_notification_history',
				async: true
			};
			$( "#canvas_notification_history" ).css( "display", "none" );

			$.post(
				ajaxurl,
				data,
				function (response) {
					// saving the result and reloading the div
					$( "#canvas_notification_history" ).html( response ).show();
				}
			);
		};

		$( "#canvas_message" ).on(
			"input",
			function () {
				canvasLimitChars( this, 107, 'canvas_message_chars' );
			}
		);

		$.post(
			ajaxurl,
			{action: 'canvas_attachment_content', async: true},
			function (response) {
				if (response.search( '<option' ) > -1) {
					$( "#canvas_notification_data_id" ).html( response ).val( '' ).trigger( 'change' );
				}
			}
		);

		$( '#canvas_notification_data_id' ).on(
			'change',
			function () {
				var value = $( this ).val();
				switch (value) {
					case 'url':
						$( '#canvas_post_id_block' ).hide();
						$( '#canvas_url_block' ).show();
						break;
					case 'custom':
						$( '#canvas_post_id_block' ).show();
						$( '#canvas_url_block' ).hide();
						break;
					default:
						$( '#canvas_post_id_block, #canvas_url_block' ).hide();
				}
			}
		)

		$( "#canvas_notification_manual_send_submit" ).click(
			function () {
				var cont = true;

				if (cont) {
					$( "#canvas_notification_manual_send_submit" ).val( $( "#canvas_notification_manual_send_submit" ).data( 'sending' ) );
					$( "#canvas_notification_manual_send_submit" ).attr( "disabled", true );

					$( "#canvas_notification_manual_send_submit" ).css( "opacity", "0.5" );

					var data = {
						action: 'canvas_notification_manual_send',
						title: $( '#canvas__sns-notification-title' ).val(),
						msg: $( "#canvas__sns-notification-text-area" ).val(),
						data_id: $( "#canvas_notification_data_id" ).val(),
						post_id: $( "#canvas__sns-post-search" ).val(),
						url: $( "#canvas__sns-notification-url" ).val(),
						os: $( "#canvas__sns-send-to-platforms:checked" ).val(),
						category_as_tag: $( "#canvas__sns-use-post-category-as-tags:checked" ).val() || '',
						tags_list: $( "#canvas__sns-additional-tags" ).val(),
						use_post_featured_image: $( '#canvas__sns-use-post-featured-image:checked' ).val() || false,
						featured_image_url: $( '#canvas__sns-featured-image-url' ).val(),
						notification_type: $( '#canvas__sns-notification-type' ).val(),
					};

					const spinner = $( '.canvas__sns-spinner' );
					spinner.show();

					$.post(
						ajaxurl,
						data,
						function (response) {
							// update history
							$( "#canvas_notification_manual_send_submit" ).val( $( "#canvas_notification_manual_send_submit" ).data( 'send' ) );
							$( "#canvas_notification_manual_send_submit" ).attr( "disabled", false );
							$( "#canvas_notification_manual_send_submit" ).css( "opacity", "1.0" );
							if (true === response) {
								canvasLoadNotificationHistory();
								$( "#success-message" ).show();
								setTimeout(
									function () {
										$( "#success-message" ).fadeOut();
									},
									2000
								);
							} else {
								if (false === response) {
									response = "There was an error sending this notification";
								} else {
									response = "There was an error sending this notification:<br>" + response;
								}
								$( '#error-message' ).html( response ).show();
								setTimeout(
									function () {
										$( "#error-message" ).fadeOut();
									},
									20000
								);
							}

							spinner.hide();
						}
					);
				}
			}
		);

		$( '#canvas_manual_message input:not([type="submit"]), #canvas_manual_message select' ).on(
			'click.clear-error, input.clear-error, change.clear-error',
			function () {
				$( '#error-message' ).hide();
			}
		)

		canvasLoadNotificationHistory();

		/* Manual notifications - end */

		if ($('#canvas_push_clean_history').length) {
			$('#canvas_push_clean_history').on('click', function () {
				var $button = $(this);
				if ( ! $button.hasClass('disabled') ) {
					$button.addClass('disabled')
					var data = {
						action: 'canvas_clean_history',
						_ajax_nonce: $('#canvas-clean-history-nonce').val()
					};
					var duplicate = false;
					$.ajax({
						url: ajaxurl,
						data: data,
						type: 'POST',
						async: true,
						success: function (response) {
							$button.removeClass('disabled')
							if ( 'OK' === response ) {
								$.notify('Cleaned', { position:'top right', className: 'canvas-success' });
							}
						},
						error: function(response) {
							console.log(response);
							$button.removeClass('disabled')
							$.notify('Error', { position:'top right', className: 'canvas-error' });
						}
					});
				}
				return false;
			});
		}

		// Clear log file

		if ($('#canvas_push_clean_log').length) {
			$('#canvas_push_clean_log').on('click', function () {
				var $button = $(this);
				if ( ! $button.hasClass('disabled') ) {
					$button.addClass('disabled')
					var data = {
						action: 'canvas_clean_log',
						_ajax_nonce: $('#canvas-clean-log-nonce').val()
					};

					$.ajax({
						url: ajaxurl,
						data: data,
						type: 'POST',
						async: true,
						success: function (response) {
							$button.removeClass('disabled')
							if ( 'OK' === response ) {
								$.notify('Log File Cleared Successfully', { position:'top right', className: 'canvas-success' });
							}
						},
						error: function(response) {
							console.log(response);
							$button.removeClass('disabled')
							$.notify('Error', { position:'top right', className: 'canvas-error' });
						}
					});
				}
				return false;
			});
		}

		$( '.custom-color-picker-field' ).wpColorPicker();
		/*
		* Select/Upload image(s) event
		*/
		$( 'body' ).on(
			'click',
			'.canvas_upload_image_button',
			function (e) {
				e.preventDefault();

				var button      = $( this ),
				custom_uploader = wp.media(
					{
						title: 'Insert image',
						library: {
							type: 'image'
						},
						button: {
							text: 'Use this image'
						},
						multiple: false
					}
				).on(
					'select',
					function () {
						var attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
						$( button ).removeClass( 'button' ).html( '<img class="true_pre_image" src="' + attachment.url + '" style="max-width:150px;display:block;" />' ).next().val( attachment.id ).next().show();
					}
				)
					.open();
			}
		);

		/*
		* Remove image event
		*/
		$( 'body' ).on(
			'click',
			'.canvas_remove_image_button',
			function () {
				$( this ).hide().prev().val( '' ).prev().addClass( 'button' ).html( 'Upload image' );
				return false;
			}
		);
		/* CSS code editor */
		if ( $( '.canvas-codemirror-css-field' ).length && wp && wp.codeEditor) {
			var cssEditorSettings = canvas_editor && canvas_editor.css ? canvas_editor.css : ( wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {} );
			wp.codeEditor.initialize( $( '.canvas-codemirror-css-field' ), cssEditorSettings );
		}
		/* HTML code editor */
		if ( $( '.canvas-codemirror-html-field' ).length && wp && wp.codeEditor) {
			var htmlEditorSettings = canvas_editor && canvas_editor.html ? canvas_editor.html : ( wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {} );
			wp.codeEditor.initialize( $( '.canvas-codemirror-html-field' ), htmlEditorSettings );
		}

		/**
		 * Notification type.
		 */
		const notificationTypeEl = $( '#canvas__sns-notification-type' );
		const notificationTitleEl = $( '#canvas__sns-notification-title' );
		const notificationMessageEl = $( '#canvas__sns-notification-text-area' );
		const notificationTypePostEl = $( '.canvas__sns-post-search' );
		const notificationTypeUrlEl = $( '.canvas__sns-notification-url' );
		const usePostCatAsTagsWrapper = $( '.canvas__sns-use-post-category-as-tags' );
		const usePostCatAsTagsEl = $( '#canvas__sns-use-post-category-as-tags' );
		const imageWrapper = $( '#canvas__sns-featured-image-wrapper' );
		const notificationImageCbEl = $( '.canvas__sns-use-post-featured-image' );
		const useFeaturedImageEl = $( '#canvas__sns-use-post-featured-image' );
		const featuredImageBtnEl = $( '#canvas__sns-upload-featured-image' );
		const uploadImagebtn = $( '#canvas__sns-upload-featured-image' );
		const notificationTagsInputEl = $( '#canvas__sns-additional-tags' );
		const notifTagsListEl = $( '.canvas__sns--all-tags' );
		const restoreDefaultTemplates = $( '.canvas-restore-default-templates' );
		const imageUrlHiddenFieldEl = $( '#canvas__sns-featured-image-url' );
		let ignoreRestoreDefaultTemplatesPrompt = false;
		let ogTagBackup = '';

		notificationTypeEl.on( 'change', function( e ) {
			notificationTypePostEl.toggleClass( 'sns--hide' );
			notificationTypeUrlEl.toggleClass( 'sns--hide' );
			usePostCatAsTagsWrapper.toggleClass( 'sns--hide' );

			notificationTitleEl.val( '' );
			notificationMessageEl.val( '' );
			usePostCatAsTagsEl.prop( 'checked', false );
			notificationTagsInputEl.val( '' );

			if ( 'url' === $( this ).val() ) {
				notificationImageCbEl.addClass( 'sns--hide' );
				useFeaturedImageEl.prop( 'checked', false );
				uploadImagebtn.removeClass( 'sns--hide' );
			} else {
				notificationImageCbEl.removeClass( 'sns--hide' );
				useFeaturedImageEl.prop( 'checked', true );
				uploadImagebtn.addClass( 'sns--hide' );
				imageUrlHiddenFieldEl.val( '' );
				imageWrapper.find( 'img' ).remove();
			}
		} );

		/**
		 * Notification image.
		 */
		useFeaturedImageEl.on( 'change', function( e ) {
			if ( e.target.checked ) {
				featuredImageBtnEl.addClass( 'sns--hide' );
				imageWrapper.addClass( 'sns--hide' );
			} else {
				featuredImageBtnEl.removeClass( 'sns--hide' );
				imageWrapper.removeClass( 'sns--hide' );
			}
		} );

		/**
		 * Notification: Search posts
		 */

		const postSearchInputEl = $( '#canvas__sns-post-search' );

		notificationTypeEl.select2( {
			minimumResultsForSearch: -1
		} );

		postSearchInputEl.select2( {
			ajax: {
				url: ajaxurl,
				delay: 300,
				data: function( params ) {
					var query = {
						search_term: params.term,
						action: 'canvas_get_posts_for_notification',
					}
					return query;
				},
				processResults: function ( response ) {
					const { success, data } = response;

					if ( success ) {
						return {
							results: data,
						};
					}

					return {
						results: []
					};
				}
			}
		} ).on( 'select2:select', function( e ) {
			const selectedValue = e.params.data;
			const { text: title, content, tags } = selectedValue;

			notificationTitleEl.val( title );
			notificationMessageEl.val( content );
			notificationTagsInputEl.val( '' );
			notifTagsListEl.text( `(${ tags.join( ', ' ) })` );
			imageUrlHiddenFieldEl.val( '' );
			imageWrapper.find( 'img' ).remove();
			useFeaturedImageEl.trigger( 'click' ).prop( 'checked', true );
			ogTagBackup = tags.join( ', ' );
		} );
			
		usePostCatAsTagsEl.on( 'change', function() {
			const ipFieldValBackup = notificationTagsInputEl
				.val()
				.trim()
				.split( ',' )
				.map( x => x.trim() )
				.filter( x => x )
				.join( ', ' );

			if ( this.checked ) {
				notificationTagsInputEl.val( `${ ipFieldValBackup }${ ipFieldValBackup.length ? ',' : '' } ${ ogTagBackup }`.trim() )
			} else {
				if ( ipFieldValBackup.includes( ogTagBackup ) ) {

					const sanitizedString = ipFieldValBackup
						.replace( ogTagBackup, '' )
						.trim()
						.split( ',' )
						.map( x => x.trim() )
						.filter( x => x )
						.join( ', ' );

					notificationTagsInputEl.val( sanitizedString );
				}
			}
		} )

		uploadImagebtn.on( 'click', function( e ) {
			e.preventDefault();

			const button = $( this );

			const uploader = wp.media( {
				title: 'Add image',
				library: {
					type: 'image',
				},
				button: {
					text: 'Use this image',
				},
				multiple: false,
			} ).on( 'select', function() {
				const attachment = uploader.state().get('selection').first().toJSON();
				const imageUrlEl = $( '#canvas__sns-featured-image-url' );

				imageWrapper.html( '' );
				imageWrapper.html( `<img style="width: 100%;" src="${ attachment.url }">` )
				imageUrlEl.val( attachment.url );
			} ).open();
		} );

		const codeEditors = $( '.canvas-code-editor textarea' );
		const codeEditorSelectEl = $( 'select[name="canvas-code-editor"]' );

		codeEditors.each( function( index, el ) {
			const currentTextArea = $( el );
			const mode = currentTextArea.data( 'mode' );
			const editorId = el.id;

			let editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					mode
				}
			);

			const cm = wp.codeEditor.initialize(
				$( `#${ editorId }` ),
				editorSettings
			);

			$( cm.codemirror.getWrapperElement() ).hide();
		} );

		codeEditorSelectEl.on( 'change', function() {
			const editorid = $( this ).val();
			const cm = $( `#${ editorid } + .CodeMirror` )[0].CodeMirror;

			$( '.canvas-code-editor .CodeMirror' ).each( function( index, el ) {
				const currentCm = $( el )[0].CodeMirror;
				$( currentCm.getWrapperElement() ).hide();
			} );

			$( cm.getWrapperElement() ).show();
		} ).change();

		restoreDefaultTemplates.on( 'click', function ( e ) {
			return window.confirm( 'Confirm restore default templates?' );
		} );
	}
);
