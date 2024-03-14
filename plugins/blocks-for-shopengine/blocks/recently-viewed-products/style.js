
const Style = ({settings, breakpoints, cssHelper})=>{
    const getObjectValues = (obj) => {
        return [...Object.values(obj)].toString();
    }



    cssHelper.add('.shopengine-recently-viewed-products .recent-viewed-product-list',settings.column,(val)=>(`
    grid-template-columns: repeat(${val}, 1fr);
    `))
    cssHelper.add('.shopengine-single-product-item .badge.sale',settings.show_sale,(val)=>(`
    display:${val ? "inline-block" : "none"};
    `))
    cssHelper.add('.shopengine-single-product-item .badge.tag',settings.show_tag,(val)=>(`
    display:${val ? "inline-block" : "none"};
    `))
    
    cssHelper.add('.shopengine-recently-viewed-products .shopengine-single-product-item .product-thumb',settings.product_wrap_padding,(val)=>(`
    padding: ${getObjectValues(val).split(',').join(' ')} ;
    `))
    cssHelper.add('.shopengine-recently-viewed-products .shopengine-single-product-item .product-thumb',settings.product_image_bg,(val)=>(`
    background: ${val};
    `))
    
    cssHelper.add('.shopengine-recently-viewed-products .shopengine-single-product-item:not(:last-child)',settings.shopengine_product_wrap_hide_right_border,(val)=>(`
        border-right:${val && "none"};
    `))
    
    if(settings.product_image_use_equal_height.desktop == 1){
        cssHelper.add('.shopengine-single-product-item .product-thumb img',settings.product_image_fit,(val)=>(`object-fit: ${val};`))
        cssHelper.add('.shopengine-single-product-item .product-thumb img',settings.product_image_height,(val)=>(`height: ${val}px !important;`))
    }
    if(settings.badge_position.desktop === 'top-left'){
        cssHelper.add(`.product-tag-sale-badge`, settings.badge_position_x_axis, val=>(`
        top: 10px;
        left: 10px;
        `))
    }
    else if(settings.badge_position.desktop === 'top-right'){
        cssHelper.add(`.product-tag-sale-badge`, settings.badge_position_x_axis, val=>(`
            top: 10px;
            right: 10px;
        `))
    }else{
        cssHelper.add(`.product-tag-sale-badge`, settings.badge_position_x_axis, val=>(`
            left: ${val};
        `))
        cssHelper.add(`.product-tag-sale-badge`, settings.badge_position_y_axis, val=>(`
            top: ${val};
        `))
    }
    cssHelper.add('.product-tag-sale-badge .off',settings.product_percentage_badge_bg,val=> (`background: ${val}`))
    
    cssHelper.add('.product-tag-sale-badge ul li:not(:last-child)',settings.product_badgey_item_space_between,val=>(`margin: 0 ${val}px 0 0`))
    cssHelper.add('.product-tag-sale-badge.align-vertical ul li:not(:last-child)',settings.product_badgey_item_space_between,val=>(`margin: margin: 0 0 ${val}px 0`))
            
            .add(`.shopengine-recently-viewed-products .recent-viewed-product-list`, settings.product_item_column_gap, val=>(`
                grid-column-gap: ${val}px;
            `))
            .add(`.shopengine-recently-viewed-products .recent-viewed-product-list`, settings.product_item_row_gap, val=>(`
                grid-row-gap: ${val}px;
            `))
            .add(`.shopengine-recently-viewed-products .shopengine-single-product-item`, settings.product_wrap_border_type, val=>(`
                border-style: ${val};
            `))
            .add(`.shopengine-recently-viewed-products .shopengine-single-product-item`, settings.product_wrap_border_width, val=>(`
                border-width: ${getObjectValues(val).split(',').join(' ')};
            `))
            .add(`.shopengine-recently-viewed-products .shopengine-single-product-item`, settings.product_wrap_border_color, val=>(`
                border-color: ${val};
            `))
            
            

            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_typography_font_family, val=>(`
                font-family: ${val.family};
            `))
            
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_typography_font_size, val=>(`
                font-size: ${val}px;
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_typography_font_weight, val=>(`
                font-weight: ${val};
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_typographytext_transform, val=>(`
                text-transform: ${val};
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_typography_line_height, val=>(`
                line-height: ${val}px;
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_typography_wordspace, val=>(`
                letter-spacing : ${val}px;
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_color, val=>(`
                color: ${val};
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_bg, val=>(`
                background: ${val};
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_padding, val=>(`
                padding: ${getObjectValues(val).split(',').join(' ')};
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.product_badge_margin, val=>(`
                margin: ${getObjectValues(val).split(',').join(' ')};
            `))
            .add(`.product-tag-sale-badge .tag a, .product-tag-sale-badge, .no-link`, settings.badge_border_radius, val=>(`
                border-radius: ${getObjectValues(val).split(',').join(' ')};
            `))
            .add(`.product-tag-sale-badge  li,
            .product-tag-sale-badge .no-link, 
            .product-tag-sale-badge a, 
            .product-tag-sale-badge .tag
            `, settings.badge_border_type, val=>(`
                border-style: ${val};
            `))
            .add(`.product-tag-sale-badge  li,
            .product-tag-sale-badge .no-link, 
            .product-tag-sale-badge a, 
            .product-tag-sale-badge .tag
            `, settings.badge_border_width, val=>(`
                border-width: ${getObjectValues(val).split(',').join(' ')};
            `))
            .add(`.product-tag-sale-badge  li,
            .product-tag-sale-badge .no-link, 
            .product-tag-sale-badge a, 
            .product-tag-sale-badge .tag
            `, settings.badge_border_color, val=>(`
                border-color: ${val};
            `))
            .add(`.shopengine-recently-viewed-products .shopengine-single-product-item .product-title
            `, settings.shopengine_show_title, val=>(`
                display: ${val ? "block" : "none"};
            `))
            .add(`.shopengine-recently-viewed-products .shopengine-single-product-item .product-price
            `, settings.shopengine_show_price, val=>(`
                display: ${val ? "block" : "none"};
            `))
            if(settings.shopengine_group_btns.desktop){
                cssHelper.add(`.shopengine-single-product-item .add-to-cart-bt .button`,
                settings.shopengine_cart_button,
                val=> (`
                display: ${val ? "inline-block" : "none" }
                `))
            }
    cssHelper.add(`
    .shopengine-recently-viewed-products .recent-viewed-product-list .shopengine-single-product-item,
    .shopengine-recently-viewed-products .recent-viewed-product-list .price
    `,
        settings.shopengine_recent_product_text_align,
        val => {
           return val === "right" ? "text-align: right; justify-content: flex-end;" : val === "center" ? "text-align: center; justify-content: center;" : "text-align: left; justify-content: flex-start;"
        }
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-title a
    `,
        settings.shopengine_product_title_color,
        val => (`
            color: ${val};
        `)
    )
    
    cssHelper.add(`
    .shopengine-single-product-item .product-title a
    `,
    settings.shopengine_product_title_font_size,
        val => (`
            font-size: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-title a
    `,
    settings.shopengine_product_title_font_weight,
        val => (`
            font-weight: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-title a
    `,
    settings.shopengine_product_title_transform,
        val => (`
            text-transform: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-title a
    `,
    settings.shopengine_product_title_line_height,
        val => (`
            line-height: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-title a
    `,
    settings.shopengine_product_title_wordspace,
        val => (`
            word-spacing: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-title
    `,
    settings.shopengine_product_title_padding,
        val => (`
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price,
    .shopengine-single-product-item .product-price .price span,
    .shopengine-single-product-item .product-price .price .amount
    `,
    settings.shopengine_product_price_color,
        val => (`
            color: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price del span,
    .shopengine-single-product-item .product-price del .amount
    `,
    settings.shopengine_product_sale_price_color,
        val => (`
            color: ${val}!important;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price,
    .shopengine-single-product-item .product-price .price .amount,
    .shopengine-single-product-item .product-price .price ins,
    .shopengine-single-product-item .product-price .price del
    `,
    settings.shopengine_product_price_font_family,
        val => (`
            font-family: ${val.family};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price,
    .shopengine-single-product-item .product-price .price .amount,
    .shopengine-single-product-item .product-price .price ins,
    .shopengine-single-product-item .product-price .price del
    `,
    settings.shopengine_product_price_font_size,
        val => (`
            font-size: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price,
    .shopengine-single-product-item .product-price .price .amount,
    .shopengine-single-product-item .product-price .price ins,
    .shopengine-single-product-item .product-price .price del
    `,
    settings.shopengine_product_price_font_weight,
        val => (`
            font-weight: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price,
    .shopengine-single-product-item .product-price .price .amount,
    .shopengine-single-product-item .product-price .price ins,
    .shopengine-single-product-item .product-price .price del
    `,
    settings.shopengine_product_price_line_height,
        val => (`
            line-height: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price,
    .shopengine-single-product-item .product-price .price .amount,
    .shopengine-single-product-item .product-price .price ins,
    .shopengine-single-product-item .product-price .price del
    `,
    settings.shopengine_product_price_letter_spacing,
        val => (`
            letter-spacing: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price,
    .shopengine-single-product-item .product-price .price .amount,
    .shopengine-single-product-item .product-price .price ins,
    .shopengine-single-product-item .product-price .price del
    `,
    settings.shopengine_product_price_wordspace,
        val => (`
            word-spacing: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-recently-viewed-products .product-price .price
    `,
    settings.shopengine_product_price_space_between,
        val => (`
            gap: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price
    `,
    settings.shopengine_product_price_padding,
        val => (`
           padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    )
    
    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_font_family,
        val => (`
           font-family: ${val.family};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_font_size,
        val => (`
           font-size: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_font_weight,
        val => (`
           font-weight: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_text_transform,
        val => (`
           text-transform: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_line_height,
        val => (`
           line-height: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_wordspace,
        val => (`
           word-spacing: ${val}px;
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_color,
        val => (`
           color: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_product_price_discount_badge_bg_color,
        val => (`
           background-color: ${val};
        `)
    )

    cssHelper.add(`
    .shopengine-single-product-item .product-price .price .shopengine-discount-badge
    `,
    settings.shopengine_price_discount_badge_padding,
        val => (`
           padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .add-to-cart-bt a:not(:last-child)
    `,
    settings.shopengine_recent_product_btns_space_between,
        val => (`
            margin-right: ${val}px;
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_font_size,
        val => (`
            font-size: ${val}px;
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_font_weight,
        val => (`
            font-weight: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_text_transform,
        val => (`
            text-transform: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_line_height,
        val => (`
            line-height: ${val}px;
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_wordspace,
        val => (`
            word-spacing: ${val}px;
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_btn_color,
        val => (`
            color: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_btn_bg_color,
        val => (`
            background-color: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button:hover,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart:hover
    `,
    settings.shopengine_recent_product_add_cart_btn_hover_color,
        val => (`
            color: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button:hover,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart:hover
    `,
    settings.shopengine_recent_product_add_cart_btn_hover_bg_color,
        val => (`
            background-color: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button:hover,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart:hover
    `,
    settings.shopengine_recent_product_add_cart_btn_hover_border_color,
        val => (`
            border-color: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_btn_padding,
        val => (`
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_border_type,
        val => (`
            border-style: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_border_width,
        val => (`
            border-width: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_border_color,
        val => (`
            border-color: ${val};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_border_radius,
        val => (`
            border-radius: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    )

    cssHelper.add(`
    .recent-viewed-product-list .shopengine-single-product-item .button,
    .recent-viewed-product-list .shopengine-single-product-item .added_to_cart
    `,
    settings.shopengine_recent_product_add_cart_btn_margin,
        val => (`
            margin: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    )

    return cssHelper.get()
}

export {Style}