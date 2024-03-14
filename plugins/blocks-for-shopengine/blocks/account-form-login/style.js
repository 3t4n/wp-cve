const Style = ({ settings, breakpoints, cssHelper }) => {
  cssHelper.add(
    ".woocommerce-form label",
    settings.shopengine_input_label_color,
    (val) => `
               color: ${val};
         `
  );

  cssHelper.add(
    ".woocommerce-form .required",
    settings.shopengine_input_required_indicator_color,
    (val) => `
               color: ${val};
         `
  );

  cssHelper.add(
    `.woocommerce-form .required, 
      .woocommerce-form label`,
    settings.shopengine_input_label_font_size,
    (val) => `
            font-size: ${val};
         `
  );

  cssHelper.add(
    ".woocommerce-form label",
    settings.shopengine_input_label_margin,
    (val) =>
      `
      margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
  );
  cssHelper.add(
    `.woocommerce-form .form-row input,
      .woocommerce-form .form-row textarea,
      .woocommerce-form .form-row .select2-selection`,
    settings.shopengine_typography_seconday,
    (val) => `
            font-size: ${val};
         `
  );

  cssHelper.add(
    `.woocommerce-form input:not(.woocommerce-form__input-checkbox),
      .woocommerce-form textarea,
      .woocommerce-form .select2-selection`,
    settings.shopengine_input_padding,
    (val) =>
      `
      padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
  );

  cssHelper.add(
    `.woocommerce-form input:not(.woocommerce-form__input-checkbox),
      .woocommerce-form textarea,
      .woocommerce-form .woocommerce-input-wrapper .select2-selection`,
    settings.shopengine_input_color,
    (val) => `
       color: ${val}!important;
   `
  );

  cssHelper.add(
    `.woocommerce-form input:not(.woocommerce-form__input-checkbox),
      .woocommerce-form textarea,
      .woocommerce-form .woocommerce-input-wrapper .select2-selection`,
    settings.shopengine_input_background,
    (val) => `
      background-color: ${val}!important;
   `
  );

  cssHelper.add(
    ".woocommerce-form input:not(.woocommerce-form__input-checkbox)",
    settings.shopengine_input_border_type,
    (val) => {
      return `
             border-style : ${val};
  
             `;
    }
  );

  cssHelper.add(
    ".woocommerce-form input:not(.woocommerce-form__input-checkbox)",
    settings.shopengine_input_border_width,
    (val) => {
      return `
             border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
  
             `;
    }
  );

  cssHelper.add(
    ".woocommerce-form input:not(.woocommerce-form__input-checkbox)",
    settings.shopengine_input_border_color,
    (val) => {
      return `
             border-color : ${val};
  
             `;
    }
  );

  cssHelper.add(
    `.woocommerce-form input:not(.woocommerce-form__input-checkbox):focus,
      .woocommerce-form textarea:focus,
      .woocommerce-form .woocommerce-input-wrapper .select2-selection:focus`,
    settings.shopengine_input_color_focus,
    (val) => `
       color: ${val}!important;
   `
  );

  cssHelper.add(
    `.woocommerce-form input:not(.woocommerce-form__input-checkbox):focus,
      .woocommerce-form textarea:focus,
      .woocommerce-form .woocommerce-input-wrapper .select2-selection:focus`,
    settings.shopengine_input_background_focus,
    (val) => `
      background-color: ${val}!important;
   `
  );

  cssHelper.add(
    ".woocommerce-form input:not(.woocommerce-form__input-checkbox):focus",
    settings.shopengine_input_border_focus_type,
    (val) => {
      return `
             border-style : ${val};
  
             `;
    }
  );

  cssHelper.add(
    ".woocommerce-form input:not(.woocommerce-form__input-checkbox):focus",
    settings.shopengine_input_border_focus_width,
    (val) => {
      return `
             border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
  
             `;
    }
  );

  cssHelper.add(
    ".woocommerce-form input:not(.woocommerce-form__input-checkbox):focus",
    settings.shopengine_input_border_focus_color,
    (val) => {
      return `
             border-color : ${val};
  
             `;
    }
  );

  cssHelper.add(
    ".woocommerce-form button.button",
    settings.shopengine_button_color,
    (val) => `
       color: ${val};
   `
  );

  cssHelper.add(
    ".woocommerce-form button.button",
    settings.shopengine_button_bg,
    (val) => `
       background-color: ${val};
   `
  );
  cssHelper.add(
    ".woocommerce-form button.button:hover",
    settings.shopengine_button_bg_hover,
    (val) => `
       background-color: ${val};
   `
  );

  cssHelper.add(
    `.woocommerce-form button.button`,
    settings.shopengine_button_font_size,
    (val) => `
            font-size: ${val};
         `
  );

  cssHelper.add(
    `.woocommerce-form button.button`,
    settings.shopengine_button_padding,
    (val) =>
      `
      padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
  );
  cssHelper.add(
    `.woocommerce-form button.button`,
    settings.shopengine_button_margin,
    (val) =>
      `
      margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
  );

  cssHelper.add(
    `.woocommerce-form button.button`,
    settings.shopengine_button_border_radius,
    (val) =>
      `
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
  );

  cssHelper.add(
    ".lost_password a",
    settings.shopengine_input_lost_password_color,
    (val) => `
       color: ${val};
   `
  );
  cssHelper.add(
    ".lost_password a:hover",
    settings.shopengine_input_lost_password_color_hover,
    (val) => `
       color: ${val};
   `
  );

  cssHelper.add(
    `.lost_password a`,
    settings.shopengine_input_lost_password_font_size,
    (val) => `
            font-size: ${val};
         `
  );

  cssHelper.add(
   `.woocommerce-form label,
   .woocommerce-form input,
   .woocommerce-form button,
   .woocommerce-form p,
   .woocommerce-form > *`,
   settings.shopengine_global_button_font_family,
   (val) => `
   font-family: ${val.family};
        `
 );


  return cssHelper.get();
};

export { Style };
