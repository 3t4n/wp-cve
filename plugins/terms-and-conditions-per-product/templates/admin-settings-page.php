<?php
/**
 * Template file for WP admin settings page
 */
require_once( TACPP4_PLUGIN_PATH . '/templates/admin-header.php' );

if ( ! function_exists( 'woocommerce_admin_fields' ) ) {
    ?>
	<section id="tacpp-admin-no-wc">
		<p><?php esc_html_e( 'Terms and Conditions per product is a WooCommerce extension.', 'terms-and-conditions-per-product' ); ?></p>
		<p><?php esc_html_e( 'Please enable the WooCommerce plugin.', 'terms-and-conditions-per-product' ); ?></p>
	</section>
<?php } else { ?>
	<div id="tacpp-admin-form-wrapper">
		<section id="tacpp-admin-form">
			<form method="POST">
				<div class="postbox ">
					<div class="inside">
                        <?php woocommerce_admin_fields( self::get_settings() ); ?>
					</div>
				</div>

				<div class="inside ">
                    <?php submit_button(); ?>
				</div>
				<div class="inside">
                    <?php
                    if ( ! tacppp_fs()->is_paying_or_trial() ) {
                        printf( __( 'Get the <a href="%s">premium version</a> now!', 'terms-and-conditions-per-product' ),
                            TACPP4_PLUGIN_PRO_BUY_URL
                        );
                    }
                    ?>
				</div>
			</form>
		</section>
		<div class="tacpp-admin-sidebar">
			<div class="tacpp-info">
                <?php
                $paid_type = __( 'Free', 'terms-and-conditions-per-product' );
                if ( tacppp_fs()->is_paying_or_trial() ) {
                    $paid_type = __( 'Premium',
						'terms-and-conditions-per-product' );
                }

                ?>
				<h3><?php esc_html_e( 'Terms and Conditions per product', 'terms-and-conditions-per-product' ); ?></h3>
				<p><?php esc_html_e( 'Version', 'terms-and-conditions-per-product' ); ?>: <?php echo TACPP4_PLUGIN_VERSION; ?> - <?php echo $paid_type; ?></p>
			</div>

			<div class="tacpp-links">
				<h3><?php esc_html_e( 'Useful Links', 'terms-and-conditions-per-product' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Do you like this plugin?', 'terms-and-conditions-per-product' ); ?>
						<a href="https://wordpress.org/support/plugin/terms-and-conditions-per-product/reviews/#new-post"><?php esc_html_e( 'Rate us', 'terms-and-conditions-per-product' ); ?></a>
					</li>
					<li><?php esc_html_e( 'Support', 'terms-and-conditions-per-product' ); ?>:
						<a href="https://tacpp-pro.com/support/"><?php esc_html_e( 'Tacpp-pro.com', 'terms-and-conditions-per-product' ); ?></a>, <a href="https://wordpress.org/support/plugin/terms-and-conditions-per-product/"> <?php esc_html_e( 'WordPress.org', 'terms-and-conditions-per-product' ); ?></a>
					</li>
					<li><a href="https://tacpp-pro.com/documentation"/><?php esc_html_e( 'Documentation', 'terms-and-conditions-per-product' ); ?></a></li>
					<li><a href="https://tacpp-pro.com/changelog/"><?php esc_html_e( 'Changelog', 'terms-and-conditions-per-product' ); ?></a></li>
				</ul>
			</div>
			<div class="tacpp-priorities">
				<h3><?php esc_html_e( 'Terms Priority', 'terms-and-conditions-per-product' ); ?></h3>
				<p><?php esc_html_e( 'The plugin will check for terms and conditions in the following order:', 'terms-and-conditions-per-product' ); ?></p>
				<ol>
					<li><?php esc_html_e( 'Terms and conditions for the variation (if applicable).', 'terms-and-conditions-per-product' ); ?></li>
					<li><?php esc_html_e( 'Terms and conditions for the product.', 'terms-and-conditions-per-product' ); ?></li>
					<li><?php esc_html_e( 'Terms and conditions for the product category and tag.', 'terms-and-conditions-per-product' ); ?></li>
				</ol>
			</div>
		</div>
	</div>
<?php }
