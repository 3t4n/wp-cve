<?php
/**
 * EverAccounting Template functions.
 *
 * Functions related to templates.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit();

/**
 * Get template part.
 *
 * @param mixed  $slug Template slug.
 * @param string $name Template name (default: '').
 */
function eaccounting_get_template_part( $slug, $name = null ) {
	if ( $name ) {
		$template = locate_template(
			array(
				"{$slug}-{$name}.php",
				eaccounting()->template_path() . "{$slug}-{$name}.php",
			)
		);

		if ( ! $template ) {
			$fallback = eaccounting()->plugin_path() . "/templates/{$slug}-{$name}.php";
			$template = file_exists( $fallback ) ? $fallback : '';
		}
	}

	if ( ! $template ) {
		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/eaccounting/slug.php.
		$template = locate_template(
			array(
				"{$slug}.php",
				eaccounting()->template_path() . "{$slug}.php",
			)
		);
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'eaccounting_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @return string
 */
function eaccounting_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = eaccounting()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = eaccounting()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'eaccounting_locate_template', $template, $template_name, $template_path );
}


/**
 * Get other templates passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array  $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 */
function eaccounting_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	$template = eaccounting_locate_template( $template_name, $template_path, $default_path );

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'eaccounting_get_template', $template, $template_name, $args, $template_path, $default_path );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			$filter_template = $template;
		}
	}

	$action_args = array(
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	do_action( 'eaccounting_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'eaccounting_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}

/**
 * Like eaccounting_get_template, but returns the HTML instead of outputting.
 *
 * @param string $template_name Template name.
 * @param array  $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @since 1.0.2
 * @return string
 * @see   eaccounting_get_template
 */
function eaccounting_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	eaccounting_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}


/**
 * Get admin view.
 *
 * since 1.0.2
 *
 * @param string $template_name Template name.
 * @param array  $args Arguments. (default: array).
 * @param null   $path Template path. (default: null).
 */
function eaccounting_get_admin_template( $template_name, $args = array(), $path = null ) {

	if ( $args && is_array( $args ) ) {
		extract( $args );
	}
	$template_name = str_replace( '.php', '', $template_name );
	if ( is_null( $path ) ) {
		$path = EACCOUNTING_ABSPATH . '/includes/admin/views/';
	}
	$template = apply_filters( 'eaccounting_admin_template', $template_name );
	$file     = $path . $template . '.php';
	if ( ! file_exists( $file ) ) {
		/* Translators: %s file name */
		eaccounting_doing_it_wrong( __FUNCTION__, sprintf( __( 'Admin template %s does not exist', 'wp-ever-accounting' ), $file ), null );

		return;
	}
	include $file;
}

/**
 * Render admin template.
 *
 * @param string $template_name Template name.
 * @param array  $args Arguments.
 *
 * @since 1.0.0
 * @return string
 */
function eaccounting_get_admin_template_html( $template_name, $args = array() ) {
	ob_start();

	eaccounting_get_admin_template( $template_name, $args );

	return ob_get_clean();
}

/**
 * Get base slug.
 *
 * @since 1.1.0
 */
function eaccounting_get_parmalink_base() {
	return apply_filters( 'eaccounting_parmalink_base', 'eaccounting' );
}

/**
 * Conditionally render templates.
 *
 * @since 1.1.0
 */
function eaccounting_render_body() {
	$ea_page = get_query_var( 'ea_page' );
	$key     = get_query_var( 'key' );
	switch ( $ea_page ) {
		case 'invoice':
			$id       = get_query_var( 'id' );
			$template = 'single-invoice.php';
			eaccounting_get_template(
				$template,
				array(
					'invoice_id' => $id,
					'key'        => $key,
				)
			);
			break;
		case 'bill':
			$id       = get_query_var( 'id' );
			$template = 'single-bill.php';
			eaccounting_get_template(
				$template,
				array(
					'bill_id' => $id,
					'key'     => $key,
				)
			);
			break;
		default:
			eaccounting_get_template( 'restricted.php' );
			break;
	}
}

add_action( 'eaccounting_body', 'eaccounting_render_body' );

/**
 * Public invoice actions
 *
 * @param Invoice $invoice Invoice.
 *
 * @since 1.0.0
 * @return void
 */
function eaccounting_public_invoice_actions( $invoice ) {
	eaccounting_get_template( 'invoice-actions.php', array( 'invoice' => $invoice ) );
}

add_action( 'eaccounting_public_before_invoice', 'eaccounting_public_invoice_actions' );


/**
 * Output bill actions.
 *
 * @param \EverAccounting\Models\Bill $bill Bill.
 *
 * @since 1.0.0
 * @return void
 */
function eaccounting_public_bill_actions( $bill ) {
	eaccounting_get_template( 'bill-actions.php', array( 'bill' => $bill ) );
}

add_action( 'eaccounting_public_before_bill', 'eaccounting_public_bill_actions' );
