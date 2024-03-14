<?php
/**
 * Template file for WP admin settings page
 */
include( TACPP4_PLUGIN_PATH . '/templates/admin-header.php' );
?>
	<div class="tacpp-wrapper">

		<div class="tacpp-description ">
			<h2><?php esc_html_e( 'Welcome to Terms and Conditions per Product.', 'terms-and-conditions-per-product' ); ?></h2>
			<p><?php esc_html_e( 'This plugin allows you to add specific terms and conditions to individual products on your online WooCommerce store.', 'terms-and-conditions-per-product' ); ?></p>
			<p><?php esc_html_e( 'We hope that this plugin will help you provide your customers with clear, concise, and customizable information about the terms and conditions of each product they purchase from your store. ', 'terms-and-conditions-per-product' ); ?></p>
		</div>
		<div class="tacpp-features">
			<ul>
				<li>&#9989; <?php _e('The ability to add terms and conditions to specific products on your WooCommerce store.', 'terms-and-conditions-per-product');?></li>
				<li>&#9989; <?php _e('A customizable terms and conditions checkbox on the checkout page, so customers must agree to the specific terms before completing their purchase.', 'terms-and-conditions-per-product');?></li>
				<li>&#9989; <?php _e('The option to add terms and conditions per product category or tag. [Premium]', 'terms-and-conditions-per-product');?></li>
				<li>&#9989; <?php _e('The option to show the terms on the product page.', 'terms-and-conditions-per-product');?></li>
				<li>&#9989; <?php _e('The option to show the terms in a modal/popup. [Premium]', 'terms-and-conditions-per-product');?></li>
				<li>&#9989; <?php _e('Great support.', 'terms-and-conditions-per-product');?></li>
			</ul>

		</div>
		<div class="tacpp-link-to-settings">
			<p><a href="<?php echo get_admin_url( '', 'admin.php?page=tacpp_settings' ); ?>"><?php
                    $text = 'Configure Terms and conditions per Product settings now!';
                    esc_html_e( $text ); ?>
				</a>
			</p>
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
	<ul class="column-wrapper">
        <?php
        if ( tacppp_fs()->is_free_plan() ) { ?>
			<li>
				<h3><?php esc_html_e( 'Upgrade to Premium', 'terms-and-conditions-per-product' ); ?></h3>
				<p><?php esc_html_e( 'You are currently using the FREE version of Terms and Conditions per product, Upgrade to Premium, and unlock all the premium features.', 'terms-and-conditions-per-product' ); ?></p>
				<a href="<?php echo tacppp_fs()->get_upgrade_url(); ?>"><?php esc_html_e( 'Get Premium', 'terms-and-conditions-per-product' ); ?></a>
			</li>
        <?php } ?>
		<li>
			<h3><?php esc_html_e( 'Documentation', 'terms-and-conditions-per-product' ); ?></h3>
			<p><?php esc_html_e( 'Just getting started? Don\'t worry we got you covered. Visit the documentation on our website and learn how you can take advantage of our plugin.', 'terms-and-conditions-per-product' ); ?>


			</p>
			<a href="https://tacpp-pro.com/documentation/"><?php esc_html_e( 'View Docs', 'terms-and-conditions-per-product' ); ?></a>
		</li>
		<li>
			<h3><?php esc_html_e( 'Support', 'terms-and-conditions-per-product' ); ?></h3>
			<p><?php esc_html_e( 'Let our support team help you with any problem or inquiry you might have.', 'terms-and-conditions-per-product' ); ?></p>
			<a href="https://wordpress.org/support/plugin/terms-and-conditions-per-product/"><?php esc_html_e( 'WordPress.org Forum', 'terms-and-conditions-per-product' ); ?></a><br/>
			<a href="https://tacpp-pro.com/support/"><?php esc_html_e( 'Open a ticket', 'terms-and-conditions-per-product' ); ?></a>
		</li>
		<li>
			<h3><?php esc_html_e( 'Like us?', 'terms-and-conditions-per-product' ); ?></h3>
			<p><?php esc_html_e( 'If you like using Terms and Conditions per Product please leave us a 5-star rating on our WordPress plugin directory plugin page. It will help us a lot!', 'terms-and-conditions-per-product' ); ?></p>
			<a href="https://wordpress.org/support/plugin/terms-and-conditions-per-product/reviews/#new-post"><?php esc_html_e( 'Rate Us', 'terms-and-conditions-per-product' ); ?></a>
		</li>
	</ul>
	</div>
<?php
