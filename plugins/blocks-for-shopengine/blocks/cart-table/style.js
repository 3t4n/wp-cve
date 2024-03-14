
const Style = ({settings, breakpoints, cssHelper})=>{
    const shopengine_shopping_button = settings.shopengine_shopping_button.desktop === false ? "inline-block" : "none";
    const shopengine_clear_button = settings.shopengine_clear_button.desktop === false ? "inline-block" : "none";
    
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer  .return-to-shop', {}, (val) => {
        return `
        display: ${shopengine_shopping_button};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer button[name=empty_cart]', {}, (val) => {
        return `
        display: ${shopengine_clear_button};
        `
    } )


    cssHelper.add('.shopengine-cart-table .shopengine-table__head', settings.shopengine_table_header_bg, (val) => {
        return `
        background-color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__head div', settings.shopengine_table_header_text_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__head div', settings.shopengine_cart_table_head_typography, (val) => {
        return (`
            font-size: ${val.fontSize};
            font-weight: ${val.fontWeight};
            text-transform: ${val.textTransform};
            line-height: ${val.lineHeight};
            letter-spacing: ${val.letterSpacing};
            word-spacing: ${val.wordSpacing};
        `)
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__head', settings.shopengine_table_head_padding, (val) => {
        return(`
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body', settings.shopengine_table_body_bg, (val) => {
        return `
            background: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-table__body-item--td, .shopengine-cart-table .shopengine-table__body div, .shopengine-cart-table .shopengine-table__body a, .shopengine-cart-table .shopengine-table__body span', settings.shopengine_table_body_text_color, (val) => {
        return `
            color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body', settings.shopengine_table_body_border_color, (val) => {
        return `
            border-style: solid; 
            border-width: 0 1px 1px 1px; 
            border-color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-table__body-item--td a:hover', settings.shopengine_table_body_link_hover_color, (val) => {
        return `
            color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-table__body-item--td .amount span, .shopengine-cart-table .shopengine-table__body .shopengine-table__body-item--td .amount bdi', settings.shopengine_table_body_price_color, (val) => {
        return `
            color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table table tbody .product-subtotal', settings.shopengine_table_body_price_color, (val) => {
        return `
            color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-table__body-item--td a, .shopengine-cart-table .shopengine-table__body .shopengine-table__body-item--td .amount, .shopengine-cart-table .shopengine-table__body .shopengine-table__body-item--td bdi)', settings.shopengine_cart_table_content_typography, (val) => {
        return(`
            font-size: ${val.fontSize};
            font-weight: ${val.fontWeight};
            text-transform: ${val.textTransform};
            line-height: ${val.lineHeight};
            letter-spacing: ${val.letterSpacing};
            word-spacing: ${val.wordSpacing};
        `)
    } )

    cssHelper.add('.shopengine-cart-table .shopengine-table__body', settings.shopengine_table_body_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body', settings.shopengine_table_body_row_gap, (val) => {
        return `
        grid-row-gap: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .product-thumbnail img', settings.shopengine_product_image_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .product-thumbnail img', settings.shopengine_product_image_width, (val) => {
        return `
        width: ${val}px;
        min-width: ${val}px;
        height: auto;
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .minus-button, .shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .plus-button, .shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .quantity, .shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity input)', settings.shopengine_quantity_text_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.shopengine-widget .shopengine-cart-table .shopengine-table__body-item--td .shopengine-cart-quantity .quantity input', settings.shopengine_quantity_text_color, (val) => {
        return `
        color: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .minus-button:hover, .shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .plus-button:hover', settings.shopengine_quantity_hover_text_color, (val) => {
        return `
        color: ${val};
        `
    } )

    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .minus-button, .shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .plus-button, .shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .quantity', settings.shopengine_quantity_border_color, (val) => {
        return `
        border-color: ${val};
        `
    } )

    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .minus-button', settings.shopengine_quantity_border_radius, (val) => {
        return `
        border-radius: ${val.top} ${val.left} ${val.bottom} ${val.right};
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        `
    })

    cssHelper.add('.shopengine-cart-table .shopengine-table__body .shopengine-cart-quantity .plus-button', settings.shopengine_quantity_border_radius, (val) => {
        return `
        border-radius: ${val.top} ${val.left} ${val.bottom} ${val.right};
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
        `
    })

    cssHelper.add('.shopengine-cart-table .shopengine-table__footer', settings.shopengine_table_footer_bg, (val) => {
        return `
        background: ${val};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer', settings.shopengine_table_footer_padding, (val) => {
        return `
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button', settings.shopengine_cart_table_footer_btn_typography, (val) => {
        return(`
            font-size: ${val.fontSize};
            font-weight: ${val.fontWeight};
            text-transform: ${val.textTransform};
            word-spacing: ${val.wordSpacing};
        `)
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button, .shopengine-cart-table .shopengine-table__footer a, .shopengine-cart-table .shopengine-table__footer i', settings.shopengine_button_color, (val) => {
        return(`
            color: ${val} !important;
        `)
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button', settings.shopengine_button_bg, (val) => {
        return(`
            background-color:${val}!important;
        `)
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button:hover, .shopengine-cart-table .shopengine-table__footer a:hover', settings.shopengine_button_hover_color, (val) => {
        return(`
            color: ${val} !important;
        `)
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button:hover a, .shopengine-cart-table .shopengine-table__footer .shopengine-footer-button:hover i, .shopengine-cart-table .shopengine-table__footer .shopengine-footer-button:hover span)', settings.shopengine_button_hover_color, (val) => {
        return ` 
            color: ${val} !important;
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button:hover', settings.shopengine_button_hover_bg, (val) => {
        return ` 
            background-color: ${val} !important;
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button', settings.shopengine_button_padding, (val) => {
        return ` 
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )
    cssHelper.add('.shopengine-cart-table .shopengine-table__footer .shopengine-footer-button', settings.shopengine_button_border_radius, (val) => {
        return ` 
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } )

    cssHelper.add(`.shopengine-cart-table .shopengine-table__body div,
    .shopengine-cart-table .shopengine-table__body span,
    .shopengine-cart-table .shopengine-table__body a,
    .shopengine-cart-table .shopengine-table__body button,
    .shopengine-cart-table .shopengine-table__body bdi,
    .shopengine-cart-table .shopengine-table__head div,
    .shopengine-cart-table .shopengine-table__head span,
    .shopengine-cart-table .shopengine-table__head a,
    .shopengine-cart-table .shopengine-table__head button,
    .shopengine-cart-table .shopengine-table__head bdi,
    .shopengine-cart-table .shopengine-table__footer div,
    .shopengine-cart-table .shopengine-table__footer span,
    .shopengine-cart-table .shopengine-table__footer a,
    .shopengine-cart-table .shopengine-table__footer button,
    .shopengine-cart-table .shopengine-table__footer bdi`, settings.shopengine_global_font_family, (val) => {
        return ` 
            font-family: ${val.family};
        `});

    cssHelper.add(`.shopengine-cart-table table th,
    .shopengine-cart-table table tr,
    .shopengine-cart-table table td,
    .shopengine-cart-table table button,
    .shopengine-cart-table table input`, settings.shopengine_global_font_family, (val) => {
        return ` 
            font-family: ${val.family};
        `})


    return cssHelper.get()
}


export {Style}