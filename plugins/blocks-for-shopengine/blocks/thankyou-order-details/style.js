
const Style = ({settings, breakpoints, cssHelper})=>{

   cssHelper.add(
      ".shopengine-thankyou-order-details table a",
      settings.shopengine_thankyou_order_details_table_link_color,
      (val) => `
        color: ${val};
     `
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table a:hover",
      settings.shopengine_thankyou_order_details_table_link_hover_color,
      (val) => `
        background: ${val};
     `
    );

    cssHelper.add(
      ".shopengine-thankyou-order-details table tr",
      settings.shopengine_thankyou_order_details_table_row_border_type,
      (val) => {
        return `
             border-style : ${val};
  
             `;
      }
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table tr",
      settings.shopengine_thankyou_order_details_table_row_border_width,
      (val) => {
        return `
             border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
  
             `;
      }
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table tr",
      settings.shopengine_thankyou_order_details_table_row_border_color,
      (val) => {
        return `
             border-color : ${val};
  
             `;
      }
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tr th,
       .shopengine-thankyou-order-details table tr  td`,
      settings.shopengine_thankyou_order_details_table_row_padding,
      (val) =>
        `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    );

    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr th",
      settings.shopengine_thankyou_order_details_font_size,
      (val) => `
        font-size: ${val};
     `
    );
    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr th",
      settings.shopengine_thankyou_order_details_font_weight,
      (val) => `
        font-weight: ${val};
     `
    );
    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr th",
      settings.shopengine_thankyou_order_details_font_style,
      (val) => `
        font-style: ${val};
     `
    );
    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr th",
      settings.shopengine_thankyou_order_details_text_transform,
      (val) => `
        text-transform: ${val};
     `
    );
    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr th",
      settings.shopengine_thankyou_order_details_line_height,
      (val) => `
        line-height: ${val}px;
     `
    );
    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr th",
      settings.shopengine_thankyou_order_details_letter_spacing,
      (val) => `
        letter-spacing: ${val}px;
     `
    );
    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr th",
      settings.shopengine_thankyou_order_details_wordspace,
      (val) => `
        word-spacing: ${val}px;
     `
    );


    cssHelper.add(
      ".shopengine-thankyou-order-details table thead th",
      settings.shopengine_thankyou_order_details_table_header_color,
      (val) => `
        color: ${val};
     `
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table thead tr",
      settings.shopengine_thankyou_order_details_table_header_bg_color,
      (val) => `
        background: ${val};
     `
    );

    
    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr,
      .shopengine-thankyou-order-details table tbody th,
      .shopengine-thankyou-order-details table tbody td,
      .shopengine-thankyou-order-details table tbody span,
      .shopengine-thankyou-order-details table tbody .amount`,
      settings.shopengine_order_details_table_body_font_size,
      (val) => `
        font-size: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr,
      .shopengine-thankyou-order-details table tbody th,
      .shopengine-thankyou-order-details table tbody td,
      .shopengine-thankyou-order-details table tbody span,
      .shopengine-thankyou-order-details table tbody .amount`,
      settings.shopengine_order_details_table_body_font_weight,
      (val) => `
        font-weight: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr,
      .shopengine-thankyou-order-details table tbody th,
      .shopengine-thankyou-order-details table tbody td,
      .shopengine-thankyou-order-details table tbody span,
      .shopengine-thankyou-order-details table tbody .amount`,
      settings.shopengine_order_details_table_body_font_style,
      (val) => `
        font-style: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr,
      .shopengine-thankyou-order-details table tbody th,
      .shopengine-thankyou-order-details table tbody td,
      .shopengine-thankyou-order-details table tbody span,
      .shopengine-thankyou-order-details table tbody .amount`,
      settings.shopengine_order_details_table_body_text_transform,
      (val) => `
        text-transform: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr,
      .shopengine-thankyou-order-details table tbody th,
      .shopengine-thankyou-order-details table tbody td,
      .shopengine-thankyou-order-details table tbody span,
      .shopengine-thankyou-order-details table tbody .amount`,
      settings.shopengine_order_details_table_body_line_height,
      (val) => `
        line-height: ${val}px;
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr,
      .shopengine-thankyou-order-details table tbody th,
      .shopengine-thankyou-order-details table tbody td,
      .shopengine-thankyou-order-details table tbody span,
      .shopengine-thankyou-order-details table tbody .amount`,
      settings.shopengine_order_details_table_body_letter_spacing,
      (val) => `
        letter-spacing: ${val}px;
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr,
      .shopengine-thankyou-order-details table tbody th,
      .shopengine-thankyou-order-details table tbody td,
      .shopengine-thankyou-order-details table tbody span,
      .shopengine-thankyou-order-details table tbody .amount`,
      settings.shopengine_order_details_table_body_wordspace,
      (val) => `
        word-spacing: ${val}px;
     `
    );

    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr:nth-child(odd) th, 
     .shopengine-thankyou-order-details table tbody tr:nth-child(odd) td ,
     .shopengine-thankyou-order-details table tbody tr:nth-child(odd) span ,
     .shopengine-thankyou-order-details table tbody tr:nth-child(odd) .amount`,
      settings.shopengine_thankyou_order_details_table_body_color,
      (val) => `
        color: ${val};
     `
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table tbody tr:nth-child(odd)",
      settings.shopengine_thankyou_order_details_table_body_bg_color,
      (val) => `
        background: ${val};
     `
    );

    cssHelper.add(
      `.shopengine-thankyou-order-details table tbody tr:nth-child(even) th,
      .shopengine-thankyou-order-details table tbody tr:nth-child(even) td,
      .shopengine-thankyou-order-details table tbody tr:nth-child(even) span,
      .shopengine-thankyou-order-details table tbody tr:nth-child(even) .amount`,
      settings.shopengine_thankyou_order_details_table_body_striped_color,
      (val) => `
        color: ${val};
     `
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table tbody tr:nth-child(even)",
      settings.shopengine_thankyou_order_details_table_body_striped_bg_color,
      (val) => `
        background: ${val};
     `
    );

    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr,
    .shopengine-thankyou-order-details table tfoot th, 
    .shopengine-thankyou-order-details table tfoot td, 
    .shopengine-thankyou-order-details table tfoot span,
    .shopengine-thankyou-order-details table tfoot .amount`,
      settings.shopengine_order_details_table_footer_font_size,
      (val) => `
        font-size: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr,
    .shopengine-thankyou-order-details table tfoot th, 
    .shopengine-thankyou-order-details table tfoot td, 
    .shopengine-thankyou-order-details table tfoot span,
    .shopengine-thankyou-order-details table tfoot .amount`,
      settings.shopengine_order_details_table_footer_font_weight,
      (val) => `
        font-weight: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr,
    .shopengine-thankyou-order-details table tfoot th, 
    .shopengine-thankyou-order-details table tfoot td, 
    .shopengine-thankyou-order-details table tfoot span,
    .shopengine-thankyou-order-details table tfoot .amount`,
      settings.shopengine_order_details_table_footer_font_style,
      (val) => `
        font-style: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr,
    .shopengine-thankyou-order-details table tfoot th, 
    .shopengine-thankyou-order-details table tfoot td, 
    .shopengine-thankyou-order-details table tfoot span,
    .shopengine-thankyou-order-details table tfoot .amount`,
      settings.shopengine_order_details_table_footer_text_transform,
      (val) => `
        text-transform: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr,
    .shopengine-thankyou-order-details table tfoot th, 
    .shopengine-thankyou-order-details table tfoot td, 
    .shopengine-thankyou-order-details table tfoot span,
    .shopengine-thankyou-order-details table tfoot .amount`,
      settings.shopengine_order_details_table_footer_line_height,
      (val) => `
        line-height: ${val}px;
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr,
    .shopengine-thankyou-order-details table tfoot th, 
    .shopengine-thankyou-order-details table tfoot td, 
    .shopengine-thankyou-order-details table tfoot span,
    .shopengine-thankyou-order-details table tfoot .amount`,
      settings.shopengine_order_details_table_footer_letter_spacing,
      (val) => `
        letter-spacing: ${val}px;
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr,
    .shopengine-thankyou-order-details table tfoot th, 
    .shopengine-thankyou-order-details table tfoot td, 
    .shopengine-thankyou-order-details table tfoot span,
    .shopengine-thankyou-order-details table tfoot .amount`,
      settings.shopengine_order_details_table_footer_wordspace,
      (val) => `
        word-spacing: ${val}px;
     `
    );

    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr:nth-child(odd) th,
      .shopengine-thankyou-order-details table tfoot tr:nth-child(odd) td,
      .shopengine-thankyou-order-details table tfoot tr:nth-child(odd) span,
      .shopengine-thankyou-order-details table tfoot tr:nth-child(odd) .amount`,
      settings.shopengine_thankyou_order_details_table_footer_color,
      (val) => `
        color: ${val};
     `
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table tfoot tr:nth-child(odd)",
      settings.shopengine_thankyou_order_details_table_footer_bg_color,
      (val) => `
        background: ${val};
     `
    );

    cssHelper.add(
      `.shopengine-thankyou-order-details table tfoot tr:nth-child(even) th,
      .shopengine-thankyou-order-details table tfoot tr:nth-child(even) td,
      .shopengine-thankyou-order-details table tfoot tr:nth-child(even) span,
      .shopengine-thankyou-order-details table tfoot tr:nth-child(even) .amount`,
      settings.shopengine_thankyou_order_details_table_footer_striped_color,
      (val) => `
        color: ${val};
     `
    );
  
    cssHelper.add(
      ".shopengine-thankyou-order-details table tfoot tr:nth-child(even)",
      settings.shopengine_thankyou_order_details_table_footer_striped_bg_color,
      (val) => `
        background: ${val};
     `
    );
    cssHelper.add(
      `.shopengine-thankyou-order-details h2,
      .shopengine-thankyou-order-details .woocommerce-order-details__title,
      .shopengine-thankyou-order-details table table tr,
      .shopengine-thankyou-order-details table table th,
      .shopengine-thankyou-order-details table table td,
      .shopengine-thankyou-order-details table table span,
      .shopengine-thankyou-order-details table table .amount,
      .shopengine-thankyou-order-details table table a,`,
       settings.shopengine_thankyou_order_details_font_family, (val) => (`
       font-family: ${val.family};
 
   `))
    // cssHelper.add('.easin', 'font-size', '24px')
    // cssHelper.add('.easin', 'color', settings.simple_test.desktop)

    return cssHelper.get()
}

export {Style}