<?php
/**
 * Get help page.
 *
 * @package RT_FoodMenu
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
/**
 * Get Help
 */

$iframe  = 'https://www.youtube.com/embed/l4xLIPvj-ic';
$pro     = 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/';
$doc     = 'https://www.radiustheme.com/docs/food-menu/getting-started/installations/';
$contact = 'https://www.radiustheme.com/contact/';
$fb      = 'https://www.facebook.com/groups/234799147426640/';
$rt      = 'https://www.radiustheme.com/';
$review  = 'https://wordpress.org/support/plugin/tlp-food-menu/reviews/?filter=5#new-post';
?>
	<style>
		.rtfm-help-wrapper {
			width: 60%;
			margin: 0 auto;
		}
		.rtfm-help-section .embed-wrapper {
			position: relative;
			display: block;
			width: calc(100% - 40px);
			padding: 0;
			overflow: hidden;
		}
		.rtfm-help-section .embed-wrapper::before {
			display: block;
			content: "";
			padding-top: 56.25%;
		}
		.rtfm-help-section iframe {
			position: absolute;
			top: 0;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 100%;
			border: 0;
		}
		.rtfm-help-wrapper .rt-document-box .rt-box-title {
			margin-bottom: 30px;
		}
		.rtfm-help-wrapper .rt-document-box .rt-box-icon {
			margin-top: -6px;
		}
		.rtfm-help-wrapper .rtfm-help-section {
			margin-top: 30px;
		}
		.rtfm-feature-list ul {
			column-count: 2;
			column-gap: 30px;
			margin-bottom: 0;
		}
		.rtfm-feature-list ul li {
			padding: 0 0 12px;
			margin-bottom: 0;
			width: 100%;
			font-size: 14px;
		}
		.rtfm-feature-list ul li:last-child {
			padding-bottom: 0;
		}
		.rtfm-feature-list ul li i {
			color: #4C6FFF;
		}
		.rtfm-pro-feature-content {
			display: flex;
			flex-wrap: wrap;
		}
		.rtfm-pro-feature-content .rt-document-box + .rt-document-box {
			margin-left: 30px;
		}
		.rtfm-pro-feature-content .rt-document-box {
			flex: 0 0 calc(33.3333% - 60px);
			margin-top: 30px;
		}
		.rtfm-testimonials {
			display: flex;
			flex-wrap: wrap;
		}
		.rtfm-testimonials .rtfm-testimonial + .rtfm-testimonial  {
			margin-left: 30px;
		}
		.rtfm-testimonials .rtfm-testimonial  {
			flex: 0 0 calc(50% - 30px)
		}
		.rtfm-testimonial .client-info {
			display: flex;
			flex-wrap: wrap;
			font-size: 14px;
			align-items: center;
		}
		.rtfm-testimonial .client-info img {
			width: 60px;
			height: 60px;
			object-fit: cover;
			border-radius: 50%;
			margin-right: 10px;
			border: 1px solid #ddd;
			-webkit-box-shadow: 0 1px 3px rgb(0, 0, 0, 0.2);
			box-shadow: 0 1px 3px rgb(0, 0, 0, 0.2);
		}
		.rtfm-testimonial .client-info .rtfm-star {
			color: #4C6FFF;
		}
		.rtfm-testimonial .client-info .client-name {
			display: block;
			color: #000;
			font-size: 16px;
			font-weight: 600;
			margin: 8px 0 0;
		}
		.rtfm-call-to-action {
			background-size: cover;
			background-repeat: no-repeat;
			background-position: left center;
			height: 150px;
			color: #ffffff;
			margin: 30px 0;
		}
		.rtfm-call-to-action a {
			color: inherit;
			display: flex;
			flex-wrap: wrap;
			width: 100%;
			height: 100%;
			flex: 1;
			align-items: center;
			font-size: 28px;
			font-weight: 700;
			text-decoration: none;
			margin-left: 130px;
			position: relative;
			outline: none;
			-webkit-box-shadow: none;
			box-shadow: none;
		}
		.rtfm-call-to-action a::before {
			content: "";
			position: absolute;
			left: -30px;
			top: 50%;
			height: 30%;
			width: 5px;
			background: #fff;
			-webkit-transform: translateY(-50%);
			transform: translateY(-50%);
		}
		.rtfm-call-to-action:hover a {
			text-decoration: underline;
		}
		.rtfm-testimonial p {
			text-align: justify;
		}
		@media all and (max-width: 1400px) {
			.rtfm-help-wrapper {
				width: 80%;
			}
		}
		@media all and (max-width: 1025px) {
			.rtfm-pro-feature-content .rt-document-box {
				flex: 0 0 calc(50% - 55px)
			}
			.rtfm-pro-feature-content .rt-document-box + .rt-document-box + .rt-document-box {
				margin-left: 0;
			}
		}
		@media all and (max-width: 991px) {
			.rtfm-help-wrapper {
				width: calc(100% - 40px);
			}
			.rtfm-call-to-action a {
				justify-content: center;
				margin-left: auto;
				margin-right: auto;
				text-align: center;
			}
			.rtfm-call-to-action a::before {
				content: none;
			}
		}
		@media all and (max-width: 600px) {
			.rt-document-box .rt-box-content .rt-box-title {
				line-height: 28px;
			}
			.rtfm-help-section .embed-wrapper {
				width: 100%;
			}
			.rtfm-feature-list ul {
				column-count: 1;
			}
			.rtfm-feature-list ul li {
				width: 100%;
			}
			.rtfm-call-to-action a {
				padding-left: 25px;
				padding-right: 25px;
				font-size: 20px;
				line-height: 28px;
				width: 80%;
			}
			.rtfm-testimonials {
				display: block;
			}
			.rtfm-testimonials .rtfm-testimonial + .rtfm-testimonial {
				margin-left: 0;
				margin-top: 30px;
				padding-top: 30px;
				border-top: 1px solid #ddd;
			}
			.rtfm-pro-feature-content .rt-document-box {
				width: 100%;
				flex: auto;
			}
			.rtfm-pro-feature-content .rt-document-box + .rt-document-box {
				margin-left: 0;
			}

			.rtfm-help-wrapper .rt-document-box {
				display: block;
				position: relative;
			}

			.rtfm-help-wrapper .rt-document-box .rt-box-icon {
				position: absolute;
				left: 20px;
				top: 30px;
				margin-top: 0;
			}

			.rt-document-box .rt-box-content .rt-box-title {
				margin-left: 45px;
			}
		}
	</style>
	<div class="rtfm-help-wrapper" >
		<div class="rtfm-help-section rt-document-box">
			<div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
			<div class="rt-box-content">
				<h3 class="rt-box-title">Thank you for installing Food Menu</h3>
				<div class="embed-wrapper">
					<iframe src="<?php echo esc_url( $iframe ); ?>" title="Food Menu" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			</div>
		</div>
		<div class="rt-document-box">
			<div class="rt-box-icon"><i class="dashicons dashicons-megaphone"></i></div>
			<div class="rt-box-content rtfm-feature-list">
				<h3 class="rt-box-title">Pro Features</h3>
				<ul>
					<li><i class="dashicons dashicons-saved"></i> 11 Amazing Layouts with Grid, Masonry, Slider, Isotope.</li>
					<li><i class="dashicons dashicons-saved"></i> Even and Masonry Grid for all Grid.</li>
					<li><i class="dashicons dashicons-saved"></i> Menu Item Popup with details.</li>
					<li><i class="dashicons dashicons-saved"></i> Layout Preview in Shortcode Settings.</li>
					<li><i class="dashicons dashicons-saved"></i> Layout by category.</li>
					<li><i class="dashicons dashicons-saved"></i> Custom image size control.</li>
					<li><i class="dashicons dashicons-saved"></i> All Text and Color control.</li>
					<li><i class="dashicons dashicons-saved"></i> AJAX Pagination (Load more and Load on Scrolling).</li>
					<li><i class="dashicons dashicons-saved"></i> AJAX Number Pagination (only for Grid layouts).</li>
					<li><i class="dashicons dashicons-saved"></i> Search field on Isotope filter.</li>
					<li><i class="dashicons dashicons-saved"></i> Custom number of menu per page.</li>
					<li><i class="dashicons dashicons-saved"></i> Order by Name, Id, Date, Random, Menu order & Price.</li>
					<li><i class="dashicons dashicons-saved"></i> Responsive Display Control.</li>
					<li><i class="dashicons dashicons-saved"></i> More Features...</li>
				</ul>
			</div>
		</div>
		<div class="rtfm-call-to-action" style="background-image: url('<?php echo esc_url( TLPFoodMenu()->assets_url() ); ?>images/admin/banner.png')">
			<a href="<?php echo esc_url( $pro ); ?>" target="_blank" class="rt-update-pro-btn">
				Update to Pro & Get More Features
			</a>
		</div>
		<div class="rt-document-box">
			<div class="rt-box-icon"><i class="dashicons dashicons-thumbs-up"></i></div>
			<div class="rt-box-content">
				<h3 class="rt-box-title">Happy clients of the Food Menu</h3>
				<div class="rtfm-testimonials">
					<div class="rtfm-testimonial">
						<p>I love this plugin. After trying few other menu plugins I must say this is so far the best one. I bought the Pro version and I can enjoy a great variety of layouts and an infinte combination of styles and settings. Technical support is (via email) is fast and reliable, and replied me during weekend hours. I feel 5 stars aren't enough to express how much I am satisfied with this plugin, after struggling with other (for me) not so complete options. Thank you RadiusTheme!</p>
						<div class="client-info">
							<img src="<?php echo esc_url( TLPFoodMenu()->assets_url() ); ?>images/admin/client1.jpeg">
							<div>
								<div class="rtfm-star">
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
								</div>
								<span class="client-name">arenablue</span>
							</div>
						</div>
					</div>
					<div class="rtfm-testimonial">
						<p>This plugin works like a charm, fully responsive without any js clash. Plugin functionality was clashing at one or two places with travelo theme but the author of the plugin provide me a quick support and resolve all issues with in a few minutes and updates the newer version on wordpress.org. I am very thankful and highly obliged to the author for his help and precious time.</p>
						<div class="client-info">
							<img src="<?php echo esc_url( TLPFoodMenu()->assets_url() ); ?>images/admin/client2.png">
							<div>
								<div class="rtfm-star">
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
									<i class="dashicons dashicons-star-filled"></i>
								</div>
								<span class="client-name">pavitwalia</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="rtfm-pro-feature-content">
			<div class="rt-document-box">
				<div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
				<div class="rt-box-content">
					<h3 class="rt-box-title">Documentation</h3>
					<p>Get started by spending some time with the documentation we included step by step process with screenshots with video.</p>
					<a href="<?php echo esc_url( $doc ); ?>" target="_blank" class="rt-admin-btn">Documentation</a>
				</div>
			</div>
			<div class="rt-document-box">
				<div class="rt-box-icon"><i class="dashicons dashicons-sos"></i></div>
				<div class="rt-box-content">
					<h3 class="rt-box-title">Need Help?</h3>
					<p>Stuck with something? Please create a
						<a href="<?php echo esc_url( $contact ); ?>">ticket here</a> or post on <a href="<?php echo esc_url( $fb ); ?>">facebook group</a>. For emergency case join our <a href="<?php echo esc_url( $rt ); ?>">live chat</a>.</p>
					<a href="<?php echo esc_url( $contact ); ?>" target="_blank" class="rt-admin-btn">Get Support</a>
				</div>
			</div>
			<div class="rt-document-box">
				<div class="rt-box-icon"><i class="dashicons dashicons-smiley"></i></div>
				<div class="rt-box-content">
					<h3 class="rt-box-title">Happy Our Work?</h3>
					<p>If you are happy with <strong>Food Menu</strong> plugin, please add a rating. It would be glad to us.</p>
					<a href="<?php echo esc_url( $review ); ?>" class="rt-admin-btn" target="_blank">Post Review</a>
				</div>
			</div>
		</div>
	</div>
<?php
