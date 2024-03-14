<?php

/*
Class Name: WOO_F_LOOKBOOK_Admin_System
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2017 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_F_LOOKBOOK_Admin_System {


	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
	}

	public function page_callback() { ?>
		<h2><?php esc_html_e( 'System Status', 'woo-lookbook' ) ?></h2>
		<table cellspacing="0" id="status" class="widefat">
			<tbody>
			<tr>
				<td data-export-label="file_get_contents">file_get_contents</td>
				<td>
					<?php
					if ( function_exists( 'file_get_contents' ) ) {
						echo '<mark class="yes">&#10004; <code class="private"></code></mark> ';
					} else {
						echo '<mark class="error">&#10005; </mark>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="file_put_contents">file_put_contents</td>
				<td>
					<?php
					if ( function_exists( 'file_put_contents' ) ) {
						echo '<mark class="yes">&#10004; <code class="private"></code></mark> ';
					} else {
						echo '<mark class="error">&#10005; </mark>';
					}
					?>

				</td>
			</tr>
		
			<tr>
				<td data-export-label="<?php esc_html_e( 'PHP Time Limit', 'woo-lookbook' ) ?>"><?php esc_html_e( 'PHP Time Limit', 'woo-lookbook' ) ?></td>
				<td><?php echo ini_get( 'max_execution_time' ); ?></td>
			</tr>
			<tr>
				<td data-export-label="<?php esc_html_e( 'PHP Max Input Vars', 'woo-lookbook' ) ?>"><?php esc_html_e( 'PHP Max Input Vars', 'woo-lookbook' ) ?></td>

				<td><?php echo ini_get( 'max_input_vars' ); ?></td>
			</tr>
			<tr>
				<td data-export-label="<?php esc_html_e( 'Memory Limit', 'woo-lookbook' ) ?>"><?php esc_html_e( 'Memory Limit', 'woo-lookbook' ) ?></td>

				<td><?php echo ini_get( 'memory_limit' ); ?></td>
			</tr>
			<tr>
				<td data-export-label="<?php esc_html_e( 'Allow URL Open', 'woo-lookbook' ) ?>"><?php esc_html_e( 'Allow URL Open', 'woo-lookbook' ) ?></td>
				<td>
					<?php
					if ( ini_get( 'allow_url_fopen' ) ) {
						echo '<mark class="yes">&#10004; <code class="private"></code></mark> ';
					} else {
						echo '<mark class="error">&#10005; </mark>';
					}
					?>
			</tr>
			</tbody>
		</table>
	<?php }

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		
		add_submenu_page( 'edit.php?post_type=woocommerce-lookbook', esc_html__( 'System Status', 'woo-lookbook' ), esc_html__( 'System Status', 'woo-lookbook' ), 'manage_options', 'woo-lookbook-system-status', array(
				$this,
				'page_callback'
			) );

	}
} ?>