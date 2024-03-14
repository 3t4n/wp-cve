const Style = ({ settings, breakpoints, cssHelper }) => {
  
   cssHelper.add(
    ".shopengine-account-details form p.form-row label",
    settings.shopengine_label_color,
    (val) => `
            color: ${val};
        `
  );

  cssHelper.add(
    ".shopengine-account-details form p.form-row label .required",
    settings.shopengine_label_required,
    (val) => `
           color: ${val};
        `
  );

   cssHelper.add(
      `.shopengine-account-details form p label,
      .shopengine-account-details form p label .required`,
      settings.shopengine_account_dashboard_label_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p label,
      .shopengine-account-details form p label .required`,
      settings.shopengine_account_dashboard_label_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p label,
      .shopengine-account-details form p label .required`,
      settings.shopengine_account_dashboard_label_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p label,
      .shopengine-account-details form p label .required`,
      settings.shopengine_account_dashboard_label_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p label,
      .shopengine-account-details form p label .required`,
      settings.shopengine_account_dashboard_label_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p label,
      .shopengine-account-details form p label .required`,
      settings.shopengine_account_dashboard_label_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p label,
      .shopengine-account-details form p label .required`,
      settings.shopengine_account_dashboard_label_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   cssHelper.add(
      `.shopengine-account-details form p.form-row label`,
      settings.shopengine_label_margin,
      (val) =>
      `
         margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   ); 

   
   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_account_dashboard_input_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_account_dashboard_input_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_account_dashboard_input_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_account_dashboard_input_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_account_dashboard_input_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_account_dashboard_input_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_account_dashboard_input_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   cssHelper.add(
      `.shopengine-account-details form p.form-row input`,
      settings.shopengine_input_padding,
      (val) =>
      `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   ); 


   cssHelper.add(
      `.shopengine-account-details form p.form-row input,
      .shopengine-account-details form p.form-row input::placeholder`,
      settings.shopengine_input_color_normal,
      (val) => `
              color: ${val};
          `
    );
  
    cssHelper.add(
      `.shopengine-account-details form p.form-row input`,
      settings.shopengine_input_background_normal,
      (val) => `
        background-color: ${val};
          `
    );



   cssHelper.add(
      `.shopengine-account-details form p.form-row input`,
      settings.shopengine_input_border_type,
      (val) => {
        return `
               border-style : ${val};
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p.form-row input`,
      settings.shopengine_input_border_width,
      (val) => {
        return (`
               border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
    
               `);
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p.form-row input`,
      settings.shopengine_input_border_color,
      (val) => { 
        return `
               border-color : ${val};
    
               `;
      }
    );




    cssHelper.add(
      `.shopengine-account-details form p.form-row input:focus`,
      settings.shopengine_input_color_focus,
      (val) => `
              color: ${val};
          `
    );
  
    cssHelper.add(
      `.shopengine-account-details form p.form-row input:focus`,
      settings.shopengine_input_background_focus,
      (val) => `
        background-color: ${val};
          `
    );



   cssHelper.add(
      `.shopengine-account-details form p.form-row input:focus`,
      settings.shopengine_input_border_focus_type,
      (val) => {
        return `
               border-style : ${val};
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p.form-row input:focus`,
      settings.shopengine_input_border_focus_width,
      (val) => {
        return `
               border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p.form-row input:focus`,
      settings.shopengine_input_border_focus_color,
      (val) => { 
        return `
               border-color : ${val};
    
               `;
      }
    );

   cssHelper.add(
      `.shopengine-account-details form p.form-row input`,
      settings.shopengine_input_border_radius,
      (val) =>
      `
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   );


   cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_form_legend_color,
      (val) => { 
        return `
              color: ${val};
    
               `;
      }
    );

    cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_account_dashboard_input_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_legend_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_legend_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_legend_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_legend_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_legend_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-details form fieldset legend`,
      settings.shopengine_legend_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   cssHelper.add(
      `.shopengine-account-details form fieldset`,
      settings.shopengine_form_outline_color,
      (val) => { 
        return `
        border-color: ${val};
    
               `;
      }
    );
    
    cssHelper.add(
      `.shopengine-account-details form p button.button`,
      settings.shopengine_form_button_font_size,
      (val) => `
         font-size: ${val};
      `
   );

    cssHelper.add(
      `.shopengine-account-details form p button.button`,
      settings.shopengine_form_button_normal_clr,
      (val) => { 
        return `
        color: ${val};
    
               `;
      }
    );

    cssHelper.add(
      `.shopengine-account-details form p button.button`,
      settings.shopengine_form_button_normal_bg,
      (val) => { 
        return `
        background: ${val};
    
               `;
      }
    );
   
    cssHelper.add(
      `.shopengine-account-details form p button.button`,
      settings.shopengine_form_button_border_type,
      (val) => {
        return `
               border-style : ${val}!important;
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p button.button`,
      settings.shopengine_form_button_border_width,
      (val) => {
        return `
               border-width : ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p button.button`,
      settings.shopengine_form_button_border_color,
      (val) => { 
        return `
               border-color : ${val}!important;
    
               `;
      }
    );    
    

    cssHelper.add(
      `.shopengine-account-details form p button.button:hover`,
      settings.shopengine_form_button_normal_clr_hover,
      (val) => { 
        return `
        color: ${val};
    
               `;
      }
    );

    cssHelper.add(
      `.shopengine-account-details form p button.button:hover`,
      settings.shopengine_form_button_normal_bg_hover,
      (val) => { 
        return `
        background: ${val};
    
               `;
      }
    );
   
    cssHelper.add(
      `.shopengine-account-details form p button.button:hover`,
      settings.shopengine_form_button_border_hover_type,
      (val) => {
        return `
               border-style : ${val}!important;
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p button.button:hover`,
      settings.shopengine_form_button_border_hover_width,
      (val) => {
        return `
               border-width : ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-details form p button.button:hover`,
      settings.shopengine_form_button_border_hover_color,
      (val) => { 
        return `
               border-color : ${val}!important;
    
               `;
      }
    );


   cssHelper.add(
      `.shopengine-account-details form p button.button`,
      settings.shopengine_save_button_radius,
      (val) =>
      `
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
         `
   );

   cssHelper.add(
      `.shopengine-account-details form button.button`,
      settings.shopengine_submit_button_padding,
      (val) =>
      `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   ); 

   cssHelper.add(
      `.shopengine-account-details form button.button`,
      settings.shopengine_submit_button_padding,
      (val) =>
      `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   ); 

  cssHelper.add(
    `.shopengine-account-details form p label,
    .shopengine-account-details form p label .required,
    .shopengine-account-details form p.form-row input,
    .shopengine-account-details form p.form-row input input::placeholder,
    .shopengine-account-details form fieldset legend,
    .shopengine-account-details form .form-row > span,
    .shopengine-account-details form p .button
   `,
    settings.shopengine_product_title_font_family,
    (val) => `
    font-family: '${val.family}';
   `
  );





  // cssHelper.add('.easin', 'font-size', '24px')
  // cssHelper.add('.easin', 'color', settings.simple_test.desktop)

  return cssHelper.get();
};

export { Style };
