var EasyImageCollage = EasyImageCollage || {};

/**
 * Variables
 */
EasyImageCollage.spinner = '<div class="eic-spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
EasyImageCollage.file_frame = undefined;
EasyImageCollage.editing_image = undefined;
EasyImageCollage.manipulating_image = undefined;
EasyImageCollage.editing_grid = {};
EasyImageCollage.callback = false;
EasyImageCollage.lightbox_settings = {
    namespace: 'eic-lightbox',
    closeOnClick: false,
    closeOnEsc: false,
    afterOpen: function() {
        var lightbox = jQuery('.eic-lightbox');
        var gridAlign = EasyImageCollage.editing_grid.properties.align;
        var gridWidth = EasyImageCollage.editing_grid.properties.width;
        var gridRatio = EasyImageCollage.editing_grid.properties.ratio;
        var borderWidth = EasyImageCollage.editing_grid.properties.borderWidth;
        var borderColor = EasyImageCollage.editing_grid.properties.borderColor;

        // Alignment
        lightbox.find('#grid-align')
            .val(gridAlign)
            .on('change', function() {
            EasyImageCollage.editing_grid.properties.align = jQuery(this).val();
        });


        // Border color - init and bind event
        jQuery('.eic-lightbox #border-color')
            .val(borderColor)
            .wpColorPicker({
                change: function () {
                    EasyImageCollage.editing_grid.properties.borderColor = jQuery(this).wpColorPicker('color');
                    EasyImageCollage.redrawBorders();
                }
            })
        ;

        // Grid width - init and bind event
        jQuery('.eic-lightbox #grid-width')
            .val(gridWidth)
            .simpleSlider({
                range: [150,2000],
                step: 1,
                snap: true
            }).bind('slider:changed', function (event, data) {
                jQuery('.eic-lightbox #grid-width-value').html(''+data.value);
                EasyImageCollage.editing_grid.properties.width = data.value;
                EasyImageCollage.redrawGrid();
            })
        ;
        jQuery('.eic-lightbox #grid-width-value').html(''+gridWidth);

        // Grid width finetuning
        jQuery('.eic-lightbox #grid-width-minus').bind('click', function() {
            var val = parseInt(jQuery('.eic-lightbox #grid-width').val());
            jQuery('.eic-lightbox #grid-width').simpleSlider('setValue', val-1);
        });
        jQuery('.eic-lightbox #grid-width-plus').bind('click', function() {
            var val = parseInt(jQuery('.eic-lightbox #grid-width').val());
            jQuery('.eic-lightbox #grid-width').simpleSlider('setValue', val+1);
        });

        // Grid ratio - init and bind event
        jQuery('.eic-lightbox #grid-ratio')
            .val(gridRatio)
            .simpleSlider({
                range: [0.25,4],
                step: 0.05,
                snap: true
            }).bind('slider:changed', function (event, data) {
                var ratio = parseFloat(data.value.toFixed(2));
                jQuery('.eic-lightbox #grid-ratio-value').html(''+ratio);
                EasyImageCollage.editing_grid.properties.ratio = ratio;
                EasyImageCollage.redrawGrid();
            })
        ;
        jQuery('.eic-lightbox #grid-ratio-value').html(''+gridRatio);

        // Border width - init and bind event
        jQuery('.eic-lightbox #border-width')
            .val(borderWidth)
            .simpleSlider({
                range: [0,20],
                step: 1,
                snap: true
            }).bind('slider:changed', function (event, data) {
                jQuery('.eic-lightbox #border-width-value').html(''+data.value*2);
                EasyImageCollage.editing_grid.properties.borderWidth = data.value;
                EasyImageCollage.redrawBorders();
            })
        ;
        jQuery('.eic-lightbox #border-width-value').html(''+borderWidth*2);

        // Border Adjustments
        jQuery('.eic-lightbox #border-change').on('change', function() {
            if(jQuery(this).is(':checked')) {
                jQuery('.eic-lightbox .eic-divider').show();
                if (typeof EasyImageCollage.redrawDividers !== 'function') {
                    jQuery('.eic-lightbox .eic-editing .eic-premium-only').show();
                }
            } else {
                jQuery('.eic-lightbox .eic-divider').hide();
                jQuery('.eic-lightbox .eic-editing .eic-premium-only').hide();
            }
        });

        // Show Image size
        jQuery('.eic-lightbox #image-size').on('change', function() {
            if(jQuery(this).is(':checked')) {
                jQuery('.eic-lightbox .eic-image-size').css('display','inline-block');
                if (typeof EasyImageCollage.recalculateSizes !== 'function') {
                    jQuery('.eic-lightbox .eic-editing .eic-premium-only').show();
                }
            } else {
                jQuery('.eic-lightbox .eic-image-size').css('display','none');
                jQuery('.eic-lightbox .eic-editing .eic-premium-only').hide();
            }
        });
    }
};

/** Variables from PHP
 *
 */
EasyImageCollage.grids = {};
EasyImageCollage.default_grid = {};

/**
 * Front end events
 */
jQuery(document).ready(function($) {
    if(typeof eic_admin_grids !== 'undefined' && typeof eic_default_grid !== 'undefined') {
        EasyImageCollage.grids = eic_admin_grids;
        EasyImageCollage.default_grid = eic_default_grid;

        // Add new button
        $('#eic-button').featherlight($('.eic-modal'), EasyImageCollage.lightbox_settings);
        $('#eic-button').click(function() {
            EasyImageCollage.setActivePage('layouts');
            EasyImageCollage.newGrid();
        });

        // Choose layout
        $('.eic-layouts .eic-frame').click(function() {
            var layout = $(this);
            if(layout.hasClass('eic-frame-custom')) {
                EasyImageCollage.setActivePage('creating');
                if (typeof EasyImageCollage.customLayout == 'function') {
                    EasyImageCollage.customLayout();
                }
            } else {
                EasyImageCollage.btnPickLayout(layout.clone(), false);
            }
        });
    }
});

/**
 * Front end control buttons
 */
EasyImageCollage.btnCreateGrid = function(id, callback) {
    EasyImageCollage.callback = callback;

    jQuery.featherlight(jQuery('.eic-modal'), EasyImageCollage.lightbox_settings);
    EasyImageCollage.setActivePage('layouts');
    EasyImageCollage.newGrid();
};

EasyImageCollage.btnEditGrid = function(id, callback) {
    // Set editing grid
    EasyImageCollage.editing_grid = EasyImageCollage.grids[id];
    EasyImageCollage.callback = callback;
    var grid = EasyImageCollage.editing_grid;

    // Open lightbox
    jQuery.featherlight(jQuery('.eic-modal'), EasyImageCollage.lightbox_settings);

    // Load grid layout
    var layout_name = (typeof grid.layout === 'string' || grid.layout instanceof String) ? grid.layout : 'custom-' + id,
        layout = jQuery('.eic-lightbox .eic-layouts .eic-frame-' + layout_name).clone();
    jQuery('.eic-editing .eic-container').html(layout);

    // Load images in grid
    if(grid['images'] !== undefined) {
        for(var i = 0; i < grid['images'].length; i++) {
            var image = grid['images'][i];

            if(image) EasyImageCollage.setImageFrontend(image);
        }
    }

    // Go to edit grid page
    EasyImageCollage.setActivePage('editing');
};

EasyImageCollage.btnChooseLayout = function() {
    EasyImageCollage.setActivePage('layouts');
};

EasyImageCollage.btnPickLayout = function(layout_element, layout) {
    jQuery('.eic-editing .eic-container').html(layout_element);

    var grid = EasyImageCollage.editing_grid;

    grid['layout'] = layout ? layout : layout_element.data('layout-name');
    grid['dividers'] = [];

    EasyImageCollage.setActivePage('editing');

    for(var i = 0; i < grid['images'].length; i++) {
        var image = grid['images'][i];

        if(image) {
            var attachment = {
                id: image.attachment_id,
                url: image.attachment_url,
                width: image.attachment_width,
                height: image.attachment_height,
                thumb: image.attachment_thumb,
                custom_link: image.custom_link,
                custom_link_new_tab: image.custom_link_new_tab,
                custom_link_nofollow: image.custom_link_nofollow,
                custom_caption: image.custom_caption
            };
            EasyImageCollage.setImage(i, attachment);
        }
    }
};

EasyImageCollage.btnImage = function(id) {
    EasyImageCollage.editing_image = id;
    EasyImageCollage.openMediaModal();
};

EasyImageCollage.btnManipulate = function(id) {
    EasyImageCollage.manipulating_image = id;
    EasyImageCollage.setActivePage('manipulating');
    if (typeof EasyImageCollage.loadImageManipulate == 'function') {
        EasyImageCollage.loadImageManipulate(id);
    }
};

EasyImageCollage.btnLink = function(id) {
    EasyImageCollage.setActivePage('links');
    if (typeof EasyImageCollage.loadCustomLinks == 'function') {
        EasyImageCollage.loadCustomLinks(id);
    }
};

EasyImageCollage.btnCaption = function(id) {
    EasyImageCollage.setActivePage('captions');
    if (typeof EasyImageCollage.loadCaptions == 'function') {
        EasyImageCollage.loadCaptions(id);
    }
};

EasyImageCollage.btnFinish = function() {
    var grid = EasyImageCollage.editing_grid;

    var data = {
        action: 'image_collage',
        security: eic_admin.nonce,
        grid: grid
    };

    var new_grid = grid.id == 0 ? true : false;

    jQuery.post(eic_admin.ajaxurl, data, function(grid_id) {
        var callback = EasyImageCollage.callback;

        if(callback) {
            // Gutenberg.
            callback(grid_id);
        } else {
            // Classic Editor.
            if(new_grid) {
                EasyImageCollage.addShortcodeToEditor(grid_id);
            } else {
                tinyMCE.activeEditor.setContent(tinyMCE.activeEditor.getContent());
            }
        }

        grid.id = grid_id;
        EasyImageCollage.grids[grid_id] = jQuery.extend(true, {}, grid);
        jQuery.featherlight.close();
    }, 'json');
};

/**
 * Other functions
 */
EasyImageCollage.newGrid = function() {
    EasyImageCollage.editing_grid = jQuery.extend(true, {}, EasyImageCollage.default_grid);
};

EasyImageCollage.openMediaModal = function() {

    // If the media frame already exists, reopen it.
    if ( EasyImageCollage.file_frame ) {
        EasyImageCollage.file_frame.open();
        return;
    }

    // Create the media frame.
    EasyImageCollage.file_frame = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false
    });

    // When an image is selected, run a callback.
    EasyImageCollage.file_frame.on( 'select', function() {
        // We set multiple to false so only get one image from the uploader
        attachment = EasyImageCollage.file_frame.state().get('selection').first().toJSON();

        if( EasyImageCollage.editing_image !== undefined ) {
            // Get thumbnail
            if(attachment.sizes.medium !== undefined && attachment.sizes.medium.url !== undefined) {
                attachment.thumb = attachment.sizes.medium.url;
            }

            // Get auto caption
            switch(eic_admin.captions_autofill) {
                case 'caption':
                    attachment.custom_caption = attachment.caption;
                    break;
                case 'title':
                    attachment.custom_caption = attachment.title;
                    break;
                case 'alt':
                    attachment.custom_caption = attachment.alt;
                    break;
                default:
                    attachment.custom_caption = '';
            }

            // Set selected image
            EasyImageCollage.setImage(EasyImageCollage.editing_image, attachment);
            EasyImageCollage.editing_image = undefined;
        }
    });

    // Finally, open the modal
    EasyImageCollage.file_frame.open();
};

EasyImageCollage.setImage = function(id, attachment) {
    var image_element = jQuery('.eic-lightbox .eic-editing .eic-image-' + id);

    if(image_element.length !== 0) {
        image = EasyImageCollage.getImageProperties(id, attachment);
        EasyImageCollage.editing_grid['images'][id] = image;
        EasyImageCollage.redrawImages();
    }

};

EasyImageCollage.getImageProperties = function(id, attachment) {
    var image_element = jQuery('.eic-lightbox .eic-editing .eic-image-' + id);

    if(image_element.length !== 0) {
        var total_border_width = 4 * parseInt(EasyImageCollage.editing_grid['properties']['borderWidth']);

        // Calculate size and position
        var frame_width = image_element.innerWidth() + total_border_width;
        var frame_height = image_element.innerHeight() + total_border_width;
        var frame_ratio = frame_width / frame_height;
        var image_ratio = attachment.width / attachment.height;

        var bg_width = frame_width;
        var bg_height = frame_width / image_ratio;
        var bg_pos_x = 0;
        var bg_pos_y = -(bg_height - frame_height) / 2; // Center vertically

        if(frame_ratio < image_ratio) {
            bg_width = frame_height * image_ratio;
            bg_height = frame_height;
            bg_pos_x = -(bg_width - frame_width) / 2; // Center horizontally
            bg_pos_y = 0;
        }

        if(attachment.thumb == undefined) {
            attachment.thumb = attachment.url;
        }

        return {
            id: id,
            attachment_id: attachment.id,
            attachment_url: attachment.url,
            attachment_width: attachment.width,
            attachment_height: attachment.height,
            attachment_thumb: attachment.thumb,
            custom_link: attachment.custom_link,
            custom_link_new_tab: attachment.custom_link_new_tab,
            custom_link_nofollow: attachment.custom_link_nofollow,
            custom_caption: attachment.custom_caption,
            size_x: bg_width,
            size_y: bg_height,
            pos_x: bg_pos_x,
            pos_y: bg_pos_y
        };
    }
    return undefined;
};

EasyImageCollage.setImageFrontend = function(image) {
    var image_element = jQuery('.eic-lightbox .eic-editing .eic-image-' + image.id);

    // Element styling
    image_element.addClass('has-image');
    image_element
        .css('background-image', 'url("'+image.attachment_url+'")')
        .css('background-size', '' + image.size_x + 'px ' + image.size_y + 'px')
        .css('background-position', '' + image.pos_x + 'px ' + image.pos_y + 'px')
    ;

    // Handle move
    EasyImageCollage.handleImageMove(image);
};

EasyImageCollage.handleImageMove = function(image) {
    var image_element = jQuery('.eic-lightbox .eic-editing .eic-image-' + image.id);

    image_element.on('mousedown touchstart', function(e) {
        if (e.target !== image_element[0]) return;
        e.preventDefault();

        if (e.originalEvent.touches) {
            EasyImageCollage.modifyEventForTouch(e);
        } else if (e.which !== 1) {
            return;
        }

        var x0 = e.clientX,
            y0 = e.clientY,
            size = image_element.css('background-size').match(/(-?\d+).*?\s(-?\d+)/),
            size_x = size[1],
            size_y = size[2],
            min_x = image_element.innerWidth() - size_x,
            min_y = image_element.innerHeight() - size_y,
            backgroundPos = image_element.css('background-position').split(" "),
            pos_x = parseInt(backgroundPos[0]),
            pos_y = parseInt(backgroundPos[1]);

        jQuery(window).on('mousemove touchmove', function(e) {
            e.preventDefault();

            if (e.originalEvent.touches) {
                EasyImageCollage.modifyEventForTouch(e);
            }

            var x = e.clientX,
                y = e.clientY;

            // New position
            pos_x = pos_x+x-x0;
            pos_y = pos_y+y-y0;

            // Check bounds
            pos_x = pos_x < min_x ? min_x : ( pos_x > 0 ? 0 : pos_x );
            pos_y = pos_y < min_y ? min_y : ( pos_y > 0 ? 0 : pos_y );

            // New starting point for drag
            x0 = x;
            y0 = y;

            image_element
                .css('background-position', '' + pos_x + 'px ' + pos_y + 'px')
        });

        jQuery(window).on('mouseup touchend', function() {
            // Update new image position

            var backgroundPos = image_element.css('background-position').split(" "),
                pos_x = parseInt(backgroundPos[0]),
                pos_y = parseInt(backgroundPos[1]);

            image.pos_x = pos_x;
            image.pos_y = pos_y;

            // Remove event handlers
            jQuery(window).off('mousemove touchmove');
            jQuery(window).off('mouseup touchend');
        });
    });
};

/**
 * Helper functions
 */
EasyImageCollage.redrawBorders = function() {
    var borderWidth = EasyImageCollage.editing_grid.properties.borderWidth;
    var borderColor = EasyImageCollage.editing_grid.properties.borderColor;

    jQuery('.eic-lightbox .eic-editing .eic-frame')
        .css('border', borderWidth + 'px solid ' + borderColor)
        .find('.eic-image')
        .css('border', borderWidth + 'px solid ' + borderColor);

    EasyImageCollage.redrawImages();
    if (typeof EasyImageCollage.recalculateSizes == 'function') {
        EasyImageCollage.recalculateSizes();
    }
};

EasyImageCollage.redrawGrid = function() {
    var width = EasyImageCollage.editing_grid.properties.width,
        ratio = EasyImageCollage.editing_grid.properties.ratio;

    var height = parseInt(width/ratio);

    jQuery('.eic-lightbox .eic-editing .eic-frame')
        .css('width', width + 'px')
        .css('height', height + 'px');

    EasyImageCollage.redrawImages();
};

EasyImageCollage.redrawImages = function() {
    var grid = EasyImageCollage.editing_grid;

    if(grid['images'] !== undefined) {
        for(var i = 0; i < grid['images'].length; i++) {
            var image = grid['images'][i];

            if(image) {
                var attachment = {
                    id: image.attachment_id,
                    url: image.attachment_url,
                    width: image.attachment_width,
                    height: image.attachment_height,
                    thumb: image.attachment_thumb,
                    custom_link: image.custom_link,
                    custom_link_new_tab: image.custom_link_new_tab,
                    custom_link_nofollow: image.custom_link_nofollow,
                    custom_caption: image.custom_caption
                };
                var newImage = EasyImageCollage.getImageProperties(i, attachment);

                if(newImage !== undefined) {
                    var change_x_size = newImage.size_x / image.size_x,
                        change_y_size = newImage.size_y / image.size_y,
                        border_width = 2 * parseInt( grid['properties']['borderWidth'] ),
                        change_x_pos = ( newImage.size_x - 2 * border_width ) / image.size_x,
                        change_y_pos = ( newImage.size_y - 2 * border_width ) / image.size_y;

                    image.size_x = Math.ceil(image.size_x * change_x_size) - 2 * border_width;
                    image.size_y = Math.ceil(image.size_y * change_y_size) - 2 * border_width;
                    image.pos_x = Math.ceil(image.pos_x * change_x_pos);
                    image.pos_y = Math.ceil(image.pos_y * change_y_pos);
                }
                grid['images'][i] = image;
                EasyImageCollage.setImageFrontend(image);
            }
        }
    }

    if (typeof EasyImageCollage.recalculateSizes == 'function') {
        EasyImageCollage.recalculateSizes();
    }
};

EasyImageCollage.setActivePage = function(name) {
    var pages = ['layouts', 'creating', 'editing', 'manipulating', 'links', 'captions'];

    pages.forEach(function(page) {
        if(page == name) {
            jQuery('.eic-' + page).show();
        } else {
            jQuery('.eic-' + page).hide();
        }
    });

    // Page specific
    if(name == 'editing') {
        if (typeof EasyImageCollage.redrawDividers == 'function') {
            EasyImageCollage.redrawDividers();
        }

        EasyImageCollage.redrawBorders();
        EasyImageCollage.redrawGrid();
    }
};

EasyImageCollage.modifyEventForTouch = function(e) {
    e.clientX = e.originalEvent.touches[0].clientX;
    e.clientY = e.originalEvent.touches[0].clientY;
};

EasyImageCollage.addShortcodeToEditor = function(id) {
    var text = ' [easy-image-collage id='+id+'] ';

    if( typeof tinyMCE == 'undefined' || !tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
        var current = jQuery('textarea#content').val();
        jQuery('textarea#content').val(current + text);
    } else {
        tinyMCE.execCommand('mceInsertContent', false, text);
    }
};