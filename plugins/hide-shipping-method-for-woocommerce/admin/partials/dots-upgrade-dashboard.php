<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package Woo_Hide_Shipping_Methods
 * @since   1.4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="wcpfc-section-left">
		<div class="dotstore-upgrade-dashboard">
			<div class="premium-benefits-section">
				<h2><?php esc_html_e( 'Go Premium to Optimize Shipping and Boost Sales', 'woo-hide-shipping-methods' ); ?></h2>
				<p><?php esc_html_e( 'Three Benefits of Upgrading to Premium', 'woo-hide-shipping-methods' ); ?></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<span><?php esc_html_e('01', 'woo-hide-shipping-methods'); ?></span>
						<h3><?php esc_html_e('Seamless Checkout Experience', 'woo-hide-shipping-methods'); ?></h3>
						<p><?php esc_html_e('Enhance the checkout experience by hiding unnecessary shipping methods, reducing confusion, and simplifying purchases.', 'woo-hide-shipping-methods'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('02', 'woo-hide-shipping-methods'); ?></span>
						<h3><?php esc_html_e('Increase Customer Satisfaction', 'woo-hide-shipping-methods'); ?></h3>
						<p><?php esc_html_e('Enhance satisfaction with personalized shipping options, eliminate irrelevant choices, save customers time, and increase loyalty.', 'woo-hide-shipping-methods'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('03', 'woo-hide-shipping-methods'); ?></span>
						<h3><?php esc_html_e('Boost Conversion and Sales', 'woo-hide-shipping-methods'); ?></h3>
						<p><?php esc_html_e('Drive conversions and boost sales by strategically hiding shipping methods based on specific conditions for your WooCommerce store.', 'woo-hide-shipping-methods'); ?></p>
					</div>
				</div>
			</div>
			<div class="premium-benefits-section unlock-premium-features">
				<p><span><?php esc_html_e( 'Unlock Premium Features', 'woo-hide-shipping-methods' ); ?></span></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<h3><?php esc_html_e('Hide Non-Compatible Shipping', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-virus-slash"></i></span>
						<p><?php esc_html_e('Hide non-compatible shipping methods by entering their values in the designated field, ensuring seamless integration with popular shipping plugins.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-one-img.png'); ?>" alt="<?php echo esc_attr('Hide Non-Compatible Shipping', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Hide non-compatible shipping methods with our plugin by entering their values in the designated field, ensuring smooth integration with popular shipping plugins.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('For instance, if you use a third-party shipping plugin incompatible with ours, you can easily identify the shipping value (e.g., "flat_rate:7") from the front end and paste it into the specified field to hide.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Time-Based Shipping Availability', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-clock"></i></span>
						<p><?php esc_html_e('Gain control over when shipping methods are displayed by selecting specific start and end dates, days of the week, and time frames for hiding them.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-two-img.png'); ?>" alt="<?php echo esc_attr('Time-Based Shipping Availability', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Customize shipping availability and fully control when shipping methods are displayed by setting specific start and end dates, days of the week, and time frames.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Show shipping methods for seasonal promotions or limited-time offers only on specific dates.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Hide shipping methods on weekends or outside of business hours when unavailable.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Location-Specific Hide Shipping', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-earth-americas"></i></span>
						<p><?php esc_html_e('Enhance shipping control by enabling precise location options such as city, state, postcode, and zone to hide shipping methods for specific locations.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-three-img.png'); ?>" alt="<?php echo esc_attr('Location-Specific Hide Shipping', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Easily hide shipping methods for specific locations by utilizing precise options like city, state, postcode, and zone.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Hide shipping methods for a specific city, ensuring accurate delivery options for local customers.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Restrict shipping methods based on postcode to provide custom shipping choices for different areas.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Attribute-Specific Hide Shipping', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-box-archive"></i></span>
						<p><?php esc_html_e('Effortlessly hide shipping methods based on product attributes, such as color and size, for a tailored shipping experience.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-four-img.png'); ?>" alt="<?php echo esc_attr('Attribute-Specific Hide Shipping', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Easily hide shipping methods based on product attributes like color and size, providing customized shipping options.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Hide specific shipping methods for products with the attribute "Color = Red" offering alternative shipping choices.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Restrict shipping methods for products with the attribute "Size = Medium" providing relevant shipping choices.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('User Role-Based Hide Shipping', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-users"></i></span>
						<p><?php esc_html_e('Easily hide shipping methods based on various user roles, including consumer, shop manager, customers, and more.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-five-img.png'); ?>" alt="<?php echo esc_attr('User Role-Based Hide Shipping', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Easily hide shipping methods based on various user roles like consumers, sellers, shop managers, and more to enhance the shipping experience for every user.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Restrict certain shipping methods for regular consumers while offering exclusive options for shop managers.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Offer premium shipping options to customers, such as express delivery or free shipping, while hiding normal shipping methods.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Cart Weight-Based Shipping', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-scale-unbalanced-flip"></i></span>
						<p><?php esc_html_e('Gain control over shipping methods by enabling advanced cart-specific conditions such as weight, length, width, and more to hide shipping options.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-six-img.png'); ?>" alt="<?php echo esc_attr('Cart Weight-Based Shipping', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Take advantage of advanced cart-specific conditions like weight, length, width, and more to tailor shipping options to your customers\' needs.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Hide certain shipping methods for oversized items based on the cart\'s weight and dimensions.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Customize shipping options based on the cart\'s weight, ensuring accurate shipping choices for different order sizes.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Hide Shipping Based on Payment', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-credit-card"></i></span>
						<p><?php esc_html_e('Hide shipping methods based on the payment method selected by customers during checkout, optimizing the shipping experience.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-seven-img.png'); ?>" alt="<?php echo esc_attr('Payment Method-Based Hide Shipping', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Hide shipping methods based on the payment method chosen by customers during checkout, ensuring a seamless shopping experience.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Hide expensive shipping methods like international shipping when customers choose the "Cash on Delivery" payment option.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Offer free shipping to customers who choose credit card payments, and encourage them to use this convenient and secure payment method.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Advanced Hide Shipping Options', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-cart-plus"></i></span>
						<p><?php esc_html_e('Unlock advanced hide shipping options to create customized ranges based on the specific product, category, cart quantity, weight, or subtotal.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-eight-img.png'); ?>" alt="<?php echo esc_attr('Advanced Hide Shipping Options', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Utilize advanced hide shipping rules to define specific ranges based on product, category, cart quantity, weight, or subtotal.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Customize shipping options based on the cart\'s subtotal, offering free shipping for orders above a certain value.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Hide specific shipping methods for products in the "Weak Items" category based on their weight.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('One Click Import & Export', 'woo-hide-shipping-methods'); ?></h3>
						<span><i class="fa fa-file-arrow-down"></i></span>
						<p><?php esc_html_e('Easily import and export the hide shipping rules for seamless management and sharing of the data across your WooCommerce store.', 'woo-hide-shipping-methods'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-nine-img.png'); ?>" alt="<?php echo esc_attr('One Click Import & Export', 'woo-hide-shipping-methods'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Easily import and export hide shipping rules, ensuring seamless data management and sharing in your WooCommerce store.', 'woo-hide-shipping-methods'); ?></p>
												<ul>
													<li><?php esc_html_e('Import hide shipping rules from a staging site to a production site with just one click, saving time and effort.', 'woo-hide-shipping-methods'); ?></li>
													<li><?php esc_html_e('Export hide shipping rules for backup purposes or to share them with colleagues or clients.', 'woo-hide-shipping-methods'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="upgrade-to-premium-btn">
				<a href="<?php echo esc_url('https://bit.ly/44ZswGY') ?>" target="_blank" class="button button-primary"><?php esc_html_e('Upgrade to Premium', 'woo-hide-shipping-methods'); ?><svg id="Group_52548" data-name="Group 52548" xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 27.263 24.368"><path id="Path_199491" data-name="Path 199491" d="M333.833,428.628a1.091,1.091,0,0,1-1.092,1.092H316.758a1.092,1.092,0,1,1,0-2.183h15.984a1.091,1.091,0,0,1,1.091,1.092Z" transform="translate(-311.117 -405.352)" fill="#fff"></path><path id="Path_199492" data-name="Path 199492" d="M312.276,284.423h0a1.089,1.089,0,0,0-1.213-.056l-6.684,4.047-4.341-7.668a1.093,1.093,0,0,0-1.9,0l-4.341,7.668-6.684-4.047a1.091,1.091,0,0,0-1.623,1.2l3.366,13.365a1.091,1.091,0,0,0,1.058.825h18.349a1.09,1.09,0,0,0,1.058-.825l3.365-13.365A1.088,1.088,0,0,0,312.276,284.423Zm-4.864,13.151H290.764l-2.509-9.964,5.373,3.253a1.092,1.092,0,0,0,1.515-.4l3.944-6.969,3.945,6.968a1.092,1.092,0,0,0,1.515.4l5.373-3.253Z" transform="translate(-285.455 -280.192)" fill="#fff"></path></svg></a>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
<?php 
