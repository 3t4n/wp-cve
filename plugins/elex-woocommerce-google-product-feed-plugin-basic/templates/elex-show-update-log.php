<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function elex_gpf_show_update_log() {
	$setting_tab_fields2 = get_option( 'elex_settings_tab_fields_data' );
	?>
	<div class="postbox elex-gpf-table-box elex-gpf-table-box-main " id="elex_gpf_update_logs" >
			<h1 id="elex_gpf_log_heading"> <?php esc_html_e( 'Generating your feed. Please do not refresh...', 'elex-product-feed' ); ?></h1>
			<div id='elex_gpf_logs_val' ></div>
			<div id='elex_gpf_logs_loader' ></div><br><br>

			<button id='elex_gpf_finish_cancel' style="background-color: gray; margin-bottom: 1%; color: white; width: 10%;" class='button button-large'><span class="update-text"><?php esc_html_e( 'Cancel', 'elex-product-feed' ); ?></span></button>
			<?php
			$dir_url = '';
		
				$upload_dir = wp_upload_dir();
				$base = $upload_dir['basedir'];
				$path = $base . '/elex-product-feed/';
				$dir_url = $upload_dir['baseurl'] . '/elex-product-feed/';
			
			?>
			<button onclick="elex_gpf_view_feed_fun('<?php echo esc_html( $dir_url ); ?>')" id='elex_gpf_continue_to_view_feed' style="color: white; width: 10%; display: none;" class='button button-large button-primary'><span class="update-text"><?php esc_html_e( 'View Feed', 'elex-product-feed' ); ?></span></button>
			<button onclick="elex_gpf_view_excluded_prods_fun('simple')" id='elex_gpf_view_excl_simple' style="color: white; width: 20%; display: none; white-space: normal;" class='button button-large button-primary'><span class="update-text"><?php esc_html_e( 'View Excluded Simple Products', 'elex-product-feed' ); ?></span></button>
			<button onclick="elex_gpf_view_excluded_prods_fun('variation')" id='elex_gpf_view_excl_variation' style="color: white; width: 20%; display: none; white-space: normal;" class='button button-large button-primary'><span class="update-text"><?php esc_html_e( 'View Excluded Variations', 'elex-product-feed' ); ?></span></button>
			<button id='elex_gpf_continue_to_manage_feed' style=" color: white; width: 10%; display: none;" class='button button-large button-primary'><span class="update-text"><?php esc_html_e( 'Manage Feeds', 'elex-product-feed' ); ?></span></button>

	</div>
	<?php
}
elex_gpf_show_update_log();
