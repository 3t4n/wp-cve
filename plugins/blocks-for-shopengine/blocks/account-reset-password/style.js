const Style = ({settings, breakpoints, cssHelper}) => {

    const {
        blockId, shopengine_account_reset_password_label_color, shopengine_account_reset_password_label_font_size, shopengine_account_reset_password_label_font_weight, shopengine_account_reset_password_label_font_style, shopengine_account_reset_password_label_text_transform, shopengine_account_reset_password_label_margin_bottom, shopengine_account_reset_password_input_height, shopengine_account_reset_password_input_width, shopengine_account_reset_password_input_font_size, shopengine_account_reset_password_input_color, shopengine_account_reset_password_input_bg_color, shopengine_account_reset_password_input_border_type, shopengine_account_reset_password_input_border_width, shopengine_account_reset_password_input_border_color, shopengine_account_reset_password_input_focus_color, shopengine_account_reset_password_input_focus_bg_color, shopengine_account_reset_password_input_focus_border_color, shopengine_account_reset_password_input_border_radius, shopengine_account_reset_password_input_padding, shopengine_account_reset_password_input_margin, 
        shopengine_account_reset_password_button_width, shopengine_account_reset_password_button_font_size, shopengine_account_reset_password_button_font_weight, shopengine_account_reset_password_button_font_style, shopengine_account_reset_password_button_text_transform, shopengine_account_reset_password_button_line_height, shopengine_account_reset_password_button_letter_spacing, shopengine_account_reset_password_button_word_spacing, shopengine_account_reset_password_button_text_color, shopengine_account_reset_password_button_bg_color, shopengine_account_reset_password_button_border_type, shopengine_account_reset_password_button_border_width, shopengine_account_reset_password_button_border_color, shopengine_account_reset_password_button_hover_text_color, shopengine_account_reset_password_button_hover_bg_color, shopengine_account_reset_password_button_hover_border_color, shopengine_account_reset_password_button_border_radius, shopengine_account_reset_password_button_padding, shopengine_account_reset_password_button_margin, shopengine_account_reset_password_global_font_family, 

    } = settings;

    cssHelper.add('.lost_reset_password .form-row label ',shopengine_account_reset_password_label_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row label ',shopengine_account_reset_password_label_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row label ',shopengine_account_reset_password_label_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row label ',shopengine_account_reset_password_label_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row label ',shopengine_account_reset_password_label_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row label ',shopengine_account_reset_password_label_margin_bottom, (val) => {
        return `
            margin-bottom: ${val}px;
        `
    })

    // Input Style Controls
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_height, (val) => {
        return `
            height: ${val}px;
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_width, (val) => {
        return `
            width: ${val}%;
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_border_type, (val) => {
        return `
            border: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"]:focus ',shopengine_account_reset_password_input_focus_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"]:focus ',shopengine_account_reset_password_input_focus_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"]:focus ',shopengine_account_reset_password_input_focus_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_border_radius, (val) => {
        return `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_padding, (val) => {
        return `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.lost_reset_password .form-row input[type="password"] ',shopengine_account_reset_password_input_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })

    // Button Style Controls
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_width, (val) => {
        return `
            width: ${val}%;
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_font_size, (val) => {
        return `
            font-size: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_font_style, (val) => {
        return `
            font-style: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_text_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_border_type, (val) => {
        return `
            border: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button:hover ',shopengine_account_reset_password_button_hover_text_color, (val) => {
        return `
            color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button:hover ',shopengine_account_reset_password_button_hover_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button:hover ',shopengine_account_reset_password_button_hover_border_color, (val) => {
        return `
            border-color: ${val};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_border_radius, (val) => {
        return `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_padding, (val) => {
        return `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add('.lost_reset_password .form-row button ',shopengine_account_reset_password_button_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    })
    cssHelper.add(`.lost_reset_password .form-row label, .lost_reset_password .form-row input, .lost_reset_password .form-row button `,shopengine_account_reset_password_global_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    })
    

    return cssHelper.get();
}

export { Style };