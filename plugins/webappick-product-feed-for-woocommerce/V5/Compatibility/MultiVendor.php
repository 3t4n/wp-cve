<?php

namespace CTXFeed\V5\Compatibility;

class MultiVendor
{
	public function __construct() {
		add_filter( 'woocommerce_account_menu_items', [$this,'woo_feed_add_view_feeds_tab_menu'] );
		add_action( 'init', [$this,'woo_feed_add_endpoint_for_view_feeds_menu'] );
		add_action( 'woocommerce_account_view-feeds_endpoint', [$this,'woo_feed_view_vendor_feeds_endpoint_add_content'] );
		add_filter( 'generate_rewrite_rules', [$this,'woo_feed_add_rewrite_rules_for_view_feeds_tab'] );
	}
	/**
	 * Add View Feed Tabs to My Account
	 *
	 * @param $menu_links
	 *
	 * @return array
	 */
	public function woo_feed_add_view_feeds_tab_menu( $menu_links ) {
		$user = wp_get_current_user();
		if ( self::woo_feed_is_multi_vendor() && !in_array('customer', $user->roles) ) {
			$menu_links = array_slice( $menu_links, 0, 5, true ) + array( 'view-feeds' => 'Product Feed' ) + array_slice( $menu_links, 5, 1, true );
		}

		return $menu_links;
	}

	public function woo_feed_add_endpoint_for_view_feeds_menu() {
		add_rewrite_endpoint( 'view-feeds', EP_PAGES );
	}

	public function woo_feed_view_vendor_feeds_endpoint_add_content() {
		global $wpdb;
		$query  = $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s", 'wf_feed_%' );
		$result = $wpdb->get_results( $query, 'ARRAY_A' ); // phpcs:ignore
		$user_id = get_current_user_id();
		?>
		<div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th style="width: 20%;"><?php esc_html_e( 'Feed Name', 'woo-feed' ); ?></th>
					<th><?php esc_html_e( 'Feed Link', 'woo-feed' ); ?></th>
					<th style="width: 30%;"><?php esc_html_e( 'Actions', 'woo-feed' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php if ( empty( $result ) || ! is_array( $result ) ) { ?>
					<tr>
						<td colspan="3"
							style="text-align: center;"><?php esc_html_e( 'No Feed Available', 'woo-feed' ); ?></td>
					</tr>
					<?php
				} else {
					foreach ( $result as $feed ) {
						$info = maybe_unserialize( get_option( $feed['option_name'] ) );
						if ( isset( $info['feedrules']['vendors'] ) ) {
							if ( in_array( $user_id, $info['feedrules']['vendors'] ) ) {
								$fileName = $info['feedrules']['filename'];
								$fileURL  = $info['url'];
								?>
								<tr>
									<td><?php echo esc_html( $fileName ); ?></td>
									<td style="color: rgb(0, 135, 121); font-weight: bold;"><?php echo esc_html( $fileURL ); ?></td>
									<td><a href="<?php echo esc_url( $fileURL ); ?>" class="button button-primary"
										   target="_blank"><?php esc_html_e( 'View/Download', 'woo-feed' ); ?></a>
									</td>
								</tr>
								<?php
							}
						}
					}
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public function woo_feed_add_rewrite_rules_for_view_feeds_tab( $wp_rewrite ) {
		$feed_rules = array(
			'my-account/?$' => 'index.php?account-page=true',
		);

		$wp_rewrite->rules = $wp_rewrite->rules + $feed_rules;

		return $wp_rewrite->rules;
	}

	public function woo_feed_get_cached_data( $key ) {
		if ( empty( $key ) ) {
			return false;
		}

		return get_transient( '__woo_feed_cache_' . $key );
	}

	/**
	 * Check any multi-vendor plugin installed or not
	 * Check if any of following multi-vendor plugin class exists
	 *
	 * @link https://wedevs.com/dokan/
	 * @link https://www.wcvendors.com/
	 * @link https://yithemes.com/themes/plugins/yith-woocommerce-multi-vendor/
	 * @link https://multivendorx.com/
	 * @link https://wordpress.org/plugins/wc-multivendor-marketplace/
	 * @return bool
	 */
	public static function woo_feed_is_multi_vendor() {
		return apply_filters(
				'woo_feed_is_multi_vendor',
				(
						class_exists( 'WeDevs_Dokan' ) ||
						class_exists( 'WC_Vendors' ) ||
						class_exists( 'YITH_Vendor' ) ||
						class_exists( 'MVX' ) ||
						class_exists( 'WCMp' ) ||
						class_exists( 'WCFMmp' )
				)
		);
	}

}
