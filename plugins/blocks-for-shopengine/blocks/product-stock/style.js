const Style = ({settings, breakpoints, cssHelper})=>{
  
  
   cssHelper.add('.shopengine-product-stock', settings.shopengine_pstock_stock_type_align, (val) => {
      return (`
         text-align: ${val};
      `)
   } );
   
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_family, (val) => {
      return (`
         font-family: ${val.family};
      `)
   } );
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_size, (val) => {
      return (`
         font-size: ${val}px;
      `)
   } );
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_weight, (val) => {
      return (`
         font-weight: ${val};
      `)
   } );
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_transform, (val) => {
      return (`
         text-transform: ${val};
      `)
   } );
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_style, (val) => {
      return (`
         font-style: ${val};
      `)
   } );
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_Line_height, (val) => {
      return (`
         line-height: ${val}px;
      `)
   } );
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_letter_spacing, (val) => {
      return (`
         letter-spacing: ${val}px;
      `)
   } );
   cssHelper.add('.shopengine-product-stock p', settings.shopengine_pstock_stock_type_font_word_spacing, (val) => {
      return (`
         word-spacing: ${val}px;
      `)
   } );
   
   cssHelper.add('.shopengine-product-stock .in-stock', settings.shopengine_pstock_in_stock_icon_color, (val) => {
      return (`
         color: ${val};
      `)
   } );
   
   cssHelper.add('.shopengine-product-stock .out-of-stock', settings.shopengine_pstock_out_of_stock_color, (val) => {
      return (`
         color: ${val};
      `)
   } );
   
   cssHelper.add('.shopengine-product-stock .available-on-backorder', settings.shopengine_pstock_available_on_backorder_color, (val) => {
      return (`
         color: ${val};
      `)
   } );


    return cssHelper.get()
}


export { Style };
