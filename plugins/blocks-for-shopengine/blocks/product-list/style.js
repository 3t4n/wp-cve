const Style = ({ settings, cssHelper }) => {


	cssHelper.add('.badge.sale', settings.show_sale, (val) => (`
		display: ${val ? 'inline-block' : 'none'} !important;
	`)).add('.badge.off', settings.show_off, (val) => (`
		display: ${val ? 'inline-block' : 'none'} !important;
	`)).add('.badge.tag', settings.show_tag, (val) => (`
		display: ${val ? 'inline-block' : 'none'} !important;
	`))
	if (settings.badge_position.desktop === 'left') {
		cssHelper.add('.product-tag-sale-badge', {}, (val) => (`
			left: 10px;
			top: 10px;
		`))
	}
	else if (settings.badge_position.desktop === 'right') {
		cssHelper.add('.product-tag-sale-badge', {}, (val) => (`
			right: 10px;
			top: 10px;
		`))
	}
	else if (settings.badge_position.desktop === 'custom') {
		cssHelper.add('.product-tag-sale-badge', settings.badge_position_x, (val) => (`
		left: ${val}%;
		`)).add('.product-tag-sale-badge', settings.badge_position_y, (val) => (`
		top: ${val}%;
		`))
	}

	cssHelper.add('.shopengine-single-product-item', settings.content_alignment, (val) => (`
		text-align: ${val};
	`))

	cssHelper.add('.shopengine-single-product-item', settings.product_item_bg_color, (val) => (`
		background-color: ${val};
	`))

	cssHelper.add('.shopengine-widget .shopengine-product-list .product-list-grid', settings.product_item_column_gap, (val) => (`
		grid-column-gap: ${val}px;
	`))

	cssHelper.add('.shopengine-widget .shopengine-product-list .product-list-grid', settings.product_item_row_gap, (val) => (`
		grid-row-gap: ${val}px;
	`))

	cssHelper.add('.shopengine-widget .shopengine-product-list .product-list-grid', settings.products_column, (val) => (`
		grid-template-columns: repeat(${val}, 1fr);
	`))

	cssHelper.add('.shopengine-single-product-item', settings.product_wrap_padding, (val) => (`
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
	`))

	cssHelper.add('.shopengine-single-product-item', settings.product_wrap_border, (val) => {
		if(val.top || val.right || val.bottom || val.left){
			return(`
				border-top: ${val.top.width} ${val.top.style || 'none'} ${val.top.color}; 
				border-right: ${val.right.width} ${val.right.style || 'none'} ${val.right.color}; 
				border-bottom: ${val.bottom.width} ${val.bottom.style || 'none'} ${val.bottom.color}; 
				border-left: ${val.left.width} ${val.left.style || 'none'} ${val.left.color}; 
			`)
		}else{
			return(`
				border: ${val.width} ${val.style || 'none'} ${val.color};
			`)
		}
	})

	cssHelper.add('.product-thumb', settings.product_image_bg, (val) => (`
		background: ${val};
	`))

	cssHelper.add('.product-thumb', settings.product_image_margin, (val) => (`
		margin: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
	`))

	cssHelper.add('.product-tag-sale-badge .tag a, .product-tag-sale-badge .no-link', 
	settings.product_badge_typography, (val) => (`
		font-size: ${val.fontSize};
		font-weight: ${val.fontWeight};
		text-transform: ${val.textTransform};
		line-height: ${val.lineHeight};
		word-spacing: ${val.wordSpacing};
	`))

	cssHelper.add('.product-tag-sale-badge .tag a, .product-tag-sale-badge .no-link', settings.product_badge_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.product-tag-sale-badge .tag, .product-tag-sale-badge .no-link', settings.product_badge_bg, (val) => (`
		background: ${val};
	`))
	cssHelper.add('.product-tag-sale-badge .off', settings.product_percentage_badge_bg, (val) => (`
		background: ${val};
	`))
	cssHelper.add('.shopengine-single-product-item .product-tag-sale-badge .tag', settings.product_tag_badge_bg, (val) => (`
		background: ${val};
	`))
	cssHelper.add(`.product-tag-sale-badge ul`, settings.product_badge_space_between, (val) => (`
		gap: ${val}px;
	`))
	cssHelper.add('.product-tag-sale-badge ul li, .product-tag-sale-badge.align-vertical ul li', settings.product_badge_padding, (val) => (`
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
	`))
	cssHelper.add('.product-tag-sale-badge ul li, .product-tag-sale-badge.align-vertical ul li', settings.product_badge_margin, (val) => (`
		margin: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
	`))
	cssHelper.add(`
	.product-tag-sale-badge .tag,
	.product-tag-sale-badge .no-link,
	.product-tag-sale-badge .no-link.out-of-stock
	`, settings.badge_border, (val) => {
		if(val.top || val.right || val.bottom || val.left){
			return(`
				border-top: ${val.top.width} ${val.top.style || 'none'} ${val.top.color}; 
				border-right: ${val.right.width} ${val.right.style || 'none'} ${val.right.color}; 
				border-bottom: ${val.bottom.width} ${val.bottom.style || 'none'} ${val.bottom.color}; 
				border-left: ${val.left.width} ${val.left.style || 'none'} ${val.left.color}; 
			`)
		}else{
			return(`
				border: ${val.width} ${val.style || 'none'} ${val.color};
			`)
		}
	})
	cssHelper.add('.product-tag-sale-badge .tag, .product-tag-sale-badge .no-link', settings.product_badge_border_radius, (val) => (`
		border-radius: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
	`))

	cssHelper.add('.product-category ul li a', settings.product_category_typography, (val) => (`
		font-size: ${val.fontSize};
		font-weight: ${val.fontWeight};
		text-transform: ${val.textTransform};
		line-height: ${val.lineHeight};
		word-spacing: ${val.wordSpacing};
	`))
	cssHelper.add('.product-category ul li a', settings.product_category_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.product-category ul li a:hover', settings.product_category_hover_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.product-category', settings.product_category_padding, (val) => (`
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
	`))
	cssHelper.add('.product-title', settings.product_title_typography, (val) => (`
		font-size: ${val.fontSize};
		font-weight: ${val.fontWeight};
		text-transform: ${val.textTransform};
		line-height: ${val.lineHeight};
		word-spacing: ${val.wordSpacing};
	`))
	cssHelper.add('.product-title a', settings.product_title_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.product-title a:hover', settings.product_title_hover_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.product-title', settings.product_title_padding, (val) => (`
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
		margin: 0;
	`))
	cssHelper.add('.product-rating .star-rating', settings.product_rating_star_size, (val) => (`
		font-size: ${val}px;
	`))
	cssHelper.add('.product-rating .star-rating span::before', settings.product_rating_star_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.product-rating .star-rating::before', settings.product_rating_empty_star_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.rating-count', settings.product_rating_count_color, (val) => (`
		color: ${val};
	`))
	cssHelper.add('.shopengine-product-list .overlay-add-to-cart', settings.shopengine_show_product_hover, (val) => (`
		display: ${val ? 'flex' : 'none'};
	`))
	cssHelper.add('.shopengine-product-list .product-price .price', settings.shopengine_product_price_alignment, (val) => (`
		justify-content: ${val};
	`))
	cssHelper.add('.shopengine-product-list .product-price .price .shopengine-discount-badge', settings.shopengine_show_price_off_tag, (val) => (`
		display: ${val ? 'inline-block' : 'none'};
	`))
	cssHelper.add('.shopengine-product-list .product-category', settings.shopengine_show_category, (val) => (`
		display: ${val ? 'block' : 'none'};
	`))
	cssHelper.add('.shopengine-product-list .product-rating', settings.shopengine_show_rating, (val) => (`
		display: ${val ? 'block' : 'none'};
	`))

	cssHelper.add('.product-rating', settings.product_rating_padding, (val) => (`
		line-height: 0; 
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left}
	`))

	cssHelper.add('.product-rating .rating-count', settings.product_rating_count_typography, (val) => (`
		font-size: ${val.fontSize};
		font-weight: ${val.fontWeight};
		text-transform: ${val.textTransform};
		line-height: ${val.lineHeight};
		word-spacing: ${val.wordSpacing};
	`))

	cssHelper.add('.product-price .price, .product-price .amount, .product-price bdi', settings.product_price_price_color, (val) => (`
		color: ${val}
	`))
	cssHelper.add('.product-price .price del, .product-price .price del .woocommerce-Price-amount bdi', settings.product_price_sale_price_color, (val) => (`
		color: ${val}
	`))
	
	cssHelper.add('.product-price .price', settings.product_price_typography, (val) => (`
		font-size: ${val.fontSize};
		font-weight: ${val.fontWeight};
		text-transform: ${val.textTransform};
		line-height: ${val.lineHeight};
		word-spacing: ${val.wordSpacing};
	`))
	
	cssHelper.add('.shopengine-product-list .product-price .price ins', settings.product_price_space_between, (val) => (`
		margin-right: ${val}px
	`))
	
	cssHelper.add('.shopengine-product-list .product-price .price .shopengine-discount-badge', settings.product_price_discount_badge_color, (val) => (`
		color: ${val}
	`))
	
	cssHelper.add('.shopengine-product-list .product-price .price .shopengine-discount-badge', settings.product_price_discount_badge_bg_color, (val) => (`
		background-color: ${val}
	`))
	
	cssHelper.add('.shopengine-product-list .product-price .price .shopengine-discount-badge', settings.product_price_discount_badge_typography, (val) => (`
		font-size: ${val.fontSize};
		font-weight: ${val.fontWeight};
		text-transform: ${val.textTransform};
		line-height: ${val.lineHeight};
		word-spacing: ${val.wordSpacing};
	`))
	
	cssHelper.add('.shopengine-product-list .product-price .price .shopengine-discount-badge', settings.product_price_discount_badge_padding, (val) => (`
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left}
	`))
	
	cssHelper.add('.shopengine-product-list .product-price .price .shopengine-discount-badge', settings.product_price_discount_badge_margin, (val) => (`
		margin: ${val.top}  ${val.right}  ${val.bottom}  ${val.left}
	`))
	
	cssHelper.add('.product-price', settings.product_price_wrap_padding, (val) => (`
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left}
	`))
	
	cssHelper.add('.overlay-add-to-cart a::before,.overlay-add-to-cart a::after', settings.product_hover_overlay_color, (val) => (`
		color: ${val}
	`))

	cssHelper.add('.shopengine-single-product-item .overlay-add-to-cart a', settings.product_hover_overlay_bg_color, (val) => (`
		background-color: ${val};
	`))
	
	cssHelper.add(`
	.overlay-add-to-cart a.active::before,
	.overlay-add-to-cart a.added::before,
	.overlay-add-to-cart a.loading::after,
	.overlay-add-to-cart a:hover::before,
	.overlay-add-to-cart a:hover::after
	`, settings.product_hover_overlay_hover_color, (val) => (`
		color: ${val}
	`))

	cssHelper.add(`
	.overlay-add-to-cart a.active,
	.overlay-add-to-cart a:hover,
	.overlay-add-to-cart a:hover
	`, settings.product_hover_overlay_hover_bg_color, (val) => (`
		background: ${val} !important
	`))
	cssHelper.add('.overlay-add-to-cart a::before, .overlay-add-to-cart a::after', settings.product_hover_overlay_font_size, (val) => (`
		font-size: ${val}px
	`))
	
	cssHelper.add('.overlay-add-to-cart a', settings.product_hover_overlay_padding, (val) => (`
		padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left}
	`))
	
	cssHelper.add('.overlay-add-to-cart.position-bottom a:not(:last-child)', settings.product_hover_overlay_item_space_between, (val) => (`
		margin-right: ${val}px
	`))
	
	cssHelper.add('.overlay-add-to-cart.position-left a:not(:last-child)', settings.product_hover_overlay_item_space_between, (val) => (`
		margin-bottom: ${val}px
	`))
	
	cssHelper.add('.overlay-add-to-cart.position-right a:not(:last-child)', settings.product_hover_overlay_item_space_between, (val) => (`
		margin-bottom: ${val}px
	`))
	
	cssHelper.add('.overlay-add-to-cart.position-center a:not(:nth-child(2n))', settings.product_hover_overlay_item_space_between, (val) => (`
		margin-right: ${val}px
	`))
	
	cssHelper.add('.overlay-add-to-cart.position-center a:not(:nth-child(1), :nth-child(2))', settings.product_hover_overlay_item_space_between, (val) => (`
		margin-top: ${val}px
	`))
	
	cssHelper.add('.shopengine-single-product-item .product-thumb .overlay-add-to-cart', settings.product_hover_overlay_border, (val) => {
		if(val.top || val.right || val.bottom || val.left){
			return(`
				border-top: ${val.top.width} ${val.top.style || 'none'} ${val.top.color}; 
				border-right: ${val.right.width} ${val.right.style || 'none'} ${val.right.color}; 
				border-bottom: ${val.bottom.width} ${val.bottom.style || 'none'} ${val.bottom.color}; 
				border-left: ${val.left.width} ${val.left.style || 'none'} ${val.left.color}; 
			`)
		}else{
			return(`
				border: ${val.width} ${val.style || 'none'} ${val.color};
			`)
		}
	})
	
	cssHelper.add('.overlay-add-to-cart', settings.product_hover_overlay_border_radius, (val) => (`
		border-radius: ${val.top}  ${val.right}  ${val.bottom}  ${val.left}
	`))
	
	cssHelper.add('.overlay-add-to-cart', settings.product_hover_overlay_margin, (val) => (`
		margin: ${val.top}  ${val.right}  ${val.bottom}  ${val.left}
	`))

	if (settings.badge_align.desktop == 'vertical') {
		cssHelper.add('.product-tag-sale-badge ul', settings.product_rating_star_color, (val) => (`
			flex-direction: column;
		`));
	}

	cssHelper.add(`
	.product-tag-sale-badge .tag a,
	.product-tag-sale-badge .no-link,
	.product-category ul li a,
	.product-title,
	.rating-count,
	.product-price .price,
	.shopengine-product-list .product-price .price .shopengine-discount-badge
	`, settings.shopengine_global_font_family, (val) => (`
			font-family : ${val.family};
	`));

	return cssHelper.get()
}


export { Style };

