const Style = ({ settings, breakpoints, cssHelper }) => {

	cssHelper.add('.shopengine-archive-description p', settings.shopengine_archive_description_align, (val) => (`
	text-align: ${val};
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_color, (val) => (`
	color: ${val};
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_font_family, (val) => (`
	font-family: ${val.family};
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_font_size, (val) => (`
	font-size: ${val}px;
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_font_weight, (val) => (`
	font-weight: ${val};
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_font_transform, (val) => (`
	text-transform: ${val};
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_font_style, (val) => (`
	font-style: ${val};
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_Line_height, (val) => (`
	line-height: ${val}px;
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_letter_spacing, (val) => (`
	letter-spacing: ${val}px;
	`)).add('.shopengine-archive-description p', settings.shopengine_archive_description_word_spacing, (val) => (`
	word-spacing: ${val}px;
	`));

	return cssHelper.get()
}

export { Style }