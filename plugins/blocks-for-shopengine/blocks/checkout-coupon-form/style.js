
const Style = ({settings, breakpoints, cssHelper})=>{ 
    const {blockId, shopengine_info_color, shopengine_info_bg, shopengine_info_link_color, shopengine_info_link_hover_color, shopengine_info_font_size, shopengine_info_font_weight, shopengine_info_line_height, shopengine_info_word_spacing, shopengine_info_border_style, shopengine_info_border_width, shopengine_info_border_color,shopengine_info_padding, shopengine_description_font_size, shopengine_description_font_weight, shopengine_description_line_height, shopengine_description_word_spacing, shopengine_description_color, shopengine_checkout_coupon_form_label_color,shopengine_checkout_coupon_form_label_bg_color, shopengine_coupon_font_size, shopengine_coupon_font_weight, shopengine_coupon_line_height, shopengine_coupon_word_spacing,shopengine_coupon_border_style, shopengine_coupon_border_width, shopengine_coupon_border_color, shopengine_coupon_border_radius, shopengine_coupon_padding, shopengine_apply_button_word_spacing,shopengine_apply_button_font_size, shopengine_apply_button_font_weight, shopengine_apply_button_text_transform, shopengine_apply_button_line_height, shopengine_button_color, shopengine_button_bg, shopengine_button_border_style, shopengine_border_width, shopengine_border_color, shopengine_border_radius, shopengine_button_padding, shopengine_button_hover_color, shopengine_button_hover_bg, shopengine_button_box_shadow_horizontal, shopengine_button_box_shadow_vertical, shopengine_button_box_shadow_blur, shopengine_button_box_shadow_spread, shopengine_button_box_shadow_position,shopengine_checkout_coupon_form_label_space_between} = settings;

    let boxShadow = {
        horizontal : settings.shopengine_button_box_shadow_horizontal.desktop,
        vertical : settings.shopengine_button_box_shadow_vertical.desktop,
        blur : settings.shopengine_button_box_shadow_blur.desktop,
        spread : settings.shopengine_button_box_shadow_spread.desktop,
        shadow: settings.shopengine_button_box_shadow_color.desktop.hex
        ,
        position: settings.shopengine_button_box_shadow_position.desktop
    }

    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle', shopengine_info_color, (val) => {
        return `
        color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle', shopengine_info_bg, (val) => {
        return `
        background: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle', shopengine_info_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle', shopengine_info_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle', shopengine_info_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle', shopengine_info_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        margin: 0;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle', shopengine_info_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle::before', shopengine_info_color, (val) => {
        return `
        color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle a', shopengine_info_link_color, (val) => {
        return `
        color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .woocommerce-info-toggle a:hover', shopengine_info_link_hover_color, (val) => {
        return `
        color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form', shopengine_info_border_style, (val) => {
        return `
        border-style: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form', shopengine_info_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form', shopengine_info_border_width, (val) => {
        return `
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form p', shopengine_description_color, (val) => {
        return `
        color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form p', shopengine_description_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form p', shopengine_description_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form p', shopengine_description_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form p', shopengine_description_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form input, .shopengine-checkout-coupon-form input::placeholder', shopengine_checkout_coupon_form_label_color, (val) => {
        return `
        color: ${val} !important;
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form input, .shopengine-checkout-coupon-form input', shopengine_checkout_coupon_form_label_bg_color, (val) => {
        return `
        background-color: ${val} !important;
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form .shopengine-checkout-coupon .form-row.form-row-last', shopengine_checkout_coupon_form_label_space_between, (val) => {
        return(`
            margin-left: ${val}px;
        `)
    });

    cssHelper.add('.shopengine-checkout-coupon-form input', shopengine_coupon_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form input', shopengine_coupon_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form input', shopengine_coupon_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form input', shopengine_coupon_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form .form-row input#coupon_code', shopengine_coupon_border_style, (val) => {
        return `
        border-style: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row input#coupon_code', shopengine_coupon_border_width, (val) => {
        return `
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row input#coupon_code', shopengine_coupon_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form .form-row input', shopengine_coupon_border_radius, (val) => {
        return `
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row input', shopengine_coupon_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_apply_button_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_apply_button_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_apply_button_text_transform, (val) => {
        return `
        text-transform: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_apply_button_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_apply_button_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_button_color, (val) => {
        return `
        color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_button_bg, (val) => {
        return `
        background: ${val};
        cursor: pointer;
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_button_border_style, (val) => {
        return `
        border-style: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_border_width, (val) => {
        return `
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_border_radius, (val) => {
        return `
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', shopengine_button_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button', boxShadow, (val) => {
        return `
        box-shadow : ${boxShadow.horizontal}px ${boxShadow.vertical}px ${boxShadow.blur}px ${boxShadow.spread}px ${boxShadow.shadow} ${boxShadow.position}; 
        `
    } );

    cssHelper.add('.shopengine-checkout-coupon-form .form-row button:hover', shopengine_button_hover_color, (val) => {
        return `
        color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-coupon-form .form-row button:hover', shopengine_button_hover_bg, (val) => {
        return `
        background: ${val};
        `
    } );

    cssHelper.add(`.shopengine-checkout-coupon-form p,
    .shopengine-checkout-coupon-form .form-row button,
    .shopengine-checkout-coupon-form .woocommerce-info-toggle,
    .shopengine-checkout-coupon-form input`, settings.shopengine_global_font_family, (val) => {
        return `
        font-family: ${val.family};
        `
    });
    
    return cssHelper.get()
}


export {Style}