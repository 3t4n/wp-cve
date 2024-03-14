const Style = ({ settings, cssHelper }) => {
    const getObjectValues = (obj) => {
        return [...Object.values(obj)].toString();
    }


    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn', settings.shopengine_avatar_is_overlay, 
    (val) => (
        val && `visibility: hidden;opacity: 0;transition: opacity 0.5s ease-in-out;`
       
    ))

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar', settings.shopengine_avatar_content_alignmnet, 
    (val) => {
        return ` 
            justify-content: ${val}
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar', settings.shopengine_avatar_content_gap, (val) => {
        return ` 
            gap: ${val}px;
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail', settings.shopengine_avatar_image_width, (val) => {
        return ` 
            width: ${val}px;
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info', settings.shopengine_avatar_right_content_gap, (val) => {
        return ` 
            gap: ${val}px;
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--name', settings.shopengine_avatar_content_name_color, (val) => {
        return ` 
            color: ${val};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--name', settings.shopengine_avatar_content_name_typography, (val) => {
        return(` 
            font-size: ${val.fontSize};
            font-weight: ${val.fontWeight};
            text-transform: ${val.textTransform};
            font-style: ${val.fontStyle};
            text-decoration: ${val.textDecoration};
            line-height: ${val.lineHeight};
            letter-spacing: ${val.letterSpacing};
            word-spacing: ${val.wordSpacing};
        `)
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--email', settings.shopengine_avatar_content_email_color, (val) => {
        return ` 
            color: ${val};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--email', settings.shopengine_avatar_content_email_typography, (val) => {
        return ` 
            font-size: ${val.fontSize};
            font-weight: ${val.fontWeight};
            text-transform: ${val.textTransform};
            font-style: ${val.fontStyle};
            text-decoration: ${val.textDecoration};
            line-height: ${val.lineHeight};
            letter-spacing: ${val.letterSpacing};
            word-spacing: ${val.wordSpacing};
        `
    })
    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--btn', settings.shopengine_avatar_content_save_btn_text_color, (val) => {
        return ` 
            color: ${val};
        `
    })
    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--btn:hover', settings.shopengine_avatar_content_save_btn_text_color_hover, (val) => {
        return ` 
            color: ${val};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--btn', settings.shopengine_avatar_content_save_btn_bg, (val) => {       
        return ` 
            background-color: ${val};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--btn:hover', settings.shopengine_avatar_content_save_btn_bg_hover, (val) => {
        return ` 
            background-color: ${val};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--btn', settings.shopengine_avatar_content_save_btn_padding, (val) => {
        return ` 
            padding: ${getObjectValues(val).split(',').join(' ')};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--btn', settings.shopengine_avatar_content_save_btn_border_radius, (val) => {
        return ` 
            border-radius: ${getObjectValues(val).split(',').join(' ')};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__info--btn', settings.shopengine_avatar_content_save_btn_text_typography, (val) => {
        return ` 
            font-size: ${val.fontSize};
            font-weight: ${val.fontWeight};
            text-transform: ${val.textTransform};
            font-style: ${val.fontStyle};
            text-decoration: ${val.textDecoration};
            line-height: ${val.lineHeight};
            letter-spacing: ${val.letterSpacing};
            word-spacing: ${val.wordSpacing};
        `
    })

    cssHelper.add(`
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn i,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn svg,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn path `, 

    settings.shopengine_avatar_upload_btn_icon_width, (val) => {
        return ` 
            font-size: ${val}px;
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn', settings.shopengine_avatar_upload_btn_icon_color, (val) => {
        return ` 
            color: ${val};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn', settings.shopengine_avatar_upload_btn_background_color, (val) => {
        return ` 
            background-color: ${val};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn', settings.shopengine_avatar_upload_btn_border_radius, (val) => {
        return ` 
            border-radius: ${getObjectValues(val).split(',').join(' ')};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn', settings.shopengine_avatar_upload_btn_padding, (val) => {
        return ` 
            padding: ${getObjectValues(val).split(',').join(' ')};
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn', settings.shopengine_avatar_upload_btn_horizontal_position, (val) => {
        return ` 
            bottom: ${val}px;
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail--btn', settings.shopengine_avatar_upload_btn_vertical_position, (val) => {
        return ` 
            left: ${val}px;
        `
    })

    cssHelper.add('.shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close', settings.shopengine_avatar_close_btn_icon_color, (val) => {
        return ` 
            color: ${val};
        `
    })

    cssHelper.add(`
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close
    `, settings.shopengine_avatar_close_btn_bg_color, (val) => {
        return ` 
            background-color: ${val};
        `
    })

    cssHelper.add(`
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close i,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close svg
    `, settings.shopengine_avatar_close_btn_size, (val) => {
        return ` 
            width: ${val}px;
            height: ${val}px;
        `
    })
    
    cssHelper.add(`
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close i,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close svg
    `, 
    settings.shopengine_avatar_close_btn_icon_size, (val) => {
        return `
             width: ${val}px ;
             font-size: ${val}px;
        `
    })

    cssHelper.add(`
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close i,
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close svg,
    `, settings.shopengine_avatar_close_btn_padding, (val) => {
        return ` 
            padding: ${getObjectValues(val).split(',').join(' ')} ;
        `
    })

    cssHelper.add(`
    .shopengine-widget .shopengine-avatar-container .shopengine-avatar .shopengine-avatar__thumbnail .shopengine-avatar__thumbnail--overlay-close

    `, settings.shopengine_avatar_close_btn_border_radius, (val) => {
        return ` 
            border-radius: ${getObjectValues(val).split(',').join(' ')};
        `
    })


    return cssHelper.get()
}


export { Style }
