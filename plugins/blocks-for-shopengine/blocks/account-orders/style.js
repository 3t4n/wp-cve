const Style = ({ settings, breakpoints, cssHelper }) => {
  const {
    shopengine_orders_button_view_box_shadow_color,
    shopengine_orders_button_view_box_horizontal,
    shopengine_orders_button_view_box_vertical,
    shopengine_orders_button_view_box_blur,
    shopengine_orders_button_view_box_spread,
    shopengine_orders_button_view_box_position,

    shopengine_orders_pagination_box_shadow_color,
    shopengine_orders_pagination_box_shadow_horizontal,
    shopengine_orders_pagination_box_shadow_vertical,
    shopengine_orders_pagination_box_shadow_blur,
    shopengine_orders_pagination_box_shadow_spread,
    shopengine_orders_pagination_box_shadow_position,
  } = settings;

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header,
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_color,
    (val) => `
            color: ${val};
         `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table thead,
       .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_background,
    (val) => `
      background-color: ${val};
         `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_font_family,
    (val) => `
       font-family: ${val.family};
 
   `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_font_size,
    (val) => `
        font-size: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_font_weight,
    (val) => `
        font-weight: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_font_style,
    (val) => `
        font-style: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_text_transform,
    (val) => `
        text-transform: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
       .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_line_height,
    (val) => `
        line-height: ${val}px;
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_letter_spacing,
    (val) => `
        letter-spacing: ${val}px;
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__header, 
      .shopengine-account-orders .woocommerce-order-details thead`,
    settings.shopengine_orders_header_wordspace,
    (val) => `
        word-spacing: ${val}px;
     `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table tbody`,
    settings.shopengine_download_body_bg_color,
    (val) => `
      background-color: ${val};
         `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell,
      .shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell .amount,
      .shopengine-account-orders .woocommerce-order-details tfoot,
      .shopengine-account-orders .woocommerce-order-details tbody`,
    settings.shopengine_orders_body_color,
    (val) => `
        color: ${val};
         `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell-order-number a,
      .shopengine-account-orders .woocommerce-order-details tbody a`,
    settings.shopengine_orders_body_link_color,
    (val) => `
        color: ${val};
         `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell-order-number a:hover,
      .shopengine-account-orders .woocommerce-order-details tbody a:hover`,
    settings.shopengine_orders_body_link_hover_color,
    (val) => `
        color: ${val};
         `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_font_family,
    (val) => `
       font-family: ${val.family};
 
   `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_font_size,
    (val) => `
        font-size: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_font_weight,
    (val) => `
        font-weight: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_font_style,
    (val) => `
        font-style: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_text_transform,
    (val) => `
        text-transform: ${val};
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_line_height,
    (val) => `
        line-height: ${val}px;
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_letter_spacing,
    (val) => `
        letter-spacing: ${val}px;
     `
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table > tbody .woocommerce-orders-table__row td,
      .shopengine-account-orders .woocommerce-order-details tbody, 
      .shopengine-account-orders .woocommerce-order-details tfoot`,
    settings.shopengine_orders_body_wordspace,
    (val) => `
        word-spacing: ${val}px;
     `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__row, 
      .shopengine-account-orders .woocommerce-order-details tbody tr, 
      .shopengine-account-orders .woocommerce-order-details tfoot tr`,
    settings.shopengine_orders_body_row_border_type,
    (val) => {
      return `
             border-style : ${val};
  
             `;
    }
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__row, 
      .shopengine-account-orders .woocommerce-order-details tbody tr, 
      .shopengine-account-orders .woocommerce-order-details tfoot tr`,
    settings.shopengine_orders_body_row_border_width,
    (val) => {
      return `
             border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
  
             `;
    }
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__row, 
      .shopengine-account-orders .woocommerce-order-details tbody tr, 
      .shopengine-account-orders .woocommerce-order-details tfoot tr`,
    settings.shopengine_orders_body_row_border_color,
    (val) => {
      return `
             border-color : ${val};
  
             `;
    }
  );

  cssHelper.add(
    `.shopengine-account-orders table.shop_table_responsive tr:nth-child(even)`,
    settings.shopengine_responsive_striped_bg,
    (val) => {
      return `
        background: ${val};
  
       `;
    }
  );
  cssHelper.add(
    `.shopengine-account-orders table tbody tr,
      .shopengine-account-orders table tfoot tr,
      .shopengine-account-orders table thead tr`,
    settings.shopengine_orders_body_cell_padding,
    (val) =>
      `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-customer-details h2`,
    settings.shopengine_account_order_title_color,
    (val) => {
      return `
        color: ${val};
  
       `;
    }
  );

  cssHelper.add(
    `.shopengine-account-orders .woocommerce-customer-details address`,
    settings.shopengine_account_order_address_color,
    (val) => {
      return `
        color: ${val};
  
       `;
    }
  );
  cssHelper.add(
    `.shopengine-account-orders .woocommerce-customer-details,
       .shopengine-account-orders .woocommerce-customer-details address`,
    settings.shopengine_account_order_address_align,
    (val) => {
      return `
          text-align: ${val};   
          `;
    }
  );

  if (settings.shopengine_account_order_address_hide_icon.desktop == true) {
    cssHelper.add(
      `.shopengine-account-orders .woocommerce-customer-details address p::before
      .shopengine-account-orders .woocommerce-customer-details address p`,
      settings.shopengine_account_order_address_hide_icon,
      (val) => {
        return `
         display: none;
         padding-left: 0px;
         `;
      }
    );
  }
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-customer-details address`,
      settings.shopengine_account_order_address_content_padding,
      (val) =>
      `
         padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   );   
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button,
      .shopengine-account-orders .woocommerce-pagination .button,
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_action_button_padding,
      (val) =>
      `
         padding: ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
         `
   );  
   

   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button,
      .shopengine-account-orders .woocommerce-pagination .button,
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_button_border_radius,
      (val) =>
      `
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   );


      
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_font_family,
      (val) => `
         font-family: ${val.family};
   
      `
   );

   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .button, 
      .shopengine-account-orders .woocommerce-pagination .button, 
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );

   let color = shopengine_orders_button_view_box_shadow_color.desktop.rgb;
   let horizontal = shopengine_orders_button_view_box_horizontal.desktop + "px";
   let vertical = shopengine_orders_button_view_box_vertical.desktop + "px";
   let blur = shopengine_orders_button_view_box_blur.desktop + "px";
   let spread = shopengine_orders_button_view_box_spread.desktop + "px";
   let position = shopengine_orders_button_view_box_position.desktop;
 
   cssHelper.add(
     `.shopengine-account-orders .woocommerce-orders-table tbody .woocommerce-orders-table__cell .button,
     .shopengine-account-orders .woocommerce-order-details .button`,
     {},
     (val) => {
       return `
                box-shadow: ${horizontal} ${vertical} ${blur} ${spread} rgba(${color.r}, ${color.g}, ${color.b}, ${color.a}) ${position};
            `;
     }
   );

   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell .button.view,
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_color_normal,
      (val) => `
         color: ${val}!important;
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table tbody .woocommerce-orders-table__cell .button.view,
      .shopengine-account-orders .woocommerce-order-details .button`,
      settings.shopengine_orders_button_view_background_normal,
      (val) => `
         background-color: ${val}!important;
         width: initial;
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell .button.view:hover,
      .shopengine-account-orders .woocommerce-order-details .button:hover`,
      settings.shopengine_orders_button_view_color_hover,
      (val) => `
         color: ${val}!important;
          `
    );
    
    cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table tbody .woocommerce-orders-table__cell .button.view:hover,
      .shopengine-account-orders .woocommerce-order-details .button:hover`,
      settings.shopengine_orders_button_view_background_hover,
      (val) => `
         background-color: ${val}!important;
         width: initial;
          `
    );


   cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell .button.cancel`,
      settings.shopengine_orders_button_cancel_color_normal,
      (val) => `
         color: ${val};
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table tbody .woocommerce-orders-table__cell .button.cancel`,
      settings.shopengine_orders_button_cancel_background_normal,
      (val) => `
         background-color: ${val};
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell .button.cancel:hover`,
      settings.shopengine_orders_button_cancel_color_hover,
      (val) => `
         color: ${val};
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-orders-table .woocommerce-orders-table__cell .button.cancel:hover`,
      settings.shopengine_orders_button_cancel_background_hover,
      (val) => `
         background-color: ${val};
          `
    );


   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_padding,
      (val) =>
      `
         height:auto;
         padding: ${val.top} ${val.right} ${val.bottom} ${val.left}!important;
         `
   ); 
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_border_radius,
      (val) =>
      `
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
         `
   );

   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_font_family,
      (val) => `
         font-family: ${val.family};
   
      `
   );

   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_font_size,
      (val) => `
         font-size: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_font_weight,
      (val) => `
         font-weight: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_font_style,
      (val) => `
         font-style: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_text_transform,
      (val) => `
         text-transform: ${val};
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_line_height,
      (val) => `
         line-height: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_letter_spacing,
      (val) => `
         letter-spacing: ${val}px;
      `
   );
   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_pagination_wordspace,
      (val) => `
         word-spacing: ${val}px;
      `
   );



   let colors = shopengine_orders_pagination_box_shadow_color.desktop.rgb;
   let horizontals = shopengine_orders_pagination_box_shadow_horizontal.desktop + "px";
   let verticals = shopengine_orders_pagination_box_shadow_vertical.desktop + "px";
   let blurs = shopengine_orders_pagination_box_shadow_blur.desktop + "px";
   let spreads = shopengine_orders_pagination_box_shadow_spread.desktop + "px";
   let positions = shopengine_orders_pagination_box_shadow_position.desktop;
 
   cssHelper.add(
     `.shopengine-account-orders .woocommerce-pagination .button`,
     {},
     (val) => {
       return `
                box-shadow: ${horizontals} ${verticals} ${blurs} ${spreads} rgba(${colors.r}, ${colors.g}, ${colors.b}, ${colors.a}) ${positions};
            `;
     }
   );


   cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_body_pagination_tab_normal_clr,
      (val) => `
         color: ${val}!important;
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button`,
      settings.shopengine_orders_body_pagination_tab_normal_bg,
      (val) => `
         background-color: ${val}!important;
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button:hover`,
      settings.shopengine_orders_body_pagination_tab_hover_clr,
      (val) => `
         color: ${val}!important;
          `
    );

    cssHelper.add(
      `.shopengine-account-orders .woocommerce-pagination .button:hover`,
      settings.shopengine_orders_body_pagination_tab_hover_bg,
      (val) => `
         background-color: ${val}!important;
          `
    );


  return cssHelper.get();
};


export { Style };
