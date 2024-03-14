jQuery(document).ready(function($) {
    'use strict';

    // Save Button reacting on any changes
    var footerSaveBtn = $('.salert-save-btn-wrap .salert-btn');
    $('.salert-input input, .salert-input textarea, .salert-input select').on('click', function() {
        footerSaveBtn.addClass('save-now');
        $('.save-notice').slideDown();
    });
console.log(admin_settings.ajax_nonce);
    // Saving Data With Ajax Request
    $('form#salert-settings-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'salert_save_settings_with_ajax',
                security: admin_settings.ajax_nonce,
                fields: $('form#salert-settings-form').serialize(),
            },
            success: function(response) {
                swal({
                    type: 'success',
                    title: 'Settings Saved!',
                    showConfirmButton: false,
                    timer: 2000,
                });
                footerSaveBtn.removeClass('save-now');
                $('.save-notice').slideUp();
            },
            error: function() {
                swal(
                    'Oops...',
                    'Something went wrong!',
                    'error'
                );
            }
        });
    });

    //main tabs
    $(".salert-settings-tab .tab-wrap li").click(function(e) {
        e.preventDefault();
        var self = $(this);
        var dispstyle = self.attr("data-id");

        $(".tab-pane").hide();
        $(".salert-settings-tab .tab-wrap li").removeClass('active');
        self.closest('li').addClass('active');
        $("." + dispstyle + "").fadeIn();
    });

    //main tabs
    $(".general-settings-section .general-tab-wrap li").click(function(e) {
        e.preventDefault();
        var self = $(this);
        var dispstyle1 = self.attr("data-id");

        $(".general-tab-pane").hide();
        $(".general-settings-section .general-tab-wrap li").removeClass('active');
        self.closest('li').addClass('active');
        $("." + dispstyle1 + "").fadeIn();
    });

    //toggle for mannual post types
    $('.salert-toggle-tab-header').on('click',function(){   
        $(this).toggleClass('salert-toggle-active');
        $(this).siblings('.salert-toggle-tab-body').slideToggle();
    });

    /* For repeater products */
    var tCount = $('#table_products_count').val();

    $('.docopy-table-product').click(function() {
        tCount++;
        $('.table-products-wrapper').append('<div class="single-product wp-dynamic"><div class="single-section-title clearfix"><h4 class="product-title fleft">Product ' + tCount + ' : </h4>' +
            '<div class="product-inputfield fleft"><input type="text" name="popup-products[title][' + tCount + ']" value="" required/></div>' +
            '<div class="product-imagefield fleft clearfix"><input type="text" name="popup-products[url][' + tCount + ']" placeholder="http://path/to/image.png" value="">' +
            '<span class="sme_galimg_ctrl"><a class="sme_add_galimg" href="#">Upload</a></span></div>' +
            '<div class="product-link fleft clearfix"><input type="text" placeholder="http://" name="popup-products[link][' + tCount + ']"></div>' +
            '<div class="delete-table-product fleft"><a href="javascript:void(0)" class="delete-product button">Delete Product</a></div>' +
            '</div></div>'
        );
    });
    $(document).on('click', '.delete-table-product > a', function() {
        $(this).parents('.single-product').remove();
    });

    /** Upload Product Image **/
    $(document).on('click', '.sme_galimg_ctrl .sme_add_galimg', function(e) {
        e.preventDefault();
        var $this = $(this);
        var image = wp.media({
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once
                multiple: false
            }).open()
            .on('select', function(e) {
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                var image_url = uploaded_image.toJSON().url;
                // Let's assign the url value to the input field
                $this.parent('.sme_galimg_ctrl').prev('input').val(image_url);
                $this.parents('.general-settings-section').siblings('.salert-backend-preview').find('.popup_template').css('background-image', 'url(' + image_url + ')');
            });
    });

    //Display Contents
    $('.mannual-contents').show();
    if ($('body .chk-woo').is(':checked')) {
        $('.mannual-contents').hide();
    }
    $('body').on('click', '.chk-woo', function() {
        $('.mannual-contents').toggle();
    });

    //Close btn
    var close = $('.popup-item .close');
    close.hide();
    if ($('body .close-btn').is(':checked')) {
        close.show();
    }
    $('body').on('click', '.close-btn', function() {
        close.toggle();
    });


    //border enable
    $('.salert-border-options').hide();
    if ($('body .chk-border').is(':checked')) {
        $('.salert-border-options').show();
    }
    $('body').on('click', '.chk-border', function() {
        $('.salert-border-options').toggle();
    });

    //border enable live preview
    var ptemmplate = $('.popup_template');
    $('body').on('change', '.chk-border', function() {
        ptemmplate.toggleClass('border');
    });
    if ($('body .chk-border').is(':checked')) {
        ptemmplate.removeClass('radius');
        $('body').on('change', '.chk-border', function() {
            ptemmplate.toggleClass('b-radius');
        });
    } else {
        $('body').on('change', '.chk-border', function() {
            ptemmplate.toggleClass('radius');
        });
    }



    //Border Color picker
    $("#popup_bordercolor").wpColorPicker(
        'option',
        'change',
        function(event, ui) {
            var color = ui.color.toString();
            var destination = $('.popup_template');
            destination.css('border-color', color);
        }
    );

    //on load
    var border_color = $('#popup_bordercolor').val();
    $('.popup_template.border').css('border-color', border_color);


    //Border Radius
    function salert_border_radius() {
        var border_radius = $('#salert-border-radius').val();
        $('.popup_template.border').css({
            "border-radius": border_radius + "px"
        });
    }
    salert_border_radius();
    $('body').on('keyup', '#salert-border-radius', function() {
        salert_border_radius();
    });

    //Border Width
    function salert_border_width() {
        var border_width = $('#salert-border-width').val();
        $('.popup_template.border').css('border-width', border_width);
    }
    salert_border_width();
    $('body').on('keyup', '#salert-border-width', function() {
        salert_border_width();
    });

     //box shadow
    var bxShadow = $('.popup_template');
    if ($('body .chk-boxs').is(':checked')) {
        bxShadow.addClass('boxs');
        $('body').on('change', '.chk-boxs', function() {
            bxShadow.toggleClass('boxs');
        });
    } else {
        $('body').on('change', '.chk-boxs', function() {
            bxShadow.toggleClass('boxs');
        });
    }

    //on load Border enable
    if ($('body .chk-border').is(':checked')) {
        return;
    } else {
        $('.popup_template').removeClass('border');
    }

});