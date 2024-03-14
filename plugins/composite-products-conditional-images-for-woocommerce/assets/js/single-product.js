;( function ( $, window, document, Backbone, undefined ) {

	$( '.composite_data' ).on( 'wc-composite-initializing', function( event, composite ) {

		function Overlay_Image_App() {

			/**
			 * Track changes to 'Overlay Image' scenarios.
			 */
			var Overlay_Image_Model = function( opts ) {

				var Model = Backbone.Model.extend( {

					initialize: function( options ) {

						composite.actions.add_action( 'active_scenarios_updated', this.component_selection_changed_handler, 20, this );

						var params = {
							active_scenarios: [],
						};

						this.set( params );
					},

					component_selection_changed_handler: function( step ) {

						// Get the active 'overlay_image' scenarios, preserving their order.
						var active_scenarios = _.intersection( composite.scenarios.get_scenarios_by_type( 'overlay_image' ), composite.scenarios.get_active_scenarios_by_type( 'overlay_image' ) );

						this.set( { active_scenarios: active_scenarios } );
					}

				} );

				var obj = new Model( opts );
				return obj;
			};

			/**
			 * Render overlays.
			 */
			var Overlay_Image_View = function( opts ) {

				var View = Backbone.View.extend( {

					$main_image_container: false,
					$main_image: false,

					initialize: function( options ) {

						this.$main_image_container = this.$el.find( '.woocommerce-product-gallery__image' ).first();

						if ( this.$main_image_container.length > 0 ) {
							this.$main_image = this.$main_image_container.find( 'a img' ).first();
						} else {
							this.$main_image_container = this.$el.find( '.woocommerce-product-gallery__image--placeholder' ).first();
							if ( this.$main_image_container.length > 0 ) {
								this.$main_image = this.$main_image_container.find( 'img' ).first();
							}
						}

						if ( ! this.$main_image ) {
							return;
						}

						this.listenTo( this.model, 'change:active_scenarios', this.render );

						var view = this;

						/**
						 * Recalculate overlay widths on resize.
						 */
						$wc_cp_window.resize( function() {

							if ( ! composite.is_initialized ) {
								return false;
							}

							view.handle_resize();

						} );

						if ( this.$main_image.get(0).complete ) {
							setTimeout( function() {
								view.handle_resize();
							}, 100 );
						} else {
							this.$main_image.one( 'load', function() {
								setTimeout( function() {
									view.handle_resize();
								}, 100 );
							} );
						}
					},

					render: function() {

						var active_scenarios = this.model.get( 'active_scenarios' ),
						    image_width      = this.$main_image.width(),
						    overlay_css      = { width: image_width, position: 'absolute', 'z-index': 1 };

						// Remove overlays.
						this.$main_image_container.find( '.wc-cp-overlay-image' ).remove();

						for ( var index = active_scenarios.length - 1; index >= 0 ; index-- ) {

							var scenario_id = active_scenarios[ index ],
							    image_html  = composite.scenarios.get_scenario_data().scenario_settings.overlay_image[ scenario_id ];

							if ( ! image_html ) {
								continue;
							}

							var $image_html = $( image_html ).css( overlay_css );

							if ( image_html ) {
								this.$main_image_container.prepend( $image_html );
								overlay_css[ 'z-index' ]++;
							}
						}
					},

					handle_resize: function() {

						var image_width = this.$main_image.width(),
						    overlay_css = { width: image_width };

						this.$main_image_container.find( '.wc-cp-overlay-image' ).css( overlay_css );
					}

				} );

				var obj = new View( opts );
				return obj;
			};

			/**
			 * Initialize app.
			 */
			this.initialize = function() {

				var $images_wrapper = $( '.woocommerce-product-gallery__wrapper' ).first();

				if ( $images_wrapper.length > 0 ) {
					this.model = new Overlay_Image_Model();
					this.view = new Overlay_Image_View( { model: this.model, el: $images_wrapper } );
				}

			};
		}

		var app = new Overlay_Image_App();

		app.initialize();

	} );

} ) ( jQuery, window, document, Backbone );
