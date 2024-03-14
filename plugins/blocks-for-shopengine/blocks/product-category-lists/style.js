const Style = ({ settings, cssHelper }) => {

    //this will return object values as a string separated by comma
    const getObjectValues = (obj) => {
        return [...Object.values(obj)].toString();
    }
    //this will return complete box shadow values as string
    const getShadowValues = (obj) => {
        let pos = obj.position === 'inset' ? obj.position : '';
        let propSet = getObjectValues(Object.fromEntries(Object.entries(obj).slice(1, Object.keys(obj).length - 1)));
        return `${pos} ${propSet.split(',').join('px ') + 'px'} rgba(${getObjectValues(obj.color.rgb).split(' ').join(',')})`;
    }


    const shopengine_list_normal_shadow = {
        position: settings.shopengine_list_normal_shadow_position.desktop,
        horizontal: settings.shopengine_list_normal_shadow_horizontal.desktop,
        vertical: settings.shopengine_list_normal_shadow_vertical.desktop,
        blur: settings.shopengine_list_normal_shadow_blur.desktop,
        spread: settings.shopengine_list_normal_shadow_spread.desktop,
        color: settings.shopengine_list_normal_shadow_color.desktop
    }
    const shopengine_list_hover_shadow = {
        position: settings.shopengine_list_hover_shadow_position.desktop,
        horizontal: settings.shopengine_list_hover_shadow_horizontal.desktop,
        vertical: settings.shopengine_list_hover_shadow_vertical.desktop,
        blur: settings.shopengine_list_hover_shadow_blur.desktop,
        spread: settings.shopengine_list_hover_shadow_spread.desktop,
        color: settings.shopengine_list_hover_shadow_color.desktop
    }

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-lists-grid', settings.shopengine_grid_columns, (val) => (`
        grid-template-columns: repeat(${val || 3}, minmax(0px, 1fr));
    `));

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-lists-grid', settings.shopengine_column_gap, (val) => (`
        grid-column-gap: ${val}px;
    `));

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-lists-grid', settings.shopengine_row_gap, (val) => (`
        grid-row-gap: ${val}px;
    `));

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-lists-grid .shopengine-category-items', settings.shopengine_product_cat_lists_item_content_gap, (val) => (`
        gap: ${val}px;
    `));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item , .shopengine-product-category-lists .shopengine-category-items', settings.shopengine_list_normal_background_color, (val) => (`
        background: ${val};
    `));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item , .shopengine-product-category-lists .shopengine-category-items', { desktop: shopengine_list_normal_shadow }, (val) => (
        `box-shadow: ${getShadowValues(val)};`
    ))

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-items', settings.shopengine_product_cat_lists_item_border_type, (val) => (
        `border-style: ${val};`
    ));

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-items', settings.shopengine_product_cat_lists_item_border_width, (val) => (
        `border-width: ${getObjectValues(val).split(',').join(' ')};`
    ));

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-items', settings.shopengine_product_cat_lists_item_border_color, (val) => (
        `border-color: ${val};`
    ));


    cssHelper.add('.shopengine-widget .shopengine-product-category-lists .shopengine-category-lists-grid .shopengine-category-items:hover', settings.shopengine_product_cat_lists_item_hover_border_type, (val) => (
        `border-style: ${val};`
    ));

    cssHelper.add('.shopengine-widget .shopengine-product-category-lists .shopengine-category-lists-grid .shopengine-category-items:hover', settings.shopengine_product_cat_lists_item_hover_border_width, (val) => (
        `border-width: ${getObjectValues(val).split(',').join(' ')};`
    ));

    cssHelper.add('.shopengine-widget .shopengine-product-category-lists .shopengine-category-lists-grid .shopengine-category-items:hover', settings.shopengine_product_cat_lists_item_hover_border_color, (val) => (
        `border-color: ${val};`
    ));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item , .shopengine-product-category-lists .shopengine-category-items', settings.shopengine_list_padding, (val) => (
        `padding: ${getObjectValues(val).split(',').join(' ')};`
    ));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item::before', settings.shopengine_list_normal_overlay_background, (val) => (`
        background: ${val.hex};
   `));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item::before', settings.shopengine_list_normal_overlay_background_opacity, (val) => (`
        opacity: ${val / 100};
   `));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item:hover', settings.shopengine_list_hover_background_color, (val) => (`
        background: ${val};
   `))

   cssHelper.add('.shopengine-product-category-lists .single-cat-list-item:hover', { desktop: shopengine_list_hover_shadow }, (val) => (
        `box-shadow: ${getShadowValues(val)};`
    ));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item:hover::before', settings.shopengine_list_hover_overlay_background, (val) => (`
        background: ${val};
   `));

    cssHelper.add('.shopengine-product-category-lists .single-product-category', settings.shopengine_content_alignment, (val) => (`
        text-align: ${val};
   `));

    cssHelper.add('.shopengine-product-category-lists .product-category-title,.shopengine-product-category-lists .shopengine-category-items .product-category-list-title', settings.shopengine_title_font_family, (val) => (`
        font-family: ${val.family};
   `))

   cssHelper.add('.shopengine-product-category-lists .product-category-title , .shopengine-product-category-lists .shopengine-category-items .product-category-list-title', settings.shopengine_title_color, (val) => (`
        color: ${val};
    `))

    cssHelper.add('.shopengine-product-category-lists .product-category-title , .shopengine-product-category-lists .shopengine-category-items .product-category-list-title', settings.shopengine_title_font_size, (val) => (`
        font-size: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .product-category-title , .shopengine-product-category-lists .shopengine-category-items .product-category-list-title', settings.shopengine_title_font_weight, (val) => (`
        font-weight: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .product-category-title , .shopengine-product-category-lists .shopengine-category-items .product-category-list-title', settings.shopengine_title_transform, (val) => (`
        text-transform: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .product-category-title , .shopengine-product-category-lists .shopengine-category-items .product-category-list-title', settings.shopengine_title_line_height, (val) => (`
        line-height: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .product-category-title , .shopengine-product-category-lists .shopengine-category-items .product-category-list-title', settings.shopengine_title_word_spacing, (val) => (`
        letter-spacing: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .product-category-title', settings.shopengine_title_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `));

    cssHelper.add('.shopengine-product-category-lists .product-category-title:hover , .shopengine-product-category-lists .shopengine-category-items:hover .product-category-list-title', settings.shopengine_title_hover_color, (val) => (`
        color: ${val};
   `));

    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_font_family, (val) => (`
        font-family: ${val.family};
    `))

    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_color, (val) => (`
        color: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_font_size, (val) => (`
        font-size: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_font_weight, (val) => (`
        font-weight: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_transform, (val) => (`
        text-transform: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_line_height, (val) => (`
        line-height: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_word_spacing, (val) => (`
        letter-spacing: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-count', settings.shopengine_category_title_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `));

    cssHelper.add('.shopengine-product-category-lists .cat-count:hover', settings.shopengine_category_title_hover_color, (val) => (`
        color: ${val};
    `));

    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item .cat-icon', settings.shopengine_button_size, (val) => (`
        width: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item .cat-icon', settings.shopengine_button_size, (val) => (`
        height: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .single-cat-list-item .cat-icon', settings.shopengine_button_size, (val) => (`
        line-height: ${val}px;
    `));

    cssHelper.add('.shopengine-product-category-lists .cat-icon', settings.shopengine_button_font_size, (val) => (`
        font-size: ${val}px;
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-icon', settings.shopengine_button_padding, (val) => (`
        padding: ${getObjectValues(val).split(',').join(' ')};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-icon', settings.shopengine_normal_button_color, (val) => (`
        color: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-icon', settings.shopengine_normal_button_background, (val) => (`
        background-color: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-icon', settings.shopengine_button_border_radius, (val) => (`
        border-radius: ${getObjectValues(val).split(',').join(' ')};
    `));

    cssHelper.add('.shopengine-product-category-lists .cat-icon:hover', settings.shopengine_hover_button_color, (val) => (`
        color: ${val};
    `))
    
    cssHelper.add('.shopengine-product-category-lists .cat-icon:hover', settings.shopengine_hover_button_background, (val) => (`
        background-color: ${val};
    `))

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-items .shopengine-category-icon', settings.shopengine_product_cat_lists_icon_color, (val) => (`
        color: ${val};
    `))

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-items:hover .shopengine-category-icon', settings.shopengine_product_cat_lists_icon_color_hover, (val) => (`
        color: ${val};
        transition: all 0.3s ease-in-out;
    `))

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-items .shopengine-category-icon i', settings.shopengine_product_cat_lists_icon_size, (val) => (`
        font-size: ${val};
    `))

    cssHelper.add('.shopengine-product-category-lists .shopengine-category-items .shopengine-category-icon i', settings.shopengine_product_cat_lists_icon_position, (val) => (`
        vertical-align: ${val};
    `))

    return cssHelper.get()
}


export { Style }