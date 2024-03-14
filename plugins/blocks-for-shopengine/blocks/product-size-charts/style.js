
const Style = ({ settings, breakpoints, cssHelper }) => {

    cssHelper.add(`
    .shopengine-product-size-chart-body
    `, settings.shopengine_product_size_chart_alignment, (val) => (`
        display: flex;
        flex-direction: column;
        align-items: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-heading
    `, settings.shopengine_product_size_charts_heading_color, (val) => (`
        color : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-heading
    `, settings.shopengine_product_size_chart_heading_font_family, (val) => (`
        font-family : ${val.family};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-heading
    `, settings.shopengine_product_size_chart_heading_font_size, (val) => (`
        font-size : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-heading
    `, settings.shopengine_product_size_chart_heading_font_weight, (val) => (`
        font-weight : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-heading
    `, settings.shopengine_product_size_chart_heading_line_height, (val) => (`
        line-height : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-heading
    `, settings.shopengine_product_size_chart_heading_margin, (val) => (`
        margin : ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-img,
    .shopengine-product-size-chart-img img
    `, settings.shopengine_product_size_charts_image_width, (val) => (`
        width : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-img
    `, settings.shopengine_product_size_charts_image_padding, (val) => (`
        padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-img,
    .shopengine-product-size-chart-img img
    `, settings.shopengine_product_size_charts_image_radius, (val) => (`
        border-radius : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_font_family, (val) => (`
        font-family: ${val.family};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_font_size, (val) => (`
        font-size: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_font_weight, (val) => (`
        font-weight : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_text_color_normal, (val) => (`
        color : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_bg_color_normal, (val) => (`
        background-color : ${val} !important;
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button:hover
    `, settings.shopengine_product_size_charts_text_color_hover, (val) => (`
        color : ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button:hover
    `, settings.shopengine_product_size_charts_bg_color_hover, (val) => (`
        background-color: ${val} !important;
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button:hover
    `, settings.shopengine_product_size_charts_border_color_hover, (val) => (`
        border-color: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_border_type, (val) => (`
        border-style: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_border_width, (val) => (`
        border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_border_color, (val) => (`
        border-color: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_border_radius, (val) => (`
        border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart-body .shopengine-product-size-chart-button
    `, settings.shopengine_product_size_charts_padding, (val) => (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart .shopengine-product-size-chart-contant,
    .shopengine-product-size-chart .shopengine-product-size-chart-contant img
    `, settings.shopengine_product_size_charts_content_border_radius, (val) => (`
        border-radius: ${val};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart .shopengine-product-size-chart-contant
    `, settings.shopengine_product_size_charts_content_padding, (val) => (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-product-size-chart .shopengine-product-size-chart-contant
    `, settings.shopengine_product_size_charts_content_background_color, (val) => (`
        background-color: ${val};
    `))



    return cssHelper.get()
}


export { Style }