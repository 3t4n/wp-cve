jQuery(function ($) {

    $(document).ready(function () {
        $('.ep-help-tip').append("<span></span>");
        $('.ep-help-tip:not([tooltip-position])').attr('tooltip-position', 'top');

        $(".ep-help-tip").mouseenter(function () {
            $(this).find('span').empty().append($(this).attr('tooltip'));
        });
        
        //Ticket Model Scroll
        $(document).on('click', '#ep_event_open_ticket_modal, .ep-ticket-row-edit', function () {
            $('#ep_event_ticket_tier_modal').animate({ scrollTop: 0 }, 'slow');
        });
        
    });

    //General Modal Global
    $.fn.openPopup = function (settings, edit = '') {
        var elem = $(this);
        // Establish our default settings
        var settings = $.extend({
            anim: 'ep-modal-',
            overlayAnim:'ep-modal-overlay-fade-'
        }, settings);
        elem.show();
        elem.find('.popup-content').addClass(settings.anim + 'in').removeClass(settings.anim + 'out');
        elem.find('.ep-modal-overlay').addClass(settings.overlayAnim + 'in').removeClass(settings.overlayAnim + 'out');
        // check if edit popup is opend.
        if( edit !== '' ) {
            // change the modal title
            if( edit.title ) {
                elem.find( '.ep-modal-title' ).html( em_event_meta_box_object.edit_text + ' ' + edit.title );
            }
            // add the edit attribute on the buttons
            if( edit.row_id ) {
                elem.find( 'button' ).attr( 'data-edit_row_id' , edit.row_id );
            }
        }
    }

    $.fn.closePopup = function (settings) {
        var elem = $(this);
        // Establish our default settings
        var settings = $.extend({
            anim: 'ep-modal-',
            overlayAnim:'ep-modal-overlay-fade-'
        }, settings);
        elem.find('.popup-content').removeClass(settings.anim + 'in').addClass(settings.anim + 'out');
        elem.find('.ep-modal-overlay').removeClass(settings.overlayAnim + 'in').addClass(settings.overlayAnim + 'out');

        setTimeout(function () {
            elem.hide();
            elem.find('.popup-content').removeClass(settings.anim + 'out');
            // remove edit attribute if exists
            elem.find( 'button' ).removeAttr( 'data-edit_row_id' );
        }, 400);
    }

    // click event for open popup
    $( document ).on( 'click', '.ep-open-modal', function () {
        $('#' + $(this).data('id')).openPopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
        
        $('body').addClass('ep-modal-open-body');
    });
    // click event for close popup
    $( document ).on( 'click', '.close-popup', function () {
        $('#' + $(this).data('id')).closePopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
        
        $('body').removeClass('ep-modal-open-body');
    });

    // extension filter
    $( document ).on( 'click', '#ep-ext-controls li a', function(e) {
        e.preventDefault();
        $('#ep-ext-controls li a' ).removeClass('ep-extension-list-active');
        $(this).addClass( 'ep-extension-list-active' );
        var that = this,
            $that = $(that),
            id = that.id,
            ext_list = $('.ep-extensions-box-wrap');
        if (id == 'all-extensions') {
            ext_list.find('.ep-ext-card').fadeIn(500);
        }
        else {
            ext_list.find('.ep-ext-card.' + id + ':hidden').fadeIn(500);
            ext_list.find('.ep-ext-card').not('.' + id).fadeOut(500);
        }
    });

    // print attendee list
    $( document ).on( 'click', '#ep_print_event_attendees_list', function() {
        $( '#ep_print_attendee_list_loader' ).addClass( 'is-active' );
        let event_id = $( '#ep_event_id' ).val();
        if( event_id ) {
            let security = $( '#ep_ep_print_event_attendees_nonce' ).val();
            let data = { 
                action: 'ep_event_print_all_attendees', 
                security: security,
                event_id: event_id,
            };
            $.ajax({
                type    : "POST",
                url     : ajaxurl,
                data    : data,
                success : function( response ) {
                    let blob = new Blob([response]);
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    let file_name = 'attendees-' + event_id + '.csv';
                    link.download = file_name;
                    link.click();
                    $( '#ep_print_attendee_list_loader' ).removeClass( 'is-active' );
                }
            });
        }
    });

});

/**
 * return ajax url
 */
function get_ajax_url() {
    return ep_admin_utility_script.ajaxurl;
}

function hide_show_default_date_setting(element, childId) {
    jQuery('#' + childId).toggle();
    if (element.checked) {
        jQuery('#' + childId).find('input').attr('required', 'required');
    } else {
        jQuery('#' + childId).find('input').removeAttr('required');
    }
}
function hide_show_paypal_client_id(element, childId) {
    jQuery('#' + childId).toggle();
    if (element.checked) {
        jQuery('#' + childId).find('input').attr('required', 'required');
    } else {
        jQuery('#' + childId).find('input').removeAttr('required');
    }
}
function hide_show_google_share_setting(element, childId) {
    jQuery('.' + childId).toggle();
    if (element.checked) {
        jQuery('.' + childId).find('input').attr('required', 'required');
    } else {
        jQuery('.' + childId).find('input').removeAttr('required');
    }
}

/*
 * Hide Show
 */
function ep_frontend_view_child_hide_show(element, childId){
    if (element.checked) {
        jQuery('#' + childId).show(200);
    } else {
        jQuery('#' + childId).hide(200);
    }
}

function ep_email_attendies_hide_show(){
    jQuery('#ep-autopopulate').toggle(200);
}
