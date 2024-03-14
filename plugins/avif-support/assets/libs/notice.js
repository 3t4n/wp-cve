import { scrollToEl, scrollToPopupTop } from "./misc";

let localizeData = gpls_avfstw_localize_data;

function popupNotice() {

}

function regularNotice() {

}

function inlineNotice( notice, wrapper, place = 'append', autoRemove = true, delay = 1000, prefix = 'gpls-general' ) {
    let noticeEl = jQuery.parseHTML(notice);
    if ( ( wrapper instanceof jQuery ) === false ) {
        wrapper = jQuery(wrapper);
    }

    wrapper[place]( noticeEl );

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

function alertNotice( noticeMessage, wrapper, noticetype = 'danger', append = true, autoRemove = true, delay = 2000, prefix = 'gpls-general', includeCloseBtn = false ) {
    if ( wrapper instanceof jQuery === false ) {
        wrapper = jQuery(wrapper);
    }

    let closeBtn = '';
    if ( includeCloseBtn ) {
        closeBtn = '<button type="buttom" class="btn-close" data-bs-dismiss="alert" aria-label="close" ></button>';
    }

    let alertNotice = jQuery('<div class="alert alert-dismissible fade show alert-' + noticetype + '" role="alert">' + noticeMessage + closeBtn + '</div>' );
    if ( append ) {
        wrapper.append( alertNotice );
    } else {
        wrapper.prepend( alertNotice );
    }
    if ( autoRemove ) {
        setTimeout(
            function() {
                wrapper.find('.alert').remove();
            },
            delay
        );
    }

    let closeBtnEl = jQuery(alertNotice).find('.btn-close');
    if ( closeBtnEl.length ) {
        closeBtnEl.on( 'click', () => {
            closeBtnEl.closest('.alert').remove();
        });
    }
}

function showToast( msg, className="bg-primary", duration = 3000, prefix = 'gpls-general', toastHeader = true, createOne = false ) {
    let statusClassMapping = {
        success: 'bg-success',
        error: 'bg-danger',
        warning: 'bg-warning',
        info: 'bg-info'
    };
    let toast = '';
    if ( ! createOne ) {
        toast = jQuery('.' + prefix + '-toast');
    } else {
        toast =
        `<div style="z-index:999999999;" class="position-fixed top-50 start-50 translate-middle-x toast ` + className + `"` + ( prefix.length ? prefix + '-toast' : '' ) + `role="alert" aria-live="assertive" aria-atomic="true">
            ` +
                (
                    toastHeader ?
                        `<div class="toast-header">
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>`
                    :
                    ``
                )
            + `<div class="toast-body text-white">` + msg + ` </div>
        </div>`;
        toast = jQuery( toast );
        jQuery(document.body).append( toast );
        new bootstrap.Toast( toast );
    }
    let closeBtn = toast.find('.btn-close');
    className    = className.startsWith('bg-') ? className : ( className in statusClassMapping ? statusClassMapping[ className ] : 'bg-primary' );
    toast.removeClass('bg-primary bg-danger').addClass( className );
    toast.find('.toast-msg').html( msg );
    toast.show();

    if ( duration ) {
        setTimeout(
            () => {
                toast.hide();
            },
            duration
        );
    }

    closeBtn.on( 'click', () => {
        if ( createOne ) {
            toast.remove();
        } else {
            toast.hide();
        }
    });
}



function toggleLoader( status = 'show', customClass = '' ) {
    if ( status === 'show' ) {
        jQuery( '.' + ( customClass || 'loader' ) ).removeClass('d-none').show();
    } else {
        jQuery( '.' + ( customClass || 'loader' ) ).addClass('d-none').hide();
    }
}

function toggleFullPageLoader( status = 'show' ) {
    if ( status === 'show' ) {
        jQuery('.' + localizeData.config.classes.full_page_loader_wrapper ).show().removeClass('d-none');
    } else {
        jQuery('.' + localizeData.config.classes.full_page_loader_wrapper ).hide().addClass('d-none');
    }
}

function toggleOrderBtn( btn, status = 'show', all = true ) {
    let target = all ? jQuery( '.' + localizeData.config.classes.paddle_checkout_btn_base ) : jQuery( btn );
    let loader = target.find( '.' + localizeData.config.classes.order_btn_loader );
    if ( status === 'show' ) {
        target.prop( 'disabled', true );
        loader.removeClass('d-none').show();
    } else {
        target.prop( 'disabled', false );
        loader.addClass('d-none').hide();
    }
}

function toggleCheckoutBtn( status = 'on' ) {
    if ( status === 'on' ) {
        jQuery('#place_order').show();
    } else {

    }
}

function checkoutNotices( error_message ) {
    let $checkout_form = jQuery('form.checkout');
    jQuery( '.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' ).remove();
    $checkout_form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + error_message + '</div>' ); // eslint-disable-line max-len
    $checkout_form.removeClass( 'processing' ).unblock();
    $checkout_form.find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).trigger( 'blur' );
    scrollToEl( jQuery( 'form.checkout' ) );
    jQuery( document.body ).trigger( 'checkout_error' , [ error_message ] );
}

function togglePopupLoader( status = 'on') {
    jQuery('.' + localizeData.config.classes.forms_popup_container + ' .' + localizeData.config.classes.forms_popup_loader ).css( 'display', status === 'on' ? 'block' : 'none' );
}

function showPopupNotice( message,  type = 'general' ) {
    let container = null;
    jQuery( '.' + localizeData.config.classes.forms_popup_container ).find( '.' + localizeData.prefix + '-notice, .woocommerce-error' ).remove();
    if ( type === 'login' ) {
        container = jQuery( '.' + localizeData.prefix + '-login-form' );
    } else if ( type === 'register' ) {
        container = jQuery( '.' + localizeData.prefix + '-register-form' );
    } else if ( type === 'billing|shipping' ) {
        container = jQuery( '.' + localizeData.config.classes.paddle_billing_shipping_container );
    }
    if ( container ) {
        container.prepend( jQuery.parseHTML( message ) );
        scrollToPopupTop();
    }
}


function clearWooNotice() {
    jQuery('.woocommerce-notices-wrapper').empty();
}

export { popupNotice, alertNotice, inlineNotice, showToast, toggleLoader, toggleFullPageLoader, toggleOrderBtn, togglePopupLoader, showPopupNotice, checkoutNotices, clearWooNotice };
