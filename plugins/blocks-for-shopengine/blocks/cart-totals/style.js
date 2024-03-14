
const Style = ({ settings, breakpoints, cssHelper }) => {
    cssHelper.add(`
    .shopengine-cart-totals a:not(.checkout-button),
    .shopengine-cart-totals tr,
    .shopengine-cart-totals td,
    .shopengine-cart-totals th,
    .shopengine-cart-totals #shipping_method .price, 
    .shopengine-cart-totals #shipping_method .amount 

    `, settings.font_size, (val) => {
        return (
            `
               font-size : ${val}px;
            `
        )
    })
        .add(`
    .shopengine-cart-totals a:not(.checkout-button),
    .shopengine-cart-totals tr,
    .shopengine-cart-totals td,
    .shopengine-cart-totals th,
    .shopengine-cart-totals #shipping_method .price, 
    .shopengine-cart-totals #shipping_method .amount 

    `, settings.font_weight, (val) => {
            return (
                `
               font-weight : ${val};
            `
            )
        })

        .add(`
    .shopengine-cart-totals a:not(.checkout-button),
    .shopengine-cart-totals tr,
    .shopengine-cart-totals td,
    .shopengine-cart-totals th,
    .shopengine-cart-totals #shipping_method .price, 
    .shopengine-cart-totals #shipping_method .amount 

    `, settings.text_transform, (val) => {
            return (
                `
               text-transform : ${val};
            `
            )
        })

        .add(`
    .shopengine-cart-totals a:not(.checkout-button),
    .shopengine-cart-totals tr,
    .shopengine-cart-totals td,
    .shopengine-cart-totals th,
    .shopengine-cart-totals #shipping_method .price, 
    .shopengine-cart-totals #shipping_method .amount 

    `, settings.line_height, (val) => {
            return (
                `
            line-height : ${val}px;
            `
            )
        })

        .add(`
    .shopengine-cart-totals a:not(.checkout-button),
    .shopengine-cart-totals tr,
    .shopengine-cart-totals td,
    .shopengine-cart-totals th,
    .shopengine-cart-totals #shipping_method .price, 
    .shopengine-cart-totals #shipping_method .amount 

    `, settings.letter_spacing, (val) => {
            return (
                `
            letter-spacing : ${val}px;
            `
            )
        })

        .add(`
    .shopengine-cart-totals a:not(.checkout-button),
    .shopengine-cart-totals tr,
    .shopengine-cart-totals td,
    .shopengine-cart-totals th,
    .shopengine-cart-totals #shipping_method .price, 
    .shopengine-cart-totals #shipping_method .amount 

    `, settings.title_wordspace, (val) => {
            return (
                `
            word-spacing : ${val}px;
            `
            )
        })

        .add(`
    .shopengine-cart-totals .shop_table tr th,
    .shopengine-cart-totals .shop_table tr.shipping td:before

    `, settings.shopengine_cart_totals_table_heading_color, (val) => {
            return (
                `
            color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .shop_table tr td,
    .shopengine-cart-totals .shop_table tr td *,
    .shopengine-cart-totals .shop_table tr td::before,

    `, settings.shopengine_cart_totals_table_data_color, (val) => {
            return (
                `
            color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li label,
    .shopengine-cart-totals tr.shipping p,
    .shopengine-cart-totals tr.shipping form a

    `, settings.shopengine_cart_totals_shipping_methods_heading_color, (val) => {
            return (
                `
            color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li input[type=radio],
    .shopengine-cart-totals tr.shipping ul li input[type=radio]:checked,
    

    `, settings.shopengine_cart_totals_shipping_methods_heading_color, (val) => {
            return (
                `
            border-color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li input[type=radio]:checked:before
    `, settings.shopengine_cart_totals_shipping_methods_heading_color, (val) => {
            return (
                `
            background : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li label,
    .shopengine-cart-totals tr.shipping p,
    .shopengine-cart-totals tr.shipping form a

    `, settings.shipping_methods_font_size, (val) => {
            return (
                `
            font-size : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li label,
    .shopengine-cart-totals tr.shipping p,
    .shopengine-cart-totals tr.shipping form a

    `, settings.shipping_methods_font_weight, (val) => {
            return (
                `
            font-weight : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li label,
    .shopengine-cart-totals tr.shipping p,
    .shopengine-cart-totals tr.shipping form a

    `, settings.shipping_methods_text_transform, (val) => {
            return (
                `
            text-transform : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li label,
    .shopengine-cart-totals tr.shipping p,
    .shopengine-cart-totals tr.shipping form a

    `, settings.shipping_methods_line_height, (val) => {
            return (
                `
            line-height : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li label,
    .shopengine-cart-totals tr.shipping p,
    .shopengine-cart-totals tr.shipping form a

    `, settings.shipping_methods_letter_spacing, (val) => {
            return (
                `
            letter-spacing : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals tr.shipping ul li label,
    .shopengine-cart-totals tr.shipping p,
    .shopengine-cart-totals tr.shipping form a

    `, settings.shipping_methods_title_wordspace, (val) => {
            return (
                `
            word-spacing : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .shop_table tr:not(:last-of-type)
    `, settings.shopengine_cart_totals_table_row_border_type, (val) => {
            return (
                `
            border-style : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .shop_table tr:not(:last-of-type)
    `, settings.shopengine_cart_totals_table_row_border_width, (val) => {
            return (
                `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .shop_table tr:not(:last-of-type)
    `, settings.shopengine_cart_totals_table_row_border_color, (val) => {
            return (
                `
            border-color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .shop_table tr:not(:first-of-type) td,
    .shopengine-cart-totals .shop_table tr:not(:first-of-type) th
    `, settings.shopengine_cart_totals_table_row_spacing, (val) => {
            return (
                `
            padding : ${val}px 0;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .shop_table tr:first-of-type td,
    .shopengine-cart-totals .shop_table tr:first-of-type th
    `, settings.shopengine_cart_totals_table_row_spacing, (val) => {
            return (
                `
           padding: 0 0 ${val}px 0;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-selection
    `, settings.shopengine_input_font_size, (val) => {
            return (
                `
            font-size : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-selection__rendered
    `, settings.shopengine_input_padding, (val) => {
            return (
                `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_margin, (val) => {
            return (
                `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-selection
    `, settings.shopengine_input_color, (val) => {
            return (
                `
            color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_background, (val) => {
            return (
                `
            background-color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_border_type, (val) => {
            return (
                `
            border-style : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_border_width, (val) => {
            return (
                `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_border_color, (val) => {
            return (
                `
            border-color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select:focus,
    .shopengine-cart-totals .woocommerce-shipping-calculator input:focus,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-selection:focus
    `, settings.shopengine_input_color_focus, (val) => {
            return (
                `
            color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select:focus,
    .shopengine-cart-totals .woocommerce-shipping-calculator input:focus,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container:focus
    `, settings.shopengine_input_background_focus, (val) => {
            return (
                `
            background-color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_border_focus_type, (val) => {
            return (
                `
            border-style : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_border_focus_width, (val) => {
            return (
                `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .woocommerce-shipping-calculator select,
    .shopengine-cart-totals .woocommerce-shipping-calculator input,
    .shopengine-cart-totals .woocommerce-shipping-calculator .select2-container
    `, settings.shopengine_input_border_focus_color, (val) => {
            return (
                `
            border-color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout a, 
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .checkout-button 
    

    `, settings.shopengine_cart_totals_checkout_button_color, (val) => {
            return (
                `
            color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout a, 
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .checkout-button 
    

    `, settings.checkout_button_font_size, (val) => {
            return (
                `
            font-size : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout a, 
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .checkout-button 
    

    `, settings.checkout_button_font_weight, (val) => {
            return (
                `
            font-weight : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout a, 
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .checkout-button 
    

    `, settings.checkout_button_text_transform, (val) => {
            return (
                `
            text-transform : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout a, 
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .checkout-button 
    

    `, settings.checkout_button_line_height, (val) => {
            return (
                `
            line-height : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout a, 
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .checkout-button 
    

    `, settings.checkout_button_letter_spacing, (val) => {
            return (
                `
            letter-spacing : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout a, 
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .checkout-button
    `, settings.checkout_button_title_wordspace, (val) => {
            return (
                `
            word-spacing : ${val}px;

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button
    `, settings.shopengine_cart_totals_checkout_button_background_color, (val) => {
            return (
                `
            background-color : ${val};

            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout button:hover, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button:hover 
    

    `, settings.shopengine_cart_totals_checkout_button_hover_color, (val) => {
            return (
                `
            color : ${val};

            `
            )
        })
        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout button:hover, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button:hover

    `, settings.shopengine_cart_totals_checkout_button_hover_border_color, (val) => {
            return (
                `
            border-color : ${val};
            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout .button,
    .shopengine-cart-totals .wc-proceed-to-checkout button

    `, settings.shopengine_cart_totals_checkout_button_border_type, (val) => {
            return (
                `
            border-style : ${val};
            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout .button,
    .shopengine-cart-totals .wc-proceed-to-checkout button

    `, settings.shopengine_cart_totals_checkout_button_border_width, (val) => {
            return (
                `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout .button,
    .shopengine-cart-totals .wc-proceed-to-checkout button

    `, settings.shopengine_cart_totals_checkout_button_border_color, (val) => {
            return (
                `
            border-color : ${val};
            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout button:hover, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button:hover
    

    `, settings.shopengine_cart_totals_hover_color, (val) => {
            return (
                `
            background-color : ${val};
            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    

    `, settings.shopengine_cart_totals_checkout_button_border_radius, (val) => {
            return (
                `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
            )
        })

        .add(`
    .shopengine-cart-totals .wc-proceed-to-checkout button, 
    .shopengine-cart-totals .wc-proceed-to-checkout .button, 
    `, settings.shopengine_cart_totals_checkout_button_padding, (val) => {
            return (
                `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
            )
        })

        .add(`.shopengine-cart-totals a, .shopengine-cart-totals h2,
    .shopengine-cart-totals tr, .shopengine-cart-totals td,
    .shopengine-cart-totals th, .shopengine-cart-totals .price,
    .shopengine-cart-totals .amount, .shopengine-cart-totals span`, settings.shopengine_global_font_family, (val) => (`
        font-family: ${val.family};
        `))

    return cssHelper.get()
}

export { Style }