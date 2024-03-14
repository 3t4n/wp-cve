/**
 * Returns the image file url for any Image Select setting
 *
 * Displays the media uploader for selecting an image.
 * http://code.tutsplus.com/tutorials/getting-started-with-the-wordpress-media-uploader--cms-22011
 *
 */

jQuery(document).ready(function($) {
    'use strict';

    /**
     * Define Variables
     */
    var file_frame;
    var image_data;
    var image_url_id;

    /**
     * jQuery Event handlers
     */

    //Button / trash icon to remove the url and image
    $('.my_plugin_image-select_clear').click(function(){

        var button = $(this);
        var image_url_id = button.attr('id').replace('_clear', '');
        setImageValue(image_url_id, '');

    });

    /**
     * On Image Select click  - Class: image_select_button + button
     */
    $('.my_plugin_image-select_button.button').click(function() {

          var button = $(this);
        image_url_id = button.attr('id').replace('_button', '');

        // Display the media uploader
        openMediaUploader();

    });


    function openMediaUploader() {

        var image_url;

        /**
         * If an instance of file_frame already exists, then we can open it
         * rather than creating a new instance.
         */
        if (undefined !== file_frame) {
            file_frame.open();
            return;
        }

        /**
         * If we're this far, then an instance does not exist, so we need to
         * create our own.
         *
         * Here, use the wp.media library to define the settings of the Media
         * Uploader. We're opting to use the 'post' frame which is a template
         * defined in WordPress core and are initializing the file frame
         * with the 'insert' state.
         *
         * We're also not allowing the user to select more than one image.
         */
        file_frame = wp.media.frames.file_frame = wp.media({
            frame: 'post',
            state: 'insert',
            title: 'Select Image',
            multiple: false
        });

        /**
         * Setup an event handler for what to do when an image has been
         * selected i.e. on 'insert'
         *
         * Since we're using the 'view' state when initializing
         * the file_frame, we need to make sure that the handler is attached
         * to the insert event.
         */
        file_frame.on('insert', function () {

            var selection = file_frame.state().get('selection');

            //Get image details as attachment
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();

                // Get the URL of the selected image
                image_url = attachment.url;

                setImageValue(image_url_id, image_url);

            });
        });

        // Now display the actual file_frame
        file_frame.open();

    }

    /**
     * Function to Set/Get Image Url values
     */
    function setImageValue(image_url_id, image_url) {

        var image_preview_id = image_url_id + '_preview';

        //Set the url value
        jQuery("#" + image_url_id).val(image_url);

        //Wrap the image url in url() to make the preview visible
        var display_class = (image_url.length > 0) ? '' : 'my_plugin_hidden';
        image_url = (image_url.length > 0) ? 'url(' + image_url + ')' : '';

        //Set the preview image - include .change() to trigger the change event to manage user leaving page before saving etc.
        jQuery('#' + image_preview_id).css({'background-image': image_url}).removeClass('my_plugin_hidden').addClass(display_class).change();

    }
});


