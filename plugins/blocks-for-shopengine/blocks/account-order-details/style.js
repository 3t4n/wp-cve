const Style = ({ settings, breakpoints, cssHelper }) => {


   const {
      shopengine_download_button_box_shadow_color,
      shopengine_download_button_box_shadow_horizontal,
      shopengine_download_button_box_shadow_vertical,
      shopengine_download_button_box_shadow_blur,
      shopengine_download_button_box_shadow_spread,
      shopengine_download_button_box_shadow_position,
  
      shopengine_order_btn_section_box_shadow_color,
      shopengine_order_btn_section_box_shadow_horizontal,
      shopengine_order_btn_section_box_shadow_vertical,
      shopengine_order_btn_section_box_shadow_blur,
      shopengine_order_btn_section_box_shadow_spread,
      shopengine_order_btn_section_box_shadow_position,
    } = settings;


   cssHelper.add(
      ".shopengine-account-order-details mark",
      settings.shopengine_account_order_hightlight_clr,
      (val) => `
              color: ${val};
          `
    );
  
    cssHelper.add(
      " .shopengine-account-order-details mark",
      settings.shopengine_account_order_hightlight_bg_clr,
      (val) => `
            color: ${val};
          `
    );    
    cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_clr,
      (val) => `
            color: ${val};
          `
    );

    cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   cssHelper.add(
      `.shopengine-account-order-details h1,
      .shopengine-account-order-details h2,
      .shopengine-account-order-details h3,
      .shopengine-account-order-details h4,
      .shopengine-account-order-details h5,
      .shopengine-account-order-details h6`,
      settings.shopengine_account_order_heading_margin,
      (val) =>
        `
          border" 0px;
          margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
          `
    );

      cssHelper.add(
       ".shopengine-account-order-details table thead tr th",
       settings.shopengine_account_table_heading_color,
       (val) => `
               color: ${val};
           `
     );      
     
     cssHelper.add(
       ".shopengine-account-order-details table thead",
       settings.shopengine_account_table_heading_background,
       (val) => `
         background-color: ${val};
           `
     );

     cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_heading_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_heading_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_heading_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_heading_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_heading_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_heading_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_heading_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   cssHelper.add(
      `.shopengine-account-order-details table tr td:first-child,
      .shopengine-account-order-details table tr  th:first-child`,
      settings.shopengine_account_table_horizontial_padding,
      (val) => `
        padding-left: ${val}!important;
        padding-right: ${val}!important;
      `
   );

   cssHelper.add(
      `.shopengine-account-order-details table thead th`,
      settings.shopengine_account_table_vertical_padding,
      (val) =>
        `
          padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
          `
    );
   cssHelper.add(
      `.shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr .amount,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr .amount,`,
      settings.shopengine_account_table_text_clr,
      (val) => `
               color: ${val};
         `
   );




   cssHelper.add(
      `.shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr span,
      .shopengine-account-order-details table tbody tr h4,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr span,
      .shopengine-account-order-details table tfoot tr h4`,
      settings.shopengine_orders_body_text_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr span,
      .shopengine-account-order-details table tbody tr h4,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr span,
      .shopengine-account-order-details table tfoot tr h4`,
      settings.shopengine_orders_body_text_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr span,
      .shopengine-account-order-details table tbody tr h4,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr span,
      .shopengine-account-order-details table tfoot tr h4`,
      settings.shopengine_orders_body_text_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr span,
      .shopengine-account-order-details table tbody tr h4,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr span,
      .shopengine-account-order-details table tfoot tr h4`,
      settings.shopengine_orders_body_text_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr span,
      .shopengine-account-order-details table tbody tr h4,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr span,
      .shopengine-account-order-details table tfoot tr h4`,
      settings.shopengine_orders_body_text_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr span,
      .shopengine-account-order-details table tbody tr h4,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr span,
      .shopengine-account-order-details table tfoot tr h4`,
      settings.shopengine_orders_body_text_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details table tbody tr th,
      .shopengine-account-order-details table tbody tr td,
      .shopengine-account-order-details table tbody tr span,
      .shopengine-account-order-details table tbody tr h4,
      .shopengine-account-order-details table tfoot tr th,
      .shopengine-account-order-details table tfoot tr td,
      .shopengine-account-order-details table tfoot tr span,
      .shopengine-account-order-details table tfoot tr h4`,
      settings.shopengine_orders_body_text_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );
   
     cssHelper.add(
       `.shopengine-account-order-details table tfoot tr .download-product a,
       .shopengine-account-order-details table tfoot tr .product-name a,
       .shopengine-account-order-details table tfoot tr th a,
       .shopengine-account-order-details table tbody tr .download-product a,
       .shopengine-account-order-details table tbody tr .product-name a,
       .shopengine-account-order-details table tbody tr th a`,
       settings.shopengine_account_table_link_color,
       (val) => `
               color: ${val};
           `
     );     
     
     cssHelper.add(
       `.shopengine-account-order-details table tfoot tr .download-product a:hover,
       .shopengine-account-order-details table tfoot tr .product-name a:hover,
       .shopengine-account-order-details table tfoot tr th a:hover,
       .shopengine-account-order-details table tbody tr .download-product a:hover,
       .shopengine-account-order-details table tbody tr .product-name a:hover,
       .shopengine-account-order-details table tbody tr th a:hover`,
       settings.shopengine_account_table_link_color_hover,
       (val) => `
               color: ${val};
           `
     );

     cssHelper.add(
      `.shopengine-account-order-details table tfoot tr .download-product a,
      .shopengine-account-order-details table tfoot tr .product-name a,
      .shopengine-account-order-details table tfoot tr th a,
      .shopengine-account-order-details table tbody tr .download-product a,
      .shopengine-account-order-details table tbody tr .product-name a,
      .shopengine-account-order-details table tbody tr th a`,
      settings.shopengine_account_table_link_text_decoration,
      (val) => `
         text-decoration: ${val};
          `
    );
    cssHelper.add(
      `.shopengine-account-order-details table tbody tr, 
      .shopengine-account-order-details table tfoot tr`,
      settings.shopengine_account_table_border_bottom_border_type,
      (val) => {
        return `
               border-style : ${val};
    
               `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-order-details table tbody tr, 
      .shopengine-account-order-details table tfoot tr`,
      settings.shopengine_account_table_border_bottom_border_width,
      (val) => {
        return `
          border-bottom-width: ${val};
          `;
      }
    );
  
    cssHelper.add(
      `.shopengine-account-order-details table tbody tr, 
      .shopengine-account-order-details table tfoot tr`,
      settings.shopengine_account_table_border_bottom_border_color,
      (val) => {
        return `
               border-color : ${val};
    
               `;
      }
    );

   cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_padding,
      (val) =>
        `
          padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
          `
    );

    cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_margin,
      (val) =>
        `
          margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
          `
    );

    cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_padding_radius,
      (val) =>
        `
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
    );

    cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_typography_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_typography_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_typography_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_typography_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_typography_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_typography_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
      settings.shopengine_download_button_typography_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   let color = shopengine_download_button_box_shadow_color.desktop.rgb;
   let horizontal = shopengine_download_button_box_shadow_horizontal.desktop + "px";
   let vertical = shopengine_download_button_box_shadow_vertical.desktop + "px";
   let blur = shopengine_download_button_box_shadow_blur.desktop + "px";
   let spread = shopengine_download_button_box_shadow_spread.desktop + "px";
   let position = shopengine_download_button_box_shadow_position.desktop;
 
   cssHelper.add(
     `.shopengine-account-order-details .woocommerce-table tbody tr td.download-file a`,
     {},
     (val) => {
       return `
                box-shadow: ${horizontal} ${vertical} ${blur} ${spread} rgba(${color.r}, ${color.g}, ${color.b}, ${color.a}) ${position};
            `;
     }
   );

   cssHelper.add(
      ".shopengine-account-order-details .woocommerce-table tbody tr td.download-file a",
      settings.shopengine_download_button_tab_clr,
      (val) => `
               color: ${val}!important;
         `
   );  
   
   cssHelper.add(
      ".shopengine-account-order-details .woocommerce-table tbody tr td.download-file a",
      settings.shopengine_download_button_tab_bg,
      (val) => `
         background: ${val}!important;
         `
   );   
   
   cssHelper.add(
      ".shopengine-account-order-details .woocommerce-table tbody tr td.download-file a:hover",
      settings.shopengine_download_button_tab_hover_clr,
      (val) => `
         color: ${val}!important;
         `
   );

   cssHelper.add(
      ".shopengine-account-order-details .woocommerce-table tbody tr td.download-file a:hover",
      settings.shopengine_download_button_tab_hover_bg,
      (val) => `
          background-color: ${val}!important;
         `
   );

   cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_again_button_margin,
      (val) =>
        `
          margin: ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
          `
    );
    cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_padding,
      (val) =>
        `
          padding: ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
          `
    );    
    cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_radius,
      (val) =>
        `
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
          `
    );


    cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details p.order-again a`,
      settings.shopengine_order_btn_section_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   let colors = shopengine_order_btn_section_box_shadow_color.desktop.rgb;
   let horizontals = shopengine_order_btn_section_box_shadow_horizontal.desktop + "px";
   let verticals = shopengine_order_btn_section_box_shadow_vertical.desktop + "px";
   let blurs = shopengine_order_btn_section_box_shadow_blur.desktop + "px";
   let spreads = shopengine_order_btn_section_box_shadow_spread.desktop + "px";
   let positions = shopengine_order_btn_section_box_shadow_position.desktop;
 
   cssHelper.add(
     `.shopengine-account-order-details p.order-again a`,
     {},
     (val) => {
       return `
                box-shadow: ${horizontals} ${verticals} ${blurs} ${spreads} rgba(${colors.r}, ${colors.g}, ${colors.b}, ${colors.a}) ${positions};
            `;
     }
   );

   cssHelper.add(
   ".shopengine-account-order-details p.order-again a",
   settings.shopengine_order_btn_normal_clr,
   (val) => `
            color: ${val};
         `
   );   
   cssHelper.add(
   ".shopengine-account-order-details p.order-again a",
   settings.shopengine_order_btn_normal_bg,
   (val) => `
        background-color: ${val};
         `
   );

   cssHelper.add(
   ".shopengine-account-order-details p.order-again a:hover",
   settings.shopengine_order_btn_hover_clr,
   (val) => `
         color: ${val};
         `
   );   
   cssHelper.add(
   ".shopengine-account-order-details p.order-again a:hover",
   settings.shopengine_order_btn_hover_bg,
   (val) => `
         background-color: ${val};
         `
   );

   cssHelper.add(
   `.shopengine-account-order-details .addresses address,
   .shopengine-account-order-details .addresses p,
   .shopengine-account-order-details .woocommerce-customer-details address,
   .shopengine-account-order-details .woocommerce-customer-details p`,
   settings.shopengin_account_address_text_clr,
   (val) => `
         color: ${val};
         `
   );
      
   cssHelper.add(
      `.shopengine-account-order-details .addresses address,
      .shopengine-account-order-details .addresses p,
      .shopengine-account-order-details .woocommerce-customer-details address,
      .shopengine-account-order-details .woocommerce-customer-details p`,
      settings.shopengin_account_address_text_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .addresses address,
      .shopengine-account-order-details .addresses p,
      .shopengine-account-order-details .woocommerce-customer-details address,
      .shopengine-account-order-details .woocommerce-customer-details p`,
      settings.shopengin_account_address_text_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .addresses address,
      .shopengine-account-order-details .addresses p,
      .shopengine-account-order-details .woocommerce-customer-details address,
      .shopengine-account-order-details .woocommerce-customer-details p`,
      settings.shopengin_account_address_text_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .addresses address,
      .shopengine-account-order-details .addresses p,
      .shopengine-account-order-details .woocommerce-customer-details address,
      .shopengine-account-order-details .woocommerce-customer-details p`,
      settings.shopengin_account_address_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .addresses address,
      .shopengine-account-order-details .addresses p,
      .shopengine-account-order-details .woocommerce-customer-details address,
      .shopengine-account-order-details .woocommerce-customer-details p`,
      settings.shopengin_account_address_text_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .addresses address,
      .shopengine-account-order-details .addresses p,
      .shopengine-account-order-details .woocommerce-customer-details address,
      .shopengine-account-order-details .woocommerce-customer-details p`,
      settings.shopengin_account_address_text_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-order-details .addresses address,
      .shopengine-account-order-details .addresses p,
      .shopengine-account-order-details .woocommerce-customer-details address,
      .shopengine-account-order-details .woocommerce-customer-details p`,
      settings.shopengin_account_address_text_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

  cssHelper.add(
    `.shopengine-account-order-details h1,
    .shopengine-account-order-details h2,
    .shopengine-account-order-details h3,
    .shopengine-account-order-details h4,
    .shopengine-account-order-details h5,
    .shopengine-account-order-details h6,
    .shopengine-account-order-details table thead th,
    .shopengine-account-order-details table tbody tr th,
    .shopengine-account-order-details table tbody tr td,
    .shopengine-account-order-details table tbody tr span,
    .shopengine-account-order-details table tbody tr h4,
    .shopengine-account-order-details table tfoot tr th,
    .shopengine-account-order-details table tfoot tr td,
    .shopengine-account-order-details table tfoot tr span,
    .shopengine-account-order-details table tfoot tr h4,
    .shopengine-account-order-details .woocommerce-table tbody tr td.download-file a,
    .shopengine-account-order-details p.order-again a,
    .shopengine-account-order-details .addresses address,
    .shopengine-account-order-details .addresses p,
    .shopengine-account-order-details .woocommerce-customer-details address,
    .shopengine-account-order-details .woocommerce-customer-details p`,
    settings.shopengine_product_title_font_family,
    (val) => `
            font-family: '${val.family}';
        `
  );

  return cssHelper.get();
};

export { Style };
