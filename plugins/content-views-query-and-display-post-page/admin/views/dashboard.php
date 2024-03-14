<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>

<style>
	#wpcontent {padding-left: 0}
	.cv-admin-settings h3 {
		font-size: 1.6em;
		margin-top: 0;
		margin-bottom: 15px;
	}
	.cv-admin-settings h4 {
		font-size: 17.5px;
		line-height: 1.3;
		margin-top: 0;
		margin-bottom: 5px;
	}
	.cv-admin-settings {
		margin: 30px auto;
		padding: 0 30px;
		max-width: 93%;
		font-size: 14px;
	}
	div.cv-admin-header {
		align-items: center;
		display: flex;		
		font-size: 23px;
		padding: 20px 25px;
	}
	.cv-admin-logo {
		max-width: 30px;
		margin-right: 20px;
	}
	.cv-admin-version {
		margin-left: auto;
		font-size: 16px;
	}
	.cv-admin-content {
		display: grid;
		gap: 20px;
		grid-template-columns: 3fr 2.1fr;
		margin-bottom: 30px;
	}

	.cv-admin-section {
		background: #fff;
		border-radius: 5px;
		box-shadow: 0 0 15px #ededed;
		padding: 25px;
		margin-bottom: 20px;
	}
	.cv-admin-settings ul {
		margin-left: 20px;
		list-style-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTMiIGhlaWdodD0iMTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTExLjMuM0w0LjUgNy4xIDEuNyA0LjMuMyA1LjdsNC4yIDQuMiA4LjItOC4yIiBmaWxsPSIjZmY1YTVmIi8+PC9zdmc+Cg==);
	}
	.cv-admin-settings li {
		margin-bottom: 15px;
		line-height: 1.5;
	}
	.cv-admin-section a {
		font-size: 1.2em;
		font-weight: 600;
		display: inline-block;
	}
	.features-list a {
		font-size: inherit;
		font-weight: 400;
		color: #fe1243;
	}
	.features-list .btn {
		background: #46d246;
		color: #fff;
	}
	.cv-admin-video {
		width: 100%;
		height: 330px;
	}
	.cv-hl-text {
		color: #fe1243;
	}
	p {
		margin-bottom: 15px;
		font-size: 14px;
	}
</style>

<?php
function cvsetting_tmp_utm1( $campaign = '' ) {
	return '?utm_source=setting-page&utm_medium=dashboard&utm_campaign=' . $campaign;
}
?>

<div class="cv-admin-settings">
	<div class="cv-admin-header cv-admin-section">
		<img src='<?php echo plugins_url( 'admin/assets/images/icon.png', PT_CV_FILE ) ?>' class="cv-admin-logo"/>
		<div>Welcome To Content Views</div>
		<div class="cv-admin-version"> Version <?php echo esc_html( PT_CV_Functions::plugin_info( PT_CV_FILE, 'Version' ) ); ?> </div>
	</div>


	<div class="cv-admin-content">		
		<div class="cv-admin-grid-left">
			<div class="cv-admin-section" id="block-intro-video">
				<h3>Blocks Introduction</h3>
				<p>We added 15+ advanced post blocks to the Block Editor to help you display posts, pages, custom post types stunningly with endless customization options.</p>
				<div class="cv-admin-video">
					<iframe width="100%" height="100%" src="https://www.youtube.com/embed/4Mj55xrPtV8?controls=1&modestbranding=1&rel=0&cc_load_policy=1&start=17" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
			</div>
			<div class="cv-admin-section">
				<h3>View Shortcode Introduction</h3>
				<p>We built a powerful shortcode feature to help you display content in the Classic editor and page builder easily. Simply create shortcodes, then insert shortcodes to where you want.</p>
				<div class="cv-admin-video">
					<iframe width="100%" height="100%" src="https://www.youtube.com/embed/QgQLjB0DQ8s?controls=1&modestbranding=1&rel=0&cc_load_policy=1" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
			</div>
		</div>
		<div class="cv-admin-grid-right">
			<div class="cv-admin-section features-list">
				<h3 class="cv-hl-text">More Benefits And Values For Your Site</h3>
				<ul>
					<li>
						<h4>Fully Support Custom Post Type, Custom Taxonomy, Custom Field</h4>
						<div>WooCommerce, EDD, The Events Calendar... (<a href="https://contentviewspro.com/demo/integration/woocommerce/<?php echo cvsetting_tmp_utm1('post-type'); ?>" target="_blank">see demo</a>)</div>
						<div>Advanced Custom Fields, Meta Box, Pods... (<a href="https://contentviewspro.com/demo/integration/advanced-custom-fields/<?php echo cvsetting_tmp_utm1('custom-field'); ?>" target="_blank">see demo</a>)</div>
					</li>
					<li>
						<h4>More Amazing Layouts To Attract Your Visitors</h4>
						<div>Pinterest, Masonry, Timeline, Glossary, Overlay... (<a href="https://contentviewspro.com/demo/blocks/pinterest/<?php echo cvsetting_tmp_utm1('layouts'); ?>" target="_blank">see demo</a>)</div>
					</li>
					<li>
						<h4>Grid Layout For Blog, Category, Search... Page</h4>
						<div>Use the beautiful grid for Blog, Category, Tag, Search ... page easily without coding (<a href="https://contentviewspro.com/demo/category/entertainment/<?php echo cvsetting_tmp_utm1('replace-layout'); ?>" target="_blank">see demo</a>)</div>
					</li>
					<li>
						<h4>Advanced Front-End Filter</h4>
						<div>Help your visitors find content faster and easier (<a href="https://contentviewspro.com/demo/faceted-search-live-filter/<?php echo cvsetting_tmp_utm1('frontend-filter'); ?>" target="_blank">see demo</a>)</div>
					</li>
					<li>
						<h4>66+ Stunning Ready-To-Use Patterns</h4>
						<div>Save time and get excellent results with patterns (<a href="https://contentviewspro.com/demo/block-patterns/<?php echo cvsetting_tmp_utm1('patterns'); ?>" target="_blank">read more</a>)</div>
					</li>
					<li>
						<h4>Make Money Online $</h4>
						<div>Display Google Ads, banners... in post grid easily (<a href="https://contentviewspro.com/demo/show-advertisements-in-layout/<?php echo cvsetting_tmp_utm1('showads'); ?>" target="_blank">read more</a>)</div>
					</li>
				</ul>
				<a href="https://www.contentviewspro.com/<?php echo cvsetting_tmp_utm1('upgrade'); ?>" target="_blank" class="btn">Get Pro Now</a>
			</div>
			<div class="cv-admin-section">
				<h3>Demo</h3>
				<p>Check out the demo pages to learn about our super powerful post blocks & shortcode.</p>
				<a href="https://contentviewspro.com/demo/<?php echo cvsetting_tmp_utm1('demo'); ?>" target="_blank">See Demos</a>
			</div>
			<div class="cv-admin-section">
				<h3>Documentation</h3>
				<p>Go through the easy documentation to get familiar with Content Views.</p>
				<a href="https://contentviewspro.com/documentation/<?php echo cvsetting_tmp_utm1('documentation'); ?>" target="_blank">Read Documentation</a>
			</div>
			<div class="cv-admin-section">
				<h3>Need Helps</h3>
				<p>Get in touch with our dedicated support team whenever you encounter an issue.</p>
				<a href="https://www.contentviewspro.com/contact/<?php echo cvsetting_tmp_utm1('contact'); ?>" target="_blank">Contact Us</a>
			</div>
		</div>
	</div>
</div>