
const Style = ({settings, breakpoints, cssHelper})=>{

    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.shopengine_currency_switcher_height, (val) => {
        return (
            `
            height: ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.currency_switcher_font_family, (val) => {
        return (
            `
            font-family : ${val.family};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.currency_switcher_font_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.currency_switcher_font_weight, (val) => {
        return (
            `
            font-weight : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.currency_switcher_text_transform, (val) => {
        return (
            `
            text-transform : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.currency_switcher_word_spacing, (val) => {
        return (
            `
            word-spacing : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--icon
    `, settings.shopengine_arrow_size, (val) => {
        return (
            `
            font-size : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher
    `, settings.shopengine_currency_switcher_color, (val) => {
        
        return (
            `
            color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.shopengine_currency_switcher_bg_color, (val) => {
        return (
            `
            background-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.shopengine_currency_switcher_border_type, (val) => {
        return (
            `
            border-style : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.shopengine_currency_switcher_border_width, (val) => {
        return (
            `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.shopengine_currency_switcher_border_color, (val) => {
        return (
            `
            border-color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-currency-switcher:hover
    `, settings.shopengine_currency_switcher_hover_color, (val) => {
        return (
            `
            color : ${val};

            `
        )
    })

    cssHelper.add(`
    .shopengine-currency-switcher--select:hover
    `, settings.shopengine_currency_switcher_hover_bg_color, (val) => {
        return (
            `
            background-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select:hover
    `, settings.shopengine_currency_switcher_hover_border, (val) => {
        return (
            `
            border-color : ${val};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.shopengine_currency_switcher_radius, (val) => {
        return (
            `
            border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })


    cssHelper.add(`
    .shopengine-currency-switcher--select
    `, settings.shopengine_currency_switcher_padding, (val) => {
        return (
            `
            padding : ${val.top} ${val.right} ${val.bottom} ${val.left};

            `
        )
    })





    return cssHelper.get()
}

export {Style}