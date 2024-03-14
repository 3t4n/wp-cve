// Here we can adjust defaults for all color pickers on page:
jscolor.presets.default = {
    position: 'right',
    palette: [
        '#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26',
        '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
        '#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d',
        '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
    ],
};

jQuery(document).ready(function(){
    if ( ! jQuery( '#ep_type_image_id' ).val() ) {
        jQuery( '.remove_image_button' ).hide();
    }
});

// fire on upload image button
var file_frame;
jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
    event.preventDefault();
    // If the media frame already exists, reopen it.
    if ( file_frame ) {
        file_frame.open();
        return;
    }
    // Create the media frame.
    file_frame = wp.media.frames.downloadable_file = wp.media({
        title: em_type_object.media_title,
        button: {
            text: em_type_object.media_button
        },
        multiple: false
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
        var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
        var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

        jQuery( '#ep_type_image_id' ).val( attachment.id );
        let imageHtml = '<span class="ep-event-type-image">';
            imageHtml += '<i class="remove_image_button dashicons dashicons-trash ep-text-danger"></i>';
            imageHtml += '<img src="'+attachment_thumbnail.url+'" data-image_id="'+attachment.id+'" width="60">';
        imageHtml += '</span>';
        jQuery( '#ep-type-admin-image' ).html( imageHtml );
    });

    // Finally, open the modal.
    file_frame.open();
});

// remove image
jQuery( document ).on( 'click', '.remove_image_button', function(){
    jQuery( '#ep-type-admin-image' ).html('');
    jQuery( '#ep_type_image_id' ).val( '' );
});

// fire event on ajax complete
jQuery( document ).ajaxComplete( function( event, request, options ) {
    if ( request && 4 === request.readyState && 200 === request.status
        && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

        var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
        if ( ! res || res.errors ) {
            return;
        }
        // Clear taxonomy fields on submit
        jQuery( '#ep-type-admin-image' ).html('');
        jQuery( '#ep_type_image_id' ).val( '' );
        jQuery( '#is_featured' ).prop( 'checked', false );
        jQuery( '.remove_image_button' ).hide();
        jQuery( '#ep-event-type-age-group option:first' ).attr( 'selected', 'selected' );
        jQuery( '#ep-event-type-age-group' ).trigger( 'change' );

        jQuery( "#ep-custom-group" ).val( 18 + ' - ' + 25 );
        jQuery( "#ep-custom-group-range" ).slider({
            range: true,
            min: 0,
            max: 100,
            values: [ 18, 25 ],
            slide: function( event, ui ) {
                jQuery( "#ep-custom-group" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
            }
        });
        return;
    }
} );

jQuery( document ).ready(function(e){
    //Age group
    jQuery('#ep-event-type-age-group' ).change(function(e){
        var selectGroup = jQuery(this).val();
        if( selectGroup == 'custom_group' ){
            jQuery( '.em-type-admin-age-group-custom' ).show(300);
        } else{
            jQuery( '.em-type-admin-age-group-custom' ).hide(200);
        }
    });

    // default age group val
    let min_age = 18;
    let max_age = 25;
    let custom_age_group_val = jQuery( "#ep-custom-group" ).val();
    if( custom_age_group_val ) {
        custom_age_group_val = custom_age_group_val.split( '-' );
        min_age = custom_age_group_val[0];
        max_age = custom_age_group_val[1];
    } else{
        jQuery( "#ep-custom-group" ).val( min_age + ' - ' + max_age );
    }
    jQuery( "#ep-custom-group-range" ).slider({
        range: true,
        min: 0,
        max: 100,
        values: [ min_age, max_age ],
        slide: function( event, ui ) {
            jQuery( "#ep-custom-group" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        }
    });
});
