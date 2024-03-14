
const Style = ({ settings, breakpoints, cssHelper }) => {

   cssHelper.add('.shopengine-additional-information tr td, .shopengine-additional-information tr th', settings.shopengine_product_addi_info_table_padding, (val) => {

      return (`
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
   });


   cssHelper.add('.shopengine-additional-information tr td, .shopengine-additional-information tr th, .shopengine-additional-information tr p', settings.shopengine_product_addi_info_table_font_size, (val) => {
      return `
      font-size: ${val}px;
      `
   });
   cssHelper.add('.shopengine-additional-information tr td, .shopengine-additional-information tr th, .shopengine-additional-information tr p', settings.shopengine_product_addi_info_table_font_family, (val) => {
      return `
      font-family: ${val.family};
      `
   });
   cssHelper.add('.shopengine-additional-information tr td, .shopengine-additional-information tr th, .shopengine-additional-information tr p', settings.shopengine_product_addi_info_table_font_weight, (val) => {
      return `
      font-weight: ${val};
      `
   });
   cssHelper.add('.shopengine-additional-information tr td, .shopengine-additional-information tr th, .shopengine-additional-information tr p', settings.shopengine_product_addi_info_table_font_transform, (val) => {
      return `
      text-transform: ${val};
      `
   });
   cssHelper.add('.shopengine-additional-information tr td, .shopengine-additional-information tr th, .shopengine-additional-information tr p', settings.shopengine_product_addi_info_table_font_style, (val) => {
      return `
      font-style: ${val};
      `
   });
   cssHelper.add('.shopengine-additional-information tr td, .shopengine-additional-information tr th, .shopengine-additional-information tr p', settings.shopengine_product_addi_info_table_font_word_spacing, (val) => {
      return `
      word-spacing: ${val}px;
      `
   });

   cssHelper.add('.shopengine-additional-information .shop_attributes tr:not(:last-child), .shopengine-additional-information table.shop_attributes tr td, .shopengine-additional-information table.shop_attributes tr th', settings.shopengine_product_addi_info_separator_color, (val) => {
      return `
      border-color: ${val};
      `
   });



   cssHelper.add('.shopengine-additional-information tr th', settings.shopengine_product_addi_info_label_color, (val) => {
      return `
      color: ${val};
      `
   });
   cssHelper.add('.shopengine-additional-information tr th', settings.shopengine_product_addi_info_label_bg_color, (val) => {
      return `
      background: ${val};
      `
   });
   cssHelper.add('.shopengine-additional-information tr th', settings.shopengine_product_addi_info_label_width, (val) => {
      return `
      width: ${val};
      `
   });



   cssHelper.add('.shopengine-additional-information tr td p, .shopengine-additional-information tr td', settings.shopengine_product_addi_info_value_color, (val) => {
      return `
      color: ${val};
      `
   });
   cssHelper.add('.shopengine-additional-information tr td', settings.shopengine_product_addi_info_value_bg_color, (val) => {
      return `
      background: ${val};
      `
   });


   return cssHelper.get()
}

/*
const Style = ({settings, breakpoints})=>{
    // cssHelper.media(`(min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px)`)
    // .add('.class-a', 'color', '#000')
    // .add('.class-b', 'color', settings.autocontrol1)

    var cssOutput = '';

    var cssOutput = `
        #${settings.blockId} .class-a{
            color: #ab0;
        }
        #${settings.blockId} .class-b{
            color: ${settings.color};
        }
    `

    // we can apply conditional styles and concat them
    cssOutput += `

    @media (min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px){
        #${settings.blockId} .class-a{
            color: #000;
        }
        #${settings.blockId} .class-b{
            color: ${settings.autocontrol1};
        }
    }
    `

    return cssOutput
}
*/

export { Style }