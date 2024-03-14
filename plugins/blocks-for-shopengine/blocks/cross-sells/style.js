const Style = ({settings, breakpoints, cssHelper})=>{

    
    cssHelper.add('.shopengine-widget', {}, () => {
        return (`
            position: relative;
        `)
    } );

    if(settings.shopengine_cross_sells_product_show_flash_sale.desktop == true){
        cssHelper.add('.shopengine-cross-sells .cross-sells .onsale', settings.shopengine_cross_sells_product_show_flash_sale, (val) => {
            return (`
            display: block;
            `)
        } );
    }
    
    if(settings.shopengine_cross_sells_product_show_sale_price.desktop == true){
        cssHelper.add('.shopengine-cross-sells .cross-sells .price del', settings.shopengine_cross_sells_product_show_sale_price, (val) => {
            return (`
            display: inline-block;
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .price del span, .shopengine-cross-sells .price del .amount', settings.shopengine_cross_sells_product_sale_price_color, (val) => {
            return (`
            color: ${val};
            `)
        } );
    }

    if(settings.shopengine_cross_sells_product_show_cart_btn.desktop == true){
        cssHelper.add('.shopengine-cross-sells .cross-sells  .button', settings.shopengine_cross_sells_product_show_cart_btn, (val) => {
            return (`
            display: block;
            `)
        } );
    }
    
    if(settings.shopengine_cross_sells_product_enable_slider.desktop == false){
        cssHelper.add('.shopengine-cross-sells ul.products', settings.shopengine_cross_sells_product_columns, (val) => {
            return (`
            display: grid; 
            grid-template-columns: repeat(${val}, 1fr) !important;
            `)
        } );
    }

    if(settings.shopengine_cross_sells_product_slider_show_dots.desktop == true){
        cssHelper.add('.shopengine-cross-sells .swiper-pagination-bullet', settings.shopengine_cross_sells_product_slider_dots_size, (val) => {
            return (`
            width: ${val}px;
            height: ${val}px;
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .swiper-pagination-bullet.swiper-pagination-bullet-active', settings.shopengine_cross_sells_product_slider_dots_size_active, (val) => {
            return (`
            width: ${val}px;
            height: ${val}px;
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .swiper-pagination-bullet', settings.shopengine_cross_sells_product_slider_dots_color, (val) => {
            return (`
            background: ${val};
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .swiper-pagination-bullet-active', settings.shopengine_cross_sells_product_slider_active_dots_color, (val) => {
            return (`
            border-color: ${val};
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .swiper-pagination', settings.shopengine_cross_sells_product_slider_dot_wrap_margin, (val) => {
            return (`
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
            `)
        } );

    }

    if(settings.shopengine_cross_sells_product_text_align.desktop == "left"){
        cssHelper.add('.shopengine-cross-sells .cross-sells .product, .shopengine-cross-sells .cross-sells .price', {}, (val) => {
            return (`
            text-align: left; 
            justify-content: flex-start;           
            `)
        } );
    }
    if(settings.shopengine_cross_sells_product_text_align.desktop == "center"){
        cssHelper.add('.shopengine-cross-sells .cross-sells .product, .shopengine-cross-sells .cross-sells .price', {}, (val) => {
            return (`
            text-align: center;
            justify-content: center;          
            `)
        } );
    }
    if(settings.shopengine_cross_sells_product_text_align.desktop == "right"){
        cssHelper.add('.shopengine-cross-sells .cross-sells .product, .shopengine-cross-sells .cross-sells .price', {}, (val) => {
            return (`
            text-align: right; 
            justify-content: flex-end;          
            `)
        } );
    }

    cssHelper.add('.shopengine-cross-sells.slider-disabled ul.products', settings.shopengine_cross_sells_product_column_gap, (val) => {
        return (`
        grid-gap: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .products li a', settings.shopengine_cross_sells_product_btns_space_between, (val) => {
        return (`
        margin-right: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_cross_sells_product_flash_sale_color, (val) => {
        return (`
        color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_cross_sells_product_flash_sale_bg_color, (val) => {
        return (`
        background-color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_flash_sale_font_size, (val) => {
        return (`
        font-size: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_flash_sale_font_weight, (val) => {
        return (`
        font_weight: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_flash_sale_text_transform, (val) => {
        return (`
        text-transform: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_flash_sale_line_height, (val) => {
        return (`
        line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_flash_sale_word_spacing, (val) => {
        return (`
        word-spacing: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_cross_sells_product_flash_sale_badge_size, (val) => {
        return (`
        width: ${val}px; 
        height: ${val}px; 
        line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_cross_sells_product_flash_sale_badge_border_radius, (val) => {
        return (`
        border-radius: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .onsale', settings.shopengine_cross_sells_product_flash_sale_padding, (val) => {
        return (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );

    cssHelper.add('.shopengine-cross-sells .cross-sells .onsale', settings.shopengine_cross_sells_product_flash_sale_position_horizontal, (val) => {
        return (`
        ${settings.shopengine_cross_sells_product_flash_sale_position.desktop}: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .onsale', settings.shopengine_cross_sells_product_flash_sale_position_vertical, (val) => {
        return (`
        top: ${val}px;
        `)
    } );

    cssHelper.add('.shopengine-cross-sells .cross-sells .product img', settings.shopengine_cross_sells_product_image_bg_color, (val) => {
        return (`
        background-color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .product img', settings.shopengine_cross_sells_product_image_height, (val) => {
        return (`
        height: ${val}px !important;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .product img', settings.shopengine_related_image_auto_fit, (val) => {
        return (`
        object-fit: cover; 
        object-position:center center;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .product img', settings.shopengine_cross_sells_product_image_padding, (val) => {
        return (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .woocommerce-loop-product__title', settings.shopengine_cross_sells_product_title_color, (val) => {
        return (`
        color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .woocommerce-loop-product__title', settings.shopengine_cross_sells_font_size, (val) => {
        return (`
        font-size: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .woocommerce-loop-product__title', settings.shopengine_cross_sells_font_weight, (val) => {
        return (`
        font_weight: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .woocommerce-loop-product__title', settings.shopengine_cross_sells_text_transform, (val) => {
        return (`
        text-transform: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .woocommerce-loop-product__title', settings.shopengine_cross_sells_line_height, (val) => {
        return (`
        line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .woocommerce-loop-product__title', settings.shopengine_cross_sells_word_spacing, (val) => {
        return (`
        word-spacing: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .woocommerce-loop-product__title', settings.shopengine_cross_sells_product_title_padding, (val) => {
        return (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .products .star-rating::before', settings.shopengine_cross_sells_product_rating_start_color, (val) => {
        return (`
        color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .star-rating', settings.shopengine_cross_sells_product_rating_start_size, (val) => {
        return (`
        font-size: ${val}px !important;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .star-rating', settings.shopengine_cross_sells_product_rating_start_margin, (val) => {
        return (`
        margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .price, .shopengine-cross-sells .price span, .shopengine-cross-sells .price .amount', settings.shopengine_cross_sells_product_price_color, (val) => {
        return (`
        color: ${val};
        `)
    } );

    cssHelper.add('.shopengine-cross-sells .price, .shopengine-cross-sells .price .amount, .shopengine-cross-sells .price ins, .shopengine-cross-sells .price del', settings.shopengine_price_font_size, (val) => {
        return (`
        font-size: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .price, .shopengine-cross-sells .price .amount, .shopengine-cross-sells .price ins, .shopengine-cross-sells .price del', settings.shopengine_price_font_weight, (val) => {
        return (`
        font-weight: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .price, .shopengine-cross-sells .price .amount, .shopengine-cross-sells .price ins, .shopengine-cross-sells .price del', settings.shopengine_price_line_height, (val) => {
        return (`
        line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .price, .shopengine-cross-sells .price .amount, .shopengine-cross-sells .price ins, .shopengine-cross-sells .price del', settings.shopengine_price_word_spacing, (val) => {
        return (`
        word-spacing: ${val}px;
        `)
    } );

    cssHelper.add('.shopengine-cross-sells .price', settings.shopengine_cross_sells_product_price_padding, (val) => {
        return (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );

    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cart_font_size, (val) => {
        return (`
        font-size: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cart_font_weight, (val) => {
        return (`
        font-weight: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cart_text_transform, (val) => {
        return (`
        text-transform: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cart_line_height, (val) => {
        return (`
        line-height: ${val}px;
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cart_word_spacing, (val) => {
        return (`
        word-spacing: ${val}px;
        `)
    } );

    if(settings.shopengine_cross_button_move_end.desktop == true){
        cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', {}, (val) => {
            return (`
            order: 1
            `)
        } );
    }

    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cross_sells_product_add_cart_btn_color, (val) => {
        return (`
        color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cross_sells_product_add_cart_btn_bg_color, (val) => {
        return (`
        background-color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button:hover, .shopengine-cross-sells .cross-sells .added_to_cart:hover', settings.shopengine_cross_sells_product_add_cart_btn_hover_color, (val) => {
        return (`
        color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button:hover, .shopengine-cross-sells .cross-sells .added_to_cart:hover', settings.shopengine_cross_sells_product_add_cart_btn_hover_bg_color, (val) => {
        return (`
        background-color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button:hover, .shopengine-cross-sells .cross-sells .added_to_cart:hover', settings.shopengine_cross_sells_product_add_cart_btn_hover_border_color, (val) => {
        return (`
        border-color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cross_sells_product_add_cart_btn_padding, (val) => {
        return (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cross_sells_product_add_cart_border, (val) => {
        return (`
        border-style: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cross_sells_product_add_cart_border_radius, (val) => {
        return (`
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .button, .shopengine-cross-sells .cross-sells .added_to_cart', settings.shopengine_cross_sells_product_add_cart_btn_margin, (val) => {
        return (`
        margin: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `)
    } );

    if(settings.shopengine_cross_sells_product_slider_show_arrows.desktop == true){
        cssHelper.add('.shopengine-cross-sells .swiper-button-prev, .shopengine-cross-sells .swiper-button-next', settings.shopengine_cross_sells_product_slider_arrow_icon_size, (val) => {
            return (`
            font-size: ${val}px;
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .swiper-button-next, .shopengine-cross-sells .swiper-button-prev', settings.shopengine_cross_sells_product_slider_arrow_size, (val) => {
            return (`
            width: ${val}px;
            height: ${val}px; 
            line-height: ${val}px;
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .swiper-button-prev, .shopengine-cross-sells .swiper-button-next)', settings.shopengine_cross_sells_product_slider_arrow_border, (val) => {
            return (`
            border-style: ${val};
            `)
        } );
        cssHelper.add('.shopengine-cross-sells .swiper-button-prev, .shopengine-cross-sells .swiper-button-next)', settings.shopengine_cross_sells_product_slider_arrow_border_radius, (val) => {
            return (`
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
            `)
        } );
    }
    
    cssHelper.add('.shopengine-cross-sells .swiper-button-prev, .shopengine-cross-sells .swiper-button-next', settings.shopengine_cross_sells_product_slider_arrow_btn_color, (val) => {
        return (`
        color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .swiper-button-prev, .shopengine-cross-sells .swiper-button-next', settings.shopengine_cross_sells_product_slider_arrow_btn_bg_color, (val) => {
        return (`
        background-color: ${val};
        `)
    } );

    cssHelper.add('.shopengine-cross-sells .swiper-button-prev:hover, .shopengine-cross-sells .swiper-button-next:hover', settings.shopengine_cross_sells_product_slider_arrow_btn_hover_color, (val) => {
        return (`
        color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .swiper-button-prev:hover, .shopengine-cross-sells .swiper-button-next:hover', settings.shopengine_cross_sells_product_slider_arrow_btn_hover_bg_color, (val) => {
        return (`
        background-color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .swiper-button-prev:hover, .shopengine-cross-sells .swiper-button-next:hover', settings.shopengine_cross_sells_product_slider_arrow_btn_hover_border_color, (val) => {
        return (`
        border-color: ${val};
        `)
    } );
    cssHelper.add('.shopengine-cross-sells .cross-sells .onsale, .shopengine-cross-sells .cross-sells .woocommerce-loop-product__title, .shopengine-cross-sells .cross-sells .price, .shopengine-cross-sells .cross-sells del, .shopengine-cross-sells .cross-sells ins, .shopengine-cross-sells .cross-sells .button', settings.font_family, (val) => {
        return ` 
        font-family: ${val.family};
        `
    } );
    



    return cssHelper.get()
}

export {Style}