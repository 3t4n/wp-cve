wp.RespAccordionSlider = 'undefined' === typeof( wp.RespAccordionSlider ) ? {} : wp.RespAccordionSlider;
wp.RespAccordionSlider.modalChildViews = 'undefined' === typeof( wp.RespAccordionSlider.modalChildViews ) ? [] : wp.RespAccordionSlider.modalChildViews;
wp.RespAccordionSlider.previewer = 'undefined' === typeof( wp.RespAccordionSlider.previewer ) ? {} : wp.RespAccordionSlider.previewer;
wp.RespAccordionSlider.modal = 'undefined' === typeof( wp.RespAccordionSlider.modal ) ? {} : wp.RespAccordionSlider.modal;
wp.RespAccordionSlider.items = 'undefined' === typeof( wp.RespAccordionSlider.items ) ? {} : wp.RespAccordionSlider.items;
wp.RespAccordionSlider.upload = 'undefined' === typeof( wp.RespAccordionSlider.upload ) ? {} : wp.RespAccordionSlider.upload;

jQuery( document ).ready( function( $ ){

	// Here we will have all gallery's items.
	wp.RespAccordionSlider.Items = new wp.RespAccordionSlider.items['collection']();
	
	// Settings related objects.
	wp.RespAccordionSlider.Settings = new wp.RespAccordionSlider.settings['model']( RespAccordionSliderHelper.settings );

	// RespAccordionSlider conditions
	wp.RespAccordionSlider.Conditions = new RespAccordionSliderGalleryConditions();

	// Initiate RespAccordionSlider Resizer
	if ( 'undefined' == typeof wp.RespAccordionSlider.Resizer ) {
		wp.RespAccordionSlider.Resizer = new wp.RespAccordionSlider.previewer['resizer']();
	}
	
	// Initiate Gallery View
	wp.RespAccordionSlider.GalleryView = new wp.RespAccordionSlider.previewer['view']({
		'el' : $( '#ras-uploader-container' ),
	});

	// RespAccordionSlider edit item modal.
	wp.RespAccordionSlider.EditModal = new wp.RespAccordionSlider.modal['model']({
		'childViews' : wp.RespAccordionSlider.modalChildViews
	});


	// Here we will add items for the gallery to collection.
	if ( 'undefined' !== typeof RespAccordionSliderHelper.items ) {
		$.each( RespAccordionSliderHelper.items, function( index, image ){
			var imageModel = new wp.RespAccordionSlider.items['model']( image );
		});
	}

	// Initiate RespAccordionSlider Gallery Upload
	new wp.RespAccordionSlider.upload['uploadHandler']();  // Comented By DEEPAK

});