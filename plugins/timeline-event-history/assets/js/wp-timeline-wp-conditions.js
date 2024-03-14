wp.TimelineWP = 'undefined' === typeof( wp.TimelineWP ) ? {} : wp.TimelineWP;

var TimelineWPGalleryConditions = Backbone.Model.extend({

	initialize: function( args ){

		var rows = jQuery('.timeline-wp-settings-container tr[data-container]');
		var tabs = jQuery('.timeline-wp-tabs .timeline-wp-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );

		this.initEvents();
		this.initValues();

	},

	initEvents: function(){

		this.listenTo( wp.TimelineWP.Settings, 'change:type', this.changedType );
		this.listenTo( wp.TimelineWP.Settings, 'change:effect', this.changedEffect );
		this.listenTo( wp.TimelineWP.Settings, 'change:lightbox', this.changedLightbox );

	},

	initValues: function(){

		this.changedType( false, wp.TimelineWP.Settings.get( 'type' ) );
		this.changedEffect( false, wp.TimelineWP.Settings.get( 'effect' ) );
		this.changedLightbox( false, wp.TimelineWP.Settings.get( 'lightbox' ) );

	},

	changedType: function( settings, value ){
		var rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );

		if ( 'custom-grid' == value ) {

			// Show Responsive tab
			tabs.filter( '[data-tab="timeline-wp-responsive"]' ).show();
			
			rows.filter( '[data-container="columns"], [data-container="gutter"]' ).show();
			rows.filter( '[data-container="width"], [data-container="height"], [data-container="margin"], [data-container="randomFactor"], [data-container="shuffle"]' ).hide();

		}else if ( 'creative-gallery' ) {

			// Hide Responsive tab
			tabs.filter( '[data-tab="timeline-wp-responsive"]' ).hide();

			rows.filter( '[data-container="columns"], [data-container="gutter"]' ).hide();
			rows.filter( '[data-container="width"], [data-container="height"], [data-container="margin"], [data-container="randomFactor"], [data-container="shuffle"]' ).show();

		}

	},

	changedLightbox: function( settings, value ){
		var rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );

		if ( 'lightbox2' == value ) {
			
			rows.filter( '[data-container="show_navigation"], [data-container="show_navigation_on_mobile"]' ).show();

		}else{

			rows.filter( '[data-container="show_navigation"], [data-container="show_navigation_on_mobile"]' ).hide();

		}

	},

	changedEffect: function( settings, value ){
		var hoverBoxes = jQuery( '.timeline-wp-effects-preview > div' );

		hoverBoxes.hide();
		hoverBoxes.filter( '.panel-' + value ).show();
	}

});