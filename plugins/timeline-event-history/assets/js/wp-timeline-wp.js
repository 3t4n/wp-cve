wp.TimelineWP = 'undefined' === typeof( wp.TimelineWP ) ? {} : wp.TimelineWP;
wp.TimelineWP.modalChildViews = 'undefined' === typeof( wp.TimelineWP.modalChildViews ) ? [] : wp.TimelineWP.modalChildViews;
wp.TimelineWP.previewer = 'undefined' === typeof( wp.TimelineWP.previewer ) ? {} : wp.TimelineWP.previewer;
wp.TimelineWP.modal = 'undefined' === typeof( wp.TimelineWP.modal ) ? {} : wp.TimelineWP.modal;
wp.TimelineWP.items = 'undefined' === typeof( wp.TimelineWP.items ) ? {} : wp.TimelineWP.items;
wp.TimelineWP.upload = 'undefined' === typeof( wp.TimelineWP.upload ) ? {} : wp.TimelineWP.upload;
// Importent Count Functions

jQuery( window ).load( function( $ ) {
	// tinymce.init({selector:'textarea'});
	// let timelineCount = jQuery('#TimelineTabContent');
	// alert("hello");

});
jQuery( document ).ready( function( $ ) {


	// Here we will have all gallery's items.
	wp.TimelineWP.Items = new wp.TimelineWP.items['collection']();
	
	// Settings related objects.
	wp.TimelineWP.Settings = new wp.TimelineWP.settings['model']( TimelineWPHelper.settings );

	// TimelineWP conditions
	wp.TimelineWP.Conditions = new TimelineWPGalleryConditions();

	// Initiate TimelineWP Resizer
	if ( 'undefined' == typeof wp.TimelineWP.Resizer ) {
		wp.TimelineWP.Resizer = new wp.TimelineWP.previewer['resizer']();
	}
	
	// Initiate Gallery View
	wp.TimelineWP.GalleryView = new wp.TimelineWP.previewer['view']({
		'el' : $( '#timeline-wp-uploader-container' ),
	});

	// TimelineWP edit item modal.
	wp.TimelineWP.EditModal = new wp.TimelineWP.modal['model']({
		'childViews' : wp.TimelineWP.modalChildViews
	});

	// Here we will add items for the gallery to collection.
	if ( 'undefined' !== typeof TimelineWPHelper.items ) {
		$.each( TimelineWPHelper.items, function( index, image ){
			var imageModel = new wp.TimelineWP.items['model']( image );
		});
	}
	// All Custom js Writen By ME	
	


	// Initiate TimelineWP Gallery Upload
	// new wp.TimelineWP.upload['uploadHandler']();  // Comented By DEEPAK

		
});
