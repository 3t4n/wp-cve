const Style = ({ settings, breakpoints, cssHelper }) => {

    cssHelper.add('.shopengine-widget .shopengine-sku', settings.shopengine_product_sku_align, (val) => (`
        text-align : ${val};
    `))

    cssHelper.add('.shopengine-sku .sku-label', settings.shopengine_product_sku_label_color, (val) => (`
        color : ${val};
    `))

    cssHelper.add('.shopengine-sku .sku-label', settings.shopengine_product_cats_label_text_decoration, (val) => (`
        text-decoration: ${val} !important; 
        text-underline-offset: 3px;
    `))
    cssHelper.add('.shopengine-sku .sku-value', settings.shopengine_product_sku_value_color, (val) => (`
        color : ${val};
    `))
    
    cssHelper.add('.shopengine-sku p', settings.shopengine_product_sku_value_color, (val) => (`
        display : none;
    `))

    cssHelper.add('.shopengine-sku .sku-label,.shopengine-sku .sku-value', settings.shopengine_font_family, (val) => (`
        font-family : ${val.family};
    `))
    
    cssHelper.add('.shopengine-sku .sku-label,.shopengine-sku .sku-value', settings.shopengine_font_size, (val) => (`
        font-size : ${val}px;
    `))

    cssHelper.add('.shopengine-sku .sku-label,.shopengine-sku .sku-value', settings.shopengine_font_weight, (val) => (`
        font-weight : ${val};
    `))

    cssHelper.add('.shopengine-sku .sku-label,.shopengine-sku .sku-value', settings.shopengine_text_transform, (val) => (`
        text-transform : ${val};
    `))

    cssHelper.add('.shopengine-sku .sku-label,.shopengine-sku .sku-value', settings.shopengine_line_height, (val) => (`
        line-height : ${val}px;
    `))

    cssHelper.add('.shopengine-sku .sku-label,.shopengine-sku .sku-value', settings.shopengine_word_spacing, (val) => (`
        word-spacing : ${val}px;
    `))

    return cssHelper.get()
}

export { Style }