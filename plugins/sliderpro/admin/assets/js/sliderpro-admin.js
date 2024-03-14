/*
 * ======================================================================
 * Slider Pro Admin
 * ======================================================================
 */
(function( $ ) {

	var SliderProAdmin = {

		/**
		 * Stores the data for all slides in the slider.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Array}
		 */
		slides: [],

		/**
		 * Keeps a count for the slides in the slider.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Int}
		 */
		slideCounter: 0,

		/**
		 * Stores all posts names and their taxonomies.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Object}
		 */
		postsData: {},

		/**
		 * Indicates if the preview images from the slides
		 * can be resized.
		 * This prevents resizing the images too often.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Boolean}
		 */
		allowSlideImageResize: true,

		/**
		 * Initializes the functionality for a single slider page
		 * or for the page that contains all the sliders.
		 *
		 * @since 4.0.0
		 */
		init: function() {
			if ( sp_js_vars.page === 'single' ) {
				this.initSingleSliderPage();
			} else if ( sp_js_vars.page === 'all' ) {
				this.initAllSlidersPage();
			}
		},

		/*
		 * ======================================================================
		 * Slider functions
		 * ======================================================================
		 */
		
		/**
		 * Initializes the functionality for a single slider page
		 * by adding all the necessary event listeners.
		 *
		 * @since 4.0.0
		 */
		initSingleSliderPage: function() {
			var that = this;

			this.initSlides();

			if ( parseInt( sp_js_vars.id, 10 ) !== -1 ) {
				this.loadSliderData( function() {
					that.checkSlideImageSize();
					$( '.slides-container' ).attr( 'data-loaded', 'true' );
				});
			}

			$( 'form' ).on( 'submit', function( event ) {
				event.preventDefault();
				that.saveSlider();

				that.checkSlideImageSize();
			});

			$( '.preview-slider' ).on( 'click', function( event ) {
				event.preventDefault();
				that.previewSlider();
			});

			$( '.update-presets' ).on( 'click', function( event ) {
				event.preventDefault();
				that.updatePresets( $( this ) );
			});

			$( '.slider-setting-presets' ).on( 'change', function( event ) {
				event.preventDefault();
				that.updateSettings( $( this ).val() );
			});

			$( '.add-slide, .slide-type a[data-type="empty"]' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addEmptySlide();
			});

			$( '.slide-type a[data-type="image"]' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addImageSlides();
			});

			$( '.slide-type a[data-type="posts"]' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addPostsSlides();
			});

			$( '.slide-type a[data-type="gallery"]' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addGallerySlides();
			});

			$( '.slide-type a[data-type="flickr"]' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addFlickrSlides();
			});

			$( '.add-breakpoint' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addBreakpoint();
			});

			$( '.breakpoints' ).on( 'click', '.breakpoint-setting-name a', function( event ) {
				event.preventDefault();

				var name = $( this ).attr( 'data-type' ),
					context = $( this ).parents( '.breakpoint' ).find( '.breakpoint-settings' );

				that.addBreakpointSetting( name, context );
			});

			$( '.breakpoints' ).on( 'click', '.remove-breakpoint', function( event ) {
				$( this ).parents( '.breakpoint' ).remove();
			});

			$( '.breakpoints' ).on( 'click', '.remove-breakpoint-setting', function( event ) {
				$( this ).parents( 'tr' ).remove();
			});

			$( '.breakpoints' ).lightSortable( {
				children: '.breakpoint',
				placeholder: ''
			} );

			$( '.postbox .hndle, .postbox .handlediv' ).on( 'click', function() {
				var postbox = $( this ).parent( '.postbox' );
				
				if ( postbox.hasClass( 'closed' ) === true ) {
					postbox.removeClass( 'closed' );
				} else {
					postbox.addClass( 'closed' );
				}
			});

			$( '.sidebar-settings' ).on( 'mouseover', 'label', function() {
				that.showInfo( $( this ) );
			});

			$( '.image-size-warning-close' ).click(function( event ) {
				event.preventDefault();

				$( '.image-size-warning' ).remove();

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { 
						action: 'sliderpro_close_image_size_warning',
						nonce: sp_js_vars.cp_nonce
					}
				});
			});

			$( window ).resize(function() {
				if ( that.allowSlideImageResize === true ) {
					that.resizeSlideImages();
					that.allowSlideImageResize = false;

					setTimeout( function() {
						that.resizeSlideImages();
						that.allowSlideImageResize = true;
					}, 250 );
				}
			});
		},

		/**
		 * Initializes the functionality for the page that contains
		 * all the sliders by adding all the necessary event listeners.
		 *
		 * @since 4.0.0
		 */
		initAllSlidersPage: function() {
			var that = this;

			$( '.getting-started-close' ).click(function( event ) {
				event.preventDefault();

				$( '.getting-started-info' ).hide();

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { 
						action: 'sliderpro_close_getting_started',
						nonce: sp_js_vars.cp_nonce
					}
				});
			});

			$( '.custom-css-js-warning-close' ).click(function( event ) {
				event.preventDefault();

				var dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="modal-window-container">' +
					'	<div class="modal-window delete-slider-dialog">' +
					'		<p class="dialog-question">' + sp_js_vars.remove_custom_css_js_warning + '</p>' +
					'		<div class="dialog-buttons">' +
					'			<a class="button dialog-ok" href="#">' + sp_js_vars.yes + '</a>' +
					'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
					'		</div>' +
					'	</div>' +
					'</div>'
				).appendTo( 'body' );

				dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
					event.preventDefault();

					$( '.custom-css-js-warning' ).hide();

					$.ajax({
						url: sp_js_vars.ajaxurl,
						type: 'post',
						data: {
							action: 'sliderpro_close_custom_css_js_warning',
							nonce: sp_js_vars.cp_nonce
						}
					});

					dialog.remove();
				});

				dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
					event.preventDefault();
					dialog.remove();
				});

				dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
					dialog.remove();
				});
			});

			$( '.sliders-list' ).on( 'click', '.preview-slider', function( event ) {
				event.preventDefault();
				that.previewSliderAll( $( this ) );
			});

			$( '.sliders-list' ).on( 'click', '.delete-slider', function( event ) {
				event.preventDefault();
				that.deleteSlider( $( this ) );
			});

			$( '.sliders-list' ).on( 'click', '.duplicate-slider', function( event ) {
				event.preventDefault();
				that.duplicateSlider( $( this ) );
			});

			$( '.sliders-list' ).on( 'click', '.export-slider', function( event ) {
				event.preventDefault();
				that.exportSlider( $( this ) );
			});

			$( '.import-slider' ).on( 'click', function( event ) {
				var url = $.lightURLParse( $( this ).attr( 'href' ) ),
					currentPage = typeof url.sp_page !== 'undefined' ? parseInt( url.sp_page, 10 ) : 1,
					totalPages = typeof url.pages !== 'undefined' ? parseInt( url.pages, 10 ) : 1;

				event.preventDefault();

				ImportWindow.open();
				ImportWindow.setPaginationParams({
					currentPage: currentPage,
					totalPages: totalPages
				})
			});

			$( '.clear-all-cache' ).on( 'click', function( event ) {
				event.preventDefault();

				$( '.clear-cache-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

				var nonce = $( this ).attr( 'data-nonce' );

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'sliderpro_clear_all_cache', nonce: nonce },
					complete: function( data ) {
						$( '.clear-cache-spinner' ).css( { 'display': '', 'visibility': '' } );
					}
				});
			});
		},

		/**
		 * Load the slider slider data.
		 * 
		 * Send an AJAX request with the slider id and the nonce, and
		 * retrieve all the slider's database data. Then, assign the
		 * data to the slides.
		 *
		 * @since 4.0.0
		 */
		loadSliderData: function( callback ) {
			var that = this;

			$( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'sliderpro_get_slider_data', id: sp_js_vars.id, nonce: sp_js_vars.lad_nonce },
				complete: function( data ) {
					var sliderData = $.parseJSON( data.responseText );

					$.each( sliderData.slides, function( index, slide ) {
						var slideData = {
							mainImage: {},
							thumbnail: {},
							caption: slide.caption,
							layers: slide.layers,
							html: slide.html,
							settings: $.isArray( slide.settings ) ? {} : slide.settings
						};

						$.each( slide, function( settingName, settingValue ) {
							if ( settingName.indexOf( 'main_image' ) !== -1 ) {
								slideData.mainImage[ settingName ] = settingValue;
							} else if ( settingName.indexOf( 'thumbnail' ) !== -1 ) {
								slideData.thumbnail[ settingName ] = settingValue;
							}
						});

						that.getSlide( index ).setData( 'all', slideData );
					});

					$( '.slide-spinner' ).css( { 'display': '', 'visibility': '' } );

					callback();
				}
			});
		},

		/**
		 * Save the slider's data.
		 * 
		 * Get the slider's data and send it to the server with AJAX. If
		 * a new slider was created, redirect to the slider's edit page.
		 *
		 * @since 4.0.0
		 */
		saveSlider: function() {
			var sliderData = this.getSliderData();
			sliderData[ 'nonce' ] = sp_js_vars.sa_nonce;
			sliderData[ 'action' ] = 'save';

			var sliderDataString = JSON.stringify( sliderData );

			var spinner = $( '.update-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_save_slider', data: sliderDataString },
				complete: function( data ) {
					spinner.css( { 'display': '', 'visibility': '' } );

					if ( parseInt( sp_js_vars.id, 10 ) === -1 && isNaN( data.responseText ) === false ) {
						$( 'h2' ).after( '<div class="updated"><p>' + sp_js_vars.slider_create + '</p></div>' );

						window.location = sp_js_vars.admin + '?page=sliderpro&id=' + data.responseText + '&action=edit';
					} else if ( $( '.updated' ).length === 0 ) {
						$( 'h2' ).after( '<div class="updated"><p>' + sp_js_vars.slider_update + '</p></div>' );
					}
				}
			});
		},

		/**
		 * Get the slider's data.
		 * 
		 * Read the value of the sidebar settings, including the breakpoints,
		 * the slides state, the name of the slider, the id, and get the
		 * data for each slide.
		 *
		 * @since 4.0.0
		 * 
		 * @return {Object} The slider data.
		 */
		getSliderData: function() {
			var that = this,
				sliderData = {
					'id': sp_js_vars.id,
					'name': $( 'input#title' ).val(),
					'settings': {},
					'slides': [],
					'panels_state': {}
				},
				breakpoints = [];

			$( '.slides-container' ).find( '.slide' ).each(function( index ) {
				var $slide = $( this ),
					slideData = that.getSlide( parseInt( $slide.attr('data-id'), 10 ) ).getData( 'all' );
				
				slideData.position = parseInt( $slide.attr( 'data-position' ), 10 );

				sliderData.slides[ index ] = slideData;
			});

			$( '.sidebar-settings' ).find( '.setting' ).each(function() {
				var setting = $( this );
				sliderData.settings[ setting.attr( 'name' ) ] = setting.attr( 'type' ) === 'checkbox' ? setting.is( ':checked' ) : setting.val();
			});

			$( '.breakpoints' ).find( '.breakpoint' ).each(function() {
				var breakpointGroup = $( this ),
					breakpoint = { 'breakpoint_width': breakpointGroup.find( 'input[name="breakpoint_width"]' ).val() };

				breakpointGroup.find( '.breakpoint-setting' ).each(function() {
					var breakpointSetting = $( this );

					breakpoint[ breakpointSetting.attr( 'name' ) ] = breakpointSetting.attr( 'type' ) === 'checkbox' ? breakpointSetting.is( ':checked' ) : breakpointSetting.val();
				});

				breakpoints.push( breakpoint );
			});

			if ( breakpoints.length > 0 ) {
				sliderData.settings.breakpoints = breakpoints;
			}

			$( '.sidebar-settings' ).find( '.postbox' ).each(function() {
				var slide = $( this );
				sliderData.panels_state[ slide.attr( 'data-name' ) ] = slide.hasClass( 'closed' ) ? 'closed' : '';
			});

			return sliderData;
		},

		/**
		 * Preview the slider in the slider's edit page.
		 *
		 * @since 4.0.0
		 */
		previewSlider: function() {
			PreviewWindow.open( this.getSliderData() );
		},

		/**
		 * Preview the slider in the sliders' list page.
		 *
		 * @since 4.0.0
		 */
		previewSliderAll: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.lad_nonce,
				id = parseInt( url.id, 10 );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'sliderpro_get_slider_data', id: id, nonce: nonce },
				complete: function( data ) {
					var sliderData = $.parseJSON( data.responseText );

					PreviewWindow.open( sliderData );
				}
			});
		},

		/**
		 * Update the settings presents
		 * 
		 * @since 4.0.0
		 */
		updatePresets: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				method = url.method,
				nonce = url.up_nonce,
				dialog,
				selectedPreset = $( '.slider-setting-presets' ).val();

			if ( ( method === 'update' || method === 'delete' ) && selectedPreset === null ) {
				return;
			}

			if ( method === 'save-new' ) {
				dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="modal-window-container">' +
					'	<div class="modal-window save-new-preset-dialog">' +
					'		<label>' + sp_js_vars.preset_name + '</label><input type="text" value="" class="preset-name">' +
					'		<div class="dialog-buttons">' +
					'			<a class="button dialog-ok" href="#">' + sp_js_vars.save + '</a>' +
					'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
					'		</div>' +
					'	</div>' +
					'</div>'
				).appendTo( 'body' );
			} else if ( method === 'update' ) {
				dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="modal-window-container">' +
					'	<div class="modal-window delete-preset-dialog">' +
					'		<p>' + sp_js_vars.preset_update + '</p>' +
					'		<div class="dialog-buttons">' +
					'			<a class="button dialog-ok" href="#">' + sp_js_vars.yes + '</a>' +
					'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
					'		</div>' +
					'	</div>' +
					'</div>'
				).appendTo( 'body' );
			} else if ( method === 'delete' ) {
				dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="modal-window-container">' +
					'	<div class="modal-window delete-preset-dialog">' +
					'		<p>' + sp_js_vars.preset_delete + '</p>' +
					'		<div class="dialog-buttons">' +
					'			<a class="button dialog-ok" href="#">' + sp_js_vars.yes + '</a>' +
					'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
					'		</div>' +
					'	</div>' +
					'</div>'
				).appendTo( 'body' );
			}

			dialog.find( '.dialog-ok' ).on( 'click', function( event ) {
				event.preventDefault();

				var presetName = ( method === 'save-new' ) ? dialog.find( '.preset-name' ).val() : $( '.slider-setting-presets' ).val(),
					settings = {},
					breakpoints = [];

				if ( method === 'save-new' && ( presetName === '' || $( '.slider-setting-presets' ).find( 'option[value="' + presetName + '"]' ).length !== 0 ) ) {
					return;
				}

				$( '.sidebar-settings' ).find( '.setting' ).each(function() {
					var setting = $( this );
					settings[ setting.attr( 'name' ) ] = setting.attr( 'type' ) === 'checkbox' ? setting.is( ':checked' ) : setting.val();
				});

				$( '.breakpoints' ).find( '.breakpoint' ).each(function() {
					var breakpointGroup = $( this ),
						breakpoint = { 'breakpoint_width': breakpointGroup.find( 'input[name="breakpoint_width"]' ).val() };

					breakpointGroup.find( '.breakpoint-setting' ).each(function() {
						var breakpointSetting = $( this );

						breakpoint[ breakpointSetting.attr( 'name' ) ] = breakpointSetting.attr( 'type' ) === 'checkbox' ? breakpointSetting.is( ':checked' ) : breakpointSetting.val();
					});

					breakpoints.push( breakpoint );
				});

				if ( breakpoints.length > 0 ) {
					settings.breakpoints = breakpoints;
				}

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'sliderpro_update_presets', method: method, name: presetName, settings: JSON.stringify( settings ), nonce: nonce },
					complete: function( data ) {
						if ( method === 'save-new' ) {
							$( '<option value="' + presetName + '">' + presetName + '</option>' ).appendTo( $( '.slider-setting-presets' ) );
						} else if ( method === 'delete' ) {
							$( '.slider-setting-presets' ).find( 'option[value="' + presetName + '"]' ).remove();
						}
					}
				});

				dialog.remove();
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		/**
		 * Update the settings based on the selected preset.
		 * 
		 * @since 4.0.0
		 *
		 * @param  {stirng} presetName The name of the selected preset.
		 */
		updateSettings: function( presetName ) {
			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'sliderpro_get_preset_settings', name: presetName },
				complete: function( data ) {
					var settings = $.parseJSON( data.responseText );

					$.each( settings, function( name, value ) {
						var $settingField = $( '.sidebar-settings' ).find( '.setting[name="' + name + '"]' );

						if ( $settingField.attr( 'type' ) === 'checkbox' ) {
							if ( value === true ) {
								$settingField.prop( 'checked', true );
							} else if ( value === false ) {
								$settingField.prop( 'checked', false );
							}
						} else {
							$settingField.val( value );
						}

						$( '.breakpoints' ).empty();

						if ( name === 'breakpoints' ) {
							$.ajax({
								url: sp_js_vars.ajaxurl,
								type: 'get',
								data: { action: 'sliderpro_get_breakpoints_preset', data: JSON.stringify( value ) },
								complete: function( data ) {
									$( data.responseText ).appendTo( $( '.breakpoints' ) );
								}
							});
						}
					});
				}
			});
		},

		/**
		 * Delete a slider.
		 *
		 * This is called in the sliders' list page upon clicking
		 * the 'Delete' link.
		 *
		 * It displays a confirmation dialog before sending the request
		 * for deletion to the server.
		 *
		 * The slider's row is removed after the slider is deleted
		 * server-side.
		 * 
		 * @since 4.0.0
		 *
		 * @param  {jQuery Object} target The clicked 'Delete' link.
		 */
		deleteSlider: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.da_nonce,
				id = parseInt( url.id, 10 ),
				row = target.parents( 'tr' );

			var dialog = $(
				'<div class="modal-overlay"></div>' +
				'<div class="modal-window-container">' +
				'	<div class="modal-window delete-slider-dialog">' +
				'		<p class="dialog-question">' + sp_js_vars.slider_delete + '</p>' +
				'		<div class="dialog-buttons">' +
				'			<a class="button dialog-ok" href="#">' + sp_js_vars.yes + '</a>' +
				'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
				'		</div>' +
				'	</div>' +
				'</div>'
			).appendTo( 'body' );

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
				event.preventDefault();

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'sliderpro_delete_slider', id: id, nonce: nonce },
					complete: function( data ) {
						if ( id === parseInt( data.responseText, 10 ) ) {
							row.fadeOut( 300, function() {
								row.remove();
							});
						}
					}
				});

				dialog.remove();
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		/**
		 * Duplicate a slider.
		 *
		 * This is called in the sliders' list page upon clicking
		 * the 'Duplicate' link.
		 *
		 * A new row is added in the list for the newly created
		 * slider.
		 * 
		 * @since 4.0.0
		 *
		 * @param  {jQuery Object} target The clicked 'Duplicate' link.
		 */
		duplicateSlider: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.dua_nonce,
				id = parseInt( url.id, 10 ),
				totalPages = typeof url.pages !== 'undefined' ? parseInt( url.pages, 10 ) : 1,
				currentPage = typeof url.sp_page !== 'undefined' ? parseInt( url.sp_page, 10 ) : 1;

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_duplicate_slider',
					id: id,
					nonce: nonce,
					total_pages: totalPages,
					current_page: currentPage
				},
				complete: function( data ) {
					if ( totalPages > 1 && currentPage !== totalPages ) {
						window.location = sp_js_vars.admin + '?page=sliderpro&sp_page=' + totalPages;
					} else {
						var row = $( data.responseText ).appendTo( $( '.sliders-list tbody' ) );
						row.hide().fadeIn();
					}
				}
			});
		},

		/**
		 * Open the slider export window.
		 *
		 * This is called in the sliders' list page upon clicking
		 * the 'Export' link.
		 * 
		 * @since 4.0.0
		 *
		 * @param  {jQuery Object} target The clicked 'Export' link.
		 */
		exportSlider: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.ea_nonce,
				id = parseInt( url.id, 10 );

			ExportWindow.open( id, nonce );
		},

		/*
		 * ======================================================================
		 * Slide functions executed by the slider
		 * ======================================================================
		 */
		
		/**
		 * Initialize all the existing slides when the page loads.
		 * 
		 * @since 4.0.0
		 */
		initSlides: function() {
			var that = this;

			$( '.slides-container' ).find( '.slide' ).each(function( index ) {
				that.initSlide( $( this ) );
			});

			$( '.slides-container' ).lightSortable( {
				children: '.slide',
				placeholder: 'slide slide-placeholder',
				sortEnd: function( event ) {
					$( '.slide' ).each(function( index ) {
						$( this ).attr( 'data-position', index );
					});
				}
			} );
		},

		/**
		 * Initialize an individual slide.
		 *
		 * Creates a new instance of the Slide object and adds it 
		 * to the array of slides.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {jQuery Object} element The slide element.
		 * @param  {Object}        data    The slide's data.
		 */
		initSlide: function( element, data ) {
			var that = this,
				$slide = element,
				slide = new Slide( $slide, this.slideCounter, data );

			this.slides.push( slide );

			slide.on( 'duplicateSlide', function( event ) {
				that.duplicateSlide( event.slideData );
			});

			slide.on( 'deleteSlide', function( event ) {
				that.deleteSlide( event.id );
			});

			$slide.attr( 'data-id', this.slideCounter );
			$slide.attr( 'data-position', this.slideCounter );

			this.slideCounter++;
		},

		/**
		 * Return the slide data.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int}    id The id of the slide to retrieve.
		 * @return {Object}    The data of the retrieved slide.
		 */
		getSlide: function( id ) {
			var that = this,
				selectedSlide;

			$.each( that.slides, function( index, slide ) {
				if ( slide.id === id ) {
					selectedSlide = slide;
					return false;
				}
			});

			return selectedSlide;
		},

		/**
		 * Duplicate an individual slide.
		 *
		 * The main image is sent to the server for the purpose
		 * of adding it to the slide preview, while the rest of the data
		 * is passed with JS.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Object} slideData The data of the object to be duplicated.
		 */
		duplicateSlide: function( slideData ) {
			var that = this,
				newSlideData = $.extend( true, {}, slideData ),
				data = [{
					settings: {
						content_type: newSlideData.settings.content_type
					},
					main_image_source: newSlideData.mainImage.main_image_source
				}];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_add_slides', data: JSON.stringify( data ) },
				complete: function( data ) {
					var slide = $( data.responseText ).appendTo( $( '.slides-container' ) );

					that.initSlide( slide, newSlideData );
				}
			});
		},

		/**
		 * Delete an individual slide.
		 *
		 * The main image is sent to the server for the purpose
		 * of adding it to the slide preview, while the rest of the data
		 * is passed with JS.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int} id The id of the slide to be deleted.
		 */
		deleteSlide: function( id ) {
			var that = this,
				slide = that.getSlide( id ),
				dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="modal-window-container">' +
					'	<div class="modal-window delete-slide-dialog">' +
					'		<p class="dialog-question">' + sp_js_vars.slide_delete + '</p>' +
					'		<div class="dialog-buttons">' +
					'			<a class="button dialog-ok" href="#">' + sp_js_vars.yes + '</a>' +
					'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
					'		</div>' +
					'	</div>' +
					'</div>').appendTo( 'body' );

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
				event.preventDefault();

				slide.off( 'duplicateSlide' );
				slide.off( 'deleteSlide' );
				slide.remove();
				dialog.remove();

				that.slides.splice( $.inArray( slide, that.slides ), 1 );
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		/**
		 * Add an empty slide.
		 *
		 * @since 4.0.0
		 */
		addEmptySlide: function() {
			var that = this;

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_add_slides' },
				complete: function( data ) {
					var slide = $( data.responseText ).appendTo( $( '.slides-container' ) );

					that.initSlide( slide );
				}
			});
		},

		/**
		 * Add image slide(s).
		 *
		 * Add one or multiple slides pre-populated with image data.
		 *
		 * @since 4.0.0
		 */
		addImageSlides: function() {
			var that = this;
			
			MediaLoader.open(function( selection ) {
				var images = [];

				$.each( selection, function( index, image ) {
					images.push({
						main_image_id: image.id,
						main_image_source: image.url,
						main_image_alt: image.alt,
						main_image_title: image.title,
						main_image_width: image.width,
						main_image_height: image.height
					});
				});

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'sliderpro_add_slides', data: JSON.stringify( images ) },
					complete: function( data ) {
						var lastIndex = $( '.slides-container' ).find( '.slide' ).length - 1,
							slides = $( '.slides-container' ).append( data.responseText ),
							indexes = lastIndex === -1 ? '' : ':gt(' + lastIndex + ')';

						slides.find( '.slide' + indexes ).each(function( index ) {
							var slide = $( this );

							that.initSlide( slide, { mainImage: images[ index ], thumbnail: {}, caption: '', layers: {}, html: '', settings: {} } );
						});

						SliderProAdmin.checkSlideImageSize();
					}
				});
			});
		},

		/**
		 * Add posts slide.
		 *
		 * Add a posts slide and pre-populate it with dynamic tags.
		 *
		 * Also, automatically open the Setting editor to allow the
		 * user to configurate the WordPress query.
		 *
		 * @since 4.0.0
		 */
		addPostsSlides: function() {
			var that = this,
				data =  [{
					settings: {
						content_type: 'posts'
					}
				}];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_add_slides', data: JSON.stringify( data ) },
				complete: function( data ) {
					var slide = $( data.responseText ).appendTo( $( '.slides-container' ) ),
						slideId = that.slideCounter;

					that.initSlide( slide, {
						mainImage: {
							main_image_source: '[sp_image_src]',
							main_image_alt: '[sp_image_alt]',
							main_image_link: '[sp_link_url]'
						},
						thumbnail: {},
						caption:'',
						layers: [
							{
								id: 1,
								name: 'Layer 1',
								type: 'paragraph',
								text: '[sp_title]',
								settings: {
									position: 'bottomLeft',
									horizontal: '0',
									vertical: '0',
									preset_styles: ['sp-black', 'sp-padding']
								}
							}
						],
						html: '',
						settings: {
							content_type: 'posts'
						}
					});

					SettingsEditor.open( slideId );
				}
			});
		},

		/**
		 * Add gallery slide.
		 *
		 * Add a gallery slide and pre-populate it with dynamic tags.
		 *
		 * Also, automatically open the Setting editor inform the user
		 * on how to use this slide type.
		 *
		 * @since 4.0.0
		 */
		addGallerySlides: function() {
			var that = this,
				data =  [{
					settings: {
						content_type: 'gallery'
					}
				}];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_add_slides', data: JSON.stringify( data ) },
				complete: function( data ) {
					var slide = $( data.responseText ).appendTo( $( '.slides-container' ) ),
						slideId = that.slideCounter;

					that.initSlide( slide, {
						mainImage: {
							main_image_source: '[sp_image_src]',
							main_image_alt: '[sp_image_alt]'
						},
						thumbnail: {},
						caption: '',
						layers: {},
						html: '',
						settings: {
							content_type: 'gallery'
						}
					});

					SettingsEditor.open( slideId );
				}
			});
		},

		/**
		 * Add Flickr slide.
		 *
		 * Add a Flickr slide and pre-populate it with dynamic tags.
		 *
		 * Also, automatically open the Setting editor to allow the
		 * user to configurate the Flickr query.
		 *
		 * @since 4.0.0
		 */
		addFlickrSlides: function() {
			var that = this,
				data =  [{
					settings: {
						content_type: 'flickr'
					}
				}];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_add_slides', data: JSON.stringify( data ) },
				complete: function( data ) {
					var slide = $( data.responseText ).appendTo( $( '.slides-container' ) ),
						slideId = that.slideCounter;

					that.initSlide( slide, {
						mainImage: {
							main_image_source: '[sp_image_src]',
							main_image_link: '[sp_image_link]'
						},
						thumbnail: {},
						caption: '',
						layers: [
							{
								id: 1,
								name: 'Layer 1',
								type: 'paragraph',
								text: '[sp_image_description]',
								settings: {
									position: 'bottomLeft',
									horizontal: '0',
									vertical: '0',
									preset_styles: ['sp-black', 'sp-padding']
								}
							}
						],
						html: '',
						settings: {
							content_type: 'flickr'
						}
					});

					SettingsEditor.open( slideId );
				}
			});
		},

		/*
		 * ======================================================================
		 * More slider functions
		 * ======================================================================
		 */
		
		/**
		 * Add a breakpoint fieldset.
		 *
		 * Also, try to automatically assigns the width of the breakpoint.
		 * 
		 * @since 4.0.0
		 */
		addBreakpoint: function() {
			var that = this,
				size = '',
				previousWidth = $( 'input[name="breakpoint_width"]' ).last().val();
			
			if ( typeof previousWidth === 'undefined' ) {
				size = '960';
			} else if ( previousWidth !== '' ) {
				size = previousWidth - 190;
			}

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'sliderpro_add_breakpoint', data: size },
				complete: function( data ) {
					$( data.responseText ).appendTo( $( '.breakpoints' ) );
				}
			});
		},

		/**
		 * Add a breakpoint setting.
		 * 
		 * @since 4.0.0
		 */
		addBreakpointSetting: function( name, context) {
			var that = this;

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'sliderpro_add_breakpoint_setting', data: name },
				complete: function( data ) {
					$( data.responseText ).appendTo( context );
				}
			});
		},

		/**
		 * Load the taxonomies for the selected post names and 
		 * pass all the returned data to the callback function.
		 *
		 * Only load the taxonomies for a particular post name if
		 * it's not already available in the 'postsData' property,
		 * which stores all the posts data loaded in a session.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Array}    posts    Array of post names.
		 * @param  {Function} callback Function to call after the taxonomies are loaded.
		 */
		getTaxonomies: function( posts, callback ) {
			var that = this,
				postsToLoad = [];

			$.each( posts, function( index, postName ) {
				if ( typeof that.postsData[ postName ] === 'undefined' ) {
					postsToLoad.push( postName );
				}
			});

			if ( postsToLoad.length !== 0 ) {
				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'get',
					data: { action: 'sliderpro_get_taxonomies', post_names: JSON.stringify( postsToLoad ) },
					complete: function( data ) {
						var response = $.parseJSON( data.responseText );

						$.each( response, function( name, taxonomy ) {
							that.postsData[ name ] = taxonomy;
						});

						callback( that.postsData );
					}
				});
			} else {
				callback( this.postsData );
			}
		},

		/**
		 * Display the informative tooltip.
		 * 
		 * @since 4.0.0
		 * 
		 * @param  {jQuery Object} target The setting label which is hovered.
		 */
		showInfo: function( target ) {
			var label = target,
				info = label.attr( 'data-info' ),
				infoTooltip = null;

			if ( typeof info !== 'undefined' ) {
				infoTooltip = $( '<div class="info-tooltip">' + info + '</div>' ).appendTo( label.parent() );
				infoTooltip.css( { 'left': - infoTooltip.outerWidth( true ), 'marginTop': - infoTooltip.outerHeight( true ) * 0.5 - 9 } );
			}

			label.on( 'mouseout', function() {
				if ( infoTooltip !== null ) {
					infoTooltip.remove();
				}
			});
		},

		/**
		 * Iterate through all slides and resizes the preview
		 * images based on their aspect ratio and the slide's
		 * current aspect ratio.
		 *
		 * @since 4.0.0
		 */
		resizeSlideImages: function() {
			var slideRatio = $( '.slide-preview' ).width() / $( '.slide-preview' ).height();

			$( '.slide-preview > img' ).each(function() {
				var image = $( this );

				if ( image.width() / image.height() > slideRatio ) {
					image.css( { width: 'auto', height: '100%' } );
				} else {
					image.css( { width: '100%', height: 'auto' } );
				}
			});
		},

		/**
		 * Check the size of the images and, if they are smaller than the size
		 * of the slider, display a warning.
		 *
		 * Only check images that have a non-zero width and height. Skip slides that
		 * have dynamic images or images from outside the Media Library.
		 *
		 * @since 4.6.0
		 */
		checkSlideImageSize: function( images ) {
			if ( $( '.image-size-warning' ).length === 0 ) {
				return;
			}

			var showWarning = false,
				sliderWidth = $( '.sidebar-settings' ).find( '.setting[name="width"]' ).val(),
				sliderHeight = $( '.sidebar-settings' ).find( '.setting[name="height"]' ).val();

			$.each( this.slides, function( index, slide ) {
				var image = slide.getData( 'mainImage' );

				if ( parseInt( image[ 'main_image_width' ], 10 ) === 0 || parseInt( image[ 'main_image_height' ], 10 ) === 0 ) {
					return;
				}
				
				if ( ( isNaN( sliderWidth ) === false && parseInt( image[ 'main_image_width' ], 10 ) < parseInt( sliderWidth, 10 ) ) ||
					( isNaN( sliderHeight ) === false && parseInt( image[ 'main_image_height' ], 10 ) < parseInt( sliderHeight, 10 ) ) ) {
					showWarning = true;
				}
			} );

			if ( showWarning === true ) {
				$( '.image-size-warning' ).css( 'display', 'block' );
			} else {
				$( '.image-size-warning' ).css( 'display', '' );
			}
		}
	};

	/*
	 * ======================================================================
	 * Export and import functions
	 * ======================================================================
	 */
		
	var ExportWindow = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		exportWindow: null,

		/**
		 * Open the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int}    id    The id of the slider.
		 * @param  {string} nonce A security nonce.
		 */
		open: function( id, nonce ) {
			var that = this;

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_export_slider', id: id, nonce: nonce },
				complete: function( data ) {
					that.exportWindow = $( data.responseText ).appendTo( 'body' );
					that.init();
				}
			});
		},

		/**
		 * Add event listeners to the buttons.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.exportWindow.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.close();
			});

			this.exportWindow.find( 'textarea' ).on( 'click', function( event ) {
				event.preventDefault();

				$( this ).focus();
				$( this ).select();
			});
		},

		/**
		 * Handle window closing.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.exportWindow.find( '.close-x' ).off( 'click' );
			this.exportWindow.find( 'textarea' ).off( 'click' );
			this.exportWindow.remove();
		}
	};

	var ImportWindow = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		importWindow: null,

		/**
		 * Reference to the 'Import' button.
		 *
		 * @since 4.8.2
		 * 
		 * @type {jQuery Object}
		 */
		$importButton: null,

		/**
		 * Store the pagination parameters.
		 *
		 * @since 4.8.6
		 * 
		 * @type {jQuery Object}
		 */
		paginationParams: null,

		/**
		 * Open the modal window.
		 *
		 * @since 4.0.0
		 */
		open: function() {
			var that = this;

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_import_slider' },
				complete: function( data ) {
					that.importWindow = $( data.responseText ).appendTo( 'body' );
					that.init();
				}
			});
		},

		/**
		 * Sets the pagination parameters, which are used to determine
		 * whether the page should reload after adding the new slider row to
		 * the list of sliders or, if the current page is also the last or single
		 * page, the slider row should be added without reloading.
		 *
		 * @since 4.8.6
		 */
		setPaginationParams: function( params ) {
			this.paginationParams = this.paginationParams || {};

			this.paginationParams.currentPage = params.currentPage;
			this.paginationParams.totalPages = params.totalPages;
		},

		/**
		 * Add event listeners to the buttons.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;
				
			this.$importButton = this.importWindow.find( '.save' );

			this.$importButton.data( 'label', this.$importButton.text() );

			this.$importButton.on( 'click', function( event ) {
				var sliderDataString = that.importWindow.find( 'textarea' ).val();

				event.preventDefault();

				if ( sliderDataString !== '' ) {
					that.importWindow.find( '.save' ).addClass( 'disabled' ).text( sp_js_vars.slider_importing );
					
					that.save();
				}
			});

			this.importWindow.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.close();
			});
		},

		/**
		 * Save the entered data.
		 *
		 * The entered JSON string is parsed, and it's sent to the server-side
		 * saving method.
		 *
		 * After the slider is created, a new row is added to the list.
		 * 
		 * @since 4.0.0
		 */
		save: function() {
			var that = this,
				sliderDataString = this.importWindow.find( 'textarea' ).val(),
				sliderData;

			if ( sliderDataString.indexOf( '<?xml version' ) !== -1 ) {
				this.loadLegacyMap( function( map ) {
					try {
						sliderData = that.convertLegacySlider( sliderDataString, map );
						that.sendData( sliderData );
					} catch ( error ) {
						that.importWindow.find( 'textarea' ).val( error );
						that.$importButton.removeClass( 'disabled' ).text( that.$importButton.data( 'label' ) );
					}
				});
			} else {
				try {
					sliderData = $.parseJSON( sliderDataString );
					this.sendData( sliderData );
				} catch ( error ) {
					this.importWindow.find( 'textarea' ).val( error );
					this.$importButton.removeClass( 'disabled' ).text( this.$importButton.data( 'label' ) );
				}
			}
		},

		/**
		 * Sends the slider data to the server to be saved in the database.
		 *
		 * @since 4.8.2
		 * 
		 * @param {object} sliderData An object containing slider data.
		 */
		sendData: function( sliderData ) {
			var that = this;

			sliderData[ 'id' ] = -1;
			sliderData[ 'nonce' ] = sp_js_vars.sa_nonce;
			sliderData[ 'action' ] = 'import';

			var paginationData = this.paginationParams !== null ? 
				{
					sp_page: this.paginationParams.currentPage !== undefined ? this.paginationParams.currentPage : 1,
					pages: this.paginationParams.totalPages !== undefined ? this.paginationParams.totalPages : 1
				}
				: {};

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_save_slider',
					data: JSON.stringify( sliderData ),
					...paginationData
				},
				complete: function( data ) {
					if ( $( '.sliders-list .no-slider-row' ).length !== 0 ) {
						$( '.sliders-list .no-slider-row' ).remove();
					}

					if ( that.paginationParams !== null && that.paginationParams.totalPages > 1 && that.paginationParams.currentPage !== that.paginationParams.totalPages ) {
						window.location = sp_js_vars.admin + '?page=sliderpro&sp_page=' + that.paginationParams.totalPages;
					} else {
						var row = $( data.responseText ).appendTo( $( '.sliders-list tbody' ) );
						
						row.hide().fadeIn();
						that.close();
					}
				}
			});
		},

		/**
		 * Loads a JSON file that contains the map of correspondences between the 
		 * legacy slider and the new version.
		 *
		 * @since 4.8.2
		 * 
		 * @param {Function} callback Callback function to be called after the JSON file has loaded.
		 */
		loadLegacyMap: function( callback ) {
			$.ajax({
				url: sp_js_vars.plugin + '/admin/assets/js/legacy-map.json',
				type: 'get',
				dataType: 'json',
				success: function( data ) {
					callback( data );
				}
			});
		},

		/**
		 * Converts HTML encoded characters to tags.
		 *
		 * @since 4.8.2
		 * 
		 * @param  {string} text Text containing HTML encoded characters.
		 * @return {string} text Provided text with HTML encoded characters converted to tags.
		 */
		decodeHTML: function( text ) {
			var map = {
				"&amp;": "&",
				"&quot;": '"',
				"&#039": "'",
				"&lt;": "<",
				"&gt;": ">"
			};

			for ( var key in map ) {
				if ( map.hasOwnProperty( key ) ) {
					var regexp = new RegExp( key, 'g' );
					text = text.replace( regexp, map[key] );
				}
			}

			// strip slashes
			text = text.replace( /\\/g, '' );

			return text;
		},

		/**
		 * Converts a legacy slider from the provided XML string to a compatible slider.
		 *
		 * @since 4.8.2
		 * 
		 * @param {string} xmlString An XML string containing the exported data from the legacy slider.
		 * @param {JSON}   map       A JSON object containing the map of correspondences between the 
		 * 							 legacy slider and the new version.
		 */
		convertLegacySlider: function( xmlString, map ) {
			try {
				var legacySliderXML = $.parseXML( xmlString );
			} catch ( error ) {
				throw new Error( error );
			}

			var that = this,
				newSliderData = {
					'name': $( legacySliderXML ).find( 'name' ).first().text(),
					'settings': {},
					'slides': []
				},
				settingsMap = map[ 'settings' ],
				slideContentMap = map[ 'slideContent' ],
				slideSettingsMap = map[ 'slideSettings' ],
				layerSettingsMap = map[ 'layerSettings' ];
			
			// Parse the legacy slider settings
			$( legacySliderXML ).find( 'settings' ).first().children().each( function( index, xmlNode ) {
				var legacySetting = settingsMap[ xmlNode.nodeName ];

				if ( typeof legacySetting !== 'undefined' ) {
					var settingName = legacySetting[ 'newName' ],
						settingType = legacySetting[ 'type' ],
						settingValue;

					if ( settingType === 'select' ) {
						settingValue = legacySetting[ 'options' ][ xmlNode.textContent ];
					} else if ( settingType === 'boolean' ) {
						settingValue = xmlNode.textContent == '0' ? false : true;
					} else if ( settingType === 'number' ) {
						settingValue = parseFloat( xmlNode.textContent );
					} else if ( settingType === 'string' ) {
						settingValue = xmlNode.textContent;
					} else if ( settingType === 'mixed' ) {
						settingValue = isNaN( xmlNode.textContent ) ? xmlNode.textContent : parseFloat( xmlNode.textContent );
					}

					newSliderData[ 'settings' ][ settingName ] = settingValue;
				}
			});

			// Parse the legacy slides
			$( legacySliderXML ).find( 'slide' ).each( function( index, slideXmlNode ) {
				var newSlideData = {
						settings: {}
					},
					layersData = [],
					layerCounter = 0;
				
				// Parse the legacy slide content
				$( slideXmlNode ).find( 'content' ).first().children().each( function( index, slideContentXmlNode ) {
					var legacySlideContent = slideContentMap[ slideContentXmlNode.nodeName ];

					if ( typeof legacySlideContent !== 'undefined' ) {
						var slideContentName = legacySlideContent[ 'newName' ],
							slideContentType = legacySlideContent[ 'type' ],
							slideContentValue;
						
						if ( slideContentType === 'string' ) {
							slideContentValue = that.decodeHTML( slideContentXmlNode.textContent );
						}

						newSlideData[ slideContentName ] = slideContentValue;

					// Parse the legacy layer content
					} else if ( slideContentXmlNode.nodeName.indexOf( 'layer_' ) !== -1 ) {
						var layer = {
							type: 'div'
						}

						layer[ 'text' ] = that.decodeHTML( slideContentXmlNode.textContent );

						layersData.push( layer );
					}
				});

				// Parse the legacy slide settings
				$( slideXmlNode ).find( 'settings' ).first().children().each( function( index, slideSettingXmlNode ) {
					var legacySlideSetting = slideSettingsMap[ slideSettingXmlNode.nodeName ];

					if ( typeof legacySlideSetting !== 'undefined' ) {
						var slideSettingName = legacySlideSetting[ 'newName' ],
							slideSettingType = legacySlideSetting[ 'type' ],
							slideSettingValue;
						
						if ( slideSettingType === 'stringToArray' ) {
							slideSettingValue = slideSettingXmlNode.textContent.split( legacySlideSetting[ 'delimiter' ] );
						} else if ( slideSettingType === 'select' ) {
							slideSettingValue = legacySlideSetting[ 'options' ][ slideSettingXmlNode.textContent ];
						} else if ( slideSettingType === 'boolean' ) {
							slideSettingValue = slideSettingXmlNode.textContent == '0' ? false : true;
						} else if ( slideSettingType === 'number' ) {
							slideSettingValue = parseFloat( slideSettingXmlNode.textContent );
						} else if ( slideSettingType === 'string' ) {
							slideSettingValue = slideSettingXmlNode.textContent;
						} else if ( slideSettingType === 'mixed' ) {
							slideSettingValue = isNaN( slideSettingXmlNode.textContent ) ? slideSettingXmlNode.textContent : parseFloat( xmlNode.textContent );
						}

						newSlideData[ 'settings' ][ slideSettingName ] = slideSettingValue;

					// Parse the legacy layer settings
					} else if ( slideSettingXmlNode.nodeName.indexOf( 'layer_' ) !== -1 ) {
						var layerSettingsData = {},
							legacyLayerSettings = slideSettingXmlNode.textContent.split( '+' );

						legacyLayerSettings.forEach( function( element ) {
							var legacyLayerSetting = element.split('='),
								legacyLayerSettingName = legacyLayerSetting[0],
								legacyLayerSettingValue = legacyLayerSetting[1],
								layerSetting = layerSettingsMap[ legacyLayerSettingName ];

							if ( typeof layerSetting !== 'undefined' ) {
								var layerSettingName = layerSetting[ 'newName' ],
									layerSettingType = layerSetting[ 'type' ],
									layerSettingValue;

								if ( layerSettingType === 'stringToArray' ) {
									if ( legacyLayerSettingName === 'layer_preset_styles' ) {
										var legacyArray = legacyLayerSettingValue.split( layerSetting[ 'delimiter' ] ),
											layerSettingValue = [];
										
										legacyArray.forEach( function( element ) {
											if ( typeof layerSetting[ 'options' ][ element ] !== 'undefined' ) {
												layerSettingValue.push( layerSetting[ 'options' ][ element ] );
											}
										});
									}
								} else if ( layerSettingType === 'select' ) {
									layerSettingValue = layerSetting[ 'options' ][ legacyLayerSettingValue ];
								} else if ( layerSettingType === 'boolean' ) {
									layerSettingValue = legacyLayerSettingValue == '0' ? false : true;
								} else if ( layerSettingType === 'number' ) {
									layerSettingValue = parseFloat( legacyLayerSettingValue );
								} else if ( layerSettingType === 'string' ) {
									layerSettingValue = legacyLayerSettingValue;
								} else if ( layerSettingType === 'mixed' ) {
									layerSettingValue = isNaN( legacyLayerSettingValue ) ? legacyLayerSettingValue : parseFloat( legacyLayerSettingValue );
								}

								layerSettingsData[ layerSettingName ] = layerSettingValue;
							}
						});

						layersData[ layerCounter ][ 'position' ] = layerCounter;
						layersData[ layerCounter ][ 'name' ] = 'Layer ' + layerCounter;
						layersData[ layerCounter ][ 'settings' ] = layerSettingsData;

						layerCounter++;
					}
				});

				if ( layersData.length !== 0 ) {
					newSlideData[ 'layers' ] = layersData;
				}

				newSliderData[ 'slides' ].push( newSlideData );
			});

			return newSliderData;
		},

		/**
		 * Handle window closing.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.importWindow.find( '.close-x' ).off( 'click' );
			this.importWindow.find( '.save' ).off( 'click' );
			this.importWindow.remove();
		}
	};

	/*
	 * ======================================================================
	 * Slide functions
	 * ======================================================================
	 */
	
	/**
	 * Slide object.
	 *
	 * @since 4.0.0
	 * 
	 * @param {jQuery Object} element The jQuery element.
	 * @param {Int}           id      The id of the slide.
	 * @param {Object}        data    The data of the slide.
	 */
	var Slide = function( element, id, data ) {
		this.$slide = element;
		this.id = id;
		this.data = data;
		this.events = $( {} );

		if ( typeof this.data === 'undefined' ) {
			this.data = { mainImage: {}, thumbnail: {}, caption: '', layers: {}, html: '', settings: {} };
		}

		this.init();
	};

	Slide.prototype = {

		/**
		 * Initialize the slide.
		 * 
		 * Add the necessary event listeners.
		 *
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.$slide.find( '.slide-preview' ).on( 'click', function( event ) {
				var contentType = that.getData( 'settings' )[ 'content_type' ];

				if ( typeof contentType === 'undefined' || contentType === 'custom' ) {
					MediaLoader.open(function( selection ) {
						var image = selection[ 0 ];

						that.setData( 'mainImage', { main_image_id: image.id, main_image_source: image.url, main_image_alt: image.alt, main_image_title: image.title, main_image_width: image.width, main_image_height: image.height } );
						that.updateSlidePreview();

						SliderProAdmin.checkSlideImageSize();
					});
				}
			});

			this.$slide.find( '.edit-main-image' ).on( 'click', function( event ) {
				event.preventDefault();
				MainImageEditor.open( that.id );
			});

			this.$slide.find( '.edit-thumbnail' ).on( 'click', function( event ) {
				event.preventDefault();
				ThumbnailEditor.open( that.id );
			});

			this.$slide.find( '.edit-caption' ).on( 'click', function( event ) {
				event.preventDefault();
				CaptionEditor.open( that.id );
			});

			this.$slide.find( '.edit-layers' ).on( 'click', function( event ) {
				event.preventDefault();
				LayersEditor.open( that.id );
			});

			this.$slide.find( '.edit-html' ).on( 'click', function( event ) {
				event.preventDefault();
				HTMLEditor.open( that.id );
			});

			this.$slide.find( '.edit-settings' ).on( 'click', function( event ) {
				event.preventDefault();
				SettingsEditor.open( that.id );
			});

			this.$slide.find( '.delete-slide' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'deleteSlide', id: that.id } );
			});

			this.$slide.find( '.duplicate-slide' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'duplicateSlide', slideData: that.data } );
			});

			this.resizeImage();
		},

		/**
		 * Return the slide's data.
		 *
		 * It can return the main image data, or the layers
		 * data, or the HTML data, or the settings data, or
		 * all the data.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String} target The type of data to return.
		 * @return {Object}        The requested data.
		 */
		getData: function( target ) {
			if ( target === 'all' ) {
				var allData = {};

				$.each( this.data.mainImage, function( settingName, settingValue ) {
					allData[ settingName ] = settingValue;
				});

				$.each( this.data.thumbnail, function( settingName, settingValue ) {
					allData[ settingName ] = settingValue;
				});

				allData[ 'caption' ] = this.data.caption;
				allData[ 'layers' ] = this.data.layers;
				allData[ 'html' ] = this.data.html;
				allData[ 'settings' ] = this.data.settings;

				return allData;
			} else if ( target === 'mainImage' ) {
				return this.data.mainImage;
			} else if ( target === 'thumbnail' ) {
				return this.data.thumbnail;
			} else if ( target === 'caption' ) {
				return this.data.caption;
			} else if ( target === 'layers' ) {
				return this.data.layers;
			} else if ( target === 'html' ) {
				return this.data.html;
			} else if ( target === 'settings' ) {
				return this.data.settings;
			}
		},

		/**
		 * Set the slide's data.
		 *
		 * It can set a specific data type, like the main image, 
		 * layers, html, settings, or it can set all the data.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String} target The type of data to set.
		 * @param  {Object} data   The data to attribute to the slide.
		 */
		setData: function( target, data ) {
			var that = this;

			if ( target === 'all' ) {
				this.data = data;
			} else if ( target === 'mainImage' ) {
				$.each( data, function( name, value ) {
					that.data.mainImage[ name ] = value;
				});
			} else if ( target === 'thumbnail' ) {
				$.each( data, function( name, value ) {
					that.data.thumbnail[ name ] = value;
				});
			} else if ( target === 'caption' ) {
				this.data.caption = data;
			} else if ( target === 'layers' ) {
				this.data.layers = data;
			} else if ( target === 'html' ) {
				this.data.html = data;
			} else if ( target === 'settings' ) {
				this.data.settings = data;
			}
		},

		/**
		 * Remove the slide.
		 * 
		 * @since 4.0.0
		 */
		remove: function() {
			this.$slide.find( '.slide-preview' ).off( 'click' );
			this.$slide.find( '.edit-main-image' ).off( 'click' );
			this.$slide.find( '.edit-caption' ).off( 'click' );
			this.$slide.find( '.edit-layers' ).off( 'click' );
			this.$slide.find( '.edit-html' ).off( 'click' );
			this.$slide.find( '.edit-settings' ).off( 'click' );
			this.$slide.find( '.delete-slide' ).off( 'click' );
			this.$slide.find( '.duplicate-slide' ).off( 'click' );

			this.$slide.fadeOut( 500, function() {
				$( this ).remove();
			});
		},

		/**
		 * Update the slide's preview.
		 *
		 * If the content type is custom, the preview will consist
		 * of an image. If the content is dynamic, a text will be 
		 * displayed that indicates the type of content (i.e., posts).
		 *
		 * This is called when the main image is changed or
		 * when the content type is changed.
		 * 
		 * @since 4.0.0
		 */
		updateSlidePreview: function() {
			var slidePreview = this.$slide.find( '.slide-preview' ),
				contentType = this.data.settings[ 'content_type' ];

			slidePreview.empty();

			if ( typeof contentType === 'undefined' || contentType === 'custom' ) {
				var mainImageSource = this.data.mainImage[ 'main_image_source' ];

				if ( typeof mainImageSource !== 'undefined' && mainImageSource !== '' ) {
					$( '<img src="' + mainImageSource + '" />' ).appendTo( slidePreview );
					this.resizeImage();
				} else if ( this.data.layers.length !== 0 ) {
					$.each( this.data.layers, function( index, layer ) {
						if ( layer.type === 'video' && layer.video_poster !== '' ) {
							$( '<img src="' + layer.video_poster + '" />' ).appendTo( slidePreview );
							return false;
						}
					});
				}

				if ( slidePreview.find( 'img' ).length === 0 ) {
					$( '<p class="no-image">' + sp_js_vars.no_image + '</p>' ).appendTo( slidePreview );
				}

				this.$slide.removeClass( 'dynamic-slide' );
			} else if ( contentType === 'posts' ) {
				$( '<p>[ ' + sp_js_vars.posts_slides + ' ]</p>' ).appendTo( slidePreview );
				this.$slide.addClass( 'dynamic-slide' );
			} else if ( contentType === 'gallery' ) {
				$( '<p>[ ' + sp_js_vars.gallery_slides + ' ]</p>' ).appendTo( slidePreview );
				this.$slide.addClass( 'dynamic-slide' );
			} else if ( contentType === 'flickr' ) {
				$( '<p>[ ' + sp_js_vars.flickr_slides + ' ]</p>' ).appendTo( slidePreview );
				this.$slide.addClass( 'dynamic-slide' );
			}
		},

		/**
		 * Resize the preview image, after it has loaded.
		 *
		 * @since 4.0.0
		 */
		resizeImage: function() {
			var slidePreview = this.$slide.find( '.slide-preview' ),
				slideImage = this.$slide.find( '.slide-preview > img' );

			if ( slideImage.length ) {
				var checkImage = setInterval(function() {
					if ( slideImage[0].complete === true ) {
						clearInterval( checkImage );

						if ( slideImage.width() / slideImage.height() > slidePreview.width() / slidePreview.height() ) {
							slideImage.css( { width: 'auto', height: '100%' } );
						} else {
							slideImage.css( { width: '100%', height: 'auto' } );
						}
					}
				}, 100 );
			}
		},

		/**
		 * Add an event listener to the slide.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String}   type    The event name.
		 * @param  {Function} handler The callback function.
		 */
		on: function( type, handler ) {
			this.events.on( type, handler );
		},

		/**
		 * Remove an event listener from the slide.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		off: function( type ) {
			this.events.off( type );
		},

		/**
		 * Triggers an event.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		trigger: function( type ) {
			this.events.triggerHandler( type );
		}
	};

	/*
	 * ======================================================================
	 * Main Image Editor
	 * ======================================================================
	 */
	
	var MainImageEditor = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		editor: null,

		/**
		 * Reference to slide for which the editor was opened.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Slide}
		 */
		currentSlide: null,

		/**
		 * Indicates whether the slide's preview needs to be updated.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Boolean}
		 */
		needsPreviewUpdate: false,

		/**
		 * Open the modal window.
		 *
		 * It checks the content type set for the slide and passes
		 * that information because the aspect of the editor will
		 * depend on what type the content is. Dynamic slides will
		 * not have the possibility to load images from the library.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int} id The id of the slide
		 */
		open: function( id ) {
			this.currentSlide = SliderProAdmin.getSlide( id );

			var that = this,
				data = this.currentSlide.getData( 'mainImage' ),
				contentType = this.currentSlide.getData( 'settings' )[ 'content_type' ],
				spinner = $( '.slide[data-id="' + id + '"]' ).find( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			if ( typeof contentType === 'undefined' ) {
				contentType = 'custom';
			}

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'sliderpro_load_main_image_editor', data: JSON.stringify( data ), content_type: contentType },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();

					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the editor.
		 *
		 * Add the necessary event listeners.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.$editor = $( '.main-image-editor' );

			this.$editor.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.save();
				that.close();

				SliderProAdmin.checkSlideImageSize();
			});

			this.$editor.find( '.image-loader, .additional-image-loader' ).on( 'click', function( event ) {
				event.preventDefault();
				that.openMediaLibrary( event );
			});

			this.$editor.find( '.clear-fieldset' ).on( 'click', function( event ) {
				event.preventDefault();
				that.clearFieldset( event );
			});

			this.$editor.find( 'input[name="main_image_source"]' ).on( 'input', function( event ) {
				that.needsPreviewUpdate = true;
			});

			this.$editor.find( '.show-additional-images, .hide-additional-images' ).on( 'click', function( event ) {
				setTimeout(function() {
					$( window ).trigger( 'resize' );
				}, 1);
			});

			$( window ).on( 'resize.mainImageEditor', function() {
				if ( that.$editor.find( '.modal-window' ).outerWidth() >= $( window ).width() ) {
					that.$editor.addClass( 'modal-window-left' );
				} else {
					that.$editor.removeClass( 'modal-window-left' );
				}

				if ( that.$editor.find( '.modal-window' ).outerHeight() >= $( window ).height() - 60 ) {
					that.$editor.addClass( 'modal-window-top' );
				} else {
					that.$editor.removeClass( 'modal-window-top' );
				}
			});

			this.$editor.find( '.show-hide-info, .show-hide-dynamic-tags' ).on( 'click', function() {
				$( window ).trigger( 'resize' );
			});

			$( window ).trigger( 'resize' );
		},

		/**
		 * Open the Media library.
		 *
		 * Allows the user to select an image from the library for
		 * the current slide. It checks if the image needs to be added
		 * for the main image or for the retina image.
		 *
		 * It updates the editor's fields with information associated
		 * with the image, like the image's alt, title, width and height.
		 * 
		 * @since 4.0.0
		 * 
		 * @param  {Event Object} event The mouse click event.
		 */
		openMediaLibrary: function( event ) {
			event.preventDefault();

			var that = this,
				imageLoader = this.$editor.find( '.main-image .image-loader' ),
				additionalImage = $( event.target ).hasClass( 'additional-image-loader' ),
				additionalImageInput = $( event.target ).siblings( 'input' );

			MediaLoader.open(function( selection ) {
				var image = selection[ 0 ];

				if ( additionalImage === true ) {
					additionalImageInput.val( image.url );
				} else {
					if ( imageLoader.find( 'img' ).length !== 0 ) {
						imageLoader.find( 'img' ).attr( 'src', image.url );
					} else {
						imageLoader.find( '.no-image' ).remove();
						$( '<img src="' + image.url + '" />' ).appendTo( imageLoader );
					}

					that.$editor.find( 'input[name="main_image_id"]' ).val( image.id );
					that.$editor.find( 'input[name="main_image_source"]' ).val( image.url );
					that.$editor.find( 'input[name="main_image_alt"]' ).val( image.alt );
					that.$editor.find( 'input[name="main_image_title"]' ).val( image.title );
					that.$editor.find( 'input[name="main_image_width"]' ).val( image.width );
					that.$editor.find( 'input[name="main_image_height"]' ).val( image.height );

					that.needsPreviewUpdate = true;
				}
			});
		},

		/**
		 * Clear the input fields for the image.
		 * 
		 * @since 4.0.0
		 * 
		 * @param  {Event Object} event The mouse click event.
		 */
		clearFieldset: function( event ) {
			event.preventDefault();

			var target = $( event.target ).parents( '.fieldset' ),
				imageLoader = target.find( '.image-loader' );

			target.find( 'input' ).val( '' );

			if ( imageLoader.find( 'img' ).length !== 0 ) {
				imageLoader.find( 'img' ).remove();
				$( '<p class="no-image">' + sp_js_vars.no_image + '</p>' ).appendTo( imageLoader );

				this.needsPreviewUpdate = true;
			}
		},

		/**
		 * Save the data entered in the editor.
		 *
		 * Iterates through all input fields and copies the
		 * data entered in an object, which is then passed
		 * to the slide.
		 *
		 * It also calls the function that updates the slide's
		 * preview, if the main image was changed.
		 * 
		 * @since 4.0.0
		 */
		save: function() {
			var that = this,
				data = {};

			this.$editor.find( '.field' ).each(function() {
				var field = $( this );
				data[ field.attr('name') ] = field.val();
			});

			this.currentSlide.setData( 'mainImage', data );

			if ( this.needsPreviewUpdate === true ) {
				this.currentSlide.updateSlidePreview();
				this.needsPreviewUpdate = false;
			}
		},

		/**
		 * Close the editor.
		 *
		 * Remove all event listeners.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.$editor.find( '.close-x' ).off( 'click' );
			this.$editor.find( '.image-loader' ).off( 'click' );
			this.$editor.find( '.additional-image-loader' ).off( 'click' );
			this.$editor.find( '.clear-fieldset' ).off( 'click' );
			this.$editor.find( 'input[name="main_image_source"]' ).off( 'input' );
			$( window ).off( 'resize.mainImageEditor' );

			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	/*
	 * ======================================================================
	 * Thumbnail Editor
	 * ======================================================================
	 */
	
	var ThumbnailEditor = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		editor: null,

		/**
		 * Reference to slide for which the editor was opened.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Slide}
		 */
		currentSlide: null,

		/**
		 * Open the modal window.
		 *
		 * It checks the content type set for the slide and passes
		 * that information because the aspect of the editor will
		 * depend on what type the content is. Dynamic slides will
		 * not have the possibility to load images from the library.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int} id The id of the slide
		 */
		open: function( id ) {
			this.currentSlide = SliderProAdmin.getSlide( id );

			var that = this,
				data = this.currentSlide.getData( 'thumbnail' ),
				contentType = this.currentSlide.getData( 'settings' )[ 'content_type' ],
				spinner = $( '.slide[data-id="' + id + '"]' ).find( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );
			
			if ( typeof contentType === 'undefined' ) {
				contentType = 'custom';
			}

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'sliderpro_load_thumbnail_editor', data: JSON.stringify( data ), content_type: contentType },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();

					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the editor.
		 *
		 * Add the necessary event listeners.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.$editor = $( '.thumbnail-editor' );

			this.$editor.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.save();
				that.close();
			});

			this.$editor.find( '.image-loader, .additional-image-loader' ).on( 'click', function( event ) {
				event.preventDefault();
				that.openMediaLibrary( event );
			});

			this.$editor.find( '.clear-fieldset' ).on( 'click', function( event ) {
				event.preventDefault();
				that.clearFieldset( event );
			});

			this.$editor.find( '.thumbnail-html-code' ).codeEditor();

			$( window ).on( 'resize.thumbnailEditor', function() {
				if ( that.$editor.find( '.modal-window' ).outerWidth() >= $( window ).width() ) {
					that.$editor.addClass( 'modal-window-left' );
				} else {
					that.$editor.removeClass( 'modal-window-left' );
				}

				if ( that.$editor.find( '.modal-window' ).outerHeight() >= $( window ).height() - 60 ) {
					that.$editor.addClass( 'modal-window-top' );
				} else {
					that.$editor.removeClass( 'modal-window-top' );
				}
			});

			this.$editor.find( '.show-hide-info, .show-hide-dynamic-tags' ).on( 'click', function() {
				$( window ).trigger( 'resize' );
			});

			$( window ).trigger( 'resize.thumbnailEditor' );
		},

		/**
		 * Open the Media library.
		 *
		 * Allows the user to select an image from the library for
		 * the current slide. It checks if the image needs to be added
		 * for the main image or for the retina image.
		 *
		 * It updates the editor's fields with information associated
		 * with the image, like the image's alt, title, width and height.
		 * 
		 * @since 4.0.0
		 * 
		 * @param  {Event Object} event The mouse click event.
		 */
		openMediaLibrary: function( event ) {
			event.preventDefault();

			var that = this,
				imageLoader = this.$editor.find( '.thumbnail .image-loader' ),
				additionalImage = $( event.target ).hasClass( 'additional-image-loader' ),
				additionalImageInput = $( event.target ).siblings( 'input' );

			MediaLoader.open(function( selection ) {
				var image = selection[ 0 ];

				if ( additionalImage === true ) {
					additionalImageInput.val( image.url );
				} else {
					if ( imageLoader.find( 'img' ).length !== 0 ) {
						imageLoader.find( 'img' ).attr( 'src', image.url );
					} else {
						imageLoader.find( '.no-image' ).remove();
						$( '<img src="' + image.url + '" />' ).appendTo( imageLoader );
					}

					that.$editor.find( 'input[name="thumbnail_source"]' ).val( image.url );
					that.$editor.find( 'input[name="thumbnail_alt"]' ).val( image.alt );
					that.$editor.find( 'input[name="thumbnail_title"]' ).val( image.title );
				}
			});
		},

		/**
		 * Clear the input fields for the image.
		 * 
		 * @since 4.0.0
		 * 
		 * @param  {Event Object} event The mouse click event.
		 */
		clearFieldset: function( event ) {
			event.preventDefault();

			var target = $( event.target ).parents( '.fieldset' ),
				imageLoader = target.find( '.image-loader' );

			target.find( 'input' ).val( '' );

			if ( imageLoader.find( 'img' ).length !== 0 ) {
				imageLoader.find( 'img' ).remove();
				$( '<p class="no-image">' + sp_js_vars.no_image + '</p>' ).appendTo( imageLoader );
			}
		},

		/**
		 * Save the data entered in the editor.
		 *
		 * Iterates through all input fields and copies the
		 * data entered in an object, which is then passed
		 * to the slide.
		 *
		 * It also calls the function that updates the slide's
		 * preview, if the main image was changed.
		 * 
		 * @since 4.0.0
		 */
		save: function() {
			var that = this,
				data = {};

			this.$editor.find( '.field' ).each(function() {
				var field = $( this );

				if ( field.attr('name') === 'thumbnail_content' ) {
					data[ 'thumbnail_content' ] = field.data('codeEditor').getValue();
				} else {
					data[ field.attr('name') ] = field.val();
				}
			});

			this.currentSlide.setData( 'thumbnail', data );
		},

		/**
		 * Close the editor.
		 *
		 * Remove all event listeners.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.$editor.find( '.close-x' ).off( 'click' );
			this.$editor.find( '.image-loader' ).off( 'click' );
			this.$editor.find( '.additional-image-loader' ).off( 'click' );
			this.$editor.find( '.clear-fieldset' ).off( 'click' );
			this.$editor.find( 'input[name="thumbnail_source"]' ).off( 'input' );
			this.$editor.find( '.thumbnail-html-code' ).codeEditor( 'destroy' );

			$( window ).off( 'resize.thumbnailEditor' );

			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	/*
	 * ======================================================================
	 * Caption editor
	 * ======================================================================
	 */
	
	var CaptionEditor = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		editor: null,

		/**
		 * Reference to slide for which the editor was opened.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Slide}
		 */
		currentSlide: null,

		/**
		 * Open the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int} id The id of the slide.
		 */
		open: function( id ) {
			this.currentSlide = SliderProAdmin.getSlide( id );
			
			var that = this,
				data = this.currentSlide.getData( 'caption' ),
				spinner = $( '.slide[data-id="' + id + '"]' ).find( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } ),
				contentType = this.currentSlide.getData( 'settings' )[ 'content_type' ];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'sliderpro_load_caption_editor', data: data, content_type: contentType },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();

					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the editor.
		 *
		 * Add the necessary event listeners.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.$editor = $( '.caption-editor' );

			this.$editor.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.save();
				that.close();
			});

			$( window ).on( 'resize.captionEditor', function() {
				if ( that.$editor.find( '.modal-window' ).outerWidth() >= $( window ).width() ) {
					that.$editor.addClass( 'modal-window-left' );
				} else {
					that.$editor.removeClass( 'modal-window-left' );
				}

				if ( that.$editor.find( '.modal-window' ).outerHeight() >= $( window ).height() - 60 ) {
					that.$editor.addClass( 'modal-window-top' );
				} else {
					that.$editor.removeClass( 'modal-window-top' );
				}
			});

			this.$editor.find( '.show-hide-info, .show-hide-dynamic-tags' ).on( 'click', function() {
				$( window ).trigger( 'resize' );
			});

			$( window ).trigger( 'resize' );
		},

		/**
		 * Save the content entered in the editor's textfield.
		 * 
		 * @since 4.0.0
		 */
		save: function() {
			this.currentSlide.setData( 'caption', this.$editor.find( 'textarea' ).val() );
		},

		/**
		 * Close the editor.
		 *
		 * Remove all event listeners.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.$editor.find( '.close-x' ).off( 'click' );
			$( window ).off( 'resize.captionEditor' );

			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	/*
	 * ======================================================================
	 * HTML editor
	 * ======================================================================
	 */
	
	var HTMLEditor = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		editor: null,

		/**
		 * Reference to slide for which the editor was opened.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Slide}
		 */
		currentSlide: null,

		/**
		 * Open the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int} id The id of the slide.
		 */
		open: function( id ) {
			this.currentSlide = SliderProAdmin.getSlide( id );
			
			var that = this,
				data = this.currentSlide.getData( 'html' ),
				spinner = $( '.slide[data-id="' + id + '"]' ).find( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } ),
				contentType = this.currentSlide.getData( 'settings' )[ 'content_type' ];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'sliderpro_load_html_editor', data: data, content_type: contentType },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();

					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the editor.
		 *
		 * Add the necessary event listeners.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.$editor = $( '.html-editor' );

			this.$editor.find( '.html-code' ).codeEditor();

			this.$editor.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.save();
				that.close();
			});

			$( window ).on( 'resize.htmlEditor', function() {
				if ( that.$editor.find( '.modal-window' ).outerWidth() >= $( window ).width() ) {
					that.$editor.addClass( 'modal-window-left' );
				} else {
					that.$editor.removeClass( 'modal-window-left' );
				}

				if ( that.$editor.find( '.modal-window' ).outerHeight() >= $( window ).height() - 60 ) {
					that.$editor.addClass( 'modal-window-top' );
				} else {
					that.$editor.removeClass( 'modal-window-top' );
				}
			});

			this.$editor.find( '.show-hide-info, .show-hide-dynamic-tags' ).on( 'click', function() {
				$( window ).trigger( 'resize' );
			});

			$( window ).trigger( 'resize' );
		},

		/**
		 * Save the content entered in the editor's textfield.
		 * 
		 * @since 4.0.0
		 */
		save: function() {
			this.currentSlide.setData( 'html', this.$editor.find( '.html-code' ).data('codeEditor').getValue() );
		},

		/**
		 * Close the editor.
		 *
		 * Remove all event listeners.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.$editor.find( '.close-x' ).off( 'click' );
			this.$editor.find( '.html-code' ).codeEditor( 'destroy' );

			$( 'window' ).off( 'resize.htmlEditor' );

			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	/*
	 * ======================================================================
	 * Layers editor
	 * ======================================================================
	 */
	
	var LayersEditor = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		editor: null,

		/**
		 * Reference to slide for which the editor was opened.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Slide}
		 */
		currentSlide: null,

		/**
		 * Array of JavaScript objects, that contain the layer's data.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Array}
		 */
		layersData: null,

		/**
		 * Array of Layer objects.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Array}
		 */
		layers: [],

		/**
		 * Counter for layers.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Int}
		 */
		counter: 0,

		/**
		 * Indicates if a layer is currently being added.
		 *
		 * Stops the addition of new layers if another addition
		 * is being processed.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Boolean}
		 */
		isWorking: false,

		/**
		 * Open the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int} id The id of the slide.
		 */
		open: function( id ) {
			this.currentSlide = SliderProAdmin.getSlide( id );
			this.layersData = this.currentSlide.getData( 'layers' );

			var that = this,
				spinner = $( '.slide[data-id="' + id + '"]' ).find( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } ),
				contentType = this.currentSlide.getData( 'settings' )[ 'content_type' ];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'sliderpro_load_layers_editor', data: JSON.stringify( this.layersData ), content_type: contentType },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();

					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the editor.
		 *
		 * Adds the necessary event listeners for adding a new layer,
		 * deleting a layer or duplicating a layer.
		 *
		 * It also creates the layers existing in the slide's data,
		 * and initializes the sorting functionality.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.counter = 0;

			this.$editor = $( '.layers-editor' );

			this.$editor.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.save();
				that.close();
			});

			$( window ).on( 'resize.layersEditor', function() {
				if ( that.$editor.find( '.modal-window' ).outerWidth() >= $( window ).width() ) {
					that.$editor.addClass( 'modal-window-left' );
				} else {
					that.$editor.removeClass( 'modal-window-left' );
				}

				if ( that.$editor.find( '.modal-window' ).outerHeight() >= $( window ).height() - 60 ) {
					that.$editor.addClass( 'modal-window-top' );
				} else {
					that.$editor.removeClass( 'modal-window-top' );
				}
			});

			this.$editor.find( '.show-hide-info' ).on( 'click', function() {
				$( window ).trigger( 'resize' );
			});

			this.$editor.find( '.add-layer-group' ).on( 'click', function( event ) {
				event.preventDefault();

				if ( that.isWorking === true ) {
					return;
				}

				var type = 'paragraph';

				if ( typeof $( event.target ).attr( 'data-type' ) !== 'undefined' ) {
					type = $( event.target ).attr( 'data-type' );
				}

				that.addNewLayer( type );
			});

			this.$editor.find( '.delete-layer' ).on( 'click', function( event ) {
				event.preventDefault();
				that.deleteLayer();
			});

			this.$editor.find( '.duplicate-layer' ).on( 'click', function( event ) {
				event.preventDefault();

				if ( that.isWorking === true ) {
					return;
				}

				that.duplicateLayer();
			});

			this.initViewport();

			$.each( this.layersData, function( index, layerData ) {
				var data = layerData;
				data.createMode = 'init';
				that.createLayer( data );

				that.counter = Math.max( that.counter, data.id );
			});

			$( '.list-layers' ).lightSortable( {
				children: '.list-layer',
				placeholder: 'list-layer-placeholder',
				sortEnd: function( event ) {
					if ( event.startPosition === event.endPosition ) {
						return;
					}

					var layer = that.layers[ event.startPosition ];
					that.layers.splice( event.startPosition, 1 );
					that.layers.splice( event.endPosition, 0, layer );

					var $viewportLayers = that.$editor.find( '.viewport-layers' ),
						total = $viewportLayers.children().length - 1;

					$( '.list-layers' ).find( '.list-layer' ).each(function( index, element ) {
						$( element ).attr( 'data-position', index );
					});

					var swapLayer = $viewportLayers.find( '.viewport-layer' ).eq( total - event.startPosition ).detach();

					if ( total - event.startPosition < total - event.endPosition ) {
						swapLayer.insertAfter( $viewportLayers.find( '.viewport-layer' ).eq( total - 1 - event.endPosition ) );
					} else {
						swapLayer.insertBefore( $viewportLayers.find( '.viewport-layer' ).eq( total - event.endPosition ) );
					}
				}
			} );

			$( '.list-layers' ).find( '.list-layer' ).each(function( index, element ) {
				$( element ).attr( 'data-position', index );
			});

			if ( this.layers.length !== 0 ) {
				this.layers[ 0 ].triggerSelect();
			}

			$( window ).trigger( 'resize.layersEditor' );
		},

		/**
		 * Initialize the viewport.
		 *
		 * The viewport will have the same size as the current image, 
		 * or, if the slide doesn't have a main image, it will
		 * have the same size as the maximum slide size.
		 *
		 * The viewport will contain the image and on top of the image,
		 * a container that will hold the layers.
		 *
		 * @since 4.0.0
		 */
		initViewport: function() {
			var $viewport = this.$editor.find( '.layer-viewport' ),
				$viewportLayers = $( '<div class="slider-pro viewport-layers"></div>' ).appendTo( $viewport ),
				viewportWidth = $( '.sidebar-settings' ).find( '.setting[name="width"]' ).val(),
				viewportHeight = $( '.sidebar-settings' ).find( '.setting[name="height"]' ).val(),
				customClass = $( '.sidebar-settings' ).find( '.setting[name="custom_class"]' ).val(),
				mainImageSource = this.currentSlide.getData( 'mainImage' )['main_image_source'];

			if ( isNaN( viewportWidth ) ) {
				viewportWidth = $( window ).width() * ( parseInt( viewportWidth, 10 ) / 100 );
			} else {
				viewportWidth = parseInt( viewportWidth, 10 );
			}

			if ( isNaN( viewportHeight ) ) {
				viewportHeight = $( window ).height() * ( parseInt( viewportHeight, 10 ) / 100 );
			} else {
				viewportHeight = parseInt( viewportHeight, 10 );
			}

			$viewport.css({ 'width': viewportWidth, 'height': viewportHeight });
			$viewportLayers.css({ 'width': viewportWidth,'height': viewportHeight });

			if ( customClass !== '' ) {
				$viewportLayers.addClass( customClass );
			}

			if ( typeof mainImageSource !== 'undefined' && mainImageSource !== '' && mainImageSource.indexOf( '[' ) === -1 ) {
				var scaleMode = $( '.sidebar-settings' ).find( '.setting[name="image_scale_mode"]' ).val(),
					centerImage = $( '.sidebar-settings' ).find( '.setting[name="center_image"]' ).is( ':checked' );
					backgroundImage = {
						'background-image': 'url(' + mainImageSource + ')',
						'background-repeat': 'no-repeat'
					};

				if ( scaleMode === 'cover' ) {
					backgroundImage['background-size'] = 'cover';
				} else if ( scaleMode === 'contain' ) {
					backgroundImage['background-size'] = 'contain';
				} else if ( scaleMode === 'exact' ) {
					backgroundImage['background-size'] = '100% 100%';
				}

				if ( centerImage === true ) {
					backgroundImage['background-position'] = 'center center';
				}

				$viewportLayers.css( backgroundImage );
			}
		},

		/**
		 * Create a layer.
		 *
		 * Based on the type of the layer, information which is
		 * available in the passed data, a certain subclass of the
		 * Layer object will be instantiated.
		 *
		 * It also checks if the created layer is a new/duplicate layer or
		 * an existing layer, and adds it either at the beginning or the 
		 * end of the list. New layers always need to be added before the 
		 * existing layers.
		 * 
		 * @since 4.0.0
		 * 
		 * @param  {Object} data The layer's data.
		 */
		createLayer: function( data ) {
			var that = this,
				layer;

			if ( data.type === 'paragraph' ) {
				layer =	new ParagraphLayer( data );
			} else if ( data.type === 'heading' ) {
				layer =	new HeadingLayer( data );
			} else if ( data.type === 'image' ) {
				layer =	new ImageLayer(data );
			} else if ( data.type === 'div' ) {
				layer =	new DivLayer( data );
			} else if ( data.type === 'video' ) {
				layer =	new VideoLayer( data );
			}

			if ( data.createMode === 'new' || data.createMode === 'duplicate' ) {
				this.layers.unshift( layer );
			} else {
				this.layers.push( layer );
			}

			layer.on( 'select', function( event ) {
				$.each( that.layers, function( index, layer ) {
					if ( layer.isSelected() === true ) {
						layer.deselect();
					}

					if (layer.getID() === event.id) {
						layer.select();
					}
				});
			});

			layer.triggerSelect();

			this.isWorking = false;

			this.$editor.removeClass( 'no-layers' );
		},

		/**
		 * Add a new layer on runtime.
		 * 
		 * Sends an AJAX request to load the layer's settings editor and
		 * also adds the layer slide in the list of layers.
		 *
		 * @since 4.0.0
		 * 
		 * @param {String} type The type of layer.
		 */
		addNewLayer: function( type ) {
			var that = this;

			this.isWorking = true;

			this.counter++;

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'sliderpro_add_layer_settings', id: this.counter, type: type },
				complete: function( data ) {
					$( data.responseText ).appendTo( $( '.layers-settings' ) );
					$( '<li class="list-layer" data-id="' + that.counter + '" data-position="' + that.layers.length + '">Layer ' + that.counter + '</li>' ).prependTo( that.$editor.find( '.list-layers' ) );

					that.createLayer( { id: that.counter, type: type, createMode: 'new' } );

					$( window ).trigger( 'resize.layersEditor' );
				}
			});
		},

		/**
		 * Delete the selected layer.
		 *
		 * Iterates through the layers and detects the selected
		 * one, then calls its 'destroy' method.
		 *
		 * @since 4.0.0
		 */
		deleteLayer: function() {
			var that = this,
				removedIndex;

			$.each( this.layers, function( index, layer ) {
				if ( layer.isSelected() === true ) {
					layer.destroy();
					that.layers.splice( index, 1 );
					removedIndex = index;

					return false;
				}
			});

			if ( this.layers.length === 0 ) {
				this.$editor.addClass( 'no-layers' );
				return;
			}

			if ( removedIndex === 0 ) {
				this.layers[ 0 ].triggerSelect();
			} else {
				this.layers[ removedIndex - 1 ].triggerSelect();
			}
		},
		
		/**
		 * Duplicate the selected layer.
		 *
		 * Iterates through the layers and detects the selected
		 * one, then copies its data and sends an AJAX request 
		 * with the copied data.
		 *
		 * @since 4.0.0
		 */
		duplicateLayer: function() {
			var that = this,
				layerData;

			$.each( this.layers, function( index, layer ) {
				if ( layer.isSelected() === true ) {
					layerData = layer.getData();
				}
			});

			if ( typeof layerData === 'undefined' ) {
				return;
			}

			this.isWorking = true;

			this.counter++;

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: {
					action: 'sliderpro_add_layer_settings',
					id: this.counter,
					type: layerData.type,
					text: layerData.text,
					heading_type: layerData.heading_type,
					image_source: layerData.image_source,
					image_alt: layerData.image_alt,
					image_link: layerData.image_link,
					image_retina: layerData.image_retina,
					settings: JSON.stringify( layerData.settings )
				},
				complete: function( data ) {
					$( data.responseText ).appendTo( $( '.layers-settings' ) );
					$( '<li class="list-layer" data-id="' + that.counter + '">Layer ' + that.counter + '</li>' ).prependTo( that.$editor.find( '.list-layers' ) );

					layerData.id = that.counter;
					layerData.createMode = 'duplicate';
					that.createLayer( layerData );

					$( window ).trigger( 'resize.layersEditor' );
				}
			});
		},

		/**
		 * Save the data from the editor.
		 *
		 * Iterate through the array of Layer objects, get their 
		 * data and send all the data to the slide.
		 * 
		 * @since 4.0.0
		 */
		save: function() {
			var data = [];

			$.each( this.layers, function( index, layer ) {
				data.push( layer.getData() );
			});

			this.currentSlide.setData( 'layers', data );

			this.currentSlide.updateSlidePreview();
		},

		/**
		 * Close the editor.
		 *
		 * Remove all event listeners and and destroy objects.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.$editor.find( '.close-x' ).off( 'click' );
			this.$editor.find( '.add-layer-group' ).off( 'click' );
			this.$editor.find( '.delete-layer' ).off( 'click' );
			this.$editor.find( '.duplicate-layer' ).off( 'click' );
			$( window ).off( 'resize.layersEditor' );

			$( '.list-layers' ).lightSortable( 'destroy' );

			$.each( this.layers, function( index, layer ) {
				layer.destroy();
			});

			this.layers.length = 0;

			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	/*
	 * ======================================================================
	 * Layer functions
	 * ======================================================================
	 */
	
	/**
	 * Layer object.
	 *
	 * Parent/Base object for all layer types.
	 *
	 * Each layer has a representation in the viewport, in the list of layers
	 * and in the settings.
	 *
	 * @since 4.0.0
	 * 
	 * @param {Object} data The layer's data.
	 */
	var Layer = function( data ) {
		this.data = data;
		this.id = this.data.id;

		this.selected = false;
		this.events = $( {} );

		this.$editor = $( '.layers-editor' );
		this.$viewportLayers = this.$editor.find( '.viewport-layers' );

		this.$viewportLayer = null;
		this.$listLayer = this.$editor.find( '.list-layer[data-id="' + this.id + '"]' );
		this.$layerSettings = this.$editor.find( '.layer-settings[data-id="' + this.id + '"]' );

		this.init();
	};

	Layer.prototype = {

		/**
		 * Initialize the layer.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			this.initLayerContent();
			this.initLayerSettings();
			this.initViewportLayer();
			this.initLayerDragging();
			this.initListLayer();
		},

		/**
		 * Return the layer's data: id, name, position and settings.
		 *
		 * Iterates through the layer's associated setting fields 
		 * and copies the settings (name and value).
		 *
		 * @since 4.0.0
		 * 
		 * @return {Object} The layer's data.
		 */
		getData: function() {
			var data = {};

			data.id = this.id;
			data.position = parseInt( this.$listLayer.attr( 'data-position' ), 10 );
			data.name = this.$listLayer.text();
			
			data.settings = {};

			this.$layerSettings.find( '.setting' ).each(function() {
				var settingField = $( this ),
					type = settingField.attr( 'type' );

				if ( type === 'radio' ) {
					if ( settingField.is( ':checked' ) ) {
						data.settings[ settingField.attr( 'name' ).split( '-' )[ 0 ] ] = settingField.val();
					}
				} else if ( type === 'checkbox' ) {
					data.settings[ settingField.attr( 'name' ) ] = settingField.is( ':checked' );
				} else if ( settingField.is( 'select' ) && typeof settingField.attr( 'multiple' ) !== 'undefined' ) {
					data.settings[ settingField.attr( 'name' ) ] = settingField.val() === null ? [] : settingField.val();
				} else {
					data.settings[ settingField.attr( 'name' ) ] = settingField.val();
				}
			});

			return data;
		},

		/**
		 * Return the id of the layer.
		 *
		 * @since 4.0.0
		 * 
		 * @return {Int} The id.
		 */
		getID: function() {
			return this.id;
		},

		/**
		 * Select the layer.
		 *
		 * Adds classes to the layer item from the list and to the 
		 * settings in order to highlight/show them.
		 * 
		 * @since 4.0.0
		 */
		select: function() {
			this.selected = true;

			this.$listLayer.addClass( 'selected-list-layer' );
			this.$layerSettings.addClass( 'selected-layer-settings' );
		},

		/**
		 * Deselect the layer by removing the added classes.
		 * 
		 * @since 4.0.0
		 */
		deselect: function() {
			this.selected = false;

			this.$listLayer.removeClass( 'selected-list-layer' );
			this.$layerSettings.removeClass( 'selected-layer-settings' );
		},

		/**
		 * Trigger the selection event.
		 *
		 * Used for programatically selecting the layer.
		 * 
		 * @since 4.0.0
		 */
		triggerSelect: function() {
			this.trigger( { type: 'select', id: this.id } );
		},

		/**
		 * Check if the layer is selected.
		 *
		 * @since 4.0.0
		 * 
		 * @return {Boolean} Whether the layer is selected.
		 */
		isSelected: function() {
			return this.selected;
		},

		/**
		 * Destroy the layer
		 *
		 * Removes all event listeners and elements associated with the layer.
		 * 
		 * @since 4.0.0
		 */
		destroy: function() {
			this.$viewportLayer.off( 'mousedown' );
			this.$viewportLayer.off( 'mouseup' );
			this.$viewportLayer.off( 'click' );

			this.$listLayer.off( 'click' );
			this.$listLayer.off( 'dblclick' );
			this.$listLayer.off( 'selectstart' );

			this.$editor.off( 'mousemove.layer' + this.id );
			this.$editor.off( 'click.layer' + this.id );

			this.$layerSettings.find( 'select[name="preset_styles"]' ).multiCheck( 'destroy' );

			this.$layerSettings.find( '.setting[name="width"]' ).off( 'change' );
			this.$layerSettings.find( '.setting[name="height"]' ).off( 'change' );
			this.$layerSettings.find( '.setting[name="position"]' ).off( 'change' );
			this.$layerSettings.find( '.setting[name="horizontal"]' ).off( 'change' );
			this.$layerSettings.find( '.setting[name="vertical"]' ).off( 'change' );
			this.$layerSettings.find( '.setting[name="preset_styles"]' ).off( 'change' );
			this.$layerSettings.find( '.setting[name="custom_class"]' ).off( 'change' );

			this.$viewportLayer.remove();
			this.$listLayer.remove();
			this.$layerSettings.remove();
		},

		/**
		 * Add an event listener to the layer.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String}   type    The event name.
		 * @param  {Function} handler The callback function.
		 */
		on: function( type, handler ) {
			this.events.on( type, handler );
		},

		/**
		 * Remove an event listener from the layer.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		off: function( type ) {
			this.events.off( type );
		},

		/**
		 * Triggers an event.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		trigger: function( type ) {
			this.events.triggerHandler( type );
		},

		/**
		 * Initialize the viewport layer.
		 *
		 * This is the layer's representation in the viewport and its
		 * role is to give a preview of how the layer will look like
		 * in the front-end. 
		 *
		 * If the layer is a newly created one, add some default styling
		 * to it (black background and padding), and if it's an existing
		 * layer or a duplicated one, set its style according to the
		 * layer's data.
		 * 
		 * @since 4.0.0
		 */
		initViewportLayer: function() {
			var that = this;

			this.$viewportLayer.attr( 'data-id', this.id );

			// append the layer before or after the other layers
			if ( this.data.createMode === 'new' || this.data.createMode === 'duplicate' ) {
				this.$viewportLayer.appendTo( this.$viewportLayers );
			} else if ( this.data.createMode === 'init' ) {
				this.$viewportLayer.prependTo( this.$viewportLayers );
			}

			if ( this.data.createMode === 'new' ) {

				// set the position of the layer
				this.$viewportLayer.css({ 'width': 'auto', 'height': 'auto', 'left': 0, 'top': 0 });

				// set the style of the layer
				if ( this.$viewportLayer.hasClass( 'sp-layer' ) ) {
					this.$viewportLayer.addClass( 'sp-black sp-padding' );
				} else {
					this.$viewportLayer.find( '.sp-layer' ).addClass( 'sp-black sp-padding' );
				}
			} else if ( this.data.createMode === 'init' || this.data.createMode === 'duplicate' ) {
				
				// set the style of the layer
				var classes = this.data.settings.preset_styles !== null ? this.data.settings.preset_styles.join( ' ' ) : '';
				classes += ' ' + this.data.settings.custom_class;
				
				if ( this.$viewportLayer.hasClass( 'sp-layer' ) ) {
					this.$viewportLayer.addClass( classes );
				} else {
					this.$viewportLayer.find( '.sp-layer' ).addClass( classes );
				}

				// set the size of the layer
				this.$viewportLayer.css({ 'width': this.data.settings.width, 'height': this.data.settings.height });

				var position = this.data.settings.position.toLowerCase(),
					horizontalPosition,
					verticalPosition,
					horizontalValue = this.data.settings.horizontal,
					verticalValue = this.data.settings.vertical,
					suffix,
					layerWidth = parseInt( that.$viewportLayer.css( 'width' ), 10 ),
					layerHeight = parseInt( that.$viewportLayer.css( 'height' ), 10 );

				if ( position.indexOf( 'right' ) !== -1 ) {
					horizontalPosition = 'right';
				} else if ( position.indexOf( 'left' ) !== -1 ) {
					horizontalPosition = 'left';
				} else {
					horizontalPosition = 'center';
				}

				if ( position.indexOf( 'bottom' ) !== -1 ) {
					verticalPosition = 'bottom';
				} else if ( position.indexOf( 'top' ) !== -1 ) {
					verticalPosition = 'top';
				} else {
					verticalPosition = 'center';
				}

				suffix = ( horizontalValue.indexOf( 'px' ) === -1 && horizontalValue.indexOf( '%' ) === -1 ) ? 'px' : '';

				if ( horizontalPosition === 'center' ) {
					this.$viewportLayer.css({ 'width': layerWidth, 'marginLeft': 'auto', 'marginRight': 'auto', 'left': horizontalValue + suffix, 'right': 0 });
				} else {
					this.$viewportLayer.css( horizontalPosition, horizontalValue + suffix );
				}

				suffix = verticalValue.indexOf( 'px' ) === -1 && verticalValue.indexOf( '%' ) === -1 ? 'px' : '';

				if ( verticalPosition === 'center' ) {
					this.$viewportLayer.css({ 'height': layerHeight, 'marginTop': 'auto', 'marginBottom': 'auto', 'top': verticalValue + suffix, 'bottom': 0 });
				} else {
					this.$viewportLayer.css( verticalPosition, verticalValue + suffix );
				}
			}

			// select the layer after it was added
			this.$viewportLayer.on( 'mousedown', function() {
				that.triggerSelect();
			});

			// prevent link navigation for links inside layers
			this.$viewportLayer.on( 'click', 'a', function( event ) {
				event.preventDefault();
			});
		},

		/**
		 * Initialize the layer's dragging functionality.
		 *
		 * This is for the viewport representation of the layer.
		 * 
		 * @since 4.0.0
		 */
		initLayerDragging: function() {
			var that = this,
				mouseX = 0,
				mouseY = 0,
				layerX = 0,
				layerY = 0,
				hasFocus = false,
				autoRightBottom = false,
				hasMoved = false;

			this.$viewportLayer.on( 'mousedown', function( event ) {
				event.preventDefault();

				// Store the position of the mouse pointer
				// and the position of the layer
				mouseX = event.pageX;
				mouseY = event.pageY;
				layerX = that.$viewportLayer[ 0 ].offsetLeft;
				layerY = that.$viewportLayer[ 0 ].offsetTop;

				hasFocus = true;
				hasMoved = false;
			});

			this.$editor.find( '.viewport-layers' ).on( 'mousemove.layer' + this.id, function( event ) {
				event.preventDefault();

				hasMoved = true;

				if ( hasFocus === true ) {
					that.$viewportLayer.css({ 'left': layerX + event.pageX - mouseX, 'top': layerY + event.pageY - mouseY });

					// While moving the layer, disable the right and bottom properties
					// so that the layer will be positioned using the left and top
					// properties.
					if ( autoRightBottom === false ) {
						autoRightBottom = true;
						that.$viewportLayer.css({ 'right': 'auto', 'bottom': 'auto' });
					}
				}
			});

			// Set the layer's position settings based on Position setting and the
			// position to which the layer was dragged.
			this.$viewportLayer.on( 'mouseup', function( event ) {
				event.preventDefault();

				hasFocus = false;
				autoRightBottom = false;

				if ( hasMoved === false ) {
					return;
				}

				var position = that.$layerSettings.find( '.setting[name="position"]' ).val().toLowerCase(),
					horizontalPosition,
					verticalPosition,
					layerLeft = parseInt( that.$viewportLayer.css( 'left' ), 10 ),
					layerTop = parseInt( that.$viewportLayer.css( 'top' ), 10 ),
					layerWidth = parseInt( that.$viewportLayer.css( 'width' ), 10 ),
					layerHeight = parseInt( that.$viewportLayer.css( 'height' ), 10 ),
					viewportWidth = that.$editor.find( '.viewport-layers' ).width(),
					viewportHeight = that.$editor.find( '.viewport-layers' ).height();

				if ( position.indexOf( 'right' ) !== -1 ) {
					horizontalPosition = 'right';
				} else if ( position.indexOf( 'left' ) !== -1 ) {
					horizontalPosition = 'left';
				} else {
					horizontalPosition = 'center';
				}

				if ( position.indexOf( 'bottom' ) !== -1 ) {
					verticalPosition = 'bottom';
				} else if ( position.indexOf( 'top' ) !== -1 ) {
					verticalPosition = 'top';
				} else {
					verticalPosition = 'center';
				}

				if ( horizontalPosition === 'left' ) {
					that.$layerSettings.find( '.setting[name="horizontal"]' ).val( layerLeft );
				} else if ( horizontalPosition === 'right' ) {
					var right = viewportWidth - layerLeft - layerWidth;

					that.$layerSettings.find( '.setting[name="horizontal"]' ).val( right );
					that.$viewportLayer.css({ 'left': 'auto', 'right': right });
				} else {
					var horizontalCenter = - ( viewportWidth - 2  * layerLeft - layerWidth );
					
					that.$layerSettings.find( '.setting[name="horizontal"]' ).val( horizontalCenter );
					that.$viewportLayer.css({ 'left': horizontalCenter, 'right': 0 });
				}

				if ( verticalPosition === 'top' ) {
					that.$layerSettings.find( '.setting[name="vertical"]' ).val( layerTop );
				} else if ( verticalPosition === 'bottom' ) {
					var bottom = viewportHeight - layerTop - layerHeight;

					that.$layerSettings.find( '.setting[name="vertical"]' ).val( bottom );
					that.$viewportLayer.css({ 'top': 'auto', 'bottom': bottom });
				} else {
					var verticalCenter = - ( viewportHeight - 2  * layerTop - layerHeight );
					
					that.$layerSettings.find( '.setting[name="vertical"]' ).val( verticalCenter );
					that.$viewportLayer.css({ 'top': verticalCenter, 'bottom': 0 });
				}
			});
		},

		/**
		 * Initialize the layer's list item.
		 *
		 * This is the layer's representation in the list of layers.
		 *
		 * Implements functionality for selecting the layer and
		 * changing its name.
		 * 
		 * @since 4.0.0
		 */
		initListLayer: function() {
			var that = this,
				isEditingLayerName = false;

			this.$listLayer.on( 'click', function( event ) {
				that.trigger( { type: 'select', id: that.id } );
			});

			this.$listLayer.on( 'dblclick', function( event ) {
				if ( isEditingLayerName === true ) {
					return;
				}

				isEditingLayerName = true;

				var name = that.$listLayer.text();

				var input = $( '<input type="text" value="' + name + '" />' ).appendTo( that.$listLayer );

				input.on( 'change', function() {
					isEditingLayerName = false;
					var layerName = input.val() !== '' ? input.val() : 'Layer ' + that.id;
					that.$listLayer.text( layerName );
					input.remove();
				});
			});

			this.$listLayer.on( 'selectstart', function( event ) {
				event.preventDefault();
			});

			this.$editor.on( 'click.layer' + this.id, function( event ) {
				if ( ! $( event.target ).is( 'input' ) && isEditingLayerName === true ) {
					isEditingLayerName = false;

					var input = that.$listLayer.find( 'input' ),
						layerName = input.val() !== '' ? input.val() : 'Layer ' + that.id;

					that.$listLayer.text( layerName );
					input.remove();
				}
			});
		},

		/**
		 * Initialize the viewport layer's content.
		 *
		 * This is overridden by child objects, based on the
		 * specific of the content type.
		 * 
		 * @since 4.0.0
		 */
		initLayerContent: function() {

		},

		/**
		 * Initialize the layer's settings.
		 *
		 * It listens for changes in the setting fields and applies the
		 * changes to the viewport representation of the layer.
		 * 
		 * @since 4.0.0
		 */
		initLayerSettings: function() {
			var that = this;

			this.$layerSettings.find( 'select[name="preset_styles"]' ).multiCheck({ width: 120 });

			// listen for position changes
			this.$layerSettings.find( '.setting[name="position"], .setting[name="horizontal"], .setting[name="vertical"], .setting[name="width"], .setting[name="height"]' ).on( 'change', function() {
				var position = that.$layerSettings.find( '.setting[name="position"]' ).val().toLowerCase(),
					horizontalPosition,
					verticalPosition,
					horizontalValue = that.$layerSettings.find( '.setting[name="horizontal"]' ).val(),
					verticalValue = that.$layerSettings.find( '.setting[name="vertical"]' ).val(),
					width = that.$layerSettings.find( '.setting[name="width"]' ).val(),
					height = that.$layerSettings.find( '.setting[name="height"]' ).val(),
					suffix,
					layerWidth = parseInt( that.$viewportLayer.css( 'width' ), 10 ),
					layerHeight = parseInt( that.$viewportLayer.css( 'height' ), 10 );

				if ( position.indexOf( 'right' ) !== -1 ) {
					horizontalPosition = 'right';
				} else if ( position.indexOf( 'left' ) !== -1 ) {
					horizontalPosition = 'left';
				} else {
					horizontalPosition = 'center';
				}

				if ( position.indexOf( 'bottom' ) !== -1 ) {
					verticalPosition = 'bottom';
				} else if ( position.indexOf( 'top' ) !== -1 ) {
					verticalPosition = 'top';
				} else {
					verticalPosition = 'center';
				}

				that.$viewportLayer.css({
					'width': width,
					'height': height,
					'top': 'auto',
					'bottom': 'auto',
					'left': 'auto',
					'right': 'auto'
				});

				suffix = ( horizontalValue.indexOf( 'px' ) === -1 && horizontalValue.indexOf( '%' ) === -1 ) ? 'px' : '';

				if ( horizontalPosition === 'center' ) {
					that.$viewportLayer.css({ 'width': layerWidth, 'marginLeft': 'auto', 'marginRight': 'auto', 'left': horizontalValue + suffix, 'right': 0 });
				} else {
					that.$viewportLayer.css( horizontalPosition, horizontalValue + suffix );
				}

				suffix = verticalValue.indexOf( 'px' ) === -1 && verticalValue.indexOf( '%' ) === -1 ? 'px' : '';

				if ( verticalPosition === 'center' ) {
					that.$viewportLayer.css({ 'height': layerHeight, 'marginTop': 'auto', 'marginBottom': 'auto', 'top': verticalValue + suffix, 'bottom': 0 });
				} else {
					that.$viewportLayer.css( verticalPosition, verticalValue + suffix );
				}
			});
			
			// listen for style changes
			this.$layerSettings.find( '.setting[name="preset_styles"], .setting[name="custom_class"]' ).on( 'change', function() {
				var classes = '',
					selectedStyles = that.$layerSettings.find( '.setting[name="preset_styles"]' ).val(),
					customClass = that.$layerSettings.find( '.setting[name="custom_class"]' ).val();

				classes += selectedStyles !== null ? ' ' + selectedStyles.join( ' ' ) : '';
				classes += customClass !== '' ? ' ' + customClass : '';

				if ( that.$viewportLayer.hasClass( 'sp-layer' ) ) {
					that.$viewportLayer.attr( 'class', 'viewport-layer sp-layer' + classes );
				} else {
					that.$viewportLayer.find( '.sp-layer' ).attr( 'class', 'sp-layer' + classes );
				}
			});
		}
	};

	/*
	 * ======================================================================
	 * Paragraph layer
	 * ======================================================================
	 */
	
	var ParagraphLayer = function( data ) {
		Layer.call( this, data );
	};

	ParagraphLayer.prototype = Object.create( Layer.prototype );
	ParagraphLayer.prototype.constructor = ParagraphLayer;

	ParagraphLayer.prototype.initLayerContent = function() {
		var that = this;

		this.text = this.data.createMode === 'new' ? this.$layerSettings.find( 'textarea[name="text"]' ).val() : this.data.text;

		this.$layerSettings.find( 'textarea[name="text"]' ).on( 'input', function() {
			that.text = $( this ).val();
			that.$viewportLayer.html( that.text );
		});
	};

	ParagraphLayer.prototype.initViewportLayer = function() {
		this.$viewportLayer = $( '<p class="viewport-layer sp-layer">' + this.text + '</p>' );
		Layer.prototype.initViewportLayer.call( this );
	};

	ParagraphLayer.prototype.getData = function() {
		var data = Layer.prototype.getData.call( this );
		data.type = 'paragraph';
		data.text = this.text;

		return data;
	};

	ParagraphLayer.prototype.destroy = function() {
		this.$layerSettings.find( 'textarea[name="text"]' ).off( 'input' );

		Layer.prototype.destroy.call( this );
	};

	/*
	 * ======================================================================
	 * Heading layer
	 * ======================================================================
	 */
	
	var HeadingLayer = function( data ) {
		Layer.call( this, data );
	};

	HeadingLayer.prototype = Object.create( Layer.prototype );
	HeadingLayer.prototype.constructor = HeadingLayer;

	HeadingLayer.prototype.initLayerContent = function() {
		var that = this;

		this.headingType = this.data.createMode === 'new' ? 'h3' : this.data.heading_type;
		this.headingText = this.data.createMode === 'new' ? this.$layerSettings.find( 'textarea[name="text"]' ).val() : this.data.text;

		this.$layerSettings.find( 'select[name="heading_type"]' ).on( 'change', function() {
			that.headingType = $( this ).val();
			
			var classes = that.$viewportLayer.find( '.sp-layer' ).attr( 'class' );
			that.$viewportLayer.html( '<' + that.headingType + ' class="' + classes + '">' + that.headingText + '</' + that.headingType + '>' );
		});

		this.$layerSettings.find( 'textarea[name="text"]' ).on( 'input', function() {
			that.headingText = $( this ).val();
			
			that.$viewportLayer.find( '.sp-layer' ).html( that.headingText );
		});
	};

	HeadingLayer.prototype.initViewportLayer = function() {
		this.$viewportLayer = $( '<div class="viewport-layer"><' + this.headingType + ' class="sp-layer">' + this.headingText + '</' + this.headingType + '></div>' );
		Layer.prototype.initViewportLayer.call( this );
	};

	HeadingLayer.prototype.getData = function() {
		var data = Layer.prototype.getData.call( this );
		data.type = 'heading';
		data.heading_type = this.headingType;
		data.text = this.headingText;

		return data;
	};

	HeadingLayer.prototype.destroy = function() {
		this.$layerSettings.find( 'select[name="heading_type"]' ).off( 'change' );
		this.$layerSettings.find( 'textarea[name="text"]' ).off( 'input' );

		Layer.prototype.destroy.call( this );
	};

	/*
	 * ======================================================================
	 * Image layer
	 * ======================================================================
	 */
	
	var ImageLayer = function( data ) {
		Layer.call( this, data );
	};

	ImageLayer.prototype = Object.create( Layer.prototype );
	ImageLayer.prototype.constructor = ImageLayer;

	ImageLayer.prototype.initLayerContent = function() {
		var that = this,
			placehoderPath = sp_js_vars.plugin + '/admin/assets/css/images/image-placeholder.png';

		this.imageSource = this.data.createMode === 'new' ? placehoderPath : this.data.image_source;
		this.hasPlaceholder = this.data.createMode === 'new' ? true : false;

		this.$layerSettings.find( 'input[name="image_source"]' ).on( 'change', function() {
			that.imageSource = $( this ).val();

			if ( that.imageSource !== '' ) {
				that.$viewportLayer.attr( 'src', that.imageSource )
									.removeClass( 'has-placeholder' );

				that.hasPlaceholder = false;
			} else {
				that.$viewportLayer.attr( 'src', placehoderPath )
									.addClass( 'has-placeholder' );

				that.hasPlaceholder = true;
			}
		});

		this.$layerSettings.find( '.layer-image-loader' ).on( 'click', function( event ) {
			var target = $( event.target ).siblings( 'input' ).attr( 'name' ) === 'image_source' ? 'default' : 'retina';

			MediaLoader.open(function( selection ) {
				var image = selection[ 0 ];

				if ( target === 'default' ) {
					that.$layerSettings.find( 'input[name="image_source"]' ).val( image.url ).trigger( 'change' );
					that.$layerSettings.find( 'input[name="image_alt"]' ).val( image.alt );
				} else if ( target === 'retina' ) {
					that.$layerSettings.find( 'input[name="image_retina"]' ).val( image.url );
				}
			});
		});
	};

	ImageLayer.prototype.initLayerSettings = function() {
		Layer.prototype.initLayerSettings.call( this );

		var that = this;

		this.$layerSettings.find( '.setting[name="preset_styles"], .setting[name="custom_class"]' ).on( 'change', function() {
			if ( that.hasPlaceholder === true ) {
				that.$viewportLayer.addClass( 'has-placeholder' );
			} else {
				that.$viewportLayer.removeClass( 'has-placeholder' );
			}
		});
	};

	ImageLayer.prototype.initViewportLayer = function() {
		this.$viewportLayer = $( '<img class="viewport-layer sp-layer" src="' + this.imageSource + '" />' );

		if ( this.hasPlaceholder === true ) {
			this.$viewportLayer.addClass( 'has-placeholder' );
		} else {
			this.$viewportLayer.removeClass( 'has-placeholder' );
		}

		Layer.prototype.initViewportLayer.call( this );
	};

	ImageLayer.prototype.getData = function() {
		var data = Layer.prototype.getData.call( this );
		data.type = 'image';
		data.image_source = this.imageSource;
		data.image_alt = this.$layerSettings.find( 'input[name="image_alt"]' ).val();
		data.image_link = this.$layerSettings.find( 'input[name="image_link"]' ).val();
		data.image_retina = this.$layerSettings.find( 'input[name="image_retina"]' ).val();

		return data;
	};

	ImageLayer.prototype.destroy = function() {
		this.$layerSettings.find( 'input[name="image_source"]' ).off( 'change' );
		this.$layerSettings.find( '.layer-image-loader' ).off( 'click' );

		Layer.prototype.destroy.call( this );
	};

	/*
	 * ======================================================================
	 * DIV layer
	 * ======================================================================
	 */
	
	var DivLayer = function( data ) {
		Layer.call( this, data );

		var that = this;

		this.on( 'select', function() {
			setTimeout( function() {
				that.$layerSettings.find( '.div-layer-html-code' ).codeEditor( 'refresh' );
			}, 1 );
		});

		this.$layerSettings.find( '.layer-settings-tab-label' ).on( 'click', function() {
			setTimeout( function() {
				that.$layerSettings.find( '.div-layer-html-code' ).codeEditor( 'refresh' );
			}, 1 );
		});
	};

	DivLayer.prototype = Object.create( Layer.prototype );
	DivLayer.prototype.constructor = DivLayer;

	DivLayer.prototype.initLayerContent = function() {
		var that = this;

		this.text = this.data.createMode === 'new' ? this.$layerSettings.find( 'textarea[name="text"]' ).val() : this.data.text;

		that.$layerSettings.find( '.div-layer-html-code' ).codeEditor()
			.on( 'edit', function( event ) {
				that.text = event.value;
				that.$viewportLayer.html( that.text );
			});
	};

	DivLayer.prototype.initViewportLayer = function() {
		this.$viewportLayer = $( '<div class="viewport-layer sp-layer">' + this.text + '</div>' );
		Layer.prototype.initViewportLayer.call( this );
	};

	DivLayer.prototype.getData = function() {
		var data = Layer.prototype.getData.call( this );
		data.type = 'div';
		data.text = this.text;

		return data;
	};

	DivLayer.prototype.destroy = function() {
		this.$layerSettings.find( 'textarea[name="text"]' ).off( 'input' );
		this.$layerSettings.find( '.div-layer-html-code' ).codeEditor( 'destroy' );
		Layer.prototype.destroy.call( this );
	};

	/*
	 * ======================================================================
	 * Video layer
	 * ======================================================================
	 */
	
	var VideoLayer = function( data ) {
		Layer.call( this, data );
	};

	VideoLayer.prototype = Object.create( Layer.prototype );
	VideoLayer.prototype.constructor = VideoLayer;

	VideoLayer.prototype.initLayerContent = function() {
		var that = this;

		this.$layerSettings.find( '.layer-image-loader' ).on( 'click', function( event ) {
			var target = $( event.target ).siblings( 'input' ).attr( 'name' ) === 'video_poster' ? 'default' : 'retina';

			MediaLoader.open(function( selection ) {
				var image = selection[ 0 ];

				if ( target === 'default' ) {
					that.$layerSettings.find( 'input[name="video_poster"]' ).val( image.url ).trigger( 'change' );
				} else if ( target === 'retina' ) {
					that.$layerSettings.find( 'input[name="video_retina_poster"]' ).val( image.url );
				}
			});
		});
	};

	VideoLayer.prototype.initViewportLayer = function() {
		var that = this;

		this.$viewportLayer = $( '<div class="viewport-layer sp-layer has-placeholder"><span class="video-placeholder"></span></div>' );
		Layer.prototype.initViewportLayer.call( this );

		this.$layerSettings.find( 'input[name="width"], input[name="height"]' ).on( 'change', function() {
			var width = that.$layerSettings.find( 'input[name="width"]' ).val(),
				height = that.$layerSettings.find( 'input[name="height"]' ).val();

			if ( width === 'auto' ) {
				that.$viewportLayer.css( 'width', 300 );
			}

			if ( height === 'auto' ) {
				that.$viewportLayer.css( 'height', 150 );
			}
		});

		this.$layerSettings.find( 'input[name="width"], input[name="height"]' ).trigger( 'change' );
	};

	VideoLayer.prototype.initLayerSettings = function() {
		Layer.prototype.initLayerSettings.call( this );

		var that = this;

		this.$layerSettings.find( '.setting[name="preset_styles"], .setting[name="custom_class"]' ).on( 'change', function() {
			that.$viewportLayer.addClass( 'has-placeholder' );
		});
	};

	VideoLayer.prototype.getData = function() {
		var data = Layer.prototype.getData.call( this );
		data.type = 'video';

		data.video_source = this.$layerSettings.find( 'select[name="video_source"]' ).val();
		data.video_id = this.$layerSettings.find( 'input[name="video_id"]' ).val();
		data.video_poster = this.$layerSettings.find( 'input[name="video_poster"]' ).val();
		data.video_retina_poster = this.$layerSettings.find( 'input[name="video_retina_poster"]' ).val();
		data.video_load_mode = this.$layerSettings.find( 'select[name="video_load_mode"]' ).val();
		data.video_params = this.$layerSettings.find( 'input[name="video_params"]' ).val();

		return data;
	};

	VideoLayer.prototype.destroy = function() {
		this.$layerSettings.find( 'input[name="width"]' ).off( 'change' );
		this.$layerSettings.find( 'input[name="height"]' ).off( 'change' );

		Layer.prototype.destroy.call( this );
	};

	/*
	 * ======================================================================
	 * Settings editor
	 * ======================================================================
	 */
	
	var SettingsEditor = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		editor: null,

		/**
		 * Reference to slide for which the editor was opened.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Slide}
		 */
		currentSlide: null,

		/**
		 * Indicates whether the slide's preview needs to be updated.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Boolean}
		 */
		needsPreviewUpdate: false,

		/**
		 * Open the modal window.
		 *
		 * Send an AJAX request providing the slide's settings data.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Int} id The id of the slide
		 */
		open: function( id ) {
			this.currentSlide = SliderProAdmin.getSlide( id );

			var that = this,
				data = this.currentSlide.getData( 'settings' ),
				spinner = $( '.slide[data-id="' + id + '"]' ).find( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'sliderpro_load_settings_editor', data: JSON.stringify( data ) },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();

					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the editor.
		 *
		 * Add the necessary event listeners.
		 * 
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.$editor = $( '.settings-editor' );
			
			this.$editor.find( '.close, .close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.save();
				that.close();
			});

			// Listen when the content type changes in order to load a new 
			// set of input fields, associated with the new content type.
			this.$editor.find( '.slide-setting[name="content_type"]' ).on( 'change', function() {
				var type = $( this ).val();

				that.loadControls( type );
				that.needsPreviewUpdate = true;
			});

			// Check if the content type is set to 'Posts' in order
			// to load the associates taxonomies for the selected posts.
			if ( this.$editor.find( '.slide-setting[name="content_type"]' ).val() === 'posts' ) {
				this.handlePostsSelects();
			}

			$( window ).on( 'resize.settingsEditor', function() {
				if ( that.$editor.find( '.modal-window' ).outerWidth() >= $( window ).width() ) {
					that.$editor.addClass( 'modal-window-left' );
				} else {
					that.$editor.removeClass( 'modal-window-left' );
				}

				if ( that.$editor.find( '.modal-window' ).outerHeight() >= $( window ).height() - 60 ) {
					that.$editor.addClass( 'modal-window-top' );
				} else {
					that.$editor.removeClass( 'modal-window-top' );
				}
			});

			this.$editor.on( 'click', '.show-hide-info', function() {
				$( window ).trigger( 'resize' );
			});

			$( window ).trigger( 'resize' );
		},

		/**
		 * Load the input fields associated with the content type.
		 *
		 * Sends an AJAX request providing the slide's settings.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {String} type The content type.
		 */
		loadControls: function( type ) {
			var that = this,
				data = this.currentSlide.getData( 'settings' );

			this.$editor.find( '.content-type-settings' ).empty();
			
			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_load_content_type_settings', type: type, data: JSON.stringify( data ) },
				complete: function( data ) {
					$( '.content-type-settings' ).append( data.responseText );

					if ( type === 'posts' ) {
						that.handlePostsSelects();
					}
				}
			});
		},

		/**
		 * Handle changes in the post names and taxonomies select.
		 *
		 * When the selected post names change, load the new associates
		 * taxonomies and construct the options for the taxonomy terms.
		 *
		 * Also, listen when the selected taxonomy terms change in order
		 * to keep a list of all selected terms. The list is useful in
		 * case the content type changes, because the selected taxonomy
		 * terms will be automatically populated next time when the
		 * 'Posts' content type is selected.
		 * 
		 * @since 4.0.0
		 */
		handlePostsSelects: function() {
			var that = this,
				$postTypes = this.$editor.find( 'select[name="posts_post_types"]' ),
				$taxonomies = this.$editor.find( 'select[name="posts_taxonomies"]' ),
				selectedTaxonomies = $taxonomies.val() || [];


			// detect when post names change
			$postTypes.on( 'change', function() {
				var postNames = $(this).val();

				$taxonomies.empty();

				if ( postNames !== null ) {
					SliderProAdmin.getTaxonomies( postNames, function( data ) {
						$.each( postNames, function( index, postName ) {
							var taxonomies = data[ postName ];
								
							$.each( taxonomies, function( index, taxonomy ) {
								var	$taxonomy = $( '<optgroup label="' + taxonomy[ 'label' ] + '"></optgroup>' ).appendTo( $taxonomies );

								$.each( taxonomy[ 'terms' ], function( index, term ) {
									var selected = $.inArray( term[ 'full' ], selectedTaxonomies ) !== -1 ? ' selected="selected"' : '';
									$( '<option value="' + term[ 'full' ] + '"' + selected + '>' + term[ 'name' ] + '</option>' ).appendTo( $taxonomy );
								});
							});
						});

						$taxonomies.multiCheck( 'refresh' );
					});
				} else {
					$taxonomies.multiCheck( 'refresh' );
				}
			});

			// detect when taxonomies change
			$taxonomies.on( 'change', function( event ) {
				$taxonomies.find( 'option' ).each( function() {
					var option = $( this ),
						term =  option.attr( 'value' ),
						index = $.inArray( term, selectedTaxonomies );

					if ( option.is( ':selected' ) === true && index === -1 ) {
						selectedTaxonomies.push( term );
					} else if ( option.is( ':selected' ) === false && index !== -1 ) {
						selectedTaxonomies.splice( index, 1 );
					}
				});
			});

			$postTypes.multiCheck({ width: 215 });
			$taxonomies.multiCheck({ width: 215 });
		},

		/**
		 * Save the settings.
		 *
		 * Create a new object in which the current settings are
		 * saved and pass the data to the slide.
		 *
		 * If the content type is changed, update the slide's
		 * preview.
		 * 
		 * @since 4.0.0
		 */
		save: function() {
			var that = this,
				data = {};

			this.$editor.find( '.slide-setting' ).each(function() {
				var $setting = $( this );

				if ( typeof $setting.attr( 'multiple' ) !== 'undefined' ) {
					data[ $setting.attr( 'name' ) ] =  $setting.val() !== null ? $setting.val() : [];
				} else if ( $setting.attr( 'type' ) === 'checkbox' ) {
					data[ $setting.attr( 'name' ) ] =  $setting.is( ':checked' );
				} else {
					data[ $setting.attr( 'name' ) ] =  $setting.val();
				}
			});

			this.currentSlide.setData( 'settings', data );

			if ( this.needsPreviewUpdate === true ) {
				this.currentSlide.updateSlidePreview();
				this.needsPreviewUpdate = false;
			}
		},

		/**
		 * Close the editor.
		 *
		 * Remove all event listeners.
		 * 
		 * @since 4.0.0
		 */
		close: function() {
			this.$editor.find( '.close-x' ).off( 'click' );

			this.$editor.find( 'select[name="posts_post_types"]' ).multiCheck( 'destroy' );
			this.$editor.find( 'select[name="posts_taxonomies"]' ).multiCheck( 'destroy' );

			this.$editor.find( 'select[name="content_type"]' ).off( 'change' );
			this.$editor.find( 'select[name="posts_post_types"]' ).off( 'change' );
			this.$editor.find( 'select[name="posts_taxonomies"]' ).off( 'change' );

			$( window ).off( 'resize.settingsEditor' );

			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	/*
	 * ======================================================================
	 * Media loader
	 * ======================================================================
	 */

	var MediaLoader = {

		/**
		 * Open the WordPress media loader and pass the
		 * information of the selected images to the 
		 * callback function.
		 *
		 * The passed that is the image's url, alt, title,
		 * width and height.
		 * 
		 * @since 4.0.0
		 */
		open: function( callback ) {
			var selection = [],
				insertReference = wp.media.editor.insert;
			
			wp.media.editor.send.attachment = function( props, attachment ) {
				var image = typeof attachment.sizes[ props.size ] !== 'undefined' ? attachment.sizes[ props.size ] : attachment.sizes[ 'full' ],
					id = attachment.id,
					url = image.url,
					width = image.width,
					height = image.height,
					alt = attachment.alt,
					title = attachment.title;

				selection.push({ id: id, url: url, alt: alt, title: title, width: width, height: height });
			};

			wp.media.editor.insert = function( prop ) {
				callback.call( this, selection );

				wp.media.editor.insert = insertReference;
			};

			wp.media.editor.open( 'media-loader' );
		}
	};

	/*
	 * ======================================================================
	 * Preview window
	 * ======================================================================
	 */
	
	var PreviewWindow = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		previewWindow: null,

		/**
		 * Reference to the slider instance.
		 *
		 * @since 4.0.0
		 * 
		 * @type {jQuery Object}
		 */
		slider: null,

		/**
		 * The slider's data.
		 *
		 * @since 4.0.0
		 * 
		 * @type {Object}
		 */
		sliderData: null,

		/**
		 * Open the preview window and pass the slider's data,
		 * which consists of slider settings and each slide's
		 * settings and content.
		 *
		 * Send an AJAX request with the data and receive the 
		 * slider's HTML markup and inline JavaScript.
		 *
		 * @since 4.0.0
		 * 
		 * @param  {Object} data The data of the slider
		 */
		open: function( data ) {
			var that = this,
				spinner = $( '.preview-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$( 'body' ).append( '<div class="modal-overlay"></div>' +
				'<div class="modal-window-container preview-window">' +
				'	<div class="modal-window">' +
				'		<span class="close-x"></span>' +
				'	</div>' +
				'</div>');

			this.sliderData = data;

			this.init();

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_preview_slider', data: JSON.stringify( data ) },
				complete: function( data ) {
					that.previewWindow.append( data.responseText );
					that.slider = that.previewWindow.find( '.slider-pro' );
					that.previewWindow.css( 'visibility', '' );
					spinner.css( { 'display': '', 'visibility': '' } );
					
					setTimeout( function() {
						$( window ).trigger( 'resize' );
					}, 100 );
				}
			});
		},

		/**
		 * Initialize the preview.
		 *
		 * Detect when the window is resized and resize the preview
		 * window accordingly, and also based on the slider's set
		 * width.
		 *
		 * @since 4.0.0
		 */
		init: function() {
			var that = this;

			this.previewWindow = $( '.preview-window .modal-window' );

			this.previewWindow.find( '.close-x' ).on( 'click', function( event ) {
				that.close();
			});

			this.previewWindow.css( 'visibility', 'hidden' );

			var previewWidth = this.sliderData[ 'settings' ][ 'width' ],
				previewHeight = this.sliderData[ 'settings' ][ 'height' ],
				visibleSize = this.sliderData[ 'settings' ][ 'visible_size' ],
				forceSize = this.sliderData[ 'settings' ][ 'force_size' ],
				orientation = this.sliderData[ 'settings' ][ 'orientation' ],
				isThumbnailScroller = this.sliderData[ 'settings' ][ 'auto_thumbnail_images' ],
				thumbnailScrollerOrientation = this.sliderData[ 'settings' ][ 'thumbnails_position' ] === 'top' || this.sliderData[ 'settings' ][ 'thumbnails_position' ] === 'bottom' ? 'horizontal' : 'vertical';

			$.each( this.sliderData.slides, function( index, element ) {
				if ( ( typeof element.thumbnail_source !== 'undefined' && element.thumbnail_source !== '' ) || ( typeof element.thumbnail_content !== 'undefined' && element.thumbnail_content !== '' ) ) {
					isThumbnailScroller = true;
				}
			});

			if ( visibleSize !== 'auto' ) {
				if ( orientation === 'horizontal' ) {
					previewWidth = visibleSize;
				} else if ( orientation === 'vertical' ) {
					previewHeight = visibleSize;
				}
			}

			if ( forceSize === 'fullWidth' ) {
				previewWidth = '100%';
			} else if ( forceSize === 'fullWindow' ) {
				previewWidth = '100%';
				previewHeight = '100%';
			}

			var isPercentageWidth = previewWidth.toString().indexOf( '%' ) !== -1,
				isPercentageHeight = previewHeight.toString().indexOf( '%' ) !== -1;

			if ( isPercentageWidth === false && isThumbnailScroller === true && thumbnailScrollerOrientation === 'vertical' ) {
				previewWidth = parseInt( previewWidth, 10 ) + parseInt( this.sliderData[ 'settings' ][ 'thumbnail_width' ], 10 );
			}

			$( window ).on( 'resize.sliderPro', function() {
				if ( isPercentageWidth === true ) {
					that.previewWindow.css( 'width', $( window ).width() * ( parseInt( previewWidth, 10 ) / 100 ) - 60 );
				} else if ( previewWidth >= $( window ).width() - 60 ) {
					that.previewWindow.css( 'width', $( window ).width() - 60 );
				} else {
					that.previewWindow.css( 'width', previewWidth );
				}

				if ( isPercentageHeight === true ) {
					that.previewWindow.css( 'height', $( window ).height() * ( parseInt( previewHeight, 10 ) / 100 ) );
				}

				if ( that.previewWindow.outerWidth() >= $( window ).width() ) {
					that.previewWindow.parent().addClass( 'modal-window-left' );
				} else {
					that.previewWindow.parent().removeClass( 'modal-window-left' );
				}

				if ( that.previewWindow.outerHeight() >= $( window ).height() - 60 ) {
					that.previewWindow.parent().addClass( 'modal-window-top' );
				} else {
					that.previewWindow.parent().removeClass( 'modal-window-top' );
				}
			});

			$( window ).trigger( 'resize' );
		},

		/**
		 * Close the preview window.
		 *
		 * Remove event listeners and elements.
		 *
		 * @since 4.0.0
		 */
		close: function() {
			this.previewWindow.find( '.close-x' ).off( 'click' );
			$( window ).off( 'resize.sliderPro' );

			this.slider.sliderPro( 'destroy' );
			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	$( document ).ready(function() {
		SliderProAdmin.init();
	});

	window.sliderpro = window.sliderpro || {};

	window.sliderpro.admin = SliderProAdmin;
	window.sliderpro.preview = PreviewWindow;

})( jQuery );

/*
 * ======================================================================
 * MultiCheck
 * ======================================================================
 */
	
;(function( $ ) {

	var MultiCheck = function( instance, options ) {

		this.options = options;
		this.isOpened = false;

		this.$select = $( instance );
		this.$multiCheck = null;
		this.$multiCheckHeader = null;
		this.$multiCheckContent = null;

		this.uid = new Date().valueOf() * Math.random();
		this.counter = 0;

		this.init();
	};

	MultiCheck.prototype = {

		init: function() {
			var that = this;

			this.settings = $.extend( {}, this.defaults, this.options );

			this.$multiCheck = $( '<div class="multi-check"></div>' ).css( 'width', this.settings.width );
			this.$multiCheckHeader = $( '<button type="button" class="multi-check-header"><span class="multi-check-header-text"></span><span class="multi-check-header-arrow">▼</span></button>' ).appendTo( this.$multiCheck );
			this.$multiCheckContent = $( '<ul class="multi-check-content"></ul>' ).appendTo( this.$multiCheck );

			this.$multiCheckHeader.on( 'mousedown.multiCheck', function( event ) {
				if ( that.isOpened === false ) {
					that.open();
				} else if ( that.isOpened === true ) {
					that.close();
				}
			});
			
			$( document ).on( 'mousedown.multiCheck.' + this.uid , function( event ) {
				if ( $.contains( that.$multiCheck[0], event.target ) === false ) {
					that.close();
				}
			});

			this.refresh();

			this.$select.after( this.$multiCheck );
			this.$select.hide();
			this.$multiCheckContent.hide();
		},

		refresh: function() {
			var that = this;

			this.counter = 0;

			this.$multiCheckContent.find( '.single-check' ).off( 'change.multiCheck' );
			this.$multiCheckContent.empty();

			this.$select.children().each(function() {
				if ( $( this ).is( 'optgroup' ) ) {
					$( '<li class="group-label">' + $( this ).attr( 'label' ) + '</li>' ).appendTo( that.$multiCheckContent );

					$( this ).children().each(function() {
						that._optionToCheckbox( $( this ) );
					});
				} else {
					that._optionToCheckbox( $( this ) );
				}
			});

			this.$multiCheckContent.find( '.single-check' ).on( 'change.multiCheck', function() {
				if ( $( this ).is( ':checked' ) ) {
					$( this ).data( 'option' ).prop( 'selected', true );
				} else {
					$( this ).data( 'option' ).prop( 'selected', false );
				}

				that.$select.trigger( 'change' );

				that._updateHeader();
			});

			this._updateHeader();
		},

		_optionToCheckbox: function( target ) {
			var $singleCheckContainer = $( '<li class="single-check-container"></li>' ).appendTo( this.$multiCheckContent ),
				$singleCheck = $( '<input id="single-check-' + this.uid + '-' + this.counter + '" class="single-check" type="checkbox" value="' + target.attr( 'value' ) + '"' + ( target.is( ':selected' ) ? ' checked="checked"' : '' ) + ' />' ).appendTo( $singleCheckContainer ),
				$singleCheckLabel = $( '<label for="single-check-' + this.uid + '-' + this.counter + '">' + target.text() + '</label>' ).appendTo( $singleCheckContainer );
			
			$singleCheck.data( 'option', target );

			this.counter++;
		},

		_updateHeader: function() {
			var $headerText = this.$multiCheckHeader.find( '.multi-check-header-text' ),
				text = '',
				count = 0,
				that = this;

			this.$multiCheckContent.find( '.single-check' ).each( function() {
				if ( $( this ).is( ':checked' ) ) {
					if ( text !== '' ) {
						text += ', ';
					}

					text += $( this ).siblings( 'label' ).text();
					count++;
				}
			});

			if ( count === 0 ) {
				text = 'Click to select';
			} else if ( count >= 2 ) {
				text = count + ' selected';
			}

			$headerText.text( text );
		},

		open: function() {
			var that = this;

			this.isOpened = true;

			this.$multiCheckContent.show();
		},

		close: function() {
			this.isOpened = false;

			this.$multiCheckContent.hide();
		},

		destroy: function() {
			this.$select.removeData( 'multiCheck' );
			this.$multiCheckHeader.off( 'mousedown.multiCheck' );
			$( document ).off( 'mousedown.multiCheck.' + this.uid );
			this.$multiCheckContent.find( '.single-check' ).off( 'change.multiCheck' );
			this.$multiCheck.remove();
			this.$select.show();
		},

		defaults: {
			width: 200
		}

	};

	$.fn.multiCheck = function( options ) {
		var args = Array.prototype.slice.call( arguments, 1 );

		return this.each(function() {
			if ( typeof $( this ).data( 'multiCheck' ) === 'undefined' ) {
				var newInstance = new MultiCheck( this, options );

				$( this ).data( 'multiCheck', newInstance );
			} else if ( typeof options !== 'undefined' ) {
				var	currentInstance = $( this ).data( 'multiCheck' );

				if ( typeof currentInstance[ options ] === 'function' ) {
					currentInstance[ options ].apply( currentInstance, args );
				} else {
					$.error( options + ' does not exist in multiCheck.' );
				}
			}
		});
	};

})( jQuery );

/*
 * ======================================================================
 * LightSortable
 * ======================================================================
 */

;(function( $ ) {

	var LightSortable = function( instance, options ) {

		this.options = options;
		this.$container = $( instance );
		this.$selectedChild = null;
		this.$placeholder = null;

		this.currentMouseX = 0;
		this.currentMouseY = 0;
		this.slideInitialX = 0;
		this.slideInitialY = 0;
		this.initialMouseX = 0;
		this.initialMouseY = 0;
		this.isDragging = false;
		
		this.checkHover = 0;

		this.uid = new Date().valueOf();

		this.events = $( {} );
		this.startPosition = 0;
		this.endPosition = 0;

		this.init();
	};

	LightSortable.prototype = {

		init: function() {
			this.settings = $.extend( {}, this.defaults, this.options );

			this.$container.on( 'mousedown.lightSortable' + this.uid, $.proxy( this._onDragStart, this ) );
			$( document ).on( 'mousemove.lightSortable.' + this.uid, $.proxy( this._onDragging, this ) );
			$( document ).on( 'mouseup.lightSortable.' + this.uid, $.proxy( this._onDragEnd, this ) );
		},

		_onDragStart: function( event ) {
			if ( event.which !== 1 || $( event.target ).is( 'select' ) || $( event.target ).is( 'input' ) || $( event.target ).is( 'a' ) ) {
				return;
			}

			this.$selectedChild = $( event.target ).is( this.settings.children ) ? $( event.target ) : $( event.target ).parents( this.settings.children );

			if ( this.$selectedChild.length === 1 ) {
				this.initialMouseX = event.pageX;
				this.initialMouseY = event.pageY;
				this.slideInitialX = this.$selectedChild.position().left;
				this.slideInitialY = this.$selectedChild.position().top;

				this.startPosition = this.$selectedChild.index();

				event.preventDefault();
			}
		},

		_onDragging: function( event ) {
			if ( this.$selectedChild === null || this.$selectedChild.length === 0 )
				return;

			event.preventDefault();

			this.currentMouseX = event.pageX;
			this.currentMouseY = event.pageY;

			if ( ! this.isDragging ) {
				this.isDragging = true;

				this.trigger( { type: 'sortStart' } );
				if ( $.isFunction( this.settings.sortStart ) ) {
					this.settings.sortStart.call( this, { type: 'sortStart' } );
				}

				var tag = this.$container.is( 'ul' ) || this.$container.is( 'ol' ) ? 'li' : 'div';

				this.$placeholder = $( '<' + tag + '>' ).addClass( 'ls-ignore ' + this.settings.placeholder )
					.insertAfter( this.$selectedChild );

				if ( this.$placeholder.width() === 0 ) {
					this.$placeholder.css( 'width', this.$selectedChild.outerWidth() );
				}

				if ( this.$placeholder.height() === 0 ) {
					this.$placeholder.css( 'height', this.$selectedChild.outerHeight() );
				}

				this.$selectedChild.css( {
						'pointer-events': 'none',
						'position': 'absolute',
						left: this.$selectedChild.position().left,
						top: this.$selectedChild.position().top,
						width: this.$selectedChild.width(),
						height: this.$selectedChild.height()
					} )
					.addClass( 'ls-ignore' );

				this.$container.append( this.$selectedChild );

				$( 'body' ).css( 'user-select', 'none' );

				var that = this;

				this.checkHover = setInterval( function() {

					that.$container.find( that.settings.children ).not( '.ls-ignore' ).each( function() {
						var $currentChild = $( this );

						if ( that.currentMouseX > $currentChild.offset().left &&
							that.currentMouseX < $currentChild.offset().left + $currentChild.width() &&
							that.currentMouseY > $currentChild.offset().top &&
							that.currentMouseY < $currentChild.offset().top + $currentChild.height() ) {

							if ( $currentChild.index() >= that.$placeholder.index() )
								that.$placeholder.insertAfter( $currentChild );
							else
								that.$placeholder.insertBefore( $currentChild );
						}
					});
				}, 200 );
			}

			this.$selectedChild.css( { 'left': this.currentMouseX - this.initialMouseX + this.slideInitialX, 'top': this.currentMouseY - this.initialMouseY + this.slideInitialY } );
		},

		_onDragEnd: function() {
			if ( this.isDragging ) {
				this.isDragging = false;

				$( 'body' ).css( 'user-select', '');

				this.$selectedChild.css( { 'position': '', left: '', top: '', width: '', height: '', 'pointer-events': '' } )
									.removeClass( 'ls-ignore' )
									.insertAfter( this.$placeholder );

				this.$placeholder.remove();

				clearInterval( this.checkHover );

				this.endPosition = this.$selectedChild.index();

				this.trigger( { type: 'sortEnd' } );
				if ( $.isFunction( this.settings.sortEnd ) ) {
					this.settings.sortEnd.call( this, { type: 'sortEnd', startPosition: this.startPosition, endPosition: this.endPosition } );
				}
			}

			this.$selectedChild = null;
		},

		destroy: function() {
			this.$container.removeData( 'lightSortable' );

			if ( this.isDragging ) {
				this._onDragEnd();
			}

			this.$container.off( 'mousedown.lightSortable.' + this.uid );
			$( document ).off( 'mousemove.lightSortable.' + this.uid );
			$( document ).off( 'mouseup.lightSortable.' + this.uid );
		},

		on: function( type, callback ) {
			return this.events.on( type, callback );
		},
		
		off: function( type ) {
			return this.events.off( type );
		},

		trigger: function( data ) {
			return this.events.triggerHandler( data );
		},

		defaults: {
			placeholder: '',
			sortStart: function() {},
			sortEnd: function() {}
		}

	};

	$.fn.lightSortable = function( options ) {
		var args = Array.prototype.slice.call( arguments, 1 );

		return this.each(function() {
			if ( typeof $( this ).data( 'lightSortable' ) === 'undefined' ) {
				var newInstance = new LightSortable( this, options );

				$( this ).data( 'lightSortable', newInstance );
			} else if ( typeof options !== 'undefined' ) {
				var	currentInstance = $( this ).data( 'lightSortable' );

				if ( typeof currentInstance[ options ] === 'function' ) {
					currentInstance[ options ].apply( currentInstance, args );
				} else {
					$.error( options + ' does not exist in lightSortable.' );
				}
			}
		});
	};

})( jQuery );

/*
 * ======================================================================
 * lightURLParse
 * ======================================================================
 */

;(function( $ ) {

	$.lightURLParse = function( url ) {
		var urlArray = url.split( '?' )[1].split( '&' ),
			result = [];

		$.each( urlArray, function( index, element ) {
			var elementArray = element.split( '=' );
			result[ elementArray[ 0 ] ] = elementArray[ 1 ];
		});

		return result;
	};

})( jQuery );

/*
 * ======================================================================
 * CodeEditor
 * ======================================================================
 */
	
;(function( $ ) {

	var CodeEditor = function( instance, options = {} ) {

		this.options = options;
		this.$textarea = $( instance );
		this.isCodeMirror = false;
		this.codeMirror = null;

		this.init();
	};

	CodeEditor.prototype = {

		init: function() {
			var that = this;

			this.settings = $.extend( {}, this.defaults, this.options );

			if ( typeof wp.codeEditor.initialize !== 'undefined' ) {
				var cm = wp.codeEditor.initialize( this.$textarea, this.setting );

				this.codeMirror = cm.codemirror;
				this.isCodeMirror = true;

				this.codeMirror.on( 'change', function() {
					that.trigger({ type: 'edit', value: that.codeMirror.getValue() });
				});
			} else {
				this.$textarea.on( 'input', function() {
					that.trigger({ type: 'edit', value: that.$textarea.val() });
				});
			}
		},

		getValue: function() {
			return this.isCodeMirror === true ? this.codeMirror.getValue() : this.$textarea.val();
		},

		// Attach an event handler to the textarea
		on: function( type, callback ) {
			return this.$textarea.on( type, callback );
		},

		// Detach an event handler to the textarea
		off: function( type ) {
			return this.$textarea.off( type );
		},

		// Trigger an event on the textarea
		trigger: function( data ) {
			return this.$textarea.triggerHandler( data );
		},

		refresh: function() {
			if ( this.isCodeMirror === true ) {
				this.codeMirror.refresh();
			}
		},

		destroy: function() {
			this.$textarea.removeData( 'codeEditor' );

			if ( this.isCodeMirror === true ) {
				this.codeMirror.toTextArea();
				this.codeMirror.off( 'change' );
			} else {
				this.$textarea.off( 'input' );
			}
		},

		defaults: {
			
		}
	};

	$.fn.codeEditor = function( options ) {
		var args = Array.prototype.slice.call( arguments, 1 );
		
		return this.each(function() {
			if ( typeof $( this ).data( 'codeEditor' ) === 'undefined' ) {
				var newInstance = new CodeEditor( this, options );

				$( this ).data( 'codeEditor', newInstance );
			} else if ( typeof options !== 'undefined' ) {
				var	currentInstance = $( this ).data( 'codeEditor' );

				if ( typeof currentInstance[ options ] === 'function' ) {
					currentInstance[ options ].apply( currentInstance, args );
				} else {
					$.error( options + ' does not exist in codeEditor.' );
				}
			}
		});
	};

})( jQuery );