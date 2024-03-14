wp.ImgSlider = 'undefined' === typeof( wp.ImgSlider ) ? {} : wp.ImgSlider;
wp.ImgSlider.modalChildViews = 'undefined' === typeof( wp.ImgSlider.modalChildViews ) ? [] : wp.ImgSlider.modalChildViews;
wp.ImgSlider.previewer = 'undefined' === typeof( wp.ImgSlider.previewer ) ? {} : wp.ImgSlider.previewer;
wp.ImgSlider.modal = 'undefined' === typeof( wp.ImgSlider.modal ) ? {} : wp.ImgSlider.modal;
wp.ImgSlider.items = 'undefined' === typeof( wp.ImgSlider.items ) ? {} : wp.ImgSlider.items;
wp.ImgSlider.upload = 'undefined' === typeof( wp.ImgSlider.upload ) ? {} : wp.ImgSlider.upload;

jQuery( document ).ready( function( $ ){

	// Here we will have all gallery's items.
	//wp.ImgSlider.Items = new wp.ImgSlider.items['collection']();
	
	// Settings related objects.
	wp.ImgSlider.Settings = new wp.ImgSlider.settings['model']( ImgSliderHelper.settings );

	// ImgSlider conditions
	wp.ImgSlider.Conditions = new ImgSliderGalleryConditions();

	// Initiate ImgSlider Resizer
	if ( 'undefined' == typeof wp.ImgSlider.Resizer ) {
		wp.ImgSlider.Resizer = new wp.ImgSlider.previewer['resizer']();
	}
	
	// Initiate Gallery View
	wp.ImgSlider.GalleryView = new wp.ImgSlider.previewer['view']({
		'el' : $( '#img-slider-uploader-container' ),
	});

	// ImgSlider edit item modal.
	wp.ImgSlider.EditModal = new wp.ImgSlider.modal['model']({
		'childViews' : wp.ImgSlider.modalChildViews
	});


	// Here we will add items for the gallery to collection.
	if ( 'undefined' !== typeof ImgSliderHelper.items ) {
		$.each( ImgSliderHelper.items, function( index, image ){
			var imageModel = new wp.ImgSlider.items['model']( image );
		});
	}

	// Initiate ImgSlider Gallery Upload
	new wp.ImgSlider.upload['uploadHandler']();  

});