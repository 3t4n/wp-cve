
const Style = ({ settings, breakpoints, cssHelper }) => {
    const {
        shopengine_font_size,
        shopengine_font_weight,
        shopengine_line_height,
        shopengine_word_spacing,
        shopengine_ppp_spacing,
        shopengine_ppp_alignment,
        shopengine_ppp_color,
        shopengine_ppp_active_color
    } = settings;

    cssHelper.add('.shopengine-products-per-page label', settings.shopengine_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    } )
    cssHelper.add('.shopengine-products-per-page label', shopengine_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-products-per-page label', shopengine_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    } )
    cssHelper.add('.shopengine-products-per-page label', shopengine_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-products-per-page label', shopengine_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-products-per-page label:not(:last-child)', shopengine_ppp_spacing, (val) => {
        return `
            margin-right: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-products-per-page label:after', shopengine_ppp_spacing, (val) => {
        return `
            margin-left: ${val}px;
        `
    } )
    cssHelper.add('.shopengine-products-per-page', shopengine_ppp_alignment, (val) => {
        return `
            display: flex;
            justify-content: ${val}; 
        `
    } )
    cssHelper.add('.shopengine-products-per-page label', shopengine_ppp_color, (val) => {
        return `
            color: ${val};
        `
    } )
    cssHelper.add('.shopengine-products-per-page input:checked + span', shopengine_ppp_active_color, (val) => {
        return `
            color: ${val};
        `
    } )
       

    return cssHelper.get()
}

/*
const Style = ({settings, breakpoints})=>{
    // cssHelper.media(`(min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px)`)
    // .add('.class-a', 'color', '#000')
    // .add('.class-b', 'color', settings.autocontrol1)

    var cssOutput = '';

    var cssOutput = `
        #${settings.blockId} .class-a{
            color: #ab0;
        }
        #${settings.blockId} .class-b{
            color: ${settings.color};
        }
    `

    // we can apply conditional styles and concat them
    cssOutput += `

    @media (min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px){
        #${settings.blockId} .class-a{
            color: #000;
        }
        #${settings.blockId} .class-b{
            color: ${settings.autocontrol1};
        }
    }
    `

    return cssOutput
}
*/

export { Style }