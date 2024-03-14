const Style = ({ settings, cssHelper }) => {

    //this will return object values as a string separated by comma
    const getObjectValues = (obj) => {
        return [...Object.values(obj)].toString();
    }
    
    settings.shopengine_add_to_cart_data_ordering_list.desktop.map((element,idx) => {
        switch(element.id){
            case "quantity":
                cssHelper.add(`.shopengine-swatches .quantity-wrap`, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "add_to_cart":
                cssHelper.add(`.shopengine-swatches .single_add_to_cart_button`, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "quick_checkout":
                cssHelper.add(`.shopengine-swatches .shopengine-quick-checkout-button`, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "wishlist":
                cssHelper.add(`.shopengine-swatches .shopengine-wishlist.badge`, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "comparison":
                cssHelper.add(`.shopengine-swatches .shopengine-comparison.badge`, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "partial_payment":
                cssHelper.add(`.shopengine-swatches .partial_payment`, {}, () => (`
                    order: ${idx};
                `));
            break;
        }
    })

    cssHelper.add('a', {}, () => (`
        text-decoration: none;
    `));

    cssHelper.add('a', {}, () => (`
        text-decoration: none;
    `));

    cssHelper.add('.shopengine-swatches .stock', settings.shopengine_add_to_cart_show_stock, (val) => (`
    display: ${val ? 'block' : 'none'};
    `)).add('.shopengine-swatches .woocommerce-variation-description', settings.shopengine_add_to_cart_show_variation_description, (val) => (`
    display: ${val ? 'block' : 'none'};
    `));

    cssHelper.add('.shopengine-swatches .stock', settings.shopengine_add_to_cart_in_stock_color, (val) => (`
        color: ${val};
    `)).add('.shopengine-swatches .stock', settings.stock_status_font_family, (val) => (`
        font-family: ${val.family};
    `)).add('.shopengine-swatches .stock', settings.stock_status_font_size, (val) => (`
        font-size: ${val}px;
    `)).add('.shopengine-swatches .stock', settings.stock_status_font_weight, (val) => (`
        font-weight: ${val};
    `)).add('.shopengine-swatches .stock', settings.stock_status_text_transform, (val) => (`
        text-transform: ${val};
    `)).add('.shopengine-swatches .stock', settings.stock_status_line_height, (val) => (`
        line-height: ${val}px;
    `)).add('.shopengine-swatches .stock', settings.stock_status_title_wordspace, (val) => (`
        word-spacing: ${val}px;
    `)).add('.shopengine-swatches .stock', settings.shopengine_add_to_cart_stock_status_alignment, (val) => (`
        text-align: ${val};
    `)).add('.shopengine-swatches .stock.out-of-stock', settings.shopengine_add_to_cart_out_of_stock_color, (val) => (`
        color: ${val};
    `));

    cssHelper.add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_text_color_normal, (val) => (`
        color: ${val};
        cursor: pointer;
    `)).add('.shopengine-swatches .cart .button:hover', settings.shopengine_add_cart_button_text_color_hover, (val) => (`
        color: ${val} !important;
    `)).add('.shopengine-swatches .cart .button', settings.add_cart_button_font_family, (val) => (`
        font-family: ${val.family};
    `)).add('.shopengine-swatches .cart .button', settings.add_cart_button_font_size, (val) => (`
        font-size: ${val}px;
    `)).add('.shopengine-swatches .cart .button', settings.add_cart_button_font_weight, (val) => (`
        font-weight: ${val};
    `)).add('.shopengine-swatches .cart .button', settings.add_cart_button_text_transform, (val) => (`
        text-transform: ${val};
    `)).add('.shopengine-swatches .cart .button', settings.add_cart_button_line_height, (val) => (`
        line-height: ${val}px;
    `)).add('.shopengine-swatches .cart .button', settings.add_cart_button_wordspace, (val) => (`
        word-spacing: ${val}px;
    `)).add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_bg_color_normal, (val) => (`
        background-color: ${val};
    `)).add('.shopengine-swatches .cart .button:hover', settings.shopengine_add_cart_button_bg_color_hover, (val) => (`
        background-color: ${val} !important;
    `)).add('.shopengine-swatches .cart .button:hover', settings.shopengine_add_cart_button_border_color_hover, (val) => (`
        border-color: ${val};
    `)).add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_border_type, (val) => (`
        border-style: ${val};
    `)).add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_border_width, (val) => (`
        border-width: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_border_color, (val) => (`
        border-color: ${val};
    `)).add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_border_radius, (val) => (`
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `)).add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_padding, (val) => (`
        padding: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-swatches .cart .button', settings.shopengine_add_cart_button_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `));

    cssHelper.add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_text_color_normal, (val) => (`
        color: ${val};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button:hover', settings.shopengine_quick_checkout_button_text_color_hover, (val) => (`
        color: ${val} !important;
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.quick_checkout_button_font_family, (val) => (`
        font-family: ${val.family};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.quick_checkout_button_font_size, (val) => (`
        font-size: ${val}px;
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.quick_checkout_button_font_weight, (val) => (`
        font-weight: ${val};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.quick_checkout_button_transform, (val) => (`
        text-transform: ${val};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.quick_checkout_button_line_height, (val) => (`
        line-height: ${val}px;
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.quick_checkout_button_letter_spacing, (val) => (`
        letter-spacing: ${val}px;
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.quick_checkout_button_wordspace, (val) => (`
        word-spacing: ${val}px;
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_bg_color_normal, (val) => (`
        background-color: ${val};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button:hover', settings.shopengine_quick_checkout_button_bg_color_hover, (val) => (`
        background-color: ${val} !important;
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button:hover', settings.shopengine_quick_checkout_button_border_color_hover, (val) => (`
        border-color: ${val};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_border_type, (val) => (`
        border-style: ${val};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_border_width, (val) => (`
        border-width: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_border_color, (val) => (`
        border-color: ${val};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_border_radius, (val) => (`
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_padding, (val) => (`
        padding: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-swatches .shopengine-quick-checkout-button', settings.shopengine_quick_checkout_button_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `));

    cssHelper.add('.shopengine-swatches .quantity .qty', settings.shopengine_add_cart_quantity_color, (val) => (`
        color: ${val};
    `)).add('.shopengine-swatches .quantity .qty:hover', settings.shopengine_add_cart_quantity_text_color_hover, (val) => (`
        color: ${val} !important;
    `)).add('.shopengine-swatches .quantity .qty', settings.add_cart_quantity_font_family, (val) => (`
        font-family: ${val.family};
    `)).add('.shopengine-swatches .quantity .qty', settings.add_cart_quantity_font_size, (val) => (`
        font-size: ${val}px;
    `)).add('.shopengine-swatches .quantity .qty', settings.add_cart_quantity_font_weight, (val) => (`
        font-weight: ${val};
    `)).add('.shopengine-swatches .quantity .qty', settings.add_cart_quantity_font_style, (val) => (`
        font-style: ${val};
    `)).add('.shopengine-swatches .quantity .qty', settings.add_cart_quantity_line_height, (val) => (`
        line-height: ${val}px;
    `)).add('.shopengine-swatches .quantity .qty', settings.add_cart_quantity_wordspace, (val) => (`
        word-spacing: ${val}px;
    `)).add('.shopengine-swatches .quantity .qty', settings.shopengine_add_cart_quantity_bg_color, (val) => (`
        background-color: ${val};
    `)).add('.shopengine-swatches .minus, .shopengine-swatches .plus', settings.shopengine_quantity_btn_icon_size, (val) => (`
        font-size: ${val}px;
        font-weight: 900;
        width: ${val}px;
    `)).add('.shopengine-swatches .plus, .shopengine-swatches .plus svg, .shopengine-swatches .minus, .shopengine-swatches .minus svg, .shopengine-swatches .plus path, .shopengine-swatches .minus path', settings.shopengine_quantity_btn_tabs_normal_clr, (val) => (`
        color: ${val};
        fill: ${val};
    `)).add('.shopengine-swatches .plus, .shopengine-swatches .minus', settings.shopengine_quantity_btn_tabs_normal_bg_clr, (val) => (`
        background-color: ${val};
    `)).add('.shopengine-swatches .plus:hover, .shopengine-swatches .plus:hover svg, .shopengine-swatches .minus:hover, .shopengine-swatches .minus:hover svg, .shopengine-swatches .plus:hover path, .shopengine-swatches .minus:hover path', settings.shopengine_quantity_btn_tabs_Hover_clr, (val) => (`
        color: ${val};
        fill: ${val};
    `)).add('.shopengine-swatches .plus:hover, .shopengine-swatches .minus:hover', settings.shopengine_quantity_btn_tabs_hover_bg_clr, (val) => (`
        background-color: ${val};
        border-color: ${val};
    `)).add('.shopengine-swatches .plus, .shopengine-swatches .minus', settings.shopengine_quantity_button_padding, (val) => (`
        padding: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-swatches .quantity .qty', settings.shopengine_add_cart_quantity_padding, (val) => (`
        padding: ${getObjectValues(val).split(',').join(' ')};
        box-sizing: border-box;
    `)).add('.shopengine-swatches .quantity .qty, .shopengine-swatches .quantity-wrap button', settings.shopengine_add_cart_quantity_border_type, (val) => (`
        border-style: ${val};
    `)).add('.shopengine-swatches .quantity-wrap.default .quantity .qty, .shopengine-swatches .quantity-wrap.both .quantity .qty, .shopengine-swatches .quantity-wrap.both .minus, .shopengine-swatches .quantity-wrap.both .plus, .shopengine-swatches .quantity-wrap.before .quantity .qty, .shopengine-swatches .quantity-wrap.before .plus, .shopengine-swatches .quantity-wrap.before .minus, .shopengine-swatches .quantity-wrap.after .quantity .qty, .shopengine-swatches .quantity-wrap.after .plus, .shopengine-swatches .quantity-wrap.after .minus', settings.shopengine_add_cart_quantity_border_width, (val) => (`
        border-width: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-swatches .quantity .qty, .shopengine-swatches .quantity-wrap button', settings.shopengine_add_cart_quantity_border_color, (val) => (`
        border-color: ${val};
    `)).add('.shopengine-swatches .quantity-wrap.default .quantity .qty, .shopengine-swatches .quantity-wrap.both .minus, .shopengine-swatches .quantity-wrap.both .plus, .shopengine-swatches .quantity-wrap.before .quantity .qty, .shopengine-swatches .quantity-wrap.before .plus, .shopengine-swatches .quantity-wrap.before .minus, .shopengine-swatches .quantity-wrap.after .quantity .qty, .shopengine-swatches .quantity-wrap.after .plus, .shopengine-swatches .quantity-wrap.after .minus', settings.shopengine_add_cart_quantity_border_radius, (val) => (`
        border-radius: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-swatches .quantity-wrap', settings.shopengine_add_cart_quantity_wrap_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `));



    //break
    cssHelper.add(`.shopengine-swatches table.variations,
    .shopengine-swatches .single_variation_wrap
    `, settings.shopengine_add_to_cart_variation_swatches_alignment, (val) => (`
        text-align: ${val};
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_font_family, (val) => (`
        font-family: ${val.family};
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_font_size, (val) => (`
        font-size: ${val}px;
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_font_style, (val) => (`
        font-style: ${val};
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_transform, (val) => (`
        text-transform: ${val};
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_line_height, (val) => (`
        line-height: ${val}px;
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_letter_spacing, (val) => (`
        letter-spacing: ${val}px;
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_wordspace, (val) => (`
        word-spacing: ${val}px;
    `))
        .add(`
    .shopengine-swatches .variations label,
    .shopengine-swatches .variations select
    `, settings.variation_label_font_weight, (val) => (`
        font-weight: ${val};
    `))
        .add(`
    .shopengine-swatches .variations td label,
    .shopengine-swatches .variations select
    `, settings.shopengine_add_to_cart_variation_label_color, (val) => (`
        color: ${val};
    `))
        .add(`
    .shopengine-swatches .variations tr
    `, settings.shopengine_add_to_cart_variation_label_display_style, (val) => (`
    flex-direction: ${val};
    `))

        .add(`
    .shopengine-swatches .variations td.value
    `, {}, (val) => (`
        width: 100%;
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.variation_description_font_size, (val) => (`
    font-size: ${val}px;
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.variation_label_font_style, (val) => (`
        font-style: ${val};
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.variation_description_transform, (val) => (`
        text-transform: ${val};
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.variation_description_line_height, (val) => (`
        line-height: ${val}px;
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.variation_description_letter_spacing, (val) => (`
        letter-spacing: ${val}px;
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.variation_description_wordspace, (val) => (`
        word-spacing: ${val}px;
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.variation_description_font_weight, (val) => (`
        font-weight: ${val};
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.shopengine_add_to_cart_variation_description_color, (val) => (`
        color: ${val};
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-description
    `, settings.shopengine_add_to_cart_variation_description_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_font_family, (val) => (`
    font-family: ${val.family};
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_font_size, (val) => (`
    font-size: ${val}px;
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_font_style, (val) => (`
        font-style: ${val};
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_transform, (val) => (`
        text-transform: ${val};
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_line_height, (val) => (`
        line-height: ${val}px;
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_letter_spacing, (val) => (`
        letter-spacing: ${val}px;
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_wordspace, (val) => (`
        word-spacing: ${val}px;
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.variation_price_font_weight, (val) => (`
        font-weight: ${val};
    `))
        .add(`
    .shopengine-swatches .price,
    .shopengine-swatches .price del, 
    .shopengine-swatches .price ins 
    `, settings.shopengine_add_to_cart_variation_price_color, (val) => (`
        color: ${val};
    `))
        .add(`
    .shopengine-swatches .price ins .amount
    `, settings.shopengine_add_to_cart_variation_sale_price_color, (val) => (`
    background: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-badge
    `, settings.shopengine_add_to_cart_variation_price_discount_badge_color, (val) => (`
    color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-badge
    `, settings.shopengine_add_to_cart_variation_price_discount_badge_bg_color, (val) => (`
    backgroud: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-badge
    `, settings.shopengine_add_to_cart_variation_price_discount_badge_font_size, (val) => (`
    font-size: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine-badge
    `, settings.shopengine_add_to_cart_variation_price_discount_badge_line_height, (val) => (`
        line-height: ${val}px;
    `))
        .add(`
    .shopengine-swatches .woocommerce-variation-price
    `, settings.shopengine_add_to_cart_variation_price_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `))
        .add(`
    .shopengine-swatches .variations tr
    `, settings.shopengine_add_to_cart_variation_item_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `))
        .add(`
    .shopengine-swatches .variations
    `, settings.shopengine_add_to_cart_variation_wrap_margin, (val) => (`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `))
        .add(`
    .shopengine-swatches .variations select
    `, settings.shopengine_add_to_cart_variation_dropdown_color, (val) => (`
    color: ${val};
    `))
        .add(`
    .shopengine-swatches .variations select
    `, settings.shopengine_add_to_cart_variation_dropdown_border_color, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .variations select
    `, settings.shopengine_add_to_cart_variation_dropdown_border_type, (val) => (`
    border-style: ${val};
    `))

        .add(`
    .shopengine-swatches .variations select
    `, settings.shopengine_add_to_cart_variation_dropdown_border_width, (val) => (`
    border-width: ${getObjectValues(val).split(',').join(' ')}
    `))

        .add(`
    .shopengine-swatches .variations select
    `, settings.shopengine_add_to_cart_variation_dropdown_border_radius, (val) => (`
    border-radius: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_color
    `, settings.shopengine_add_to_cart_variation_swatch_color_border_radius, (val) => (`
    border-radius: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_color
    `, settings.shopengine_add_to_cart_variation_swatch_color_label_border_color, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_color
    `, settings.shopengine_add_to_cart_variation_swatch_color_label_border_type, (val) => (`
    border-style: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_color
    `, settings.shopengine_add_to_cart_variation_swatch_color_label_border_width, (val) => (`
    border-width: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_color
    `, settings.shopengine_add_to_cart_variation_swatch_color_width, (val) => (`
    width: ${val}px;
    `))

        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_color
    `, settings.shopengine_add_to_cart_variation_swatch_color_height, (val) => (`
    height: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_color.selected
    `, settings.shopengine_add_to_cart_variation_swatch_color_selected_label_border, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_image
    `, settings.shopengine_add_to_cart_variation_swatch_image_width, (val) => (`
    width: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_image
    `, settings.shopengine_add_to_cart_variation_swatch_image_height, (val) => (`
    height: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_image
    `, settings.shopengine_add_to_cart_variation_swatch_image_border_radius, (val) => (`
    border-radius: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_image
    `, settings.shopengine_add_to_cart_variation_swatch_image_label_border_type, (val) => (`
    border-style: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_image
    `, settings.shopengine_add_to_cart_variation_swatch_image_label_border_width, (val) => (`
    border-width: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_image
    `, settings.shopengine_add_to_cart_variation_swatch_image_label_border_color, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_image.selected
    `, settings.shopengine_add_to_cart_variation_swatch_image_selected_label_border, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_label_width, (val) => (`
    width: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch.swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_label_height, (val) => (`
    height: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_label_border_radius, (val) => (`
    border-radius: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_image_label_border_type, (val) => (`
    border-style: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_label_label_border_width, (val) => (`
    border-width: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_label_label_border_color, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_label.selected
    `, settings.shopengine_add_to_cart_variation_swatch_label_selected_label_border, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_label_text_color, (val) => (`
    color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine_swatches .swatch_label
    `, settings.shopengine_add_to_cart_variation_swatch_label_background_color, (val) => (`
    background: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_size, (val) => (`
    font-size: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_button_color, (val) => (`
    color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_button_bg_color, (val) => (`
    background: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge.active,
    .shopengine-swatches .shopengine-wishlist.badge:hover
    `, settings.shopengine_add_cart_wishlist_button_hover_color, (val) => (`
    color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge.active,
    .shopengine-swatches .shopengine-wishlist.badge:hover
    `, settings.shopengine_add_cart_wishlist_button_bg_hover_color, (val) => (`
    background: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge.active,
    .shopengine-swatches .shopengine-wishlist.badge:hover
    `, settings.shopengine_add_cart_wishlist_button_border_color_hover, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_border_radius, (val) => (`
    border-radius: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_border_type, (val) => (`
    border-style: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_border_width, (val) => (`
    border-width: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_border_color, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_padding, (val) => (`
    padding: ${getObjectValues(val).split(',').join(' ')};
    `))
        .add(`
    .shopengine-swatches .shopengine-wishlist.badge
    `, settings.shopengine_add_cart_wishlist_margin, (val) => (`
    margin: ${getObjectValues(val).split(',').join(' ')};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_size, (val) => (`
    font-size: ${val}px;
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_button_color, (val) => (`
    color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_button_bg_color, (val) => (`
    background: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge.active,
    .shopengine-swatches .shopengine-comparison.badge:hover
    `, settings.shopengine_add_cart_compare_button_hover_color, (val) => (`
    color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge.active,
    .shopengine-swatches .shopengine-comparison.badge:hover
    `, settings.shopengine_add_cart_compare_button_bg_hover_color, (val) => (`
    background: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge.active,
    .shopengine-swatches .shopengine-comparison.badge:hover
    `, settings.shopengine_add_cart_compare_button_border_color_hover, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_border_radius, (val) => (`
    border-radius: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_border_type, (val) => (`
    border-style: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_border_width, (val) => (`
    border-width: ${getObjectValues(val).split(',').join(' ')}
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_border_color, (val) => (`
    border-color: ${val};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_padding, (val) => (`
    padding: ${getObjectValues(val).split(',').join(' ')};
    `))
        .add(`
    .shopengine-swatches .shopengine-comparison.badge
    `, settings.shopengine_add_cart_compare_margin, (val) => (`
    margin: ${getObjectValues(val).split(',').join(' ')};
    `));

    // .add(`
    // .shopengine-swatches .variations td.label,
    // `, settings.shopengine_add_to_cart_variation_inline_label_width, (val) => (`
    //     width: ${val}px;
    // `))

    return cssHelper.get()
}


export { Style }