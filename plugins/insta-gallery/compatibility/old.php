<?php

use QuadLayers\IGG\Models\Account as Models_Account;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

add_filter(
	'init',
	function() {

		/**
		 * Compatibility with the old version
		 */
		if ( ! isset( $_REQUEST['accounts'] ) || ! is_array( $_REQUEST['accounts'] ) ) {
			return;
		}

		$_REQUEST['accounts'] = array_map(
			function( $value ) {

				if ( isset( $value['token_type'] ) ) {
					$value['access_token_type'] = $value['token_type'];
					unset( $value['token_type'] );
				}

				return $value;
			},
			$_REQUEST['accounts']
		);
	},
	-10
);

/**
 * Compatibility with the old version
 */
add_filter(
	'option_insta_gallery_accounts',
	function( $accounts ) {

		foreach ( $accounts as $i => $account ) {
			$is_old = isset( $account['token_type'] );

			if ( empty( $account['id'] ) ) {
				$accounts[ $i ]['id'] = $i;
			}

			if ( isset( $account['id'] ) && 'integer' === gettype( $account['id'] ) ) {
				$accounts[ $i ]['id'] = strval( $account['id'] );
			}

			if ( ! $is_old ) {
				continue;
			}

			if ( $account['token_type'] ) {
				$accounts[ $i ]['access_token_type'] = $account['token_type'];
				unset( $accounts[ $i ]['token_type'] );
			}
		}

		return $accounts;
	}
);

/**
 * Compatibility with the old version
 */
add_filter(
	'option_insta_gallery_feeds',
	function( $feeds ) {

		foreach ( $feeds as $i => $feed ) {
			$is_old = isset( $feed['username'] );

			if ( isset( $feed['account_id'] ) && 'integer' === gettype( $feed['account_id'] ) ) {
				$feeds[ $i ]['account_id'] = strval( $feed['account_id'] );
			}

			if ( ! $is_old ) {
				continue;
			}

			if ( $feed['username'] ) {
				$feeds[ $i ]['account_id'] = $feed['username'];
				unset( $feeds[ $i ]['username'] );
			}

			if ( isset( $feed['type'] ) ) {
				$feeds[ $i ]['source'] = $feed['type'];
				unset( $feeds[ $i ]['type'] );
			}

			if ( isset( $feed['popup'] ) ) {
				$feeds[ $i ]['modal'] = $feed['popup'];
				unset( $feeds[ $i ]['popup'] );
			}

			if ( isset( $feed['box']['profile'] ) ) {
				$feeds[ $i ]['profile']['display'] = $feed['box']['profile'];
				unset( $feed['box']['profile'] );
			}
		}
		return $feeds;
	}
);

add_action(
	'init',
	function() {

		if ( ! is_admin() ) {
			return;
		}

		$old_menus = array( 'qligg', 'qligg_account', 'qligg_feeds', 'qligg_setting' );

		if ( ! isset( $_GET['page'] ) || ! in_array( $_GET['page'], $old_menus ) ) {
			return;
		}

		switch ( $_GET['page'] ) {
			case 'qligg':
				wp_safe_redirect( admin_url( 'admin.php?page=qligg_backend' ) );
				// exit;
			case 'qligg_account':
				if ( ! isset( $_GET['accounts'] ) ) {
					wp_safe_redirect( admin_url( 'admin.php?page=qligg_backend&tab=accounts' ) );
					// exit;
				}
			case 'qligg_feeds':
				wp_safe_redirect( admin_url( 'admin.php?page=qligg_backend&tab=feeds' ) );
				// exit;
			case 'qligg_setting':
				wp_safe_redirect( admin_url( 'admin.php?page=qligg_backend&tab=settings' ) );
				// exit;
		}

	}
);

/**
 * Apply the filter to 'render_block_data' to include the missing account_id
 */
add_filter(
	'render_block_data',
	function ( $parsed_block ) {
		// Check if the block type is 'qligg/box'
		if ( $parsed_block['blockName'] === 'qligg/box' ) {

			$is_old = isset( $parsed_block['attrs']['username'] );

			// Add the missing account_id to the block attributes
			if ( ! isset( $parsed_block['attrs']['account_id'] ) && ! isset( $parsed_block['attrs']['username'] ) ) {
				$models_account = new Models_Account();
				$accounts       = array_values( (array) $models_account->get() );
				if ( isset( $accounts[0]['id'] ) ) {
					$parsed_block['attrs']['account_id'] = $accounts[0]['id'];
				}
			}
			// Add the missing account_id to the block attributes from username
			if ( ! isset( $parsed_block['attrs']['account_id'] ) && isset( $parsed_block['attrs']['username'] ) ) {
				$parsed_block['attrs']['account_id'] = $parsed_block['attrs']['username'];
			}
			// Add the missing source to the block attributes from type
			if ( ! isset( $parsed_block['attrs']['source'] ) && isset( $parsed_block['attrs']['type'] ) ) {
				$parsed_block['attrs']['source'] = $parsed_block['attrs']['type'];
			}
			// Add the missing modal to the block attributes from popup
			if ( ! isset( $parsed_block['attrs']['modal'] ) && isset( $parsed_block['attrs']['popup'] ) ) {
				$parsed_block['attrs']['modal'] = $parsed_block['attrs']['popup'];
			}
		}

		return $parsed_block;
	},
	10,
	2
);

/**
 * Add the old attributes to ensure compatibility with old blocks
 */
add_filter(
	'register_block_type_args',
	function ( $args, $block_name ) {
		if ( $block_name == 'qligg/box' ) {
			$args['attributes']['username'] = array(
				'type' => 'string',
			);
			$args['attributes']['type']     = array(
				'type' => 'string',
			);
			$args['attributes']['popup']    = array(
				'type' => 'object',
			);
		}
		return $args;
	},
	10,
	2
);

/**
 * Register widget
 */
add_action(
	'widgets_init',
	function() {
		require_once 'widget.php';
		register_widget( 'QLIGG_Widget' );
	}
);

if ( ! class_exists( 'QLIGG', false ) ) {
	class QLIGG {

	}
}
