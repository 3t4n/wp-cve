const Style = ({settings, breakpoints, cssHelper})=>{

    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle-wrapper
    `,settings.shopengine_filter_toggle_button_toggler_alignment, (val) => (`
        text-align: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group-content-wrapper
    `,settings.shopengine_section_filter_content_width, (val) => (`
        width: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-product-filters-wrapper
    `, settings.shopengine_section_filter_layout_col_number, (val) => (`
        grid-template-columns: repeat(${val}, 1fr);
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-product-filters-wrapper
    `, settings.shopengine_section_filter_layout_col_padding, (val) => (`
        grid-gap: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.filters_typography_title_font_size, (val) => (`
        font-size: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.filters_typography_title_font_weight, (val) => (`
        font-weight: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.filters_typography_title_text_transform, (val) => (`
        text-transform: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.filters_typography_title_font_style, (val) => (`
        font-style: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.filters_typography_title_line_height, (val) => (`
        line-height: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.filters_typography_title_letter_spacing, (val) => (`
        letter-spacing: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.filters_typography_title_word_spacing, (val) => (`
        word-spacing: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,.shopengine-product-filters .shopengine-filter-price-result,.shopengine-product-filters .filter-input-group label
    `, settings.product_filters_typography_primary_font_size, (val) => (`
        font-size: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,.shopengine-product-filters .shopengine-filter-price-result,.shopengine-product-filters .filter-input-group label
    `, settings.product_filters_typography_primary_font_weight, (val) => (`
        font-weight: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,.shopengine-product-filters .shopengine-filter-price-result,.shopengine-product-filters .filter-input-group label
    `, settings.product_filters_typography_primary_text_transform, (val) => (`
        text-transform: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,.shopengine-product-filters .shopengine-filter-price-result,.shopengine-product-filters .filter-input-group label
    `, settings.product_filters_typography_primary_font_style, (val) => (`
        font-style: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,.shopengine-product-filters .shopengine-filter-price-result,.shopengine-product-filters .filter-input-group label
    `, settings.product_filters_typography_primary_line_height, (val) => (`
        line-height: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,.shopengine-product-filters .shopengine-filter-price-result,.shopengine-product-filters .filter-input-group label
    `, settings.product_filters_typography_primary_letter_spacing, (val) => (`
        letter-spacing: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,.shopengine-product-filters .shopengine-filter-price-result,.shopengine-product-filters .filter-input-group label
    `, settings.product_filters_typography_primary_word_spacing, (val) => (`
        word-spacing: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.shopengine_filter_heading_color, (val) => (`
        color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-category-label,
    .shopengine-product-filters .shopengine-filter-color-label,
    .shopengine-product-filters .shopengine-filter-attribute-label,
    .shopengine-product-filters .shopengine-filter-shipping-label,
    .shopengine-product-filters .shopengine-filter-stock-label,
    .shopengine-product-filters .shopengine-filter-onsale-label
    `, settings.shopengine_filter_title_color, (val) => (`
        color: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-category-label:hover,
    .shopengine-product-filters .shopengine-filter-color-label:hover,
    .shopengine-product-filters .shopengine-filter-attribute-label:hover,
    .shopengine-product-filters .shopengine-filter-shipping-label:hover,
    .shopengine-product-filters .shopengine-filter-stock-label:hover,
    .shopengine-product-filters .shopengine-filter-onsale-label:hover
    `, settings.shopengine_filter_title_color_hover, (val) => (`
        color: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-price-reset,
    .shopengine-product-filters .shopengine-filter-price-result,
    .shopengine-product-filters .filter-input-group label,
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle,
    .shopengine-product-filters h3.shopengine-product-filter-title
    `, settings.shopengine_filter_price_font, (val) => (`
        font-family: ${val.family};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-single:not(.shopengine-collapse) h3.shopengine-product-filter-title
    `, settings.shopengine_filter_title_spacing, (val) => (`
        padding-bottom: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .filter-input-group,
    .shopengine-product-filters .shopengine-filter-rating__labels a:not(:last-child)
    `, settings.shopengine_filter_color_line_spacing, (val) => (`
        margin: ${val} 0;
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels--mark,
    .shopengine-product-filters .shopengine-checkbox-icon
    `, settings.shopengine_checkbox_tabs_normal_clr, (val) => (`
        border-color: ${val} !important;
    `))


    cssHelper.add(`
    .shopengine-product-filters .rating-label-triger.checked .shopengine-filter-rating__labels--mark,.shopengine-product-filters input:checked + label .shopengine-checkbox-icon
    `, settings.shopengine_checkbox_tabs_checked_clr, (val) => (`
        color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .rating-label-triger.checked .shopengine-filter-rating__labels--mark
    `, settings.shopengine_checkbox_tabs_checked_bg_clr, (val) => (`
        background: ${val};
        border-color : ${val} !important;
    `))


    cssHelper.add(`
    .shopengine-product-filters input:checked + label .shopengine-checkbox-icon
    `, settings.shopengine_checkbox_tabs_checked_bg_clr, (val) => (`
        background: ${val};
        border-color : ${val} !important;
    `))


    cssHelper.add(`
    .open .shopengine-collapse-icon
    `, settings.shopengine_checkbox_tabs_checked_bg_clr, (val) => (`
        color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels--mark,
    .shopengine-product-filters .shopengine-checkbox-icon
    `, settings.shopengine_checkbox_margin_right, (val) => (`
        margin-right: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels--mark span,.shopengine-product-filters .shopengine-filter-rating__labels--mark i, .shopengine-product-filters .shopengine-filter-rating__labels--mark svg, .shopengine-product-filters .shopengine-filter-rating__labels--mark img
    `, settings.shopengine_checkbox_icon_size, (val) => (`
        font-size: ${val};
        width : ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-checkbox-icon span,.shopengine-product-filters .shopengine-checkbox-icon i,.shopengine-product-filters .shopengine-checkbox-icon svg, .shopengine-product-filters .shopengine-checkbox-icon img
    `, settings.shopengine_checkbox_icon_size, (val) => (`
        font-size: ${val};
        width : ${val};
    `))



    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels--mark,.shopengine-product-filters .shopengine-checkbox-icon
    `, settings.shopengine_checkbox_size, (val) => (`
        line-height: ${val};
        width : ${val};
        height : ${val};
    `))



    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels--mark,.shopengine-product-filters .shopengine-checkbox-icon
    `, settings.shopengine_checkbox_vertical_position, (val) => (`
        transform: translateY(${val});
    `))



    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels--mark,.shopengine-product-filters .shopengine-checkbox-icon
    `, settings.shopengine_checkbox_radius, (val) => (`
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_font_size, (val) => (`
        font-size: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_font_weight, (val) => (`
        font-weight: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_text_transform, (val) => (`
        text-transform: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_font_style, (val) => (`
        font-style: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_line_height, (val) => (`
        line-height: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_letter_spacing, (val) => (`
        letter-spacing: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_word_spacing, (val) => (`
        word-spacing: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_color, (val) => (`
        color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_background, (val) => (`
        background-color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle:hover,.shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle.active
    `, settings.shopengine_filter_toggler_color_hover, (val) => (`
        color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle:hover,.shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle.active
    `, settings.shopengine_filter_toggler_background_hover, (val) => (`
        background-color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_border_type, (val) => (`
        border-style: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_border_width, (val) => (`
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_border_color, (val) => (`
        border-color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_padding, (val) => (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-toggle
    `, settings.shopengine_filter_toggler_margin, (val) => (`
        margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-content
    `, settings.shopengine_section_filter_content_background, (val) => (`
        background-color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-content-wrapper
    `, settings.shopengine_filter_content_border_type, (val) => (`
        border-style: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-content-wrapper
    `, settings.shopengine_filter_content_border_width, (val) => (`
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-content-wrapper
    `, settings.shopengine_filter_content_border_color, (val) => (`
        border-color: ${val};
    `))


    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-content
    `, settings.shopengine_section_filter_content_padding, (val) => (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    let shadowColor = settings.shopengine_section_filter_content_box_shadow_color.desktop;
    let shdowHorizontal = settings.shopengine_section_filter_content_box_shadow_horizontal.desktop;
    let shdowVertical = settings.shopengine_section_filter_content_box_shadow_vertical.desktop;
    let shadowBlur = settings.shopengine_section_filter_content_box_shadow_blur.desktop;
    let shadowSpread = settings.shopengine_section_filter_content_box_shadow_spread.desktop;
    let shadowPosition = settings.shopengine_section_filter_content_box_shadow_position.desktop;
    
    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-group .shopengine-filter-group-content-wrapper
    `, {}, (val) => (`
        box-shadow: ${shdowHorizontal || 0} ${shdowVertical || 0} ${shadowBlur || 0} ${shadowSpread || 0} ${shadowColor || '#00FFFFFF'} ${shadowPosition || ''};
    `))


    cssHelper.add(`
    .shopengine-widget .shopengine-filter-overlay
    `, settings.shopengine_filter_offcanvas_overlay_color, (val) => (`
        background-color: ${val};
    `))

    cssHelper.add(`
    .shopengine-filter-price .asRange:before
    `, settings.shopengine_range_slider_color, (val) => (`
        background-color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-filter-price .asRange > .asRange-selected:before
    `, settings.shopengine_range_slider_active_color, (val) => (`
        background-color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-filter-price .asRange > .asRange-pointer
    `, settings.shopengine_range_slider_active_color, (val) => (`
        color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-filter-price-result
    `, settings.shopengine_range_text_color, (val) => (`
        color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-filter-price-btns
    `, settings.shopengine_reset_btn_margin_bottom, (val) => (`
        margin-bottom: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-filter-price-reset
    `, settings.shopengine_reset_btn_color, (val) => (`
        color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-filter-price-reset
    `, settings.shopengine_reset_btn_bg_color, (val) => (`
        background-color: ${val};
    `))

    
    
    cssHelper.add(`
    .shopengine-filter-price-reset:hover
    `, settings.shopengine_reset_btn_hover_color, (val) => (`
        color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-filter-price-reset:hover
    `, settings.shopengine_reset_btn_hover_bg_color, (val) => (`
        background-color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels-star.active
    `, settings.shopengine_star_active_clr, (val) => (`
        color: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels-star i, .shopengine-product-filters .shopengine-filter-rating__labels-star svg
    `, settings.shopengine_star_spacing, (val) => (`
        margin: 0 ${val};
    `))

    
    
    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-rating__labels-star i, .shopengine-product-filters .shopengine-filter-rating__labels-star svg
    `, settings.shopengine_star_size, (val) => (`
        font-size: ${val};
        width : ${val};
    `))

    
    cssHelper.add(`
    .shopengine-product-filters .shopengine-filter-category .shopengine-filter-category-subcategories
    `, settings.shopengine_filter_subcategory_marign, (val) => (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))


    
    cssHelper.add(`
    .shopengine-collapse
    `, settings.shopengine_section_filter_collapse_border, (val) => (`
        border: 1px solid ${val};
    `))

    
    cssHelper.add(`
    .shopengine-collapse .shopengine-product-filter-title,.shopengine-collapse .shopengine-collapse-body.open
    `, settings.shopengine_section_filter_collapse_padding, (val) => (`
        padding: ${val};
    `))

    
    cssHelper.add(`
    .shopengine-collapse .shopengine-collapse-body.open
    `, settings.shopengine_section_filter_collapse_padding, (val) => (`
        padding: 0 ${val} ${val} ${val};
    `))

    return cssHelper.get()
}

export { Style }