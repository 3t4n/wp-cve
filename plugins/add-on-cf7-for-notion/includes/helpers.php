<?php
/**
 * Plugin's helpers.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN\Helpers;

use WPC_WPCF7_NTN\Options;

defined( 'ABSPATH' ) || exit;

/**
 * Process the Notion /account response.
 *
 * @param mixed            $pretty_response A response from `wp_remote_request` if the request was successful.
 * @param object|\WP_Error $response A WP_Error from `wp_remote_request` if the request failed.
 * @return void
 */
function process_notion_test_request_response( $pretty_response, $response ) {
	if ( is_wp_error( $response ) ) {
		Options\update_plugin_option(
			'notion_api_error',
			array(
				'code'    => $response->get_error_code(),
				'message' => $response->get_error_message(),
			)
		);
		Options\delete_plugin_option( 'notion_api_key_is_valid' );
	} elseif ( ! is_wp_error( $response ) && is_array( $pretty_response ) ) {
		Options\update_plugin_option( 'notion_api_key_is_valid', time() );
		Options\delete_plugin_option( 'notion_api_error' );
	}
}

/**
 * Get a cached version of Notion databases list.
 *
 * @param boolean $bypass_cache Bypass API cache.
 * @param boolean $return_wp_error Whether to return a WP_Error Object or not if the request failed.
 * @return array
 */
function get_notion_databases( $bypass_cache = false, $return_wp_error = false ) {
	$transient_name = sprintf( '%1$snotion_databases', WPCONNECT_WPCF7_NTN_OPTIONS_PREFIX );
	$result         = get_transient( $transient_name );

	if ( false === $result || $bypass_cache ) {
		$api    = wpconnect_wpcf7_notion_get_api();
		$result = $api->get_databases();

		if ( ! is_wp_error( $result ) ) {
			set_transient( $transient_name, $result, 1 * MINUTE_IN_SECONDS );
		} elseif ( $return_wp_error ) {
			return $result;
		}
	}

	return $result;
}

/**
 * Get a cached version of Notion databases columns.
 *
 * @param string  $from_db_id The Notion database id.
 * @param boolean $bypass_cache Bypass API cache.
 * @return array
 */
function get_notion_databases_columns( $from_db_id = null, $bypass_cache = false ) {
	$databases = get_notion_databases( $bypass_cache );
	$columns   = array();

	if ( is_array( $databases ) && ! empty( $databases ) ) {
		foreach ( $databases as $database ) {
			if ( ! is_null( $from_db_id ) && $database->id !== $from_db_id ) {
				continue;
			}

			foreach ( $database->properties as $column ) {
				$column_id             = $column->id;
				$columns[ $column_id ] = (object) array(
					'database_id'   => $database->id,
					'database_name' => $database->title[0]->plain_text,
					'id'            => $column_id,
					'name'          => $column->name,
					'type'          => $column->type,
				);
			}
		}
	}

	return $columns;
}


/**
 * Prepare fields for Notion API.
 *
 * @param object[] $fields A list of fields:
 * [
 *    {
 *        'column_id' => '...',
 *        'field_value' => '...',
 *        'field_type' => '...'
 *    }
 * ].
 * @param string   $database_id The database id.
 * @return array
 */
function prepare_fields_for_notion( $fields, $database_id ) {
	$result      = array();
	$all_columns = get_notion_databases_columns( $database_id );

	foreach ( $fields as $column_id => $map_details ) {
		$field_value = $map_details->field_value;

		if ( empty( $field_value ) ) {
			continue;
		}

		$result[ $column_id ] = apply_filters( 'add-on-cf7-for-notion/notion-column-value', $field_value, $map_details );
	}

	return apply_filters( 'add-on-cf7-for-notion/notion-columns', $result, $fields, $all_columns, $database_id );
}

/**
 * Returns mapped Notion's fields name for each Contact Form 7 tags with a `notion` property based on a Notion database column list.
 *
 * @param WPCF7_ContactForm $contact_form A WPCF7_ContactForm instance.
 * @param array             $columns A Notion database column list.
 * @return array
 */
function get_mapped_tags_from_contact_form( $contact_form, $columns ) {
	$prop        = wp_parse_args(
		$contact_form->prop( 'wpc_notion' ),
		array(
			'enable_database'   => false,
			'database_selected' => '',
			'mapping'           => array(),
		)
	);
	$mapped_tags = array();
	if ( $prop['enable_database'] && ! empty( $prop['database_selected'] ) ) {
		$mapping = $prop['mapping'];
		foreach ( $contact_form->scan_form_tags() as $tag ) {
			// The field is not mapped.
			if ( ! isset( $mapping[ $tag->name ] ) || empty( $mapping[ $tag->name ] ) ) {
				continue;
			}
			$column_id = $mapping[ $tag->name ];
			// The Notion's column does not exist anymore.
			if ( ! isset( $columns[ $column_id ] ) ) {
				$mapped_tags[ $tag->name ] = array(
					'type'              => rtrim( $tag->type, '*' ),
					'notion_field_id'   => $column_id,
					'notion_field_name' => '',
				);
				continue;
			}

			$column                    = $columns[ $column_id ];
			$mapped_tags[ $tag->name ] = array(
				'type'              => $tag->basetype,
				'content'           => $tag->content,
				'notion_field_name' => $column->name,
				'notion_field_id'   => $column->id,
				'notion_field_type' => $column->type,
			);
		}
	}
	return $mapped_tags;
}

/**
 * Display a tooltip.
 *
 * @param string $text Tooltip text, HTML tags allowed: a and br.
 */
function tooltip( $text ) {
	printf(
		'<span class="wpc-wpcf7-notion-tooltip dashicons dashicons-editor-help"><span class="wpc-wpcf7-notion-tooltiptext">%s</span></span>',
		wp_kses(
			$text,
			array(
				'a'  => array(
					'href'   => true,
					'target' => true,
				),
				'br' => array(),
			)
		)
	);
}
