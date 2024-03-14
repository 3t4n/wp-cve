
const Style = ({settings, breakpoints, cssHelper}) => {
    const {blockId, shopengine_form_login_title_color, shopengine_form_login_title_link_color, shopengine_form_login_title_bg_color, shopengine_form_login_title_font_size, shopengine_form_login_title_font_weight, shopengine_form_login_title_transform, shopengine_form_login_title_textDecoration, shopengine_form_login_title_line_height, shopengine_form_login_title_word_spacing,  shopengine_form_login_title_border_style, shopengine_form_login_title_border_width, shopengine_form_login_title_border_color, shopengine_form_login_title_padding, shopengine_form_login_title_margin, shopengine_form_login_container_background, shopengine_form_login_container_border_style, shopengine_form_login_container_border_width, shopengine_form_login_container_border_color, shopengine_form_login_container_padding, 
    shopengine_form_login_input_label_color, shopengine_form_login_input_label_required_color, shopengine_form_login_input_label_font_family, shopengine_form_login_input_label_font_size, shopengine_form_login_input_label_font_weight, shopengine_form_login_input_label_text_transform, shopengine_form_login_input_label_Line_height, shopengine_form_login_input_label_letter_spacing, shopengine_form_login_input_label_wordspace, shopengine_form_login_input_color, shopengine_form_login_input_background, shopengine_form_login_input_border_type, shopengine_form_login_input_border_width, shopengine_form_login_input_border_color, shopengine_form_login_input_color_focus, shopengine_form_login_input_background_focus, shopengine_form_login_input_border_focus_type, shopengine_form_login_input_border_focus_width, shopengine_form_login_input_border_focus_color, shopengine_form_login_input_font_family, shopengine_form_login_input_font_size, shopengine_form_login_input_font_weight, shopengine_form_login_input_text_transform, shopengine_form_login_input_Line_height, shopengine_form_login_input_border_radius, shopengine_form_login_input_padding, shopengine_form_login_button_color_normal, shopengine_form_login_button_background_normal, shopengine_form_login_button_color_hover, shopengine_form_login_button_background_hover, shopengine_form_login_button_font_family, shopengine_form_login_button_font_size, shopengine_form_login_button_font_weight, shopengine_form_login_button_text_transform,  shopengine_form_login_button_Line_height, shopengine_form_login_button_letter_spacing, shopengine_form_login_button_wordspace, shopengine_form_login_button_border_type, shopengine_form_login_button_border_width, shopengine_form_login_button_border_color, shopengine_form_login_button_border_radius, shopengine_form_login_button_padding, shopengine_form_login_button_margin, shopengine_form_login_lost_password_btn_color_normal, shopengine_form_login_lost_password_btn_background_normal, 
    shopengine_form_login_lost_password_btn_color_hover, shopengine_form_login_lost_password_btn_background_hover, shopengine_form_login_lost_password_btn_font_family, shopengine_form_login_lost_password_btn_font_size, shopengine_form_login_lost_password_btn_font_weight, shopengine_form_login_lost_password_btn_text_transform, shopengine_form_login_lost_password_btn_text_decoration, shopengine_form_login_lost_password_btn_Line_height, shopengine_form_login_lost_password_btn_letter_spacing, shopengine_form_login_lost_password_btn_wordspace, shopengine_form_login_lost_password_btn_border_type, shopengine_form_login_lost_password_btn_border_width, shopengine_form_login_lost_password_btn_border_color, shopengine_form_login_lost_password_btn_border_radius, shopengine_form_login_lost_password_btn_padding, shopengine_form_login_lost_password_btn_margin, 
    
    } = settings;

    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info',shopengine_form_login_title_color, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info .showlogin',shopengine_form_login_title_link_color, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info',shopengine_form_login_title_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info', shopengine_form_login_title_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info', shopengine_form_login_title_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info', shopengine_form_login_title_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info .showlogin ', shopengine_form_login_title_textDecoration, (val) => {
        return `
            text-decoration: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info', shopengine_form_login_title_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info', shopengine_form_login_title_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info',shopengine_form_login_title_border_style, (val) => {
        return `
            border: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info',shopengine_form_login_title_border_width, (val) => {
        return `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info',shopengine_form_login_title_border_color, (val) => {
        return `
            border-color : ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login >.woocommerce-form-login-toggle >.woocommerce-info',shopengine_form_login_title_padding, (val) => {
        return `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .woocommerce-form-login-toggle .woocommerce-info',shopengine_form_login_title_margin, (val) => {
        return `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form',shopengine_form_login_container_border_style, (val) => {
        return `
            border : ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form',shopengine_form_login_container_background, (val) => {
        return `
            background-color : ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login > .shopengine-checkout-login-form',shopengine_form_login_container_border_width, (val) => {
        return `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form',shopengine_form_login_container_border_color, (val) => {
        return `
            border-color : ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form',shopengine_form_login_container_padding, (val) => {
        return `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_color, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label .required',shopengine_form_login_input_label_required_color, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_Line_height, (val) => {
        return `
            line-height: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row label ',shopengine_form_login_input_label_wordspace, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input',shopengine_form_login_input_color, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input',shopengine_form_login_input_background, (val) => {
        return `
            background-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form  .form-row input',shopengine_form_login_input_border_type, (val) => {
        return `
            border: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_border_color, (val) => {
        return `
            border-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input:focus ',shopengine_form_login_input_color_focus, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input:focus ',shopengine_form_login_input_background_focus, (val) => {
        return `
            background-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input:focus ',shopengine_form_login_input_border_focus_type, (val) => {
        return `
            border: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input:focus ',shopengine_form_login_input_border_focus_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input:focus ',shopengine_form_login_input_border_focus_color, (val) => {
        return `
            border-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_Line_height, (val) => {
        return `
            line-height: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_border_radius, (val) => {
        return `
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row input ',shopengine_form_login_input_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    // Login Button Styles
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_color_normal, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit:hover ',shopengine_form_login_button_color_hover, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_background_normal, (val) => {
        return `
            background-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit:hover ',shopengine_form_login_button_background_hover, (val) => {
        return `
            background-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_Line_height, (val) => {
        return `
            line-height: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_wordspace, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_border_type, (val) => {
        return `
            border: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_border_color, (val) => {
        return `
            border-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_border_radius, (val) => {
        return `
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .form-row .woocommerce-form-login__submit ',shopengine_form_login_button_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    // Lost Password Button Styles
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a  ',shopengine_form_login_lost_password_btn_color_normal, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a:hover ',shopengine_form_login_lost_password_btn_color_hover, (val) => {
        return `
            color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a',shopengine_form_login_lost_password_btn_background_normal, (val) => {
        return `
            background-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a:hover ',shopengine_form_login_lost_password_btn_background_hover, (val) => {
        return `
            background-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_text_decoration, (val) => {
        return `
            text-decoration: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_Line_height, (val) => {
        return `
            line-height: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_wordspace, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_border_type, (val) => {
        return `
            border: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_border_color, (val) => {
        return `
            border-color: ${val};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_border_radius, (val) => {
        return `
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    cssHelper.add('.shopengine-checkout-form-login .shopengine-checkout-login-form .lost_password a ',shopengine_form_login_lost_password_btn_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });
    
   return cssHelper.get()
}

export {Style};