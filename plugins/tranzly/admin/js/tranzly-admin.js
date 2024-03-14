( function( $ ) {
	'use strict';

	$( function() {
		if ( typeof tranzlyParams === 'undefined' ) {
			return;
		}


			// $('.tranzly_select2').select2({placeholder: 'Click to select multiple '});



			$( '.tranzly-cn-save-btn' ).on( 'click', function( e ) {
				e.preventDefault();
				const $btn = $( this ),
				$wrapper = $btn.closest( '.tranzly-translator-meta-cn-box' ),
				$loader = $wrapper.find( '.tranzly_spinner_div' ),
				deepltranslated =$wrapper.find( '[name="deepl_translated"]' ).val(),
				nonce = $wrapper.find( '[name="tranzly_meta_tranzly_box_nonce"]' ).val(),
				postId = $wrapper.find( '[name="tranzly_post_id"]' ).val();

				$btn.addClass( 'button-disabled' );
				$loader.css( 'visibility', 'visible' );

				const data = {
					tranzly_nonce: nonce,
					post_id: postId,
					deepl_translated: deepltranslated,
					action: 'tranzly_dpl_translated',
				};
				$.ajax( {
					type: 'POST',
					url: tranzlyParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						if ( response.success ) {
							response.deepl_translated
							  location.reload();
						}
						$btn.removeClass( 'button-disabled' );
						$loader.removeAttr( 'style' );
					},
				} );
			});
			$( '.tranzly-generate-new-btn' ).on( 'click', function( e ) {

				e.preventDefault();
				const $btn = $( this ),
					$wrapper = $btn.closest( '.tranzly-translator-meta-box' ),
					$loader = $wrapper.find( '.tranzly_spinner_div' ),
					$errorWrapper = $wrapper.find( '.tranzly-translation-error' ),
					nonce = $wrapper.find( '[name="tranzly_meta_box_nonce"]' ).val(),
					sourceLang = $wrapper.find( '#source_lang' ).val(),
					targetLang = $wrapper.find( '#target_lang' ).val(),
					translateAtts = $wrapper.find( '#translate_atts' ).is( ':checked' ),
					translateSlug = $wrapper.find( '#translate_slug' ).is( ':checked' ),
					translateSeo = $wrapper.find( '#translate_seo' ).is( ':checked' ),
					tranzly_saveas = $wrapper.find( '#tranzly_saveas' ).is( ':checked' ),
					gutenbergActive = $wrapper.find( '[name="gutenberg_active"]' ).val(),
					postId = $wrapper.find( '[name="post_id"]' ).val();
					
				$errorWrapper.css( 'display', 'none' );
				$errorWrapper.html( '' );
				$btn.addClass( 'button-disabled' );
				$loader.css( 'visibility', 'visible' );
				var tranzly_status;
				if (tranzly_saveas) {
					tranzly_status='draft';
				}else{
					tranzly_status='publish';
				}

				const data = {
					tranzly_nonce: nonce,
					post_id: postId,
					source_lang: sourceLang,
					target_lang: targetLang,
					translate_atts: translateAtts,
					translate_slug: translateSlug,
					translate_seo: translateSeo,
					gutenberg_active: gutenbergActive,
					tranzly_post_status: tranzly_status,
					action: 'tranzly_generate',
				};

				$.ajax( {
					type: 'POST',
					url: tranzlyParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						$( 'body' ).trigger( 'tranzly_after_ajax_response', [ response ] );

						if ( response.error.length ) {
							$errorWrapper.css( 'display', 'block' );
							$errorWrapper.html( '<p>' + response.error + '</p>' );
						} else if ( response.success ) {
							  $('#generate_new_page_url').attr('href', tranzlyParams.admin_post_url+'?post='+response.translated+'&action=edit');
							  //$('#generate_new').show();
							  location.reload();
						}
						$( 'body' ).trigger( 'tranzly_before_hiding_loader', [ response ] );
						$btn.removeClass( 'button-disabled' );
						$loader.removeAttr( 'style' );

					},
				} );
			} );

			$( '.tranzly-generate-manual-btn' ).on( 'click', function( e ) {

				e.preventDefault();
				const $btn = $( this ),
					$wrapper = $btn.closest( '.tranzly-translator-meta-box' ),
					$loader = $wrapper.find( '.tranzly_spinner_div' ),
					$errorWrapper = $wrapper.find( '.tranzly-translation-error' ),
					nonce = $wrapper.find( '[name="tranzly_meta_box_nonce"]' ).val(),
					sourceLang = $wrapper.find( '#source_lang' ).val(),
					targetLang = $wrapper.find( '#target_lang' ).val(),
					translateAtts = $wrapper.find( '#translate_atts' ).is( ':checked' ),
					translateSlug = $wrapper.find( '#translate_slug' ).is( ':checked' ),
					translateSeo = $wrapper.find( '#translate_seo' ).is( ':checked' ),
					tranzly_saveas = $wrapper.find( '#tranzly_saveas' ).is( ':checked' ),
					manual_translate = 'manual_translate',
					gutenbergActive = $wrapper.find( '[name="gutenberg_active"]' ).val(),
					postId = $wrapper.find( '[name="post_id"]' ).val();
					
				$errorWrapper.css( 'display', 'none' );
				$errorWrapper.html( '' );
				$btn.addClass( 'button-disabled' );
				$loader.css( 'visibility', 'visible' );
				var tranzly_status;
				if (tranzly_saveas) {
					tranzly_status='draft';
				}else{
					tranzly_status='publish';
				}

				const data = {
					tranzly_nonce: nonce,
					post_id: postId,
					source_lang: sourceLang,
					target_lang: targetLang,
					translate_atts: translateAtts,
					translate_slug: translateSlug,
					translate_seo: translateSeo,
					gutenberg_active: gutenbergActive,
					tranzly_post_status: tranzly_status,
					tranzly_manual_translate: manual_translate,
					action: 'tranzly_generate',
				};

				$.ajax( {
					type: 'POST',
					url: tranzlyParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						$( 'body' ).trigger( 'tranzly_after_ajax_response', [ response ] );

						if ( response.error.length ) {
							$errorWrapper.css( 'display', 'block' );
							$errorWrapper.html( '<p>' + response.error + '</p>' );
						} else if ( response.success ) {
							  $('#generate_new_page_url').attr('href', tranzlyParams.admin_post_url+'?post='+response.translated+'&action=edit');
							  //$('#generate_new').show();
							  location.reload();
						}
						$( 'body' ).trigger( 'tranzly_before_hiding_loader', [ response ] );
						$btn.removeClass( 'button-disabled' );
						$loader.removeAttr( 'style' );

					},
				} );
			} );
			
		

		$( '.tranzly-translate-btn' ).on( 'click', function( e ) {
			e.preventDefault();

			const $btn = $( this ),
				$wrapper = $btn.closest( '.tranzly-translator-meta-box' ),
				$loader = $wrapper.find( '.tranzly_spinner_div' ),
				$errorWrapper = $wrapper.find( '.tranzly-translation-error' ),
				nonce = $wrapper.find( '[name="tranzly_meta_box_nonce"]' ).val(),
				sourceLang = $wrapper.find( '#source_lang' ).val(),
				targetLang = $wrapper.find( '#target_lang' ).val(),
				translateAtts = $wrapper.find( '#translate_atts' ).is( ':checked' ),
				translateSlug = $wrapper.find( '#translate_slug' ).is( ':checked' ),
				translateSeo = $wrapper.find( '#translate_seo' ).is( ':checked' ),
				gutenbergActive = $wrapper.find( '[name="gutenberg_active"]' ).val(),
				postId = $wrapper.find( '[name="post_id"]' ).val();

			$errorWrapper.css( 'display', 'none' );
			$errorWrapper.html( '' );
			$btn.addClass( 'button-disabled' );
			$loader.css( 'visibility', 'visible' );

			const data = {
				tranzly_nonce: nonce,
				post_id: postId,
				source_lang: sourceLang,
				target_lang: targetLang,
				translate_atts: translateAtts,
				translate_slug: translateSlug,
				translate_seo: translateSeo,
				gutenberg_active: gutenbergActive,
				action: 'tranzly_translate',
			};

			$.ajax( {
				type: 'POST',
				url: tranzlyParams.ajaxurl,
				data,
				dataType: 'json',
				success( response ) {
					$( 'body' ).trigger( 'tranzly_after_ajax_response', [ response ] );

					if ( response.error.length ) {
						$errorWrapper.css( 'display', 'block' );
						$errorWrapper.html( '<p>' + response.error + '</p>' );
					} else if ( response.success ) {
						if ( gutenbergActive ) {
							// Reload the page
							location.reload();
						} else {
							location.reload();
							// // Replace the content with translated content
							// $( '#poststuff #title' ).val( response.translated.post_title );
							// tranzlySetWpEditorContent( response.translated.post_content );

							// if ( response.translated.post_name !== undefined ) {
							// 	$( '#editable-post-name, #editable-post-name-full' ).html( response.translated.post_name );
							// }
						}
					}

					$( 'body' ).trigger( 'tranzly_before_hiding_loader', [ response ] );

					$btn.removeClass( 'button-disabled' );
					$loader.removeAttr( 'style' );
				},
			} );
		} );

		function tranzlyGetWpEditorContent() {
			let content;

			if (
				'undefined' !== typeof window.tinyMCE &&
                window.tinyMCE.get( 'content' ) &&
                ! window.tinyMCE.get( 'content' ).isHidden()
			) {
				content = window.tinyMCE.get( 'content' ).getContent();
			} else {
				content = $( '#content' ).val();
			}

			return content.trim();
		}

		function tranzlySetWpEditorContent( newContent ) {
			if (
				'undefined' !== typeof window.tinyMCE &&
                window.tinyMCE.get( 'content' ) &&
                ! window.tinyMCE.get( 'content' ).isHidden()
			) {
				const editor = window.tinyMCE.get( 'content' );
				editor.setContent( newContent, { format: 'html' } );
			} else {
				$( '#content' ).val( newContent );
			}
		}

		function initSelect2() {
			if ( jQuery().select2 ) {
				$( '.tranzly-taxonomy-select' ).select2();
			}
		}

		$( '.toplevel_page_tranzly #post_type' ).on( 'change', function() {
			const that = $( this ),
				$form = that.closest( 'form' ),
				postType = that.val(),
				$spinner = that.parent().find( '.tranzly-spinner' ),
				$wrapper = $( '.tranzly-dynamic-taxonomy-filter-wrapper' );

			let data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();

			data += '&action=tranzly_load_taxonomy';
			that.attr( 'disabled', 'disabled' );
			$spinner.css( 'visibility', 'visible' );
			$wrapper.html( '' );

			if ( ! postType ) {
				that.removeAttr( 'disabled' );
				$spinner.css( 'visibility', 'hidden' );
				return;
			}

			$.ajax( {
				type: 'POST',
				url: tranzlyParams.ajaxurl,
				data,
				dataType: 'html',
				success( response ) {
					$wrapper.html( response );
					// Attach select2
					initSelect2();

					that.removeAttr( 'disabled' );
					$spinner.css( 'visibility', 'hidden' );
				},
			} );
		} );

		function processTranslation( $form, data ) {
			const $progressWrapper = $form.parent( '.wrap' ).find( '.tranzly-translation-progress' ),
				$progressbar = $progressWrapper.find( '.progressbar > div' ),
				$progressCount = $progressWrapper.find( '.count' ),
				$progressTotal = $progressWrapper.find( '.total' ),
				$submitBtn = $form.find( '.button' ),
				$mylod = $form.find( '.mylod' ),
				$messageWrapper = $progressWrapper.find( '.tranzly-success-message' );

			$.ajax( {
				url: tranzlyParams.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data,
				success( response ) {
					if ( response.success === 'true' ) {
						$progressCount.html( response.data.count );
						$progressbar.css( 'width', response.data.percentage + '%' );

						if ( ! $progressWrapper.hasClass( 'active' ) ) {
							$progressWrapper.addClass( 'active' );
						}

						if ( response.data.status === 'incomplete' ) {
							$progressTotal.html( response.data.total_posts );

							data += '&page=' + response.data.page;
							data += '&count=' + response.data.count;

							processTranslation( $form, data );
						} else {
							$submitBtn.removeAttr( 'disabled' );
							$mylod.hide();
							$messageWrapper.html( tranzlyParams.translationSuccessMessage );
						}
					} else {
						$mylod.hide();
						console.log( 'there was an error' );
					}
				},
			} ).fail( function( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			} );
		}

		function validateBeforeTranslation( $form, $submitBtn, $errorWrapper ) {
			let data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
			const $mylod = $form.find( '.mylod' );

			data += '&action=tranzly_validate_before_translate_posts';

			$.ajax( {
				type: 'POST',
				url: tranzlyParams.ajaxurl,
				data,
				dataType: 'json',
				success( response ) {
					if ( 'false' === response.valid ) {
						const messages = response.messages;
						let markup = '';

						for ( let i = 0; i < messages.length; i++ ) {
							markup += '<p>' + messages[ i ] + '</p>';
						}

						$errorWrapper.html( markup );
						$errorWrapper.css( 'display', 'block' );
						$submitBtn.removeAttr( 'disabled' );
					} else {
						const $progressWrapper = $form.parent( '.wrap' ).find( '.tranzly-translation-progress' ),
							$progressInfo = $progressWrapper.find( '.progress-info' );

						data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
						data += '&action=tranzly_translate_posts';

						$progressInfo.html( tranzlyParams.total_translated_placeholder );
						$progressWrapper.addClass( 'active' );
						$mylod.show();
						processTranslation( $form, data );
					}
				},
			} );
		}

		$( '.toplevel_page_tranzly #translate_posts_btn' ).on( 'click', function( e ) {
			e.preventDefault();
			// console.log('admin all translate');

			const $form = $( this ).closest( 'form' ),
				$progressWrapper = $form.parent().find( '.tranzly-translation-progress' ),
				$progressbar = $progressWrapper.find( '.progressbar > div' ),
				$submitBtn = $form.find( '.button' ),
				$mylod = $form.find( '.mylod' ),
				$messageWrapper = $progressWrapper.find( '.tranzly-success-message' ),
				$errorWrapper = $form.parent().find( '.tranzly-translation-error' );

			$progressbar.css( 'width', '0' );
			$messageWrapper.html( '' );
			$errorWrapper.html( '' );
			$errorWrapper.css( 'display', 'none' );
			$submitBtn.attr( 'disabled', 'disabled' );
			

			// Validate the user inputs.
			validateBeforeTranslation( $form, $submitBtn, $errorWrapper );
		} );


		//////
			function tranzly_processTranslation( $form, data ) {
				const $progressWrapper = $form.parent( '.wrap' ).find( '.tranzly-translation-progress' ),
					$progressbar = $progressWrapper.find( '.progressbar > div' ),
					$progressCount = $progressWrapper.find( '.count' ),
					$progressTotal = $progressWrapper.find( '.total' ),
					$submitBtn = $form.find( '.button' ),
					$mylod = $form.find( '.mylod' ),
					$messageWrapper = $progressWrapper.find( '.tranzly-success-message' );

				$.ajax( {
					url: tranzlyParams.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data,
					success( response ) {
						if ( response.success === 'true' ) {
							$progressCount.html( response.data.count );
							$progressbar.css( 'width', response.data.percentage + '%' );
							if ( ! $progressWrapper.hasClass( 'active' ) ) {
								$progressWrapper.addClass( 'active' );
							}
							if ( response.data.status === 'incomplete' ) {
								$progressTotal.html( response.data.total_posts );
								data += '&page=' + response.data.page;
								data += '&count=' + response.data.count;
								tranzly_processTranslation( $form, data );
							} else {
								$submitBtn.removeAttr( 'disabled' );
								$mylod.hide();
								$messageWrapper.html( tranzlyParams.translationSuccessMessage );
							}
						} else {
							$mylod.hide();
							console.log( 'there was an error' );
						}
					},
				} ).fail( function( response ) {
					if ( window.console && window.console.log ) {
						console.log( response );
					}
				} );
			}
			function tranzly_validateBeforeTranslation( $form, $submitBtn, $errorWrapper ) {
				let data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
				const $mylod = $form.find( '.mylod' );
				data += '&action=tranzly_validate_before_translate_posts';

				$.ajax( {
					type: 'POST',
					url: tranzlyParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						if ( 'false' === response.valid ) {
							const messages = response.messages;
							let markup = '';
							for ( let i = 0; i < messages.length; i++ ) {
								markup += '<p>' + messages[ i ] + '</p>';
							}
							$errorWrapper.html( markup );
							$errorWrapper.css( 'display', 'block' );
							$submitBtn.removeAttr( 'disabled' );
						} else {
							const $progressWrapper = $form.parent( '.wrap' ).find( '.tranzly-translation-progress' ),
								$progressInfo = $progressWrapper.find( '.progress-info' );

							data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
							data += '&action=tranzly_generate_posts';

							$progressInfo.html( tranzlyParams.total_translated_placeholder );
							$progressWrapper.addClass( 'active' );
							$mylod.show();
							tranzly_processTranslation( $form, data );
						}
					},
				} );
			}
			$('.toplevel_page_tranzly #generate_posts_btn' ).on('click', function(e) {
				e.preventDefault();
				 console.log('admin all Generate');
				const $form = $( this ).closest( 'form' ),
					$progressWrapper = $form.parent().find( '.tranzly-translation-progress' ),
					$progressbar = $progressWrapper.find( '.progressbar > div' ),
					$submitBtn = $form.find( '.button' ),
					$mylod = $form.find( '.mylod' ),
					$messageWrapper = $progressWrapper.find( '.tranzly-success-message' ),
					$errorWrapper = $form.parent().find( '.tranzly-translation-error' );
				$progressbar.css( 'width', '0' );
				$messageWrapper.html( '' );
				$errorWrapper.html( '' );
				$errorWrapper.css( 'display', 'none' );
				$submitBtn.attr( 'disabled', 'disabled' );


				// Validate the user inputs.
				tranzly_validateBeforeTranslation( $form, $submitBtn, $errorWrapper );
			} );
		/////

	} );
}( jQuery ) );
