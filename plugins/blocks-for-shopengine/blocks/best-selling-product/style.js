
const Style = ({ settings, breakpoints, cssHelper }) => {

    if (settings.shopengine_show_regular_price.desktop === true) {
    cssHelper.add(`
    .shopengine-widget .shopengine-best-selling-product .price del
    `, {}, (val) => {
            return (
            `
            display : block;
            `
            )
        })
    }

    cssHelper.add(`
    .view-grid
    `, settings.shopengine_grid_layout_column, (val) => {
        return (
            `
            display : grid;
            grid-template-columns: repeat(${val}, 1fr);

            `
        )
    })
    cssHelper.add(`
    .view-grid
    `, settings.shopengine_grid_layout_gap, (val) => {
        return (
            `
            grid-gap: ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-single-product-item
    `, settings.shopengine_product_border_type, (val) => {
        return (
            `
            border-style : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-single-product-item
    `, settings.shopengine_product_border_width, (val) => {
        return (
            `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })

    cssHelper.add(`
    .shopengine-single-product-item
    `, settings.shopengine_product_border_color, (val) => {
        return (
            `
            border-color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-single-product-item
    `, settings.shopengine_product_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .product-thumb img
    `, settings.shopengine_image_height, (val) => {
        return (
            `
            height : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-thumb img
    `, settings.shopengine_image_fit, (val) => {
        return (
            `
            object-fit : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-thumb img
    `, settings.shopengine_image_position, (val) => {
        return (
            `
            object-position : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-category a
    `, settings.shopengine_cats_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-category a
    `, settings.shopengine_cats_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })


    cssHelper.add(`
    .product-category a
    `, settings.shopengine_cats_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-category a
    `, settings.shopengine_cats_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-category a
    `, settings.shopengine_cats_line_height, (val) => {
        return (
            `
            line-height : ${val};

            `
        )
    })


    cssHelper.add(`
    .product-category a
    `, settings.shopengine_cats_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-category
    `, settings.shopengine_cats_spacing, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .product-title a
    `, settings.shopengine_title_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })


    cssHelper.add(`
    .product-title a:hover
    `, settings.shopengine_title_hover_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })


    cssHelper.add(`
    .product-title a
    `, settings.shopengine_title_color_font_size, (val) => {
        return (
            `
            font-size: ${val};
            
            `
        )
    })

    cssHelper.add(`
    .product-title a
    `, settings.shopengine_title_color_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-title a
    `, settings.shopengine_title_color_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-title a
    `, settings.shopengine_title_color_line_height, (val) => {
        return (
            `
            line-height : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-title a
    `, settings.shopengine_title_color_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-title a
    `, settings.shopengine_title_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })

    cssHelper.add(`
    .product-rating .star-rating
    `, settings.shopengine_product_start_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-rating .star-rating,
    .product-rating .rating-count
    `, settings.shopengine_product_start_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })


    cssHelper.add(`
    .product-rating .star-rating
    `, settings.shopengine_product_rating_gap, (val) => {
        return (
            `
            letter-spacing : ${val};
            width: calc(5.4em + (4 * ${val}));

            `
        )
    })

    cssHelper.add(`
    .product-rating
    `, settings.shopengine_product_star_padding, (val) => {
        return (
            `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })

    cssHelper.add(`
    .product-price .price
    `, settings.shopengine_sell_price_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-price .price .amount
    `, settings.shopengine_product_price_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-price .price .amount
    `, settings.shopengine_product_price_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-price .price .amount
    `, settings.shopengine_product_price_line_height, (val) => {
        return (
            `
            line-height : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-price .price .amount
    `, settings.shopengine_product_price_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-price .price > del bdi
    `, settings.shopengine_price_reg_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .product-price .price
    `, settings.shopengine_price_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left} !important;

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_margin, (val) => {
        return (
            `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left} !important;

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_radius, (val) => {
        return (
            `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left} !important;

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_radius, (val) => {
        return (
            `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left} !important;

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    let shadowColor = settings.shopengine_archvie_btn_box_shadow_color.desktop.rgb;
    let horizontal = settings.shopengine_archvie_btn_box_shadow_horizontal.desktop || "0px";
    let vertical = settings.shopengine_archvie_btn_box_shadow_vertical.desktop || "0px";
    let blur = settings.shopengine_archvie_btn_box_shadow_blur.desktop || "";
    let spread = settings.shopengine_archvie_btn_box_shadow_spread.desktop || "";
    let position = settings.shopengine_archvie_btn_box_shadow_position.desktop || "";

    cssHelper.add(`
    .add-to-cart-bt .button[data-quantity]
    `, {}, (val) => {
        return (
            `
            box-shadow : ${horizontal} ${vertical} ${blur} ${spread} rgba(${shadowColor.r || 0}, ${shadowColor.g || 0}, ${shadowColor.b || 0}, ${shadowColor.a || 1}) ${position || ""};

            `
        )
    })


    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_normal_clr, (val) => {
        return (
            `
            text-align : left;
            color : ${val} !important;

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger)
    `, settings.shopengine_archvie_btn_normal_bg, (val) => {
        return (
            `
            background-color : ${val} !important;

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger):hover
    `, settings.shopengine_archvie_btn_hover_clr, (val) => {
        return (
            `
            color : ${val} !important;

            `
        )
    })

    cssHelper.add(`
    .add-to-cart-bt a.button:not(.shopengine-quickview-trigger):hover
    `, settings.shopengine_archvie_btn_hover_bg, (val) => {
        return (
            `
            background-color : ${val} !important;

            `
        )
    })





    return cssHelper.get()
}

export { Style }