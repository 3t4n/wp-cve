function wpppfm_convertPromotionToAttribute( promotions ) {
	var promotionsString = JSON.stringify( promotions );
	return [ new Wppfm_AttributeMeta( 'promotions', promotionsString ) ];
}

function wpppfm_getPromotionData( promotions ) {
	var promotionData = [];
	var promotionIds = ['promotion_id', 'product_applicability', 'offer_type', 'long_title', 'promotion_effective_start_date', 'promotion_effective_end_date', 'redemption_channel', 'promotion_destination',
		'product_filter_selector_include', 'product_filter_selector_exclude', 'minimum_purchase_amount', 'buy_this_quantity', 'percent_off', 'money_off_amount', 'get_this_quantity_discounted', 'free_shipping', 'free_gift_value',
		'free_gift_description', 'free_gift_item_id', 'coupon_value_type', 'limit_quantity', 'limit_value', 'promotion_display_dates', 'description', 'image_link', 'fine_print', 'promotion_price',];

	promotions.forEach( function ( promotion ) {
		var promotionDataItem = [];
		promotionIds.forEach( function ( promotionId ) {
			var item = promotion.find( obj => obj.meta_key === promotionId);
			var o = {};
			o.key = promotionId.replaceAll( '_', '-' );
			o.value = item && item.hasOwnProperty('meta_value') ? promotion.find( obj => obj.meta_key === promotionId).meta_value : '';

			promotionDataItem.push(o);
		});

		promotionData.push(promotionDataItem);
	});

	return promotionData;
}
