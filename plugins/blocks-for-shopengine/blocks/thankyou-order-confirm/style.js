const Style = ({ settings, breakpoints, cssHelper }) => {
  cssHelper.add(
    ".shopengine-thankyou-order-confirm table:not(thead), tr th, .shopengine-thankyou-order-confirm table:not(thead), tr td, .shopengine-thankyou-order-confirm table:not(thead), tr span, .shopengine-thankyou-order-confirm table:not(thead), tr a",
    settings.shopengine_thankyou_order_confirm_font_size,
    (val) => `
      font-size: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-order-confirm table:not(thead), tr th, .shopengine-thankyou-order-confirm table:not(thead), tr td, .shopengine-thankyou-order-confirm table:not(thead), tr span, .shopengine-thankyou-order-confirm table:not(thead), tr a",
    settings.shopengine_thankyou_order_confirm_font_weight,
    (val) => `
      font-weight: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-order-confirm table:not(thead), tr th, .shopengine-thankyou-order-confirm table:not(thead), tr td, .shopengine-thankyou-order-confirm table:not(thead), tr span, .shopengine-thankyou-order-confirm table:not(thead), tr a",
    settings.shopengine_thankyou_order_confirm_font_style,
    (val) => `
      font-style: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-order-confirm table:not(thead), tr th, .shopengine-thankyou-order-confirm table:not(thead), tr td, .shopengine-thankyou-order-confirm table:not(thead), tr span, .shopengine-thankyou-order-confirm table:not(thead), tr a",
    settings.shopengine_thankyou_order_confirm_text_transform,
    (val) => `
      text-transform: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-order-confirm table:not(thead), tr th, .shopengine-thankyou-order-confirm table:not(thead), tr td, .shopengine-thankyou-order-confirm table:not(thead), tr span, .shopengine-thankyou-order-confirm table:not(thead), tr a",
    settings.shopengine_thankyou_order_confirm_line_height,
    (val) => `
      line-height: ${val}px;
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-order-confirm table:not(thead), tr th, .shopengine-thankyou-order-confirm table:not(thead), tr td, .shopengine-thankyou-order-confirm table:not(thead), tr span, .shopengine-thankyou-order-confirm table:not(thead), tr a",
    settings.shopengine_thankyou_order_confirm_letter_spacing,
    (val) => `
      letter-spacing: ${val}px;
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-order-confirm table:not(thead), tr th, .shopengine-thankyou-order-confirm table:not(thead), tr td, .shopengine-thankyou-order-confirm table:not(thead), tr span, .shopengine-thankyou-order-confirm table:not(thead), tr a",
    settings.shopengine_thankyou_order_confirm_wordspace,
    (val) => `
      word-spacing: ${val}px;
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(even) th, .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(even) td, .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(even) span, .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(even) .amount",
    settings.shopengine_thankyou_order_confirm_table_body_color,
    (val) => `
      color: ${val};
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(even)",
    settings.shopengine_thankyou_order_confirm_table_body_bg_color,
    (val) => `
      background: ${val};
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(odd) th, .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(odd) td, .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(odd) span, .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(odd) .amount",
    settings.shopengine_thankyou_order_confirm_table_body_striped_color,
    (val) => `
      color: ${val};
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(odd)",
    settings.shopengine_thankyou_order_confirm_table_body_striped_bg_color,
    (val) => `
      background: ${val};
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr a",
    settings.shopengine_thankyou_order_confirm_table_body_link_color,
    (val) => `
      color: ${val};
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr a:hover",
    settings.shopengine_thankyou_order_confirm_table_body_link_hover_color,
    (val) => `
      color: ${val};
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr",
    settings.shopengine_thankyou_order_confirm_table_body_border_type,
    (val) => {
      return `
           border-style : ${val};

           `;
    }
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr",
    settings.shopengine_thankyou_order_confirm_table_body_border_width,
    (val) => {
      return `
           border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

           `;
    }
  );

  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr",
    settings.shopengine_thankyou_order_confirm_table_body_border_color,
    (val) => {
      return `
           border-color : ${val};

           `;
    }
  );


  cssHelper.add(
    ".shopengine-thankyou-order-confirm table :not(thead) tr td",
    settings.shopengine_thankyou_order_confirm_table_body_padding,
    (val) =>
      `
      padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
  );
  
  cssHelper.add(
     `.shopengine-thankyou-order-confirm table tr,
      .shopengine-thankyou-order-confirm table th,
      .shopengine-thankyou-order-confirm table td,
      .shopengine-thankyou-order-confirm table span,
      .shopengine-thankyou-order-confirm table .amount,
      .shopengine-thankyou-order-confirm table a,`,
      settings.shopengine_thankyou_order_confirm_font_family, (val) => (`
      font-family: ${val.family};

  `))


  // cssHelper.add('.easin', 'font-size', '24px')
  // cssHelper.add('.easin', 'color', settings.simple_test.desktop)
  return cssHelper.get();
};

export { Style };
