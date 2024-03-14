wp.ImgSlider = 'undefined' === typeof( wp.ImgSlider ) ? {} : wp.ImgSlider;

var ImgSliderGalleryConditions = Backbone.Model.extend({

	initialize: function( args ){

		var rows = jQuery('.img-slider-settings-container tr[data-container]');
		var tabs = jQuery('.img-slider-tabs .img-slider-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );

		this.initEvents();
		this.initValues();

	},

	initEvents: function(){

		this.listenTo( wp.ImgSlider.Settings, 'change:designName', this.changedType );
	},

	initValues: function(){

		this.changedType( true, wp.ImgSlider.Settings.get( 'designName' ) );
		//console.log(wp.ImgSlider.Settings.get( 'designName' ));
	},

	changedType: function( settings, value ){
		var rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );

		if ( 'Boxed_Slider' == value ) {

			tabs.filter( '[data-tab="portfolio-wp-captions"], [data-tab="portfolio-wp-sliderControls"]' ).show();

			rows.filter( '[data-container="titleColor"], [data-container="titleBgColor"], [data-container="hide_title"], [data-container="titleFontSize"]' ).show();
			rows.filter( '[data-container="captionColor"], [data-container="captionBgColor"], [data-container="hide_description"], [data-container="captionFontSize"], [data-container="sliderColor"], [data-container="sliderBgColor"], [data-container="numOfImages"]' ).hide();

		} 
		else if ( 'Caption_Slider' == value ){
			tabs.filter( '[data-tab="portfolio-wp-sliderControls"]' ).hide();
			tabs.filter( '[data-tab="portfolio-wp-captions"]' ).show();
			rows.filter( '[data-container="hide_navigation"], [data-container="sliderBgColor"] , [data-container="numOfImages"]' ).hide();
		} 
		else if ( 'Content_Slider' == value ){
			tabs.filter( '[data-tab="portfolio-wp-captions"]' ).show();
			tabs.filter( '[data-tab="portfolio-wp-sliderControls"]' ).show();
			rows.filter( '[data-container="sliderColor"], [data-container="numOfImages"]' ).hide();
		} 
		else if ( 'Thumbnail_Slider' == value ){
			tabs.filter( '[data-tab="portfolio-wp-sliderControls"]' ).show();
			tabs.filter( '[data-tab="portfolio-wp-captions"]' ).hide();
			rows.filter( '[data-container="numOfImages"]' ).hide();
		} 
		else if ( 'Effect_Coverflow_Slider' == value || 'Owl_Slider' == value ) {

			// Hide Responsive tab
			tabs.filter( '[data-tab="portfolio-wp-captions"], [data-tab="portfolio-wp-sliderControls"]' ).hide();
			rows.filter( ' [data-container="numOfImages"]' ).show();


		}else {
			tabs.filter( '[data-tab="portfolio-wp-captions"]' ).show();
			tabs.filter( '[data-tab="portfolio-wp-sliderControls"]' ).show();
			rows.filter( '[data-container="sliderColor"], [data-container="sliderBgColor"], [data-container="numOfImages"]' ).hide();
		}

	},

});