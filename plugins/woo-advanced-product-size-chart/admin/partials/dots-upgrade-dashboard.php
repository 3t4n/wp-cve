<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package SCFW_Size_Chart_For_Woocommerce
 * @since   2.4.3
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="wcpfc-section-left">
		<div class="dotstore-upgrade-dashboard">
			<div class="premium-benefits-section">
				<h2><?php esc_html_e( 'Go Premium to Increase Order Value', 'size-chart-for-woocommerce' ); ?></h2>
				<p><?php esc_html_e( 'Three Benefits of Upgrading to Premium', 'size-chart-for-woocommerce' ); ?></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<span><?php esc_html_e('01', 'size-chart-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Amplify Customer Satisfaction', 'size-chart-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Boost customer satisfaction with a comprehensive size chart that ensures confident purchasing decisions based on accurate sizing information.', 'size-chart-for-woocommerce'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('02', 'size-chart-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Boost Conversion Rates', 'size-chart-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Increase conversion rates by providing a clear and detailed product size chart that reduces uncertainty and boosts customer confidence in purchasing decisions.', 'size-chart-for-woocommerce'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('03', 'size-chart-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Enhance Product Confidence', 'size-chart-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('A product size chart empowers customers to make informed size decisions, instilling confidence and increasing purchase likelihood.', 'size-chart-for-woocommerce'); ?></p>
					</div>
				</div>
			</div>
			<div class="premium-benefits-section unlock-premium-features">
				<p><span><?php esc_html_e( 'Unlock Premium Features', 'size-chart-for-woocommerce' ); ?></span></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<h3><?php esc_html_e('Easy Size Chart User Access', 'size-chart-for-woocommerce'); ?></h3>
						<span><i class="fa fa-user"></i></span>
						<p><?php esc_html_e('Grant specific user roles, such as shop managers, access to manage size charts by providing the necessary permissions for seamless control.', 'size-chart-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'size-chart-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(SCFW_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-one-img.png'); ?>" alt="<?php echo esc_attr('Easy Size Chart User Access', 'size-chart-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Grant access to specific user roles, such as shop managers, to manage size charts and reduce the admin headache by providing them with the necessary permissions.', 'size-chart-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Empower shop managers to update and customize size charts based on their specific inventory and customer needs.', 'size-chart-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Enable vendors to maintain accurate and relevant size charts for their specific products.', 'size-chart-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('One Click Import Export', 'size-chart-for-woocommerce'); ?></h3>
						<span><i class="fa fa-download"></i></span>
						<p><?php esc_html_e('Easily import and export size charts for seamless management and sharing of size chart templates across your WooCommerce store.', 'size-chart-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'size-chart-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(SCFW_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-two-img.png'); ?>" alt="<?php echo esc_attr('One Click Import Export', 'size-chart-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Effortlessly manage and share size chart templates across your WooCommerce store using the import and export feature with just a few clicks.', 'size-chart-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Import bulk size charts effortlessly between staging to the production site in minutes once you want to make the changes live.', 'size-chart-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Export all the size charts templates for backup purposes or share them with colleagues or clients.', 'size-chart-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Country Based Size Chart', 'size-chart-for-woocommerce'); ?></h3>
						<span><i class="fa fa-globe"></i></span>
						<p><?php esc_html_e('Optimize the visibility of your size charts and enhance the user experience by displaying relevant size information based on customer location.', 'size-chart-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'size-chart-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(SCFW_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-three-img.png'); ?>" alt="<?php echo esc_attr('Country Based Size Chart', 'size-chart-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Improve size chart visibility and enhance the user experience by displaying location-based relevant size chart information.', 'size-chart-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Personalized size chart recommendation for US customers to enhance user experience.', 'size-chart-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Display specific size chart guidance for French shoppers with different measurements.', 'size-chart-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Flexible Size Chart Position', 'size-chart-for-woocommerce'); ?></h3>
						<span><i class="fa fa-map-pin"></i></span>
						<p><?php esc_html_e('Customize the position of the size chart pop-up button for optimal visibility and engagement - before the summary, after "add to cart", or after the product meta.', 'size-chart-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'size-chart-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(SCFW_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-four-img.png'); ?>" alt="<?php echo esc_attr('Flexible Size Chart Position', 'size-chart-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Define the size chart link placement before the product summary, before/after the cart button, or after the product meta.', 'size-chart-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Place the size chart button before the summary to aid purchase decisions.', 'size-chart-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Showcase the size chart button after the “add to cart” button for easy reference.', 'size-chart-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Size Chart Compatibility', 'size-chart-for-woocommerce'); ?></h3>
						<span><i class="fa fa-code"></i></span>
						<p><?php esc_html_e('Utilize a shortcode to seamlessly display size charts on your custom product template to make it compatible with enhancing the user experience.', 'size-chart-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'size-chart-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(SCFW_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-five-img.png'); ?>" alt="<?php echo esc_attr('Size Chart Compatibility', 'size-chart-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Enhance the user experience by seamlessly integrating size charts into your custom product templates using a shortcode.', 'size-chart-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Provide valuable sizing information on your unique product pages, boosting customer confidence and informed decision-making.', 'size-chart-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Integrate size charts effortlessly using the shortcode in the custom product templates of WPBakery, Elementor, or any other page builder.', 'size-chart-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Own Chart Table Design', 'size-chart-for-woocommerce'); ?></h3>
						<span><i class="fa fa-table"></i></span>
						<p><?php esc_html_e('Effortlessly customize size chart layout, including table head color, row color, etc., to create visually stunning size charts that captivate your customers.', 'size-chart-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'size-chart-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(SCFW_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-six-img.png'); ?>" alt="<?php echo esc_attr('Own Chart Table Design', 'size-chart-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Customize size chart tables easily to match visually with your theme, captivating customers.', 'size-chart-for-woocommerce'); ?></p>
												<p><?php esc_html_e('You can adjust table head colors, row colors, and more to match your brand aesthetic.', 'size-chart-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Design attractive size charts with custom colors with different color effects, like table cell hover, etc.', 'size-chart-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Create glamorous size charts with silver table heads and soft silver even rows for effective table visualization.', 'size-chart-for-woocommerce'); ?></li>
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
				<a href="<?php echo esc_url('https://www.thedotstore.com/woocommerce-advanced-product-size-charts/') ?>" target="_blank" class="button button-primary"><?php esc_html_e('Upgrade to Premium', 'size-chart-for-woocommerce'); ?><svg id="Group_52548" data-name="Group 52548" xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 27.263 24.368"><path id="Path_199491" data-name="Path 199491" d="M333.833,428.628a1.091,1.091,0,0,1-1.092,1.092H316.758a1.092,1.092,0,1,1,0-2.183h15.984a1.091,1.091,0,0,1,1.091,1.092Z" transform="translate(-311.117 -405.352)" fill="#fff"></path><path id="Path_199492" data-name="Path 199492" d="M312.276,284.423h0a1.089,1.089,0,0,0-1.213-.056l-6.684,4.047-4.341-7.668a1.093,1.093,0,0,0-1.9,0l-4.341,7.668-6.684-4.047a1.091,1.091,0,0,0-1.623,1.2l3.366,13.365a1.091,1.091,0,0,0,1.058.825h18.349a1.09,1.09,0,0,0,1.058-.825l3.365-13.365A1.088,1.088,0,0,0,312.276,284.423Zm-4.864,13.151H290.764l-2.509-9.964,5.373,3.253a1.092,1.092,0,0,0,1.515-.4l3.944-6.969,3.945,6.968a1.092,1.092,0,0,0,1.515.4l5.373-3.253Z" transform="translate(-285.455 -280.192)" fill="#fff"></path></svg></a>
			</div>
		</div>
	</div>
	</div>
</div>
</div>
</div>
<?php 
