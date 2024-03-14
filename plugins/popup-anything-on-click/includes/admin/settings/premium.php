<?php
/**
 * Plugin Premium Offer Page
 *
 * @package Popup Anything on Click
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">

	<h2><?php esc_html_e( 'Popup Anything Pro - A Marketing Popup', 'popup-anything-on-click' ); ?></h2>

	<style>
		.wpos-plugin-pricing-table thead th h2{font-weight: 400; font-size: 2.4em; line-height:normal; margin:0px; color: #2ECC71;}
		.wpos-plugin-pricing-table thead th h2 + p{font-size: 1.25em; line-height: 1.4; color: #999; margin:5px 0 5px 0;}

		table.wpos-plugin-pricing-table{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}

		.wpos-plugin-pricing-table th, .wpos-plugin-pricing-table td{font-size:14px; line-height:normal; color:#444; vertical-align:middle; padding:12px;}
		.wpos-about-epb-table td{padding: 12px 12px 60px;position: relative;}

		.wpos-plugin-pricing-table colgroup:nth-child(1) { width: 31%; border: 0 none; }
		.wpos-plugin-pricing-table colgroup:nth-child(2) { width: 22%; border: 1px solid #ccc; }
		.wpos-plugin-pricing-table colgroup:nth-child(3) { width: 25%; border: 10px solid #2ECC71; }

		/* Tablehead */
		.wpos-plugin-pricing-table thead th {background-color: #fff; background:linear-gradient(to bottom, #ffffff 0%, #ffffff 100%); text-align: center; position: relative; border-bottom: 1px solid #ccc; padding: 1em 0 1em; font-weight:400; color:#999;}
		.wpos-plugin-pricing-table thead th:nth-child(1) {background: transparent;}
		.wpos-plugin-pricing-table thead th:nth-child(3) p{color:#000;}	
		.wpos-plugin-pricing-table thead th p.promo {font-size: 14px; color: #fff; position: absolute; bottom:0; left: -17px; z-index: 1000; width: 100%; margin: 0; padding: .625em 17px .75em; background-color: #ca4a1f; box-shadow: 0 2px 4px rgba(0,0,0,.25); border-bottom: 1px solid #ca4a1f;}
		.wpos-plugin-pricing-table thead th p.promo:before {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 0 7px 7px 0; border-color: transparent #900 transparent transparent; bottom: -7px; left: 0;}
		.wpos-plugin-pricing-table thead th p.promo:after {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 7px 7px 0 0; border-color: #900 transparent transparent transparent; bottom: -7px; right: 0;}
		.wpos-about-epb-table{margin:10px 0;}
		.wpos-about-epb-table, .wpos-about-epb-table td{border:1px solid #ccc;}
		.wpos-about-epb-table th {background: #ff4081 !important; font-size:18px; font-weight:100%;  color:#fff; border-bottom: 1px solid #ccc !important;  padding:10px !important; color:#fff !important;}
		.wpos-about-epb-table th, .wpos-about-epb-table td{text-align:left !important; vertical-align:top;}
		.wpos-about-epb-table td ul{list-style:none;}		
		.wpos-about-epb-table ul li::before {content: "\2022";color:#ff2700 ;font-weight: bold;display: inline-block;width: 15px;}
		
		/* Tablebody */
		.wpos-plugin-pricing-table tbody th{background: #fff; border-left: 1px solid #ccc; font-weight: 600;}
		.wpos-plugin-pricing-table tbody th span{font-weight: normal; font-size: 87.5%; color: #999; display: block;}

		.wpos-plugin-pricing-table tbody td{background: #fff; text-align: center;}
		.wpos-plugin-pricing-table tbody td .dashicons{height: auto; width: auto; font-size:30px;}
		.wpos-plugin-pricing-table tbody td .dashicons-no-alt{color: #ff2700;}
		.wpos-plugin-pricing-table tbody td .dashicons-yes{color: #2ECC71;}

		.wpos-plugin-pricing-table tbody tr:nth-child(even) th,
		.wpos-plugin-pricing-table tbody tr:nth-child(even) td { background: #f5f5f5; border: 1px solid #ccc; border-width: 1px 0 1px 1px; }
		.wpos-plugin-pricing-table tbody tr:last-child td {border-bottom: 0 none;}

		/* Table Footer */
		.wpos-plugin-pricing-table tfoot th, .wpos-plugin-pricing-table tfoot td{text-align: center; border-top: 1px solid #ccc;}
		.wpos-plugin-pricing-table tfoot a, .wpos-plugin-pricing-table thead a{font-weight: 600; color: #fff; text-decoration: none; text-transform: uppercase; display: inline-block; padding: 1em 2em; background: #ff2700; border-radius: .2em;}
		a.epb-cutom-button{font-weight: 600; color: #fff; text-decoration: none; text-transform: uppercase; display: inline-block; padding: 1em 2em; background: #ff2700; border-radius: .2em;}
		.wpos-epb{color:#ff2700 !important;}

		.wp-plugin-icon{position: absolute;bottom: 15px;}
		.wp-plugin-icon img {margin: 0 5px;}
		.wp-plugin-icon span{font-size: 14px;font-style: italic;color: red;display: block;}
		.wp-icons {margin-bottom: 20px;text-align: center;}
		.wp-icons img{vertical-align: top;}
		.wp-icons ul{margin: 0;}
		.wp-icons li{display: inline-block;margin-right: 5px;}
		
		/* SideBar */
		.wpos-sidebar .wpos-epb-wrap{background:#0055fb; color:#fff; padding:15px;}
		.wpos-sidebar .wpos-epb-wrap  h2{font-size:24px !important; color:#fff; margin:0 0 15px 0; padding:0px !important;}
		.wpos-sidebar .wpos-epb-wrap  h2 span{font-size:20px !important; color:#ffff00 !important;}
		.wpos-sidebar .wpos-epb-wrap ul li{font-size:16px; margin-bottom:8px;}
		.wpos-sidebar .wpos-epb-wrap ul li span{color:#ffff00 !important;}
		.wpos-sidebar .wpos-epb-wrap ul{list-style: decimal inside none;}
		.wpos-sidebar .wpos-epb-wrap b{font-weight:bold !important;}
		.wpos-sidebar .wpos-epb-wrap p{font-size:16px;}
		.wpos-sidebar .wpos-epb-wrap .button-yellow{font-weight: 600;color: #000; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ffff00;border-radius: .2em;}
		.wpos-sidebar .wpos-epb-wrap .button-orange{font-weight: 600;color: #fff; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ff2700;border-radius: .2em;}
		.larger-font{font-size:24px; line-height:35px; margin:0px;}
		.h-blue{color:#0055fb ;}
		.h-orange{color:#FF5D52 ;}
		.wpos-deal-heading{padding:0px 10px;}
		
		/* Table CSS */
		table, th, td {border: 1px solid #d1d1d1;}
		table.wpos-plugin-list{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; margin-bottom: 50px;}
		.wpos-plugin-list th {width: 16%; background: #2271b1; color: #fff; }
		.wpos-plugin-list td {vertical-align: top;}
		.wpos-plugin-type { text-align: left; color: #fff; font-weight: 700; padding: 0 10px; margin: 15px 0; }
		.wpos-slider-list { font-size: 14px; font-weight: 500; padding: 0 10px 0 25px; }
		.wpos-slider-list li {text-align: left; font-size: 13px; list-style: disc;}
	</style>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
				
				<div style="text-align: center; background: #DCDCDC; margin: 30px 0; padding: 10px 30px 30px 30px;">
					<!-- <h1 style="font-size: 28px; font-weight: 700; letter-spacing: -1px; text-align: center; margin-top: 30px; margin-bottom: 5px;">Only <span class="popupaoc-blue">November 2022</span> Deal <span class="popupaoc-blue">40% OFF</span></h1>
					<h2>Build <span class="bg-highlight">better websites</span>, <span class="bg-highlight">landing pages</span> & <span class="bg-highlight">conversion flow</span></h2>
					<h2>With <span class="popupaoc-blue">35+ plugins</span>, <span class="popupaoc-blue">2000+ templates</span> & $600 saving in <span class="popupaoc-blue">Essential Plugin Bundle</span></h2>
					<h3><span style="text-decoration:line-through; color: #FF1000;">$299</span> <span class="popupaoc-blue" style="font-size:30px;">$179</span> Unlimited Site License</h3> -->

					<p style="font-weight: bold !important; font-size:20px !important;"><span style="color: #50c621;">Essential Plugin Bundle</span> + Any Leading Builders (Avada / Elementor / Divi /<br> VC-WPBakery / Site Origin / Beaver) = <span style="background: #50c621;color: #fff;padding: 2px 10px;">WordPress Magic</span></p>
					<h4 style="color: #333; font-size: 14px; font-weight: 700;">Over 15K+ Customers Using <span style="color: #50c621 !important;">Essential Plugin Bundle</span></h4>
					<a href="<?php echo esc_url(POPUPAOC_PLUGIN_LINK_UPGRADE); ?>" target="_blank" class="popupaoc-sf-btn popupaoc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
				</div>
				
				<h2 style="font-size: 24px; text-align: center; color: #6c63ff;">Bundle Deal Details</h2>
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
						<li>Timeline and History slider</li>
						<li>Meta slider and carousel with lightbox</li>
						<li>Post Category Image With Grid and Slider</li>
					</ul>
				</td>
				<td>
					<ul class="wpos-slider-list">
						<li><span style="color:#2271b1; font-weight: bold;">Popup Anything - A Marketing Popup and Lead Generation Conversions</span></li>
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
						<li>Timeline and History slider</li>
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
						<li><span style="color:#2271b1; font-weight: bold;">Popup Anything - A Marketing Popup and Lead Generation Conversions</span></li>
						<li>Countdown Timer Ultimate</li>
					</ul>
				</td>
			</tr>
		</thead>
	</table>

	<div style="text-align: center; margin-bottom:30px">
		<h3 class="popupaoc-blue" style="margin:0; margin-bottom:10px; font-size:24px; font-weight:bold;">Use Essential Plugin Bundle</h3>
		<h1 style="font-size: 28px; font-weight: 700; letter-spacing: -1px; text-align: center; padding:0; margin-bottom: 5px;">With Your Favourite Page Builders</h1>
		<span style="font-size: 14px; color: #000;">and see how Essential Plugins can help you.</span>
	</div>

	<div style="text-align: center;">
		<img style="width: 100%; margin-bottom:30px;" src="<?php echo esc_url( POPUPAOC_URL ); ?>assets/images/image-upgrade.png" alt="image-upgrade" title="image-upgrade" />
		<div style="font-size: 14px; margin-bottom:10px;"><span class="popupaoc-blue">Popup Anything </span>Including in <span class="popupaoc-blue">Essential Plugin Bundle</span></div>
		<a href="<?php echo esc_url(POPUPAOC_PLUGIN_LINK_UPGRADE); ?>" target="_blank" class="popupaoc-sf-btn popupaoc-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
	</div>

				
				<!-- <h3 style="text-align:center"><?php //esc_html_e( 'Compare "Popup Anything - A Marketing Popup" Free VS Pro', 'popup-anything-on-click' ); ?></h3> -->

				<!-- <table class="wpos-plugin-pricing-table">
					<colgroup></colgroup>
					<colgroup></colgroup>
					<colgroup></colgroup>	
					<thead>
						<tr>
							<th></th>
							<th>
								<h2>Free</h2>
							</th>
							<th>
								<h2 class="wpos-epb">Premium</h2>
								<h3 class="wpos-deal-heading">Choose best pricing in <span class="h-blue"> Annual</span> and <span class="h-blue">Lifetime</span> deal</h3>
								<a href="<?php //echo esc_url(POPUPAOC_PLUGIN_LINK_UPGRADE); ?>" target="_blank">Buy Now</a>
							</th>	    		
						</tr>
					</thead>

					<tfoot>
						<tr>
							<th></th>
							<td></td>
							<td>
							<h3 class="wpos-deal-heading">Choose best pricing in <span class="h-blue"> Annual</span> and <span class="h-blue">Lifetime</span> deal</h3>
							<a href="<?php //echo esc_url(POPUPAOC_PLUGIN_LINK_UPGRADE); ?>" target="_blank">Buy Now</a></td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th>Create Unlimited Popups  <span>Create and manage as many popups as you want.</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Customize Popup <span>Customize the look and feel of the popup.</span></th>
							<td>Limited</td>
							<td>More Options</td>
						</tr>
						<tr>
							<th>Popup Goal <span class="subtext">Set popup goal as you want.</span></th>
							<td>Announcement Popup</td>
							<td>Announcement, Collect Lead, Target URL, Phone Calls Popup</td>
						</tr>
						<tr>
							<th>Popup Type <span class="subtext">Set popup type as you want.</span></th>
							<td>Modal Popup</td>
							<td>Modal, Bar, Push Notification, Slide In Popup</td>
						</tr>
						<tr>
							<th>Popup Designs <span class="subtext">You can choose popup design.</span></th>
							<td>1 Design</td>
							<td>10 Designs</td>
						</tr>
						<tr>
							<th>Cookie Expiry Time <span class="subtext">Set cookie expiry time for popup as you want.</span></th>
							<td>Days</td>
							<td>Days, Hours, Minutes</td>
						</tr>
						<tr>
							<th>WP Templating Features <span class="subtext">You can modify plugin html/designs in your current theme.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>			
						<tr>
							<th>Page Load <span class="subtext">Display popup on page load.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Simple Link <span class="subtext">Display popup on simple link click.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Image Click <span class="subtext">Display popup on image click.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Button Click <span class="subtext">Display popup on button click</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Inactivity <span class="subtext">Display popup on any inactivity.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Scroll Down <span class="subtext">Display popup on scroll down of page.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Scroll Up <span class="subtext">Display popup on scroll up of page.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Exit Intent <span class="subtext">Display popup when the cursor moves outside the upper page boundary.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>HTML Click <span class="subtext">Display popup on custom click.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Full Screen Popup <span class="subtext">Set full screen popup on the screen.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Effects <span class="subtext">Set animation effects for popup.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Positions <span class="subtext">Set popup 9 positions on the screen.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Width <span class="subtext">Set popup width.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Height <span class="subtext">Set popup height.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Image <span class="subtext">Set popup image.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Background Color <span class="subtext">Set background color for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Overlay Image <span class="subtext">Set overlay image for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Popup Overlay Color <span class="subtext">Set overlay color for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Heading Settings <span class="subtext">Set heading and sub heading settings for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Content Settings <span class="subtext">Set content, secondary content, etc settings for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Show for <span class="subtext">Choose popup visibility for users.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Display On <span class="subtext">Select device on which popup will be display.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Adblocker Popup <span class="subtext">Popup will be displaye when browser is blocking ads.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Impression or Clicks Data <span class="subtext">Store popup impressions or clicks data in database. </span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Schedule Popup <span class="subtext">Set schedule popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Form Fields <span class="subtext">Set collect lead form fields for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Social Profile <span class="subtext">Set social icons for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Notification <span class="subtext">Set email notification for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Integration <span class="subtext">Set mailchimp integration for collext lead popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Inline Popup <span class="subtext">Now you can embed popup inline to any post or page content.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Referrer Popup <span class="subtext">Set Referrer Popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>UTM Popup <span class="subtext">Set UTM a URL parameter based popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Cookie Popup <span class="subtext">Set cookie based popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Google Analytics <span class="subtext">Set google analytics settings for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>A/B Testing <span class="subtext">Set A/B testing for popup.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Automatic Update <span>Get automatic  plugin updates </span></th>
							<td>Lifetime</td>
							<td>Lifetime</td>
						</tr>
						<tr>
							<th>Support <span>Get support for plugin</span></th>
							<td>Limited</td>
							<td>1 Year</td>
						</tr>
					</tbody>
				</table> -->
			
			</div>
		</div>
	</div>
</div>