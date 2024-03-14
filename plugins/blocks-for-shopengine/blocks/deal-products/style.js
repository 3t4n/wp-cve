const Style = ({ settings, cssHelper }) => {

    //this will return object values as a string separated by comma
    const getObjectValues = (obj) => {
        return [...Object.values(obj)].toString();
    }


    cssHelper.add('.shopengine-deal-products-widget .deal-products-container', settings.shopengine_product_column_count, (val) => (`
	grid-template-columns: repeat(${val},1fr);
    `)).add('.shopengine-deal-products-widget .deal-products-container', settings.shopengine_product_column_gap, (val) => (`
	column-gap: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products-container', settings.shopengine_product_row_gap, (val) => (`
	row-gap: ${val}px;
    `));

    cssHelper.add('.shopengine-deal-products-widget .deal-products', settings.shopengine_product_wrapper_padding, (val) => (`
	padding: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .deal-products', settings.shopengine_product_wrapper_background, (val) => (`
	background-color: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products', settings.shopengine_product_wrapper_border_width, (val) => (`
	border-width: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .deal-products', settings.shopengine_product_wrapper_border_color, (val) => (`
	border-color: ${val};
    `));

    cssHelper.add('.shopengine-deal-products-widget .deal-products__top--img', settings.shopengine_product_image_height, (val) => (`
	height: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__top--img', settings.shopengine_product_image_size, (val) => (`
	object-fit: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__top--img', settings.shopengine_product_image_position, (val) => (`
	object-position: ${val};
    `));

    cssHelper.add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_padding, (val) => (`
	padding: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_border_radius, (val) => (`
	border-radius: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_position_left, (val) => (`
	left: ${val}%;
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_position_top, (val) => (`
	top: ${val}%;
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_position_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_position_background, (val) => (`
	background-color: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-offer-badge', settings.shopengine_percentage_badge_wordspace, (val) => (`
	word-spacing: ${val}px;
    `));

    cssHelper.add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_padding, (val) => (`
	padding: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_border_radius, (val) => (`
	border-radius: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_position_left, (val) => (`
	left: ${val}%;
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_position_top, (val) => (`
	top: ${val}%;
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_position_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_position_background, (val) => (`
	background-color: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_font_style, (val) => (`
	font-style: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_text_transform, (val) => (`
	text-transform: ${val};
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_line_height, (val) => (`
	line-height: ${val}px;
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_letter_spacing, (val) => (`
	letter-spacing: ${val}px;
    `)).add('.shopengine-deal-products-widget .shopengine-sale-badge', settings.shopengine_sale_badge_wordspace, (val) => (`
	word-spacing: ${val}px;
    `));

    cssHelper.add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_number_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_number_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_number_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_number_wordspace, (val) => (`
	word-spacing: ${val}px;
    `)).add('.shopengine-countdown-clock .se-clock-item span:last-child', settings.shopengine_countdown_clock_label_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_label_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_label_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_label_transform, (val) => (`
	text-transform: ${val};
    `)).add('.shopengine-countdown-clock .se-clock-item span:first-child', settings.shopengine_countdown_clock_label_wordspace, (val) => (`
	word-spacing: ${val}px;
    `));

    cssHelper.add('.shopengine-countdown-clock .se-clock-item', settings.shopengine_countdown_clock_background, (val) => (`
	background-color: ${val};
    `)).add('.shopengine-countdown-clock .se-clock-item', settings.shopengine_countdown_clock_border_color, (val) => (`
	border-color: ${val};
    `)).add('.shopengine-countdown-clock .se-clock-item', settings.shopengine_countdown_clock_border_width, (val) => (`
	border-width: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-countdown-clock .se-clock-item', settings.shopengine_countdown_clock_padding, (val) => (`
	padding: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-countdown-clock', settings.shopengine_countdown_clock_space_bottom, (val) => (`
	bottom: ${val}px;
    `)).add('.shopengine-countdown-clock', settings.shopengine_countdown_clock_countdown_wrapper_width, (val) => (`
	width: ${val}%;
    `));

    cssHelper.add('.shopengine-deal-products-widget .deal-products__desc--name a', settings.shopengine_content_style_title_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__desc--name a:hover', settings.shopengine_content_style_title_hover_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__desc--name a', settings.shopengine_content_style_title_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__desc--name a', settings.shopengine_content_style_title_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__desc--name a', settings.shopengine_content_style_title_transform, (val) => (`
	text-transform: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__desc--name a', settings.shopengine_content_style_title_wordspace, (val) => (`
	word-spacing: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__desc--name a', settings.shopengine_content_style_title_margin, (val) => (`
	margin: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .deal-products__prices', settings.shopengine_content_style_title_row_margin, (val) => (`
	margin: ${getObjectValues(val).split(',').join(' ')};
    `)).add('.shopengine-deal-products-widget .deal-products__prices del .amount', settings.shopengine_content_style_regular_price_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__prices del .amount', settings.shopengine_content_style_regular_price_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__prices del .amount', settings.shopengine_content_style_regular_price_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__prices del .amount', settings.shopengine_content_style_regular_price_transform, (val) => (`
	text-transform: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__prices del .amount', settings.shopengine_content_style_regular_price_wordspace, (val) => (`
	word-spacing: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__prices ins .amount', settings.shopengine_content_style_sale_price_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__prices ins .amount', settings.shopengine_content_style_sale_price_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__prices ins .amount', settings.shopengine_content_style_sale_price_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__prices ins .amount', settings.shopengine_content_style_sale_price_transform, (val) => (`
	text-transform: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__prices ins .amount', settings.shopengine_content_style_sale_price_wordspace, (val) => (`
	word-spacing: ${val}px;
    `));


    cssHelper.add('.shopengine-deal-products-widget .deal-products__grap__sells span', settings.shopengine_stock_progress_text_color, (val) => (`
	color: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__grap__sells > div', settings.shopengine_stock_progress_text_font_size, (val) => (`
	font-size: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__grap__sells > div', settings.shopengine_stock_progress_text_font_weight, (val) => (`
	font-weight: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__grap__sells > div', settings.shopengine_stock_progress_text_transform, (val) => (`
	text-transform: ${val};
    `)).add('.shopengine-deal-products-widget .deal-products__grap__sells > div', settings.shopengine_stock_progress_text_line_height, (val) => (`
	line-height: ${val}px;
    `)).add('.shopengine-deal-products-widget .deal-products__grap__sells > div', settings.shopengine_stock_progress_text_wordspace, (val) => (`
	word-spacing: ${val}px;
    `));

    cssHelper.add(`.shopengine-countdown-clock .se-clock-item, .shopengine-deal-products-widget .deal-products__desc--name a,
    .shopengine-deal-products-widget .deal-products__prices .amount, .shopengine-deal-products-widget .deal-products__grap__sells,
    .shopengine-deal-products-widget .shopengine-sale-badge, .shopengine-deal-products-widget .shopengine-offer-badge`, settings.shopengine_font_family, (val) => (`
	font-family: ${val.family};
    `));

    return cssHelper.get()
}


export { Style }