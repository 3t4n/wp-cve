
const Style = ({ settings, breakpoints, cssHelper }) => {

    cssHelper.add('.shopengine-widget .shopengine-checkout-shipping-methods table', {}, (val) => {
        return (
            `
               width : 100%;
            `
        )
    })
    cssHelper.add('.shopengine-checkout-shipping-methods .woocommerce-shipping-totals.shipping td::before', settings.shopengine_title_color, (val) => {
        return (
            `
               color : ${val};
            `
        )
    })

    cssHelper.add('.shopengine-checkout-shipping-methods .woocommerce-shipping-totals.shipping td::before', settings.shopengine_title_font_size, (val) => {
        return (
            `
               font-size : ${val}px;
            `
        )
    })

    cssHelper.add('.shopengine-checkout-shipping-methods .woocommerce-shipping-totals.shipping td::before', settings.shopengine_title_weight, (val) => {
        return (
            `
               font-weight : ${val};
            `
        )
    })


    cssHelper.add('.shopengine-checkout-shipping-methods .woocommerce-shipping-totals.shipping td::before', settings.shopengine_title_transform, (val) => {
        return (
            `
               text-transform : ${val};
            `
        )
    })

    cssHelper.add('.shopengine-checkout-shipping-methods .woocommerce-shipping-totals.shipping td::before', settings.shopengine_title_wordspace, (val) => {
        return (
            `
               word-spacing : ${val}px;
            `
        )
    })

    cssHelper.add('.shopengine-checkout-shipping-methods .woocommerce-shipping-totals.shipping td::before', settings.shopengine_table_title_margin_bottom, (val) => {
        return (
            `
               margin-bottom : ${val}px;
            `
        )
    })

    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li label,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li .amount,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li span
    `, settings.shopengine_payment_label_text_color, (val) => {
        return (
            `
               color : ${val};
            `
        )
    })

    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li label,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li .amount,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li span,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li bdi
    `, settings.shopengine_payment_label_font_size, (val) => {
        return (
            `
               font-size : ${val}px;
            `
        )
    })

    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li label,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li .amount,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li span,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li bdi
    `, settings.shopengine_payment_label_font_weight, (val) => {
        return (
            `
               font-weight : ${val};
            `
        )
    })

    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li label,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li .amount,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li span,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li bdi
    `, settings.shopengine_payment_label_line_height, (val) => {
        return (
            `
               line-height : ${val}px;
            `
        )
    })

    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li label,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li .amount,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li span,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li bdi
    `, settings.shopengine_payment_label_wordspace, (val) => {
        return (
            `
               word-spacing : ${val}px;
            `
        )
    })

    cssHelper.add(`
    .shopengine-checkout-shipping-methods #shipping_method
    `, settings.shopengine_payment_label_gap, (val) => {
        return (
            `
            display:flex;
            flex-direction: column; 
            gap: ${val}px;

            `
        )
    })


    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li input
    `, settings.shopengine_payment_methods_checkbox_position_y, (val) => {
        return (
            `
            transform : translateY(${val}px);

            `
        )
    })

    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals ul li input
    `, settings.shopengine_payment_methods_checkbox_margin, (val) => {
        return (
            `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })
    
    cssHelper.add(`
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals th,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals label,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals td,
    .shopengine-checkout-shipping-methods .woocommerce-shipping-totals td::before
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