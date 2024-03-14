<?php
namespace webaware\gf_dpspxpay;

use GFAPI;
use GFCommon;
use GFFormsModel;
use Exception;

if (!defined('ABSPATH')) {
	exit;
}

// minimum versions required
const MIN_VERSION_GF	= '2.0';

// entry meta keys
const META_UNIQUE_ID					= 'gfdpspxpay_unique_id';	// unique form submission
const META_TRANSACTION_ID				= 'gfdpspxpay_txn_id';		// merchant's transaction ID (invoice number, etc.)
const META_GATEWAY_TXN_ID				= 'gateway_txn_id';			// duplicate of transaction_id to enable passing to integrations (e.g. Zapier)
const META_FEED_ID						= 'gfdpspxpay_feed_id';		// link to feed under which the transaction was processed
const META_AUTHCODE						= 'authcode';				// bank authorisation code
const META_SURCHARGE					= 'gfdpspxpay_surcharge';	// optional surcharge added by the payment gateway

// end points for return to website
const ENDPOINT_RETURN					= '__gfpxpayreturn';
const ENDPOINT_RETURN_TEST				= '__gfpxpayreturntest';	// return from test environment
const ENDPOINT_CONFIRMATION				= '__gfpxpayconfirm';

/**
* custom exception types
*/
class GFDpsPxPayException extends Exception {}

/**
* compare Gravity Forms version against target
* @param string $target
* @param string $operator
* @return bool
*/
function gform_version_compare($target, $operator) {
	if (class_exists('GFCommon', false)) {
		return version_compare(GFCommon::$version, $target, $operator);
	}

	return false;
}

/**
* test whether the minimum required Gravity Forms is installed / activated
* @return bool
*/
function has_required_gravityforms() {
	return gform_version_compare(MIN_VERSION_GF, '>=');
}

/**
* get confirmation block anchor HTML
* @param array $form
* @return string
*/
function get_form_confirmation_anchor($form) {
	$default_anchor = count(GFCommon::get_fields_by_type($form, ['page'])) > 0 ? 1 : 0;
	$default_anchor = apply_filters('gform_confirmation_anchor_' . $form['id'], apply_filters('gform_confirmation_anchor', $default_anchor));

	if (empty($default_anchor)) {
		$default_anchor = "<a id='gf_{$form["id"]}' name='gf_{$form["id"]}' class='gform_anchor' ></a>";
	}

	return $default_anchor;
}

/**
* encode some values to pass back from the callback to the confirmation page safely
* @param array $values
* @return string
*/
function encode_confirmation_values($values) {
	$hash = wp_hash(http_build_query($values));
	$values['hash']	= $hash;
	return base64_encode(http_build_query($values));
}

/**
* decode values passed from the callback to the confirmation page
* @param string $values
* @return array|false
*/
function decode_confirmation_values($encoded) {
	parse_str(base64_decode($encoded), $decoded);

	if (!empty($decoded) && count($decoded) > 1 && isset($decoded['hash'])) {
		//  get a copy of the array without the hash element
		$values = array_filter($decoded, function($key) {
			return $key !== 'hash';
		}, ARRAY_FILTER_USE_KEY);

		if (wp_hash(http_build_query($values)) === rgar($decoded, 'hash')) {
			return $values;
		}
	}

	return false;
}

/**
* check whether this form entry's unique ID has already been used; if so, we've already done/doing a payment attempt.
* @param int $form_id
* @return boolean
*/
function has_form_been_processed($form_id) {
	$unique_id = GFFormsModel::get_form_unique_id($form_id);

	$search = [
		'field_filters' => [
			[
				'key'		=> META_UNIQUE_ID,
				'value'		=> $unique_id,
			],
		],
	];

	$entries = GFAPI::get_entries($form_id, $search);

	return !empty($entries);
}
