wp.PhotoGallery = 'undefined' === typeof( wp.PhotoGallery ) ? {} : wp.PhotoGallery;

var PhotoGalleryGalleryConditions = Backbone.Model.extend({

	initialize: function( args ){

		var rows = jQuery('.photo-gallery-settings-container tr[data-container]');
		var tabs = jQuery('.photo-gallery-tabs .photo-gallery-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );

		this.initEvents();
		this.initValues();

	},

	initEvents: function(){

		this.listenTo( wp.PhotoGallery.Settings, 'change:layout', this.changedType );
		this.listenTo( wp.PhotoGallery.Settings, 'change:resizeOption', this.changedTypeResize );

	},

	initValues: function(){

		this.changedType( false, wp.PhotoGallery.Settings.get( 'layout' ) );
		this.changedType( false, wp.PhotoGallery.Settings.get( 'resizeOption' ) );
	},

	changedTypeResize: function( settings, value ){
		var rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );
			if('theme_default'== value){
				rows.filter( '[data-container="img_width"], [data-container="img_height"]' ).hide();
			} else {
				rows.filter( '[data-container="img_width"], [data-container="img_height"]' ).show();
			}
	},

	changedType: function( settings, value ){
		var rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );

		if ( 1 == value ) {

			// Show Responsive tab
			tabs.filter( '[data-tab="photo-gallary-responsive"]' ).show();

			tabs.filter( '[data-tab="photo-gallery-wp-captions"]' ).hide();
			
			rows.filter( '[data-container="gutter"], [data-container="shadowSize"], [data-container="shadowColor"],  [data-container="pgb_lightbox"]' ).show();
			rows.filter( '[data-container="margin"]' ).hide();

		}else if( 2  == value ){
			// Hide Responsive tab
			tabs.filter( '[data-tab="photo-gallary-responsive"]' ).hide();
			tabs.filter( '[data-tab="photo-gallery-wp-captions"]' ).show();

			rows.filter( '[data-container="margin"], [data-container="shadowSize"], [data-container="shadowColor"] ' ).hide();
			rows.filter( '[data-container="pgb_lightbox"], [data-container="gutter"]' ).show();
		
		}else if( 3  == value ){
			// Hide Responsive tab
			tabs.filter( '[data-tab="photo-gallary-responsive"]' ).hide();
			tabs.filter( '[data-tab="photo-gallery-wp-captions"]' ).show();

			rows.filter( '[data-container="margin"], [data-container="shadowSize"], [data-container="shadowColor"] ' ).hide();
			rows.filter( '[data-container="pgb_lightbox"], [data-container="gutter"]' ).show();

		}else if( 4  == value ){
			// Hide Responsive tab
			tabs.filter( '[data-tab="photo-gallary-responsive"]' ).hide();
			tabs.filter( '[data-tab="photo-gallery-wp-captions"]' ).show();

			rows.filter( '[data-container="margin"], [data-container="shadowSize"], [data-container="shadowColor"] ' ).hide();
			rows.filter( '[data-container="pgb_lightbox"], [data-container="gutter"], [data-container="shadowSize"], [data-container="shadowColor"]' ).show();
		
		}else if( 5  == value ){
			rows.filter( '[data-container="pgb_lightbox"],[data-container="margin"], [data-container="shadowSize"], [data-container="shadowColor"] ' ).hide();

		}else if( 6 == value ){
			rows.filter( '[data-container="pgb_lightbox"],[data-container="margin"], [data-container="shadowSize"], [data-container="shadowColor"] ' ).hide();

		}else  { //inner layout

			// Hide Responsive tab
			tabs.filter( '[data-tab="photo-gallary-responsive"]' ).hide();
			tabs.filter( '[data-tab="photo-gallery-wp-captions"]' ).show();

			rows.filter( '[data-container="margin"]' ).hide();
			rows.filter( '[data-container="pgb_lightbox"], [data-container="gutter"]' ).show();

		}

	},

});