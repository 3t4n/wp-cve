
const Style = ({ settings, breakpoints, cssHelper }) => {
    const {
        shopengine_show_sale_flash,
        shopengine_show_off_tag,
        shopengine_is_hover_details,
        shopengine_show_regular_price,
        shopengine_archive_product_show_rating,
        shopengine_background,
        shopengine__border,
        shopengine__border_width,
        shopengine__border_color,
        shopengine_margin,
        shopengine_button_shadow_color,
        shopengine_shadow_horizontal,
        shopengine_shadow_vertical,
        shopengine_shadow_blur,
        shopengine_shadow_spread,
        shopengine_shadow_position,
        shopengine_archive_products_container_row_spacing,
        shopengine_padding,
        shopengine_image_bg_color,
        shopengine_image_height_switch,
        shopengine_image_height,
        shopengine_image_padding,
        shopengine_cats_color,
        shopengine_font_size,
        shopengine_font_weight,
        shopengine_text_transform,
        shopengine_line_height,
        shopengine_word_spacing,
        shopengine_cats_spacing,
        shopengine_title_color,
        shopengine_title_hover_color,
        shopengine_title_color_typography_font_size,
        shopengine_title_color_typography_font_weight,
        shopengine_title_color_typography_transform,
        shopengine_title_color_typography_line_height,
        shopengine_title_color_typography_word_spacing,
        shopengine_title_padding,
        shopengine_price_color,
        shopengine_price_color_typography_font_size,
        shopengine_price_color_typography_font_weight,
        shopengine_price_color_typography_line_height,
        shopengine_price_color_typography_word_spacing,
        shopengine_price_reg_size,
        shopengine_price_padding,
        shopengine_archive_description_color,
        shopengine_description_color_typography_font_size,
        shopengine_description_color_typography_font_weight,
        shopengine_description_color_typography_transform,
        shopengine_description_color_typography_line_height,
        shopengine_description_color_typography_word_spacing,
        shopengine_archive_description_border,
        shopengine_archive_description_border_width,
        shopengine_archive_description_border_color,
        shopengine_archive_description_padding,
        shopengine_archive_footer_padding,
        product_price_discount_badge_color,
        product_price_discount_badge_bg_color,
        product_price_discount_badge_padding,
        product_price_discount_badge_margin,
        shopengine_archvie_btn_padding,
        shopengine_archvie_btn_margin,
        shopengine_archvie_btn_radius,
        shopengine_archvie_btn_typography_font_size,
        shopengine_archvie_btn_typography_font_weight,
        shopengine_archvie_btn_typography_text_transform,
        shopengine_archvie_btn_typography_word_spacing,
        shopengine_archvie_btn_box_shadow_color,
        shopengine_archvie_btn_box_shadow_horizontal,
        shopengine_archvie_btn_box_shadow_vertical,
        shopengine_archvie_btn_box_shadow_blur,
        shopengine_archvie_btn_box_shadow_spread,
        shopengine_archvie_btn_box_shadow_position,
        shopengine_archvie_btn_normal_clr,
        shopengine_archvie_btn_normal_bg,
        shopengine_archvie_btn_hover_clr,
        shopengine_archvie_btn_hover_bg,
        shopengine_product_star_color,
        shopengine_product_start_size,
        shopengine_product_rating_gap,
        shopengine_product_star_margin,
        shopengine_sale_flash_color,
        shopengine_sale_flash_bg_color,
        shopengine_sale_flash_typography_font_size,
        shopengine_sale_flash_typography_font_weight,
        shopengine_sale_flash_typography_text_transform,
        shopengine_sale_flash_typography_word_spacing,
        shopengine_use_fixed_size,
        shopengine_sale_flash_radius,
        shopengine_sale_flash_paddng,
        shopengine_sale_flash_sizee,
        shopengine_sale_flash_pos,
        shopengine_sale_flash_position_horizontial,
        shopengine_sale_flash_position_vertical,
        shopengine_button_group_btn_bg_clr,
        shopengine_button_group_btn_clr,
        shopengine_button_group_btn_hover_active_clr,
        shopengine_button_group_btn_icon_size,
        shopengine_button_group_btn_padding,
        shopengine_pagi_align,
        shopengine_pagi_font_font_size,
        shopengine_pagi_font_font_weight,
        shopengine_pagi_font_word_spacing,
        shopengine_pagi_top_space,
        shopengine_pagi_n_color,
        shopengine_pagi_n_bgcolor,
        shopengine_pagi_n_bd,
        shopengine_pagi_n_bd_width,
        shopengine_pagi_n_bd_color,
        shopengine_pagi_h_color,
        shopengine_pagi_h_bgcolor,
        shopengine_pagi_h_bdc,
        shopengine_pagi_radius,
        shopengine_pagi_mg,
        shopengine_pagi_pd,
        shopengine_tooltip_horizontal_position,
        shopengine_tooltip_vertical_position,
        shopengine_tooltip_text_color,
        shopengine_tooltip_background_color,
        shopengine_tooltip_text_font_size,
        shopengine_tooltip_text_font_weight,
        shopengine_tooltip_text_transform,
        shopengine_tooltip_text_line_height,
        shopengine_tooltip_text_word_spacing,
        archive_product_layout_column
    } = settings;

    cssHelper.add(`
        .shopengine-archive-products a
        `, {}, (val) => {
            return `
            text-decoration:none;
            `
        });
    
    cssHelper.add(`
        .shopengine-archive-products .product > .added_to_cart
        `, {}, (val) => {
            return `
            width: 135px;
            position: absolute;
            line-height: 40px;
            height: 40px;
            display: inline-block !important;
            font-size: 18px;
            padding: 0 !important;
            border-radius: 26px;
            color: #101010;
            background: #fff;
            text-align: center !important;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            `
        });

    if (shopengine_show_sale_flash.desktop === true) {
        cssHelper.add('.shopengine-archive-products .product .onsale', shopengine_show_sale_flash, (val) => {
            return `
            display: block;
            `
        });
    }

    if (shopengine_archive_product_show_rating.desktop == true) {
        cssHelper.add(`
        .shopengine-archive-products .shopengine-product-rating-review-count,
        .shopengine-archive-products .star-rating
        `, shopengine_archive_product_show_rating, (val) => {
            return `
            display: inline-flex;
            `
        });
    }

    if (shopengine_show_regular_price.desktop == true) {
        cssHelper.add('.shopengine-archive-products .product .price del', shopengine_show_regular_price, (val) => {
            return `
            display: block;
            `
        });
    }

    if (shopengine_show_off_tag.desktop == true) {
        cssHelper.add('.shopengine-archive-products .price .shopengine-discount-badge', shopengine_show_off_tag, (val) => {
            return `
            display: block;
            `
        });
    }
    if (settings.shopengine_group_btns.desktop == true || settings.shopengine_is_hover_details.desktop == true) {
      cssHelper.add('.shopengine-archive-mode-grid li > a.shopengine_add_to_list_action, .shopengine-archive-mode-grid li > a.product_type_grouped, .shopengine-archive-mode-grid li > a.shopengine-quickview-trigger, .shopengine-archive-mode-grid li > a.add_to_cart_button, .shopengine-archive-mode-grid li > a.shopengine_comparison_add_to_list_action,.shopengine-archive-mode-grid li > a.product_type_simple', shopengine_is_hover_details, (val) => {
         return(`
            display: none;
         `)
     });
    }

    if (settings.shopengine_add_to_cart_data_ordering_enable.desktop == true) {
        cssHelper.add(`
        .shopengine-archive-products.shopengine-hover-disable .products .product,
        .shopengine-archive-products .shopengine-product-description-btn-group
        `, settings.shopengine_add_to_cart_data_ordering_enable, (val) => {
            return(`
                display: flex; 
                flex-direction: row; 
                flex-wrap: wrap; 
                align-items: center !important;
            `)
        });
    }

    if (settings.shopengine_add_to_cart_data_ordering_enable.desktop == true) {
        cssHelper.add(`
        .shopengine-archive-products.shopengine-hover-disable .products .product a.woocommerce-LoopProduct-link`, settings.shopengine_add_to_cart_data_ordering_enable, (val) => {
            return `
            text-decoration : none;
            width: 100%;
            order: -99;
            `
        });
    }

    settings.shopengine_custom_ordering_list.desktop.map((element,idx) => {
        switch(element.id){
            case "quickview":
                cssHelper.add(`.shopengine-archive-products .shopengine-quickview-trigger`, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "wishlist":
                cssHelper.add(`.shopengine-archive-products .shopengine-wishlist`, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "add_to_cart":
                cssHelper.add(`
                .shopengine-archive-products a.add_to_cart_button,
                .shopengine-archive-products a.product_type_variable,
                .shopengine-archive-products a.product_type_grouped,
                .shopengine-archive-products a.product_type_external,
                .shopengine-archive-products a.product_type_simple
                 `, {}, () => (`
                    order: ${idx};
                `));
            break;
            case "comparison":
                cssHelper.add(`.shopengine-archive-products .shopengine-comparison`, {}, () => (`
                    order: ${idx};
                `));
            break;
        }
    })

    if (settings.shopengine_container_text_align.desktop === 'left') {
        cssHelper.add(`
        .shopengine-archive-products:not(.shopengine-archive-products--view-list) .product > a,
        .shopengine-archive-products:not(.shopengine-archive-products--view-list) .shopengine-product-description-btn-group,
        .shopengine-archive-products.shopengine-hover-disable .products .product
        `, settings.shopengine_container_text_align, (val) => {
            return `
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
            text-align: ${val};
            `
        });
    }

    if (settings.shopengine_container_text_align.desktop === 'center') {
        cssHelper.add(`
        .shopengine-archive-products:not(.shopengine-archive-products--view-list) .product > a,
        .shopengine-archive-products:not(.shopengine-archive-products--view-list) .shopengine-product-description-btn-group,
        .shopengine-archive-products.shopengine-hover-disable .products .product
        `, settings.shopengine_container_text_align, (val) => {
            return `
            -webkit-box-pack: center; 
            -ms-flex-pack: center; 
            justify-content: center;
            text-align: ${val};
            `
        });
    }

    if (settings.shopengine_container_text_align.desktop === 'right') {
        cssHelper.add(`
        .shopengine-archive-products:not(.shopengine-archive-products--view-list) .product > a,
        .shopengine-archive-products:not(.shopengine-archive-products--view-list) .shopengine-product-description-btn-group,
        .shopengine-archive-products.shopengine-hover-disable .products .product
        `, settings.shopengine_container_text_align, (val) => {
            return `
            -webkit-box-pack: end; 
            -ms-flex-pack: end; 
            justify-content: flex-end;
            text-align: ${val};
            `
        });
    }

    cssHelper.add(`
    .shopengine-archive-products .archive-product-container,
    .shopengine-archive-products .archive-product-container .shopengine-product-description-footer
    `, shopengine_background, (val) => {
        return `
            background-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .archive-product-container', shopengine__border, (val) => {
        return `
            border-style: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .archive-product-container', shopengine__border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .archive-product-container', shopengine__border_color, (val) => {
        return `
            border-color : ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products.shopengine-grid ul.products', shopengine_margin, (val) => {
        return `
            grid-gap: ${val}px;
        `
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-grid ul.products
    `, archive_product_layout_column, (val) => {
        return `
        grid-template-columns: repeat(${val}, minmax(10px, 1fr));
        `
    });


    let horizontal = shopengine_shadow_horizontal.desktop + 'px'
    let vertical = shopengine_shadow_vertical.desktop + 'px'
    let blur = shopengine_shadow_blur.desktop + 'px'
    let spread = shopengine_shadow_spread.desktop + 'px'
    let color = shopengine_button_shadow_color.desktop.rgb
    let position = shopengine_shadow_position.desktop

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .archive-product-container:hover', {}, (val) => {
        return `
                box-shadow: ${horizontal} ${vertical} ${blur} ${spread} rgba(${color.r},${color.g}, ${color.b}, ${color.a}) ${position};
            `
    });

    cssHelper.add('.shopengine-widget .shopengine-archive-products li, .shopengine-widget .shopengine-archive-products ol, .shopengine-widget .shopengine-archive-products ul', {}, (val) => {
      return `
         position: relative;
          `
   });

    cssHelper.add('.shopengine-archive-products .products .archive-product-container', shopengine_archive_products_container_row_spacing, (val) => {
        return `
            margin-bottom: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .archive-product-container', shopengine_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .attachment-woocommerce_thumbnail', shopengine_image_bg_color, (val) => {
        return `
            text-decoration : none !important;
            order : -1 !important ;
            background-color: ${val};
        `
    });

    if (shopengine_image_height_switch.desktop == true) {
        cssHelper.add('.shopengine-archive-products .product .attachment-woocommerce_thumbnail', shopengine_image_height, (val) => {
            return`
                height: ${val}px;
            `
        });
    }

    cssHelper.add('.shopengine-archive-products .product .attachment-woocommerce_thumbnail', shopengine_image_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product-categories', shopengine_cats_color, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product-categories > li', shopengine_font_size, (val) => {
        return `
            text-decoration : none;
            font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product-categories > li', shopengine_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product-categories > li', shopengine_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product-categories > li', shopengine_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product-categories > li', shopengine_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product-categories', shopengine_cats_spacing, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .product .woocommerce-loop-product__title', shopengine_title_color, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .product a:hover .woocommerce-loop-product__title', shopengine_title_hover_color, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) ul.products li.product .woocommerce-loop-product__title', shopengine_title_color_typography_font_size, (val) => {
        return `
            text-decoration : none;
            font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) ul.products li.product .woocommerce-loop-product__title', shopengine_title_color_typography_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) ul.products li.product .woocommerce-loop-product__title', shopengine_title_color_typography_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) ul.products li.product .woocommerce-loop-product__title', shopengine_title_color_typography_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) ul.products li.product .woocommerce-loop-product__title', shopengine_title_color_typography_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products:not(.shopengine-archive-products--view-list) .product .woocommerce-loop-product__title', shopengine_title_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .price', shopengine_price_color, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .price .amount', shopengine_price_color_typography_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product .price .amount', shopengine_price_color_typography_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .price .amount', shopengine_price_color_typography_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    });
    cssHelper.add('.shopengine-archive-products .product .price .amount', shopengine_price_color_typography_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .price > del bdi', shopengine_price_reg_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product .price', shopengine_price_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_archive_description_color, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_description_color_typography_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_description_color_typography_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_description_color_typography_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });
    
    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_description_color_typography_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_archive_description_border, (val) => {
        return `
            border-style: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_archive_description_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_archive_description_border_color, (val) => {
        return `
            border-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-excerpt', shopengine_archive_description_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product .shopengine-product-description-footer', shopengine_archive_footer_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-discount-badge', product_price_discount_badge_color, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-discount-badge', product_price_discount_badge_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    });

    cssHelper.add('.shopengine-discount-badge', product_price_discount_badge_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-discount-badge', product_price_discount_badge_margin, (val) => {
        return `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_radius, (val) => {
        return `
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_typography_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_typography_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_typography_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_typography_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    });

    let btn_shadow_horizontal = shopengine_archvie_btn_box_shadow_horizontal.desktop + 'px'
    let btn_shadow_vertical = shopengine_archvie_btn_box_shadow_vertical.desktop + 'px'
    let btn_shadow_blur = shopengine_archvie_btn_box_shadow_blur.desktop + 'px'
    let btn_shadow_spread = shopengine_archvie_btn_box_shadow_spread.desktop + 'px'
    let btn_shadow_color = shopengine_archvie_btn_box_shadow_color.desktop.rgb
    let btn_shadow_position = shopengine_archvie_btn_box_shadow_position.desktop

    cssHelper.add('.shopengine-archive-products .product .button[data-quantity]', shopengine_archvie_btn_typography_word_spacing, (val) => {
        return `
            box-shadow: ${btn_shadow_horizontal} ${btn_shadow_vertical} ${btn_shadow_blur} ${btn_shadow_spread} rgba(${btn_shadow_color.r},${btn_shadow_color.g}, ${btn_shadow_color.b}, ${btn_shadow_color.a}) ${btn_shadow_position};
        `
    });
    
    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_normal_clr, (val) => {
        return `
            color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger)', shopengine_archvie_btn_normal_bg, (val) => {
        return `
            background-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger):hover', shopengine_archvie_btn_hover_clr, (val) => {
        return `
            color: ${val} !important;
        `
    });

    if(!settings.shopengine_group_btns.desktop){
        cssHelper.add('.shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger):hover', shopengine_archvie_btn_hover_bg, (val) => {
            return `
                background-color: ${val} !important;
            `
        });
    }

    cssHelper.add('.shopengine-archive-products .product .star-rating', shopengine_product_star_color, (val) => {
        return `
        color: ${val};
        `
    });

    cssHelper.add(`
    .shopengine-archive-products .product .star-rating,
    .shopengine-product-rating-review-count
    `, shopengine_product_start_size, (val) => {
        return `
        font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .product .star-rating', shopengine_product_rating_gap, (val) => {
        return `
        letter-spacing: ${val}px; 
        width: calc(5.71em + (4 * ${val}px));
        `
    });

    cssHelper.add('.shopengine-archive-products .product .star-rating', shopengine_product_star_margin, (val) => {
        return `
        margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_color, (val) => {
        return `
        color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_bg_color, (val) => {
        return `
        background-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_typography_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_typography_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_typography_text_transform, (val) => {
        return `
        text-transform: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_typography_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_radius, (val) => {
        return `
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_paddng, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    if(shopengine_use_fixed_size.desktop == true){
        cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_sizee, (val) => {
            return `
            min-width: auto;
            min-height: auto;
            padding: 0px;
            text-align: center;
            line-height: ${val}px;
            width: ${val}px;
            height: ${val}px;
            `
        });
    }

    if (shopengine_sale_flash_pos.desktop == 'left') {
        cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_position_horizontial, (val) => {
            return `
            left: ${val}px;
            `
        });
    }

    if (shopengine_sale_flash_pos.desktop == 'right') {
        cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_position_horizontial, (val) => {
            return `
            right: ${val}px;
            `
        });
    }

    cssHelper.add('.shopengine-archive-products .onsale', shopengine_sale_flash_position_vertical, (val) => {
        return `
        top: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner button, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner .button,.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner', shopengine_button_group_btn_bg_clr, (val) => {
        return `
        background-color: ${val} !important;
        `
    });

    cssHelper.add('.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a i, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist) span, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist) svg, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist) path, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist) a::before,.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.button:not(.shopengine-quickview-trigger)::before', shopengine_button_group_btn_clr, (val) => {
        return `
        fill  : ${val} !important;
        color : ${val} !important;
        `
    });

    cssHelper.add('.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner .shopengine-wishlist path', shopengine_button_group_btn_clr, (val) => {
        return `
        color : ${val} !important;
        `
    });

    cssHelper.add('.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:hover i, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist):hover span, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist):hover svg, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist):hover path, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.shopengine-wishlist):hover a::before,.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.button:hover:not(.shopengine-quickview-trigger)::before,.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner .shopengine-wishlist.active path,.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.button.active:not(.shopengine-quickview-trigger)::before', shopengine_button_group_btn_hover_active_clr, (val) => {
        return `
        fill: ${val} !important;
        color : ${val} !important;
        `
    });

    cssHelper.add('.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner .shopengine-wishlist:hover path', shopengine_button_group_btn_hover_active_clr, (val) => {
        return `
        color: ${val} !important;
        `
    });

    cssHelper.add('.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.active:not(.shopengine-wishlist) i, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.active:not(.shopengine-wishlist) span, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.active:not(.shopengine-wishlist) svg, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.active:not(.shopengine-wishlist) path, .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a.active:not(.shopengine-wishlist) a::before', shopengine_button_group_btn_hover_active_clr, (val) => {
        return `
        fill: ${val} !important;
        `
    });

    cssHelper.add(`
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.wc-forward),
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner button,
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner .button
    `, settings.shopengine_button_group_sbtn_bg_clr, (val) => {
        return `
        background: ${val}!important;
        `
    });

    cssHelper.add(`
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.wc-forward):hover,
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner button:hover,
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner .button:hover
    `, settings.shopengine_button_group_sbtn_hbg_clr, (val) => {
        return `
        background: ${val}!important;
        `
    });

    cssHelper.add('.shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner svg', shopengine_button_group_btn_icon_size, (val) => {
        return `
        width: ${val}px !important;
        `
    });

    cssHelper.add('.shopengine-widget .shopengine-archive-products .loop-product--btns a::before, .shopengine-widget .shopengine-archive-products .loop-product--btns i::before', shopengine_button_group_btn_icon_size, (val) => {
        return `
        font-size: ${val}px !important;
        `
    });

    cssHelper.add(`
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner a:not(.wc-forward),
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner button,
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner .button
    `, shopengine_button_group_btn_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
        `
    });
    
    cssHelper.add(`
    .shopengine-archive-products ul li .loop-product--btns .loop-product--btns-inner
    `, settings.shopengine_button_group_gap, (val) => {
        return `
        column-gap: ${val}px;
        `
    });

    if (shopengine_pagi_align.desktop == "left") {
        cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul', shopengine_pagi_pd, (val) => {
            return `
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
            `
        });
    }

    if (shopengine_pagi_align.desktop == "center") {
        cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul', shopengine_pagi_pd, (val) => {
            return `
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            `
        });
    }

    if (shopengine_pagi_align.desktop == "right") {
        cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul', shopengine_pagi_pd, (val) => {
            return `
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            `
        });
    }

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination', shopengine_pagi_font_font_size, (val) => {
        return `
        font-size: ${val}px;
        `
    });
    cssHelper.add('.shopengine-archive-products .woocommerce-pagination', shopengine_pagi_font_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    });
    cssHelper.add('.shopengine-archive-products .woocommerce-pagination', shopengine_pagi_font_word_spacing, (val) => {
        return `
        word-spacing: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination', shopengine_pagi_top_space, (val) => {
        return `
        padding-top: ${val}px;
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination', shopengine_pagi_n_color, (val) => {
        return `
        color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers:not(.dots)', shopengine_pagi_n_bgcolor, (val) => {
        return `
        background-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers:not(.dots)', shopengine_pagi_n_bd, (val) => {
        return `
        border-style: ${val};
        `
    });


    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers:not(.dots)', shopengine_pagi_n_bd_width, (val) => {
        return `
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers:not(.dots)', shopengine_pagi_n_bd_color, (val) => {
        return `
        border-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers:hover:not(.current)', shopengine_pagi_h_color, (val) => {
        return `
        color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers:hover:not(.current)', shopengine_pagi_h_bgcolor, (val) => {
        return `
        background-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers.current,.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers:hover', shopengine_pagi_h_bdc, (val) => {
        return `
        border-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers.current', settings.shopengine_pagi_ab_color, (val) => {
        return `
        color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers.current', settings.shopengine_pagi_bh_bgcolor, (val) => {
        return `
        background: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers.current', settings.shopengine_pagi_ab_bdc, (val) => {
        return `
        border-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers.current:hover', settings.shopengine_pagi_ab_bdc, (val) => {
        return `
        border-color: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers.current', settings.shopengine_pagi_ab_radius, (val) => {
        return `
        border-radius: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers', shopengine_pagi_radius, (val) => {
        return `
        border-radius: ${val};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers', shopengine_pagi_mg, (val) => {
        return `
        margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add('.shopengine-archive-products .woocommerce-pagination > ul > li > .page-numbers', shopengine_pagi_pd, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    });

    cssHelper.add(`.shopengine-archive-products .product-categories > li,
    .shopengine-archive-products .product .woocommerce-loop-product__title,
    .shopengine-archive-products .product .price .amount,
    .shopengine-archive-products .product a.button:not(.shopengine-quickview-trigger),
    .shopengine-archive-products .onsale,
    .shopengine-archive-products .woocommerce-pagination,
    .shopengine-archive-products .product .shopengine-product-excerpt`, settings.shopengine_global_font_family, (val) => {
        return `font-family: ${val.family};`
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `,shopengine_tooltip_horizontal_position, (val) => {
        return(`
            bottom: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `,shopengine_tooltip_vertical_position, (val) => {
        return(`
            left: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `,shopengine_tooltip_text_color, (val) => {
        return(`
            color: ${val} !important;
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `,shopengine_tooltip_background_color, (val) => {
        return(`
            background-color: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext::after
    `,shopengine_tooltip_background_color, (val) => {
        return(`
            border-color: ${val} transparent transparent transparent;
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `,shopengine_tooltip_text_font_size, (val) => {
        return(`
            font-size: ${val}px;
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `, shopengine_tooltip_text_font_weight, (val) => {
        return(`
            font-weight: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `,shopengine_tooltip_text_transform, (val) => {
        return(`
            text-transform: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `, shopengine_tooltip_text_line_height, (val) => {
        return(`
            line-height: ${val}px;
        `)
    });

    cssHelper.add(`
    .shopengine-widget .shopengine-archive-mode-grid .tooltiptext
    `,shopengine_tooltip_text_word_spacing, (val) => {
        return(`
            word-spacing: ${val}px;
        `)
    });

    return cssHelper.get()
}


export { Style }