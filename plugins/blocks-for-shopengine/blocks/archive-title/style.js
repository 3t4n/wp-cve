const Style = ({ settings, cssHelper }) => {


    cssHelper.add('.shopengine-archive-title', settings.shopengine_archive_title_align, (val) => (`
	text-align: ${val};
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_archive_title_color, (val) => (`
    color: ${val};
    
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_font_family, (val) => (`
    font-family: ${val.family};
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_font_size, (val) => (`
    font-size: ${val}px;
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_font_weight, (val) => (`
    font-weight: ${val};
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_font_style, (val) => (`
    font-style: ${val};
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_text_transform, (val) => (`
    text-transform: ${val};
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_line_height, (val) => (`
    line-height: ${val}px;
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_letter_spacing, (val) => (`
    letter-spacing: ${val}px;
    `)).add('.shopengine-archive-title .archive-title', settings.shopengine_archive_title_wordspace, (val) => (`
    word-spacing: ${val}px;
    `));

    return cssHelper.get()
}


export { Style }