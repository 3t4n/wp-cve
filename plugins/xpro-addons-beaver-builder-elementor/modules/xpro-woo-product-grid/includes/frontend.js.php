
(function ($){

    var product_grid_container = $('.xpro-woo-product-grid-main'),
        item = $('.xpro-woo-product-grid-item');

    $(".xpro-hv-qv-btn").click(function (e) {
        e.preventDefault();
        var product_id = $(this).attr('id');
        var data = {
            action: "load_quick_view_product_data",
            nonce: xproWooProducts.nonce,
        }
        $.ajax(
            {
                url: xproWooProducts.wc_ajax_url,
                type: 'post',
                data: data,
                dataType: 'html',
                cache: false,
                beforeSend: function () {
                    $('.xpro-qv-loader-wrapper').css('display', 'unset');
                    $('.xpro-qv-popup-wrapper').css('display', 'none');
                },
                complete: function () {
                    $('.xpro-qv-loader-wrapper').css('display', 'none');
                    $('.xpro-qv-popup-wrapper').css('display', 'unset');
                    $('.xpro-qv-popup-overlay').css(
                        {
                            "opacity": "1",
                            "visibility": "visible"
                        }
                    );
                },
                success: function (data) {
                    $('#xpro-elementor_fetch_qv_data').html(data);
                    $('.xpro-woo-qv-content-sec .variations_form').wc_variation_form().find('.variations select:eq(0)').trigger('change');
                    $('.xpro-woo-qv-content-sec .variations_form').trigger('wc_variation_form');
                }

            }
        )
    });

    // Close Button
    $('.xpro-woo-qv-cross').on('click', function (e){
        e.preventDefault();
        $('.xpro-qv-popup-wrapper').css('display','none');
        $('.xpro-qv-popup-overlay').css(
            {
                "opacity": "0",
                "visibility": "hidden"
            }
        );
    });

    //overlay click to close quick view
    $('.xpro-qv-popup-overlay').on('click', function (e){
        e.preventDefault();
        $('.xpro-qv-popup-wrapper').css('display','none');
        $('.xpro-qv-popup-overlay').css(
            {
                "opacity": "0",
                "visibility": "hidden"
            }
        );
    });

    //press esc to close quick view
    $(document).keyup(function (e){
        if (e.keyCode === 27){
            $('.xpro-qv-popup-wrapper').css('display', 'none');
            $('.xpro-qv-popup-overlay').css(
                {
                    "opacity": "0",
                    "visibility": "hidden"
                }
            );
        }
    });

    // Qucik view product ajax
    $('#xpro_elementor_fetch_qv_data .single_add_to_cart_button:not(.disabled)').on('click', function(e){
        e.preventDefault();
        if($(this).parents('form').attr('action') !== ''){
            window.location.href = $(this).parents('form').attr('action');
            return false;
        }

        var $form = $(this).closest('form');
        if (!$form[0].checkValidity()) {
            $form[0].reportValidity();
            return false;
        }

        var $thisbutton = $(this),
            product_id = $(this).val(),
            variation_id = $('input[name="variation_id"]').val() || "",
            quantity = $('input[name="quantity"]').val();

        if ($('.woocommerce-grouped-product-list-item').length){
            var quantities = $('input.qty'),
                quantity = [];
            $.each(
                quantities,
                function (index, val) {

                    let name = $(this).attr('name');
                    name = name.replace('quantity[', '');
                    name = name.replace(']', '');
                    name = parseInt(name);

                    if ($(this).val()) {
                        quantity[name] = $(this).val();
                    }
                }
            );
        };

        var carFormData = $form.serialize();

        if ($thisbutton.is(".single_add_to_cart_button")){
            $thisbutton.removeClass('added');
            $thisbutton.addClass('loading');
            $.ajax(
                {
                    url: xproWooProducts.wc_ajax_url,
                    type: "POST",
                    data: "action=add_cart_single_product_ajax&product_id=" +
                        product_id +
                        "&nonce=" + xproWooProducts.nonce +
                        "&" + cartFormData,

                    success: function (results){
                        $(document.body).trigger("wc_fragment_refresh");
                        $thisbutton.removeClass("loading");
                        $thisbutton.addClass("added");
                    },
                }
            );
        }

    });


    $('.fl-node-<?php echo esc_attr( $id ); ?> .xpro-woo-product-grid-main').cubeportfolio({
            layoutMode: 'grid',
            gridAdjustment: 'responsive',
            mediaQueries: [{
                width: <?php echo $global_settings->medium_breakpoint + 1; ?>,
                cols: <?php echo $settings->column_grid ? $settings->column_grid : 3 ; ?>,
                options: {
                    gapHorizontal: <?php echo $settings->space_between ? $settings->space_between : 15 ; ?>,
                    gapVertical: <?php echo $settings->space_between ? $settings->space_between : 15 ; ?>,
                }
            }, {
                width: <?php echo $global_settings->responsive_breakpoint + 1; ?>,
                cols: <?php echo $settings->column_grid_medium ? $settings->column_grid_medium : 2 ; ?>,
                options: {
                    gapHorizontal: <?php echo $settings->space_between_medium ? $settings->space_between_medium : 15 ; ?>,
                    gapVertical: <?php echo $settings->space_between_medium ? $settings->space_between_medium : 15 ; ?>,
                }
            }, {
                width: 0,
                cols: <?php echo $settings->column_grid_responsive ? $settings->column_grid_responsive : 1 ; ?>,
                options: {
                    gapHorizontal: <?php echo $settings->space_between_responsive ? $settings->space_between_responsive : 15 ; ?>,
                    gapVertical: <?php echo $settings->space_between_responsive ? $settings->space_between_responsive : 15 ; ?>,
                }
            }],
            displayType: 'sequentially',
            displayTypeSpeed: 80,
        });

}(jQuery));