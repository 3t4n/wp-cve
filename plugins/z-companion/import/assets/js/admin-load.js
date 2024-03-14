var ZCompaionSitesAjaxQueue = (function() {

	var requests = [];

	return {

		/**
		 * Add AJAX request
		 *
		 * @since 1.0.0
		 */
		add:  function(opt) {
		    requests.push(opt);
		},

		/**
		 * Remove AJAX request
		 *
		 * @since 1.0.0
		 */
		remove:  function(opt) {
		    if( jQuery.inArray(opt, requests) > -1 )
		        requests.splice($.inArray(opt, requests), 1);
		},

		/**
		 * Run / Process AJAX request
		 *
		 * @since 1.0.0
		 */
		run: function() {
		    var self = this,
		        oriSuc;

		    if( requests.length ) {
		        oriSuc = requests[0].complete;

		        requests[0].complete = function() {
		             if( typeof(oriSuc) === 'function' ) oriSuc();
		             requests.shift();
		             self.run.apply(self, []);
		        };

		        jQuery.ajax(requests[0]);

		    } else {

		      self.tid = setTimeout(function() {
		         self.run.apply(self, []);
		      }, 1000);
		    }
		},

		/**
		 * Stop AJAX request
		 *
		 * @since 1.0.0
		 */
		stop:  function() {

		    requests = [];
		    clearTimeout(this.tid);
		}
	};

}());


(function($){

var ZCompanionSSEImport = {
		complete: {
			posts: 0,
			media: 0,
			users: 0,
			comments: 0,
			terms: 0,
		},

		updateDelta: function (type, delta) {
			this.complete[ type ] += delta;

			var self = this;
			requestAnimationFrame(function () {
				self.render();
			});
		},
		updateProgress: function ( type, complete, total ) {
			var text = complete + '/' + total;

			if( 'undefined' !== type && 'undefined' !== text ) {
				total = parseInt( total, 10 );
				if ( 0 === total || isNaN( total ) ) {
					total = 1;
				}
				var percent = parseInt( complete, 10 ) / total;
				var progress     = Math.round( percent * 100 ) + '%';
				var progress_bar = percent * 100;
			}
		},
		render: function () {
			var types = Object.keys( this.complete );
			var complete = 0;
			var total = 0;

			for (var i = types.length - 1; i >= 0; i--) {
				var type = types[i];
				this.updateProgress( type, this.complete[ type ], this.data.count[ type ] );

				complete += this.complete[ type ];
				total += this.data.count[ type ];
			}

			this.updateProgress( 'total', complete, total );
		}
	};



	ZCompanionTemplateAdmin = {

		log_file        : '',
		customizer_data : '',
		xml_url         : '',
		options_data    : '',
		widgets_data    : '',


		init: function(){
			this._getJsonData();
			this._bind();
		},

		_getJsonData: function(cate ='all',builder ='zitashop'){
			$('.spinner-wrap').addClass('loading').html('<span class="spinner is-active"></span>');
				var data = {
						'action': 'z_companion_sites_json',
						'cate' : cate,
						'builder' : builder
					};
			 	$.ajax({
			 		dataType: "json",
			        url: zCompanionAdmin.ajax_url,
			        type: 'POST',
			        data: data,
			 
			        success: function( success ) {
			        	ZCompanionTemplateAdmin._filterJson(success);
						jQuery('#z-companion-sites').show();

			        },
			        error: function( error ) {
			            console.log( error );
			        }
			    });
		},

		_filterJson: function(tmplData){
			ZCompanionTemplateAdmin._addCategory(tmplData.cate);
			$('.select-page-builder').hide();
			 	//var templateData =  $.parseJSON(success);
				var template = wp.template( 'z-companion' );

				$("#z-companion-sites").html( template( tmplData ) );
					ZCompanionTemplateAdmin._demoClick();
				jQuery('.spinner-wrap').removeClass('loading').html('');

		},
		// show demo category
		_addCategory:function(cate){
			var cateList = '<ul class="filter-links zita-category">';
			$.each(cate, function(k, v) {
				cateList += '<li><a href="#" data-cate="'+k+'">'+v+'</a></li>';
				});
			cateList += '</ul>';
		$("#z-companion-sites-category").html(cateList);
		},

		// show category demo
	  _builderDemo:function(event){
	  			jQuery('#z-companion-sites').hide();
	  			event.preventDefault();
	  			var builderName = $(this).data('value');
				var $cate_slug 	= jQuery( event.target );
				ZCompanionTemplateAdmin._getJsonData($cate_slug.data('cate'),builderName);
		},

		// show category demo
	  	_categoryDemo:function(event){
	  			jQuery('#z-companion-sites').hide();
	  			event.preventDefault();
	  			var builderName = $('.zsl-demo-type ul li.cs-selected').data('value');
				var $cate_slug 	= jQuery( event.target );
				ZCompanionTemplateAdmin._getJsonData($cate_slug.data('cate'),builderName);
		},

		_overlayclose: function(){
		$('.wp-full-overlay').hide();
		},
	/**
		 * Remove plugin from the queue.
		 */
		_removePluginFromQueue: function( removeItem, pluginsList ) {
			 return $.grep(pluginsList, function( value ) {
			 	return value.slug != removeItem;
			 });
		},

	_checkPlugins: function(requiredPlugins,demo_type=''){
		if( $.isArray( requiredPlugins ) ) {

			var $pluginsFilter    = jQuery( '#plugin-filter' ),
				data 			= {
										action           : 'z-companion-plugins-check',
										zc_ajax_nonce      : zCompanionAdmin.z_companion_zc_ajax_nonce,
										required_plugins : requiredPlugins
									};
			 	$.ajax({
			        url: 	zCompanionAdmin.ajax_url,
			        type: 	'POST',
			        data:	data,
			        fail:function( jqXHR ){

					// Remove loader.
					jQuery('.required-plugins').removeClass('loading').html('');

					ZCompanionTemplateAdmin._importFailMessage( jqXHR.status + ' ' + jqXHR.responseText, 'plugins' );
					},
			        success: function( success ) {
							console.log(success);	

							// Release disabled class from import button.
					$('.zita-demo-import')
						.removeClass('disabled not-click-able')
						.attr('data-import', 'disabled');
					// Remove loader.
					jQuery('.required-plugins').removeClass('loading').html('');


						/**
					 * Count remaining plugins.
					 * @type number
					 */
					var remaining_plugins = 0;


					/**
					 * Not Installed
					 *
					 * List of not installed required plugins.
					 */
					if ( typeof success.data.notinstalled !== 'undefined' ) {

						// Add not have installed plugins count.
						remaining_plugins += parseInt( success.data.notinstalled.length );

						jQuery( success.data.notinstalled ).each(function( index, plugin ) {

							var output  = '<div class="plugin-card ';
								output += ' 		plugin-card-'+plugin.slug+'"';
								output += ' 		data-slug="'+plugin.slug+'"';
								output += ' 		data-init="'+plugin.init+'">';
								output += '	<span class="title">'+plugin.name+'</span>';
								output += '	<button class="button install-now"';
								output += '			data-init="' + plugin.init + '"';
								output += '			data-slug="' + plugin.slug + '"';
								output += '			data-name="' + plugin.name + '">';
								output += 	'Install Now'; //wp.updates.l10n.installNow
								output += '	</button>';
								 output += '	<span class="dashicons-no dashicons"></span>';
								output += '</div>';

							jQuery('.required-plugins').append(output);

						});
					}


					/**
					 * Inactive
					 *
					 * List of not inactive required plugins.
					 */
					if ( typeof success.data.inactive !== 'undefined' ) {

						// Add inactive plugins count.
						remaining_plugins += parseInt( success.data.inactive.length );

						jQuery( success.data.inactive ).each(function( index, plugin ) {
							var output  = '<div class="plugin-card ';
								output += ' 		plugin-card-'+plugin.slug+'"';
								output += ' 		data-slug="'+plugin.slug+'"';
								output += ' 		data-init="'+plugin.init+'">';
								output += '	<span class="title">'+plugin.name+'</span>';
								output += '	<button class="button activate-now button-primary"';
								output += '		data-init="' + plugin.init + '"';
								output += '		data-slug="' + plugin.slug + '"';
								output += '		data-name="' + plugin.name + '">';
								output += 	'Activate'; //wp.updates.l10n.activatePlugin;
								output += '	</button>';
								// output += '	<span class="dashicons-no dashicons"></span>';
								output += '</div>';
							jQuery('.required-plugins').append(output);
						});
					}


					/**
					 * Active
					 *
					 * List of not active required plugins.
					 */
					if ( typeof success.data.active !== 'undefined' ) {

						jQuery( success.data.active ).each(function( index, plugin ) {

							var output  = '<div class="plugin-card ';
								output += ' 		plugin-card-'+plugin.slug+'"';
								output += ' 		data-slug="'+plugin.slug+'"';
								output += ' 		data-init="'+plugin.init+'">';
								output += '	<span class="title">'+plugin.name+'</span>';
								output += '	<button class="button disabled"';
								output += '			data-slug="' + plugin.slug + '"';
								output += '			data-name="' + plugin.name + '">';
								output += zCompanionAdmin.unique.pluginActive;
								output += '	</button>';
								// output += '	<span class="dashicons-yes dashicons"></span>';
								output += '</div>';

							jQuery('.required-plugins').append(output);
						});
					}
					/**
					 * Enable Demo Import Button
					 * @type number
					 */
					zCompanionAdmin.requiredPlugins = success.data;
					ZCompanionTemplateAdmin._enable_demo_import_button(demo_type);
	
			        },
			        error: function( error ) {
			            console.log( error );
			        }
			    });
		} else{
				// Enable Demo Import Button
				ZCompanionTemplateAdmin._enable_demo_import_button( demo_type );
				jQuery('.required-plugins-wrap').remove();
			 }
	},
		/**
		 * Enable Demo Import Button.
		 */
		_enable_demo_import_button: function( type ) {
			type = ( undefined !== type ) ? type : 'free';
			switch( type ) {

				case 'free':
							var all_buttons      = parseInt( jQuery( '.plugin-card .button' ).length ) || 0,
								disabled_buttons = parseInt( jQuery( '.plugin-card .button.disabled' ).length ) || 0;

							if( all_buttons === disabled_buttons ) {

								jQuery('.zita-demo-import')
									.removeAttr('data-import')
									.removeClass('installing updating-message')
									.addClass('button-primary')
									.text( zCompanionAdmin.unique.importDemo );
							}

					break;

				case 'upgrade':
						var all_buttons = parseInt( jQuery( '.plugin-card .button' ).length ) || 0,
							disabled_buttons = parseInt( jQuery( '.plugin-card .button.disabled' ).length ) || 0;
							if( all_buttons === disabled_buttons ) {
								jQuery('.zita-demo-import')
									.removeAttr('data-import')
									.removeClass('installing updating-message')
									.addClass('button-primary')
									.text( zCompanionAdmin.unique.importDemo );
							}
							// var demo_slug = jQuery('.wp-full-overlay-header').attr('data-demo-slug');
							// jQuery('.zita-demo-import')
							// 		.addClass('go-pro button-primary')
							// 		.removeClass('zita-demo-import')
							// 		.attr('target', '_blank')
							// 		.attr('href', zCompanionAdmin.getUpgradeURL + demo_slug )
							// 		.text( zCompanionAdmin.getUpgradeText )
							// 		.append('<i class="dashicons dashicons-external"></i>');
					break;

				default:
							var demo_slug = jQuery('.wp-full-overlay-header').attr('data-demo-slug');

							jQuery('.zita-demo-import')
									.addClass('go-pro button-primary')
									.removeClass('zita-demo-import')
									.attr('target', '_blank')
									.attr('href', zCompanionAdmin.getProURL )
									.text( zCompanionAdmin.getProText )
									.append('<i class="dashicons dashicons-external"></i>');
					break;
			}

		},


		/**
		 * Install Now
		 */
		_installPluginsStart: function(event)
		{
			event.preventDefault();
			var $button 	= jQuery( event.target ),
				$document   = jQuery(document);

			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
				wp.updates.requestFilesystemCredentials( event );

				$document.on( 'credential-modal-cancel', function() {
					var $message = $( '.install-now.updating-message' );

					$message.removeClass( 'updating-message' ).text( "Install Now" ); //wp.updates.l10n.installNow


					wp.a11y.speak( "Update canceled", 'polite' ); //wp.updates.l10n.updateCancel
				} );
			}
			wp.updates.installPlugin( {
				slug:    $button.data( 'slug' )
			} );

		},
/**
		 * Plugin Installation Error.
		 */
		_installError: function( event, response ) {

			var $card = jQuery( '.plugin-card-' + response.slug );
			$card
				.removeClass( 'button-primary' )
				.addClass( 'disabled' )
				.html( "Installation Failed!" );
				//wp.updates.l10n.installFailedShort
					//console.log(response.errorMessage);
					ZCompanionTemplateAdmin._importFailMessage( response.errorMessage );
		},

		/**
		 * Installing Plugin
		 */
		_pluginInstalling: function(event, args) {
			event.preventDefault();
			var $card = jQuery( '.plugin-card-' + args.slug );
			var $button = $card.find( '.button' );
			$card.addClass('updating-message');
			$button.addClass('already-started');
		},

		/**
		 * Install Success
		 */
		_installSuccess: function( event, response ) {
			console.log(response);

			event.preventDefault();
			var $message     = jQuery( '.plugin-card-' + response.slug ).find( '.button' );
			// Transform the 'Install' button into an 'Activate' button.

			var $init = $message.data('init');
			$message.removeClass( 'install-now installed button-disabled updated-message' )
				.addClass('updating-message')
				.html(zCompanionAdmin.unique.pluginActivating);

			// Reset not installed plugins list.
				var pluginsList = zCompanionAdmin.requiredPlugins.notinstalled;

			zCompanionAdmin.requiredPlugins.notinstalled = ZCompanionTemplateAdmin._removePluginFromQueue( response.slug, pluginsList );

			// WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
			setTimeout( function() {

				$.ajax({
					url: zCompanionAdmin.ajax_url,
					type: 'POST',
					data: {
						'action'            : 'zita-plugins-active',
						'init'              : $init,
					},
				}).success(function (result) {
					console.log(result.success);

					 if( result.success ) {

					var pluginsList = zCompanionAdmin.requiredPlugins.inactive;

					 	// Reset not installed plugins list.
					 zCompanionAdmin.requiredPlugins.inactive = ZCompanionTemplateAdmin._removePluginFromQueue( response.slug, pluginsList );

					$message.removeClass( 'button-primary install-now activate-now updating-message' )
							.attr('disabled', 'disabled')
							.addClass('disabled')
							.text( zCompanionAdmin.unique.pluginActive );

						//Enable Demo Import Button
						ZCompanionTemplateAdmin._enable_demo_import_button();

					 } else {

					 	$message.removeClass( 'updating-message' );

					 }

				});

			}, 1200 );

		},

		/**
		 * Bulk Plugin Active & Install
		 */
		_bulkPluginInstallActivate: function()
		{
			if( 0 === zCompanionAdmin.requiredPlugins.length ) {
				return;
			}

			jQuery('.required-plugins')
				.find('.install-now')
				.addClass( 'updating-message' )
				.removeClass( 'install-now' )
				.text( "Installing..." );
				//wp.updates.l10n.installing

			jQuery('.required-plugins')
				.find('.activate-now')
				.addClass('updating-message')
				.removeClass( 'activate-now' )
				.html( zCompanionAdmin.unique.pluginActivating );

			var not_installed 	 = zCompanionAdmin.requiredPlugins.notinstalled || '';
			var activate_plugins = zCompanionAdmin.requiredPlugins.inactive || '';

			// First Install Bulk.
			if( not_installed.length > 0 ) {
				ZCompanionTemplateAdmin._allPluginsInstall( not_installed );
			}

			// Second Activate Bulk.
			if( activate_plugins.length > 0 ) {
				ZCompanionTemplateAdmin._allPluginsActivate( activate_plugins );
			}

		},


		/**
		 * Activate All Plugins.
		 */
		_allPluginsActivate: function( activate_plugins ) {

			// Activate ALl Plugins.
			ZCompaionSitesAjaxQueue.stop();
			ZCompaionSitesAjaxQueue.run();


			$.each( activate_plugins, function(index, single_plugin) {

				var $card    	 = jQuery( '.plugin-card-' + single_plugin.slug ),
					$button  	 = $card.find('.button');
			
					$button.addClass('updating-message');

				ZCompaionSitesAjaxQueue.add({
					url: zCompanionAdmin.ajax_url,
					type: 'POST',
					data: {
						'action'            : 'zita-plugins-active',
						'init'              : single_plugin.init,
					},
					success: function( result ){

						if( result.success ) {
							var $card = jQuery( '.plugin-card-' + single_plugin.slug );
							var $button = $card.find( '.button' );
							if( ! $button.hasClass('already-started') ) {
								var pluginsList = zCompanionAdmin.requiredPlugins.inactive;

								// Reset not installed plugins list.
								zCompanionAdmin.requiredPlugins.inactive = ZCompanionTemplateAdmin._removePluginFromQueue( single_plugin.slug, pluginsList );

							}
							$button.removeClass( 'button-primary install-now activate-now updating-message' )
								.attr('disabled', 'disabled')
								.addClass('disabled')
								.text( zCompanionAdmin.unique.pluginActive );
							// Enable Demo Import Button
							ZCompanionTemplateAdmin._enable_demo_import_button();
						} else {
						}
					}
				});
			});
		},

		/**
		 * Install All Plugins.
		 */
		_allPluginsInstall: function( not_installed ) {
			
			$.each( not_installed, function(index, single_plugin) {

				var $card   = jQuery( '.plugin-card-' + single_plugin.slug ),
					$button = $card.find('.button');

				if( ! $button.hasClass('already-started') ) {

					// Add each plugin activate request in Ajax queue.
					// @see wp-admin/js/updates.js
					wp.updates.queue.push( {
						action: 'install-plugin', // Required action.
						data:   {
							slug: single_plugin.slug
						}
					} );
				}
			});

			// Required to set queue.
			wp.updates.queueChecker();
		},


		/**
		 * Render Demo Preview
		 */
		_activateNow: function( eventn ) {

			event.preventDefault();

			var $button = jQuery( event.target ),
				$init 	= $button.data( 'init' ),
				$slug 	= $button.data( 'slug' );

			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}

			$button.addClass('updating-message button-primary')
				.html( zCompanionAdmin.unique.btnActivating );
			$.ajax({
				url: zCompanionAdmin.ajax_url,
				type: 'POST',
				data: {
					'action'            : 'zita-plugins-active',
					'init'              : $init,
				},
			})
			.done(function (result) {

				if( result.success ) {

					var pluginsList = zCompanionAdmin.requiredPlugins.inactive;


					// Reset not installed plugins list.
					zCompanionAdmin.requiredPlugins.inactive = ZCompanionTemplateAdmin._removePluginFromQueue( $slug, pluginsList );

					$button.removeClass( 'button-primary install-now activate-now updating-message' )
						.attr('disabled', 'disabled')
						.addClass('disabled')
						.text( zCompanionAdmin.unique.pluginActive );

					// Enable Demo Import Button
					ZCompanionTemplateAdmin._enable_demo_import_button();

				}

			})
			.fail(function () {
			});

		},

	_demosite: function(gthis){
				$('.required-plugins').addClass('loading').html('<span class="spinner is-active"></span>');

			var getdata = $(gthis);
			 	getdata.addClass('theme-preview-on');
				var plugin = getdata.attr('plugins');
				var plugins =  '['+plugin.substring(0,plugin.length - 1)+']';
				var demo_type   =  getdata.attr('demo_type');

			 	var themeData = {
				// 	id                       : '23',
				 	demo_name                : getdata.attr('zita_template'),
				 	demo_url                 : getdata.attr('zita_demo'),
				 	demo_type                : demo_type,
				 	demo_api                 : getdata.attr('api'),
				 	screenshot               : getdata.attr('thumb'),
				 	slug                     : getdata.attr('slug'),
				 	zita_import              : getdata.attr('zita_import'),
				 	required_plugins         : plugins,
				 };
				 var plugins =  JSON.parse(plugins);
				 if(plugin==''){
				 	plugins = '';
				 }

    			var template = wp.template( 'z-companion-demo-template' );
			$(".z-companion-sites-theme-preview").html( template( themeData ) );
			ZCompanionTemplateAdmin._checkPlugins(plugins,demo_type);
			ZCompanionTemplateAdmin._checkNextPrev();

	},

		_demoClick: function(){
			 $(".zitademo").click(function(){
			 ZCompanionTemplateAdmin._demosite(this);
				});

		},

		/**
		 * Collapse Sidebar.
		 */
		_collapse: function() {
			event.preventDefault();

			overlay = jQuery('.wp-full-overlay');

			if (overlay.hasClass('expanded')) {
				overlay.removeClass('expanded');
				overlay.addClass('collapsed');
				return;
			}

			if (overlay.hasClass('collapsed')) {
				overlay.removeClass('collapsed');
				overlay.addClass('expanded');
				return;
			}
		},

		/**
		 * Next Theme.
		 */
		_nextSite: function (event) {
			event.preventDefault();
			currentDemo = jQuery('.theme-preview-on')
			currentDemo.removeClass('theme-preview-on');
			nextDemo = currentDemo.next('.themes');
			nextDemo.addClass('theme-preview-on');
			ZCompanionTemplateAdmin._demosite('.theme-preview-on');
		},
		/**
		 * Previous Theme.
		 */
		_prevSite: function (event) {
			event.preventDefault();
			currentDemo = jQuery('.theme-preview-on')
			currentDemo.removeClass('theme-preview-on');
			nextDemo = currentDemo.prev('.themes');
			nextDemo.addClass('theme-preview-on');
			ZCompanionTemplateAdmin._demosite('.theme-preview-on');
		},
		/**
		 * Check Next & Previous Buttons.
		 */
		_checkNextPrev: function() {
			currentDemo = jQuery('.theme-preview-on');
			nextDemo = currentDemo.nextAll('.themes').length;
			prevDemo = currentDemo.prevAll('.themes').length;
			if (nextDemo == 0) {
				jQuery('.next-theme').addClass('disabled');
			} else if (nextDemo != 0) {
				jQuery('.next-theme').removeClass('disabled');
			}

			if (prevDemo == 0) {
				jQuery('.previous-theme').addClass('disabled');
			} else if (prevDemo != 0) {
				jQuery('.previous-theme').removeClass('disabled');
			}

			return;
		},

		/**
		 * Fires when a nav item is clicked.
		 *
		 * @since 1.0
		 * @access private
		 * @method _importDemo
		 */
		_importDemo: function()
		{
			var $this 	= jQuery(this),
				$theme  = $this.closest('.zita-sites-preview').find('.wp-full-overlay-header'),
				apiURL  = $theme.data('demo-api') || '',
				plugins = $theme.data('required-plugins');

			var disabled = $this.attr('data-import');

			if ( typeof disabled !== 'undefined' && disabled === 'disabled' || $this.hasClass('disabled') ) {

				$('.zita-demo-import').addClass('updating-message installing')
					.text( "Installing..." );
					//wp.updates.l10n.installing

				/**
				 * Process Bulk Plugin Install & Activate
				 */
				ZCompanionTemplateAdmin._bulkPluginInstallActivate();

				return;
			}

			// Proceed?
			if( ! confirm( zCompanionAdmin.unique.importWarning ) ) {
				return;
			}
			$('body').addClass('importing-site');
			//$('.previous-theme, .next-theme').addClass('disabled');			// Remove all notices before import start.
			
			$('.install-theme-info > .notice').remove();

			$('.zita-demo-import').attr('data-import', 'disabled')
				.addClass('updating-message installing')
				.text( zCompanionAdmin.unique.importingDemo );

			$this.closest('.theme').focus();

			var $theme = $this.closest('.zita-sites-preview').find('.wp-full-overlay-header');

			var apiURL = $theme.data('demo-api') || '';

			// Site Import by API URL.
			if( apiURL ) {
				ZCompanionTemplateAdmin._importSite( apiURL );
			}

		},


		/**
		 * Start Import Process by API URL.
		 * 
		 * @param  {string} apiURL Site API URL.
		 */
		_importSite: function( apiURL ) {

			$('.button-hero.zita-demo-import').text( zCompanionAdmin.unique.gettingData );

			// 1. Request Site Import
			$.ajax({
				url  : zCompanionAdmin.ajax_url,
				type : 'POST',
			//	dataType: 'json',
				data : {
					'action'  : 'zita-import-demo-data',
					'api_url' : apiURL,
				},
			})
			.fail(function( jqXHR ){

				ZCompanionTemplateAdmin._importFailMessage( jqXHR.status + ' ' + jqXHR.responseText );
		    })
			.done(function ( demo_data ) {

				//1. Fail - Request Site Import
				if( false === demo_data.success ) {

					ZCompanionTemplateAdmin._importFailMessage( demo_data.data );

				} else { 

					ZCompanionTemplateAdmin.customizer_data = JSON.stringify( demo_data.data['zita-customizer-data'] ) || '';
					ZCompanionTemplateAdmin.xml_url         = encodeURI( demo_data.data['zita-xml-path'] ) || '';
					ZCompanionTemplateAdmin.widgets_data    = JSON.stringify( demo_data.data['zita-widgets-data'] ) || '';
					ZCompanionTemplateAdmin.options_data    = JSON.stringify( demo_data.data['zita-option-data'] ) || '';
			 		$(document).trigger( 'zita-sites-import-set-site-data-done' )
				}
		 });
		},
		
		

		_bind: function()
		{				
			$( document ).on('click'	, '.zsl-demo-type ul li', ZCompanionTemplateAdmin._builderDemo);
			$( document ).on('click'	, '.zita-category li a', ZCompanionTemplateAdmin._categoryDemo);
			$( document ).on('click'	, '.devices button', ZCompanionTemplateAdmin._previewDevice);
			$( document ).on('click'	, '.close-full-overlay', ZCompanionTemplateAdmin._overlayclose);
			$( document ).on('click'	, '.next-theme', ZCompanionTemplateAdmin._nextSite);
			$( document ).on('click'	, '.previous-theme', ZCompanionTemplateAdmin._prevSite);
			$( document ).on('click'    , '.collapse-sidebar', ZCompanionTemplateAdmin._collapse);
			$( document ).on('click'    , '.zita-demo-import', ZCompanionTemplateAdmin._importDemo);
			$( document ).on('click'    , '.install-now', ZCompanionTemplateAdmin._installPluginsStart);
			$( document ).on('click'    , '.activate-now', ZCompanionTemplateAdmin._activateNow);
			$( document ).on('wp-plugin-install-error'   , ZCompanionTemplateAdmin._installError);
			$( document ).on('wp-plugin-installing'      , ZCompanionTemplateAdmin._pluginInstalling);
			$( document ).on('wp-plugin-install-success' , ZCompanionTemplateAdmin._installSuccess);
			$( document ).on('zita-sites-import-set-site-data-done' , ZCompanionTemplateAdmin._importPrepareXML );
			$( document ).on('zita-sites-import-xml-success'       , ZCompanionTemplateAdmin._importCustomizerSettings );
			$( document ).on('zita-import-customizer-settings-success'                 , ZCompanionTemplateAdmin._importOptions );
			$( document ).on('zita-import-option-data-success'             , ZCompanionTemplateAdmin._importWidgets );
			$( document ).on('zita-sites-import-widgets-success'             , ZCompanionTemplateAdmin._importEnd );
		},


		/**
		 * Preview Device
		 */
		_previewDevice: function( event ) {
			var device = $( event.currentTarget ).data( 'device' );

			$('.theme-install-overlay')
				.removeClass( 'preview-desktop preview-tablet preview-mobile' )
				.addClass( 'preview-' + device )
				.data( 'current-preview-device', device );

			ZCompanionTemplateAdmin._tooglePreviewDeviceButtons( device );
		},


		/**
		 * Import Error Button.
		 * 
		 * @param  {string} data Error message.
		 */
		_importFailMessage: function( message, from ) {

			$('.zita-demo-import')
				.addClass('go-pro button-primary')
				.removeClass('updating-message installing')
				.removeAttr('data-import')
				.attr('target', '_blank')
				.append('<i class="dashicons dashicons-external"></i>')
				.removeClass('zita-demo-import');

			// Add the doc link due to import log file not generated.
			if( 'undefined' === from ) {

				$('.wp-full-overlay-header .go-pro').text( zCompanionAdmin.unique.importFailedBtnSmall );
				$('.wp-full-overlay-footer .go-pro').text( zCompanionAdmin.unique.importFailedBtnLarge );
				$('.go-pro').attr('href',  zCompanionAdmin.unique.serverConfiguration );

			// Add the import log file link.
			} else {
				
				$('.wp-full-overlay-header .go-pro').text( zCompanionAdmin.unique.importFailBtn );
				$('.wp-full-overlay-footer .go-pro').text( zCompanionAdmin.unique.importFailBtnLarge )
				
			}

			var output  = '<div class="zita-api-error notice notice-error notice-alt is-dismissible">';
				output += '	<p>'+message+'</p>';
				output += '	<button type="button" class="notice-dismiss">';
				output += '		<span class="screen-reader-text">'+commonL10n.dismiss+'</span>';
				output += '	</button>';
				output += '</div>';

			// Fail Notice.
			$('.install-theme-info').append( output );
			

			// !important to add trigger.
			// Which reinitialize the dismiss error message events.
			$(document).trigger('wp-updates-notice-added');
		},


		/**
		 * Toggle Preview Buttons
		 */
		_tooglePreviewDeviceButtons: function( newDevice ) {
			var $devices = $( '.wp-full-overlay-footer .devices' );

			$devices.find( 'button' )
				.removeClass( 'active' )
				.attr( 'aria-pressed', false );

			$devices.find( 'button.preview-' + newDevice )
				.addClass( 'active' )
				.attr( 'aria-pressed', true );
		},

	/**
		 * Import Success Button.
		 * 
		 * @param  {string} data Error message.
		 */
		_importSuccessMessage: function() {

			$('.zita-demo-import').removeClass('updating-message installing')
				.removeAttr('data-import')
				.addClass('view-site')
				.removeClass('zita-demo-import')
				.text( zCompanionAdmin.unique.viewSite )
				.attr('target', '_blank')
				.append('<i class="dashicons dashicons-external"></i>')
				.attr('href', zCompanionAdmin.siteURL );
		},

		/**
		 * 5. Import Complete.
		 */
		_importEnd: function( event ) {

			$.ajax({
				url  : zCompanionAdmin.ajax_url,
				type : 'POST',
			//	dataType: 'json',
				data : {
					action : 'zita-site-library-import-close',
				},
				beforeSend: function() {
				$('.button-hero.zita-demo-import').text( zCompanionAdmin.unique.importComplete );
				}
			})
			.fail(function( jqXHR ){
				ZCompanionTemplateAdmin._importFailMessage( jqXHR.status + ' ' + jqXHR.responseText );
		    })
			.done(function ( data ) {

				// 5. Fail - Import Complete.
				if( false === data.success ) {
				ZCompanionTemplateAdmin._importFailMessage( data.data );
				} else {
								console.log('complete');
					$('body').removeClass('importing-site');
					//$('.previous-theme, .next-theme').removeClass('disabled');

					// 5. Pass - Import Complete.
					ZCompanionTemplateAdmin._importSuccessMessage();
				}
			});
		},

		

		/**
		 * 1. Customizer Srttings.
		 */
		_importCustomizerSettings: function( event ) {
		$.ajax({
				url  :  zCompanionAdmin.ajax_url,
				type : 'POST',
				//dataType: 'json',
				data : {
					action          : 'zita-site-library-import-customizer',
					customizer_data : ZCompanionTemplateAdmin.customizer_data,
				},
				beforeSend: function() {
			$('.button-hero.zita-demo-import').text( zCompanionAdmin.unique.importCustomizer );
				},
			})
			.fail(function( jqXHR ){
				ZCompanionTemplateAdmin._importFailMessage( jqXHR.status + ' ' + jqXHR.responseText );
		    })
			.done(function ( customizer_data ) {
				// 1. Fail - Import Customizer Options.
				if( false === customizer_data.success ) {
					ZCompanionTemplateAdmin._importFailMessage( customizer_data.data );
				} else {

					// 1. Pass - Import Customizer Options.
					$(document).trigger( 'zita-import-customizer-settings-success' );
				}
			});
		},
		/**
		 * 2. Prepare XML Data.
		 */
		_importPrepareXML: function( event ) {
			$.ajax({url  : zCompanionAdmin.ajax_url,
				
				type : 'POST',
			//	dataType: 'json',
				data : {
					'action'  : 'zita-import-xml',
					'xml_url' : ZCompanionTemplateAdmin.xml_url,
				},
			beforeSend: function() {
					$('.button-hero.zita-demo-import').text( zCompanionAdmin.unique.importXMLPreparing );
				},
			})
			.fail(function( jqXHR ){

				ZCompanionTemplateAdmin._importFailMessage( jqXHR.status + ' ' + jqXHR.responseText );
		    })
			.done(function ( xml_data ) {

				console.log(xml_data);


				// 2. Fail - Prepare XML Data.
				if( false === xml_data.success ) {
					 ZCompanionTemplateAdmin._importFailMessage( xml_data.data );

				} else {
					console.log('complete page & post');

					// 2. Pass - Prepare XML Data.

					// Import XML though Event Source.
					ZCompanionSSEImport.data = xml_data.data;
					ZCompanionSSEImport.render();

					$('.button-hero.zita-demo-import').text( zCompanionAdmin.unique.importingXML );
					
					var evtSource = new EventSource( ZCompanionSSEImport.data.url );
					evtSource.onmessage = function ( message ) {
						var data = JSON.parse( message.data );
						switch ( data.action ) {
							case 'updateDelta':
								console.log('updateDelta'+data.type);
									ZCompanionSSEImport.updateDelta( data.type, data.delta );
								break;

							case 'complete':
							console.log('complete' );

								evtSource.close();

								// 2. Pass - Import XML though "Source Event".

							$(document).trigger( 'zita-sites-import-xml-success' );

								break;
						}
					};
					evtSource.addEventListener( 'log', function ( message ) {
						var data = JSON.parse( message.data );
					
					});
				}
		 });

		},

		/**
		 * 3. Import option data.
		 */
		_importOptions: function( event ) {

			$.ajax({
				url  : zCompanionAdmin.ajax_url,
				type : 'POST',
				//dataType: 'json',
				data : {
					action       : 'zita-site-library-import-options',
					options_data : ZCompanionTemplateAdmin.options_data,
				},
				beforeSend: function() {
					$('.button-hero.zita-demo-import').text( zCompanionAdmin.unique.importingOptions );

				},
			})
			.fail(function( jqXHR ){
				ZCompanionTemplateAdmin._importFailMessage( jqXHR.status + ' ' + jqXHR.responseText );
		    })
			.done(function ( options_data ) {

				// 3. Fail - Import Site Options.
				if( false === options_data.success ) {
				ZCompanionTemplateAdmin._importFailMessage( options_data.data );

				} else {

					// 3. Pass - Import Site Options.
					$(document).trigger( 'zita-import-option-data-success' );
				}
			});
		},


		/**
		 * 4. Import Widgets.
		 */
		_importWidgets: function( event ) {
			$.ajax({
				url  : zCompanionAdmin.ajax_url,
				type : 'POST',
			//	dataType: 'json',
				data : {
					action       : 'zita-site-library-import-widgets',
					widgets_data : ZCompanionTemplateAdmin.widgets_data,
				},
				beforeSend: function() {
					$('.button-hero.zita-demo-import').text( zCompanionAdmin.unique.importingWidgets );
				},
			})
			.fail(function( jqXHR ){
				ZCompanionTemplateAdmin._importFailMessage( jqXHR.status + ' ' + jqXHR.responseText );
		    })
			.done(function ( widgets_data ) {
				// 4. Fail - Import Widgets.
				if( false === widgets_data.success ) {
				ZCompanionTemplateAdmin._importFailMessage( widgets_data.data );

				} else {
				console.log('Import completed..');
					// 4. Pass - Import Widgets.
					$(document).trigger( 'zita-sites-import-widgets-success' );					
				}
			});
		},
}; // class end

ZCompanionTemplateAdmin.init();


[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
					new SelectFx(el);
				} );

})(jQuery);
