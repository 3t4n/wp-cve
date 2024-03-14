
const Style = ({ settings, breakpoints, cssHelper }) => {

    cssHelper.add(`
    .shopengine-vacation-module-container
    `, settings.shopengine_vacation_content_bg, (val) => (`
        background-color: ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container
    `, settings.shopengine_vacation_content_padding, (val) => (`
        padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_holiday_title_color, (val) => (`
        color : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_holiday_title_font_size, (val) => (`
        font-size : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_holiday_title_font_weight, (val) => (`
        font-weight : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_holiday_title_text_transform, (val) => (`
        text-transform : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_holiday_title_line_height, (val) => (`
        line-height : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_holiday_title_letter_spacing, (val) => (`
        letter-spacing : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_holiday_title_word_spacing, (val) => (`
        word-spacing : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-header h1
    `, settings.shopengine_vacation_margin_bottom, (val) => (`
        margin-bottom : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container p
    `, settings.shopengine_vacation_message_color, (val) => (`
        color : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container p
    `, settings.shopengine_vacation_message_font_size, (val) => (`
        font-size : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container p
    `, settings.shopengine_vacation_message_font_weight, (val) => (`
        font-weight : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container p
    `, settings.shopengine_vacation_message_text_transform, (val) => (`
        text-transform : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container p
    `, settings.shopengine_vacation_message_letter_spacing, (val) => (`
        letter-spacing : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container p
    `, settings.shopengine_vacation_message_word_spacing, (val) => (`
        word-spacing : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer h6
    `, settings.shopengine_mail_title_color, (val) => (`
        color : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer h6
    `, settings.shopengine_mail_title_font_size, (val) => (`
        font-size : ${val};
    `))


    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer h6
    `, settings.shopengine_mail_title_font_weight, (val) => (`
        font-weight : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer h6
    `, settings.shopengine_mail_title_line_height, (val) => (`
        line-height : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer h6
    `, settings.shopengine_mail_title_letter_spacing, (val) => (`
        letter-spacing : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer h6
    `, settings.shopengine_mail_title_word_spacing, (val) => (`
        word-spacing : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer .vacation-holidays button
    `, settings.shopengine_vacation_button_color, (val) => (`
        color: ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer .vacation-holidays button
    `, settings.shopengine_vacation_button_bg, (val) => (`
        background-color : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer .vacation-holidays button
    `, settings.shopengine_vacation_button_padding, (val) => (`
        padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer .vacation-emergency p a
    `, settings.shopengine_vacation_mail_color, (val) => (`
        color : ${val};
    `))

    cssHelper.add(`
    .shopengine-vacation-module-container .shopengine-vacation-module-footer .vacation-emergency p a:hover
    `, settings.shopengine_vacation_mail_hover, (val) => (`
        color : ${val};
    `))





    return cssHelper.get()
}

export { Style }