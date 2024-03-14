//--------------------------------------------------------------------//
// Hester Core Demo Library script.
//--------------------------------------------------------------------//
;(function( $ ) {
	"use strict";

	/**
	 * Common element caching.
	 */
	var $body     = $( 'body' );
	var $document = $( document );
	var $wrapper  = $( '#page' );
	var $html     = $( 'html' );
	var $this;

	/**
	 * Holds most important methods that bootstrap the whole theme.
	 * 
	 * @type {Object}
	 */
	var HesterCoreDemoLibrary = {

		import_start_time : '',
		install_plugins   : [],
		installed_plugins : [],
		progress          : 0,
		import_steps      : [],
		completed_steps   : [],

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			// Document ready
			$(document).ready( HesterCoreDemoLibrary.ready );

			// Window load
			$(window).on( 'load', HesterCoreDemoLibrary.load );

			// Bind UI actions
			HesterCoreDemoLibrary.bindUIActions();

			// Trigger event when Hester fully loaded
			$(document).trigger( 'hesterCoreReady' );
		},

		//--------------------------------------------------------------------//
		// Events
		//--------------------------------------------------------------------//

		/**
		 * Document ready.
		 *
		 * @since 1.0.0
		 */
		ready: function() {
		},

		/**
		 * Window load.
		 *
		 * @since 1.0.0
		 */
		load: function() {
			HesterCoreDemoLibrary.populateTemplates( hesterCoreDemoLibrary.templates );
		},

		/**
		 * Bind UI actions.
		 *
		 * @since 1.0.0
		*/
		bindUIActions: function() {

			// Demo preview screen.
			$document.on( 'click' , '.hester-demo .demo-screenshot, .hester-demo .preview', HesterCoreDemoLibrary.preview );
			
			// Direct import button.
			$document.on( 'click' , '.hester-demo .import', function(e) {
				$(this).siblings( '.preview' ).click();
				$( '.wp-full-overlay-main iframe' ).on( 'load', function() {
					$( '.wp-full-overlay-header .hester-demo-import' ).trigger( 'click' );
				});
			} );

			// Close preview screen.
			$document.on( 'click', '.close-full-overlay', HesterCoreDemoLibrary.closePreview );
			
			$document.keyup( function(e) {
				if ( 27 === e.keyCode ) {
					$( '.close-full-overlay' ).trigger( 'click' );
				}
			});

			// Next/Preview demo.
			$document.on( 'click', '.next-theme', HesterCoreDemoLibrary.previewNext );
			$document.on( 'click', '.previous-theme', HesterCoreDemoLibrary.previewPrevious );

			// Preview demo on a device screen.
			$document.on( 'click', '.devices button', HesterCoreDemoLibrary.previewDevice );

			// Collapse sidebar.
			$document.on( 'click', '.collapse-sidebar', HesterCoreDemoLibrary.collapseSidebar );

			// Collapse section.
			$document.on( 'click', '.hester-demo-section-title .control-toggle label', HesterCoreDemoLibrary.collapsePreviewSection );

			// Import demo.
			$document.on( 'click', '.hester-demo-import', HesterCoreDemoLibrary.importDemo );

			$document.on( 'click', '#import_content, #import_media', HesterCoreDemoLibrary.importOptionsContent );

			// Import steps triggers.
			$document.on( 'hester-core-import_started', HesterCoreDemoLibrary.importStarted );
			$document.on( 'hester-core-install_plugins', HesterCoreDemoLibrary.pluginsInstallActivate );
			$document.on( 'hester-core-import_customizer', HesterCoreDemoLibrary.importCustomizer );
			$document.on( 'hester-core-import_content', HesterCoreDemoLibrary.importContent );
			$document.on( 'hester-core-import_widgets', HesterCoreDemoLibrary.importWidgets );
			$document.on( 'hester-core-import_options', HesterCoreDemoLibrary.importOptions );
			$document.on( 'hester-core-import_wpforms', HesterCoreDemoLibrary.importWPForms );
			$document.on( 'hester-core-import_completed', HesterCoreDemoLibrary.importCompleted );

			// Filter template list.
			$document.on( 'click', '.demo-filters a', HesterCoreDemoLibrary.filters );
			$document.on( 'input', '.demo-search input', HesterCoreDemoLibrary.search );
		},

		//--------------------------------------------------------------------//
		// Functions
		//--------------------------------------------------------------------//

		/**
		 * Demo preview page.
		 *
		 * On click on image, more link & preview button.
		 */
		preview: function( event ) {

			event.preventDefault();

			var site_id = $(this).parents('.hester-demo').data('demo-id') || '';

			var self = $(this).parents( '.hester-demo' );
			self.addClass( 'hester-demo-previewed' );

			$html.addClass( 'hester-demo-preview-on' );

			HesterCoreDemoLibrary.previewDemo( self );
		},

		/**
		 * Preview Demo website.
		 */
		previewDemo: function( anchor ) {

			var template = wp.template( 'hester-core-demo-preview' );

			var data = {
				id                 : anchor.data( 'demo-id' ),
				pro                : anchor.data( 'demo-pro' ),
				url                : anchor.data( 'demo-url' ),
				screenshot         : anchor.data( 'demo-screenshot' ),
				name               : anchor.data( 'demo-name' ),
				description        : anchor.data( 'demo-description' ),
				slug               : anchor.data( 'demo-slug' ),
				required_plugins   : anchor.data( 'required-plugins' ),
				//categories   	   : anchor.data( 'categories' ),
				is_pro			   : !!hesterCoreDemoLibrary.is_pro,
				upgrade_to_pro	 : hesterCoreDemoLibrary.upgrade_to_pro_url
			};

			$( '.theme-install-overlay' ).remove();
			$( '.hester-section.demos' ).append( template( data ) );
			$( '.theme-install-overlay' ).css('display', 'block');

			HesterCoreDemoLibrary.updateNextPrev();

			$( '.wp-full-overlay-main iframe' ).on( 'load', function() {
				$( '.hester-demo-preview .hester-demo-import' ).removeAttr( 'disabled' );
			});
		},

		/**
		 * Check Next Previous Buttons.
		 */
		updateNextPrev: function() {

			if ( $body.hasClass( 'importing' ) ) {
				$( '.next-theme, .previous-theme' ).addClass( 'disabled' );
				return;
			}

			var current = $( '.hester-demo-previewed' ).parent();
			var next    = current.nextAll( '.hester-column' ).length;
			var prev    = current.prevAll( '.hester-column' ).length;

			if ( 0 == next ) {
				$( '.next-theme' ).addClass( 'disabled' );
			} else if ( 0 != next ) {
				$( '.next-theme' ).removeClass( 'disabled' );
			}

			if ( 0 == prev ) {
				$( '.previous-theme' ).addClass( 'disabled' );
			} else if ( 0 != prev ) {
				$( '.previous-theme' ).removeClass( 'disabled' );
			}

			return;
		},

		/**
		 * Close demo preview screen.
		 */
		closePreview: function( event ) {

			event.preventDefault();

			// Import process is started?
			// And Closing the window? Then showing the warning confirm message.
			if ( $('body').hasClass( 'importing' ) && ! confirm( hesterCoreDemoLibrary.strings.closeWindowWarning ) ) {
				return;
			}

			$( 'body' ).removeClass( 'importing' );
			$( '.previous-theme, .next-theme' ).removeClass( 'disabled' );
			$( '.theme-install-overlay' ).css( 'display', 'none' );
			$( '.theme-install-overlay' ).remove();
			$( '.hester-demo-previewed' ).removeClass( 'hester-demo-previewed' );
			$html.removeClass( 'hester-demo-preview-on' );
		},

		/**
		 * Preview previous demo.
		 */
		previewPrevious: function( event ) {
			
			event.preventDefault();

			var current = $( '.hester-demo-previewed' ).removeClass( 'hester-demo-previewed' ).parent();
			var prev    = current.prev( '.hester-column' ).find( '.hester-demo' ).addClass( 'hester-demo-previewed' );

			var site_id = $(this).parents( '.wp-full-overlay-header' ).data('demo-id') || '';

			HesterCoreDemoLibrary.previewDemo( prev );
		},

		/**
		 * Preview next demo.
		 */
		previewNext: function( event ) {
			
			event.preventDefault();
			
			var current = $( '.hester-demo-previewed' ).removeClass( 'hester-demo-previewed' ).parent();
			var next    = current.next( '.hester-column' ).find( '.hester-demo' ).addClass( 'hester-demo-previewed' );

			var site_id = $(this).parents( '.wp-full-overlay-header' ).data( 'demo-id' ) || '';

			HesterCoreDemoLibrary.previewDemo( next );
		},

		/**
		 * Preview on a device sized screen.
		 */
		previewDevice: function( event ) {

			var device = $( event.currentTarget ).data( 'device' );

			$( '.theme-install-overlay' )
				.removeClass( 'preview-desktop preview-tablet preview-mobile' )
				.addClass( 'preview-' + device )
				.data( 'current-preview-device', device );

			HesterCoreDemoLibrary.previewDeviceButtons( device );
		},

		/**
		 * Toggle preview device buttons.
		 */
		previewDeviceButtons: function( device ) {
			
			var $devices = $( '.wp-full-overlay-footer .devices' );

			$devices.find( 'button' )
				.removeClass( 'active' )
				.attr( 'aria-pressed', false );

			$devices.find( 'button.preview-' + device )
				.addClass( 'active' )
				.attr( 'aria-pressed', true );
		},

		/**
		 * Collapse Sidebar.
		 */
		collapseSidebar: function() {

			event.preventDefault();

			var overlay = $( '.wp-full-overlay' );

			if ( overlay.hasClass( 'expanded' ) ) {
				overlay.removeClass( 'expanded' );
				overlay.addClass( 'collapsed' );
				return;
			}

			if ( overlay.hasClass( 'collapsed' ) ) {
				overlay.removeClass( 'collapsed' );
				overlay.addClass( 'expanded' );
				return;
			}
		},

		/**
		 * Collapse Section.
		 */
		collapsePreviewSection: function() {

			var section_content = $(this).closest( '.hester-demo-section-title' ).next( '.hester-demo-section-content' );

			if ( $(this).prev('input[type="checkbox"]').is(':checked') ) {
				section_content.removeClass( 'hidden' );
			} else {
				section_content.addClass( 'hidden' );
			}

		},

		/**
		 * Start Demo import.
		 */
		importDemo: function( event ) {

			event.preventDefault();

			if ( ! confirm( hesterCoreDemoLibrary.strings.importDemoWarning ) ) {
				return;
			}	

			var date = new Date();

			HesterCoreDemoLibrary.import_start_time = new Date();

			var disabled = $(this).attr( 'disabled' );

			if ( typeof disabled === 'undefined' || ! $(this).hasClass('disabled') ) {

				// Get list of plugins to install.
				HesterCoreDemoLibrary.install_plugins = $( '.plugin-list input:checked' ).not( ':disabled' ).map( function(){
					return {
						slug:   $(this).data( 'slug' ),
						name:   $(this).next( '.hester-label' ).html(),
						status: $(this).data( 'status' ),
					};
				}).get();

				// Get import options.
				HesterCoreDemoLibrary.import_steps = $( '.hester-demo-section-content.import-options input:checked' ).map( function(){
					var step = $(this).attr('id');

					if ( 'import_media' !== step ) {
						return $(this).attr('id');
					}
				}).get();

				if ( HesterCoreDemoLibrary.install_plugins.length ) {
					HesterCoreDemoLibrary.import_steps.unshift( 'install_plugins' );
				}

				if ( HesterCoreDemoLibrary.import_steps.includes( 'import_content') ) {

					if ( $( '#install_plugin_wpforms-lite' ).is(':checked') ) {
						HesterCoreDemoLibrary.import_steps.splice( HesterCoreDemoLibrary.import_steps.indexOf( 'import_content'), 0, 'import_wpforms' );
					}
					HesterCoreDemoLibrary.import_steps.push( 'import_options' );				
				}

				HesterCoreDemoLibrary.import_steps.unshift( 'import_started' );
				HesterCoreDemoLibrary.import_steps.push( 'import_completed' );

				// Start import.
				$body.addClass( 'importing' );

				HesterCoreDemoLibrary.updateNextPrev();

				$( '.hester-checkbox input' ).attr( 'disabled', 'disabled' );
				$( '.wp-full-overlay-header .hester-demo-import' ).text( hesterCoreDemoLibrary.strings.importing );

				$document.trigger( 'hester-core-' + HesterCoreDemoLibrary.import_steps[0] );
			}
		},

		/**
		 * Install and activate selected plugins.
		 */
		pluginsInstallActivate: function() {

			// Set up progress delta.
			HesterCoreDemoLibrary.progress_delta = ( 50 / HesterCoreDemoLibrary.import_steps.length ) / HesterCoreDemoLibrary.install_plugins.length;

			// Start plugin installation.
			HesterCoreDemoLibrary.pluginInstall( HesterCoreDemoLibrary.install_plugins[0] );
		},

		/**
		 * Install specific plugin.
		 */
		pluginInstall: function( plugin ) {

			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.installingPlugin + plugin.name );

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {

				wp.updates.requestFilesystemCredentials( event );

				$( document ).on( 'credential-modal-cancel', function() {
					wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
				} );
			}

			if ( 'not_installed' === plugin.status ) {

				wp.updates.installPlugin( {
					slug: plugin.slug,
					success: function() {

						HesterCoreDemoLibrary.updateProgressBar();

						$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.installed );

						HesterCoreDemoLibrary.pluginActivate( plugin );
					},
					error: function( response ) {
						console.log( response );
					}
				});

			} else if ( 'installed' === plugin.status ) {

				HesterCoreDemoLibrary.updateProgressBar();

				$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.installed );

				HesterCoreDemoLibrary.pluginActivate( plugin );

			} else {

				HesterCoreDemoLibrary.updateProgressBar();
				
				$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.activated );

				HesterCoreDemoLibrary.installed_plugins.push( HesterCoreDemoLibrary.install_plugins.shift() );

				if ( HesterCoreDemoLibrary.install_plugins.length ) {
					return HesterCoreDemoLibrary.pluginInstall( HesterCoreDemoLibrary.install_plugins[0] );
				} else {
					HesterCoreDemoLibrary.importNextStep();
				}

			}
		},

		/**
		 * Activate specific plugin.
		 */
		pluginActivate: function( plugin ) {

			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.activatingPlugin + plugin.name );

			return $.ajax({
				url : hester_strings.ajaxurl,
				type : 'POST',
				dataType: 'json',
				data : {
					_ajax_nonce: hester_strings.wpnonce,
					action:      'hester_core_import_step',
					import_step: 'activate_plugin',
					plugin:      plugin,
					demo_id:     $( '.wp-full-overlay-header' ).data( 'demo-slug' ),
				},
			}).then( function(data) {

				HesterCoreDemoLibrary.updateProgressBar();
				
				$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.activated );

				HesterCoreDemoLibrary.installed_plugins.push( HesterCoreDemoLibrary.install_plugins.shift() );

				if ( HesterCoreDemoLibrary.install_plugins.length ) {
					return HesterCoreDemoLibrary.pluginInstall( HesterCoreDemoLibrary.install_plugins[0] );
				} else {
					HesterCoreDemoLibrary.importNextStep();
				}
			});
		},

		/**
		 * Import step AJAX.
		 */
		importStepAJAX: function( data ) {

			data._ajax_nonce = hester_strings.wpnonce;
			data.action      = 'hester_core_import_step';
			data.demo_id     = $( '.wp-full-overlay-header' ).data( 'demo-slug' );

			return $.ajax({
				url : hester_strings.ajaxurl,
				type : 'POST',
				dataType: 'json',
				data : data,
			}).done( function(response) {

				if ( response.success ) {
					HesterCoreDemoLibrary.updateProgressBar();
					HesterCoreDemoLibrary.importNextStep();
				} else {
					console.log( response );
				}
			}).fail(function(jqXHR, textStatus, errorThrown)  {
				console.log(jqXHR);
				console.log(textStatus);
			    console.log(errorThrown);
			});
		},

		/**
		 * Import Started.
		 */
		importStarted: function() {

			var data = {
				import_step: 'import_started',
			};

			return HesterCoreDemoLibrary.importStepAJAX( data );
		},

		/**
		 * Import Customizer.
		 */
		importCustomizer: function() {

			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.importingCustomizer );

			var data = {
				'import_step' : 'import_customizer'
			};

			return HesterCoreDemoLibrary.importStepAJAX( data );
		},

		/**
		 * Import Content.
		 */
		importContent: function() {

			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.importingContent );

			var data = {
				import_step: 'import_content',
				attachments: $( '#import_media' ).is(':checked') ? 1 : 0,
			};

			return HesterCoreDemoLibrary.importStepAJAX( data );
		},

		/**
		 * Import Customizer.
		 */
		importOptions: function() {

			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.importingOptions );

			var data = {
				import_step: 'import_options',
			};

			return HesterCoreDemoLibrary.importStepAJAX( data );
		},

		/**
		 * Import WPForms.
		 */
		importWPForms: function() {

			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.importingWPForms );

			var data = {
				import_step: 'import_wpforms',
			};

			return HesterCoreDemoLibrary.importStepAJAX( data );
		},

		/**
		 * Import Customizer.
		 */
		importWidgets: function() {

			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.importingWidgets );

			var data = {
				import_step: 'import_widgets',
			};

			return HesterCoreDemoLibrary.importStepAJAX( data );
		},

		/**
		 * Import Completed.
		 */
		importCompleted: function() {

			var data = {
				import_step: 'import_completed',
			};

			HesterCoreDemoLibrary.importStepAJAX( data );

			$( '.wp-full-overlay-header .hester-demo-import' ).html( hesterCoreDemoLibrary.strings.preview );
			$( '.hester-demo-preview .hester-demo-import' ).removeClass( 'hester-demo-import' ).attr( 'href', hesterCoreDemoLibrary.homeurl );
			$( '.wp-full-overlay-footer .status' ).text( hesterCoreDemoLibrary.strings.importCompleted );

			$body.removeClass( 'importing' );

			// Particle animation
			setTimeout(function() {
				$( '.wp-full-overlay-footer .hester-btn' ).addClass('animate');
			}, 100);

			setTimeout(function() {
				$( '.wp-full-overlay-footer .hester-btn' ).removeClass('animate');
			}, 3000);

			HesterCoreDemoLibrary.updateNextPrev();
		},

		/**
		 * Trigger next import step.
		 */
		importNextStep: function() {

			// No further steps defined.
			if ( ! HesterCoreDemoLibrary.import_steps.length ) {
				$document.trigger( 'hester-core-import_completed' );
				return;
			}

			// Track completed steps.
			HesterCoreDemoLibrary.completed_steps.push( HesterCoreDemoLibrary.import_steps.shift() );

			// Update progress bar.
			HesterCoreDemoLibrary.updateProgressBar();

			// Trigger next step.
			$document.trigger( 'hester-core-' + HesterCoreDemoLibrary.import_steps[0] );
		},

		/**
		 * Update progress bar.
		 */
		updateProgressBar: function() {

			var remaining = 100 - HesterCoreDemoLibrary.progress;
			var delta     = ( remaining / HesterCoreDemoLibrary.import_steps.length );

			if ( HesterCoreDemoLibrary.install_plugins.length > 0 ) {
				delta = delta / 2;
				delta = delta / HesterCoreDemoLibrary.install_plugins.length;
			}

			HesterCoreDemoLibrary.progress += delta;

			$( '#hester-progress-bar .hester-progress-percentage' ).css( 'width', HesterCoreDemoLibrary.progress + '%' );
		},

		/**
		 * Import options: content and media dependency.
		 */
		importOptionsContent: function() {

			var $checkbox = $(this);

			if ( 'import_content' === $checkbox.attr('id') ) {

				if ( ! $checkbox.is(':checked') ) {
					$( '#import_media' ).attr( 'disabled', 'disabled' ).removeAttr( 'checked' );
				} else {
					$( '#import_media' ).removeAttr( 'disabled', 'disabled' );
				}
			}

			if ( 'import_media' === $checkbox.attr('id') && $checkbox.is(':checked') ) {
				$( '#import_content' ).attr( 'checked', 'checked' );
			}
		},

		/**
		 * Populate templates.
		 */
		populateTemplates: function( templates ) {

			$( '.hester-section.demos' ).html('');

			if ( _.isEmpty( templates ) ) {
				$( '.hester-section.demos' ).html( '<div class="hester-column">' + hesterCoreDemoLibrary.strings.noResultsFound + '</div>' );
				return;
			}

			var data;
			var demo_template = wp.template( 'hester-core-demo-item' );

			for ( var key in templates ) {
				
				data = {
					id               : key,
					pro              : templates[key].pro,
					url              : templates[key].url,
					screenshot       : templates[key].screenshot,
					name             : templates[key].name,
					description      : templates[key].description,
					slug             : templates[key].slug,
					required_plugins : templates[key].plugins,
					//categories   	 : templates[key].categories,
					is_pro			 : !!hesterCoreDemoLibrary.is_pro,
					upgrade_to_pro	 : hesterCoreDemoLibrary.upgrade_to_pro_url
				};

				$( '.hester-section.demos' ).append( demo_template( data ) );
			}
		},

		/**
		 * Handle clicks on filter items.
		 */
		filters: function() {
			var $this = $(this);

			$this.closest('ul').find('li').removeClass('selected');
			$this.closest('li').addClass('selected');

			HesterCoreDemoLibrary.filterDemoList();
		},

		/**
		 * Handle clicks on filter items.
		 */
		search: function() {
			var $this = $(this);
			var timer = 0;

			if ( timer ) {
				clearTimeout( timer );
			}

			var search_input = $this.val();

			if ( ! search_input || search_input && search_input.length >= 4 ) {	
				timer = setTimeout( function() { HesterCoreDemoLibrary.filterDemoList() }, 300 );
			}
		},

		/**
		 * Reload filtered demo list.
		 */
		filterDemoList: function() {

			var filters = {
				'category' : $( '.demo-filters .demo-categories li.selected a' ).data( 'category' ),
				'builder'  : $( '.demo-filters .demo-builders li.selected a' ).data( 'builder' ),
				's'        : $( '.demo-search input' ).val(),
			};

			return $.ajax({
				url : hester_strings.ajaxurl,
				type : 'POST',
				dataType: 'json',
				data : {
					_ajax_nonce: hester_strings.wpnonce,
					action: 'hester-core-filter-demos',
					filters: filters
				},
			}).then( function(response) {

				if ( response.success ) {
					HesterCoreDemoLibrary.populateTemplates( response.data );
				}
			});
		}

	}; // END var HesterCoreDemoLibrary.

	HesterCoreDemoLibrary.init();
	window.HesterCoreDemoLibrary = HesterCoreDemoLibrary;	

})( jQuery );
