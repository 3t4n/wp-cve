wp.RespAccordionSlider = 'undefined' === typeof( wp.RespAccordionSlider ) ? {} : wp.RespAccordionSlider;

var RespAccordionSliderGalleryConditions = Backbone.Model.extend({

	initialize: function( args ){

		var rows = jQuery('.ras-settings-container tr[data-container]');
		var tabs = jQuery('.ras-tabs .ras-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );

	},


});