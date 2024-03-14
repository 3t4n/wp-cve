
const Style = ({ settings, cssHelper }) => {
    const { blockId, shopengine_heading_spacing_bottom, shopengine_heading_font_size, shopengine_heading_color, shopengine_form_label_color, shopengine_form_label_font_size, shopengine_form_label_font_weight, shopengine_form_label_spacing_bottom, shopengine_form_label_text_transform, shopengine_form_label_word_spacing, shopengine_textarea_color, shopengine_textarea_bg, shopengine_textarea_placeholder_color, shopengine_textarea_font_size, shopengine_textarea_font_weight, shopengine_textarea_text_transform, shopengine_textarea_line_height, shopengine_textarea_word_spacing, shopengine_textarea_height, shopengine_textarea_padding, shopengine_textarea_border_style, shopengine_textarea_border_width, shopengine_textarea_border_color, shopengine_textarea_border_color_focus, shopengine_textarea_border_radius } = settings;
    const shopengine_heading_show = settings.shopengine_heading_show.desktop === true ? "block" : "none";



    cssHelper.add('.shopengine-checkout-form-additional h3', {}, (val) => {
        return `
            display: ${shopengine_heading_show};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional h3', shopengine_heading_spacing_bottom, (val) => {
        return `
            padding-bottom: ${val}px !important;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional h3', shopengine_heading_font_size, (val) => {
        return `
            font-size: ${val}px !important;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional h3', shopengine_heading_color, (val) => {
        return `
            color: ${val};
        `
    });


    cssHelper.add('.shopengine-checkout-form-additional .form-row label', shopengine_form_label_color, (val) => {
        return `
            display: block;
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .form-row label', shopengine_form_label_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .form-row label', shopengine_form_label_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .form-row label', shopengine_form_label_spacing_bottom, (val) => {
        return `
            margin-bottom: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .form-row label', shopengine_form_label_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .form-row label', shopengine_form_label_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_color, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_bg, (val) => {
        return `
            background-color: ${val} !important;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .input-text', shopengine_textarea_border_radius, (val) => {
        return `
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-checkout-form-additional .input-text::placeholder', shopengine_textarea_placeholder_color, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-checkout-form-additional textarea[name=order_comments]', shopengine_textarea_height, (val) => {
        return `
            width: 100%;
            background-image: none;
            height: ${val}px; 
        `
    });

    cssHelper.add('.shopengine-checkout-form-additional .form-row .input-text', shopengine_textarea_border_style, (val) => {
        return `
            border-style: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .form-row .input-text', shopengine_textarea_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-additional .form-row .input-text', shopengine_textarea_border_color, (val) => {
        return `
            border-color: ${val};
        `
    });

    cssHelper.add('.shopengine-checkout-form-additional .form-row .input-text:focus', shopengine_textarea_border_color_focus, (val) => {
        return `
            border-color: ${val};
        `
    });


    cssHelper.add(`.shopengine-checkout-form-additional h3, .shopengine-checkout-form-additional .form-row label,
    .shopengine-checkout-form-additional .input-text`, settings.shopengine_global_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    });




    return cssHelper.get()
}

export { Style }