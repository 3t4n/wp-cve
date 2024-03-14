wp.ImgSlider = 'undefined' === typeof( wp.ImgSlider ) ? {} : wp.ImgSlider;

(function( $, ImgSlider ){

	var ImgSliderToolbar = wp.media.view.Toolbar.Select.extend({
		clickSelect: function() {

			var controller = this.controller,
				state = controller.state(),
				selection = state.get('selection');

			controller.close();
			state.trigger( 'insert', selection ).reset();
		}
	});

	var ImgSliderAttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			var LibraryViewSwitcher, Filters, toolbarOptions;

			wp.media.view.AttachmentsBrowser.prototype.createToolbar.call(this);

			this.toolbar.set( 'img-slider-error', new ImgSlider.upload['errorview']({
				controller: this.controller,
				priority: -80
			}) );

		},
	});

	var ImgSliderFrame = wp.media.view.MediaFrame.Select.extend({

		className: 'media-frame img-slider-media-modal',

		createStates: function() {
			var options = this.options;

			if ( this.options.states ) {
				return;
			}

			// Add the default states.
			this.states.add([
				// Main states.
				new ImgSlider.upload['library']({
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

			toolbar.view = new ImgSlider.upload['toolbar']( options );
		},

		browseContent: function( contentRegion ) {
			var state = this.state();

			// this.$el.removeClass('hide-toolbar');

			// Browse our library of attachments.
			contentRegion.view = new ImgSlider.upload['attachmentsbrowser']({
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

	var ImgSliderSelection = wp.media.model.Selection.extend({

		add: function( models, options ) {
			var needed, differences;

			if ( ! this.multiple ) {
				this.remove( this.models );
			}

			if ( this.length >= 30 ) {
				models = [];
				wp.media.frames.ImgSlider.trigger( 'ImgSlider:show-error', {'message' : ImgSliderHelper.strings.limitExceeded } );
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
						wp.media.frames.ImgSlider.trigger( 'ImgSlider:show-error', {'message' : ImgSliderHelper.strings.limitExceeded } );
					}

				}

			}

			/**
			 * call 'add' directly on the parent class
			 */
			return wp.media.model.Attachments.prototype.add.call( this, models, options );
		},

	});

	var ImgSliderLibrary = wp.media.controller.Library.extend({

		initialize: function() {
			var selection = this.get('selection'),
				props;

			if ( ! this.get('library') ) {
				this.set( 'library', wp.media.query() );
			}

			if ( ! ( selection instanceof ImgSlider.upload['selection'] ) ) {
				props = selection;

				if ( ! props ) {
					props = this.get('library').props.toJSON();
					props = _.omit( props, 'orderby', 'query' );
				}

				this.set( 'selection', new ImgSlider.upload['selection']( null, {
					multiple: this.get('multiple'),
					props: props
				}) );
			}

			this.resetDisplays();
		},

	});

	var ImgSliderError = wp.media.View.extend({
		tagName:   'div',
		className: 'img-slider-error-container hide',
		errorTimeout: false,
		delay: 400,
		message: '',

		initialize: function() {

			this.controller.on( 'ImgSlider:show-error', this.show, this );
			this.controller.on( 'ImgSlider:hide-error', this.hide, this );

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
			var html = '<div class="img-slider-error"><span>' + this.message + '</span></div>';
			this.$el.html( html );
		}
	});

	var uploadHandler = Backbone.Model.extend({

		uploaderOptions: {
			container: $( '#img-slider-uploader-container' ),
			browser: $( '#img-slider-uploader-browser' ),
			dropzone: $( '#img-slider-uploader-container' ),
			max_files: 30,
		},
		dropzone: $( '#img-slider-dropzone-container' ),
		progressBar: $( '.img-slider-progress-bar' ),
		containerUploader: $( '.img-slider-upload-actions' ),
		errorContainer: $( '.img-slider-error-container' ),
		galleryCotainer: $( '#img-slider-uploader-container .img-slider-uploader-inline-content' ),
		ImgSlider_files_count: 0,
		limitExceeded: false,

		initialize: function(){
			
			var ImgSliderGalleryObject = this,
				uploader,
				dropzone,
				attachments,
				limitExceeded = false,
				ImgSlider_files_count = 0;

			uploader = new wp.Uploader( ImgSliderGalleryObject.uploaderOptions );
			// Uploader events
			// Files Added for Uploading - show progress bar
			uploader.uploader.bind( 'FilesAdded', $.proxy( ImgSliderGalleryObject.filesadded, ImgSliderGalleryObject ) );

			// File Uploading - update progress bar
			uploader.uploader.bind( 'UploadProgress', $.proxy( ImgSliderGalleryObject.fileuploading, ImgSliderGalleryObject ) );

			// File Uploaded - add images to the screen
			uploader.uploader.bind( 'FileUploaded', $.proxy( ImgSliderGalleryObject.fileupload, ImgSliderGalleryObject ) );

			// Files Uploaded - hide progress bar
			uploader.uploader.bind( 'UploadComplete', $.proxy( ImgSliderGalleryObject.filesuploaded, ImgSliderGalleryObject ) );

			// File Upload Error - show errors
			uploader.uploader.bind( 'Error', function( up, err ) {

				// Show message
	            ImgSliderGalleryObject.errorContainer.html( '<div class="error fade"><p>' + err.file.name + ': ' + err.message + '</p></div>' );
	            up.refresh();

			});

			// Dropzone events
			dropzone = uploader.dropzone;
			dropzone.on( 'dropzone:enter', ImgSliderGalleryObject.show );
			dropzone.on( 'dropzone:leave', ImgSliderGalleryObject.hide );

			// Single Image Actions ( Delete/Edit )
			ImgSliderGalleryObject.galleryCotainer.on( 'click', '.img-slider-delete-image', function( e ){
				e.preventDefault();
				$(this).parents( '.img-slider-single-image' ).remove();
			});

			// ImgSlider WordPress Media Library
	        wp.media.frames.ImgSlider = new ImgSlider.upload['frame']({
	            frame: 'select',
	            reset: false,
	            title:  wp.media.view.l10n.addToGalleryTitle,
	            button: {
	                text: wp.media.view.l10n.addToGallery,
	            },
	            multiple: 'add',
	        });

	        // Mark existing Gallery images as selected when the modal is opened
	        wp.media.frames.ImgSlider.on( 'open', function() {

	            // Get any previously selected images
	            var selection = wp.media.frames.ImgSlider.state().get( 'selection' );
	            selection.reset();

	            // Get images that already exist in the gallery, and select each one in the modal
	            wp.ImgSlider.Items.each( function( item ) {
	            	var image = wp.media.attachment( item.get( 'id' ) );
	                selection.add( image ? [ image ] : [] );
	            });

	            selection.single( selection.last() );

	        } );
	        

	        // Insert into Gallery Button Clicked
	        wp.media.frames.ImgSlider.on( 'insert', function( selection ) {

	            // Get state
	            var state = wp.media.frames.ImgSlider.state();
	            var oldItemsCollection = wp.ImgSlider.Items;

	            ImgSlider.Items = new ImgSlider.items['collection']();

	            // Iterate through selected images, building an images array
	            selection.each( function( attachment ) {
	            	var attachmentAtts = attachment.toJSON(),
	            		currentModel = oldItemsCollection.get( attachmentAtts['id'] );

	            	if ( currentModel ) {
	            		wp.ImgSlider.Items.addItem( currentModel );
	            		oldItemsCollection.remove( currentModel );
	            	}else{

	            		ImgSliderGalleryObject.generateSingleImage( attachmentAtts );
	            	}
	            }, this );

	            while ( model = oldItemsCollection.first() ) {
				  model.delete();
				}

	        } );

	        // Open WordPress Media Gallery
	        $( '#img-slider-gallery' ).click( function( e ){
	        	e.preventDefault();
	        	wp.media.frames.ImgSlider.open();
	        });

		},

		// Uploader Events
		// Files Added for Uploading - show progress bar
		filesadded: function( up, files ){

	            		console.log( files);

			var ImgSliderGalleryObject = this;

			// Hide any existing errors
            ImgSliderGalleryObject.errorContainer.html( '' );

			// Get the number of files to be uploaded
            ImgSliderGalleryObject.ImgSlider_files_count = files.length;

            // Set the status text, to tell the user what's happening
            $( '.img-slider-upload-numbers .img-slider-current', ImgSliderGalleryObject.containerUploader ).text( '1' );
            $( '.img-slider-upload-numbers .img-slider-total', ImgSliderGalleryObject.containerUploader ).text( ImgSliderGalleryObject.ImgSlider_files_count );

            // Show progress bar
            ImgSliderGalleryObject.containerUploader.addClass( 'show-progress' );

		},

		// File Uploading - update progress bar
		fileuploading: function( up, file ) {

			var ImgSliderGalleryObject = this;

			// Update the status text
            $( '.img-slider-upload-numbers .img-slider-current', ImgSliderGalleryObject.containerUploader ).text( ( ImgSliderGalleryObject.ImgSlider_files_count - up.total.queued ) + 1 );

            // Update the progress bar
            $( '.img-slider-progress-bar-inner', ImgSliderGalleryObject.progressBar ).css({ 'width': up.total.percent + '%' });

		},

		// File Uploaded - add images to the screen
		fileupload: function( up, file, info ){

			var ImgSliderGalleryObject = this;

			var response = JSON.parse( info.response );
			if ( wp.ImgSlider.Items.length < 30 ) {
				ImgSliderGalleryObject.generateSingleImage( response['data'] );
			}else{
				ImgSliderGalleryObject.limitExceeded = true;
			}

		},

		// Files Uploaded - hide progress bar
		filesuploaded: function() {

			var ImgSliderGalleryObject = this;

			setTimeout( function() {
                ImgSliderGalleryObject.containerUploader.removeClass( 'show-progress' );
            }, 1000 );

			if ( ImgSliderGalleryObject.limitExceeded ) {
				ImgSliderGalleryObject.limitExceeded = false;
				wp.media.frames.ImgSlider.open();
				wp.media.frames.ImgSlider.trigger( 'ImgSlider:show-error', {'message' : ImgSliderHelper.strings.limitExceeded } );
			}

		},

		show: function() {
			var $el = $( '#img-slider-dropzone-container' ).show();

			// Ensure that the animation is triggered by waiting until
			// the transparent element is painted into the DOM.
			_.defer( function() {
				$el.css({ opacity: 1 });
			});
		},

		hide: function() {
			var $el = $( '#img-slider-dropzone-container' ).css({ opacity: 0 });

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
				captionSource = ImgSlider.Settings.get( 'wp_field_caption' ),
				titleSource = ImgSlider.Settings.get( 'wp_field_title' );

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
			new ImgSlider.items['model']( data );
		}

	});

    ImgSlider.upload = {
        'toolbar' : ImgSliderToolbar,
        'attachmentsbrowser' : ImgSliderAttachmentsBrowser,
        'frame' : ImgSliderFrame,
        'selection' : ImgSliderSelection,
        'library' : ImgSliderLibrary,
        'errorview' : ImgSliderError,
        'uploadHandler' : uploadHandler
    };

}( jQuery, wp.ImgSlider ))