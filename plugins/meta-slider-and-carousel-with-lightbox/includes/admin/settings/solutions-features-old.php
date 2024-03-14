<?php
/**
 * Plugin Solutions & Features Page
 *
 * @package Meta slider and carousel with lightbox
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$igsp_add_link = add_query_arg( array( 'post_type' =>WP_IGSP_POST_TYPE ), admin_url( 'post-new.php' ) );
?>

<div id="wrap">
	<div class="igsp-sf-wrap">
		<div class="igsp-sf-inr">
		
			<div class="igsp-sf-features-section igsp-sf-team igsp-sf-center">
				<p style="font-weight: bold !important; font-size:20px !important;"><span style="color: #50c621;">Essential Plugin Bundle</span> + Any Leading Builders (Avada / Elementor / Divi / <br>VC-WPBakery / Site Origin / Beaver) = <span style="background: #50c621;color: #fff;padding: 2px 10px;">WordPress Magic</span></p>
				<h4 style="color: #333; font-size: 14px; font-weight: 700;">Over 15K+ Customers Using <span style="color: #50c621 !important;">Essential Plugin Bundle</span></h4>
				<a href="<?php echo esc_url( WP_IGSP_PLUGIN_BUNDLE_LINK ); ?>" target="_blank" class="igsp-sf-btn igsp-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
			</div>
			<div class="igsp-sf-features-section igsp-sf-team igsp-sf-center">
				<h1 class="igsp-sf-heading">Powerful Team Behind <span class="igsp-sf-blue">Meta Slider</span> Including in <span class="igsp-sf-blue">Essential Plugin Bundle</span></h1>
				<div class="igsp-sf-cont">Alone we can do so little; together we can do so much. Our love language is helping small businesses grow and compete with the big guys.  Every time you see growth in your business, our little hearts go flip-flop!</div>
				<p></p>
				<div class="igsp-sf-cont">This is why I wanted to introduce you to <span class="igsp-sf-blue">Anoop Ranawat & Team</span> at EssentialPlugin.com</div>
				<img class="igsp-sf-image" src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/wpos-team.png" alt="wpos team" />
				<a href="<?php echo esc_url( WP_IGSP_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="igsp-sf-btn igsp-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
			</div>
			
			<h1 class="igsp-sf-heading">Build and display multiple responsive <span class="igsp-sf-blue">image sliders & carousels </span> to create animated image for increase website engagement. </h1>

			<!-- Start - Welcome Box -->
			<div class="igsp-sf-welcome-wrap">
				<div class="igsp-sf-welcome-inr igsp-sf-center">					
						
						<h5 class="igsp-sf-content">Experience <span class="igsp-sf-blue">5 Layouts</span>, <span class="igsp-sf-blue">15+ stunning designs</span>. </h5>
						<h5 class="igsp-sf-content"><span class="igsp-sf-blue">7,000+ </span>websites are using <span class="igsp-sf-blue">Meta Slider</span>.</h5>
						<a href="<?php echo esc_url( $igsp_add_link ); ?>" class="igsp-sf-btn">Launch Meta Slider With Free Features</a> <br /><b>OR</b>
						<p style="font-size: 14px;"><span class="igsp-sf-blue">Meta Slider </span>Including in <span class="igsp-sf-blue">Essential Plugin Bundle</span></p>
						<a href="<?php echo esc_url( WP_IGSP_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="igsp-sf-btn igsp-sf-btn-orange"> <span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
						<div class="igsp-rc-wrap">
							<div class="igsp-rc-inr igsp-rc-bg-box">
								<div class="igsp-rc-icon">
									<img src="<?php echo esc_url( WP_IGSP_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
								</div>
								<div class="igsp-rc-cont">
									<h3>14 Days Refund Policy</h3>
									<p>14-day No Question Asked Refund Guarantee</p>
								</div>
							</div>
							<div class="igsp-rc-inr igsp-rc-bg-box">
								<div class="igsp-rc-icon">
									<img src="<?php echo esc_url( WP_IGSP_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
								</div>
								<div class="igsp-rc-cont">
									<h3>Include Done-For-You Meta Gallery Setup</h3>
								<p>Our  experts team will design 1 free Meta Gallery for you as per your need.</p>
								</div>
							</div>
						</div>
					<div class="igsp-sf-welcome-left">
						
					</div>
					<div class="igsp-sf-welcome-right">
						
					</div>
				</div>
			</div>
			<!-- End - Welcome Box -->

			<!-- Start - Logo Showcase - Features -->
			<div class="igsp-features-section">
				<div class="igsp-sf-center">
					<h1 class="igsp-sf-heading">Powerful Pro Features, Simplified</h1>
					
				</div>
				<div class="igsp-sf-welcome-wrap igsp-sf-center">	
					<div class="igsp-features-box-wrap">
						<ul class="igsp-features-box-grid">
							<li>
							<div class="igsp-popup-icon"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/popup-icon/slider.png" /></div>
							Meta Slider View</li>
							<li>
							<div class="igsp-popup-icon"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/popup-icon/slider-carousel.png" /></div>
							Meta Slider Carousel View</li>
							<li>
							<div class="igsp-popup-icon"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/popup-icon/Variablewidth.png" /></div>
							Meta Slider Variable Width</li>
							<li>
							<div class="igsp-popup-icon"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/popup-icon/slider-with-navigation.png" /></div>
							Meta Slider with Navigation</li>
							<li>
							<div class="igsp-popup-icon"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/popup-icon/slider-with-navigation.png" /></div>
							Variable Width with Navigation</li>
							<li>
							<div class="igsp-popup-icon"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/popup-icon/Centermode.png" /></div>
							Meta Slider With Centermode</li>
						</ul>
					</div>
					<p style="font-size: 14px;"><span class="igsp-sf-blue">Meta Slider </span>Including in <span class="igsp-sf-blue">Essential Plugin Bundle</span></p>
					<a href="<?php echo esc_url( WP_IGSP_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="igsp-sf-btn igsp-sf-btn-orange"> <span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
					<div class="igsp-rc-wrap">
						<div class="igsp-rc-inr igsp-rc-bg-box">
							<div class="igsp-rc-icon">
								<img src="<?php echo esc_url( WP_IGSP_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="igsp-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="igsp-rc-inr igsp-rc-bg-box">
							<div class="igsp-rc-icon">
								<img src="<?php echo esc_url( WP_IGSP_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="igsp-rc-cont">
								<h3>Include Done-For-You Meta Gallery Setup</h3>
								<p>Our  experts team will design 1 free Meta Gallery for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Logo Showcase - Features -->

			<!-- Start - Pagebuilder Section -->
			<div class="igsp-sf-testimonial-wrap">
				<div class="igsp-sf-center igsp-sf-features-ttl">
					<h1 class=" igsp-sf-heading">Seamless Integration With All Major <span class=" igsp-sf-blue">Page Builders</span></h1>
					<div class=" igsp-sf-cont  igsp-sf-center">Compatible with Gutenberg, DIVI, Elementor, Avada, VC/WPBakery etc page builder/themes</div>
					<div class=" igsp-sf-welcome-wrap  igsp-sf-center">
						<img src="<?php echo esc_url( WP_IGSP_URL ); ?>assets/images/page-builder-icon.png" alt="page-builder-icon" title="page-builder-icon" />	
					</div>
				</div>
			</div>
			<!-- End - pagebuilder Section -->

			<!-- Start - Testimonial Section -->
			<div class="igsp-sf-testimonial-wrap">
				<div class="igsp-center igsp-features-ttl">
					<h1 class="igsp-sf-heading">Looking for a Reason to Use <span class="igsp-sf-blue">Essential Plugin </span>with Meta Slider?</h1>	
					<div class="igsp-sf-cont igsp-sf-center"> Here are 10+...</div>	
				</div>
				<div class="igsp-testimonial-section-inr">
					<div class="igsp-testimonial-box-wrap">
						<div class="igsp-testimonial-box-grid">
							<h3 class="igsp-testimonial-title">Excellent plugin</h3>
							<div class="igsp-testimonial-desc">The plugin is great!!!It’s just that when I try to update gallery specific to a post, it’s not saving the update even though I clicked the update button.</div>
							<div class="igsp-testimonial-clnt">@feitan</div>
							<div class="igsp-testimonial-rating"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/rating.png" /></div>
						</div>
						<div class="igsp-testimonial-box-grid">
							<h3 class="igsp-testimonial-title">Very useful tool</h3>
							<div class="igsp-testimonial-desc">It is a carousel easy to use and well programmed. Highly recommended. And it has excellent customer service, too.</div>
							<div class="igsp-testimonial-clnt">@guijimu</div>
							<div class="igsp-testimonial-rating"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/rating.png" /></div>
						</div>
						<div class="igsp-testimonial-box-grid">
							<h3 class="igsp-testimonial-title">quick support solved my problem</h3>
							<div class="igsp-testimonial-desc">Many thanks for your quickly support. My problem report has been urgently solved.</div>
							<div class="igsp-testimonial-clnt">@mrtmrtmrt</div>
							<div class="igsp-testimonial-rating"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/rating.png" /></div>
						</div>
						<div class="igsp-testimonial-box-grid">
							<h3 class="igsp-testimonial-title">Best Carrousel Plugin Fast, Simple and Elegant</h3>
							<div class="igsp-testimonial-desc">Thanks a lot for sharing this demo version. Even the demo version is far better than any other paid plugins I’ve tried before. I will buy the pro version soon. It is very fast, well coded, easy to install & use. It is also very elegant. Customization possible with shortcode variables. Perfect solution for a Carrousel plugin ! Thanks.</div>
							<div class="igsp-testimonial-clnt">@edorsan</div>
							<div class="igsp-testimonial-rating"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/rating.png" /></div>
						</div>
						<div class="igsp-testimonial-box-grid">
							<h3 class="igsp-testimonial-title">Very nice plugin with great tech support!</h3>
							<div class="igsp-testimonial-desc">The plugin is easy to set up. The plugin developer is really helpful and was able to resolve our issue. Thank you!</div>
							<div class="igsp-testimonial-clnt">@michfm</div>
							<div class="igsp-testimonial-rating"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/rating.png" /></div>
						</div>
						<div class="igsp-testimonial-box-grid">
							<h3 class="igsp-testimonial-title">Awesome help!</h3>
							<div class="igsp-testimonial-desc">thanks for the great help – in less than 24 hours, I had the answer to my confusion</div>
							<div class="igsp-testimonial-clnt">@terrie1103</div>
							<div class="igsp-testimonial-rating"><img src="<?php echo esc_url( WP_IGSP_URL ); ?>/assets/images/rating.png" /></div>
						</div>
					</div>
					<a href="https://wordpress.org/support/plugin/meta-slider-and-carousel-with-lightbox/reviews/?filter=5" target="_blank" class="igsp-sf-btn"><span class="dashicons dashicons-star-filled"></span> View All Reviews</a> OR 
					<p style="font-size: 14px;"><span class="igsp-sf-blue">Meta Slider </span>Including in <span class="igsp-sf-blue">Essential Plugin Bundle</span></p>
					<a href="<?php echo esc_url( WP_IGSP_PLUGIN_BUNDLE_LINK ); ?>"  target="_blank" class="igsp-sf-btn igsp-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
					<div class="igsp-rc-wrap">
						<div class="igsp-rc-inr igsp-rc-bg-box">
							<div class="igsp-rc-icon">
								<img src="<?php echo esc_url( WP_IGSP_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="igsp-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="igsp-rc-inr igsp-rc-bg-box">
							<div class="igsp-rc-icon">
								<img src="<?php echo esc_url( WP_IGSP_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="igsp-rc-cont">
								<h3>Include Done-For-You Meta Gallery Setup</h3>
								<p>Our experts team will design 1 free Meta Gallery for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Testimonial Section -->
		</div>
	</div><!-- end .igsp-sf-wrap -->
</div><!-- end .wrap -->