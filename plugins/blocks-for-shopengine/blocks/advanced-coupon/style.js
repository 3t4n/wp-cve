
const Style = ({ settings, breakpoints, cssHelper }) => {
    cssHelper.add(`
    .shopengine-advanced-coupon-container
    `, settings.shopengine_advanced_coupon_alignment, (val) => {
        return (
            `
            justify-content : ${val};
            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner
    `, settings.shopengine_advanced_coupon_content_bg, (val) => {
        return (
            `
            background : ${val};
            
            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-body .shopengine-advanced-coupon-content h5
    `, settings.shopengine_advanced_coupon_title_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-body .shopengine-advanced-coupon-content h5
    `, settings.shopengine_advanced_coupon_title_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-body .shopengine-advanced-coupon-content h5
    `, settings.shopengine_advanced_coupon_title_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-body .shopengine-advanced-coupon-content h5
    `, settings.shopengine_advanced_coupon_title_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-body .shopengine-advanced-coupon-content h5
    `, settings.shopengine_advanced_coupon_title_letter_spacing, (val) => {
        return (
            `
            letter-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-body .shopengine-advanced-coupon-content h5
    `, settings.shopengine_advanced_coupon_title_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-body .shopengine-advanced-coupon-content h5 p
    `, settings.shopengine_advanced_coupon_subtitle_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-discount h1,
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-discount .advanced-coupon-discount
    `, settings.shopengine_advanced_coupon_discount_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-date p
    `, settings.shopengine_advanced_coupon_date_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-date p
    `, settings.shopengine_advanced_coupon_date_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-date p
    `, settings.shopengine_advanced_coupon_date_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-date p
    `, settings.shopengine_advanced_coupon_date_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-date p
    `, settings.shopengine_advanced_coupon_date_letter_spacing, (val) => {
        return (
            `
            letter-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-container-inner .shopengine-advanced-coupon-date p
    `, settings.shopengine_advanced_coupon_date_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-button button,
    .shopengine-advanced-coupon-footer button
    `, settings.shopengine_advanced_coupon_buttons_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-button button,
    .shopengine-advanced-coupon-footer button
    `, settings.shopengine_advanced_coupon_buttons_bg, (val) => {
        return (
            `
            background-color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-footer button:before
    `, settings.shopengine_advanced_coupon_buttons_bg, (val) => {
        return (
            `
            border-color: transparent transparent ${val} transparent;

            `
        )
    })

    cssHelper.add(`
    .shopengine-advanced-coupon-footer button:after
    `, settings.shopengine_advanced_coupon_buttons_bg, (val) => {
        return (
            `
            border-color: transparent transparent transparent ${val};

            `
        )
    })

    





    return cssHelper.get()
}


export { Style }