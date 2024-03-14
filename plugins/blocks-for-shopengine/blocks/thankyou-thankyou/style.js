const Style = ({ settings, breakpoints, cssHelper }) => {
  cssHelper.add(
    ".shopengine-thankyou-thankyou",
    settings.shopengine_thankyou_align,
    (val) => `
	   text-align: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thankyou_title_color,
    (val) => `
      color: ${val};
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thank_you_font_size,
    (val) => `
      font-size: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thank_you_font_weight,
    (val) => `
      font-weight: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thank_you_font_style,
    (val) => `
      font-style: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thank_you_text_transform,
    (val) => `
      text-transform: ${val};
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thank_you_line_height,
    (val) => `
      line-height: ${val}px;
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thank_you_letter_spacing,
    (val) => `
      letter-spacing: ${val}px;
   `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thank_you_wordspace,
    (val) => `
      word-spacing: ${val}px;
   `
  );

  cssHelper.add(
    ".shopengine-thankyou-thankyou h3",
    settings.shopengine_order_thankyou_title_margin,
    (val) =>
      `
      margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `
  );

  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_color,
    (val) =>
      `
      color: ${val};
      `
  );

  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_description_font_size,
    (val) => `
   font-size: ${val};
 `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_description_font_weight,
    (val) => `
    font-weight: ${val};
 `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_description_font_style,
    (val) => `
    font-style: ${val};
 `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_description_text_transform,
    (val) => `
    text-transform: ${val};
 `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_description_line_height,
    (val) => `
    line-height: ${val}px;
 `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_description_letter_spacing,
    (val) => `
    letter-spacing: ${val}px;
 `
  );
  cssHelper.add(
    ".shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_description_wordspace,
    (val) => `
    word-spacing: ${val}px;
 `
  );

  cssHelper.add(
    ".shopengine-thankyou-thankyou h2, .shopengine-thankyou-thankyou h3, .shopengine-thankyou-thankyou p",
    settings.shopengine_order_thankyou_font_family,
    (val) => `
      font-family: ${val.family};

   `
  );

  // cssHelper.add('.easin', 'font-size', '24px')
  // cssHelper.add('.easin', 'color', settings.simple_test.desktop)

  return cssHelper.get();
};

export { Style };
