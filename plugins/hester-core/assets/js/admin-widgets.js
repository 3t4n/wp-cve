//--------------------------------------------------------------------//
// Hester Core Admin Widgets script.
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
	var HesterCoreAdminWidgets = {

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			// Document ready
			$(document).ready( HesterCoreAdminWidgets.ready );

			// Window load
			$(window).on( 'load', HesterCoreAdminWidgets.load );

			// Bind UI actions
			HesterCoreAdminWidgets.bindUIActions();

			// Trigger event when Hester fully loaded
			$(document).trigger( 'hesterCoreWidgetsReady' );
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

			HesterCoreAdminWidgets.initRepeatableSortable();
		},

		/**
		 * Window load.
		 *
		 * @since 1.0.0
		 */
		load: function() {
		},

		/**
		 * Bind UI actions.
		 *
		 * @since 1.0.0
		*/
		bindUIActions: function() {

			var $this,
				index = 0,
				template,
				$widget;

			$(document).on( 'click', '.hester-repeatable-widget .add-new-item', function(e){
				e.preventDefault();

				$this    = $(this);
				index    = parseInt( $this.attr('data-index') );
				template = wp.template( 'hester-core-repeatable-item' );

				var data = {
					index: index,
					name: $this.attr('data-widget-name'),
					id: $this.attr('data-widget-id'),
				};

				index++;

				$this.attr( 'data-index', index );
				$( template( data ) ).insertBefore( $this.closest('.hester-repeatable-footer') );
				$this.closest('.widget-inside').trigger('change');

				update_widget_repeatable_class( $this );
			});

			$(document).on( 'click', '.hester-repeatable-widget .remove-repeatable-item', function(e){
				e.preventDefault();

				$this   = $(this);
				$widget = $this.closest('.hester-repeatable-container');

				$this.closest('.widget-inside').trigger('change');
				$this.closest('.hester-repeatable-item').remove();
				
				update_widget_repeatable_class( $widget );
			});

			$(document).on( 'click', '.hester-repeatable-widget .hester-repeatable-item-title', function(){
				$(this).closest('.hester-repeatable-item').toggleClass('open');
			});

			var update_widget_repeatable_class = function( $target ) {

				var $widget = $target.closest('.hester-repeatable-container');

				if ( $widget.find('.hester-repeatable-item').length ) {
					$widget.removeClass('empty');
				} else {
					$widget.addClass('empty');
				}
			};

			// Updated widget event.
			$(document).on( 'widget-updated widget-added', function( e, widget ){
				if ( widget.find('.hester-repeatable-container').length ) {
					HesterCoreAdminWidgets.initRepeatableSortable();
				}
			});
		},

		//--------------------------------------------------------------------//
		// Functions
		//--------------------------------------------------------------------//

		initRepeatableSortable: function() {

			$('.hester-repeatable-container').sortable({
				handle: '.hester-repeatable-item-title',
				accent: '.hester-repeatable-item',
				containment: 'parent',
				tolerance: 'pointer',
				change: function( event, ui ){
					$(this).closest('.widget-inside').trigger('change');
				},
			});


		},

	}; // END var HesterCoreAdminWidgets.

	HesterCoreAdminWidgets.init();
	window.HesterCoreAdminWidgets = HesterCoreAdminWidgets;	

})( jQuery );
