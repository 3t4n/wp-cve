
const Style = ({settings, breakpoints, cssHelper})=>{
    const getObjectValues = (obj) => {
        return  [...Object.values(obj)].toString();
    }
    cssHelper.add('.shopengine-product-meta .sku_wrapper', settings.shopengine_sku_display, (val) => (`
            display: ${!val && "none"} !important;
        `))
        .add('.shopengine-layout-inline .shopengine-product-meta .sku_wrapper', settings.shopengine_layout_control, (val) => (`
            display: ${!val && "none"};
        `))
        .add('.shopengine-product-meta .posted_in', settings.shopengine_category_display, (val) => (`
            display: ${!val && "none"} !important;
        `))
        .add('.shopengine-layout-inline .shopengine-product-meta .posted_in', settings.shopengine_category_display, (val) => (`
            display: ${!val && "none"} !important;
        `))
        .add('.shopengine-product-meta .products-page-cats', settings.shopengine_category_display, (val) => (`
            display: ${!val && "none"} !important;
        `))
        .add('.shopengine-layout-inline .shopengine-product-meta  .products-page-cat', settings.shopengine_category_display, (val) => (`
            display: ${!val && "none"} !important;
        `))
        .add('.shopengine-product-meta .tagged_as', settings.shopengine_tag_display, (val) => (`
            display: ${!val && "none"} !important;
        `))
        .add('.shopengine-layout-inline .shopengine-product-meta .tagged_as', settings.shopengine_tag_display, (val) => (`
            display: ${!val && "none"} !important;
        `))
        .add('.shopengine-product-meta .sku_wrapper,.shopengine-product-meta .posted_in, .shopengine-layout-inline .shopengine-product-meta .sku_wrapper,.shopengine-layout-inline .shopengine-product-meta .posted_in ', settings.shopengine_layout_control, (val) => (`
            display: ${val};
        `))
        .add('.shopengine-product-meta .product_meta', settings.shopengine_align_control, (val) => (`
            text-align: ${val};
        `))
        .add('.shopengine-product-meta .product_meta .sku_wrapper,.shopengine-product-meta .product_meta .posted_in,.shopengine-product-meta .product_meta .tagged_as', settings.shopengine_padding_control, (val) => (`
            padding: ${getObjectValues(val).split(',').join(' ')};
        `))
        .add('.shopengine-product-meta .product_meta .sku_wrapper, .shopengine-product-meta .product_meta .posted_in, .shopengine-product-meta .product_meta .tagged_as', settings.shopengine_label_color, (val) => (`
            color: ${val.hex};
        `))
        .add('.shopengine-product-meta .product_meta .sku, .shopengine-product-meta .product_meta .posted_in a, .shopengine-product-meta .product_meta .tagged_as a', settings.shopengine_meta_value_color, (val) => (`
            color: ${val.hex};
        `))
        .add('.shopengine-product-meta .product_meta .posted_in a:hover, .shopengine-product-meta .product_meta .tagged_as a:hover', settings.shopengine_link_hover_color, (val) => (`
            color: ${val.hex};
        `))

        .add('.shopengine-product-meta .product_meta a,.shopengine-product-meta .product_meta span,.shopengine-product-meta .product_meta .sku_wrapper,.shopengine-product-meta .product_meta .posted_in,.shopengine-product-meta .product_meta .tagged_as', settings.shopengine_font_family, (val) => (`
            font-family: ${val.family};
        `))
        .add('.shopengine-product-meta .product_meta a,.shopengine-product-meta .product_meta span,.shopengine-product-meta .product_meta .sku_wrapper,.shopengine-product-meta .product_meta .posted_in,.shopengine-product-meta .product_meta .tagged_as', settings.shopengine_font_size, (val) => (`
            font-size: ${val}px;
        `))
        .add('.shopengine-product-meta .product_meta a,.shopengine-product-meta .product_meta span,.shopengine-product-meta .product_meta .sku_wrapper,.shopengine-product-meta .product_meta .posted_in,.shopengine-product-meta .product_meta .tagged_as', settings.shopengine_font_weight, (val) => (`
            font-weight: ${val};
        `))
        .add('.shopengine-product-meta .product_meta a,.shopengine-product-meta .product_meta span,.shopengine-product-meta .product_meta .sku_wrapper,.shopengine-product-meta .product_meta .posted_in,.shopengine-product-meta .product_meta .tagged_as', settings.shopengine_line_height, (val) => (`
            line-height: ${val}px;
        `))
        .add('.shopengine-product-meta .product_meta a,.shopengine-product-meta .product_meta span,.shopengine-product-meta .product_meta .sku_wrapper,.shopengine-product-meta .product_meta .posted_in,.shopengine-product-meta .product_meta .tagged_as', settings.shopengine_transform, (val) => (`
            text-transform: ${val};
        `))
        .add('.shopengine-product-meta .product_meta a,.shopengine-product-meta .product_meta span,.shopengine-product-meta .product_meta .sku_wrapper,.shopengine-product-meta .product_meta .posted_in,.shopengine-product-meta .product_meta .tagged_as', settings.shopengine_word_spacing, (val) => (`
            word-spacing: ${val}px;
        `))


    return cssHelper.get()
}
export {Style}