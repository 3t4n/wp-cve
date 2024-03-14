<?php
/**
 * EverAccounting Currency Functions.
 *
 * Currency related functions.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Currency;

defined( 'ABSPATH' ) || exit();

/**
 * Return all available currency codes.
 *
 * @return array
 * @since 1.1.0
 */
function eaccounting_get_currency_codes() {
	return eaccounting_get_data( 'currencies' );
}

/**
 * Check if currency code is a valid one.
 *
 * @param string $code Currency code.
 *
 * @return string
 * @since 1.1.0
 */
function eaccounting_sanitize_currency_code( $code ) {
	$codes = eaccounting_get_currency_codes();
	$code  = strtoupper( $code );
	if ( empty( $code ) || ! array_key_exists( $code, $codes ) ) {
		return '';
	}

	return $code;
}

/**
 * Main function for returning currency.
 *
 * This function is little different from rest
 * Even if the currency in the database doest not
 * exist it will it populate with default data.
 *
 * Whenever need to check existence of the object
 * in database must check $currency->exist()
 *
 * @param object|string|int $currency Currency object, code or ID.
 *
 * @return EverAccounting\Models\Currency|null
 * @since 1.1.0
 */
function eaccounting_get_currency( $currency ) {
	if ( empty( $currency ) ) {
		return null;
	}
	try {
		return new EverAccounting\Models\Currency( $currency );
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Get currency rate.
 *
 * @param string $currency Currency code.
 *
 * @return mixed|null
 * @since 1.1.0
 */
function eaccounting_get_currency_rate( $currency ) {
	$exist = eaccounting_get_currency( $currency );
	if ( $exist ) {
		return $exist->get_rate();
	}

	return 1;
}


/**
 *  Create new currency programmatically.
 *
 *  Returns a new currency object on success.
 *
 * @param array $args {
 *                                  An array of elements that make up a currency to update or insert.
 *
 * @type int $id The currency ID. If equal to something other than 0,
 *                                         the currency with that id will be updated. Default 0.
 *
 * @type string $name The name of the currency . Default empty.
 *
 * @type string $code The code of currency. Default empty.
 *
 * @type double $rate The rate for the currency.Default is 1.
 *
 * @type double $precision The precision for the currency. Default 0.
 *
 * @type string $symbol The symbol for the currency. Default empty.
 *
 * @type string $position The position where the currency code will be set in amount. Default before.
 *
 * @type string $decimal_separator The decimal_separator for the currency code. Default ..
 *
 * @type string $thousand_separator The thousand_separator for the currency code. Default ,.
 *
 * @type int $enabled The status of the currency. Default 1.
 *
 * @type string $date_created The date when the currency is created. Default is current time.
 *
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return EverAccounting\Models\Currency|\WP_Error|bool
 * @since 1.1.0
 */
function eaccounting_insert_currency( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args(
			$args,
			array(
				'code' => null,
			)
		);
		// Retrieve the currency.
		$item = new \EverAccounting\Models\Currency( $args );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_currency', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete a currency.
 *
 * @param string $currency_code Currency code.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_currency( $currency_code ) {
	try {
		$currency = new EverAccounting\Models\Currency( $currency_code );

		return $currency->exists() ? $currency->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get currency items.
 *
 * @param array $args Query arguments.
 *
 * @return array|int|null
 * @since 1.1.0
 */
function eaccounting_get_currencies( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'search'      => '',
			'fields'      => '*',
			'orderby'     => 'name',
			'order'       => 'ASC',
			'number'      => - 1,
			'offset'      => 0,
			'paged'       => 1,
			'return'      => 'objects',
			'count_total' => false,
		)
	);

	$qv         = apply_filters( 'eaccounting_get_currencies_args', $args );
	$option     = \EverAccounting\Repositories\Currencies::OPTION;
	$columns    = \EverAccounting\Repositories\Currencies::get_columns();
	$currencies = wp_cache_get( 'ea_currencies', 'ea_currencies' );

	if ( false === $currencies ) {
		$currencies = get_option( $option, array() );
		wp_cache_add( 'ea_currencies', $currencies, 'ea_currencies' );
	}
	$currencies = eaccounting_collect( $currencies );

	if ( ! empty( $qv['search'] ) ) {
		$currencies = $currencies->filter(
			function ( $item ) use ( $qv ) {
				$search = implode( ' ', array( $item['name'], $item['code'], $item['symbol'] ) );
				if ( false !== strpos( strtolower( $search ), strtolower( $qv['search'] ) ) ) {
					return $item;
				}

				return false;
			}
		);
	}

	if ( ! empty( $qv['include'] ) ) {
		$includes = wp_parse_list( $qv['include'] );
		foreach ( $includes as $include ) {
			$currencies = $currencies->where_loose( 'code', $include );
		}
	}

	$qv['fields'] = wp_parse_list( $qv['fields'] );
	foreach ( $qv['fields'] as $index => $field ) {
		if ( ! in_array( $field, $columns, true ) ) {
			unset( $qv['fields'][ $index ] );
		}
	}

	$fields        = is_array( $qv['fields'] ) && ! empty( $qv['fields'] ) ? $qv['fields'] : '*';
	$qv['order']   = isset( $qv['order'] ) ? strtoupper( $qv['order'] ) : 'ASC';
	$qv['orderby'] = in_array( $qv['orderby'], $columns, true ) ? $qv['orderby'] : 'name';

	$qv['number'] = isset( $qv['number'] ) && $qv['number'] > 0 ? $qv['number'] : - 1;
	$qv['offset'] = isset( $qv['offset'] ) ? $qv['offset'] : ( $qv['number'] * ( $qv['paged'] - 1 ) );
	$count_total  = true === $qv['count_total'];
	$currencies   = $currencies->sort(
		function ( $a, $b ) use ( $qv ) {
			if ( 'ASC' === $qv['order'] ) {
				return $a[ $qv['orderby'] ] < $b[ $qv['orderby'] ];
			}

			return $a[ $qv['orderby'] ] > $b[ $qv['orderby'] ];
		}
	);

	if ( $count_total ) {
		return $currencies->count();
	}

	if ( $qv['number'] > 1 ) {
		$currencies = $currencies->splice( $qv['offset'], $qv['number'] );
	}

	$results = $currencies->values()->all();

	if ( 'objects' === $qv['return'] ) {
		$results = array_map( 'eaccounting_get_currency', $results );
	} else {
		$results = array_map(
			function ( $result ) {
				return (object) $result;
			},
			$results
		);
	}

	return $results;
}
