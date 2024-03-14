<?php
/**
 * Plugin Solutions & Features Page
 *
 * @package Meta Slider and Carousel with Lightbox
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$igsp_add_link = add_query_arg( array( 'post_type' => WP_IGSP_POST_TYPE ), admin_url( 'post-new.php' ) );

$tab = isset( $_GET['tab'] ) ? '#'.$_GET['tab'] : '#igsp_welcome_tabs'; ?>

<div id="wrap" class="wpos-solutions-features-page">
	<h2  class="wpos-main-heading-tittle">Welcome to <span class="igsp-blue">Meta Slider and Carousel with Lightbox</span></h2>
	<style>

		/**** Solution Features CSS ****/
		.wpos-solutions-features-page{width: 90%; margin: 0 auto; }
		.wpos-main-heading-tittle{font-size:24px; text-align: center;}
		.igsp-overview-tabs{font-size: 14px;font-weight: 700;font-style: oblique; letter-spacing: 1px;}
		.igsp-blue{color:#6c63ff; font-weight:bold;}
		.igsp-basic-pro-tabs{color: #ff2700 !important;}

		.igsp-vtab-nav a{padding:15px !important; text-transform: uppercase;}
		.igsp-vtab-nav.igsp-active-vtab a{ box-shadow: 5px 0 0 0 #46b450 inset !important; }
		.igsp-vtab-nav-wrap .igsp-vtab-nav a:hover {box-shadow: 5px 0 0 0 #6c63ff inset !important; }

		/**** commod deal offer ****/
		.igsp-deal-offer-wrap{position: relative;padding: 0.75rem 1.25rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: 0.25rem; color: #000;background-color: #ffd104;border-color: #ffd104;margin-top: 20px;}

		.igsp-deal-offer{display:flex;align-items: center; margin-top: 15px;}
		.igsp-inn-deal-offer{flex-basis:60%; padding: 20px; text-align:left;}
		.igsp-inn-deal-hedding{font-size: 22px;}
		.igsp-inn-deal-hedding span{color:#6c63ff;}

		.igsp-inn-deal-sub-hedding{font-size: 18px;}
		.igsp-inn-deal-sub-hedding span{font-size:20px; color: #6c63ff;}
		.igsp-inn-deal-code{margin-bottom: 10px;}
		.igsp-inn-deal-code span{display: inline-block; padding:15px 60px; border: 1px #000 dashed; color: #FF1000; font-weight: 700; font-size: 18px; background-color: #FAFAD2;}
		.igsp-inn-deal-offer-btn{flex-basis:40%; padding:20px;text-align: center;}
		.igsp-inn-deal-offer-btn a{border-radius: unset; padding: 20px;}

		/****** Basic Vs Pro ******/
		.igsp-basic-heading{text-align: center;}
		.wpos-epb{color:#ff2700 !important;}

		.wpos-plugin-pricing-table thead th h2{font-weight: 400; font-size: 2.4em; line-height:normal; margin:0px; color: #2ECC71;}
		.wpos-plugin-pricing-table thead th h2 + p{font-size: 1.25em; line-height: 1.4; color: #999; margin:5px 0 5px 0;}
		table.wpos-plugin-pricing-table{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}

		.wpos-plugin-pricing-table th, .wpos-plugin-pricing-table td{font-size:14px; line-height:normal; color:#444; vertical-align:middle; padding:12px;}

		.wpos-plugin-pricing-table colgroup:nth-child(1) { width: 31%; border: 0 none; }
		.wpos-plugin-pricing-table colgroup:nth-child(2) { width: 22%; border: 1px solid #ccc; }
		.wpos-plugin-pricing-table colgroup:nth-child(3) { width: 25%; border: 10px solid #2ECC71; }

		/* Tablehead */
		.wpos-plugin-pricing-table thead th {background-color: #fff; background:linear-gradient(to bottom, #ffffff 0%, #ffffff 100%); text-align: center; position: relative; border-bottom: 1px solid #ccc; padding: 1em 0 1em; font-weight:400; color:#999;}
		.wpos-plugin-pricing-table thead th:nth-child(1) {background: transparent;}
		.wpos-plugin-pricing-table thead th:nth-child(3) p{color:#000;}	

		/* Tablebody */
		.wpos-plugin-pricing-table tbody th{background: #fff; border: 1px solid #ccc; font-weight: 600;}
		.wpos-plugin-pricing-table tbody th span{font-weight: normal; font-size: 87.5%; color: #999; display: block;}
		.wpos-plugin-pricing-table tbody td{background: #fff; text-align: center;}
		.wpos-plugin-pricing-table tbody td .dashicons{height: auto; width: auto; font-size:30px;}
		.wpos-plugin-pricing-table tbody td .dashicons-no-alt{color: #ff2700;}
		.wpos-plugin-pricing-table tbody td .dashicons-yes{color: #2ECC71;}
		.wpos-plugin-pricing-table tbody tr:nth-child(even) th,
		.wpos-plugin-pricing-table tbody tr:nth-child(even) td { background: #f5f5f5; border: 1px solid #ccc; border-width: 1px 0 1px 1px; }
		
		/*** Unlock CSS***/
		/* Frist CSS */
		.igsp-unlock-magic{background: #50c621;color: #fff;padding: 2px 10px;}
		.igsp-unlock-heading{color: #333; font-size: 18px; font-weight: 700;}
		.igsp-unlock-sub-heading{font-weight: bold !important; font-size:20px !important;}
		.igsp-unlock-magic{background: #50c621;color: #fff;padding: 2px 10px;}
		.igsp-plugin-list{font-size: 24px; text-align: center; color: #6c63ff;}

		/* Table CSS */
		table, th, td {border: 1px solid #d1d1d1;}
		table.wpos-plugin-list{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; margin-bottom: 50px;}
		.wpos-plugin-list th {width: 16%; background: #2271b1; color: #fff; }
		.wpos-plugin-list td {vertical-align: top;}
		.wpos-plugin-type { text-align: left; color: #fff; font-weight: 700; padding: 0 10px; margin: 15px 0; }
		.wpos-slider-list { font-size: 14px; font-weight: 500; padding: 0 10px 0 25px; }
		.wpos-slider-list li {text-align: left; font-size: 13px; list-style: disc;}

		/* Favourite CSS */
		.igsp-favourite-section{text-align: center; margin-bottom:30px}
		.igsp-favourite-heading{margin:0; margin-bottom:10px; font-size:24px; font-weight:bold;}
		.igsp-favourite-sub-heading{font-size: 28px; font-weight: 700; letter-spacing: -1px; text-align: center; padding:0; margin-bottom: 5px;}
		.igsp-favourite-section span{font-size: 16px;color: #000;display: inline-block;width: 100%;}
		.igsp-favourite-section span i{color: #50c621; font-weight: 600; vertical-align: middle;}
		.igsp-favourite-section span img{display: inline-block; vertical-align: middle; max-width: 100%; height: auto;}
		.igsp-upgrade-image-wrap img{width: 100%; margin-bottom:30px;}

	</style>
	<div class="igsp-vtab-wrap igsp-cnt-wrap igsp-clearfix">
		<ul class="igsp-vtab-nav-wrap">
			<li class="igsp-vtab-nav igsp-active-vtab">
				<a href="#igsp_welcome_tabs" class="igsp-overview-tabs"><?php esc_html_e('Welcome', 'wp-slick-slider-and-image-carousel'); ?></a>
			</li>

			<li class="igsp-vtab-nav">
				<a href="#igsp_themes_tabs" class="igsp-overview-tabs"><?php esc_html_e('Pro Features', 'wp-slick-slider-and-image-carousel'); ?></a>
			</li>

			<li class="igsp-vtab-nav">
				<a href="#igsp_basic_tabs" class="igsp-overview-tabs igsp-basic-pro-tabs"><?php esc_html_e('Basic Vs Pro', 'wp-slick-slider-and-image-carousel'); ?></a>
			</li>

			<li class="igsp-vtab-nav">
				<a href="#igsp_unlock_tabs" class="igsp-overview-tabs"><?php esc_html_e('Meta Slider in Essential Bundle', 'wp-slick-slider-and-image-carousel'); ?></a>
			</li>

			<li class="igsp-vtab-nav">
				<a href="#igsp_review_tabs" class="igsp-overview-tabs"><?php esc_html_e('Reviews', 'wp-slick-slider-and-image-carousel'); ?></a>
			</li>
		</ul>

		<div class="igsp-vtab-cnt-wrp">
			<?php
			// Welcome Template
			include_once( WP_IGSP_DIR  . '/includes/admin/settings/solution-features/welcome-tab.php' );

			// Themes Template
			include_once( WP_IGSP_DIR  . '/includes/admin/settings/solution-features/pro-features-tab.php' );

			// Basic Pro Template
			include_once( WP_IGSP_DIR  . '/includes/admin/settings/solution-features/basicpro-tab.php' );

			// Unlock Template
			include_once( WP_IGSP_DIR  . '/includes/admin/settings/solution-features/popup-ess-bundle-tab.php' );

			// Reviews Template
			include_once( WP_IGSP_DIR  . '/includes/admin/settings/solution-features/reviews-tab.php' ); ?>
		</div>
		<input type="hidden" value="<?php echo esc_attr($tab); ?>" class="igsp-selected-tab" name="igsp_tab" />
	</div>
</div>