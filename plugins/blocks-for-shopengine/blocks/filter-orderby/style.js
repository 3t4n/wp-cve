
const Style = ({ settings, breakpoints, cssHelper }) => {
    const {
        shopengine_orderby_height,
        shopengine_font_size,
        shopengine_font_weight,
        shopengine_text_transform,
        shopengine_line_height,
        shopengine_word_spacing,
        shopengine_orderby_color,
        shopengine_orderby_bg_color,
        shopengine_orderby_border,
        shopengine_orderby_border_width,
        shopengine_orderby_border_color,
        shopengine_orderby_hover_color,
        shopengine_orderby_hover_bg_color,
        shopengine_orderby_hover_border,
        shopengine_orderby_radius,
        shopengine_orderby_padding
    } = settings;
    
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group label', {}, (val) => {
        return `
        padding-left: 10px;
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_height, (val) => {
        return `
            height: ${val}px;
        `
    } );

    cssHelper.add('.shopengine-filter-orderby, .shopengine-filter-orderby .orderby', settings.shopengine_font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby, .shopengine-filter-orderby .orderby', shopengine_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-filter-orderby, .shopengine-filter-orderby .orderby', shopengine_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_font_weight, (val) => {
        return `
            font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby, .shopengine-filter-orderby .orderby', shopengine_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby, .shopengine-filter-orderby .orderby', shopengine_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_line_height, (val) => {
        return `
            line-height: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-filter-orderby, .shopengine-filter-orderby .orderby', shopengine_word_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_word_spacing, (val) => {
        return `
            letter-spacing: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-filter-orderby', shopengine_orderby_color, (val) => {
        return `
            color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_border, (val) => {
        return `
            border-style: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby:after', shopengine_orderby_border, (val) => {
        return `
            border-style: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_border, (val) => {
        return `
            border-style: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_border_width, (val) => {
        return `
            border-top-width: ${val.top};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_border_width, (val) => {
        return `
            border-top-width: ${val.top};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_border_width, (val) => {
        return `
            border-bottom-width: ${val.bottom};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby:after', shopengine_orderby_border_width, (val) => {
        return `
            border-bottom-width: ${val.bottom};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_border_width, (val) => {
        return `
            border-bottom-width: ${val.bottom};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_border_width, (val) => {
        return `
            border-left-width: ${val.left};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby:after', shopengine_orderby_border_width, (val) => {
        return `
            border-left-width: ${val.left};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_border_width, (val) => {
        return `
            border-left-width: ${val.left};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_border_width, (val) => {
        return `
            border-right-width: ${val.right};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby:after', shopengine_orderby_border_width, (val) => {
        return `
            border-right-width: ${val.right};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_border_width, (val) => {
        return `
            border-right-width: ${val.right};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_border_color, (val) => {
        return `
            border-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby:after', shopengine_orderby_border_color, (val) => {
        return `
            border-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_border_color, (val) => {
        return `
            border-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby:hover', shopengine_orderby_hover_color, (val) => {
        return `
            color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby:hover + label', shopengine_orderby_hover_color, (val) => {
        return `
            color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby:checked + label', shopengine_orderby_hover_color, (val) => {
        return `
            color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby:hover', shopengine_orderby_hover_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group:hover', shopengine_orderby_hover_bg_color, (val) => {
        return `
            background-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby:hover:after, .shopengine-filter-orderby .orderby-input-group:hover,.shopengine-filter-orderby .orderby:hover', shopengine_orderby_hover_border, (val) => {
        return `
            border-color: ${val};
        `
    } );

    cssHelper.add('.shopengine-filter-orderby .orderby, .shopengine-filter-orderby .orderby-input-group', shopengine_orderby_radius, (val) => {
        return `
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_padding, (val) => {
        return `
            padding-top: ${val.top};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_padding, (val) => {
        return `
            padding-top: ${val.top};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_padding, (val) => {
        return `
            padding-bottom: ${val.bottom};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_padding, (val) => {
        return `
            padding-bottom: ${val.bottom};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_padding, (val) => {
        return `
            padding-left: ${val.left};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_padding, (val) => {
        return `
            padding-left: ${val.left};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby', shopengine_orderby_padding, (val) => {
        return `
            padding-right: ${val.right};
        `
    } );
    cssHelper.add('.shopengine-filter-orderby .orderby-input-group', shopengine_orderby_padding, (val) => {
        return `
            padding-right: ${val.right};
        `
    } );


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