
const Style = ({settings, breakpoints, cssHelper})=>{
    
    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products-container
    `, settings.shopengine_layout_column, (val) => {
        return (
            `
            grid-template-columns : repeat(${val}, 1fr);

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products-container
    `, settings.shopengine_layout_product_col_gap, (val) => {
        return (
            `
            column-gap : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products-container
    `, settings.shopengine_layout_product_row_gap, (val) => {
        return (
            `
            row-gap : ${val};

            `
        )
    })

    
    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products
    `, settings.shopengine_deal_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products
    `, settings.shopengine_product_wrapper_bg, (val) => {
        return (
            `
            background-color : ${val};

            `
        )
    })



    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products
    `, settings.shopengine_product_wrapper_border_width, (val) => {
        return (
            `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products
    `, settings.shopengine_product_wrapper_border_clr, (val) => {
        return (
            `
            border-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__top--img
    `, settings.shopengine_product_image_height, (val) => {
        return (
            `
            height : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__top--img
    `, settings.shopengine_product_image_size, (val) => {
        return (
            `
            object-fit : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__top--img
    `, settings.shopengine_product_image_position, (val) => {
        return (
            `
            object-position : ${val};

            `
        )
    })

    
    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.shopengine_product_percentage_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.shopengine_product_percentage_radius, (val) => {
        return (
            `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.shopengine_product_percentage_position_left, (val) => {
        return (
            `
            left : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.shopengine_product_percentage_position_top, (val) => {
        return (
            `
            top : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.shopengine_product_percentage_clr, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.shopengine_product_percentage_bg_clr, (val) => {
        return (
            `
            background-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.product_percentage_font_size, (val) => {
        return (
            `
            font-size: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.product_percentage_font_weight, (val) => {
        return (
            `
            font-weight: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.shopengine_product_sale_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.shopengine_product_sale_radius, (val) => {
        return (
            `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.shopengine_product_sale_position_left, (val) => {
        return (
            `
            left : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.shopengine_product_sale_position_top, (val) => {
        return (
            `
            top : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.shopengine_product_sele_clr, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.shopengine_product_sale_bg_clr, (val) => {
        return (
            `
            background-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.product_sale_font_size, (val) => {
        return (
            `
            font-size: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.product_sale_font_weight, (val) => {
        return (
            `
            font-weight: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.product_sale_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.product_sale_line_height, (val) => {
        return (
            `
            line-height : ${val};

            `
        )
    })

    
    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.product_sale_letter_spacing, (val) => {
        return (
            `
            letter-spacing : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .shopengine-sale-badge
    `, settings.product_sale_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:first-child
    `, settings.shopengine_product_countDown_number_clr, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:first-child
    `, settings.product_countDown_number_font_size, (val) => {
        return (
            `
            font-size: ${val};
            
            `
        )
    })

    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:first-child
    `, settings.product_countDown_number_font_weight, (val) => {
        return (
            `
            font-weight: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:first-child
    `, settings.product_countDown_number_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:first-child
    `, settings.product_countDown_number_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:last-child
    `, settings.shopengine_product_countDown_label_clr, (val) => {
        return (
            `
            color: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:last-child
    `, settings.product_countDown_label_font_size, (val) => {
        return (
            `
            font-size: ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:last-child
    `, settings.product_countDown_label_font_weight, (val) => {
        return (
            `
            font-weight: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:last-child
    `, settings.product_countDown_label_text_transform, (val) => {
        return (
            `
            text-transform: ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:last-child
    `, settings.product_countDown_label_line_height, (val) => {
        return (
            `
            line-height: ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item span:last-child
    `, settings.product_countDown_label_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item
    `, settings.shopengine_product_countDown_bg, (val) => {
        return (
            `
            background-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item
    `, settings.shopengine_product_countDown_border_clr, (val) => {
        return (
            `
            border-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item
    `, settings.shopengine_product_countDown_border_width, (val) => {
        return (
            `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item
    `, settings.shopengine_product_countDown_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock
    `, settings.shopengine_product_countDown_space_bottom, (val) => {
        return (
            `
            bottom : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-countdown-clock
    `, settings.shopengine_product_countDown_width, (val) => {
        return (
            `
            width : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a
    `, settings.shopengine_product_content_title_clr, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a:hover
    `, settings.shopengine_product_content_title_hover_clr, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a
    `, settings.product_content_title_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a
    `, settings.product_content_title_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a
    `, settings.product_content_title_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a
    `, settings.product_content_title_line_height, (val) => {
        return (
            `
            line-height : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a
    `, settings.product_content_title_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__desc--name a
    `, settings.shopengine_product_content_title_margin, (val) => {
        return (
            `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices
    `, settings.shopengine_product_content_prices_margin, (val) => {
        return (
            `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices del .amount
    `, settings.shopengine_product_content_reg_price_clr, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices del .amount
    `, settings.product_content_reg_price_font_size, (val) => {
        return (
            `
            font-size: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices del .amount
    `, settings.product_content_reg_price_font_weight, (val) => {
        return (
            `
            font-weight: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices del .amount
    `, settings.product_content_reg_price_text_transform, (val) => {
        return (
            `
            text-transform: ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices del .amount
    `, settings.product_content_reg_price_word_spacing, (val) => {
        return (
            `
            word-spacing: ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices ins .amount
    `, settings.shopengine_product_content_sell_price_clr, (val) => {
        return (
            `
            color: ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices ins .amount
    `, settings.product_content_sell_price_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices ins .amount
    `, settings.product_content_sell_price_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices ins .amount
    `, settings.product_content_sell_price_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__prices ins .amount
    `, settings.product_content_sell_price_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__grap__sells span
    `, settings.shopengine_product_stock_text_clr, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__grap__sells > div
    `, settings.product_stock_text_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__grap__sells > div
    `, settings.product_stock_text_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__grap__sells > div
    `, settings.product_stock_text_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-deal-products-widget .deal-products__grap__sells > div
    `, settings.product_stock_text_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-countdown-clock .se-clock-item,
    .shopengine-deal-products-widget .deal-products__desc--name a,
    .shopengine-deal-products-widget .deal-products__prices .amount,
    .shopengine-deal-products-widget .deal-products__grap__sells,
    .shopengine-deal-products-widget .shopengine-sale-badge,
    .shopengine-deal-products-widget .shopengine-offer-badge
    `, settings.shopengine_product_stock_font_family, (val) => {
        return (
            `
            font-family: : ${val.family} !important;

            `
        )
    })










    return cssHelper.get()
}

export {Style}