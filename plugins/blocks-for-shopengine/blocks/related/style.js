
const Style = ({settings, breakpoints, cssHelper})=>{
    const getObjectValues = (obj) => {
        return [...Object.values(obj)].toString().split(',').join(' ');
    }
    cssHelper.add(`
    .shopengine-related .related .onsale
    `,settings.simple_test, (val) => (`
        display: ${val ? "block" : "none"};
    `))
    .add(`
    .shopengine-related .related .price del
    `,settings.shopengine_sale_price, (val) => (`
        display: ${val ? "inline-block" : "none"};
    `))
    .add(`
    .shopengine-related .related  .button
    `,settings.shopengine_car_button, (val) => (`
        display: ${val ? "block" : "none"};
    `))
    if(!settings.shopengine_enable_slider.desktop){
        cssHelper.add(`
        .shopengine-widget .shopengine-related .related ul.products
        `,settings.shopengine_column, (val) => (`
            display: grid; 
            grid-template-columns: repeat(${val}, 1fr);
        `))
    }
    if(settings.shopengine_show_dots.desktop){
        cssHelper.add(`
        .shopengine-related .swiper-pagination-bullet
        `,settings.shopengine_dot_size, (val) => (`
            width: ${val}px;
            height: ${val}px;
            border-radius : 50%;
        `))
        .add(`
        .swiper-pagination-bullet-active
        `,settings.shopengine_active_dot_size, (val) => (`
            width: ${val}px !important;
            height: ${val}px !important;
        `))
    }
    cssHelper.add(`
    .shopengine-widget .shopengine-related .related .products .product,
    .shopengine-widget .shopengine-related .price
        `,settings.shopengine_item_alignment, (val) => (`
            text-align: ${val};
            justify-content: ${val === "right" ? "flex-end" : val === "center" ? val : "flex-start"}
        `))
    
    cssHelper.add(`
    .shopengine-related.slider-disabled ul.products
    `,settings.shopengine_item_column_gap, (val) => (`
        grid-gap: ${val}px;
    `))

    cssHelper.add(`
    .shopengine-related .related .products li a:not(.woocommerce-LoopProduct-link) , 
    .shopengine-related .related .products li a:not(.add_to_cart_button),
    .shopengine-related .related .products li a:not(.product_type_simple),
    .shopengine-related .related .products li a:not(.product_type_external),
    .shopengine-related .related .products li a:not(.product_type_variable),
    .shopengine-related .related .products li a:not(:last-child)
    `,settings.shopengine_product_item_space_between, (val) => (`
        margin-right: ${val}px;
    `))
    .add(`
    .shopengine-related .related .products li a:not(.woocommerce-LoopProduct-link) , 
    .shopengine-related .related .products li a:not(.add_to_cart_button),
    .shopengine-related .related .products li a:not(.product_type_simple),
    .shopengine-related .related .products li a:not(.product_type_external),
    .shopengine-related .related .products li a:not(.product_type_variable)
    `,settings.shopengine_module_icon_size, (val) => (`
        font-size: ${val}px;
    `))
    .add(`
    .shopengine-related .onsale
    `,settings.shopengine_flash_sale_color, (val) => (`
        color: ${val}
    `))
    .add(`
    .shopengine-related .onsale
    `,settings.shopengine_flash_sale_bg_color, (val) => (`
        background-color: ${val}
    `))
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_flash_sale_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .onsale'
    ,settings.shopengine_flash_Sale_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_flash_Sale_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_flash_Sale_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_flash_Sale_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_flash_Sale_letter_spacing, (val) => {
        return `
        letter-spacing: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_product_badge_size, (val) => {
        return `
        width: ${val}px;
        height: ${val}px;
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_badge_radius, (val) => {
        return `
        border-radius: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .onsale',
    settings.shopengine_badge_padding, (val) => {
        return `
        padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `
    } )
    if(settings.shopengine_badge_position === "left"){
        cssHelper.add('.shopengine-related .onsale',
        {}, (val) => {
            return `
                top: 20px;
                left: 25px
            `
        } )
        cssHelper.add('.shopengine-related .onsale',
        settings.shopengine_badge_horizontal_space, (val) => {
            return `
                left: ${val}px
            `
        } )
    }
    if(settings.shopengine_badge_position === "right"){
        cssHelper.add('.shopengine-related .onsale',
        {}, (val) => {
            return `
                top: 20px;
                right: 25px
            `
        } )
        cssHelper.add('.shopengine-related .onsale',
        settings.shopengine_badge_horizontal_space, (val) => {
            return `
                right: ${val}px
            `
        } )
    }
    cssHelper.add('.shopengine-related .onsale',
        settings.shopengine_badge_vertical_space, (val) => {
            return `
                top: ${val}px
            `
        } )
        .add(`
        .shopengine-related .related .product img
        `,settings.shopengine_image_bg_color, (val) => (`
            background-color: ${val}
        `))
        .add(`
        .shopengine-related .related .product img
        `,settings.shopengine_image_height, (val) => (`
            height: ${val}px !important;
        `))
    if(settings.shopengine_auto_fit.desktop){
        cssHelper.add(`
        .shopengine-related .related .product img
        `,
        {}, (val) => {
            return `
            object-fit: cover; object-position:center center;
            `
        } )
        
    }
    cssHelper.add(`
        .shopengine-related .related .product img
        `,
        settings.shopengine_image_padding, (val) => {
            return `
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        } )   
    cssHelper.add(`
    .shopengine-related .woocommerce-loop-product__title
        `,
        settings.shopengine_title_color, (val) => {
            return `
            color:${val};
            `
        } )  
        cssHelper.add('.shopengine-related .woocommerce-loop-product__title',
    settings.shopengine_title_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .woocommerce-loop-product__title'
    ,settings.shopengine_title_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add('.shopengine-related .woocommerce-loop-product__title',
    settings.shopengine_title_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    } )
    cssHelper.add('.shopengine-related .woocommerce-loop-product__title',
    settings.shopengine_title_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-related .woocommerce-loop-product__title',
    settings.shopengine_title_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add(`
    .shopengine-related .woocommerce-loop-product__title
        `,
        settings.shopengine_title_padding, (val) => {
            return `
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        } )  
        cssHelper.add(`
        .shopengine-related .products .star-rating,
        .shopengine-related .products .star-rating::before
            `,
        settings.shopengine_rating_star_color, (val) => {
            return `
            color:${val};
            `
        } ) 
        cssHelper.add('.shopengine-related .products .star-rating',
        settings.shopengine_rating_star_size, (val) => {
            return `
            font-size: ${val}px;
            `
        } ) 
        cssHelper.add(`
        .shopengine-related .products .star-rating
        `,
        settings.shopengine_rating_margin, (val) => {
            return `
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        } )
        cssHelper.add(`
        .shopengine-related :is(.price .amount),
        .shopengine-related :is(.price del span),
        .shopengine-related :is(.price del)
        `,
        settings.shopengine_price_color, (val) => {
            return `
            color:${val};
            `
        } ) 
        cssHelper.add(`
        .shopengine-related .price bdi .amount
        `,
        settings.shopengine_sale_price_color, (val) => {
            return `
            color:${val};
            `
        } ) 
        cssHelper.add(`
        .shopengine-related .price,
        .shopengine-related .price .amount,
        .shopengine-related .price ins,
        .shopengine-related .price del
        `,
        settings.shopengine_price_font_family, (val) => {
        return `
        font-family: ${val.family}px;
        `
        } )
        cssHelper.add(`
        .shopengine-related .price,
        .shopengine-related .price .amount,
        .shopengine-related .price ins,
        .shopengine-related .price del
        `,
        settings.shopengine_price_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
        })
    cssHelper.add(`
    .shopengine-related .price,
    .shopengine-related .price .amount,
    .shopengine-related .price ins,
    .shopengine-related .price del
    `
    ,settings.shopengine_price_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add(`
        .shopengine-related .price,
        .shopengine-related .price .amount,
        .shopengine-related .price ins,
        .shopengine-related .price del
        `,
    settings.shopengine_price_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add(`
        .shopengine-related .price,
        .shopengine-related .price .amount,
        .shopengine-related .price ins,
        .shopengine-related .price del
        `,
    settings.shopengine_price_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    cssHelper.add(`
        .shopengine-related .price
        `,settings.shopengine_price_padding, (val) => {
            return `
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        })
    cssHelper.add(`
        .shopengine-related .related .products .product a.button,
        .shopengine-related .related .products .product a.added_to_cart
        `,settings.shopengine_cart_font_size, (val) => {
            return(`
                font-size: ${val}px;
            `)
        })
    cssHelper.add(`
    .shopengine-related .related .products .product a.button,
    .shopengine-related .related .products .product a.added_to_cart
    `,settings.shopengine_cart_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } )
    cssHelper.add(`
    .shopengine-related .related .products .product a.button,
    .shopengine-related .related .products .product a.added_to_cart
    `,settings.shopengine_cart_text_transform, (val) => {
        return (`
            text-transform: ${val};
        `)
    } )
    cssHelper.add(`
    .shopengine-related .related .products .product a.button,
    .shopengine-related .related .products .product a.added_to_cart
    `,settings.shopengine_cart_line_height, (val) => {
        return `
        line-height: ${val}px;
        `
    } )
    cssHelper.add(`
    .shopengine-related .related .products .product a.button,
    .shopengine-related .related .products .product a.added_to_cart
    `,
    settings.shopengine_cart_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    } )
    if(settings.shopengine_position_end.desktop){
        cssHelper.add(`
        .shopengine-related .related .button,
        .shopengine-related .related .added_to_cart
        `,
    {}, (val) => {
        return `
        order: 1;
        `
    } )
    }
    cssHelper.add(`
        .shopengine-related .related .button,
        .shopengine-related .related .added_to_cart
            `,
        settings.shopengine_cart_color, (val) => {
            return `
            color:${val};
            `
        } )
    cssHelper.add(`
        .shopengine-related .related .button,
        .shopengine-related .related .added_to_cart
            `,
        settings.shopengine_cart_bg, (val) => {
            return `
            background-color:${val};
            `
        } ) 
    cssHelper.add(`
        .shopengine-related .related .button:hover,
        .shopengine-related .related .added_to_cart:hover
            `,
        settings.shopengine_cart_hover_color, (val) => {
            return `
            color:${val};
            `
        } )
    cssHelper.add(`
        .shopengine-related .related .button:hover,
        .shopengine-related .related .added_to_cart:hover
            `,
        settings.shopengine_cart_hover_bg, (val) => {
            return `
            background-color:${val};
            `
        } )  
    cssHelper.add(`
        .shopengine-related .related .button:hover,
        .shopengine-related .related .added_to_cart:hover
            `,
        settings.shopengine_cart_hover_border_color, (val) => {
            return `
            border-color:${val};
            `
        } )
        cssHelper.add(`
        .shopengine .shopengine-related .related .button,
        .shopengine .shopengine-related .related .added_to_cart
        `,settings.shopengine_cart_padding, (val) => {
            return `
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        } ) 
        cssHelper.add(`
        .shopengine-related .related .products .product a.button,
        .shopengine-related .related .products .product a.added_to_cart
        `,settings.shopengine_cart_border, (val) => {
            return `
            border-style:${val};
            `
        })

        cssHelper.add(`
        .shopengine-related .related .products .product a.button,
        .shopengine-related .related .products .product a.added_to_cart
        `,settings.shopengine_cart_border_width, (val) => {
            return `
            border-width: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        })

        cssHelper.add(`
        .shopengine-related .related .products .product a.button,
        .shopengine-related .related .products .product a.added_to_cart
        `,settings.shopengine_cart_border_color, (val) => {
            return `
            border-color: ${val};
            `
        })
        cssHelper.add(`
        .shopengine-related .related .products .product a.button,
        .shopengine-related .related .products .product a.added_to_cart
        `,settings.shopengine_cart_border_radius, (val) => {
            return `
            border-radius: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        } )
        cssHelper.add(`
        .shopengine-related .related .products .product a.button,
        .shopengine-related .related .products .product a.added_to_cart
        `,
        settings.shopengine_cart_margin, (val) => {
            return `
            margin: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `
        } )   
        if(settings.shopengine_show_arrows.desktop){
            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,
            settings.shopengine_icon_size, (val) => {
                return `
                font-size: ${val}px;
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,
            settings.shopengine_arrow_size, (val) => {
                return `
                width: ${val}px;
                align-items: center;
                justify-content: center;
                display: flex;
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,
            settings.shopengine_arrow_color, (val) => {
                return `
                color: ${val};
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,
            settings.shopengine_arrow_bg, (val) => {
                return `
                background-color: ${val};
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-button-prev:hover,
            .shopengine-related .swiper-button-next:hover
            `,
            settings.shopengine_arrow_hover_color, (val) => {
                return `
                color: ${val};
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-button-prev:hover,
            .shopengine-related .swiper-button-next:hover
            `,
            settings.shopengine_arrow_hover_bg, (val) => {
                return `
                background-color: ${val};
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-button-prev:hover,
            .shopengine-related .swiper-button-next:hover
            `,
            settings.shopengine_arrow_hover_border_color, (val) => {
                return `
                border-color: ${val};
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,
            settings.shopengine_arrow_border, (val) => {
                return `
                border-style:${val};
                `
            } )

            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,settings.shopengine_arrow_border_width, (val) => {
                return`
                border-width: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
                `
            } )

            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,settings.shopengine_arrow_border_color, (val) => {
                return`
                border-color: ${val};
                `
            } )


            cssHelper.add(`
            .shopengine-related .swiper-button-prev,
            .shopengine-related .swiper-button-next
            `,settings.shopengine_arrow_border_radius, (val) => {
                return `
                border-radius: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
                `
            } )
            
        }
        if(settings.shopengine_show_dots.desktop){
            cssHelper.add(`
            .shopengine-related .swiper-pagination-bullet
            `,
            settings.shopengine_dots_color, (val) => {
                return `
                background-color: ${val};
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-pagination-bullet-active
            `,
            settings.shopengine_dots_active_bg, (val) => {
                return `
                border-color: ${val};
                `
            } )
            cssHelper.add(`
            .shopengine-related .swiper-pagination
                `,
                settings.shopengine_wrap_margin, (val) => {
                return `
                margin: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
                `
            } ) 
        }
        cssHelper.add(`
        .shopengine-related .related .onsale,
        .shopengine-related .related .woocommerce-loop-product__title,
        .shopengine-related .related .price, 
        .shopengine-related .related del, 
        .shopengine-related .related  ins,
        .shopengine-related .related .button
        `,
        settings.font_family, (val) => {
            return `
            font-family:${val.family};
            `
        } ) 
        

    return cssHelper.get()
}

export {Style}