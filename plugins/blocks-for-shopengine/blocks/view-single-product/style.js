
const Style = ({settings, breakpoints, cssHelper})=>{
    const getObjectValues = (obj) => {
        return [...Object.values(obj)].toString();
    }
    const getShadowValues = (obj) => {
        const position = obj.position ==='inset' ? obj.position : ''
        let propSet = getObjectValues(Object.fromEntries(Object.entries(obj).slice(1, Object.keys(obj).length - 1)));
        return `${position} ${propSet.split(',').join('px ')+'px'} rgba(${getObjectValues(obj.color.rgb).split(' ').join(',')})`;
    }


    const shopengine_list_normal_shadow = {
        position: settings.shopengine_button_box_shadow_position.desktop,
        horizontal: settings.shopengine_button_box_shadow_horizontal.desktop,
        vertical: settings.shopengine_button_box_shadow_vertical.desktop,
        blur: settings.shopengine_button_box_shadow_blur.desktop,
        spread: settings.shopengine_button_box_shadow_spread.desktop,
        color: settings.shopengine_button_box_shadow_color.desktop
    }

    cssHelper.add('.shopengine-view-single-product .view-single-product',settings.shopengine_button_align, val=> (`text-align: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button:hover',{}, val=> (`color: #fff`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_typography_primary_font_size, val=> (`font-size: ${val}px`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_typography_primary_font_weight, val=> (`font-weight: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_typography_primary_text_transform, val=> (`text-transform: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_typography_primary_line_height, val=> (`line-height: ${val}px`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_typography_primary_wordspace, val=> (`word-spacing: ${val}px`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_button_text_color, val=> (`color: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_button_text_bg_color, val=> (`background-color: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_input_border_type, val=> (`border-style: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_input_border_width, val=> (`border-width: ${getObjectValues(val).split(',').join(' ')}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_input_border_color, val=> (`border-color: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_button_border_radius, val=> (`border-radius: ${getObjectValues(val).split(',').join(' ')}`) )
    cssHelper.add('.shopengine-view-single-product .button',settings.shopengine_button_text_padding, val=> (`padding: ${getObjectValues(val).split(',').join(' ')}`) )
    cssHelper.add('.shopengine-view-single-product .button',{desktop: shopengine_list_normal_shadow}, val=> (`box-shadow: ${getShadowValues(val)}`) )
    cssHelper.add('.shopengine-view-single-product .button:hover',settings.shopengine_button_hover_text_color, val=> (`color: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button:hover',settings.shopengine_button_hover_text_bg_color, val=> (`background-color: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button:hover',settings.shopengine_input_hover_border_type, val=> (`border-style: ${val}`) )
    cssHelper.add('.shopengine-view-single-product .button:hover',settings.shopengine_input_hover_border_width, val=> (`border-width: ${getObjectValues(val).split(',').join(' ')}`) )
    cssHelper.add('.shopengine-view-single-product .button:hover',settings.shopengine_input_hover_border_color, val=> (`border-color: ${val}`) )

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

export {Style}