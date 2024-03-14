    jQuery(window).on('load',function () {
        // Ordering
        jQuery('.price_adjustment tbody').sortable({
            items: 'tr',
            cursor: 'move',
            axis: 'y',
            handle: '.sort',
            scrollSensitivity: 40,
            forcePlaceholderSize: true,
            helper: 'clone',
            opacity: 0.65,
            placeholder: 'wc-metabox-sortable-placeholder',
            start: function (event, ui) {
                ui.item.css('baclbsround-color', '#f6f6f6');
            },
            stop: function (event, ui) {
                ui.item.removeAttr('style');
                elex_cm_price_adjustment_row_indexes();
            }
        });

        elex_cm_hide_placeholder_text('#eh_catalog_pricing_discount_price_catalog_mode', '#eh_catalog_pricing_discount_price_catalog_mode_text');
        elex_cm_hide_cart_placeholder_text('#eh_pricing_discount_cart_catalog_mode', '#eh_pricing_discount_cart_catalog_mode_text','#elex_catalog_remove_addtocart_shop','#elex_catalog_remove_addtocart_product');
        elex_cm_replace_addtocart_catalog();


        jQuery('#eh_pricing_discount_cart_catalog_mode').change(function () {
            elex_cm_hide_cart_placeholder_text('#eh_pricing_discount_cart_catalog_mode', '#eh_pricing_discount_cart_catalog_mode_text','#elex_catalog_remove_addtocart_shop','#elex_catalog_remove_addtocart_product');
        });

        jQuery('#eh_catalog_pricing_discount_price_catalog_mode').change(function () {
            elex_cm_hide_placeholder_text('#eh_catalog_pricing_discount_price_catalog_mode', '#eh_catalog_pricing_discount_price_catalog_mode_text');
		});

        jQuery('#eh_pricing_discount_replace_cart_catalog_mode').change(function () {
            elex_cm_replace_addtocart_catalog();
        });
        jQuery('label[for="eh_pricing_discount_hide_payment_gateways_catalog"]').append('<span style="vertical-align: super;color:green;font-size:12px">[Premium]</span>');
        var labels = jQuery('label[for="eh_pricing_discount_replace_place_order_catalog"], label[for="eh_pricing_discount_hide_place_order_catalog"]');

        labels.each(function() {
          var premiumTag = jQuery('</br><span style="vertical-align: super; color: green; font-size: 12px">[Premium]</span>');
          jQuery(this).closest('tr').find('th.titledesc').append(premiumTag);
        });
                    
        function elex_cm_price_adjustment_row_indexes() {
            jQuery('.price_adjustment tbody tr').each(function (index, el) {
                jQuery('input.order', el).val(parseInt(jQuery(el).index('.price_adjustment tr')));
            });
        };

        function elex_cm_hide_placeholder_text(check, hide_field) {
            if (jQuery(check).is(":checked")) {
                jQuery(hide_field).closest("tr").show();
            } else {
                jQuery(hide_field).closest("tr").hide();
            }
        }
        ;

        function elex_cm_hide_cart_placeholder_text(check, hide_field1, hide_field2, hide_field3) {
            if (jQuery(check).is(":checked")) {
                jQuery(hide_field1).closest("tr").show();
                jQuery(hide_field2).closest("tr").show();
                jQuery(hide_field3).closest("tr").show();

            } else {
                jQuery(hide_field1).closest("tr").hide();
                jQuery(hide_field2).closest("tr").hide();
                jQuery(hide_field3).closest("tr").hide();

            }
        }
        ;
        
        //To show/hide placeholder text and url for replace add to cart button for Catalog mode
        function elex_cm_replace_addtocart_catalog() {
            if (jQuery('#eh_pricing_discount_replace_cart_catalog_mode').is(":checked")) {
                jQuery('#eh_pricing_discount_replace_cart_catalog_mode_text_shop').closest("tr").show();
                jQuery('#eh_pricing_discount_replace_cart_catalog_mode_url_shop').closest("tr").show();
                jQuery('#eh_pricing_discount_replace_cart_catalog_mode_text_product').closest("tr").show();
            } else {
                jQuery('#eh_pricing_discount_replace_cart_catalog_mode_text_shop').closest("tr").hide();
                jQuery('#eh_pricing_discount_replace_cart_catalog_mode_url_shop').closest("tr").hide();
                jQuery('#eh_pricing_discount_replace_cart_catalog_mode_text_product').closest("tr").hide();
            }
        }
        ;
        
    });


