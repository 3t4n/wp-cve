<?php
/**
 * Plugin settings
 *
 * @uses PMCS_Admin::get_tabs Get registerd tabs.
 * @param PMCS_Admin $setings
 * @package pmcs
 */

?>
<div class="wrap">
	<h1><?php _e( 'Currency Switcher for WooCommerce', 'pmcs' ); ?></h1>
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
	<?php
	$admin_url = admin_url( 'admin.php?page=pm_currency_switcher' );
	foreach ( $registered_tabs as $key => $setting ) {
		$link = add_query_arg( array( 'tab' => $key ), $admin_url ); // @codingStandardsIgnoreLine
		$active = '';
		if ( $current_tab == $key || ( ! $current_tab && 'general' == $key ) ) {
			$active = ' nav-tab-active';
		}
		echo '<a href="' . esc_url( $link ) . '" class="nav-tab' . esc_attr( $active ) . '">' . esc_html( $setting->title ) . '</a>';
	}



		$wrapper_class = 'pmcs-limit wrap woocommerce';
	

	?>
	</nav>
	<hr class="wp-header-end">
	<div class="<?php echo esc_attr( $wrapper_class ); ?>">
		<h1 class="screen-reader-text"><?php echo esc_html( 'tab' ); ?></h1>
		<form method="post" id="mainform" class="pmcs-form-<?php echo esc_attr( $current_tab ); ?>" action="" enctype="multipart/form-data">
			<?php
			WC_Admin_Settings::show_messages();
			if ( isset( $_POST['save'] ) ) {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e( 'Your settings have been saved.', 'pmcs' ); ?></p>
				</div>
				<?php
			}

			if ( isset( $registered_tabs[ $current_tab ] ) ) {
				WC_Admin_Settings::output_fields( $registered_tabs[ $current_tab ]->get_settings() );
			}

			if ( method_exists( $registered_tabs[ $current_tab ], 'before_end' ) ) {
				$registered_tabs[ $current_tab ]->before_end();
			}

			if ( pmcs()->admin->is_show_submit_btn() ) {
				?>
			<p class="submit-btn">
				<button name="save" class="button-primary woocommerce-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
				<?php wp_nonce_field( 'pmcs-settings' ); ?>
			</p>
				<?php
			}
			?>

		</form>
	</div>
</div>
