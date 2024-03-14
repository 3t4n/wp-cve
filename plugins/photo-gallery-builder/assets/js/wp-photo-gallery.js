wp.PhotoGallery = 'undefined' === typeof( wp.PhotoGallery ) ? {} : wp.PhotoGallery;
wp.PhotoGallery.modalChildViews = 'undefined' === typeof( wp.PhotoGallery.modalChildViews ) ? [] : wp.PhotoGallery.modalChildViews;
wp.PhotoGallery.previewer = 'undefined' === typeof( wp.PhotoGallery.previewer ) ? {} : wp.PhotoGallery.previewer;
wp.PhotoGallery.modal = 'undefined' === typeof( wp.PhotoGallery.modal ) ? {} : wp.PhotoGallery.modal;
wp.PhotoGallery.items = 'undefined' === typeof( wp.PhotoGallery.items ) ? {} : wp.PhotoGallery.items;
wp.PhotoGallery.upload = 'undefined' === typeof( wp.PhotoGallery.upload ) ? {} : wp.PhotoGallery.upload;

jQuery( document ).ready( function( $ ){

	// Here we will have all gallery's items.
	wp.PhotoGallery.Items = new wp.PhotoGallery.items['collection']();
	
	// Settings related objects.
	wp.PhotoGallery.Settings = new wp.PhotoGallery.settings['model']( PhotoGalleryHelper.settings );

	// PhotoGallery conditions
	wp.PhotoGallery.Conditions = new PhotoGalleryGalleryConditions();

	// Initiate PhotoGallery Resizer
	if ( 'undefined' == typeof wp.PhotoGallery.Resizer ) {
		wp.PhotoGallery.Resizer = new wp.PhotoGallery.previewer['resizer']();
	}
	
	// Initiate Gallery View
	wp.PhotoGallery.GalleryView = new wp.PhotoGallery.previewer['view']({
		'el' : $( '#photo-gallery-uploader-container' ),
	});

	// PhotoGallery edit item modal.
	wp.PhotoGallery.EditModal = new wp.PhotoGallery.modal['model']({
		'childViews' : wp.PhotoGallery.modalChildViews
	});


	// Here we will add items for the gallery to collection.
	if ( 'undefined' !== typeof PhotoGalleryHelper.items ) {
		$.each( PhotoGalleryHelper.items, function( index, image ){
			var imageModel = new wp.PhotoGallery.items['model']( image );
		});
	}

	// Initiate PhotoGallery Gallery Upload
	new wp.PhotoGallery.upload['uploadHandler']();  // Comented By DEEPAK

});