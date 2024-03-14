const Style = ({ cssHelper, settings }) => {
  cssHelper
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row label",
      settings?.shopengine_account_form_register_label_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row label span",
      settings?.shopengine_account_form_register_label_indicator_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row label",
      settings?.shopengine_account_form_register_label_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_margin,
      (val) => {
        return `padding: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left} !important`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_margin,
      (val) => {
        return `margin: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left} !important;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_background_color,
      (val) => {
        return `background-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_border_style,
      (val) => {
        return `border-style: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_border_width,
      (val) => {
        return `border-width: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input",
      settings.shopengine_account_form_register_input_border_color,
      (val) => {
        return `border-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input:focus",
      settings.shopengine_account_form_register_input_focus_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input:focus",
      settings.shopengine_account_form_register_input_focus_background_color,
      (val) => {
        return `background-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input:focus",
      settings.shopengine_account_form_register_input_focus_border_style,
      (val) => {
        return `border-style: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input:focus",
      settings.shopengine_account_form_register_input_focus_border_width,
      (val) => {
        return `border-width: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-form-row .woocommerce-Input:focus",
      settings.shopengine_account_form_register_input_focus_border_color,
      (val) => {
        return `border-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-pending-message ",
      settings.shopengine_account_form_register_message_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-privacy-policy-text p ",
      settings.shopengine_account_form_register_message_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-privacy-policy-text p .woocommerce-privacy-policy-link",
      settings?.shopengine_account_form_register_message_link_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-privacy-policy-text p .woocommerce-privacy-policy-link",
      settings?.shopengine_account_form_register_message_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-pending-message",
      settings?.shopengine_account_form_register_message_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form .woocommerce-privacy-policy-text p",
      settings?.shopengine_account_form_register_message_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form-register .woocommerce-pending-message ",
      settings?.shopengine_account_form_register_message_line_spacing,
      (val) => {
        return `line-height: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form-register .woocommerce-privacy-policy-text p ",
      settings?.shopengine_account_form_register_message_line_spacing,
      (val) => {
        return `line-height: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form-register .woocommerce-form-row .woocommerce-form-register__submit",
      settings?.shopengine_account_form_register_button_color,
      (val) => {
        return `color: ${val} !important;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form-register .woocommerce-form-row .woocommerce-form-register__submit",
      settings?.shopengine_account_form_register_button_background_color,
      (val) => {
        return `background-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form-register .woocommerce-form-row .woocommerce-form-register__submit:hover",
      settings?.shopengine_account_form_register_button_background_hover_color,
      (val) => {
        return `background-color: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form-register .woocommerce-form-row .woocommerce-form-register__submit",
      settings?.shopengine_account_form_register_button_padding,
      (val) => {
        return `padding: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register .woocommerce-form-register .woocommerce-form-row .woocommerce-form-register__submit",
      settings?.shopengine_account_form_register_button_margin,
      (val) => {
        return `margin: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left} !important;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-form-register",
      settings?.shopengine_account_form_register_button_font_family,
      (val) => {
        return `font-family: ${val?.family} !important;`;
      }
    );

  return cssHelper.get();
};
export { Style };
