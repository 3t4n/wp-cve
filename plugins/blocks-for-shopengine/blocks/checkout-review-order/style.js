
const Style = ({ settings, breakpoints, cssHelper }) => {

        cssHelper.add('.shopengine-checkout-review-order .woocommerce-checkout-review-order-table thead th', settings.shopengine_orders_header_color, (val) => {
            return (
                `
                color : ${val};
                `
            )
        })

        .add(`.shopengine-checkout-review-order .woocommerce-checkout-review-order-table thead tr`, settings.shopengine_orders_header_border_color, (val) => {
            return (
                `
               box-shadow: 0px 1px ${val};
            `
            )
        })

        .add(`.shopengine-checkout-review-order #order_review .woocommerce-checkout-review-order-table thead th`, settings.orders_header_text_font_size, (val) => {
            return (
                `
               font-size : ${val}px;
            `
            )
        })

        .add(`.shopengine-checkout-review-order #order_review .woocommerce-checkout-review-order-table thead th`, settings.orders_header_text_font_weight, (val) => {
            return (
                `
               font-weight : ${val};
            `
            )
        })

        .add(`.shopengine-checkout-review-order #order_review .woocommerce-checkout-review-order-table thead th`, settings.orders_header_text_text_transform, (val) => {
            return (
                `
               text-transform : ${val};
            `
            )
        })

        .add(`.shopengine-checkout-review-order #order_review .woocommerce-checkout-review-order-table thead th`, settings.orders_header_text_title_wordspace, (val) => {
            return (
                `
               word-spacing : ${val}px;
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table thead tr
    `, settings.shopengine_table_header_padding, (val) => {
            return (
                `
               padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table thead tr
    `, settings.shopengine_table_header_spacing, (val) => {
            return (
                `
            margin-bottom : ${val}px;
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td
    `, settings.shopengine_table_body_text_color, (val) => {
            return (
                `
            color : ${val};
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table > tbody > tr
    `, settings.shopengine_orders_body_background, (val) => {
            return (
                `
            background-color : ${val} !important;
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td .amount
    `, settings.shopengine_table_body_price_color, (val) => {
            return (
                `
            color : ${val};
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody tr
    `, settings.shopengine_table_item_border_color, (val) => {
            return (
            `
            box-shadow : 0px 1px ${val}, 0 3px #ffffff;
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td .woocommerce-Price-amount
    `, settings.orders_body_price_font_weight, (val) => {
            return (
                `
            font-weight : ${val};
            
            `
            )
        })

        .add(
            `.shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td .woocommerce-Price-amount
    `, settings.orders_body_price_wordspace, (val) => {
            return (
                `
            word-spacing : ${val}px;

            `
            )
        })

        .add(
            `
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody label,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody .amount,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody strong,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody bdi
    
    `, settings.orders_body_text_font_size, (val) => {
            return (
                `
            font-size : ${val}px;

            `
            )
        })

        .add(
            `
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody label,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody .amount,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody strong,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody bdi
    
    `, settings.orders_body_text_transform, (val) => {
            return (
                `
            text-transform : ${val};

            `
            )
        })

        .add(
            `
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody label,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody .amount,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody strong,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody bdi
    
    `, settings.orders_body_text_line_height, (val) => {
            return (
                `
            line-height : ${val}px;

            `
            )
        })

        .add(
            `
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody td,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody label,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody .amount,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody strong,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody bdi
    
    `, settings.orders_body_text_wordspace, (val) => {
            return (
                `
            word-spacing : ${val}px;

            `
            )
        })

        .add(
            `
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table:not(.shipping__table--multiple) > tbody > tr
    
    `, settings.shopengine_table_body_data_padding, (val) => {
            return (
                `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
            )
        })

    .add(`
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tbody tr:not(:last-child)
    `, settings.shopengine_table_body_spacing, (val) => {
        return (`
            margin-bottom : ${val}px;
        `)
    })
    .add(`
    .shopengine-checkout-review-order .shopengine-order-review-product img
    `, settings.shopengine_table_body_image_size, (val) => {
        return (`
            height: ${val}px;
            width : ${val}px;
        `)
    })

        .add(
            `
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot th,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot label
        
        `, settings.shopengine_footer_text_color, (val) => {
            return (
                `
                color : ${val};
    
                `
            )
        })

        .add(
            `
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot th .amount,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td .amount
        
        `, settings.shopengine_footer_price_color, (val) => {
            return (
                `
                color : ${val} !important;
    
                `
            )
        })

        .add(
            `
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot tr:not(.woocommerce-shipping-totals)
        
        `, settings.shopengine_orders_footer_border_color, (val) => {
            return (
                `
                box-shadow: 0px 1px ${val}, 0 3px #ffffff;
    
                `
            )
        })

        .add(
            `
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot th,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td label,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td .amount
        
        `, settings.shopengine_footer_text_font_size, (val) => {
            return (
                `
                font-size : ${val}px;
    
                `
            )
        })

        .add(
            `
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot th,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td label,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td .amount
        
        `, settings.shopengine_footer_text_font_weight, (val) => {
            return (
                `
                font-weight : ${val};
    
                `
            )
        })

        .add(
            `
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot th,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td label,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td .amount
        
        `, settings.shopengine_footer_text_text_transform, (val) => {
            return (
                `
                text-transform : ${val};
    
                `
            )
        })

        .add(
            `
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot th,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td label,
        .shopengine-checkout-review-order .woocommerce-checkout-review-order-table tfoot td .amount
        
        `, settings.shopengine_footer_text_wordspace, (val) => {
            return (
                `
                word-spacing : ${val}px;
    
                `
            )
        })

        .add(
            `
            .shopengine-checkout-review-order .woocommerce-checkout-review-order-table:not(.shipping__table--multiple) > tfoot > tr
        `, settings.shopengine_table_footer_data_padding, (val) => {
            return (
                `
                padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
    
                `
            )
        })

    .add(`
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table td,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table th,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table a,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table label,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table span,
    .shopengine-checkout-review-order .woocommerce-checkout-review-order-table *
    `, settings.shopengine_global_font_family, (val) => {
        return (
            `
            font-family : ${val.family};

            `
        )
    })

    return cssHelper.get()
}

export { Style }