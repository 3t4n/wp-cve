"use strict";

/* Basic -------------------------------------------------------------- */

(function($) {
	var abrMetabox = {};

	( function() {
		var $this;

		abrMetabox = {
			/*
			* Initialize
			*/
			init: function( e ) {
				$this = abrMetabox;

				// Variables.
				$this.wrap = $( '.abr-metabox-wrap' );

				// Init.
				$this.metaboxInit( e );

				// Init events.
				$this.events( e );
			},

			/*
			* Events
			*/
			events: function( e ) {

				// Custom Events
				$this.wrap.on( 'click change keyup keydown', '.abr-metabox-repeater .attribute-name', $this.setSignature );
				$this.wrap.on( 'click', '.abr-metabox-repeater .row-topbar', $this.toggleItems );
				$this.wrap.on( 'click', '.abr-metabox-repeater .btn-remove-row', $this.removeRepeaterRow );
				$this.wrap.on( 'click', '.abr-metabox-repeater .btn-add-row', $this.addRepeaterRow );
			},

			/*
			* Init metabox elements
			*/
			metaboxInit: function( e ) {
				// Add tabs for Meta Box (UI)
				$this.wrap.find( '.abr-metabox-tabs' ).tabs();

				// Repeater sortable
				$this.wrap.find( '.abr-metabox-repeater tbody' ).sortable( {
					items: 'tr',
					placeholder: 'ui-state-highlight',
					handle: '.row-topbar, .row-handle',
					start: function( e, ui ) {
						ui.placeholder.height( ui.item.height() );
					},
				} );
			},

			/*
			* Toggle items
			*/
			toggleItems: function() {
				if ( $( this ).hasClass( 'closed' ) ) {
					$( this ).removeClass( 'closed' );

					$( this ).siblings( '.row-fields' ).slideDown();
				} else {
					$( this ).addClass( 'closed' );

					$( this ).siblings( '.row-fields' ).slideUp();
				}
			},

			/*
			* Set signature
			*/
			setSignature: function() {

				var label = '<span>' + $( this ).data( 'label' ) + '</span>';

				var value = $( this ).val() ? $( this ).val() : label;

				$( this ).parents( '.row-content' ).find( '.signature' ).html( value );
			},

			/*
			* Add repeater row
			*/
			addRepeaterRow: function() {

				var repeater = $( this ).siblings( '.abr-metabox-repeater-table' )

				// Get html row.
				var html = repeater.find( 'tbody tr.hidden' ).html();

				// Add new row.
				repeater.find( 'tbody' ).append( '<tr class="row">' + html + '</tr>' );

				// Visible all input and textarea.
				repeater.find( 'tr' ).not( '.hidden' ).find( 'input, textarea' ).removeAttr( 'disabled' );

				return false;
			},

			/*
			* Remove repeater row
			*/
			removeRepeaterRow: function() {
				$( this ).parents( '.row' ).remove();

				return false;
			}
		};

	} )();

	// Initialize.
	$( function() {
		abrMetabox.init();
	} );

})(jQuery);

/* Reviews -------------------------------------------------------------- */

(function($) {
	var abrReviews = {};

	( function() {
		var $this;

		abrReviews = {
			/*
			* Initialize
			*/
			init: function( e ) {
				$this = abrReviews;

				// Variables.
				$this.wrap = $( '.review-wrap' );

				// Init.
				$this.reviewsInit( e );

				// Init events.
				$this.events( e );
			},

			/*
			* Events
			*/
			events: function( e ) {
				// Custom Events
				$this.wrap.on( 'click', 'input[name="abr_review_settings"]', $this.toggleMetaBox );
				$this.wrap.on( 'click', 'input[name="abr_review_auto_score"]', $this.actionAutoScore );
				$this.wrap.on( 'change', 'select[name="abr_review_schema_author"]', $this.actionSchemaAuthor );
			},

			/*
			* Init reviews elements
			*/
			reviewsInit: function( e ) {
				$this.actionAutoScore( 'input[name="abr_review_auto_score"]' );
				$this.actionSchemaAuthor( 'select[name="abr_review_schema_author"]' );
			},

			/*
			* Toggle metabox view
			*/
			toggleMetaBox: function() {
				if ( $( this ).prop( 'checked' ) ) {
					$this.wrap.find( '.abr-metabox-tabs' ).attr( 'checked', 'checked' );
				} else {
					$this.wrap.find( '.abr-metabox-tabs' ).removeAttr( 'checked' );
				}
			},

			/*
			* Action auto score
			*/
			actionAutoScore: function( e ) {
				let checked = $( typeof e === 'string' ? e : this ).prop('checked');

				if ( checked ) {
					$( '.review-field-total-score' ).addClass( 'hidden' );
				} else {
					$( '.review-field-total-score' ).removeClass( 'hidden' );
				}
			},

			/*
			* Action schema author
			*/
			actionSchemaAuthor: function( e ) {
				let val = $( typeof e === 'string' ? e : this ).val();

				if ( 'custom' !== val ) {
					$( '.review-field-schema-author-custom' ).addClass( 'hidden' );
				} else {
					$( '.review-field-schema-author-custom' ).removeClass( 'hidden' );
				}
			},
		};

	} )();

	// Initialize.
	$( function() {
		abrReviews.init();
	});

})(jQuery);