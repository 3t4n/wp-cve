<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the Legend items
 *
 */
function wpsbc_include_files_legend() {

	// Get legend dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Include main Legend Item class
	if( file_exists( $dir_path . 'class-legend-item.php' ) )
		include $dir_path . 'class-legend-item.php';

	// Include the db layer classes
	if( file_exists( $dir_path . 'class-object-db-legend-items.php' ) )
		include $dir_path . 'class-object-db-legend-items.php';

	if( file_exists( $dir_path . 'class-object-meta-db-legend-items.php' ) )
		include $dir_path . 'class-object-meta-db-legend-items.php';

}
add_action( 'wpsbc_include_files', 'wpsbc_include_files_legend' );


/**
 * Register the class that handles database queries for the Legend Items
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpsbc_register_database_classes_legend( $classes ) {

	$classes['legend_items']    = 'WPSBC_Object_DB_Legend_Items';
	$classes['legend_itemmeta'] = 'WPSBC_Object_Meta_DB_Legend_Items';

	return $classes;

}
add_filter( 'wpsbc_register_database_classes', 'wpsbc_register_database_classes_legend' );


/**
 * Returns an array with WPSBC_Legend_Items objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpsbc_get_legend_items( $args = array(), $count = false ) {

	return wp_simple_booking_calendar()->db['legend_items']->get_legend_items( $args, $count );

}


/**
 * Gets a legend item from the database
 *
 * @param int $legend_item_id
 *
 * @return WPSBC_Legend_Item|false
 *
 */
function wpsbc_get_legend_item( $legend_item_id ) {

	return wp_simple_booking_calendar()->db['legend_items']->get_object( $legend_item_id );

}


/**
 * Inserts a new legend item into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpsbc_insert_legend_item( $data ) {

	return wp_simple_booking_calendar()->db['legend_items']->insert( $data );

}

/**
 * Updates a legend item from the database
 *
 * @param int 	$legend_item_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpsbc_update_legend_item( $legend_item_id, $data ) {

	return wp_simple_booking_calendar()->db['legend_items']->update( $legend_item_id, $data );

}

/**
 * Deletes a legend item from the database
 *
 * @param int $legend_item_id
 *
 * @return bool
 *
 */
function wpsbc_delete_legend_item( $legend_item_id ) {

	return wp_simple_booking_calendar()->db['legend_items']->delete( $legend_item_id );

}

/**
 * Inserts a new meta entry for the legend item
 *
 * @param int    $legend_item_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $unique
 *
 * @return mixed int|false
 *
 */
function wpsbc_add_legend_item_meta( $legend_item_id, $meta_key, $meta_value, $unique = false ) {

	return wp_simple_booking_calendar()->db['legend_itemmeta']->add( $legend_item_id, $meta_key, $meta_value, $unique );

}

/**
 * Updates a meta entry for the legend item
 *
 * @param int    $legend_item_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $prev_value
 *
 * @return bool
 *
 */
function wpsbc_update_legend_item_meta( $legend_item_id, $meta_key, $meta_value, $prev_value = '' ) {

	return wp_simple_booking_calendar()->db['legend_itemmeta']->update( $legend_item_id, $meta_key, $meta_value, $prev_value );

}

/**
 * Returns a meta entry for the legend item
 *
 * @param int    $legend_item_id
 * @param string $meta_key
 * @param bool   $single
 *
 * @return mixed
 *
 */
function wpsbc_get_legend_item_meta( $legend_item_id, $meta_key = '', $single = false ) {

	return wp_simple_booking_calendar()->db['legend_itemmeta']->get( $legend_item_id, $meta_key, $single );

}

/**
 * Removes a meta entry for the legend item
 *
 * @param int    $legend_item_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $delete_all
 *
 * @return bool
 *
 */
function wpsbc_delete_legend_item_meta( $legend_item_id, $meta_key, $meta_value = '', $delete_all = '' ) {

	return wp_simple_booking_calendar()->db['legend_itemmeta']->delete( $legend_item_id, $meta_key, $meta_value, $delete_all );

}


/**
 * Returns an array with the default legend items
 *
 * @return array
 *
 */
function wpsbc_get_default_legend_items_data() {

	$legend_items_data = array(
		array(
			'name' 		 => __( 'Available', 'wp-simple-booking-calendar' ),
			'type'		 => 'single',
			'color'		 => array( '#ddffcc' ),
			'is_visible' => 1,
			'is_default' => 1,
			'translations' => array(
                'en' => 'Available',
                'bg' => 'Свободен',
                'ca' => 'Disponible',
                'hr' => 'Dostupno',
                'cz' => 'Dostupný',
                'da' => 'Ledig',
                'nl' => 'Beschikbaar',
                'et' => 'Saadaval',
                'fi' => 'Käytettävissä',
                'fr' => 'Disponible',
                'gl' => 'Dispoñible',
                'de' => 'Verfügbar',
                'el' => 'Διαθέσιμος',
                'hu' => 'Elérhető',
                'it' => 'Disponibile',
                'jp' => '利用可能',
                'lt' => 'Yra',
                'no' => 'Tilgjengelig',
                'pl' => 'Dostępny',
                'pt' => 'Disponível',
                'ro' => 'Disponibil',
                'ru' => 'Доступный',
                'sr' => 'Доступан',
                'sk' => 'Dostupný',
                'sl' => 'Veljaven',
                'es' => 'Disponible',
                'sv' => 'Tillgängliga',
                'tr' => 'Mevcut',
                'ua' => 'Доступні',
            ),
		),
		array(
			'name' 		 => __( 'Booked', 'wp-simple-booking-calendar' ),
			'type'		 => 'single',
			'color'		 => array( '#ffc0bd' ),
			'is_visible' => 1,
			'translations' => array(
                'en' => 'Booked',
                'bg' => 'Резервирано',
                'ca' => 'Reservat',
                'hr' => 'Rezerviran',
                'cs' => 'Rezervováno',
                'da' => 'Reserveret',
                'nl' => 'Geboekt',
                'et' => 'Broneeritud',
                'fi' => 'Varattu',
                'fr' => 'Réservé',
                'gl' => 'Reservado',
                'de' => 'Gebucht',
                'el' => 'Κράτηση',
                'hu' => 'Foglalt',
                'it' => 'Riservato',
                'jp' => '予約済み',
                'lt' => 'Užsakyta',
                'no' => 'Bestilt',
                'pl' => 'Zarezerwowane',
                'pt' => 'Reservado',
                'ro' => 'Rezervat',
                'ru' => 'Бронирования',
                'sr' => 'Резервисан',
                'sk' => 'Rezervovaný',
                'sl' => 'Rezervirano',
                'es' => 'Reservado',
                'sv' => 'Bokad',
                'tr' => 'Rezervasyon',
                'ua' => 'Забронювали',
            ),
		),
		array(
			'name' 		 => __( 'Changeover', 'wp-simple-booking-calendar' ),
			'type'		 => 'single',
			'color'		 => array( '#fee2a0' ),
			'is_visible' => 1,
			'translations' => array(
                'en' => 'Changeover',
                'bg' => 'смяна',
                'ca' => 'Canvi',
                'hr' => 'Prebacivanje',
                'cs' => 'Přechod',
                'da' => 'Skiftedag',
                'nl' => 'Wisseldag',
                'et' => 'Üleminek',
                'fi' => 'Siirtyminen',
                'fr' => 'Passage',
                'gl' => 'Cambio',
                'de' => 'Umstellung',
                'el' => 'Εναλλαγή',
                'hu' => 'Átállás',
                'it' => 'Passaggio',
                'jp' => '切り替え',
                'lt' => 'Euro įvedimas',
                'no' => 'Ankomst',
                'pl' => 'Przełączenie',
                'pt' => 'Mudança',
                'ro' => 'Schimbare',
                'ru' => 'переналадка',
                'sr' => 'Промјена',
                'sk' => 'Zmena',
                'sl' => 'Zamenjava',
                'es' => 'Cambio',
                'sv' => 'Övergång',
                'tr' => 'Değiştirme',
                'ua' => 'Перехід',
            ),
		),
		array(
			'name' 		 => __( 'Changeover 1', 'wp-simple-booking-calendar' ),
			'type'		 => 'split',
			'color'		 => array( '#ddffcc', '#ffc0bd' ),
			'is_visible' => 0
		),
		array(
			'name' 		 => __( 'Changeover 2', 'wp-simple-booking-calendar' ),
			'type'		 => 'split',
			'color'		 => array( '#ffc0bd', '#ddffcc' ),
			'is_visible' => 0
		)
	);

	return $legend_items_data;

}


/**
 * Modifies the order of the legend items when being called through the DB Legend Items object
 * to match the order saved in the calendar meta table under the "legend_items_sort_order" key
 *
 * @param array $results
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpsbc_get_legend_items_filter_by_order( $results, $args, $count = false ) {

	if( true === $count )
		return $results;

	if( empty( $args['calendar_id'] ) )
		return $results;

	$legend_items_sort_order = wpsbc_get_calendar_meta( $args['calendar_id'], 'legend_items_sort_order', true );


	if( empty( $legend_items_sort_order ) || ! is_array( $legend_items_sort_order ) )
		return $results;

	// New results array
	$new_results = array();

	// Go through each legend item id in the order list and place the legend item object
	// with the corresponding id in the new results array
	foreach( $legend_items_sort_order as $legend_item_id ) {

		foreach( $results as $legend_item ) {

			if( $legend_item->get('id') == $legend_item_id )
				$new_results[$legend_item_id] = $legend_item;

		}

	}

	// Go through each results and add to the new results array the legend item objects 
	// that did not have the id in the legend items sort order
	foreach( $results as $legend_item ) {

		if( ! in_array( $legend_item->get('id'), array_keys( $new_results ) ) )
			$new_results[$legend_item->get('id')] = $legend_item;

	}

	$results = array_values( $new_results );

	return $results;

}
add_filter( 'wpsbc_get_legend_items', 'wpsbc_get_legend_items_filter_by_order', 10, 3 );