const Style = ({ settings, breakpoints, cssHelper }) => {
  cssHelper.add(
    `.shopengine-thankyou-address-details,
     .shopengine-thankyou-address-details address`,
    settings.shopengine_thankyou_address_details_alignment,
    (val) => `
            text-align: ${val};
        `
  );


  cssHelper.add(
    `.shopengine-thankyou-address-details h2,
    .shopengine-thankyou-address-details .woocommerce-column__title`,
    settings.shopengine_thankyou_address_details_title_color,
    (val) => `
            color: ${val};
        `
  );


  cssHelper.add(
   `.shopengine-thankyou-address-details h2,
  .shopengine-thankyou-address-details .woocommerce-column__title`,
   settings.shopengine_thankyou_address_details_title_font_size,
   (val) => `
     font-size: ${val};
  `
 );
 cssHelper.add(
   `.shopengine-thankyou-address-details h2,
  .shopengine-thankyou-address-details .woocommerce-column__title`,
   settings.shopengine_thankyou_address_details_title_font_weight,
   (val) => `
     font-weight: ${val};
  `
 );
 cssHelper.add(
   `.shopengine-thankyou-address-details h2,
  .shopengine-thankyou-address-details .woocommerce-column__title`,
   settings.shopengine_thankyou_address_details_title_font_style,
   (val) => `
     font-style: ${val};
  `
 );
 cssHelper.add(
   `.shopengine-thankyou-address-details h2,
  .shopengine-thankyou-address-details .woocommerce-column__title`,
   settings.shopengine_thankyou_address_details_title_text_transform,
   (val) => `
     text-transform: ${val};
  `
 );
 cssHelper.add(
   `.shopengine-thankyou-address-details h2,
  .shopengine-thankyou-address-details .woocommerce-column__title`,
   settings.shopengine_thankyou_address_details_title_line_height,
   (val) => `
     line-height: ${val}px;
  `
 );
 cssHelper.add(
   `.shopengine-thankyou-address-details h2,
  .shopengine-thankyou-address-details .woocommerce-column__title`,
   settings.shopengine_thankyou_address_details_title_letter_spacing,
   (val) => `
     letter-spacing: ${val}px;
  `
 );
 cssHelper.add(
   `.shopengine-thankyou-address-details h2,
  .shopengine-thankyou-address-details .woocommerce-column__title`,
   settings.shopengine_thankyou_address_details_title_wordspace,
   (val) => `
     word-spacing: ${val}px;
  `
 );


 cssHelper.add(
   `.shopengine-thankyou-address-details :not(.woocommerce-column__title)`,
   settings.shopengine_thankyou_address_details_address_color,
   (val) => `
           color: ${val};
       `
 );

 
 cssHelper.add(
   ".shopengine-thankyou-address-details :not(.woocommerce-column__title)",
   settings.shopengine_thankyou_address_details_address_font_size,
   (val) => `
     font-size: ${val};
  `
 );
 cssHelper.add(
   ".shopengine-thankyou-address-details :not(.woocommerce-column__title)",
   settings.shopengine_thankyou_address_details_address_font_weight,
   (val) => `
     font-weight: ${val};
  `
 );
 cssHelper.add(
   ".shopengine-thankyou-address-details :not(.woocommerce-column__title)",
   settings.shopengine_thankyou_address_details_address_font_style,
   (val) => `
     font-style: ${val};
  `
 );
 cssHelper.add(
   ".shopengine-thankyou-address-details :not(.woocommerce-column__title)",
   settings.shopengine_thankyou_address_details_address_text_transform,
   (val) => `
     text-transform: ${val};
  `
 );
 cssHelper.add(
   ".shopengine-thankyou-address-details :not(.woocommerce-column__title)",
   settings.shopengine_thankyou_address_details_address_line_height,
   (val) => `
     line-height: ${val}px;
  `
 );
 cssHelper.add(
   ".shopengine-thankyou-address-details :not(.woocommerce-column__title)",
   settings.shopengine_thankyou_address_details_address_letter_spacing,
   (val) => `
     letter-spacing: ${val}px;
  `
 );
 cssHelper.add(
   ".shopengine-thankyou-address-details :not(.woocommerce-column__title)",
   settings.shopengine_thankyou_address_details_address_wordspace,
   (val) => `
     word-spacing: ${val}px;
  `
 );

 cssHelper.add(
   `.shopengine-thankyou-address-details,
   .shopengine-thankyou-address-details h2, 
   .shopengine-thankyou-address-details p,
   .shopengine-thankyou-address-details .address,
   .shopengine-thankyou-address-details .woocommerce-column__title
   `,
    settings.shopengine_thankyou_address_details_font_family, (val) => (`
    font-family: ${val.family};

`))



  // cssHelper.add('.easin', 'font-size', '24px')
  // cssHelper.add('.easin', 'color', settings.simple_test.desktop)

  return cssHelper.get();
};

export { Style };
