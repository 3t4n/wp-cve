wp.RespAccordionSlider = 'undefined' === typeof( wp.RespAccordionSlider ) ? {} : wp.RespAccordionSlider;

(function( $, RespAccordionSlider ){

	var RespAccordionSliderToolbar = wp.media.view.Toolbar.Select.extend({
		clickSelect: function() {

			var controller = this.controller,
				state = controller.state(),
				selection = state.get('selection');

			controller.close();
			state.trigger( 'insert', selection ).reset();
		}
	});

	var RespAccordionSliderAttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			var LibraryViewSwitcher, Filters, toolbarOptions;

			wp.media.view.AttachmentsBrowser.prototype.createToolbar.call(this);

			this.toolbar.set( 'ras-slider-error', new RespAccordionSlider.upload['errorview']({
				controller: this.controller,
				priority: -80
			}) );

		},
	});

	var RespAccordionSliderFrame = wp.media.view.MediaFrame.Select.extend({

		className: 'media-frame ras-media-modal',

		createStates: function() {
			var options = this.options;

			if ( this.options.states ) {
				return;
			}

			// Add the default states.
			this.states.add([
				// Main states.
				new RespAccordionSlider.upload['library']({
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

			toolbar.view = new RespAccordionSlider.upload['toolbar']( options );
		},

		browseContent: function( contentRegion ) {
			var state = this.state();

			// this.$el.removeClass('hide-toolbar');

			// Browse our library of attachments.
			contentRegion.view = new RespAccordionSlider.upload['attachmentsbrowser']({
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

	var RespAccordionSliderSelection = wp.media.model.Selection.extend({

		add: function( models, options ) {
			var needed, differences;

			if ( ! this.multiple ) {
				this.remove( this.models );
			}

			if ( this.length >= 25 ) {
				models = [];
				wp.media.frames.RespAccordionSlider.trigger( 'RespAccordionSlider:show-error', {'message' : RespAccordionSliderHelper.strings.limitExceeded } );
			}else{

				needed = 25 - this.length;

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
						wp.media.frames.RespAccordionSlider.trigger( 'RespAccordionSlider:show-error', {'message' : RespAccordionSliderHelper.strings.limitExceeded } );
					}

				}

			}

			/**
			 * call 'add' directly on the parent class
			 */
			return wp.media.model.Attachments.prototype.add.call( this, models, options );
		},

	});

	var RespAccordionSliderLibrary = wp.media.controller.Library.extend({

		initialize: function() {
			var selection = this.get('selection'),
				props;

			if ( ! this.get('library') ) {
				this.set( 'library', wp.media.query() );
			}

			if ( ! ( selection instanceof RespAccordionSlider.upload['selection'] ) ) {
				props = selection;

				if ( ! props ) {
					props = this.get('library').props.toJSON();
					props = _.omit( props, 'orderby', 'query' );
				}

				this.set( 'selection', new RespAccordionSlider.upload['selection']( null, {
					multiple: this.get('multiple'),
					props: props
				}) );
			}

			this.resetDisplays();
		},

	});

	var RespAccordionSliderError = wp.media.View.extend({
		tagName:   'div',
		className: 'ras-error-container hide',
		errorTimeout: false,
		delay: 400,
		message: '',

		initialize: function() {

			this.controller.on( 'RespAccordionSlider:show-error', this.show, this );
			this.controller.on( 'RespAccordionSlider:hide-error', this.hide, this );

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
			var html = '<div class="ras-slider-error"><span>' + this.message + '</span></div>';
			this.$el.html( html );
		}
	});

	var uploadHandler = Backbone.Model.extend({

		uploaderOptions: {
			container: $( '#ras-uploader-container' ),
			browser: $( '#ras-uploader-browser' ),
			dropzone: $( '#ras-uploader-container' ),
			max_files: 25,
		},
		dropzone: $( '#ras-dropzone-container' ),
		progressBar: $( '.ras-progress-bar' ),
		containerUploader: $( '.ras-upload-actions' ),
		errorContainer: $( '.ras-error-container' ),
		galleryCotainer: $( '#ras-uploader-container .ras-uploader-inline-content' ),
		RespAccordionSlider_files_count: 0,
		limitExceeded: false,

		initialize: function(){
			
			var RespAccordionSliderGalleryObject = this,
				uploader,
				dropzone,
				attachments,
				limitExceeded = false,
				RespAccordionSlider_files_count = 0;

			uploader = new wp.Uploader( RespAccordionSliderGalleryObject.uploaderOptions );
			// Uploader events
			// Files Added for Uploading - show progress bar
			uploader.uploader.bind( 'FilesAdded', $.proxy( RespAccordionSliderGalleryObject.filesadded, RespAccordionSliderGalleryObject ) );

			// File Uploading - update progress bar
			uploader.uploader.bind( 'UploadProgress', $.proxy( RespAccordionSliderGalleryObject.fileuploading, RespAccordionSliderGalleryObject ) );

			// File Uploaded - add images to the screen
			uploader.uploader.bind( 'FileUploaded', $.proxy( RespAccordionSliderGalleryObject.fileupload, RespAccordionSliderGalleryObject ) );

			// Files Uploaded - hide progress bar
			uploader.uploader.bind( 'UploadComplete', $.proxy( RespAccordionSliderGalleryObject.filesuploaded, RespAccordionSliderGalleryObject ) );

			// File Upload Error - show errors
			uploader.uploader.bind( 'Error', function( up, err ) {

				// Show message
	            RespAccordionSliderGalleryObject.errorContainer.html( '<div class="error fade"><p>' + err.file.name + ': ' + err.message + '</p></div>' );
	            up.refresh();

			});

			// Dropzone events
			dropzone = uploader.dropzone;
			dropzone.on( 'dropzone:enter', RespAccordionSliderGalleryObject.show );
			dropzone.on( 'dropzone:leave', RespAccordionSliderGalleryObject.hide );

			// Single Image Actions ( Delete/Edit )
			RespAccordionSliderGalleryObject.galleryCotainer.on( 'click', '.ras-delete-image', function( e ){
				e.preventDefault();
				$(this).parents( '.ras-single-image' ).remove();
			});

			// RespAccordionSlider WordPress Media Library
	        wp.media.frames.RespAccordionSlider = new RespAccordionSlider.upload['frame']({
	            frame: 'select',
	            reset: false,
	            title:  wp.media.view.l10n.addToGalleryTitle,
	            button: {
	                text: wp.media.view.l10n.addToGallery,
	            },
	            multiple: 'add',
	        });

	        // Mark existing Gallery images as selected when the modal is opened
	        wp.media.frames.RespAccordionSlider.on( 'open', function() {

	            // Get any previously selected images
	            var selection = wp.media.frames.RespAccordionSlider.state().get( 'selection' );
	            selection.reset();

	            // Get images that already exist in the gallery, and select each one in the modal
	            wp.RespAccordionSlider.Items.each( function( item ) {
	            	var image = wp.media.attachment( item.get( 'id' ) );
	                selection.add( image ? [ image ] : [] );
	            });

	            selection.single( selection.last() );

	        } );
	        

	        // Insert into Gallery Button Clicked
	        wp.media.frames.RespAccordionSlider.on( 'insert', function( selection ) {

	            // Get state
	            var state = wp.media.frames.RespAccordionSlider.state();
	            var oldItemsCollection = wp.RespAccordionSlider.Items;

	            RespAccordionSlider.Items = new RespAccordionSlider.items['collection']();

	            // Iterate through selected images, building an images array
	            selection.each( function( attachment ) {
	            	var attachmentAtts = attachment.toJSON(),
	            		currentModel = oldItemsCollection.get( attachmentAtts['id'] );

	            	if ( currentModel ) {
	            		wp.RespAccordionSlider.Items.addItem( currentModel );
	            		oldItemsCollection.remove( currentModel );
	            	}else{

	            		RespAccordionSliderGalleryObject.generateSingleImage( attachmentAtts );
	            	}
	            }, this );

	            while ( model = oldItemsCollection.first() ) {
				  model.delete();
				}

	        } );

	        // Open WordPress Media Gallery
	        $( '#ras-gallery' ).click( function( e ){
	        	e.preventDefault();
	        	wp.media.frames.RespAccordionSlider.open();
	        });

		},

		// Uploader Events
		// Files Added for Uploading - show progress bar
		filesadded: function( up, files ){

	            		console.log( files);

			var RespAccordionSliderGalleryObject = this;

			// Hide any existing errors
            RespAccordionSliderGalleryObject.errorContainer.html( '' );

			// Get the number of files to be uploaded
            RespAccordionSliderGalleryObject.RespAccordionSlider_files_count = files.length;

            // Set the status text, to tell the user what's happening
            $( '.ras-upload-numbers .ras-current', RespAccordionSliderGalleryObject.containerUploader ).text( '1' );
            $( '.ras-upload-numbers .ras-total', RespAccordionSliderGalleryObject.containerUploader ).text( RespAccordionSliderGalleryObject.RespAccordionSlider_files_count );

            // Show progress bar
            RespAccordionSliderGalleryObject.containerUploader.addClass( 'show-progress' );

		},

		// File Uploading - update progress bar
		fileuploading: function( up, file ) {

			var RespAccordionSliderGalleryObject = this;

			// Update the status text
            $( '.ras-upload-numbers .ras-current', RespAccordionSliderGalleryObject.containerUploader ).text( ( RespAccordionSliderGalleryObject.RespAccordionSlider_files_count - up.total.queued ) + 1 );

            // Update the progress bar
            $( '.ras-progress-bar-inner', RespAccordionSliderGalleryObject.progressBar ).css({ 'width': up.total.percent + '%' });

		},

		// File Uploaded - add images to the screen
		fileupload: function( up, file, info ){

			var RespAccordionSliderGalleryObject = this;

			var response = JSON.parse( info.response );
			if ( wp.RespAccordionSlider.Items.length < 25 ) {
				RespAccordionSliderGalleryObject.generateSingleImage( response['data'] );
			}else{
				RespAccordionSliderGalleryObject.limitExceeded = true;
			}

		},

		// Files Uploaded - hide progress bar
		filesuploaded: function() {

			var RespAccordionSliderGalleryObject = this;

			setTimeout( function() {
                RespAccordionSliderGalleryObject.containerUploader.removeClass( 'show-progress' );
            }, 1000 );

			if ( RespAccordionSliderGalleryObject.limitExceeded ) {
				RespAccordionSliderGalleryObject.limitExceeded = false;
				wp.media.frames.RespAccordionSlider.open();
				wp.media.frames.RespAccordionSlider.trigger( 'RespAccordionSlider:show-error', {'message' : RespAccordionSliderHelper.strings.limitExceeded } );
			}

		},

		show: function() {
			var $el = $( '#ras-dropzone-container' ).show();

			// Ensure that the animation is triggered by waiting until
			// the transparent element is painted into the DOM.
			_.defer( function() {
				$el.css({ opacity: 1 });
			});
		},

		hide: function() {
			var $el = $( '#ras-dropzone-container' ).css({ opacity: 0 });

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
				captionSource = RespAccordionSlider.Settings.get( 'wp_field_caption' ),
				titleSource = RespAccordionSlider.Settings.get( 'wp_field_title' );

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
			new RespAccordionSlider.items['model']( data );
		}

	});

    RespAccordionSlider.upload = {
        'toolbar' : RespAccordionSliderToolbar,
        'attachmentsbrowser' : RespAccordionSliderAttachmentsBrowser,
        'frame' : RespAccordionSliderFrame,
        'selection' : RespAccordionSliderSelection,
        'library' : RespAccordionSliderLibrary,
        'errorview' : RespAccordionSliderError,
        'uploadHandler' : uploadHandler
    };

}( jQuery, wp.RespAccordionSlider ))