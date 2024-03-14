<?php


/**
 * Returns a conversion table between the ajax data items from a feed generation process to the corresponding database items.
 *
 * @since 2.5.0
 *
 * @return mixed|void
 */
function wppfm_ajax_feed_data_to_database_array( $feed_type ) {
	$mappings = [
		'feedId' => ['product_feed_id', '%d'],
		'channel' => ['channel_id', '%d'],
		'language' => ['language', '%s'],
		'includeVariations' => ['include_variations', '%d'],
		'isAggregator' => ['is_aggregator', '%d'],
		'aggregatorName' => ['aggregator_name', '%s'],
		'country' => ['country_id', '%d'],
		'dataSource' => ['source_id', '%d'],
		'title' => ['title', '%s'],
		'feedTitle' => ['feed_title', '%s'],
		'feedDescription' => ['feed_description', '%s'],
		'mainCategory' => ['main_category', '%s'],
		'url' => ['url', '%s'],
		'status' => ['status_id', '%d'],
		'updateSchedule' => ['schedule', '%s'],
		'feedType' => ['feed_type_id', '%d'],
	];

	if ( 'product-feed' === $feed_type ) { // add in case of a normal product feed
		$mappings['currency'] = ['currency', '%s'];
	} else { // add in case of a Review feed or a Promotion feed
		$mappings['publisherName'] = [ 'publisher_name', '%s' ];
		$mappings['publisherFavicon'] = [ 'publisher_favicon_url', '%s' ];
	}

	$conversionTable = [];

	foreach ($mappings as $feed => $mapping) {
		list($db, $type) = $mapping;
		$conversionTable[] = (object) ['feed' => $feed, 'db' => $db, 'type' => $type];
	}

	return apply_filters('wppfm_feed_data_ajax_to_database_conversion_table', $conversionTable);
}

function wppfm_get_all_feed_names() {
	$query_class = new WPPFM_Queries();
	$feed_names  = $query_class->get_all_feed_names();
	$used_names  = array();

	foreach ( $feed_names as $name ) {
		$used_names[] = $name->title;
	}

	return $used_names;
}
