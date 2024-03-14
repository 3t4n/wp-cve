<?php
/**
 * Plugin Premium Offer Page
 *
 * @package Timeline and History Slider
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
	<h2 style="font-size: 24px; text-align: center; margin-bottom:25px;"><span class="wpostahs-sf-blue">Timeline and History slider </span>Including in <span class="wpostahs-sf-blue">Essential Plugin Bundle</span></h2>
	<style>
		/* Table CSS */
		table, th, td {border: 1px solid #d1d1d1;}
		table.wpos-plugin-list{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; margin-bottom: 50px;}
		.wpos-plugin-list th {width: 16%; background: #2271b1; color: #fff; }
		.wpos-plugin-list td {vertical-align: top;}
		.wpos-plugin-type { text-align: left; color: #fff; font-weight: 700; padding: 0 10px; margin: 15px 0; }
		.wpos-slider-list { font-size: 14px; font-weight: 500; padding: 0 10px 0 25px; }
		.wpos-slider-list li {text-align: left; font-size: 13px; list-style: disc;}

		.wpostahs-sf-blue{color:#6c63ff; font-weight:bold;}
		.wpostahs-sf-btn{display: inline-block; font-size: 18px; padding: 10px 25px; border-radius: 100px;  background-color: #46b450; border-color: #46b450; color: #fff !important; font-weight: 600; text-decoration: none;}
		.wpostahs-sf-btn-orange{ background-color: #FF1000; border-color: #FF1000 ;}
		.wpostahs-sf-btn:hover,
		.wpostahs-sf-btn:focus{background-color: #3fa548; border-color: #3fa548;}
		.wpostahs-sf-btn-orange:hover,
		.wpostahs-sf-btn-orange:focus {background-color: #D01003 ; border-color: #D01003 ;}

		.wpostahs-favourite-section{text-align: center; margin-bottom:30px}
		.wpostahs-favourite-heading{margin:0; margin-bottom:10px; font-size:24px; font-weight:bold;}
		.wpostahs-favourite-sub-heading{font-size: 28px !important; font-weight: 700 !important; letter-spacing: -1px !important; text-align: center; padding:0 !important; margin-bottom: 5px !important;}
		.wpostahs-favourite-section span{font-size: 16px;color: #000;display: inline-block;width: 100%;}
		.wpostahs-favourite-section span i{color: #50c621; font-weight: 600; vertical-align: middle;}
		.wpostahs-favourite-section span img{display: inline-block; vertical-align: middle; max-width: 100%; height: auto;}
	</style>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
					<table class="wpos-plugin-list">
						<thead>
							<tr>
								<th><h3 class="wpos-plugin-type">Image Slider</h3></th>
								<th><h3 class="wpos-plugin-type">Marketing</h3></th>
								<th><h3 class="wpos-plugin-type">Photo Album</h3></th>
								<th><h3 class="wpos-plugin-type">Publication</h3></th>
								<th><h3 class="wpos-plugin-type">Showcase</h3></th>
								<th><h3 class="wpos-plugin-type">WooCommerce</h3></th>
							</tr>
							<tr>
								<td>
									<ul class="wpos-slider-list">
										<li>Accordion and Accordion Slider</li>
										<li>WP Slick Slider and Image Carousel</li>
										<li>WP Responsive Recent Post Slider/Carousel</li>
										<li>WP Logo Showcase Responsive Slider and Carousel</li>
										<li>WP Featured Content and Slider</li>
										<li>Trending/Popular Post Slider and Widget</li>
										<li><span style="color:#2271b1; font-weight: bold;">Timeline and History slider</span></li>
										<li>Meta slider and carousel with lightbox</li>
										<li>Post Category Image With Grid and Slider</li>
									</ul>
								</td>
								<td>
									<ul class="wpos-slider-list">
										<li>Popup Anything - A Marketing Popup and Lead Generation Conversions</li>
										<li>Countdown Timer Ultimate</li>
									</ul>
								</td>
								<td>
									<ul class="wpos-slider-list">
										<li>Album and Image Gallery plus Lightbox</li>
										<li>Portfolio and Projects</li>
										<li>Video gallery and Player</li>
									</ul>
								</td>
								<td>
									<ul class="wpos-slider-list">
										<li>WP Responsive Recent Post Slider/Carousel</li>
										<li>WP News and Scrolling Widgets</li>
										<li>WP Blog and Widget</li>
										<li>Blog Designer - Post and Widget</li>
										<li>Trending/Popular Post Slider and Widget</li>
										<li>WP Featured Content and Slider</li>
										<li><span style="color:#2271b1; font-weight: bold;">Timeline and History slider</span></li>
										<li>Testimonial Grid and Testimonial Slider plus Carousel with Rotator Widget</li>
										<li>Post Ticker Ultimate</li>
										<li>Post grid and filter ultimate</li>
									</ul>
								</td>
								<td>
									<ul class="wpos-slider-list">
										<li>Testimonial Grid and Testimonial Slider plus Carousel with Rotator Widget</li>
										<li>Team Slider and Team Grid Showcase plus Team Carousel</li>
										<li>Hero Banner Ultimate</li>
										<li>WP Logo Showcase Responsive Slider and Carousel</li>
									</ul>
								</td>
								<td>
									<ul class="wpos-slider-list">
										<li>Product Slider and Carousel with Category for WooCommerce</li>
										<li>Product Categories Designs for WooCommerce</li>
										<li>Popup Anything - A Marketing Popup and Lead Generation Conversions</li>
										<li>Countdown Timer Ultimate</li>
									</ul>
								</td>
							</tr>
						</thead>
					</table>

					<div class="wpostahs-favourite-section">
						<h3 class="wpostahs-sf-blue  wpostahs-favourite-heading">Use Essential Plugin Bundle</h3>
						<h1 class="wpostahs-favourite-sub-heading">With Your Favourite Page Builders</h1>
						<span><i class="dashicons dashicons-yes"></i> = <img src="<?php echo esc_url( WPOSTAHS_URL ); ?>assets/images/essential-logo-small.png" width="15" height="15" /> Essential Plugin Bundle contain many more layouts and designs</span>
					</div>

					<div style="text-align: center;">
						<img style="width: 100%; margin-bottom:30px;" src="<?php echo esc_url( WPOSTAHS_URL ); ?>assets/images/image-upgrade.png" alt="image-upgrade" title="image-upgrade" />
						<div style="font-size: 14px; margin-bottom:10px;"><span class="wpostahs-sf-blue">Timeline Slider </span>Including in <span class="wpostahs-sf-blue">Essential Plugin Bundle</span></div>
						<a href="<?php echo esc_url(WPOSTAHS_PLUGIN_LINK_UPGRADE); ?>" target="_blank" class="wpostahs-sf-btn wpostahs-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Bundle Deal</a>
					</div>
			</div>
		</div>
	</div>
</div>