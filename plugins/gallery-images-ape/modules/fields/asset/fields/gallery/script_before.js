/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */


(function($) {
	
    var frame,
        idsList,
        fieldObj,
        idsArray,
        frameState = 'gallery-library',
        previewDiv = document.getElementById('ape_gallery_images_preview'),
        imagesButton = document.getElementsByClassName('wpapeGalleryFieldImagesButton');

    if (imagesButton.length == 0) {
        console.log("button not found ");
        return;
    }

    imagesButton = imagesButton[0];

    fieldObj = document.getElementById(imagesButton.getAttribute('data-id'));

    idsList = fieldObj.value;

    wpapeGalleryUpdateImages(idsList);

    idsArray = idsList == '' ? [] : idsList.split(',');

    previewDiv.addEventListener('click', function(event) {
        event.preventDefault();
        if (!event.target.matches('img')) return;
        imagesButton.click();
    });

    var clickButtonManangeImages = function(buttonObj) {
        
        if (frame) {
            frame.open();
            return;
        }
        if (idsArray.length > 0) frameState = 'gallery-edit';

        frame = new wp.media.view.emWpGallery({
            multiple: true, // Enable/disable multiple select    
            state: frameState, //'gallery-edit', //gallery-library
            library: {
                order: 'ASC', // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',                            
                orderby: 'title', // 'id', 'post__in', 'menuOrder' ]
                type: 'image', // mime type. e.g. 'image', 'image/jpeg'
                search: null, // Searches the attachment title.
                uploadedTo: null, // Attached to a specific post (ID).
                multiple: true,
            },

        });

        /*frame.on('activate', function() {});*/

        frame.on('ready', function() {
            //console.log(' event ready ');

            if (idsArray.length == 0) return;
            var library = frame.state().get('library');
            var all_attachments = wp.media.query({ post__in: idsArray }).more().then();            
            idsArray.forEach(function(id) {
                   var itemObj = new wp.media.attachment(id);
                   itemObj.fetch();
                  // var itemObj = wp.media.attachment(id);
                   if( jQuery !=undefined ) itemObj = jQuery.extend(true, wp.media.attachment(id), { attributes: { sizes: { medium: { url: apeGalleryFieldGallery.iconUrl } } } } );                   
                   library.add(itemObj);
            });
        });

        // Fires when the frame's $el is appended to its DOM container.
        // @see media.view.Modal.attach()
        /*frame.on('attach', function() {});*/

        // Fires when the modal opens (becomes visible).
        // @see media.view.Modal.open()
        frame.on('open', function() {
            //console.log(' event open');
            if (idsArray.length == 0 || frameState == 'gallery-edit') return;

            //console.log('set state');
            frame.setState('gallery-edit');
            frame.options.state = 'gallery-edit';
            frameState = 'gallery-edit';
        });

        // Fires when the modal closes via the escape key.
        // @see media.view.Modal.close()
        /*frame.on('escape', function() {});*/

        // Fires when the modal closes.
        // @see media.view.Modal.close()
        /*frame.on('close', function() {});*/

        // Fires when a user has selected attachment(s) and clicked the insert button.
        // @see media.view.MediaFrame.Post.mainInsertToolbar()
        frame.on('insert', function() {
            var selectionCollection = frame.state().get('selection');
            console.log(selectionCollection);
        });

        frame.on('update', function(newSelection) {
            var state = frame.state(),
                resultSelection;

            resultSelection = newSelection || state.get('selection');

            if (!resultSelection) {
                console.log('return selection');
                return;
            }

            //if (resultSelection.gallery) {
            var idsArrayObj = resultSelection.toJSON();
            if (idsArrayObj.length == 0) return;
            idsArray = [];
            for (var i = 0; i < idsArrayObj.length; i++) {
                idsArray.push(idsArrayObj[i].id);
            }
            idsList = idsArray.join(',');
            console.log('idsList', idsList);
            wpapeGalleryUpdateImages(idsList);
            fieldObj.value = idsList;
            console.log('gallery array', idsArray);
            //}
        });

        // Fires when a state activates.
        /*frame.on('activate', function() {});*/

        // Fires when a mode is deactivated on a region.
        /*frame.on('{region}:deactivate', function() {});*/
        // and a more specific event including the mode.
        /*frame.on('{region}:deactivate:{mode}', function() {});*/

        // Fires when a region is ready for its view to be created.
        /*frame.on('{region}:create', function() {});*/
        // and a more specific event including the mode.
        /*frame.on('{region}:create:{mode}', function() {});*/

        // Fires when a region is ready for its view to be rendered.
        /*frame.on('{region}:render', function() {});*/
        // and a more specific event including the mode.
        /*frame.on('{region}:render:{mode}', function() {});*/

        // Fires when a new mode is activated (after it has been rendered) on a region.
/*        frame.on('{region}:activate', function() {});*/
        // and a more specific event including the mode.
        /*frame.on('{region}:activate:{mode}', function() {});*/

        // Get an object representing the current state.
        //frame.state();

        // Get an object representing the previous state.
        //frame.lastState();

        // Open the modal.
        frame.open();
    }

    imagesButton.addEventListener("click", clickButtonManangeImages);

    function wpapeGalleryUpdateImages(idString) {
        var $previewDiv = jQuery(previewDiv);
        var data = {
            'action': 'wpape_gallery_get_images_from_ids',
            'idstring': idString
        };
        jQuery.post(ajaxurl, data, function(response) {
            $previewDiv.html(response);
            var imgCount = $previewDiv.find('img').length;
            if (imgCount <= 10) {
                $previewDiv.addClass('items10');
            } else if (imgCount <= 20) {
                $previewDiv.addClass('items20');
            } else if (imgCount <= 30) {
                $previewDiv.addClass('items30');
            } else {
                $previewDiv.addClass('items50');
            }            
            //$previewDiv.sortable({});
        });
    }


})(jQuery);