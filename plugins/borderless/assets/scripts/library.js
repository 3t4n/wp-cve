jQuery( function ( $ ) {
	'use strict';
	
	/* ==================================================
	Events
	================================================== */
	
	$( '.library-install-plugins-content-content .borderless-library-import__plugin.plugin-item--required input[type=checkbox]' ).on( 'click', function( event ) {
		event.preventDefault();
		
		return false;
	} );
	
	$( '.js-library-install-plugins' ).on( 'click', function( event ) {
		event.preventDefault();
		
		var $button = $( this );
		
		if ( $button.hasClass( 'library-button-disabled' ) ) {
			return false;
		}
		
		var pluginsToInstall = $( '.library-install-plugins-content-content .borderless-library-import__plugin input[type=checkbox]' ).serializeArray();
		
		if ( pluginsToInstall.length === 0 ) {
			return false;
		}
		
		$button.addClass( 'library-button-disabled' );
		
		installPluginsAjaxCall( pluginsToInstall, 0, $button, false, false );
	} );
	
	$( '.js-library-install-plugins-before-import' ).on( 'click', function( event ) {
		event.preventDefault();
		
		var $button = $( this );
		
		if ( $button.hasClass( 'library-button-disabled' ) ) {
			return false;
		}
		
		var pluginsToInstall = $( '.library-install-plugins-content-content .borderless-library-import__plugin:not(.plugin-item--disabled) input[type=checkbox]' ).serializeArray();
		
		if ( pluginsToInstall.length === 0 ) {
			startImport( getUrlParameter( 'import' ) );
			
			return false;
		}
		
		$button.addClass( 'library-button-disabled' );
		
		installPluginsAjaxCall( pluginsToInstall, 0, $button, true, false );
	} );
	
	
	$( '.js-library-create-content' ).on( 'click', function( event ) {
		event.preventDefault();
		
		var $button = $( this );
		
		if ( $button.hasClass( 'library-button-disabled' ) ) {
			return false;
		}
		
		var itemsToImport = $( '.library-create-content-content .content-item input[type=checkbox]' ).serializeArray();
		
		if ( itemsToImport.length === 0 ) {
			return false;
		}
		
		$button.addClass( 'library-button-disabled' );
		
		createDemoContentAjaxCall( itemsToImport, 0, $button );
	} );
	
	
	$( document ).on( 'change', '.library--create-content .content-item input[type=checkbox]', function( event ) {
		var $checkboxes = $( '.library--create-content .content-item input[type=checkbox]' ),
		$missingPluginNotice = $( '.js-library-create-content-install-plugins-notice' ),
		missingPlugins = [];
		
		$checkboxes.each( function() {
			var $checkbox = $( this );
			if ( $checkbox.is( ':checked' ) ) {
				missingPlugins = missingPlugins.concat( getMissingPluginNamesFromImportContentPageItem( $checkbox.data( 'plugins' ) ) );
			}
		} );
		
		missingPlugins = missingPlugins.filter( onlyUnique ).join( ', ' );
		
		if ( missingPlugins.length > 0 ) {
			$missingPluginNotice.find( '.js-library-create-content-install-plugins-list' ).text( missingPlugins );
			$missingPluginNotice.show();
		} else {
			$missingPluginNotice.find( '.js-library-create-content-install-plugins-list' ).text( '' );
			$missingPluginNotice.hide();
		}
	} );
	
	
	
	/* ==================================================
	Helper functions
	================================================== */
	
	function ajaxCall( data ) {
		$.ajax({
			method:      'POST',
			url:         library.ajax_url,
			data:        data,
			contentType: false,
			processData: false,
			beforeSend:  function() {
				$( '.borderless-library-import__required-plugins' ).hide();
				$( '.js-library-importing' ).show();
			}
		})
		.done( function( response ) {
			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				ajaxCall( data );
			}
			else if ( 'undefined' !== typeof response.status && 'customizerAJAX' === response.status ) {
				var newData = new FormData();
				newData.append( 'action', 'library_import_customizer_data' );
				newData.append( 'security', library.ajax_nonce );
				
				if ( true === library.wp_customize_on ) {
					newData.append( 'wp_customize', 'on' );
				}
				
				ajaxCall( newData );
			}
			else if ( 'undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status ) {
				var newData = new FormData();
				newData.append( 'action', 'library_after_import_data' );
				newData.append( 'security', library.ajax_nonce );
				ajaxCall( newData );
			}
			else if ( 'undefined' !== typeof response.message ) {
				$( '.js-library-ajax-response' ).append( response.message );
				
				if ( 'undefined' !== typeof response.title ) {
					$( '.js-library-ajax-response-title' ).html( response.title );
				}
				
				if ( 'undefined' !== typeof response.subtitle ) {
					$( '.js-library-ajax-response-subtitle' ).html( response.subtitle );
				}
				
				$( '.js-library-importing' ).hide();
				$( '.js-library-imported' ).show();
				
				$( document ).trigger( 'libraryImportComplete' );
			}
			else {
				$( '.js-library-ajax-response' ).append( '<i class="borderless-library-import__failed-imported-icon bi bi-exclamation-circle-fill"></i><p>' + response + '</p>' );
				$( '.js-library-ajax-response-title' ).html( library.texts.import_failed );
				$( '.js-library-ajax-response-subtitle' ).html( '<p>' + library.texts.import_failed_subtitle + '</p>' );
				$( '.js-library-importing' ).hide();
				$( '.js-library-imported' ).show();
			}
		})
		.fail( function( error ) {
			$( '.js-library-ajax-response' ).append( '<i class="borderless-library-import__failed-imported-icon bi bi-exclamation-circle-fill"></i><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p>' );
			$( '.js-library-ajax-response-title' ).html( library.texts.import_failed );
			$( '.js-library-ajax-response-subtitle' ).html( '<p>' + library.texts.import_failed_subtitle + '</p>' );
			$( '.js-library-importing' ).hide();
			$( '.js-library-imported' ).show();
		});
	}
	
	function getMissingPluginNamesFromImportContentPageItem( requiredPluginSlugs ) {
		var requiredPluginSlugs = requiredPluginSlugs.split( ',' ),
		pluginList = [];
		
		library.missing_plugins.forEach( function( plugin ) {
			if ( requiredPluginSlugs.indexOf( plugin.slug ) !== -1 ) {
				pluginList.push( plugin.name )
			}
		} );
		
		return pluginList;
	}
	
	function onlyUnique( value, index, self ) {
		return self.indexOf( value ) === index;
	}
	
	function installPluginsAjaxCall( plugins, counter, $button , runImport, pluginInstallFailed ) {
		var plugin = plugins[ counter ],
		slug = plugin.name;
		
		$.ajax({
			method:      'POST',
			url:         library.ajax_url,
			data:        {
				action: 'library_install_plugin',
				security: library.ajax_nonce,
				slug: slug,
			},
			beforeSend:  function() {
				var $currentPluginItem = $( '.plugin-item-' + slug );
				$currentPluginItem.find( '.js-library-plugin-item-info' ).empty();
				$currentPluginItem.find( '.js-library-plugin-item-error' ).empty();
				$currentPluginItem.addClass( 'plugin-item--loading' );
			}
		})
		.done( function( response ) {
			var $currentPluginItem = $( '.plugin-item-' + slug );
			
			$currentPluginItem.removeClass( 'plugin-item--loading' );
			
			if ( response.success ) {
				$currentPluginItem.addClass( 'plugin-item--active' );
				$currentPluginItem.find( 'input[type=checkbox]' ).prop( 'disabled', true );
			} else {
				
				if ( -1 === response.data.indexOf( '<p>' ) ) {
					response.data = '<p>' + response.data + '</p>';
				}
				
				$currentPluginItem.find( '.js-library-plugin-item-error' ).append( response.data );
				$currentPluginItem.find( 'input[type=checkbox]' ).prop( 'checked', false );
				pluginInstallFailed = true;
			}
		})
		.fail( function( error ) {
			var $currentPluginItem = $( '.plugin-item-' + slug );
			$currentPluginItem.removeClass( 'plugin-item--loading' );
			$currentPluginItem.find( '.js-library-plugin-item-error' ).append( '<p>' + error.statusText + ' (' + error.status + ')</p>' );
			pluginInstallFailed = true;
		})
		.always( function() {
			counter++;
			
			if ( counter === plugins.length ) {
				if ( runImport ) {
					if ( ! pluginInstallFailed ) {
						startImport( getUrlParameter( 'import' ) );
					} else {
						alert( library.texts.plugin_install_failed );
					}
				}
				
				$button.removeClass( 'library-button-disabled' );
			} else {
				installPluginsAjaxCall( plugins, counter, $button, runImport, pluginInstallFailed );
			}
		} );
	}
	
	function createDemoContentAjaxCall( items, counter, $button ) {
		var item = items[ counter ],
		slug = item.name;
		
		$.ajax({
			method:      'POST',
			url:         library.ajax_url,
			data:        {
				action: 'library_import_created_content',
				security: library.ajax_nonce,
				slug: slug,
			},
			beforeSend:  function() {
				var $currentItem = $( '.content-item-' + slug );
				$currentItem.find( '.js-library-content-item-info' ).empty();
				$currentItem.find( '.js-library-content-item-error' ).empty();
				$currentItem.addClass( 'content-item--loading' );
			}
		})
		.done( function( response ) {
			if ( response.data && response.data.refresh ) {
				createDemoContentAjaxCall( items, counter, $button );
				return;
			}
			
			var $currentItem = $( '.content-item-' + slug );
			
			$currentItem.removeClass( 'content-item--loading' );
			
			if ( response.success ) {
				$currentItem.find( '.js-library-content-item-info' ).append( '<p>' + library.texts.successful_import + '</p>' );
			} else {
				$currentItem.find( '.js-library-content-item-error' ).append( '<p>' + response.data + '</p>' );
			}
		})
		.fail( function( error ) {
			var $currentItem = $( '.content-item-' + slug );
			$currentItem.removeClass( 'content-item--loading' );
			$currentItem.find( '.js-library-content-item-error' ).append( '<p>' + error.statusText + ' (' + error.status + ')</p>' );
		})
		.always( function( response ) {
			if ( response.data && response.data.refresh ) {
				return;
			}
			
			counter++;
			
			if ( counter === items.length ) {
				$button.removeClass( 'library-button-disabled' );
			} else {
				createDemoContentAjaxCall( items, counter, $button );
			}
		} );
	}
	
	
	function getUrlParameter( param ) {
		var sPageURL = window.location.search.substring( 1 ),
		sURLVariables = sPageURL.split( '&' ),
		sParameterName,
		i;
		
		for ( i = 0; i < sURLVariables.length; i++ ) {
			sParameterName = sURLVariables[ i ].split( '=' );
			
			if ( sParameterName[0] === param ) {
				return typeof sParameterName[1] === undefined ? true : decodeURIComponent( sParameterName[1] );
			}
		}
		
		return false;
	}
	
	
	function startImport( selected ) {
		var data = new FormData();
		data.append( 'action', 'library_import_demo_data' );
		data.append( 'security', library.ajax_nonce );
		
		if ( selected ) {
			data.append( 'selected', selected );
		}
		
		ajaxCall( data );
	}


	/* ==================================================
	Page Builder
	================================================== */

	$('.borderless-library__template.wpbakery .borderless-library__template-body').prepend('<svg class="borderless-library__template-body-page-builder" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16 0a16 16 0 1 0 0 32 16 16 0 1 0 0-32zm6.25 20.84l-1.17 4.19c-3.33-1.98-7.41-.59-7.71-.54-.83.21-2.17-1.96-1.78-2.1 2.27-.32 3.88-1.64 4-1.78C7.49 24.36 4 17.42 4 14.24c0-.38.25-7.27 7.97-7.27 5.52-.01 4.29 5.4 10.86 4.44-1-.31-2.29.1-3.08-.83C28.06 8.86 28 15.34 28 15.57c.05 5.03-5.75 5.27-5.75 5.27z" fill="#0373aa"/></svg>');

	$('.borderless-library__template.elementor .borderless-library__template-body').prepend('<svg class="borderless-library__template-body-page-builder" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32"><defs><path id="A" d="M0 0h32v32H0z"/></defs><clipPath id="B"><use xlink:href="#A"/></clipPath><g clip-path="url(#B)"><path d="M16 0A16 16 0 0 0 0 16c0 8.83 7.16 16 16 16a16 16 0 0 0 16-16c0-8.84-7.17-16-16-16zm-4 22.66H9.34V9.33H12v13.33zm10.66 0h-8V20h8v2.66zm0-5.33h-8v-2.67h8v2.67zm0-5.33h-8V9.33h8V12z" fill="#92003b"/></g></svg>');
	
	
	/* ==================================================
	Filter
	================================================== */
	
	$(window).load(function(){ 
		
		var buttonFilters = {};
		var buttonFilter;
		var qsRegex;
		
		// Isotope / Masonry    
		var $grid = $('.borderless-library .borderless-library__templates').isotope({
			itemSelector: '.borderless-library__template',
			layoutMode: 'masonry',
			
			getSortData: {
				category: '[data-category]'
			},
			
			filter: function() {
				var $this = $(this);
				var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
				var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
				return searchResult && buttonResult;
			},
		});
		
		$grid.imagesLoaded().progress(function() {
			setTimeout(function(){
				$grid.isotope('layout');
			},200);
		});
		
		$('.borderless-library__filters').on( 'click', '.borderless-library__collapse-nav-link', function() {
			var $this = $(this);
			// get group key
			var $buttonGroup = $this.parents('.borderless-library__collapse-nav');
			var filterGroup = $buttonGroup.attr('data-filter-group');
			// set filter for group
			buttonFilters[ filterGroup ] = $this.attr('data-filter');
			// combine filters
			buttonFilter = concatValues( buttonFilters );
			// Isotope arrange
			$grid.isotope();
		});
		
		
		// use value of search field to filter
		var $quicksearch = $('.borderless-library__live-search-input').keyup( debounce( function() {
			qsRegex = new RegExp( $quicksearch.val(), 'gi' );
			$grid.isotope();
		}) );
		
		// change active class on buttons
		$('.borderless-library__collapse-nav').each( function( i, buttonGroup ) {
			var $buttonGroup = $( buttonGroup );
			$buttonGroup.on( 'click', '.borderless-library__collapse-nav-link', function() {
				$buttonGroup.find('.active').removeClass('active');
				$( this ).addClass('active');
			});
		});
		
		// flatten object by concatting values
		function concatValues( obj ) {
			var value = '';
			for ( var prop in obj ) {
				value += obj[ prop ];
			}
			return value;
		}
		
		// debounce so filtering doesn't happen every millisecond
		function debounce( fn, threshold ) {
			var timeout;
			threshold = threshold || 100;
			return function debounced() {
				clearTimeout( timeout );
				var args = arguments;
				var _this = this;
				function delayed() {
					fn.apply( _this, args );
				}
				timeout = setTimeout( delayed, threshold );
			};
		}
		
	});
	
} );
