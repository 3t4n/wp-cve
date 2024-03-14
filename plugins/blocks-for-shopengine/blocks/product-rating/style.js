const Style = ({ settings, cssHelper }) => {

    cssHelper.add('.shopengine-product-rating .star-rating', settings.shopengine_star_size, (val) => (`
    font-size: ${val}px;
   `));

    cssHelper.add('.shopengine-product-rating .star-rating::before', settings.shopengine_empty_star_color, (val) => (`
    color: ${val};
   `));

    cssHelper.add('.shopengine-product-rating .star-rating span::before', settings.shopengine_star_color, (val) => (`
    color: ${val};
   `));

    cssHelper.add('.shopengine-product-rating', settings.shopengine_alignment, (val) => (`
    text-align: ${val};
   `));

    cssHelper.add('.shopengine-product-rating a', settings.shopengine_link_color, (val) => (`
    color: ${val};
    `))
    .add('.shopengine-product-rating .star-rating', settings.shopengine_rating_space_between, (val) => (`
    margin-right: ${val}px;
    
    `)).add('.shopengine-product-rating a', settings.shopengine_font_family, (val) => (`
    font-family: ${val.family};
    `))
    .add('.shopengine-product-rating a', settings.shopengine_font_size, (val) => (`
    font-size: ${val}px;
    `))
    .add('.shopengine-product-rating a', settings.shopengine_font_weight, (val) => (`
    font-weight: ${val};
    `))
    .add('.shopengine-product-rating a', settings.shopengine_transform, (val) => (`
    text-transform: ${val};
    `))
    .add('.shopengine-product-rating a', settings.shopengine_line_height, (val) => (`
    line-height: ${val}px;
    `))
    .add('.shopengine-product-rating a', settings.shopengine_word_spacing, (val) => (`
    word-spacing: ${val}px;
    `));

    cssHelper.add('.shopengine-product-rating a:hover', settings.shopengine_link_hover_color, (val) => (`
        color: ${val};
   `));

    return cssHelper.get()
}


export { Style }