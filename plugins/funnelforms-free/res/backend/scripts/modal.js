const af2_open_modal = (dom_element, attributes) => {
    const modal_id = jQuery(dom_element).data('target');
    const custom_class = jQuery(dom_element).data('class');
    const confirmationid = jQuery(dom_element).data('confirmationid') != null ? 'data-confirmationid="'+jQuery(dom_element).data('confirmationid')+'"' : '';
    const modal_selector = jQuery('#'+modal_id);
    const heading = modal_selector.data('heading');
    const close = modal_selector.data('close');
    const sizeclass = modal_selector.data('sizeclass');
    const data_bottombar = modal_selector.data('bottombar') == true ? 'af2_bottombar' : '';
    const modal_content = '<div class="af2_modal_content af2_card '+data_bottombar+'"><div class="af2_card_block">'+jQuery('#'+modal_id+' .af2_modal_content').html()+'</div></div>';

    let heading_el = '<h3 class="af2_modal_heading">'+heading+'</h3>';
    let close_btn = '';
    close_btn += '<div class="af2_btn af2_btn_primary af2_modal_close" data-target="'+modal_id+'_" data-usetarget="'+modal_id+'">';
    close_btn += '<i class="fas fa-times"></i>'+close;
    close_btn += '</div>'

    const bottombar = modal_selector.data('bottombar') ? '<div class="af2_modal_bottombar">'+jQuery('#'+modal_id+' .af2_modal_bottombar').html()+'</div>' : '';

    let content_wrapper = '';
    content_wrapper += '<div class="af2_modal_content_wrapper '+sizeclass+'">';
    content_wrapper += '<div class="af2_modal_content_wrapper_headline">';
    content_wrapper += heading_el;
    content_wrapper += close_btn;
    content_wrapper += '</div>';
    content_wrapper += modal_content;
    content_wrapper += bottombar;
    content_wrapper += '</div>';

    let modal_html = '';
    modal_html += '<div id="'+modal_id+'_" class="af2_modal '+custom_class+'" '+confirmationid+'>';
    modal_html += content_wrapper;
    modal_html += '</div>';

    jQuery('.af2_wrapper').append(modal_html);

    if(attributes != null) jQuery('#'+modal_id+'_ #'+attributes.id).attr('data-'+attributes.data_attribute, attributes.data_value);

    jQuery('#'+modal_id+'_').addClass('af2_show');
}

const af2_close_modal = (dom_element) => {
    const modal_selector = dom_element != null ? '#'+jQuery(dom_element).data('target') : '#'+jQuery('.af2_modal.af2_show').attr('id');
    const off_click_listener = jQuery(modal_selector).attr('data-confirmationid');
    
    if(off_click_listener != null) jQuery(document).off('click', '#'+off_click_listener);

    jQuery(modal_selector).removeClass('af2_show');
    jQuery(modal_selector).remove();

    let event = jQuery.Event('af2_close_modal');
    jQuery('#'+jQuery(dom_element).data('usetarget')).trigger(event);
}

jQuery( document ).ready(function() {


    jQuery(document).on('click', '.af2_modal_btn', function() { af2_open_modal(this) });
    jQuery(document).on('click', '.af2_modal_close', function() { af2_close_modal(this) });

    jQuery(document).on('keyup', function(e) {
        if (e.key === "Escape" || e.keycode == 27) { // escape key maps to keycode `27`
            af2_close_modal();
       }
    });

    jQuery(document).on('click', '.af2_modal.af2_show', function() { af2_close_modal(jQuery(this).find('.af2_btn.af2_modal_close')); })
    jQuery(document).on('click', '.af2_modal.af2_show .af2_modal_content_wrapper', function(e) { e.stopPropagation(); })

    
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const show_modal = urlParams.get('show_modal');

    if(show_modal != null) {
        const selector = jQuery('.af2_modal[data-urlopen="'+show_modal+'"]').attr('id');
        if(selector != null) af2_open_modal('#'+selector);
    }
});