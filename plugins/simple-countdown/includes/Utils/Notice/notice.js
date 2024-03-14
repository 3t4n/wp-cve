
function popupNotice() {

}

function regularNotice() {

}

function inlineNotice( notice, wrapper, append = true, autoRemove = true, delay = 1000, prefix = 'gpls-general' ) {
    let noticeEl = jQuery.parseHTML(notice);
    if ( wrapper instanceof jQuery === false ) {
        wrapper = jQuery(wrapper);
    }
    if ( append ) {
        wrapper.append( noticeEl );
    } else {
        wrapper.prepend( noticeEl );
    }
    if ( autoRemove ) {
        setTimeout(
            function() {
                wrapper.find('.' + prefix + '-notice').remove();
            },
            delay
        );
    }

    let closeBtn = jQuery(noticeEl).find('.btn-close');
    if ( closeBtn.length ) {
        closeBtn.on( 'click', () => {
            wrapper.find('.' + prefix + '-notice').remove();
        });
    }
}

function showToast( msg, className="bg-primary", duration = 3000, prefix = 'gpls-general' ) {
    let statusClassMapping = {
        success: 'bg-primary',
        error: 'bg-danger',
        warning: 'bg-warning',
        info: 'bg-info'
    };
    let toast    = jQuery('.' + prefix + '-toast');
    let closeBtn = toast.find('.btn-close');
    className    = className.startsWith('bg-') ? className : ( className in statusClassMapping ? statusClassMapping[ className ] : className );
    toast.removeClass('bg-primary bg-danger').addClass( className );
    toast.find('.toast-msg').html( msg );
    toast.collapse('show');
    if ( duration ) {
        setTimeout(
            () => {
                toast.collapse('hide');
            },
            duration
        );
    }

    closeBtn.on( 'click', () => {
        toast.collapse('hide');
    });
}

function toggleLoader( status = 'show' ) {
    if ( status === 'show' ) {
        jQuery('.loader').removeClass('d-none');
    } else {
        jQuery('.loader').addClass('d-none');
    }
}

export { popupNotice, inlineNotice, showToast, toggleLoader };
