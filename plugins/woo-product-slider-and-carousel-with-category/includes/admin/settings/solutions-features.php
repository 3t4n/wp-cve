<?php
/**
 * Plugin Solutions & Features Page
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$wcpscwc_add_link = add_query_arg( array( 'post_type' =>WCPSCWC_POST_TYPE ), admin_url( 'post-new.php' ) );
?>

<div id="wrap">
	<div class="wcpscwc-sf-wrap">
		<div class="wcpscwc-sf-inr">
			<!-- Start - Welcome Box -->

			<div class="wcpscwc-sf-features-section wcpscwc-sf-team wcpscwc-sf-center">
				<p style="font-weight: bold !important; font-size:20px !important;"><span style="color: #50c621;">Essential Plugin Bundle</span> + Any Leading Builders (Avada / Elementor / Divi / <br>VC-WPBakery / Site Origin / Beaver) = <span style="background: #50c621;color: #fff;padding: 2px 10px;">WordPress Magic</span></p>
				<h4 style="color: #333; font-size: 14px; font-weight: 700;">Over 15K+ Customers Using <span style="color: #50c621 !important;">Essential Plugin Bundle</span></h4>
				<a href="<?php echo esc_url( WCPSCWC_PLUGIN_BUNDLE_LINK ); ?>" target="_blank" class="wcpscwc-sf-btn wcpscwc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
			</div>

			<div class="wcpscwc-sf-features-section wcpscwc-sf-team wcpscwc-sf-center">
				<h1 class="wcpscwc-sf-heading">Powerful Team Behind <span class="wcpscwc-sf-blue">Product Slider and Carousel</span> Including in <span class="wcpscwc-sf-blue">Essential Plugin Bundle</span></h1>
				<div class="wcpscwc-sf-cont">Alone we can do so little; together we can do so much. Our love language is helping small businesses grow and compete with the big guys.  Every time you see growth in your business, our little hearts go flip-flop!</div>
				<p></p>
				<div class="wcpscwc-sf-cont">This is why I wanted to introduce you to <span class="wcpscwc-sf-blue">Essential Plugin Team</span> at EssentialPlugin.com</div>
				<img class="wcpscwc-sf-image" src="<?php echo esc_url( WCPSCWC_URL ); ?>/assets/images/wpos-team.png" alt="wpos team" />
				<a href="<?php echo esc_url( WCPSCWC_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="wcpscwc-sf-btn wcpscwc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
			</div>

			<h1 class="wcpscwc-sf-heading">Slide your <span class="wcpscwc-sf-blue">WooCommerce Products, Best Selling and Featured Products</span></h1>

			<!-- Start - Welcome Box -->
			<div class="wcpscwc-sf-welcome-wrap">
				<div class="wcpscwc-sf-welcome-inr wcpscwc-sf-center">
						<h5 class="wcpscwc-sf-content">Experience <span class="wcpscwc-sf-blue">8 Layouts</span>, <span class="wcpscwc-sf-blue">50+ stunning designs</span>. </h5>
						<h5 class="wcpscwc-sf-content"><span class="wcpscwc-sf-blue">20,000+ </span>websites are using <span class="wcpscwc-sf-blue">Product Slider and Carousel</span>.</h5>
						<a href="<?php echo esc_url( $wcpscwc_add_link ); ?>" class="wcpscwc-sf-btn">Launch Product Slider and Carousel With Free Features</a> <br /><b>OR</b> <br />
						<p style="font-size: 14px;"><span class="wcpscwc-sf-blue">Product Slider and Carousel </span>Including in <span class="wcpscwc-sf-blue">Essential Plugin Bundle</span></p>
						<a href="<?php echo esc_url( WCPSCWC_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="wcpscwc-sf-btn wcpscwc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
						<div class="wcpscwc-rc-wrap">
							<div class="wcpscwc-rc-inr wcpscwc-rc-bg-box">
								<div class="wcpscwc-rc-icon">
									<img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
								</div>
								<div class="wcpscwc-rc-cont">
									<h3>14 Days Refund Policy</h3>
									<p>14-day No Question Asked Refund Guarantee</p>
								</div>
							</div>
							<div class="wcpscwc-rc-inr wcpscwc-rc-bg-box">
								<div class="wcpscwc-rc-icon">
									<img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
								</div>
								<div class="wcpscwc-rc-cont">
									<h3>Include Done-For-You Product Slider Setup</h3>
								<p>Our  experts team will design 1 free Product Slider for you as per your need.</p>
								</div>
							</div>
						</div>
					<div class="wcpscwc-sf-welcome-left"></div>
					<div class="wcpscwc-sf-welcome-right"></div>
				</div>
			</div>
			<!-- End - Welcome Box -->

			<!-- Start - Product Slider and Carousel with Category for WooCommerce - Features -->
			<div class="wcpscwc-features-section">
				<div class="wcpscwc-sf-center">
					<h1 class="wcpscwc-sf-heading">Powerful Pro Features, Simplified</h1>
				</div>
				<div class="wcpscwc-sf-welcome-wrap wcpscwc-sf-center">
					<div class="wcpscwc-features-box-wrap">
						<ul class="wcpscwc-features-box-grid">
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-grid.png" /></div>
							Woo Product Grid View</li>
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-carousel.png" /></div>
							Woo Product Slider View</li>
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-centermode.png" /></div>
							Woo Product with Center Mode</li>
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-carousel.png" /></div>
							Woo Featured Product</li>
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-carousel.png" /></div>
							Woo Best Selling Product</li>
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-carousel.png" /></div>
							Woo Sale Price Product</li>
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-carousel.png" /></div>
							Woo Regular Price Product</li>
							<li>
							<div class="wcpscwc-popup-icon"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/woo-carousel.png" /></div>
							Woo Rated Product Slider</li>
						</ul>
					</div>

					<p style="font-size: 14px;"><span class="wcpscwc-sf-blue">Product Slider and Carousel </span>Including in <span class="wcpscwc-sf-blue">Essential Plugin Bundle</span></p>

					<a href="<?php echo esc_url( WCPSCWC_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="wcpscwc-sf-btn wcpscwc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>

					<div class="wcpscwc-rc-wrap">
						<div class="wcpscwc-rc-inr wcpscwc-rc-bg-box">
							<div class="wcpscwc-rc-icon">
								<img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="wcpscwc-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="wcpscwc-rc-inr wcpscwc-rc-bg-box">
							<div class="wcpscwc-rc-icon">
								<img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="wcpscwc-rc-cont">
								<h3>Include Done-For-You Product Slider Setup</h3>
								<p>Our  experts team will design 1 free Product Slider for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Logo Showcase - Features -->

			<!-- Start - Pagebuilder Section -->
			<div class="wcpscwc-sf-testimonial-wrap">
				<div class="wcpscwc-sf-center wcpscwc-sf-features-ttl">
					<h1 class=" wcpscwc-sf-heading">Seamless Integration With All Major <span class=" wcpscwc-sf-blue">Page Builders</span></h1>
					<div class=" wcpscwc-sf-cont  wcpscwc-sf-center">Compatible with Gutenberg, DIVI, Elementor, Avada, VC/WPbakery etc page builder/themes</div>
					<div class=" wcpscwc-sf-welcome-wrap  wcpscwc-sf-center">
						<img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/page-builder-icon.png" alt="page-builder-icon" title="page-builder-icon" />	
					</div>	
				</div>
			</div>
			<!-- End - pagebuilder Section -->

			<!-- Start - Testimonial Section -->
			<div class="wcpscwc-sf-testimonial-wrap">
				<div class="wcpscwc-sf-center wcpscwc-sf-features-ttl">
					<h1 class="wcpscwc-sf-heading">Looking for a Reason to Use <span class="wcpscwc-sf-blue">Product Slider and Carousel</span>?</h1>
					<div class="wcpscwc-sf-cont wcpscwc-sf-center"> Here are 40+...</div>
				</div>

				<div class="wcpscwc-testimonial-section-inr">
					<div class="wcpscwc-testimonial-box-wrap">
						<div class="wcpscwc-testimonial-box-grid">
							<h3 class="wcpscwc-testimonial-title">Excellent</h3>
							<div class="wcpscwc-testimonial-desc">The only free plugin that allows the products being filtered by categories, and have a awesome support.</div>
							<div class="wcpscwc-testimonial-clnt">@discopegre</div>
							<div class="wcpscwc-testimonial-rating"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wcpscwc-testimonial-box-grid">
							<h3 class="wcpscwc-testimonial-title">Great Plugin, easy to use</h3>
							<div class="wcpscwc-testimonial-desc">Thanks for this, great plugin, easy to use with shortcodes, working perfectly and not breaking my custom CSS for products! </div>
							<div class="wcpscwc-testimonial-clnt">@alizotiwebsolutions</div>
							<div class="wcpscwc-testimonial-rating"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wcpscwc-testimonial-box-grid">
							<h3 class="wcpscwc-testimonial-title">What I need! and great support</h3>
							<div class="wcpscwc-testimonial-desc">I had try some similar plugin for this carousel, but this one is the best. and the support is nice + fast. thank you</div>
							<div class="wcpscwc-testimonial-clnt">@mseka</div>
							<div class="wcpscwc-testimonial-rating"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wcpscwc-testimonial-box-grid">
							<h3 class="wcpscwc-testimonial-title">best service</h3>
							<div class="wcpscwc-testimonial-desc">very good and helpful service very happy to use the plugin </div>
							<div class="wcpscwc-testimonial-clnt">@vidhi123</div>
							<div class="wcpscwc-testimonial-rating"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wcpscwc-testimonial-box-grid">
							<h3 class="wcpscwc-testimonial-title">Great Plug in and excellent support</h3>
							<div class="wcpscwc-testimonial-desc">Great Plug in and excellent support, amazing support</div>
							<div class="wcpscwc-testimonial-clnt">@mitchelly</div>
							<div class="wcpscwc-testimonial-rating"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wcpscwc-testimonial-box-grid">
							<h3 class="wcpscwc-testimonial-title">easy to use and great support</h3>
							<div class="wcpscwc-testimonial-desc">It is very easy to add sliders and customise them. Support is helpful. You can’t ask more for a free plugin!</div>
							<div class="wcpscwc-testimonial-clnt">@francesca111</div>
							<div class="wcpscwc-testimonial-rating"><img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/rating.png" /></div>
						</div>
					</div>

					<a href="https://wordpress.org/support/plugin/woo-product-slider-and-carousel-with-category/reviews/?filter=5" target="_blank" class="wcpscwc-sf-btn"><span class="dashicons dashicons-star-filled"></span> View All Reviews</a> OR 
					
					<p style="font-size: 14px;"><span class="wcpscwc-sf-blue">Product Slider and Carousel </span>Including in <span class="wcpscwc-sf-blue">Essential Plugin Bundle</span></p>
					
					<a href="<?php echo esc_url( WCPSCWC_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="wcpscwc-sf-btn wcpscwc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
					
					<div class="wcpscwc-rc-wrap">
						<div class="wcpscwc-rc-inr wcpscwc-rc-bg-box">
							<div class="wcpscwc-rc-icon">
								<img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="wcpscwc-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="wcpscwc-rc-inr wcpscwc-rc-bg-box">
							<div class="wcpscwc-rc-icon">
								<img src="<?php echo esc_url( WCPSCWC_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="wcpscwc-rc-cont">
								<h3>Include Done-For-You Product Slider Setup</h3>
								<p>Our  experts team will design 1 free Product Slider for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Testimonial Section -->
		</div>
	</div><!-- end .wcpscwc-sf-wrap -->
</div><!-- end .wrap -->