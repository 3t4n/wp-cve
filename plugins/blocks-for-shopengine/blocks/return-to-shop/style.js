
const Style = ({settings, breakpoints, cssHelper})=>{

    let boxShadow = {
        horizontal : settings.shopengine_button_box_shadow_horizontal.desktop,
        vertical : settings.shopengine_button_box_shadow_vertical.desktop,
        blur : settings.shopengine_button_box_shadow_blur.desktop,
        spread : settings.shopengine_button_box_shadow_spread.desktop,
        shadow: settings.shopengine_button_box_shadow_color.desktop.hex,
        position: settings.shopengine_button_box_shadow_position.desktop
    }

    cssHelper.add('.shopengine-return-to-shop .return-to-shop', settings.shopengine_button_alignment, (align) => {
        return (`
            text-align: ${align};
        `)
    } );
    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button', settings.font_family, (val) => {
        return(` 
            font-family: ${val.family};
        `)
    } );
    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button', settings.shopengine_typography_primary, (val) => {
        return (`
            font-size: ${val.fontSize};
            font-weight: ${val.fontWeight};
            text-transform: ${val.textTransform};
            line-height: ${val.lineHeight};
            word-spacing: ${val.wordSpacing};
        `)
    } );

    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button', settings.shopengine_button_color, (color) => {
        return (`
            color: ${color};
        `)
    } );

    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button', settings.shopengine_button_bg, (color) => {
        return (`
            background-color: ${color};
        `)
    } );
    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button', settings.shopengine_button_border, (border) => {
        if (border.left || border.right || border.top || border.bottom) {
            return (`
            border-left: ${border.left?.width} ${border.left?.style} ${border.left?.color};
            border-right: ${border.right?.width} ${border.right?.style} ${border.right?.color};
            border-bottom: ${border.bottom?.width} ${border.bottom?.style} ${border.bottom?.color};
            border-top: ${border.top?.width} ${border.top?.style} ${border.top?.color};
        `)
        }else{
            return(`
                border: ${border.width} ${border.style} ${border.color};
            `)
        }
    });

    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button', settings.shopengine_border_radius, (val) => {
        return (`
            border-radius: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    } );
    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button', settings.shopengine_button_padding, (padding) => {
        return (`
            padding: ${padding.top} ${padding.right} ${padding.bottom} ${padding.left};
        `)
    } );
    
    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button:hover', settings.shopengine_button_hover_color, (val) => {
        return (`
            color: ${val};
        `)
    } );

    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button:hover', settings.shopengine_button_hover_bg, (val) => {
        return (`
            background-color: ${val};
        `)
    } );

    cssHelper.add('.shopengine-return-to-shop .return-to-shop .button:hover', settings.shopengine_button_border_hover, (border) => {
        if (border.left || border.right || border.top || border.bottom) {
            return (`
            border-left: ${border.left?.width} ${border.left?.style} ${border.left?.color};
            border-right: ${border.right?.width} ${border.right?.style} ${border.right?.color};
            border-bottom: ${border.bottom?.width} ${border.bottom?.style} ${border.bottom?.color};
            border-top: ${border.top?.width} ${border.top?.style} ${border.top?.color};
        `)
        }else{
            return(`
                border: ${border.width} ${border.style} ${border.color};
            `)
        }
    } );

    cssHelper.add('.shopengine-return-to-shop .button', boxShadow, (val) => {
        return(`
            box-shadow: ${boxShadow.vertical || 0}px ${boxShadow.horizontal || 0}px ${boxShadow.blur || 0}px ${boxShadow.spread || 0}px ${boxShadow.shadow};
        `)
    } );

    return cssHelper.get()
}


export {Style}