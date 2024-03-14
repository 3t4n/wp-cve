
const Style = ({settings, breakpoints, cssHelper})=>{

    const blockid = cssHelper.blockId;
    
    cssHelper.add(`.shopengine-categories h2
    `, settings.shopengine_product_categories_title_color, (val) => {
        return (
            `
               color: ${val};
            `
        )
    })

    cssHelper.add(`.shopengine-categories h2
    `, settings.product_categories_title_font_size, (val) => {
        return (
            `
               font-size: ${val}px;
            `
        )
    })

    cssHelper.add(`.shopengine-categories h2
    `, settings.product_categories_title_font_weight, (val) => {
        return (
            `
               font-weight: ${val};
            `
        )
    })

    cssHelper.add(`.shopengine-categories h2
    `, settings.product_categories_title_text_transform, (val) => {
        return (
            `
               text-transform: ${val};
            `
        )
    })


    cssHelper.add(`.shopengine-categories h2
    `, settings.product_categories_title_line_height, (val) => {
        return (
            `
               line-height: ${val}px;
            `
        )
    })


    cssHelper.add(`.shopengine-categories h2
    `, settings.product_categories_title_letter_spacing, (val) => {
        return (
            `
               letter-spacing: ${val}px;
            `
        )
    })


    cssHelper.add(`.shopengine-categories h2
    `, settings.shopengine_product_categories_title_margin, (val) => {
        return (
            `
               margin : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li *,
    .shopengine-categories ul li.cat-parent::before
    `, settings.product_categories_list_font_size, (val) => {
        return (
            `
               font-size: ${val}px;
            `
        )
    })

    
    cssHelper.add(`
    .shopengine-categories ul li *,
    .shopengine-categories ul li.cat-parent::before
    `, settings.product_categories_list_font_weight, (val) => {
        return (
            `
               font-weight: ${val};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li *,
    .shopengine-categories ul li.cat-parent::before
    `, settings.product_categories_list_text_transform, (val) => {
        return (
            `
               text-transform: ${val};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li *,
    .shopengine-categories ul li.cat-parent::before
    `, settings.product_categories_list_line_height, (val) => {
        return (
            `
               line-height: ${val}px;
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li *,
    .shopengine-categories ul li.cat-parent::before
    `, settings.product_categories_list_letter_spacing, (val) => {
        return (
            `
               letter-spacing: ${val}px;
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li *,
    .shopengine-categories ul li.cat-parent::before
    `, settings.shopengine_product_categories_list_color, (val) => {
        return (
            `
               color : ${val};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li:hover > a,
    .shopengine-categories ul li:hover > span,
    .shopengine-categories ul li:hover::before
    `, settings.shopengine_product_categories_list_hover_color, (val) => {
        return (
            `
               color : ${val};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li a
    `, settings.shopengine_product_categories_list_padding, (val) => {
        return (
            `
               padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
        )
    })

    cssHelper.add(`
    .shopengine-categories ul li:not(:first-of-type),
    .shopengine-categories ul li .children li
    `, settings.shopengine_product_categories_list_border_type, (val) => {
        return (
            `
               border-style: ${val};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li:not(:first-of-type),
    .shopengine-categories ul li .children li
    `, settings.shopengine_product_categories_list_border_width, (val) => {
        return (
            `
               border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul li:not(:first-of-type),
    .shopengine-categories ul li .children li
    `, settings.shopengine_product_categories_list_border_color, (val) => {
        return (
            `
               border-color : ${val};
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories ul.children  li
    `, settings.shopengine_product_categories_sub_category_padding_left, (val) => {
        return (
            `
            padding-left : ${val}px;
            `
        )
    })

    cssHelper.add(`
    .shopengine-categories .select2 :is(.select2-selection__rendered),
    .select2-container--default .${blockid} .select2-dropdown :is(.select2-results__option)
    `, settings.product_categories_dropdown_font_size, (val) => {
        return (
            `
            font-size : ${val}px !important; 
            `
        )
    })

    cssHelper.add(`
    .shopengine-categories .select2 :is(.select2-selection__rendered),
    .select2-container--default .${blockid} .select2-dropdown :is(.select2-results__option)
    `, settings.product_categories_dropdown_font_weight, (val) => {
        return (
            `
            font-weight : ${val} !important; 
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2 :is(.select2-selection__rendered),
    .select2-container--default .${blockid} .select2-dropdown :is(.select2-results__option)
    `, settings.product_categories_dropdown_text_transform, (val) => {
        return (
            `
            text-transform : ${val} !important; 
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2 :is(.select2-selection__rendered),
    .select2-container--default .${blockid} .select2-dropdown :is(.select2-results__option)
    `, settings.product_categories_dropdown_line_height, (val) => {
        return (
            `
            line-height : ${val}px !important; 
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2 :is(.select2-selection__rendered),
    .select2-container--default .${blockid} .select2-dropdown :is(.select2-results__option)
    `, settings.product_categories_dropdown_letter_spacing, (val) => {
        return (
            `
            letter-spacing : ${val}px !important; 
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2-selection__rendered, .select2-container--default .${blockid} :is(ul li)
    `, settings.shopengine_product_categories_dropdown_color, (val) => {
        return (
            `
            color : ${val}!important; 

            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2-selection__arrow b
    `, settings.shopengine_product_categories_dropdown_color, (val) => {
        return (
            `
            border-color : ${val} transparent transparent transparent !important; 
            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2-selection,
    .select2-container--default .${blockid}.select2-dropdown
    `, settings.shopengine_product_categories_dropdown_bg_color, (val) => {
        return (
            `
            background-color : ${val} !important;
            `
        )
    })

    
    cssHelper.add(`
    .select2-container--default .${blockid} :is(.select2-results__option--highlighted,
    .select2-results__option:hover)
    `, settings.shopengine_product_categories_dropdown_hover_color, (val) => {
        return (
            `
            color : ${val} !important;
            `
        )
    })


    cssHelper.add(`
    .select2-container--default .${blockid} :is(.select2-results__option--highlighted,
    .select2-results__option:hover)
    `, settings.shopengine_product_categories_dropdown_bg_hover_color, (val) => {
        return (
            `
            background-color : ${val} !important;

            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2-selection, .select2-dropdown.${blockid},
    .select2-container--default .${blockid} .select2-search--dropdown .select2-search__field,
    .select2-container--default .${blockid} .select2-search--dropdown .select2-search__field:focus

    `, settings.shopengine_product_categories_dropdown_border_type, (val) => {
        return (
            `
            border-style: ${val} !important;

            `
        )
    })


    cssHelper.add(`
    .shopengine-categories .select2-selection, .select2-dropdown.${blockid},
    .select2-container--default .${blockid} .select2-search--dropdown .select2-search__field,
    .select2-container--default .${blockid} .select2-search--dropdown .select2-search__field:focus

    `, settings.shopengine_product_categories_dropdown_border_width, (val) => {
        return (
            `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;

            `
        )
    })

    cssHelper.add(`
    .shopengine-categories .select2-selection, .select2-dropdown.${blockid},
    .select2-container--default .${blockid} .select2-search--dropdown .select2-search__field,
    .select2-container--default .${blockid} .select2-search--dropdown .select2-search__field:focus

    `, settings.shopengine_product_categories_dropdown_border_color, (val) => {
        return (
            `
            border-color: ${val} !important;

            `
        )
    })


    cssHelper.add(`.shopengine-categories > *`
    , settings.shopengine_product_categories_font_family, (val) => {
        return (
            `
            font-family: ${val.family};

            `
        )
    })

    return cssHelper.get()
}

export {Style}