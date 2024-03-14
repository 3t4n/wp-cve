var af2_toast_count = 0;

const af2_create_toast = (wrapper_class, label, type='success', time=true, clickCallback, param) => {
    let toast = '';

    const actualCount = af2_toast_count;

    af2_toast_count++;

    toast += '<div id="af2_toast_'+actualCount+'" class="af2_toast show '+type+'">';
    toast += '<div class="af2_toast_content_wrapper">';
    toast += '<div class="af2_toast_icon">';
    if(type == 'af2_error') toast += '<i class="fas fa-times"></i>';
    if(type == 'af2_success') toast += '<i class="fas fa-check"></i>';
    if(type == 'af2_warning') toast += '<i class="fas fa-exclamation"></i>';
    if(type == 'af2_info') toast += '<i class="fas fa-info"></i>';
    toast += '</div>';
    toast += '<div class="af2_toast_text">';
    toast += '<p>'+label+'</p>';
    toast += '</div>';
    toast += '</div>';
    toast += '</div>';

    jQuery('.'+wrapper_class).append(toast);

    if(time) {
        setTimeout(_ => {
            jQuery('#af2_toast_'+actualCount).removeClass('show');
            jQuery('#af2_toast_'+actualCount).addClass('out');
            setTimeout(_ => {
                jQuery('#af2_toast_'+actualCount).remove();
            }, 500);
        }, 5000);
    }

    if(clickCallback != null) {
        jQuery(document).on('click', '#af2_toast_'+actualCount, _ => {
            clickCallback(param);
        });
    }
}

const af2_clear_toast = (wrapper_class, callback=function(){}, param) => {
    jQuery('.'+wrapper_class+' .af2_toast').removeClass('show');
    jQuery('.'+wrapper_class+' .af2_toast').addClass('out');

    setTimeout(_ => {
        jQuery('.'+wrapper_class+' .af2_toast').remove();
        callback(param);
    }, 500);
}