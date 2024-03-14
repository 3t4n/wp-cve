
const Style = ({settings, breakpoints, cssHelper})=>{
   
   cssHelper.add('.shopengine-product-price .price', settings.shopengine_product_price_text_align, (val) => (`
        display: flex; 
        align-items: center; 
        justify-content: ${val};
    `))


   cssHelper.add('.shopengine-product-price .price del,.shopengine-product-price .price ins', settings.shopengine_product_price_text_align, (val) => (`
        background: none;
    `))

   cssHelper.add('.shopengine-product-price .price, .shopengine-product-price .price .amount, .shopengine-product-price .price ins', settings.shopengine_product_price_typography_line_height, (val) => (`
         line-height: ${val}px;
    `))

    cssHelper.add('.shopengine-product-price .price, .shopengine-product-price .price .amount, .shopengine-product-price .price ins', settings.shopengine_product_price_typography_font_family, (val) => {
      return `
       font-family: ${val.family};
      `
     } );

   cssHelper.add('.shopengine-product-price .price, .shopengine-product-price .price .amount, .shopengine-product-price .price ins', settings.shopengine_product_price_typography_font_size, (val) => (`
         font-size: ${val}px;
    `))

   cssHelper.add('.shopengine-product-price .price, .shopengine-product-price .price .amount, .shopengine-product-price .price ins', settings.shopengine_product_price_typography_font_weight, (val) => (`
         font-weight: ${val};
    `))

   cssHelper.add('.shopengine-product-price .price, .shopengine-product-price .price .amount, .shopengine-product-price .price ins', settings.shopengine_product_price_typography_word_spacing, (val) => (`
   word-spacing: ${val}px;
    `))

   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_typography_word_spacing, (val) => (`
         word-spacing: ${val}px;
    `))



   cssHelper.add('.shopengine-product-price .price,.shopengine-product-price .price del, .shopengine-product-price .price del .amount,.shopengine-product-price .price ins', settings.shopengine_product_price_price_color, (val) => (`
        color: ${val}; 
        opacity: 1; 
        vertical-align: middle;
    `))

   cssHelper.add('.shopengine-product-price .price ins', settings.shopengine_product_price_space_between, (val) => (`
   text-decoration : none;
    `))
   cssHelper.add('.shopengine-product-price .price del', settings.shopengine_product_price_space_between, (val) => (`
        margin-right: ${val}px;
    `))

   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_space_between, (val) => (`
        margin-left: ${val}px;
    `))

   cssHelper.add('.shopengine-product-price .price ins .amount', settings.shopengine_product_price_sale_price_color, (val) => (`
        background: transparent; 
        color: ${val};
    `))


    cssHelper.add('.shopengine-product-price .price ins .amount', settings.shopengine_product_price_sale_price_typography_font_family, (val) => {
      return `
       font-family: ${val.family};
      `
     } );
   cssHelper.add('.shopengine-product-price .price ins .amount', settings.shopengine_product_price_sale_price_typography_font_size, (val) => (`
        font-size: ${val}px;
    `))

   cssHelper.add('.shopengine-product-price .price ins .amount', settings.shopengine_product_price_sale_price_typography_font_weight, (val) => (`
        font-weight: ${val};
    `))



    cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_typography_font_family, (val) => {
      return `
       font-family: ${val.family};
      `
     } );
     
   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_typography_font_size, (val) => (`
        font-size: ${val}px;
    `))

   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_typography_font_weight, (val) => (`
        font-weight: ${val};
    `))

   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_typography_text_transform, (val) => (`
        text-transform: ${val};
    `))

   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_typography_line_height, (val) => (`
        line-height: ${val}px;
    `))



   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_color, (val) => (`
        color: ${val};
    `))

   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_bg_color, (val) => (`
        background: ${val};
    `))

   cssHelper.add('.shopengine-product-price .price .shopengine-discount-badge', settings.shopengine_product_price_discount_badge_padding, (val) => (`
   padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    return cssHelper.get()
}


export { Style }

