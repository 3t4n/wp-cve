const Style = ({ settings, breakpoints, cssHelper }) => {
    const {blockId, shopengine_checkout_order_pay_header_color, shopengine_checkout_order_pay_header_background_color, shopengine_checkout_order_pay_header_border_type, 
        shopengine_checkout_order_pay_header_border_width, shopengine_checkout_order_pay_header_border_color, shopengine_checkout_order_pay_header_font_size, shopengine_checkout_order_pay_header_font_weight, shopengine_checkout_order_pay_header_font_style, shopengine_checkout_order_pay_header_text_transform, shopengine_checkout_order_pay_header_line_height, shopengine_checkout_order_pay_header_letter_spacing, shopengine_checkout_order_pay_header_word_spacing, shopengine_checkout_order_pay_header_padding, shopengine_checkout_order_pay_body_color, shopengine_checkout_order_pay_body_background_color, 
        shopengine_checkout_order_pay_body_font_size, shopengine_checkout_order_pay_body_font_weight, shopengine_checkout_order_pay_body_font_style, shopengine_checkout_order_pay_body_text_transform, shopengine_checkout_order_pay_body_line_height, shopengine_checkout_order_pay_body_letter_spacing, shopengine_checkout_order_pay_body_word_spacing, shopengine_checkout_order_pay_body_border_type, shopengine_checkout_order_pay_body_border_width, shopengine_checkout_order_pay_body_border_color, shopengine_checkout_order_pay_body_padding, shopengine_checkout_order_pay_footer_color, 
        shopengine_checkout_order_pay_footer_background_color, shopengine_checkout_order_pay_footer_font_size, shopengine_checkout_order_pay_footer_font_weight, shopengine_checkout_order_pay_footer_font_style, shopengine_checkout_order_pay_footer_text_transform, shopengine_checkout_order_pay_footer_line_height, shopengine_checkout_order_pay_footer_letter_spacing, shopengine_checkout_order_pay_footer_word_spacing, shopengine_checkout_order_pay_footer_border_type, shopengine_checkout_order_pay_footer_border_width, shopengine_checkout_order_pay_footer_border_color, shopengine_checkout_order_pay_footer_padding, 
        shopengine_checkout_order_pay_payment_method_background_color, shopengine_checkout_order_pay_payment_checkbox_label_color, shopengine_checkout_order_pay_payment_method_label_gap, shopengine_checkout_order_pay_payment_label_font_size, shopengine_checkout_order_pay_payment_label_font_weight, shopengine_checkout_order_pay_payment_label_font_style, shopengine_checkout_order_pay_payment_label_text_transform, shopengine_checkout_order_pay_payment_label_line_height,  shopengine_checkout_order_pay_payment_label_letter_spacing, shopengine_checkout_order_pay_payment_checkbox_color, shopengine_checkout_order_pay_payment_method_checkbox_position, shopengine_checkout_order_pay_payment_desc_color, shopengine_checkout_order_pay_payment_method_desc_background_color, shopengine_checkout_order_pay_payment_desc_font_size,shopengine_checkout_order_pay_payment_desc_font_weight, shopengine_checkout_order_pay_payment_desc_font_style, shopengine_checkout_order_pay_payment_desc_text_transform, 
		shopengine_checkout_order_pay_payment_desc_line_height, shopengine_checkout_order_pay_payment_desc_letter_spacing, shopengine_checkout_order_pay_payment_desc_word_spacing,  shopengine_checkout_order_pay_payment_methods_padding, shopengine_checkout_order_pay_payment_methods_desc_padding, shopengine_checkout_order_pay_payment_methods_desc_margin, shopengine_checkout_order_pay_order_button_color, shopengine_checkout_order_pay_order_button_background_color, shopengine_checkout_order_pay_order_button_hover_color,shopengine_checkout_order_pay_order_button_background_hover_color, shopengine_checkout_order_pay_order_button_font_size, shopengine_checkout_order_pay_order_button_font_weight, shopengine_checkout_order_pay_order_button_font_style, shopengine_checkout_order_pay_order_button_text_transform, 
        shopengine_checkout_order_pay_order_button_line_height, shopengine_checkout_order_pay_order_button_letter_spacing, shopengine_checkout_order_pay_order_button_word_spacing, shopengine_checkout_order_pay_order_button_border_type, shopengine_checkout_order_pay_order_button_border_width, shopengine_checkout_order_pay_order_button_border_color, 
        shopengine_checkout_order_pay_order_button_padding, shopengine_checkout_order_pay_order_button_margin, shopengine_checkout_order_pay_privacy_color, shopengine_checkout_order_pay_privacy_link_color, shopengine_checkout_order_pay_privacy_font_size, shopengine_checkout_order_pay_privacy_font_weight, shopengine_checkout_order_pay_privacy_font_style, shopengine_checkout_order_pay_privacy_text_transform, shopengine_checkout_order_pay_privacy_line_height, shopengine_checkout_order_pay_privacy_letter_spacing, shopengine_checkout_order_pay_privacy_word_spacing, shopengine_checkout_order_pay_privacy_padding, 
    } = settings;

    /** Table Header Styles */ 
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th',shopengine_checkout_order_pay_header_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead ',shopengine_checkout_order_pay_header_background_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_line_height, (val) => {
        return `
            line-height: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_letter_spacing, (val) => {
        return `
            letter-spacing: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_word_spacing, (val) => {
        return `
            word-spacing: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_border_type, (val) => {
        return `
            border-bottom: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th ',shopengine_checkout_order_pay_header_border_width, (val) => {
        return `
            border-bottom-width: ${val}px;
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table thead tr th',shopengine_checkout_order_pay_header_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table > thead > tr > th ',shopengine_checkout_order_pay_header_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })

    /** Table Body Styles */ 
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td',shopengine_checkout_order_pay_body_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody ',shopengine_checkout_order_pay_body_background_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_line_height, (val) => {
        return `
            line-height: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_border_type, (val) => {
        return `
            border-bottom: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td ',shopengine_checkout_order_pay_body_border_width, (val) => {
        return `
            border-bottom-width: ${val}px;
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tbody tr td',shopengine_checkout_order_pay_body_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table > tbody > tr > td ',shopengine_checkout_order_pay_body_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })

    /** Table Footer Styles */ 
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay .shop_table tfoot ',shopengine_checkout_order_pay_footer_background_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `, shopengine_checkout_order_pay_footer_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_line_height, (val) => {
        return `
            line-height: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_border_type, (val) => {
        return `
            border-bottom: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_border_width, (val) => {
        return `
            border-bottom-width: ${val}px;
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `,shopengine_checkout_order_pay_footer_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay .shop_table tfoot tr th, .shopengine-checkout-order-pay .shop_table tfoot tr td `, shopengine_checkout_order_pay_footer_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })

    /** Checkout Payment Methods Styles */ 
    cssHelper.add('.shopengine-checkout-order-pay #payment .wc_payment_methods ',shopengine_checkout_order_pay_payment_method_background_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay #payment > .wc_payment_methods `, shopengine_checkout_order_pay_payment_methods_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_checkbox_label_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_method_label_gap, (val) => {
        return `
            margin-left: ${val}px;
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_label_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_label_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_label_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_label_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_label_line_height, (val) => {
        return `
            line-height: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > label ',shopengine_checkout_order_pay_payment_label_letter_spacing, (val) => {
        return `
            letter-spacing: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay #payment > ul > li > input[type=radio]:checked::before  `,shopengine_checkout_order_pay_payment_checkbox_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay #payment > ul > li > input[type=radio]:checked `,shopengine_checkout_order_pay_payment_checkbox_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .input-radio ',shopengine_checkout_order_pay_payment_method_checkbox_position, (val) => {
        return `
            transform: translateY(${val}px);
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_line_height, (val) => {
        return `
            line-height: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box > p ',shopengine_checkout_order_pay_payment_desc_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box',shopengine_checkout_order_pay_payment_method_desc_background_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment > ul > li > .payment_box ',shopengine_checkout_order_pay_payment_methods_desc_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add(`.shopengine-checkout-order-pay #payment > ul > li > .payment_box `, shopengine_checkout_order_pay_payment_methods_desc_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })

    /** Checkout Order Button Styles */ 
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_background_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button:hover ',shopengine_checkout_order_pay_order_button_hover_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button:hover ',shopengine_checkout_order_pay_order_button_background_hover_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_line_height, (val) => {
        return `
            line-height: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_letter_spacing, (val) => {
        return `
            letter-spacing: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_word_spacing, (val) => {
        return `
            word-spacing: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_border_type, (val) => {
        return `
            border: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row button ',shopengine_checkout_order_pay_order_button_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p .woocommerce-privacy-policy-link ',shopengine_checkout_order_pay_privacy_link_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_line_height, (val) => {
        return `
            line-height: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_letter_spacing, (val) => {
        return `
            letter-spacing: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_word_spacing, (val) => {
        return `
            word-spacing: ${val};
        `
    })
    cssHelper.add('.shopengine-checkout-order-pay #payment .form-row .woocommerce-terms-and-conditions-wrapper p ',shopengine_checkout_order_pay_privacy_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    
    return cssHelper.get()
}

export { Style };