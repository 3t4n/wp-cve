<?php
/**
 * It includes the code for the pointers.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

do_action( 'fdp_define_pointers' );
add_filter( 'fdp_admin_pointers-eos_dp_menu', 'eos_dp_register_pointers_singles' );
function eos_dp_register_pointers_singles( $p ) {
	$pointers = array(
		'fdp_getting_start'      => array(
			esc_html__( 'Getting started', 'freesoul-deactivate-plugins' ),
			sprintf( wp_kses_post( __( "Uncheck the plugins wich you don't need on specific pages. %1\$sThe Post Types settings will override the inactive rows.%2\$s%3\$sIf many pages need the same set of active plugins, better disable the related rows by using the switches, and use the Post Types settings for those pages.%4\$s", 'freesoul-deactivate-plugins' ) ), '<p>', '</p>', '<p>', '</p>' ),
			'#eos-dp-setts-nav',
			'',
			'',
			'',
			'top',
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ),
			'',
		),
		'fdp_uncheck_plugins'    => array(
			esc_html__( 'Uncheck unused plugins', 'freesoul-deactivate-plugins' ),
			esc_html__( 'Activate/deactivate the plugins by clicking on the cells.', 'freesoul-deactivate-plugins' ),
			'.eos-dp-post-name-wrp+td',
			'',
			'',
			'',
			'top',
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ),
			'',
		),
		'fdp_global_row_uncheck' => array(
			esc_html__( 'How activate/deactivate entire rows', 'freesoul-deactivate-plugins' ),
			esc_html__( 'If you need to activate/deactivate all the plugins in a row, click on the square next to the switch.', 'freesoul-deactivate-plugins' ),
			'.eos-dp-global-chk-row',
			'',
			'',
			'',
			'top',
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ),
			'',
		),
		'fdp_global_col_uncheck' => array(
			esc_html__( 'How activate/deactivate entire columns', 'freesoul-deactivate-plugins' ),
			esc_html__( 'If you need to activate/deactivate all the plugins in a column, click on the plugin icon below the plugin name.', 'freesoul-deactivate-plugins' ),
			'.eos-dp-global-chk-col-wrp',
			'',
			'',
			'',
			'top',
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ),
			'',
		),
		'fdp_plugins_slider'     => array(
			esc_html__( 'Plugin slider', 'freesoul-deactivate-plugins' ),
			esc_html__( 'Drag the slide control above to scroll the columns.', 'freesoul-deactivate-plugins' ),
			'.fdp-plugins-slider',
			'',
			'',
			'',
			'top',
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ),
			'',
		),
		'fdp_action_buttons'     => array(
			esc_html__( 'Action buttons', 'freesoul-deactivate-plugins' ),
			sprintf( wp_kses_post( __( "Click on the plus icon before the switch to open the action buttons panel.%1\$sClick on the lens icon to see the preview of the page loading the plugins that remain active.%2\$s%3\$sDon't care about all the other icons, now focus only on what you need to get started.", 'freesoul-deactivate-plugins' ) ), '<p>', '</p>', '<p>', '</p>' ),
			'.fdp-row-actions-ico',
			'.fdp-row-actions-ico',
			40,
			'.eos-dp-theme-sel+.eos-dp-preview',
			'top',
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ),
			'',
		),
		'fdp_save'               => array(
			esc_html__( 'Save after check', 'freesoul-deactivate-plugins' ),
			esc_html__( "If after checking the preview you don't see anything strange save the settings clicking on \"Save all chagnes\".", 'freesoul-deactivate-plugins' ),
			'.eos-dp-btn-wrp', // element.
			'', // click.
			'-60', // offset.
			'.eos-dp-btn-wrp', // indicated.
			'bottom',   // edge.
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ),
			'',
		),
		'fdp_other_singles'      => array(
			esc_html__( 'Other kind of single pages', 'freesoul-deactivate-plugins' ),
			esc_html__( 'You will find the settings for the other kind of single pages under the menu item "Singles".', 'freesoul-deactivate-plugins' ),
			'#fdp-menu-singles', // element.
			'', // click.
			'100', // offset.
			'#fdp-menu-singles', // indicated.
			'top',  // edge.
			esc_html__( 'Next', 'freesoul-deactivate-plugins' ), // button text.
			'fdp-hover', // CSS class added to elemeent.
		),
		'fdp_post_types'         => array(
			esc_html__( 'Continue on post types', 'freesoul-deactivate-plugins' ),
			esc_html__( 'After having disabled plugins on specific pages, assign the used plugins by post type to cover the rest of the pages.', 'freesoul-deactivate-plugins' ),
			'#fdp-menu-post-types a', // element.
			'', // click.
			'20', // offset.
			'#fdp-menu-post-types', // indicated.
			'top',  // edge.
			esc_html__( 'Close', 'freesoul-deactivate-plugins' ), // button text.
			'', // CSS class added to elemeent.
		),
	);
	return eos_dp_build_pointers( $p, $pointers, 'fdp_pointers_singles' );
}

// Chcek if pointers are dismissed
function eos_dp_is_dismissed( $pointers ) {
	if ( ! isset( $_GET['reopen_pointer'] ) ) {
		$user_meta = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
		if ( is_string( $user_meta ) ) {
			$dismissed = '' !== $user_meta ? explode( ',', $user_meta ) : array();
		} elseif ( is_array( $user_meta ) ) {
			$dismissed = $user_meta;
		} else {
			return true;
		}
		foreach ( array_keys( $pointers ) as $pkey ) {
			if ( in_array( $pkey, $dismissed ) ) {
				return true;
			}
		}
		return false;
	}
	return false;
}

// Build pointers.
function eos_dp_build_pointers( $p, $pointers, $filter_name = false ) {
	if ( ! is_array( $p ) ) {
		$p = array();
	}
	$pointers = $filter_name ? apply_filters( $filter_name, $pointers ) : $pointers;
	if ( eos_dp_is_dismissed( $pointers ) ) {
		return $p;
	}
	$n              = 0;
	$pointer_values = array_values( $pointers );
	foreach ( $pointers as $pointer_id => $arr ) {
		if ( isset( $pointer_values[ $n + 1 ] ) ) {
			$nextA       = $pointer_values[ $n + 1 ];
			$el_selector = $n + 1 <= count( $pointer_values ) ? $nextA[2] : 'end';
		}
		$last             = $n + 1 === count( $pointer_values );
		$p[ $pointer_id ] = eos_dp_build_pointer_array( $n, $pointer_id, $arr[0], $arr[1], $el_selector, $arr[3], $arr[4], $arr[5], $arr[6], $arr[7], $arr[8], $last );
		++$n;
	}
	return $p;
}
// Build pointers array.
function eos_dp_build_pointer_array( $n, $pointer_id, $title, $description, $el_selector, $click_el, $ofs, $indicated, $edge, $btn_text, $el_class, $last ) {
	$close = ! $last ? '<span class="fdp-pointer-close button">' . __( 'Close', 'freesoul-deactivate-plugins' ) . '</span>&nbsp;' : '';
	return array(
		'pointer_id'   => $pointer_id,
		'content'      => sprintf(
			'<h3>%s</h3><p>%s</p><p class="right">' . $close . '<span id="' . $pointer_id . '-button" class="button" data-add_class="' . $el_class . '" data-indicated="' . $indicated . '" data-click="' . $click_el . '" data-n="' . ( $n + 1 ) . '" data-offset="' . $ofs . '" data-el_selector="' . $el_selector . '" data-next-pointer=".fdp-pointer-' . ( $n + 1 ) . '">%s</span>',
			$title,
			$description,
			$btn_text
		),
		'position'     => array(
			'edge'  => $edge,
			'align' => 'middle',
		),
		'pointerClass' => 'wp-pointer arrow-bottom fdp-pointer fdp-pointer-' . $n,
		'pointerWidth' => 420,
	);
}
