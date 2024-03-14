wp.TimelineWP = 'undefined' === typeof( wp.TimelineWP ) ? {} : wp.TimelineWP;

(function( $, TimelineWP ){

	var TimelineWPToolbar = wp.media.view.Toolbar.Select.extend({
		clickSelect: function() {

			var controller = this.controller,
				state = controller.state(),
				selection = state.get('selection');

			controller.close();
			state.trigger( 'insert', selection ).reset();

		}
	});

	var TimelineWPAttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			var LibraryViewSwitcher, Filters, toolbarOptions;

			wp.media.view.AttachmentsBrowser.prototype.createToolbar.call(this);


			this.toolbar.set( 'timeline-wp-error', new TimelineWP.upload['errorview']({
				controller: this.controller,
				priority: -80
			}) );

		},
	});

	var TimelineWPFrame = wp.media.view.MediaFrame.Select.extend({

		className: 'media-frame timeline-wp-media-modal',

		createStates: function() {
			var options = this.options;

			if ( this.options.states ) {
				return;
			}

			// Add the default states.
			this.states.add([
				// Main states.
				new TimelineWP.upload['library']({
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

			toolbar.view = new TimelineWP.upload['toolbar']( options );
		},

		browseContent: function( contentRegion ) {
			var state = this.state();

			// this.$el.removeClass('hide-toolbar');

			// Browse our library of attachments.
			contentRegion.view = new TimelineWP.upload['attachmentsbrowser']({
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

	var TimelineWPSelection = wp.media.model.Selection.extend({

		add: function( models, options ) {
			var needed, differences;

			if ( ! this.multiple ) {
				this.remove( this.models );
			}

			if ( this.length >= 20 ) {
				models = [];
				wp.media.frames.TimelineWP.trigger( 'TimelineWP:show-error', {'message' : TimelineWPHelper.strings.limitExceeded } );
			}else{

				needed = 20 - this.length;

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
						wp.media.frames.TimelineWP.trigger( 'TimelineWP:show-error', {'message' : TimelineWPHelper.strings.limitExceeded } );
					}

				}

			}

			/**
			 * call 'add' directly on the parent class
			 */
			return wp.media.model.Attachments.prototype.add.call( this, models, options );
		},

	});

	var TimelineWPLibrary = wp.media.controller.Library.extend({

		initialize: function() {
			var selection = this.get('selection'),
				props;

			if ( ! this.get('library') ) {
				this.set( 'library', wp.media.query() );
			}

			if ( ! ( selection instanceof TimelineWP.upload['selection'] ) ) {
				props = selection;

				if ( ! props ) {
					props = this.get('library').props.toJSON();
					props = _.omit( props, 'orderby', 'query' );
				}

				this.set( 'selection', new TimelineWP.upload['selection']( null, {
					multiple: this.get('multiple'),
					props: props
				}) );
			}

			this.resetDisplays();
		},

	});

	var TimelineWPError = wp.media.View.extend({
		tagName:   'div',
		className: 'timeline-wp-error-container hide',
		errorTimeout: false,
		delay: 400,
		message: '',

		initialize: function() {

			this.controller.on( 'TimelineWP:show-error', this.show, this );
			this.controller.on( 'TimelineWP:hide-error', this.hide, this );

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
			var html = '<div class="timeline-wp-error"><span>' + this.message + '</span></div>';
			this.$el.html( html );
		}
	});

	var uploadHandler = Backbone.Model.extend({
		uploaderOptions: {
			container: $( '#timeline-wp-uploader-container' ),
			browser: $( '#timeline-wp-uploader-browser' ),
			dropzone: $( '#timeline-wp-uploader-container' ),
			max_files: 20,
		},
		dropzone: $( '#timeline-wp-dropzone-container' ),
		progressBar: $( '.timeline-wp-progress-bar' ),
		containerUploader: $( '.timeline-wp-upload-actions' ),
		errorContainer: $( '.timeline-wp-error-container' ),
		galleryCotainer: $( '#timeline-wp-uploader-container .timeline-wp-uploader-inline-content' ),
		TimelineWP_files_count: 0,
		limitExceeded: false,

		initialize: function(){
			var TimelineWPGalleryObject = this,
				uploader,
				dropzone,
				attachments,
				limitExceeded = false,
				TimelineWP_files_count = 0;

			uploader = new wp.Uploader( TimelineWPGalleryObject.uploaderOptions );

			// Uploader events
			// Files Added for Uploading - show progress bar
			uploader.uploader.bind( 'FilesAdded', $.proxy( TimelineWPGalleryObject.filesadded, TimelineWPGalleryObject ) );

			// File Uploading - update progress bar
			uploader.uploader.bind( 'UploadProgress', $.proxy( TimelineWPGalleryObject.fileuploading, TimelineWPGalleryObject ) );

			// File Uploaded - add images to the screen
			uploader.uploader.bind( 'FileUploaded', $.proxy( TimelineWPGalleryObject.fileupload, TimelineWPGalleryObject ) );

			// Files Uploaded - hide progress bar
			uploader.uploader.bind( 'UploadComplete', $.proxy( TimelineWPGalleryObject.filesuploaded, TimelineWPGalleryObject ) );

			// File Upload Error - show errors
			uploader.uploader.bind( 'Error', function( up, err ) {

				// Show message
	            TimelineWPGalleryObject.errorContainer.html( '<div class="error fade"><p>' + err.file.name + ': ' + err.message + '</p></div>' );
	            up.refresh();

			});

			// Dropzone events
			dropzone = uploader.dropzone;
			dropzone.on( 'dropzone:enter', TimelineWPGalleryObject.show );
			dropzone.on( 'dropzone:leave', TimelineWPGalleryObject.hide );

			// Single Image Actions ( Delete/Edit )
			TimelineWPGalleryObject.galleryCotainer.on( 'click', '.timeline-wp-delete-image', function( e ){
				e.preventDefault();
				$(this).parents( '.timeline-wp-single-image' ).remove();
			});

			// TimelineWP WordPress Media Library
	        wp.media.frames.TimelineWP = new TimelineWP.upload['frame']({
	            frame: 'select',
	            reset: false,
	            title:  wp.media.view.l10n.addToGalleryTitle,
	            button: {
	                text: wp.media.view.l10n.addToGallery,
	            },
	            multiple: 'add',
	        });

	        // Mark existing Gallery images as selected when the modal is opened
	        wp.media.frames.TimelineWP.on( 'open', function() {

	            // Get any previously selected images
	            var selection = wp.media.frames.TimelineWP.state().get( 'selection' );
	            selection.reset();

	            // Get images that already exist in the gallery, and select each one in the modal
	            wp.TimelineWP.Items.each( function( item ) {
	            	var image = wp.media.attachment( item.get( 'id' ) );
	                selection.add( image ? [ image ] : [] );
	            });

	            selection.single( selection.last() );

	        } );
	        

	        // Insert into Gallery Button Clicked
	        wp.media.frames.TimelineWP.on( 'insert', function( selection ) {

	            // Get state
	            var state = wp.media.frames.TimelineWP.state();
	            var oldItemsCollection = wp.TimelineWP.Items;

	            TimelineWP.Items = new TimelineWP.items['collection']();

	            // Iterate through selected images, building an images array
	            selection.each( function( attachment ) {
	            	var attachmentAtts = attachment.toJSON(),
	            		currentModel = oldItemsCollection.get( attachmentAtts['id'] );

	            	if ( currentModel ) {
	            		wp.TimelineWP.Items.addItem( currentModel );
	            		oldItemsCollection.remove( currentModel );
	            	}else{
	            		TimelineWPGalleryObject.generateSingleImage( attachmentAtts );
	            	}
	            }, this );

	            while ( model = oldItemsCollection.first() ) {
				  model.delete();
				}

	        } );

	        // Open WordPress Media Gallery
	        $( '#timeline-wp-wp-gallery' ).click( function( e ){
	        	e.preventDefault();
	        	wp.media.frames.TimelineWP.open();
	        });

		},

		// Uploader Events
		// Files Added for Uploading - show progress bar
		filesadded: function( up, files ){

			var TimelineWPGalleryObject = this;

			// Hide any existing errors
            TimelineWPGalleryObject.errorContainer.html( '' );

			// Get the number of files to be uploaded
            TimelineWPGalleryObject.TimelineWP_files_count = files.length;

            // Set the status text, to tell the user what's happening
            $( '.timeline-wp-upload-numbers .timeline-wp-current', TimelineWPGalleryObject.containerUploader ).text( '1' );
            $( '.timeline-wp-upload-numbers .timeline-wp-total', TimelineWPGalleryObject.containerUploader ).text( TimelineWPGalleryObject.TimelineWP_files_count );

            // Show progress bar
            TimelineWPGalleryObject.containerUploader.addClass( 'show-progress' );

		},

		// File Uploading - update progress bar
		fileuploading: function( up, file ) {

			var TimelineWPGalleryObject = this;

			// Update the status text
            $( '.timeline-wp-upload-numbers .timeline-wp-current', TimelineWPGalleryObject.containerUploader ).text( ( TimelineWPGalleryObject.TimelineWP_files_count - up.total.queued ) + 1 );

            // Update the progress bar
            $( '.timeline-wp-progress-bar-inner', TimelineWPGalleryObject.progressBar ).css({ 'width': up.total.percent + '%' });

		},

		// File Uploaded - add images to the screen
		fileupload: function( up, file, info ){

			var TimelineWPGalleryObject = this;

			var response = JSON.parse( info.response );
			if ( wp.TimelineWP.Items.length < 20 ) {
				TimelineWPGalleryObject.generateSingleImage( response['data'] );
			}else{
				TimelineWPGalleryObject.limitExceeded = true;
			}

		},

		// Files Uploaded - hide progress bar
		filesuploaded: function() {

			var TimelineWPGalleryObject = this;

			setTimeout( function() {
                TimelineWPGalleryObject.containerUploader.removeClass( 'show-progress' );
            }, 1000 );

			if ( TimelineWPGalleryObject.limitExceeded ) {
				TimelineWPGalleryObject.limitExceeded = false;
				wp.media.frames.TimelineWP.open();
				wp.media.frames.TimelineWP.trigger( 'TimelineWP:show-error', {'message' : TimelineWPHelper.strings.limitExceeded } );
			}

		},

		show: function() {
			var $el = $( '#timeline-wp-dropzone-container' ).show();

			// Ensure that the animation is triggered by waiting until
			// the transparent element is painted into the DOM.
			_.defer( function() {
				$el.css({ opacity: 1 });
			});
		},

		hide: function() {
			var $el = $( '#timeline-wp-dropzone-container' ).css({ opacity: 0 });

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
				captionSource = TimelineWP.Settings.get( 'wp_field_caption' ),
				titleSource = TimelineWP.Settings.get( 'wp_field_title' );

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

			new TimelineWP.items['model']( data );
		}

	});

    TimelineWP.upload = {
        'toolbar' : TimelineWPToolbar,
        'attachmentsbrowser' : TimelineWPAttachmentsBrowser,
        'frame' : TimelineWPFrame,
        'selection' : TimelineWPSelection,
        'library' : TimelineWPLibrary,
        'errorview' : TimelineWPError,
        'uploadHandler' : uploadHandler
    };

}( jQuery, wp.TimelineWP ))