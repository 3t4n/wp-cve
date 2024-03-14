const Style = ({ settings, cssHelper }) => {

	cssHelper.add('.shopengine-archive-result-count', settings.shopengine_result_count_align, (val) => (`
		text-align: ${val};
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_color, (val) => (`
		color: ${val};
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_font_family, (val) => (`
		font-family: ${val.family};
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_font_size, (val) => (`
		font-size: ${val}px;
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_font_weight, (val) => (`
		font-weight: ${val};
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_font_style, (val) => (`
		font-style: ${val};
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_text_transform, (val) => (`
		text-transform: ${val};
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_line_height, (val) => (`
		line-height: ${val}px;
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_letter_spacing, (val) => (`
		letter-spacing: ${val}px;
	`)).add('.shopengine-archive-result-count .woocommerce-result-count', settings.shopengine_result_count_word_space, (val) => (`
		word-spacing: ${val}px;
	`));


	return cssHelper.get()
}


export { Style }