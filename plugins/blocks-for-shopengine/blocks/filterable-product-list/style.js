
const Style = ({settings, breakpoints, cssHelper})=>{
    const {blockId, shopengine_products_column_no, shopengine_product_filter_alignment, shopengine_product_filter_font_size, shopengine_product_filter_font_weight, shopengine_product_filter_text_transform,shopengine_product_filter_line_height,shopengine_product_filter_letter_spacing, shopengine_product_filter_word_spacing, shopengine_product_filter_color, shopengine_product_filter_hover_color, shopengine_item_indicator_padding,shopengine_item_indicator_bottom, shopengine_item_indicator_border,shopengine_item_indicator_color, shopengine_nav_wrap_margin} = settings;

    const shopengine_flash_sale_show = settings.shopengine_flash_sale_show.desktop === true ? "block" : "none";
    const shopengine_regular_price_show = settings.shopengine_regular_price_show.desktop === true ? "inline-block" : "none";
    const shopengine_off_tag_badge_show = settings.shopengine_off_tag_badge_show.desktop === true ? "inline-block" : "none";
    
    cssHelper.add('.product-tag-sale-badge',{}, (val) => {
        return `
        display: ${shopengine_flash_sale_show};
        `
    } )
    cssHelper.add('.price del',{}, (val) => {
        return `
        display: ${shopengine_regular_price_show};
        `
    } )
    cssHelper.add('.price .shopengine-badge',{}, (val) => {
        return `
        display: ${shopengine_off_tag_badge_show};
        `
    } )
    cssHelper.add('.shopengine-widget .shopengine-filterable-product-wrap .filter-content-row', shopengine_products_column_no, (val) => {
        return `
        grid-template-columns: repeat(${val}, 1fr);
        `
    } )
    cssHelper.add('.filter-nav',shopengine_product_filter_alignment, (val) => {
        return `
        text-align: ${val};
        `
    } )
    cssHelper.add('.filter-nav a ',shopengine_product_filter_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.filter-nav a ',shopengine_product_filter_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.filter-nav a ',shopengine_product_filter_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.filter-nav a ',shopengine_product_filter_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.filter-nav a ',shopengine_product_filter_letter_spacing, (val) => {
        return `
        letter-spacing: ${val}px;
        `
    } )
    cssHelper.add('.filter-nav a ',shopengine_product_filter_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.filter-nav a ',shopengine_product_filter_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.filter-nav a.active, .filter-nav a:hover ',shopengine_product_filter_hover_color, (val) => {
        return `
        color: ${val};
        `
    } )
    
    cssHelper.add('.filter-nav li a',shopengine_item_indicator_padding, (val) => {
        return `
        margin-right: ${val.right};
        margin-top :0px;
        margin-bottom: 0px;
        `
    } )
    cssHelper.add('.filter-nav li a',shopengine_item_indicator_padding, (val) => {
        return `
        margin-left: ${val.left};
        `
    } )
    cssHelper.add('.filter-nav li a',shopengine_item_indicator_padding, (val) => {
        return `
        padding-left: 0px;
        padding-top : ${val.top}
        `
    } )
    cssHelper.add('.filter-nav li a',shopengine_item_indicator_padding, (val) => {
        return `
        padding-right: 0px;
        padding-bottom: ${val.bottom};
        `
    } )
    cssHelper.add('.filter-nav a:hover::before, .filter-nav a.active::before',shopengine_item_indicator_bottom, (val) => {
        return `
        border-style: ${val};
        `
    } )
    cssHelper.add('.filter-nav a:hover::before, .filter-nav a.active::before',shopengine_item_indicator_bottom, (val) => {
        return `
        border-style: ${val};
        `
    } )
    cssHelper.add('.filter-nav a:hover::before, .filter-nav a.active::before',shopengine_item_indicator_border, (val) => {
        return `
        border-width: 0px 0px ${val.bottom} 0px;
        `
    } )
    cssHelper.add('.filter-nav a:hover::before, .filter-nav a.active::before',shopengine_item_indicator_color, (val) => {
        return `
        border-color: ${val};
        `
    } )
    if (settings.shopengine_nav_separator.desktop) {
    cssHelper.add('.filter-nav li:not(:last-child)::before',settings.shopengine_nav_separator_height, (val) => {
        return `
        height: ${val}px;
        `
    } )
    cssHelper.add('.filter-nav li:not(:last-child)::before',settings.shopengine_nav_separator_position, (val) => {
        return `
        top: ${val}px;
        `
    } )
    cssHelper.add('.filter-nav li:not(:last-child)::before',settings.shopengine_nav_separator_border, (val) => {
        return `
        border-style: ${val};
        `
    } )
    cssHelper.add('.filter-nav li:not(:last-child)::before',settings.shopengine_nav_separator_width, (val) => {
        return `
        border-width: 0 ${val}px 0 0;
        `
    } )
    cssHelper.add('.filter-nav li:not(:last-child)::before',settings.shopengine_nav_separator_color, (val) => {
        return `
        border-color: ${val};
        `
    } )
}
    cssHelper.add('.filter-nav li a:not(.active, :hover)',settings.shopengine_nav_item_border, (val) => {
        return `
        border-style: ${val};
        `
    } )
    if(settings.shopengine_active_item_border.desktop){
    cssHelper.add('.filter-nav li a.active, .filter-nav li a:hover',settings.shopengine_nav_item_border, (val) => {
        return `
        border-style: ${val};
        `
    } )
    cssHelper.add('.filter-nav li a.active, .filter-nav li a:hover',settings.shopengine_nav_item_border_width, (val) => {
        return `
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.filter-nav li a.active, .filter-nav li a:hover',settings.shopengine_nav_item_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } )
    cssHelper.add('.filter-nav li a.active, .filter-nav li a:hover',settings.shopengine_nav_item_border_radius, (val) => {
        return `
        border-radius: ${val}px;
        `
    } )
}
    cssHelper.add('.filter-nav',shopengine_nav_wrap_margin, (val) => {
        return `
        margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.filter-nav',settings.shopengine_nav_wrap_border, (val) => {
        return `
        border-style: ${val};
        `
    } )
    cssHelper.add('.filter-nav',settings.shopengine_nav_wrap_border_width, (val) => {
        return `
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.filter-nav',settings.shopengine_nav_wrap_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } )

    if(settings.shopengine_product_wrap_alignment.desktop == "left"){
        cssHelper.add('.shopengine-single-product-item .product-category ul, .shopengine-single-product-item .product-title, .shopengine-single-product-item .product-rating, .shopengine-single-product-item .price, .shopengine-single-product-item .add-to-cart-bt',settings.shopengine_product_wrap_alignment, (val) => {
            return `
            text-align: left;
            -webkit-box-pack: start;
            -ms-flex-pack: start; 
            justify-content: flex-start;
            `
        } )
    } 
    if(settings.shopengine_product_wrap_alignment.desktop == "center"){
        cssHelper.add('.shopengine-single-product-item .product-category ul, .shopengine-single-product-item .product-title, .shopengine-single-product-item .product-rating, .shopengine-single-product-item .price, .shopengine-single-product-item .add-to-cart-bt',settings.shopengine_product_wrap_alignment, (val) => {
            return `
            text-align: center; 
            -webkit-box-pack: center; 
            -ms-flex-pack: center; 
            justify-content: center;
            `
        } )
    }
    if(settings.shopengine_product_wrap_alignment.desktop == "right"){
        cssHelper.add('.shopengine-single-product-item .product-category ul, .shopengine-single-product-item .product-title, .shopengine-single-product-item .product-rating, .shopengine-single-product-item .price, .shopengine-single-product-item .add-to-cart-bt',settings.shopengine_product_wrap_alignment, (val) => {
            return `
            text-align: right; 
            -webkit-box-pack: end; 
            -ms-flex-pack: end; 
            justify-content: flex-end;
            `
        } )
    }
    cssHelper.add('.shopengine-single-product-item',settings.shopengine_product_wrap_color, (val) => {
        return `
        background-color: ${val};
        `
    } )
    cssHelper.add('.shopengine-gutenova_filterable_product_list .filtered-product-list.active',settings.shopengine_product_wrap_gap, (val) => {
        return `
        grid-gap: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-single-product-item',settings.shopengine_product_wrap_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.shopengine-single-product-item',settings.shopengine_product_wrap_border, (val) => {
        return `
        border-style: ${val};
        `
    } )

    cssHelper.add('.product-thumb',settings.shopengine_product_image_bg, (val) => {
        return `
        background: ${val};
        `
    } )
    cssHelper.add('.product-thumb',settings.shopengine_product_image_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.product-category ul li a',settings.shopengine_product_category_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.product-category ul li a',settings.shopengine_product_category_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.product-category ul li a',settings.shopengine_product_category_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.product-category ul li a',settings.shopengine_product_category_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.product-category ul li a',settings.shopengine_product_category_word_spacing, (val) => {
        return `
        letter-spacing: ${val}px;
        `
    } )
    cssHelper.add('.product-category ul li a',settings.shopengine_product_category_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.product-category ul li a:hover',settings.shopengine_product_category_hover_color, (val) => {
        return `
        color: ${val};
        `
    } )
    
    cssHelper.add('.product-category',settings.shopengine_product_category_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        line-height: 0;
        `
    } )
    cssHelper.add('.product-title',settings.shopengine_product_title_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.product-title',settings.shopengine_product_title_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.product-title',settings.shopengine_product_title_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.product-title',settings.shopengine_product_title_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.product-title',settings.shopengine_product_title_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.product-title',settings.shopengine_product_title_Letter_spacing, (val) => {
        return `
        letter-spacing: ${val}px;
        `
    } )

    cssHelper.add('.product-title a',settings.shopengine_product_title_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.product-title a:hover',settings.shopengine_product_title_hover_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.product-title',settings.shopengine_product_title_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        margin: 0;
        `
    } )
    cssHelper.add('.product-rating .star-rating',settings.shopengine_rating_star_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.product-rating .star-rating',settings.shopengine_rating_star_color, (val) => {
        return `
        color: ${val} !important;
        `
    } )
    cssHelper.add('.product-rating .star-rating::before',settings.shopengine_empty_star_color, (val) => {
        return `
        color: ${val} !important;
        `
    } )
    cssHelper.add('.rating-count',settings.shopengine_count_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.rating-count',settings.shopengine_count_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.rating-count',settings.shopengine_count_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.rating-count',settings.shopengine_count_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.product-rating',settings.shopengine_count_padding, (val) => {
        return `
        line-height: 0; 
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_letter_spacing, (val) => {
        return `
        letter-spacing: ${val}px;
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_bg, (val) => {
        return `
        background: ${val};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_border, (val) => {
        return `
        border-style: ${val};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_border_radius, (val) => {
        return `
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.product-price .shopengine-discount-badge',settings.shopengine_off_tag_margin, (val) => {
        return `
        margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_bg, (val) => {
        return `
        background-color:${val};
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_border_radius, (val) => {
        return `
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_size, (val) => {
        return ` 
        line-height: ${val}px
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_size, (val) => {
        return `
        width: ${val}px;
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_size, (val) => {
        return `
        height: ${val}px;
        `
    } )

    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_horizontal, (val) => {
        return `
        ${settings.shopengine_flash_sale_position.desktop}: ${val}% ;
        `
    } )
    cssHelper.add('.product-tag-sale-badge',settings.shopengine_flash_sale_vertical, (val) => {
        return `
        top: ${val}% ;
        `
    } )
    .add('.product-price .price del span.amount',settings.shopengine_sale_price_color, (val) => {
        return `
            color: ${val} ;
        `
    } )
    .add('.product-price .price span.amount',settings.shopengine_price_color, (val) => {
        return `
            color: ${val} ;
        `
    } )
    .add('.product-price .price',settings.shopengine_sale_price_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left} ;
        `
    } )
    .add('.product-price .price ins',settings.shopengine_space_between, (val) => {
        return `
        margin-right: ${val}px ;
        `
    } )
    
    .add('.product-price .price',settings.shopengine_price_font_size, (val) => {
        return `
            font-size: ${val}px ;
        `
    } )
    .add('.product-price .price',settings.shopengine_price_font_weight, (val) => {
        return `
            font-weight: ${val} ;
        `
    } )
    .add('.product-price .price',settings.shopengine_price_word_spacing, (val) => {
        return `
            letter-spacing: ${val}px ;
        `
    } )
    .add(`.shopengine-single-product-item .product-price .onsale-off,
    .shopengine-single-product-item .product-price .price del`,settings.shopengine_sale_price_font_size, (val) => {
        return `
            font-size: ${val}px ;
        `
    } )
    .add(`.shopengine-single-product-item .product-price .onsale-off,
    .shopengine-single-product-item .product-price .price del`,settings.shopengine_sale_price_font_weight, (val) => {
        return `
            font-weight: ${val} ;
        `
    } )
    .add(`.shopengine-single-product-item .product-price .onsale-off,
    .shopengine-single-product-item .product-price .price del`,settings.shopengine_sale_price_text_transform, (val) => {
        return `
            text-transform: ${val} ;
        `
    } )
    .add(`.shopengine-single-product-item .product-price .onsale-off,
    .shopengine-single-product-item .product-price .price del`,
    settings.shopengine_sale_price_line_height, (val) => {
        return `
            line-height: ${val}px ;
        `
    } )
    .add(`.shopengine-single-product-item .product-price .onsale-off,
    .shopengine-single-product-item .product-price .price del`,
    settings.shopengine_sale_price_letter_spacing, (val) => {
        return `
            letter-spacing: ${val}px ;
        `
    } )


    cssHelper.add('.prodcut-description',settings.shopengine_description_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_letter_spacing, (val) => {
        return `
        letter-spacing: ${val}px;
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } )
    cssHelper.add('.prodcut-description',settings.shopengine_description_border, (val) => {
        return `
        border-style: ${val};
        `
    } )
    
    .add('.prodcut-description',settings.shopengine_description_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left} ;
        `
    } )
    .add('.prodcut-description',settings.shopengine_description_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left} ;
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_letter_spacing, (val) => {
        return `
        letter-spacing: ${val}px;
        `
    } )
    .add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left} ;
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_color, (val) => {
        return `
        color: ${val};
        border: 0!important;
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button',settings.shopengine_cart_bg, (val) => {
        return `
        background: ${val};
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button:hover',settings.shopengine_cart_hover_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.filter-content .filtered-product-list .shopengine-single-product-item .add-to-cart-bt .button:hover',settings.shopengine_cart_hover_bg, (val) => {
        return `
        background: ${val};
        `
    } )
    cssHelper.add('.filter-nav a, .product-category ul li a, .product-title, .rating-count, .product-tag-sale-badge, .product-price .price, .product-price .onsale-off, .shopengine-single-product-item .product-price .price del, .prodcut-description, .button',settings.font_family, (val) => {
        return `
        font-family: ${val.family};
        `
    } )

    return cssHelper.get()
}

export {Style}