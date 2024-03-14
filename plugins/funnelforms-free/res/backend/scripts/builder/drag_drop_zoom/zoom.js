
var zoom_level = parseInt(jQuery('.af2_zoom_container').data('zoomlevel'));

jQuery(document).on('click', '#af2_zoom_out', _ => {
    zoom_level--;
    if(zoom_level < 1) zoom_level = 1;
    jQuery('.af2_zoom_container').attr('data-zoomlevel', zoom_level);

    let event = jQuery.Event('af2_zoomed_out');
    jQuery('.af2_zoom_container').trigger(event);
});


jQuery(document).on('click', '#af2_zoom_in', _ => {
    zoom_level++;
    if(zoom_level > 5) zoom_level = 5;
    jQuery('.af2_zoom_container').attr('data-zoomlevel', zoom_level);

    let event = jQuery.Event('af2_zoomed_in');
    jQuery('.af2_zoom_container').trigger(event);
});