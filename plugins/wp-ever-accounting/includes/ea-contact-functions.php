<?php
/**
 * EverAccounting Contact Functions.
 *
 * Contact related functions.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit();

/**
 * Get contact types.
 *
 * @return array
 * @since 1.1.0
 */
function eaccounting_get_contact_types() {
	return apply_filters(
		'eaccounting_contact_types',
		array(
			'customer' => esc_html__( 'Customer', 'wp-ever-accounting' ),
			'vendor'   => esc_html__( 'Vendor', 'wp-ever-accounting' ),
		)
	);
}


/**
 * Get the contact type label of a specific type.
 *
 * @param string $type Contact type.
 *
 * @return string
 * @since 1.1.0
 */
function eaccounting_get_contact_type( $type ) {
	$types = eaccounting_get_contact_types();

	return array_key_exists( $type, $types ) ? $types[ $type ] : null;
}


/**
 * Get customer.
 *
 * @param mixed $customer Customer ID or object.
 *
 * @return \EverAccounting\Models\Customer|null
 * @since 1.1.0
 */
function eaccounting_get_customer( $customer ) {
	if ( empty( $customer ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Customer( $customer );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Get customer by email.
 *
 * @since 1.1.0
 *
 * @param string $email Customer email.
 *
 * @return \EverAccounting\Models\Customer | null
 */
function eaccounting_get_customer_by_email( $email ) {
	global $wpdb;
	$email = sanitize_email( $email );
	if ( empty( $email ) ) {
		return null;
	}
	$cache_key = "customer-email-$email";
	$customer  = wp_cache_get( $cache_key, 'ea_contacts' );
	if ( false === $customer ) {
		$customer = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ea_contacts where `email`=%s AND `type`='customer'", eaccounting_clean( $email ) ) );
		wp_cache_set( $cache_key, $customer, 'ea_contacts' );
	}
	if ( $customer ) {
		wp_cache_set( $customer->id, $customer, 'ea_contacts' );
		return eaccounting_get_customer( $customer );
	}

	return null;
}

/**
 *  Create new customer programmatically.
 *
 *  Returns a new customer object on success.
 *
 * @param array $args {
 *                             An array of elements that make up an customer to update or insert.
 *
 * @type int $id ID of the contact. If equal to something other than 0,
 *                               the post with that ID will be updated. Default 0.
 * @type int $user_id user_id of the contact. Default null.
 * @type string $name name of the contact. Default not null.
 * @type string $email email of the contact. Default null.
 * @type string $phone phone of the contact. Default null.
 * @type string $fax fax of the contact. Default null.
 * @type string $fax fax of the contact. Default null.
 * @type string $birth_date date of birth of the contact. Default null.
 * @type string $address address of the contact. Default null.
 * @type string $country country of the contact. Default null.
 * @type string $website website of the contact. Default null.
 * @type string $tax_number tax_number of the contact. Default null.
 * @type string $currency_code currency_code of the contact. Default null.
 * @type string $note Additional note of the contact. Default null.
 * @type string $attachment Attachment attached with contact. Default null.
 *
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return EverAccounting\Models\Customer|\WP_Error|bool
 * @since 1.1.0
 */
function eaccounting_insert_customer( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the customer.
		$item = new \EverAccounting\Models\Customer( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_customer', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete a customer.
 *
 * @param int $customer_id Customer ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_customer( $customer_id ) {
	try {
		$customer = new EverAccounting\Models\Customer( $customer_id );

		return $customer->exists() ? $customer->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get customers items.
 *
 * @param array $args {
 * An array of arguments.
 * @type int $id ID of the contact.
 * @type int $user_id user_id of the contact.
 * @type string $name name of the contact.
 * @type string $email email of the contact.
 * @type string $phone phone of the contact.
 * @type string $fax fax of the contact.
 * @type string $fax fax of the contact.
 * @type string $birth_date date of birth of the contact.
 * @type string $address address of the contact.
 * @type string $country country of the contact.
 * @type string $website website of the contact.
 * @type string $tax_number tax_number of the contact.
 * @type string $currency_code currency_code of the contact.
 *
 * }
 *
 * @return array|int
 * @since 1.1.0
 */
function eaccounting_get_customers( $args = array() ) {
	return eaccounting_get_contacts( array_merge( $args, array( 'type' => 'customer' ) ) );
}
/**
 * Get vendor.
 *
 * @param mixed $vendor Vendor ID or object.
 *
 * @return \EverAccounting\Models\Vendor|null
 * @since 1.1.0
 */
function eaccounting_get_vendor( $vendor ) {
	if ( empty( $vendor ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Vendor( $vendor );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Get vendor by email.
 *
 * @since 1.1.0
 *
 * @param string $email Vendor email.
 *
 * @return \EverAccounting\Models\Vendor
 */
function eaccounting_get_vendor_by_email( $email ) {
	global $wpdb;
	$email = sanitize_email( $email );
	if ( empty( $email ) ) {
		return null;
	}
	$cache_key = "vendor-email-$email";
	$vendor    = wp_cache_get( $cache_key, 'ea_contacts' );
	if ( false === $vendor ) {
		$vendor = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ea_contacts where `email`=%s AND `type`='vendor'", eaccounting_clean( $email ) ) );
		wp_cache_set( $cache_key, $vendor, 'ea_contacts' );
	}
	if ( $vendor ) {
		wp_cache_set( $vendor->id, $vendor, 'ea_contacts' );
		return eaccounting_get_vendor( $vendor );
	}

	return null;
}


/**
 *  Create new vendor programmatically.
 *
 *  Returns a new vendor object on success.
 *
 * @param array $args {
 * An array of elements that make up a vendor to update or insert.
 *
 * @type int $id ID of the contact. If equal to something other than 0,
 *                               the post with that ID will be updated. Default 0.
 * @type int $user_id user_id of the contact. Default null.
 * @type string $name name of the contact. Default not null.
 * @type string $email email of the contact. Default null.
 * @type string $phone phone of the contact. Default null.
 * @type string $fax fax of the contact. Default null.
 * @type string $fax fax of the contact. Default null.
 * @type string $birth_date date of birth of the contact. Default null.
 * @type string $address address of the contact. Default null.
 * @type string $country country of the contact. Default null.
 * @type string $website website of the contact. Default null.
 * @type string $tax_number tax_number of the contact. Default null.
 * @type string $currency_code currency_code of the contact. Default null.
 * @type string $note Additional note of the contact. Default null.
 * @type string $attachment Attachment attached with contact. Default null.
 *
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return EverAccounting\Models\Vendor|\WP_Error|bool
 * @since 1.1.0
 */
function eaccounting_insert_vendor( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the vendor.
		$item = new \EverAccounting\Models\Vendor( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete a vendor.
 *
 * @param int $vendor_id Vendor ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_vendor( $vendor_id ) {
	try {
		$vendor = new EverAccounting\Models\Vendor( $vendor_id );

		return $vendor->exists() ? $vendor->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get vendors items.
 *
 * @param array $args {
 * An array of elements that make up a vendor to update or insert.
 * @type int $id ID of the contact.
 * @type int $user_id user_id of the contact.
 * @type string $name name of the contact.
 * @type string $email email of the contact.
 * @type string $phone phone of the contact.
 * @type string $fax fax of the contact.
 * @type string $fax fax of the contact.
 * @type string $birth_date date of birth of the contact.
 * @type string $address address of the contact.
 * @type string $country country of the contact.
 * @type string $website website of the contact.
 * @type string $tax_number tax_number of the contact.
 * @type string $currency_code currency_code of the contact.
 *
 * }
 *
 * @return array|int
 * @since 1.1.0
 */
function eaccounting_get_vendors( $args = array() ) {
	return eaccounting_get_contacts( array_merge( $args, array( 'type' => 'vendor' ) ) );
}

/**
 * Get customers items.
 *
 * @param array $args {
 * An array of elements that make up a customer to update or insert.
 * @type int $id ID of the contact.
 * @type int $user_id user_id of the contact.
 * @type string $name name of the contact.
 * @type string $email email of the contact.
 * @type string $phone phone of the contact.
 * @type string $fax fax of the contact.
 * @type string $fax fax of the contact.
 * @type string $birth_date date of birth of the contact.
 * @type string $address address of the contact.
 * @type string $country country of the contact.
 * @type string $website website of the contact.
 * @type string $tax_number tax_number of the contact.
 * @type string $currency_code currency_code of the contact.
 *
 * }
 *
 * @return array|int
 * @since 1.1.0
 */
function eaccounting_get_contacts( $args = array() ) {
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'type'        => '',
			'include'     => '',
			'search'      => '',
			'transfer'    => true,
			'fields'      => '*',
			'orderby'     => 'id',
			'order'       => 'ASC',
			'number'      => 20,
			'offset'      => 0,
			'paged'       => 1,
			'return'      => 'objects',
			'count_total' => false,
		)
	);
	global $wpdb;
	$qv           = apply_filters( 'eaccounting_get_contact_args', $args );
	$table        = \EverAccounting\Repositories\Contacts::TABLE;
	$columns      = \EverAccounting\Repositories\Contacts::get_columns();
	$qv['fields'] = wp_parse_list( $qv['fields'] );
	foreach ( $qv['fields'] as $index => $field ) {
		if ( ! in_array( $field, $columns, true ) ) {
			unset( $qv['fields'][ $index ] );
		}
	}
	$fields = is_array( $qv['fields'] ) && ! empty( $qv['fields'] ) ? implode( ',', $qv['fields'] ) : '*';
	$where  = 'WHERE 1=1';
	if ( ! empty( $qv['include'] ) ) {
		$include = implode( ',', wp_parse_id_list( $qv['include'] ) );
		$where  .= " AND $table.`id` IN ($include)";
	} elseif ( ! empty( $qv['exclude'] ) ) {
		$exclude = implode( ',', wp_parse_id_list( $qv['exclude'] ) );
		$where  .= " AND $table.`id` NOT IN ($exclude)";
	}
	// search.
	$search_cols = array( 'id', 'name', 'email', 'phone', 'street', 'country' );
	if ( ! empty( $qv['search'] ) ) {
		$searches = array();
		$where   .= ' AND (';
		foreach ( $search_cols as $col ) {
			$searches[] = $wpdb->prepare( $col . ' LIKE %s', '%' . $wpdb->esc_like( $qv['search'] ) . '%' );
		}
		$where .= implode( ' OR ', $searches );
		$where .= ')';
	}

	if ( ! empty( $qv['type'] ) ) {
		$types  = implode( "','", wp_parse_list( $qv['type'] ) );
		$where .= " AND $table.`type` IN ('$types')";
	}

	if ( ! empty( $qv['currency_code'] ) ) {
		$currency_code = implode( "','", wp_parse_list( $qv['currency_code'] ) );
		$where        .= " AND $table.`currency_code` IN ('$currency_code')";
	}

	if ( ! empty( $qv['status'] ) && ! in_array( $qv['status'], array( 'all', 'any' ), true ) ) {
		$status = eaccounting_string_to_bool( $qv['status'] );
		$status = eaccounting_bool_to_number( $status );
		$where .= " AND $table.`enabled` = ('$status')";
	}

	if ( ! empty( $qv['creator_id'] ) ) {
		$creator_id = implode( ',', wp_parse_id_list( $qv['creator_id'] ) );
		$where     .= " AND $table.`creator_id` IN ($creator_id)";
	}

	if ( ! empty( $qv['date_created'] ) && is_array( $qv['date_created'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['date_created'], "{$table}.date_created" );
		$where             .= $date_created_query->get_sql();
	}

	$order   = isset( $qv['order'] ) ? strtoupper( $qv['order'] ) : 'ASC';
	$orderby = isset( $qv['orderby'] ) && in_array( $qv['orderby'], $columns, true ) ? eaccounting_clean( $qv['orderby'] ) : "{$table}.id";

	$limit = '';
	if ( isset( $qv['number'] ) && $qv['number'] > 0 ) {
		if ( $qv['offset'] ) {
			$limit = $wpdb->prepare( 'LIMIT %d, %d', $qv['offset'], $qv['number'] );
		} else {
			$limit = $wpdb->prepare( 'LIMIT %d, %d', $qv['number'] * ( $qv['paged'] - 1 ), $qv['number'] );
		}
	}

	$select      = "SELECT {$fields}";
	$from        = "FROM {$wpdb->prefix}$table $table";
	$orderby     = "ORDER BY {$orderby} {$order}";
	$count_total = true === $qv['count_total'];
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_contacts' );
	$results     = wp_cache_get( $cache_key, 'ea_contacts' );
	$clauses     = compact( 'select', 'from', 'where', 'orderby', 'limit' );

	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT(id) $from $where" );
			wp_cache_set( $cache_key, $results, 'ea_contacts' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					if ( ! empty( $item->email ) ) {
						wp_cache_set( $item->type . '-email-' . $item->email, $item, 'ea_contacts' );
					}
					wp_cache_set( $item->id, $item, 'ea_contacts' );
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_contacts' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map(
			function ( $item ) {
				switch ( $item->type ) {
					case 'customer':
						$contact = eaccounting_get_customer( $item );
						break;
					case 'vendor':
						$contact = eaccounting_get_vendor( $item );
						break;
					default:
						$contact = apply_filters( 'eaccounting_get_contact_callback_' . $item->type, null, $item );
				}

				return $contact;
			},
			$results
		);
	}

	return $results;
}
