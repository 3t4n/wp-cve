
const Style = ({settings, breakpoints, cssHelper})=>{
    const {blockId, shopengine_content_label_color, shopengine_content_url_color, shopengine_content_url_hover_color, shopengine_content_description_color, shopengine_content_font_size, shopengine_content_font_weight,shopengine_content_line_height,shopengine_content_word_spacing,shopengine_body_font_size, shopengine_body_font_weight, shopengine_body_line_height, shopengine_body_word_spacing, shopengine_checkbox_position,shopengine_checkbox_margin,shopengine_payment_list_padding,shopengine_payment_list_border_style, shopengine_payment_list_border_width,shopengine_payment_list_border_color,shopengine_payment_description_padding, shopengine_button_border_radius, shopengine_button_padding,shopengine_space, shopengine_button_font_size,shopengine_button_font_weight,shopengine_button_text_transform,shopengine_button_decoration, shopengine_button_line_height, shopengine_button_word_spacing, shopengine_wrap_margin, shopengine_button_color,shopengine_button_bg,shopengine_button_hover_color,shopengine_button_hover_bg, shopengine_button_box_shadow_horizontal, shopengine_button_box_shadow_vertical, shopengine_button_box_shadow_blur, shopengine_button_box_shadow_spread, shopengine_button_box_shadow_position} = settings;

    const shopengine_button_full_show = settings.shopengine_button_full_show.desktop === true ? "" : "none";
    let boxShadow = {
        horizontal : settings.shopengine_button_box_shadow_horizontal.desktop,
        vertical : settings.shopengine_button_box_shadow_vertical.desktop,
        blur : settings.shopengine_button_box_shadow_blur.desktop,
        spread : settings.shopengine_button_box_shadow_spread.desktop,
        shadow: settings.shopengine_button_box_shadow_color.desktop.hex,
        position: settings.shopengine_button_box_shadow_position.desktop
    }

    cssHelper.add(`.shopengine-checkout-payment .place-order ${shopengine_button_full_show}`, shopengine_space, (val) => {
        return (`
        grid-template-columns: 100%;
        grid-gap: ${val}px 0;
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment .wc_payment_method label', shopengine_content_label_color, (val) => {
        return (`
            color: ${val} !important;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment .wc_payment_method label', shopengine_content_font_size, (val) => {
        return (`
            font-size: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment .wc_payment_method label', shopengine_content_font_weight, (val) => {
        return (`
            font-weight: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment .wc_payment_method label', shopengine_content_line_height, (val) => {
        return (`
            line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment .wc_payment_method label', shopengine_content_word_spacing, (val) => {
        return (`
            word-spacing: ${val}px;
        `)
    } );


    cssHelper.add('.shopengine-checkout-payment a', shopengine_content_url_color, (val) => {
        return (`
            color: ${val};
        `)
    } );


    cssHelper.add('.shopengine-checkout-payment a:hover', shopengine_content_url_hover_color, (val) => {
        return (`
            color: ${val};
        `)
    } );


    cssHelper.add('.shopengine-checkout-payment #payment .payment_methods .payment_box, .shopengine-checkout-payment #payment .payment_methods .payment_box p, .shopengine-checkout-payment #payment .payment_methods .payment_box a, .shopengine-checkout-payment #payment .woocommerce-privacy-policy-text p', shopengine_content_description_color, (val) => {
        return (`
            color: ${val};
        `)
    } );


    cssHelper.add('.shopengine-checkout-payment #payment .payment_methods .payment_box p', shopengine_payment_description_padding, (val) => {
        return (`
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `)
    } );


    cssHelper.add('.shopengine-checkout-payment #payment .payment_box p, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper p, .shopengine-checkout-payment #payment .payment_method_paypal p, .shopengine-checkout-payment #payment .payment_box a, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper a, .shopengine-checkout-payment #payment .payment_method_paypal a', shopengine_body_font_size, (val) => {
        return (`
            font-size: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment .payment_box p, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper p, .shopengine-checkout-payment #payment .payment_method_paypal p, .shopengine-checkout-payment #payment .payment_box a, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper a, .shopengine-checkout-payment #payment .payment_method_paypal a', shopengine_body_font_weight, (val) => {
        return (`
            font-weight: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment .payment_box p, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper p, .shopengine-checkout-payment #payment .payment_method_paypal p, .shopengine-checkout-payment #payment .payment_box a, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper a, .shopengine-checkout-payment #payment .payment_method_paypal a', shopengine_body_line_height, (val) => {
        return (`
            line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment .payment_box p, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper p, .shopengine-checkout-payment #payment .payment_method_paypal p, .shopengine-checkout-payment #payment .payment_box a, .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper a, .shopengine-checkout-payment #payment .payment_method_paypal a', shopengine_body_word_spacing, (val) => {
        return (`
            word-spacing: ${val}px;
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment .wc_payment_method input[type="radio"]'
    , settings.shopengine_payment_method_radio_input_color, (val) => {
        return (`
            accent-color: ${val};
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment .wc_payment_method input[type="radio"]', shopengine_checkbox_position, (val) => {
        return (`
            transform: translateY(${val}px);
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment .wc_payment_method input[type="radio"]', shopengine_checkbox_margin, (val) => {
        return (`
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `)
    } );


    cssHelper.add('.shopengine-checkout-payment #payment .payment_methods li', shopengine_payment_list_padding, (val) => {
        return (`
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment .payment_methods li', shopengine_payment_list_border_style, (val) => {
        return (`
            border-style: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment .payment_methods li', shopengine_payment_list_border_width, (val) => {
        return (`
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment .payment_methods li', shopengine_payment_list_border_color, (val) => {
        return (`
            border-color: ${val};
        `)
    } );


    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_padding, (val) => {
        return (`
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment #place_order', settings.shopengine_input_border_type, (val) => {
        return (`
            border-style: ${val};
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment #place_order', settings.shopengine_input_border_width, (val) => {
        return (`
            border-width: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment #place_order', settings.shopengine_input_border_color, (val) => {
        return (`
            border-color: ${val};
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_border_radius, (val) => {
        return (`
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_font_size, (val) => {
        return (`
            font-size: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_text_transform, (val) => {
        return (`
        text-transform: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_font_weight, (val) => {
        return (`
            font-weight: ${val}};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_decoration, (val) => {
        return (`
            text-decoration: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_color, (val) => {
        return (`
            color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_bg, (val) => {
        return (`
            background: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_line_height, (val) => {
        return (`
            line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order', shopengine_button_word_spacing, (val) => {
        return (`
            word-spacing: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order:hover', shopengine_button_hover_color, (val) => {
        return (`
            color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-checkout-payment #payment #place_order:hover', shopengine_button_hover_bg, (val) => {
        return (`
            background-color: ${val};
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment .form-row.place-order', shopengine_wrap_margin, (val) => {
        return (`
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `)
    } );

    cssHelper.add('.shopengine-checkout-payment #payment #place_order', boxShadow, (val) => {
        return (`
            box-shadow : ${boxShadow.horizontal}px ${boxShadow.vertical}px ${boxShadow.blur}px ${boxShadow.spread}px ${boxShadow.shadow} ${boxShadow.position}; 
        `)
    } );

    cssHelper.add(`.shopengine-checkout-payment .wc_payment_method label,
    .shopengine-checkout-payment #payment .payment_box p,
    .shopengine-checkout-payment #payment .payment_box a,
    .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper p,
    .shopengine-checkout-payment #payment .woocommerce-terms-and-conditions-wrapper a,
    .shopengine-checkout-payment #payment .payment_method_paypal p,
    .shopengine-checkout-payment #payment .payment_method_paypal a,
    .shopengine-checkout-payment #payment #place_order`, settings.shopengine_global_font_family, (val) => {
        return (`
        font-family: ${val.family};
    `)
    });

    return cssHelper.get()
}

export {Style}