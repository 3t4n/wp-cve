/**
* Image Model
*/
var EnviraGalleryImage = Backbone.Model.extend( {

    /**
    * Defaults
    * As we always populate this model with existing data, we
    * leave these blank to just show how this model is structured.
    */
    defaults: {
        'id':       '',
        'title':    '',
        'caption':  '',
        'alt':      '',
        'link':     '',
    },

} );

/**
* Images Collection
* - Comprises of all images in an Envira Gallery
* - Each image is represented by an EnviraGalleryImage Model
*/
var EnviraGalleryImages = new Backbone.Collection;

/**
* Modal Window
* - Used by most Envira Backbone views to display information e.g. bulk edit, edit single image etc.
*/
if ( typeof EnviraGalleryModalWindow == 'undefined' ) {
    var EnviraGalleryModalWindow = new wp.media.view.Modal( {
        controller: {
            trigger: function() {
            }
        }
    } );
}

/**
* View
*/
var EnviraGalleryEditView = wp.Backbone.View.extend( {

    /**
    * The Tag Name and Tag's Class(es)
    */
    tagName:    'div',
    className:  'edit-attachment-frame mode-select hide-menu hide-router',

    /**
    * Template
    * - The template to load inside the above tagName element
    */
    template:   wp.template( 'envira-meta-editor' ),

    /**
    * Events
    * - Functions to call when specific events occur
    */
    events: {
        'click .edit-media-header .left':               'loadPreviousItem',
        'click .edit-media-header .right':              'loadNextItem',

        'keyup input':                                  'updateItem',
        'keyup textarea':                               'updateItem',
        'change input':                                 'updateItem',
        'change textarea':                              'updateItem',
        'blur textarea':                                'updateItem',
        'change select':                                'updateItem',

        'click .actions a.envira-gallery-meta-submit':  'saveItem',

        'keyup input#link-search':                      'searchLinks',
        'click div.query-results li':                   'insertLink',

        'click button.media-file':                      'insertMediaFileLink',
        'click button.attachment-page':                 'insertAttachmentPageLink',
    },

    /**
    * Initialize
    *
    * @param object model   EnviraGalleryImage Backbone Model
    */
    initialize: function( args ) {

        // Define loading and loaded events, which update the UI with what's happening.
        this.on( 'loading', this.loading, this );
        this.on( 'loaded',  this.loaded, this );

        // Set some flags
        this.is_loading = false;
        this.collection = args.collection;
        this.child_views = args.child_views;
        this.attachment_id = args.attachment_id;
        this.attachment_index = 0;
        this.search_timer = '';

        // Get the model from the collection
        var count = 0;
        this.collection.each( function( model ) {
            // If this model's id matches the attachment id, this is the model we want
            if ( model.get( 'id' ) == this.attachment_id ) {
                this.model = model;
                this.attachment_index = count;
                return false;
            }

            // Increment the index count
            count++;
        }, this );

    },

    /**
    * Render
    * - Binds the model to the view, so we populate the view's fields and data
    */
    render: function() {

        // Get HTML
        this.$el.html( this.template( this.model.attributes ) );

        // If any child views exist, render them now
        if ( this.child_views.length > 0 ) {
            this.child_views.forEach( function( view ) {
                // Init with model
                var child_view = new view( {
                    model: this.model
                } );

                // Render view within our main view
                this.$el.find( 'div.envira-addons' ).append( child_view.render().el );
            }, this );
        }

        // Set caption
        this.$el.find( 'textarea[name=caption]' ).val( this.model.get( 'caption' ) );

        // Init QuickTags on the caption editor
        // Delay is required for the first load for some reason
        setTimeout( function() {
            quicktags( {
                id:     'caption',
                buttons:'strong,em,link,ul,ol,li,close'
            } );
            QTags._buttonsInit();
        }, 500 );

        // Init Link Searching
        wpLink.init;

        // Enable / disable the buttons depending on the index
        if ( this.attachment_index == 0 ) {
            // Disable left button
            this.$el.find( 'button.left' ).addClass( 'disabled' );
        }
        if ( this.attachment_index == ( this.collection.length - 1 ) ) {
            // Disable right button
            this.$el.find( 'button.right' ).addClass( 'disabled' );
        }

        // Return
        return this;

    },

    /**
    * Renders an error using
    * wp.media.view.EnviraGalleryError
    */
    renderError: function( error ) {

        // Define model
        var model = {};
        model.error = error;

        // Define view
        var view = new wp.media.view.EnviraGalleryError( {
            model: model
        } );

        // Return rendered view
        return view.render().el;

    },

    /**
    * Tells the view we're loading by displaying a spinner
    */
    loading: function() {

        // Set a flag so we know we're loading data
        this.is_loading = true;

        // Show the spinner
        this.$el.find( '.spinner' ).css( 'visibility', 'visible' );

    },

    /**
    * Hides the loading spinner
    */
    loaded: function( response ) {

        // Set a flag so we know we're not loading anything now
        this.is_loading = false;

        // Hide the spinner
        this.$el.find( '.spinner' ).css( 'visibility', 'hidden' );

        // Display the error message, if it's provided
        if ( typeof response !== 'undefined' ) {
            this.$el.find( 'div.media-toolbar' ).after( this.renderError( response ) );
        }

    },

    /**
    * Load the previous model in the collection
    */
    loadPreviousItem: function() {

        // Decrement the index
        this.attachment_index--;

        // Get the model at the new index from the collection
        this.model = this.collection.at( this.attachment_index );

        // Update the attachment_id
        this.attachment_id = this.model.get( 'id' );

        // Re-render the view
        this.render();

    },

    /**
    * Load the next model in the collection
    */
    loadNextItem: function() {

        // Increment the index
        this.attachment_index++;

        // Get the model at the new index from the collection
        this.model = this.collection.at( this.attachment_index );

        // Update the attachment_id
        this.attachment_id = this.model.get( 'id' );

        // Re-render the view
        this.render();

    },

    /**
    * Updates the model based on the changed view data
    */
    updateItem: function( event ) {

        // Check if the target has a name. If not, it's not a model value we want to store
        if ( event.target.name == '' ) {
            return;
        }

        // Update the model's value, depending on the input type
        if ( event.target.type == 'checkbox' ) {
            value = ( event.target.checked ? event.target.value : 0 );
        } else {
            value = event.target.value;
        }

        // Update the model
        this.model.set( event.target.name, value );

    },

    /**
    * Saves the image metadata
    */
    saveItem: function( event ) {
      var $error_element = jQuery('#envira-metabox-saving-error');
      if(0 !== $error_element.length){
        $error_element.hide();
      }

      event.preventDefault();

        // Tell the View we're loading
        this.trigger( 'loading' );

        // Make an AJAX request to save the image metadata
        wp.media.ajax( 'envira_gallery_save_meta', {
            context: this,
            data: {
                nonce:     envira_gallery_metabox.save_nonce,
                post_id:   envira_gallery_metabox.id,
                attach_id: this.model.get( 'id' ),
                meta:      this.model.attributes,
            },
            success: function( response ) {
                // Tell the view we've finished successfully
                this.trigger( 'loaded loaded:success' );

                // Assign the model's JSON string back to the underlying item
                var item = JSON.stringify( this.model.attributes ),
                    item_element = jQuery( 'ul#envira-gallery-output li#' + this.model.get( 'id' ) );

                // Assign the JSON
                jQuery( item_element ).attr( 'data-envira-gallery-image-model', item );

                // Update the title and hint
                jQuery( 'div.meta div.title span', item_element ).text( this.model.get( 'title' ) );
                jQuery( 'div.meta div.title a.hint', item_element ).attr( 'title', this.model.get( 'title' ) );

                // Display or hide the title hint depending on the title length
                if ( this.model.get( 'title' ).length > 20 ) {
                    jQuery( 'div.meta div.title a.hint', item_element ).removeClass( 'hidden' );
                } else {
                    jQuery( 'div.meta div.title a.hint', item_element ).addClass( 'hidden' );
                }

                // Show the user the 'saved' notice for 1.5 seconds
                var saved = this.$el.find( '.saved' );
                saved.fadeIn();
                setTimeout( function() {
                    saved.fadeOut();
                }, 1500 );

            },
            error: function( error_message ) {
                // Tell wp.media we've finished, but there was an error
                this.trigger( 'loaded loaded:error', error_message );
                if(0 === $error_element.length){
                  var saved = this.$el.find('.saved');
                  jQuery(saved).after(`<span id='envira-metabox-saving-error' style='color: red'>${error_message}</span>`)
                } else {
                  $error_element.show();
                  $error_element.html(error_message);
                }
            }
        } );

    },

    /**
    * Searches Links
    */
    searchLinks: function( event ) {


    },

    /**
    * Inserts the clicked link into the URL field
    */
    insertLink: function( event ) {



    },

    /**
    * Inserts the direct media link for the Media Library item
    *
    * The button triggering this event is only displayed if we are editing a
    * Media Library item, so there's no need to perform further checks
    */
    insertMediaFileLink: function( event ) {

        // Tell the View we're loading
        this.trigger( 'loading' );

        // Make an AJAX request to get the media link
        wp.media.ajax( 'envira_gallery_get_attachment_links', {
            context: this,
            data: {
                nonce:          envira_gallery_metabox.save_nonce,
                attachment_id:  this.model.get( 'id' ),
            },
            success: function( response ) {

                // Update model
                this.model.set( 'link', response.media_link );

                // Tell the view we've finished successfully
                this.trigger( 'loaded loaded:success' );

                // Re-render the view
                this.render();

            },
            error: function( error_message ) {

                // Tell wp.media we've finished, but there was an error
                this.trigger( 'loaded loaded:error', error_message );

            }
        } );

    },

    /**
    * Inserts the attachment page link for the Media Library item
    *
    * The button triggering this event is only displayed if we are editing a
    * Media Library item, so there's no need to perform further checks
    */
    insertAttachmentPageLink: function( event ) {

        // Tell the View we're loading
        this.trigger( 'loading' );

        // Make an AJAX request to get the media link
        wp.media.ajax( 'envira_gallery_get_attachment_links', {
            context: this,
            data: {
                nonce:          envira_gallery_metabox.save_nonce,
                attachment_id:  this.model.get( 'id' ),
            },
            success: function( response ) {

                // Update model
                this.model.set( 'link', response.attachment_page );

                // Tell the view we've finished successfully
                this.trigger( 'loaded loaded:success' );

                // Re-render the view
                this.render();

            },
            error: function( error_message ) {

                // Tell wp.media we've finished, but there was an error
                this.trigger( 'loaded loaded:error', error_message );

            }
        } );

    }

} );

/**
* Sub Views
* - Addons must populate this array with their own Backbone Views, which will be appended
* to the settings region
*/
var EnviraGalleryChildViews = [];

/**
* DOM
*/
jQuery( document ).ready( function( $ ) {

    // Edit Image
    $( document ).on( 'click', '#envira-gallery-main a.envira-gallery-modify-image', function( e ) {

        // Prevent default action
        e.preventDefault();

        // (Re)populate the collection
        // The collection can change based on whether the user previously selected specific images
        EnviraGalleryImagesUpdate( false );

        // Get the selected attachment
        var attachment_id = $( this ).parent().data( 'envira-gallery-image' );

        // Pass the collection of images for this gallery to the modal view, as well
        // as the selected attachment
        EnviraGalleryModalWindow.content( new EnviraGalleryEditView( {
            collection:     EnviraGalleryImages,
            child_views:    EnviraGalleryChildViews,
            attachment_id:  attachment_id,
        } ) );

        // Open the modal window
        EnviraGalleryModalWindow.open();

    } );

} );

/**
* Populates the EnviraGalleryImages Backbone collection, which comprises of a set of Envira Gallery Images
*
* Called when images are added, deleted, reordered or selected
*
* @global           EnviraGalleryImages     The backbone collection of images
* @param    bool    selected_only           Only populate collection with images the user has selected
*/
function EnviraGalleryImagesUpdate( selected_only ) {

    // Clear the collection
    EnviraGalleryImages.reset();

    // Iterate through the gallery images in the DOM, adding them to the collection
    var selector = 'ul#envira-gallery-output li.envira-gallery-image' + ( selected_only ? '.selected' : '' );

    jQuery( selector ).each( function() {
        // Build an EnviraGalleryImage Backbone Model from the JSON supplied in the element
        var envira_gallery_image = jQuery.parseJSON( jQuery( this ).attr( 'data-envira-gallery-image-model' ) );

        // Strip slashes from some fields
        envira_gallery_image.alt = EnviraGalleryStripslashes( envira_gallery_image.alt );

        // Add the model to the collection
        EnviraGalleryImages.add( new EnviraGalleryImage( envira_gallery_image ) );
    } );

    // Update the count in the UI
    jQuery( '#envira-gallery-main span.count' ).text( jQuery( 'ul#envira-gallery-output li.envira-gallery-image' ).length );

}

/**
* Strips slashes from the given string, which may have been added to escape certain characters
*
* @since 1.4.2.0
*
* @param    string  str     String
* @return   string          String without slashes
*/
function EnviraGalleryStripslashes( str ) {

    return (str + '').replace(/\\(.?)/g, function(s, n1) {
        switch (n1) {
            case '\\':
                return '\\';
            case '0':
                return '\u0000';
            case '':
                return '';
            default:
                return n1;
        }
    });

}

jQuery( document ).ready( function( $ ) {

	/**
    * Delete Multiple Images
    */
    $( document ).on( 'click', 'a.envira-gallery-images-delete', function( e ) {

        e.preventDefault();

        // Bail out if the user does not actually want to remove the image.
        var confirm_delete = confirm(envira_gallery_metabox.remove_multiple);
        if ( ! confirm_delete ) {
            return false;
        }

        // Build array of image attachment IDs
        var attach_ids = [];
        $( 'ul#envira-gallery-output > li.selected' ).each( function() {
            attach_ids.push( $( this ).attr( 'id' ) );
        } );

        // Send an AJAX request to delete the selected items from the Gallery
        var attach_id = $( this ).parent().attr( 'id' );
        $.ajax( {
            url:      envira_gallery_metabox.ajax,
            type:     'post',
            dataType: 'json',
            data: {
                action:        'envira_gallery_remove_images',
                attachment_ids:attach_ids,
                post_id:       envira_gallery_metabox.id,
                nonce:         envira_gallery_metabox.remove_nonce
            },
            success: function( response ) {
                // Remove each image
                $( 'ul#envira-gallery-output > li.selected' ).remove();

                // Hide Select Options
                $( 'nav.envira-select-options' ).fadeOut();

                // Refresh the modal view to ensure no items are still checked if they have been removed.
                $( '.envira-gallery-load-library' ).attr( 'data-envira-gallery-offset', 0 ).addClass( 'has-search' ).trigger( 'click' );

                // Repopulate the Envira Gallery Image Collection
                EnviraGalleryImagesUpdate( false );
            },
            error: function( xhr, textStatus, e ) {
                // Inject the error message into the tab settings area
                $( envira_gallery_output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
            }
        } );

    } );

    /**
    * Delete Single Image
    */
    $( document ).on( 'click', '#envira-gallery-main .envira-gallery-remove-image', function( e ) {
        
        e.preventDefault();

        // Bail out if the user does not actually want to remove the image.
        var confirm_delete = confirm( envira_gallery_metabox.remove );
        if ( ! confirm_delete ) {
            return;
        }

        // Send an AJAX request to delete the selected items from the Gallery
        var attach_id = $( this ).parent().attr( 'id' );
        $.ajax( {
            url:      envira_gallery_metabox.ajax,
            type:     'post',
            dataType: 'json',
            data: {
                action:        'envira_gallery_remove_image',
                attachment_id: attach_id,
                post_id:       envira_gallery_metabox.id,
                nonce:         envira_gallery_metabox.remove_nonce
            },
            success: function( response ) {
                $( '#' + attach_id ).fadeOut( 'normal', function() {
                    $( this ).remove();

                    // Refresh the modal view to ensure no items are still checked if they have been removed.
                    $( '.envira-gallery-load-library' ).attr( 'data-envira-gallery-offset', 0 ).addClass( 'has-search' ).trigger( 'click' );

                    // Repopulate the Envira Gallery Image Collection
                    EnviraGalleryImagesUpdate( false );
                } );
            },
            error: function( xhr, textStatus, e ) {
                // Inject the error message into the tab settings area
                $( envira_gallery_output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
            }
        } );
    } );

} );
/**
 * Creates and handles a wp.media instance for Envira Galleries, allowing
 * the user to insert images from the WordPress Media Library into their Gallery
 */
jQuery( document ).ready( function( $ ) {

    // Select Files from Other Sources
    $( 'a.envira-media-library' ).on( 'click', function( e ) {

        // Prevent default action
        e.preventDefault();

        // If the wp.media.frames.envira instance already exists, reopen it
        if ( wp.media.frames.envira ) {
            wp.media.frames.envira.open();
            return;
        } else {
            // Create the wp.media.frames.envira instance (one time)
            wp.media.frames.envira = wp.media( {
                frame: 'post',
                title:  wp.media.view.l10n.insertIntoPost,
                button: {
                    text: wp.media.view.l10n.insertIntoPost,
                },
                multiple: true
            } );
        }

        // Mark existing Gallery images as selected when the modal is opened
        wp.media.frames.envira.on( 'open', function() {
            // Get any previously selected images
            var selection = wp.media.frames.envira.state().get( 'selection' );

            // Get images that already exist in the gallery, and select each one in the modal
            $( 'ul#envira-gallery-output li' ).each( function() {
                var attachment = wp.media.attachment( $( this ).attr( 'id' ) );
                selection.add( attachment ? [ attachment ] : [] );
            } );
        } );

        // Insert into Gallery Button Clicked
        wp.media.frames.envira.on( 'insert', function( selection ) {

            // Get state
            var state = wp.media.frames.envira.state(),
                images = [];

            // Iterate through selected images, building an images array
            selection.each( function( attachment ) {
                // Get the chosen options for this image (size, alignment, link type, link URL)
                var display = state.display( attachment ).toJSON();

                // Change the image link parameter based on the "Link To" setting the user chose in the media view
                switch ( display.link ) {
                    case 'none':
                        // Because users cry when their images aren't linked, we need to actually set this to the attachment URL
                        attachment.set( 'link', attachment.get( 'url' ) );
                        break;
                    case 'file':
                        attachment.set( 'link', attachment.get( 'url' ) );
                        break;
                    case 'post':
                        // Already linked to post by default
                        break;
                    case 'custom':
                        attachment.set( 'link', display.linkUrl );
                        break;
                }

                // Add the image to the images array
                images.push( attachment.toJSON() );
            }, this );

            // Make visible the "items are being added"
            $( document ).find('.envira-progress-adding-images').css('display', 'block');

            // Send the ajax request with our data to be processed.
            $.post(
                envira_gallery_metabox.ajax,
                {
                    action:     'envira_gallery_insert_images',
                    nonce:      envira_gallery_metabox.insert_nonce,
                    post_id:    envira_gallery_metabox.id,
                    // make this a JSON string so we can send larger amounts of data (images), otherwise max is around 20 by default for most server configs
                    images:     JSON.stringify(images),
                },
                function( response ) {
                    // Response should be a JSON success with the HTML for the image grid
                    if ( response && response.success ) {
                        // Set the image grid to the HTML we received
                        $( '#envira-gallery-output' ).html( response.success );

                        // Repopulate the Envira Gallery Image Collection
                        EnviraGalleryImagesUpdate( false );

                        $( document ).find('.envira-progress-adding-images').css('display', 'none');
                    }
                },
                'json'
            );

        } );

        wp.media.frames.envira.open();
        // Remove the 'Create Gallery' left hand menu item in the modal, as we don't
        // want users inserting galleries!
        $( 'div.media-menu a.media-menu-item:nth-child(2)' ).addClass( 'hidden' );
        $( 'div.media-menu a.media-menu-item:nth-child(4)' ).addClass( 'hidden' );
        return;

    } );

} );
/**
 * Handles:
 * - Selection and deselection of media in an Envira Gallery
 * - Toggling edit / delete button states when media is selected / deselected,
 * - Toggling the media list / grid view
 * - Storing the user's preferences for the list / grid view
 */

 // Setup some vars
var envira_gallery_output = '#envira-gallery-output',
    envira_gallery_shift_key_pressed = false,
    envira_gallery_last_selected_image = false;

jQuery( document ).ready( function( $ ) {

    // Toggle List / Grid View
    $( document ).on( 'click', 'nav.envira-tab-options a', function( e ) {

        e.preventDefault();

        // Get the view the user has chosen
        var envira_tab_nav          = $( this ).closest( '.envira-tab-options' ),
            envira_tab_view         = $( this ).data( 'view' ),
            envira_tab_view_style   = $( this ).data( 'view-style' );

        // If this view style is already displayed, don't do anything
        if ( $( envira_tab_view ).hasClass( envira_tab_view_style ) ) {
            return;
        }

        // Update the view class
        $( envira_tab_view ).removeClass( 'list' ).removeClass( 'grid' ).addClass( envira_tab_view_style );

        // Mark the current view icon as selected
        $( 'a', envira_tab_nav ).removeClass( 'selected' );
        $( this ).addClass( 'selected' );

        // Send an AJAX request to store this user's preference for the view
        // This means when they add or edit any other Gallery, the image view will default to this setting
        $.ajax( {
            url:      envira_gallery_metabox.ajax,
            type:     'post',
            dataType: 'json',
            data: {
                action:  'envira_gallery_set_user_setting',
                name:    'envira_gallery_image_view',
                value:   envira_tab_view_style,
                nonce:   envira_gallery_metabox.set_user_setting_nonce
            },
            success: function( response ) {
            },
            error: function( xhr, textStatus, e ) {
                // Inject the error message into the tab settings area
                $( envira_gallery_output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
            }
        } );

    } );

    // Toggle Select All / Deselect All
    $( document ).on( 'change', 'nav.envira-tab-options input', function( e ) {

        if ( $( this ).prop( 'checked' ) ) {
            $( 'li', $( envira_gallery_output ) ).addClass( 'selected' );
            $( 'nav.envira-select-options' ).fadeIn();
        } else {
            $( 'li', $( envira_gallery_output ) ).removeClass( 'selected' );
            $( 'nav.envira-select-options' ).fadeOut();
        }

    } );
	
    // Enable sortable functionality on images
	envira_gallery_sortable( $ );

    // When the Gallery Type is changed, reinitialise the sortable
    $( document ).on( 'enviraGalleryType', function() {

        if ( $( envira_gallery_output ).length > 0 ) {
            // Re-enable sortable functionality on images, now we're viewing the default gallery type
            envira_gallery_sortable( $ );
        }
        
    } );

    // Select / deselect images
    $( document ).on( 'click', 'ul#envira-gallery-output li.envira-gallery-image > img, li.envira-gallery-image > div, li.envira-gallery-image > a.check', function( e ) {

        // Prevent default action
        e.preventDefault();

        // Get the selected gallery item
        var gallery_item = $( this ).parent();

        if ( $( gallery_item ).hasClass( 'selected' ) ) {
            $( gallery_item ).removeClass( 'selected' );
            envira_gallery_last_selected_image = false;
        } else {
            
            // If the shift key is being held down, and there's another image selected, select every image between this clicked image
            // and the other selected image
            if ( envira_gallery_shift_key_pressed && envira_gallery_last_selected_image !== false ) {
                // Get index of the selected image and the last image
                var start_index = $( 'ul#envira-gallery-output li' ).index( $( envira_gallery_last_selected_image ) ),
                    end_index = $( 'ul#envira-gallery-output li' ).index( $( gallery_item ) ),
                    i = 0;

                // Select images within the range
                if ( start_index < end_index ) {
                    for ( i = start_index; i <= end_index; i++ ) {
                        $( 'ul#envira-gallery-output li:eq( ' + i + ')' ).addClass( 'selected' );
                    }
                } else {
                    for ( i = end_index; i <= start_index; i++ ) {
                        $( 'ul#envira-gallery-output li:eq( ' + i + ')' ).addClass( 'selected' );
                    }
                }
            }

            // Select the clicked image
            $( gallery_item ).addClass( 'selected' );
            envira_gallery_last_selected_image = $( gallery_item );

        }
        
        // Show/hide buttons depending on whether
        // any galleries have been selected
        if ( $( 'ul#envira-gallery-output > li.selected' ).length > 0 ) {
            $( 'nav.envira-select-options' ).fadeIn();
        } else {
            $( 'nav.envira-select-options' ).fadeOut();
        }
    } );

    // Determine whether the shift key is pressed or not
    $( document ).on( 'keyup keydown', function( e ) {
        envira_gallery_shift_key_pressed = e.shiftKey;
    } );

} );

/**
 * Enables sortable functionality on a grid of Envira Gallery Images
 *
 * @since 1.5.0
 */
function envira_gallery_sortable( $ ) {

    // Add sortable support to Envira Gallery Media items
    $( envira_gallery_output ).sortable( {
        containment: envira_gallery_output,
        items: 'li',
        cursor: 'move',
        forcePlaceholderSize: true,
        placeholder: 'dropzone',
        helper: function( e, item ) {

            // Basically, if you grab an unhighlighted item to drag, it will deselect (unhighlight) everything else
            if ( ! item.hasClass( 'selected' ) ) {
                item.addClass( 'selected' ).siblings().removeClass( 'selected' );
            }
            
            // Clone the selected items into an array
            var elements = item.parent().children( '.selected' ).clone();
            
            // Add a property to `item` called 'multidrag` that contains the 
            // selected items, then remove the selected items from the source list
            item.data( 'multidrag', elements ).siblings( '.selected' ).remove();
            
            // Now the selected items exist in memory, attached to the `item`,
            // so we can access them later when we get to the `stop()` callback
            
            // Create the helper
            var helper = $( '<li/>' );
            return helper.append( elements );

        },
        stop: function( e, ui ) {
            // Remove the helper so we just display the sorted items
            var elements = ui.item.data( 'multidrag' );
            ui.item.after(elements).remove();

            // Remove the selected class from everything
            $( 'li.selected', $( envira_gallery_output ) ).removeClass( 'selected' );
            
            // Send AJAX request to store the new sort order
            $.ajax( {
                url:      envira_gallery_metabox.ajax,
                type:     'post',
                async:    true,
                cache:    false,
                dataType: 'json',
                data: {
                    action:  'envira_gallery_sort_images',
                    order:   $( envira_gallery_output ).sortable( 'toArray' ).toString(),
                    post_id: envira_gallery_metabox.id,
                    nonce:   envira_gallery_metabox.sort
                },
                success: function( response ) {
                    // Repopulate the Envira Gallery Backbone Image Collection
                    EnviraGalleryImagesUpdate( false );
                    return;
                },
                error: function( xhr, textStatus, e ) {
                    // Inject the error message into the tab settings area
                    $( envira_gallery_output ).before( '<div class="error"><p>' + textStatus.responseText + '</p></div>' );
                }
            } );
        }
    } );

}
/**
 * Hooks into the global Plupload instance ('uploader'), which is set when includes/admin/metaboxes.php calls media_form()
 * We hook into this global instance and apply our own changes during and after the upload.
 *
 * @since 1.3.1.3
 */
(function( $ ) {
    $(function() {

        if ( typeof uploader !== 'undefined' ) {

            // Change "Select Files" button in the pluploader to "Select Files from Your Computer"
            $( 'input#plupload-browse-button' ).val( envira_gallery_metabox.uploader_files_computer );

            // Set a custom progress bar
            var envira_bar      = $( '#envira-gallery .envira-progress-bar' ),
                envira_progress = $( '#envira-gallery .envira-progress-bar div.envira-progress-bar-inner' ),
                envira_status   = $( '#envira-gallery .envira-progress-bar div.envira-progress-bar-status' ),
                envira_output   = $( '#envira-gallery-output' ),
                envira_error    = $( '#envira-gallery-upload-error' ),
                ENVIRA_LITE_FILE_count = 0;

            // Uploader has initialized
            uploader.bind( 'Init', function( up ) {

                // Fade in the uploader, as it's hidden with CSS so the user doesn't see elements reposition on screen and look messy.
                $( '#drag-drop-area' ).fadeIn();
                $( 'a.envira-media-library.button' ).fadeIn();

            } );

            // Files Added for Uploading
            uploader.bind( 'FilesAdded', function ( up, files ) {

                // Hide any existing errors
                $( envira_error ).html( '' );

                // Get the number of files to be uploaded
                ENVIRA_LITE_FILE_count = files.length;

                // Set the status text, to tell the user what's happening
                $( '.uploading .current', $( envira_status ) ).text( '1' );
                $( '.uploading .total', $( envira_status ) ).text( ENVIRA_LITE_FILE_count );
                $( '.uploading', $( envira_status ) ).show();
                $( '.done', $( envira_status ) ).hide();

                // Fade in the upload progress bar
                $( envira_bar ).fadeIn( "fast", function() {
                    $( 'p.max-upload-size' ).css('padding-top', '10px');
                });



            } );

            // File Uploading - show progress bar
            uploader.bind( 'UploadProgress', function( up, file ) {

                // Update the status text
                $( '.uploading .current', $( envira_status ) ).text( ( ENVIRA_LITE_FILE_count - up.total.queued ) + 1 );

                // Update the progress bar
                $( envira_progress ).css({
                    'width': up.total.percent + '%'
                });

            });

            // File Uploaded - AJAX call to process image and add to screen.
            uploader.bind( 'FileUploaded', function( up, file, info ) {

                // AJAX call to Envira to store the newly uploaded image in the meta against this Gallery
                $.post(
                    envira_gallery_metabox.ajax,
                    {
                        action:  'envira_gallery_load_image',
                        nonce:   envira_gallery_metabox.load_image,
                        id:      info.response,
                        post_id: envira_gallery_metabox.id
                    },
                    function(res){
                        // Prepend or append the new image to the existing grid of images,
                        // depending on the media_position setting
                        switch ( envira_gallery_metabox.media_position ) {
                            case 'before':
                                $(envira_output).prepend(res);
                                break;
                            case 'after':
                            default:
                                $(envira_output).append(res);
                                break;
                        }

                        // Repopulate the Envira Gallery Image Collection
                        EnviraGalleryImagesUpdate( false );

                    },
                    'json'
                );
            });

            // Files Uploaded
            uploader.bind( 'UploadComplete', function() {

                // Update status
                $( '.uploading', $( envira_status ) ).hide();
                $( '.done', $( envira_status ) ).show();

                // Hide Progress Bar
                setTimeout( function() {
                    $( envira_bar ).fadeOut( "fast", function() {
                        $( 'p.max-upload-size' ).css('padding-top', '0');
                    });
                }, 1000 );

            });

            // File Upload Error
            uploader.bind('Error', function(up, err) {

                // Show message
                $('#envira-gallery-upload-error').html( '<div class="error fade"><p>' + err.file.name + ': ' + err.message + '</p></div>' );
                up.refresh();

            });

        }

    });
})( jQuery );
/**
 * Handles:
 * - Inline Video Help
 *
 * @since 1.5.0
 */

// Setup vars
var envira_video_link       = 'p.envira-intro a.envira-video',
    envira_close_video_link = 'a.envira-video-close';

jQuery( document ).ready( function( $ ) {
    /**
    * Display Video Inline on Video Link Click
    */
    $( document ).on( 'click', envira_video_link, function( e ) {

        // Prevent default action
        e.preventDefault();

        // Get the video URL
        var envira_video_url = $( this ).attr( 'href' );

        // Check if the video has the autoplay parameter included
        // If not, add it now - this will play the video when it's inserted to the iframe.
        if ( envira_video_url.search( 'autoplay=1' ) == -1 ) {
            if ( envira_video_url.search( 'rel=' ) == -1 ) {
                envira_video_url += '?rel=0&autoplay=1';
            } else {
                envira_video_url += '&autoplay=1';
            }
        }

        // Destroy any other instances of Envira Video iframes
        $( 'div.envira-video-help' ).remove();

        // Get the intro paragraph
        var envira_video_paragraph = $( this ).closest( 'p.envira-intro' );

        // Load the video below the intro paragraph on the current tab
        $( envira_video_paragraph ).append( '<div class="envira-video-help"><iframe src="' + envira_video_url + '" /><a href="#" class="envira-video-close dashicons dashicons-no"></a></div>' );

    } );

    /**
    * Destroy Video when closed
    */
    $( document ).on( 'click', envira_close_video_link, function( e ) {
        
        e.preventDefault();
        
        $( this ).closest( '.envira-video-help' ).remove();

    } );

} );