/**
 * Handles admin scripts.
 */
jQuery(document).ready(function($){
    "use strict";

    var Ajaxurl = WpmagazineObject.ajax_url;
    var _wpnonce = WpmagazineObject._wpnonce;
    /**
     * Main tabs trigger
     */
    function admin_page_main_nav_trigger() {
        var last_segment = window.location.hash.substr(1);
        if( last_segment == '' ) {
            return;
        }
        $( ".cvmm-nav-tab-wrapper ul li." + last_segment ).siblings().removeClass( "isActive" );
        $( ".cvmm-nav-tab-wrapper ul li." + last_segment ).addClass( "isActive" );
        $( "#cvmm-main-content #" + last_segment ).siblings().hide();
        $( "#cvmm-main-content #" + last_segment ).show();
    }
    admin_page_main_nav_trigger();

    $( "#cvmm-main-header .cvmm-nav-tab-wrapper ul li" ).on( 'click', function(e) {
        e.preventDefault();
        var dis = $(this);
        dis.siblings().removeClass( "isActive" );
        dis.addClass( "isActive" );
        var content_attr = dis.find("a").attr("href");
        var main_content = $( "#cvmm-main-content " + content_attr );
        main_content.siblings().hide();
        main_content.show();
    });

    /**
     * Handles toggle tabs
     * 
     */
    $( ".cvmm-admin-group-fields .cvmm-admin-single-field .cvmm-admin-field-heading" ).on( "click", function() {
        var _this = $( this );
        _this.find( "span" ).toggleClass( "dashicons-arrow-down-alt2" ).toggleClass( "dashicons-arrow-up-alt2" );
        _this.next( ".cvmm-admin-field-options" ).slideToggle( "slow" );
    });

    /**
     * Embed color control field.
     * 
     */
    $( ".cvmm-wpmagazine-modules-lite-color-field" ).wpColorPicker({
        change: function() {
            var formButton = $( '#cvmm-wpmagazine-modules-lite-options-form input[type="submit"]' );
            var saveText = formButton.data( "save" );
            formButton.removeAttr( "disabled" );
            formButton.val( saveText );
        },
        clear: function() {
            var formButton = $( '#cvmm-wpmagazine-modules-lite-options-form input[type="submit"]' );
            var saveText = formButton.data( "save" );
            formButton.removeAttr( "disabled" );
            formButton.val( saveText );
        }
    });

    /**
     * Ajax call on form submit
     * 
     */
    $( "#cvmm-wpmagazine-modules-lite-options-form" ).on( "submit", function(e) {
        e.preventDefault();
        var formButton = $( this ).find( 'input[type="submit"]' );
        var formData = $( this ).serialize();
        $.ajax({
            url: Ajaxurl,
            method: 'post',
            data: {
                action  : "wpmagazine_modules_lite_submit_form",
                data    : formData,
                _wpnonce: _wpnonce
            },
            beforeSend: function() {
                var savingText = formButton.data( "saving" );
                formButton.val( savingText );
            },
            success: function( response ) {
                if( response ) {
                    var savedText = formButton.data( "saved" );
                    formButton.val( savedText );
                    formButton.attr( "disabled", "disabled" );
                }
            }
        });
    });

    /**
     * Reset form values
     * 
     */
    $( "#cvmm-wpmagazine-modules-lite-options-form .cvmm-form-button .button-reset" ).on( "click", function(e) {
        e.preventDefault();
        var confirmReset = confirm( "Are you sure you want to reset form values" );
        if( confirmReset ) {
            $( ".cvmm-wpmagazine-modules-lite-color-field" ).each( function() {
                $( this ).parent().next( ".wp-picker-clear" ).trigger( "click" );
            })
            $( "#cvmm-wpmagazine-modules-lite-options-form" ).submit();
        }
    })
})