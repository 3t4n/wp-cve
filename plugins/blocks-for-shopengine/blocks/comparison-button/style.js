
const Style = ({ settings, breakpoints, cssHelper }) => {

    cssHelper.add(`
    .shopengine-comparison-button
    `, settings.shopengine_comparison_btn_alignment, (val) => {
        return (`
        text-align: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_width, (val) => {
        return (`
        width : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_padding, (val) => {
        return (`
        padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_font_size, (val) => {
        return (`
        font-size: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_font_weight, (val) => {
        return (`
        font-weight: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_font_style, (val) => {
        return (`
        font-style: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_text_transform, (val) => {
        return (`
        text-transform: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_line_height, (val) => {
        return (`
        line-height: ${val};
        `)
    });


    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_letter_spacing, (val) => {
        return (`
        letter-spacing: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_word_spacing, (val) => {
        return (`
        word-spacing: ${val};
        `)
    });

    let textShadowColor = settings.shopengine_comparison_btn_shadow_color.desktop.rgb;
    let textShadowBlur = settings.shopengine_comparison_btn_shadow_blur.desktop || '0px';
    let textShadowHorizontal = settings.shopengine_comparison_btn_shadow_horizontal.desktop || '0px';
    let textShadowVertical = settings.shopengine_comparison_btn_shadow_vertical.desktop || '0px';

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, {}, (val) => {
        return (`
        text-shadow : ${textShadowHorizontal} ${textShadowVertical} ${textShadowBlur} rgba(${textShadowColor.r || 0} , ${textShadowColor.g || 0} , ${textShadowColor.b || 0} , ${textShadowColor.a || 1});
        `)
    });


    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_text_color, (val) => {
        return (`
        color: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button path
    `, settings.shopengine_comparison_btn_text_color, (val) => {
        return (`
        stroke : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_bg_color, (val) => {
        return (`
        background-color : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button:hover
    `, settings.shopengine_comparison_btn_hover_color, (val) => {
        return (`
        color : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button:hover svg path
    `, settings.shopengine_comparison_btn_hover_color, (val) => {
        return (`
        stroke : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button:hover
    `, settings.shopengine_comparison_btn_bg_hover_color, (val) => {
        return (`
        background-color: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_border_style, (val) => {
        return (`
        border-style : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_border_dimensions, (val) => {
        return (`
        border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_border_color, (val) => {
        return (`
        border-color : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, settings.shopengine_comparison_btn_border_radius, (val) => {
        return (`
        border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button:hover
    `, settings.shopengine_comparison_btn_hover_border_color, (val) => {
        return (`
        border-color : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button:hover
    `, settings.shopengine_comparison_btn_border_radius_hover, (val) => {
        return (`
        border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    });

    let box_shadow_color = settings.shopengine_comparison_btn_box_shadow_color.desktop.rgb;
    let box_shadow_horizontal = settings.shopengine_comparison_btn_box_shadow_horizontal.desktop || "0px";
    let box_shadow_vertical = settings.shopengine_comparison_btn_box_shadow_vertical.desktop || "0px";
    let box_shadow_blur = settings.shopengine_comparison_btn_box_shadow_blur.desktop || "0px";
    let box_shadow_spread = settings.shopengine_comparison_btn_box_shadow_spread.desktop || "0px";
    let box_shadow_position = settings.shopengine_comparison_btn_box_shadow_position.desktop || "";



    cssHelper.add(`
    .shopengine-comparison-button .comparison-button
    `, {}, (val) => {
        return (`
        box-shadow : ${box_shadow_horizontal} ${box_shadow_vertical} ${box_shadow_blur} ${box_shadow_spread} rgba(${box_shadow_color.r || 0}, ${box_shadow_color.g|| 0} , ${box_shadow_color.b|| 0} , ${box_shadow_color.a|| 1}) ${box_shadow_position};
        `)
    });


    cssHelper.add(`
    .shopengine-comparison-button .comparison-button > i
    `, settings.shopengine_comparison_btn_normal_icon_font_size, (val) => {
        return (`
        font-size : ${val};
        `)
    });
    
    cssHelper.add(`
    .shopengine-comparison-button .comparison-button > svg
    `, settings.shopengine_comparison_btn_normal_icon_font_size, (val) => {
        return (`
        max-width : ${val};
        `)
    });


    cssHelper.add(`
    .shopengine-comparison-button .comparison-button > i,
    .shopengine-comparison-button .comparison-button > svg
    `, settings.shopengine_comparison_btn_normal_icon_padding_left, (val) => {
        return (`
        margin-right : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button > i,
    .shopengine-comparison-button .comparison-button > svg
    `, settings.shopengine_comparison_btn_normal_icon_padding_right, (val) => {
        return (`
        margin-left : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button > i,
    .shopengine-comparison-button .comparison-button > svg
    `, settings.shopengine_comparison_btn_normal_icon_vertical_align, (val) => {
        return (`
        -webkit-transform: translateY(${val}); 
        -ms-transform: translateY(${val}); 
        transform: translateY(${val});
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_top_position, (val) => {
        return (`
        top : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_right_position, (val) => {
        return (`
        right : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_bottom_position, (val) => {
        return (`
        bottom : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_left_position, (val) => {
        return (`
        left : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_size, (val) => {
        return (`
        width: ${val}; 
        height: ${val}; 
        line-height: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_font_size, (val) => {
        return (`
        font-size: ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_text_color, (val) => {
        return (`
        color : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_bg_color, (val) => {
        return (`
        background-color : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_border_type, (val) => {
        return (`
        border-style : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_border_width, (val) => {
        return (`
        border-Width : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_border_color, (val) => {
        return (`
        border-color : ${val};
        `)
    });

    cssHelper.add(`
    .shopengine-comparison-button .comparison-button .comparison-counter-badge
    `, settings.shopengine_comparison_btn_badge_border_radius, (val) => {
        return (`
        border-radius : ${val.top} ${val.right} ${val.bottom} ${val.left};
        `)
    });

    return cssHelper.get()
}

export { Style }