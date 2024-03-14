wp.PhotoGallery = 'undefined' === typeof( wp.PhotoGallery ) ? {} : wp.PhotoGallery;

(function( $, PhotoGallery ){

	var PhotoGalleryToolbar = wp.media.view.Toolbar.Select.extend({
		clickSelect: function() {

			var controller = this.controller,
				state = controller.state(),
				selection = state.get('selection');

			controller.close();
			state.trigger( 'insert', selection ).reset();
		}
	});

	var PhotoGalleryAttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			var LibraryViewSwitcher, Filters, toolbarOptions;

			wp.media.view.AttachmentsBrowser.prototype.createToolbar.call(this);

			this.toolbar.set( 'photo-gallery-error', new PhotoGallery.upload['errorview']({
				controller: this.controller,
				priority: -80
			}) );

		},
	});

	var PhotoGalleryFrame = wp.media.view.MediaFrame.Select.extend({

		className: 'media-frame photo-gallery-media-modal',

		createStates: function() {
			var options = this.options;

			if ( this.options.states ) {
				return;
			}

			// Add the default states.
			this.states.add([
				// Main states.
				new PhotoGallery.upload['library']({
					library:   wp.media.query( options.library ),
					multiple:  options.multiple,
					title:     options.title,
					priority:  20
				})
			]);
		},

		createSelectToolbar: function( toolbar, options ) {
			options = options || this.options.button || {};
			options.controller = this;

			toolbar.view = new PhotoGallery.upload['toolbar']( options );
		},

		browseContent: function( contentRegion ) {
			var state = this.state();

			// this.$el.removeClass('hide-toolbar');

			// Browse our library of attachments.
			contentRegion.view = new PhotoGallery.upload['attachmentsbrowser']({
			// contentRegion.view = new wp.media.view.AttachmentsBrowser({
				controller: this,
				collection: state.get('library'),
				selection:  state.get('selection'),
				model:      state,
				sortable:   state.get('sortable'),
				search:     state.get('searchable'),
				filters:    state.get('filterable'),
				date:       state.get('date'),
				display:    state.has('display') ? state.get('display') : state.get('displaySettings'),
				dragInfo:   state.get('dragInfo'),

				idealColumnWidth: state.get('idealColumnWidth'),
				suggestedWidth:   state.get('suggestedWidth'),
				suggestedHeight:  state.get('suggestedHeight'),

				AttachmentView: state.get('AttachmentView')
			});
		},

	});

	var PhotoGallerySelection = wp.media.model.Selection.extend({

		add: function( models, options ) {
			var needed, differences;

			if ( ! this.multiple ) {
				this.remove( this.models );
			}

			if ( this.length >= 30 ) {
				models = [];
				wp.media.frames.PhotoGallery.trigger( 'PhotoGallery:show-error', {'message' : PhotoGalleryHelper.strings.limitExceeded } );
			}else{

				needed = 30 - this.length;

				if ( Array.isArray( models ) && models.length > 1 ) {
					// Create an array with elements that we don't have in our selection
					differences = _.difference( _.pluck(models, 'cid'), _.pluck(this.models, 'cid') );

					// Check if we have mode elements that we need
					if ( differences.length > needed ) {
						// Filter our models, to have only that we don't have already
						models = _.filter( models, function( model ){
							return _.contains( differences, model.cid );
						});
						// Get only how many we need.
						models = models.slice( 0, needed );
						wp.media.frames.PhotoGallery.trigger( 'PhotoGallery:show-error', {'message' : PhotoGalleryHelper.strings.limitExceeded } );
					}

				}

			}

			/**
			 * call 'add' directly on the parent class
			 */
			return wp.media.model.Attachments.prototype.add.call( this, models, options );
		},

	});

	var PhotoGalleryLibrary = wp.media.controller.Library.extend({

		initialize: function() {
			var selection = this.get('selection'),
				props;

			if ( ! this.get('library') ) {
				this.set( 'library', wp.media.query() );
			}

			if ( ! ( selection instanceof PhotoGallery.upload['selection'] ) ) {
				props = selection;

				if ( ! props ) {
					props = this.get('library').props.toJSON();
					props = _.omit( props, 'orderby', 'query' );
				}

				this.set( 'selection', new PhotoGallery.upload['selection']( null, {
					multiple: this.get('multiple'),
					props: props
				}) );
			}

			this.resetDisplays();
		},

	});

	var PhotoGalleryError = wp.media.View.extend({
		tagName:   'div',
		className: 'photo-gallery-error-container hide',
		errorTimeout: false,
		delay: 400,
		message: '',

		initialize: function() {

			this.controller.on( 'PhotoGallery:show-error', this.show, this );
			this.controller.on( 'PhotoGallery:hide-error', this.hide, this );

			this.render();
		},

		show: function( e ) {

			if ( 'undefined' !== typeof e.message ) {
				this.message = e.message;
			}

			if ( '' != this.message ) {
				this.render();
				this.$el.removeClass( 'hide' );
			}

		},

		hide: function() {
			this.$el.addClass( 'hide' );
		},

		render: function() {
			var html = '<div class="photo-gallery-error"><span>' + this.message + '</span></div>';
			this.$el.html( html );
		}
	});

	var uploadHandler = Backbone.Model.extend({

		uploaderOptions: {
			container: $( '#photo-gallery-uploader-container' ),
			browser: $( '#photo-gallery-uploader-browser' ),
			dropzone: $( '#photo-gallery-uploader-container' ),
			max_files: 30,
		},
		dropzone: $( '#photo-gallary-dropzone-container' ),
		progressBar: $( '.photo-gallery-progress-bar' ),
		containerUploader: $( '.photo-gallery-upload-actions' ),
		errorContainer: $( '.photo-gallery-error-container' ),
		galleryCotainer: $( '#photo-gallery-uploader-container .photo-gallery-uploader-inline-content' ),
		PhotoGallery_files_count: 0,
		limitExceeded: false,

		initialize: function(){
			
			var PhotoGalleryGalleryObject = this,
				uploader,
				dropzone,
				attachments,
				limitExceeded = false,
				PhotoGallery_files_count = 0;

			uploader = new wp.Uploader( PhotoGalleryGalleryObject.uploaderOptions );
			// Uploader events
			// Files Added for Uploading - show progress bar
			uploader.uploader.bind( 'FilesAdded', $.proxy( PhotoGalleryGalleryObject.filesadded, PhotoGalleryGalleryObject ) );

			// File Uploading - update progress bar
			uploader.uploader.bind( 'UploadProgress', $.proxy( PhotoGalleryGalleryObject.fileuploading, PhotoGalleryGalleryObject ) );

			// File Uploaded - add images to the screen
			uploader.uploader.bind( 'FileUploaded', $.proxy( PhotoGalleryGalleryObject.fileupload, PhotoGalleryGalleryObject ) );

			// Files Uploaded - hide progress bar
			uploader.uploader.bind( 'UploadComplete', $.proxy( PhotoGalleryGalleryObject.filesuploaded, PhotoGalleryGalleryObject ) );

			// File Upload Error - show errors
			uploader.uploader.bind( 'Error', function( up, err ) {

				// Show message
	            PhotoGalleryGalleryObject.errorContainer.html( '<div class="error fade"><p>' + err.file.name + ': ' + err.message + '</p></div>' );
	            up.refresh();

			});

			// Dropzone events
			dropzone = uploader.dropzone;
			dropzone.on( 'dropzone:enter', PhotoGalleryGalleryObject.show );
			dropzone.on( 'dropzone:leave', PhotoGalleryGalleryObject.hide );

			// Single Image Actions ( Delete/Edit )
			PhotoGalleryGalleryObject.galleryCotainer.on( 'click', '.photo-gallery-delete-image', function( e ){
				e.preventDefault();
				$(this).parents( '.photo-gallery-single-image' ).remove();
			});

			// PhotoGallery WordPress Media Library
	        wp.media.frames.PhotoGallery = new PhotoGallery.upload['frame']({
	            frame: 'select',
	            reset: false,
	            title:  wp.media.view.l10n.addToGalleryTitle,
	            button: {
	                text: wp.media.view.l10n.addToGallery,
	            },
	            multiple: 'add',
	        });

	        // Mark existing Gallery images as selected when the modal is opened
	        wp.media.frames.PhotoGallery.on( 'open', function() {

	            // Get any previously selected images
	            var selection = wp.media.frames.PhotoGallery.state().get( 'selection' );
	            selection.reset();

	            // Get images that already exist in the gallery, and select each one in the modal
	            wp.PhotoGallery.Items.each( function( item ) {
	            	var image = wp.media.attachment( item.get( 'id' ) );
	                selection.add( image ? [ image ] : [] );
	            });

	            selection.single( selection.last() );

	        } );
	        

	        // Insert into Gallery Button Clicked
	        wp.media.frames.PhotoGallery.on( 'insert', function( selection ) {

	            // Get state
	            var state = wp.media.frames.PhotoGallery.state();
	            var oldItemsCollection = wp.PhotoGallery.Items;

	            PhotoGallery.Items = new PhotoGallery.items['collection']();

	            // Iterate through selected images, building an images array
	            selection.each( function( attachment ) {
	            	var attachmentAtts = attachment.toJSON(),
	            		currentModel = oldItemsCollection.get( attachmentAtts['id'] );

	            	if ( currentModel ) {
	            		wp.PhotoGallery.Items.addItem( currentModel );
	            		oldItemsCollection.remove( currentModel );
	            	}else{

	            		PhotoGalleryGalleryObject.generateSingleImage( attachmentAtts );
	            	}
	            }, this );

	            while ( model = oldItemsCollection.first() ) {
				  model.delete();
				}

	        } );

	        // Open WordPress Media Gallery
	        $( '#photo-wp-gallery' ).click( function( e ){
	        	e.preventDefault();
	        	wp.media.frames.PhotoGallery.open();
	        });

		},

		// Uploader Events
		// Files Added for Uploading - show progress bar
		filesadded: function( up, files ){

	            		console.log( files);

			var PhotoGalleryGalleryObject = this;

			// Hide any existing errors
            PhotoGalleryGalleryObject.errorContainer.html( '' );

			// Get the number of files to be uploaded
            PhotoGalleryGalleryObject.PhotoGallery_files_count = files.length;

            // Set the status text, to tell the user what's happening
            $( '.photo-gallery-upload-numbers .photo-gallery-current', PhotoGalleryGalleryObject.containerUploader ).text( '1' );
            $( '.photo-gallery-upload-numbers .photo-gallery-total', PhotoGalleryGalleryObject.containerUploader ).text( PhotoGalleryGalleryObject.PhotoGallery_files_count );

            // Show progress bar
            PhotoGalleryGalleryObject.containerUploader.addClass( 'show-progress' );

		},

		// File Uploading - update progress bar
		fileuploading: function( up, file ) {

			var PhotoGalleryGalleryObject = this;

			// Update the status text
            $( '.photo-gallery-upload-numbers .photo-gallery-current', PhotoGalleryGalleryObject.containerUploader ).text( ( PhotoGalleryGalleryObject.PhotoGallery_files_count - up.total.queued ) + 1 );

            // Update the progress bar
            $( '.photo-gallery-progress-bar-inner', PhotoGalleryGalleryObject.progressBar ).css({ 'width': up.total.percent + '%' });

		},

		// File Uploaded - add images to the screen
		fileupload: function( up, file, info ){

			var PhotoGalleryGalleryObject = this;

			var response = JSON.parse( info.response );
			if ( wp.PhotoGallery.Items.length < 30 ) {
				PhotoGalleryGalleryObject.generateSingleImage( response['data'] );
			}else{
				PhotoGalleryGalleryObject.limitExceeded = true;
			}

		},

		// Files Uploaded - hide progress bar
		filesuploaded: function() {

			var PhotoGalleryGalleryObject = this;

			setTimeout( function() {
                PhotoGalleryGalleryObject.containerUploader.removeClass( 'show-progress' );
            }, 1000 );

			if ( PhotoGalleryGalleryObject.limitExceeded ) {
				PhotoGalleryGalleryObject.limitExceeded = false;
				wp.media.frames.PhotoGallery.open();
				wp.media.frames.PhotoGallery.trigger( 'PhotoGallery:show-error', {'message' : PhotoGalleryHelper.strings.limitExceeded } );
			}

		},

		show: function() {
			var $el = $( '#photo-gallary-dropzone-container' ).show();

			// Ensure that the animation is triggered by waiting until
			// the transparent element is painted into the DOM.
			_.defer( function() {
				$el.css({ opacity: 1 });
			});
		},

		hide: function() {
			var $el = $( '#photo-gallary-dropzone-container' ).css({ opacity: 0 });

			wp.media.transition( $el ).done( function() {
				// Transition end events are subject to race conditions.
				// Make sure that the value is set as intended.
				if ( '0' === $el.css('opacity') ) {
					$el.hide();
				}
			});

			// https://core.trac.wordpress.org/ticket/27341
			_.delay( function() {
				if ( '0' === $el.css('opacity') && $el.is(':visible') ) {
					$el.hide();
				}
			}, 500 );
		},

		generateSingleImage: function( attachment ){
			var data = { halign: 'center', valign: 'middle', link: '', target: '' }
				captionSource = PhotoGallery.Settings.get( 'wp_field_caption' ),
				titleSource = PhotoGallery.Settings.get( 'wp_field_title' );

			data['full']      = attachment['sizes']['full']['url'];
			if ( "undefined" != typeof attachment['sizes']['large'] ) {
				data['thumbnail'] = attachment['sizes']['large']['url'];
			}else{
				data['thumbnail'] = data['full'];
			}
			data['id']          = attachment['id'];
			data['alt']         = attachment['alt'];
			data['orientation'] = attachment['orientation'];

			// Check from where to populate image title
			if ( 'none' == titleSource ) {
				data['title'] = '';
			}else if ( 'title' == titleSource ) {
				data['title'] = attachment['title'];
			}else if ( 'description' == titleSource ) {
				data['title'] = attachment['description'];
			}

			// Check from where to populate image caption
			if ( 'none' == captionSource ) {
				data['caption'] = '';
			}else if ( 'title' == captionSource ) {
				data['caption'] = attachment['title'];
			}else if ( 'caption' == captionSource ) {
				data['caption'] = attachment['caption'];
			}else if ( 'description' == captionSource ) {
				data['caption'] = attachment['description'];
			}

			//console.log( data ) ;
			new PhotoGallery.items['model']( data );
		}

	});

    PhotoGallery.upload = {
        'toolbar' : PhotoGalleryToolbar,
        'attachmentsbrowser' : PhotoGalleryAttachmentsBrowser,
        'frame' : PhotoGalleryFrame,
        'selection' : PhotoGallerySelection,
        'library' : PhotoGalleryLibrary,
        'errorview' : PhotoGalleryError,
        'uploadHandler' : uploadHandler
    };

}( jQuery, wp.PhotoGallery ))