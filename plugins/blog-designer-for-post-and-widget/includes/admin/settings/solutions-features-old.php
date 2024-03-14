<?php
/**
 * Plugin Solutions & Features Page
 *
 * @package Blog Designer - Post and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$bdpw_add_link = add_query_arg( array( 'post_type' =>BDPW_POST_TYPE ), admin_url( 'post-new.php' ) );
?>

<div id="wrap">
	<div class="bdpw-sf-wrap">
		<div class="bdpw-sf-inr">
			<div class="bdpw-sf-features-section bdpw-sf-team bdpw-sf-center">
				<p style="font-weight: bold !important; font-size:20px !important;"><span style="color: #50c621;">Essential Plugin Bundle</span> + Any Leading Builders (Avada / Elementor / Divi /<br> VC-WPBakery / Site Origin / Beaver) = <span style="background: #50c621;color: #fff;padding: 2px 10px;">WordPress Magic</span></p>
				<h4 style="color: #333; font-size: 14px; font-weight: 700;">Over 15K+ Customers Using <span style="color: #50c621 !important;">Essential Plugin Bundle</span></h4>
				<a href="<?php echo esc_url(BDPW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="bdpw-sf-btn bdpw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
			</div>
			<div class="bdpw-sf-features-section bdpw-sf-team bdpw-sf-center">					
				<h1 class="bdpw-sf-heading">Powerful Team Behind <span class="bdpw-sf-blue">Album Gallery</span> Including in <span class="bdpw-sf-blue">Essential Plugin Bundle</span></h1>
				<div class="bdpw-sf-cont">Alone we can do so little; together we can do so much. Our love language is helping small businesses grow and compete with the big guys.  Every time you see growth in your business, our little hearts go flip-flop!</div>
				<p></p>
				<div class="bdpw-sf-cont">This is why I wanted to introduce you to <span class="bdpw-sf-blue">Essential Plugin Team</span> at EssentialPlugin.com</div>
				<img class="bdpw-sf-image" src="<?php echo esc_url(BDPW_URL); ?>/assets/images/wpos-team.png" alt="wpos team" />
				<a href="<?php echo esc_url(BDPW_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="bdpw-sf-btn bdpw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
			</div>
			
			<h1 class="bdpw-sf-heading">Display customizable  <span class="bdpw-sf-blue">blog layouts, vertical scrolling blog widgets</span> in the most engaging and customized way</h1>
		
			<!-- Start - Welcome Box -->
			<div class="bdpw-sf-welcome-wrap">
				<div class="bdpw-sf-welcome-inr bdpw-sf-center">					
						
						<h5 class="bdpw-sf-content">Experience <span class="bdpw-sf-blue">7 Layouts</span>, <span class="bdpw-sf-blue">70+ stunning designs</span>. </h5>
						<h5 class="bdpw-sf-content"><span class="bdpw-sf-blue">10,000+ </span>websites are using <span class="bdpw-sf-blue">Blog Designer</span>.</h5>
						<a href="<?php echo esc_url( $bdpw_add_link ); ?>" class="bdpw-sf-btn">Launch Blog Designer With Free Features</a> <br /><b>OR</b> <br /> 
						<p style="font-size: 14px;"><span class="bdpw-sf-blue">Blog Designer </span>Including in <span class="bdpw-sf-blue">Essential Plugin Bundle</span></p>
						<a href="<?php echo esc_url(BDPW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="bdpw-sf-btn bdpw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
						<div class="bdpw-rc-wrap">
							<div class="bdpw-rc-inr bdpw-rc-bg-box">
								<div class="bdpw-rc-icon">
									<img src="<?php echo esc_url( BDPW_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
								</div>
								<div class="bdpw-rc-cont">
									<h3>14 Days Refund Policy</h3>
									<p>14-day No Question Asked Refund Guarantee</p>
								</div>
							</div>
							<div class="bdpw-rc-inr bdpw-rc-bg-box">
								<div class="bdpw-rc-icon">
									<img src="<?php echo esc_url( BDPW_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
								</div>
								<div class="bdpw-rc-cont">
									<h3>Include Done-For-You Blog Setup</h3>
									<p>Our experts team will design 1 free blog page for you as per your need.</p>
								</div>
							</div>
						</div>
					<div class="bdpw-sf-welcome-left"></div>
					<div class="bdpw-sf-welcome-right"></div>
				</div>
			</div>
			<!-- End - Welcome Box -->

			<!-- Start - Blog Designer - Post and Widget/Carousel - Features -->
			<div class="bdpw-features-section">
				<div class="bdpw-sf-center">
					<h1 class="bdpw-sf-heading">Powerful Pro Features, Simplified</h1>
				</div>
				<div class="bdpw-sf-welcome-wrap bdpw-sf-center">	
					<div class="bdpw-features-box-wrap">
						<ul class="bdpw-features-box-grid">
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/blog-grid.png" /></div>
							Blog Grid View</li>
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/blog-grid.png" /></div>
							Recent Blog Grid View</li>
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/slider.png" /></div>
							Blog Slider View</li>
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/blog-list-view.png" /></div>
							Blog List View</li>
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/carousel.png" /></div>
							Blog Ticker View</li>
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/grid-box.png" /></div>
							Blog Gridbox View</li>
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/grid-box.png" /></div>
							Blog Gridbox Slider View</li>
							<li>
							<div class="bdpw-popup-icon"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/popup-icon/blog-masonry.png" /></div>
							Blog Masonry View</li>
						</ul>
					</div>
					<p style="font-size: 14px;"><span class="bdpw-sf-blue">Blog Designer </span>Including in <span class="bdpw-sf-blue">Essential Plugin Bundle</span></p>
					<a href="<?php echo esc_url(BDPW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="bdpw-sf-btn bdpw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
					<div class="bdpw-rc-wrap">
						<div class="bdpw-rc-inr bdpw-rc-bg-box">
							<div class="bdpw-rc-icon">
								<img src="<?php echo esc_url( BDPW_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="bdpw-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="bdpw-rc-inr bdpw-rc-bg-box">
							<div class="bdpw-rc-icon">
								<img src="<?php echo esc_url( BDPW_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="bdpw-rc-cont">
								<h3>Include Done-For-You Blog Designer Slider Setup</h3>
								<p>Our  experts team will design 1 free Blog Designer Slider for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Logo Showcase - Features -->
			
			<!-- Start - Pagebuilder Section -->
			
			<div class="bdpw-sf-testimonial-wrap">
				<div class="bdpw-sf-center bdpw-sf-features-ttl">
					<h1 class=" bdpw-sf-heading">Seamless Integration With All Major <span class=" bdpw-sf-blue">Page Builders</span></h1>	
					<div class=" bdpw-sf-cont  bdpw-sf-center">Compatible with Gutenberg, DIVI, Elementor, Avada, VC/WPbakery etc page builder/themes</div>
					<div class=" bdpw-sf-welcome-wrap  bdpw-sf-center">
						<img src="<?php echo esc_url( BDPW_URL ); ?>assets/images/page-builder-icon.png" alt="page-builder-icon" title="page-builder-icon" />	
					</div>	
				</div>
	
			</div>
			<!-- End - pagebuilder Section -->

			<!-- Start - Testimonial Section -->
			<div class="bdpw-sf-testimonial-wrap">
				<div class="bdpw-center bdpw-features-ttl">
					<h1 class="bdpw-sf-heading">Looking for a Reason to Use <span class="bdpw-sf-blue">Essential Plugin </span>with Blog Designer Slider?</h1>
					<div class="bdpw-sf-cont bdpw-sf-center"> Here are 80+...</div>
				</div>
				<div class="bdpw-testimonial-section-inr">
					<div class="bdpw-testimonial-box-wrap">
						<div class="bdpw-testimonial-box-grid">
							<h3 class="bdpw-testimonial-title">Excellent!</h3>
							<div class="bdpw-testimonial-desc">Produces a very clean, well ordered display. Currently using the free version. Excellent configuration options.</div>
							<div class="bdpw-testimonial-clnt">@kenhaynes_it</div>
							<div class="bdpw-testimonial-rating"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="bdpw-testimonial-box-grid">
							<h3 class="bdpw-testimonial-title">Exactly what I need</h3>
							<div class="bdpw-testimonial-desc">Great plugin, good support. They should add more customization options. We need to add extra css in order to customize colour, font size…</div>
							<div class="bdpw-testimonial-clnt">@danilobd</div>
							<div class="bdpw-testimonial-rating"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="bdpw-testimonial-box-grid">
							<h3 class="bdpw-testimonial-title">Great Work! Great support!</h3>
							<div class="bdpw-testimonial-desc">Plugin works very well! Great & superb technical support, within 5min i had a solution. I strongly recommend this plugin.</div>
							<div class="bdpw-testimonial-clnt">@modospace</div>
							<div class="bdpw-testimonial-rating"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="bdpw-testimonial-box-grid">
							<h3 class="bdpw-testimonial-title">Fantastic plugin with great features</h3>
							<div class="bdpw-testimonial-desc">Not only is the free version fantastic and really easy to setup. The developers just included some extra features within a few hours following a questions I posted. Great job!</div>
							<div class="bdpw-testimonial-clnt">@timothyabgreen</div>
							<div class="bdpw-testimonial-rating"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="bdpw-testimonial-box-grid">
							<h3 class="bdpw-testimonial-title">Great technical support, sensational contact</h3>
							<div class="bdpw-testimonial-desc">Great technical support, sensational contact, I recommend with full conscience. The perfect product.</div>
							<div class="bdpw-testimonial-clnt">@adiex</div>
							<div class="bdpw-testimonial-rating"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="bdpw-testimonial-box-grid">
							<h3 class="bdpw-testimonial-title">Fantastic support</h3>
							<div class="bdpw-testimonial-desc">We had an issue with the plug-in after installation. I contacted support and they worked with me to find the problem and eventually resolve the issue. The support was top-notch and I really appreciated the effort they put into making sure the problem was resolved. The plug-in itself works great and we are looking forward to using it.</div>
							<div class="bdpw-testimonial-clnt">@mah2681</div>
							<div class="bdpw-testimonial-rating"><img src="<?php echo esc_url(BDPW_URL); ?>assets/images/rating.png" /></div>
						</div>
					</div>
					<a href="https://wordpress.org/support/plugin/blog-designer-for-post-and-widget/reviews/?filter=5" target="_blank" class="bdpw-sf-btn"><span class="dashicons dashicons-star-filled"></span> View All Reviews</a> OR 
					<p style="font-size: 14px;"><span class="bdpw-sf-blue">Blog Designer </span>Including in <span class="bdpw-sf-blue">Essential Plugin Bundle</span></p>
					<a href="<?php echo esc_url(BDPW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="bdpw-sf-btn bdpw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
					<div class="bdpw-rc-wrap">
						<div class="bdpw-rc-inr bdpw-rc-bg-box">
							<div class="bdpw-rc-icon">
								<img src="<?php echo esc_url( BDPW_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="bdpw-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="bdpw-rc-inr bdpw-rc-bg-box">
							<div class="bdpw-rc-icon">
								<img src="<?php echo esc_url( BDPW_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="bdpw-rc-cont">
								<h3>Include Done-For-You Blog Designer Slider Setup</h3>
								<p>Our  experts team will design 1 free Blog Designer Slider for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Testimonial Section -->
		</div>
	</div><!-- end .bdpw-sf-wrap -->
</div><!-- end .wrap -->