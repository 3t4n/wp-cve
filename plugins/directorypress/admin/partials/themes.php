<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$DirectoryPress_Admin_Panel = new DirectoryPress_Admin_Panel();

?>
<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-extensions directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div id="directorypress-extensions" class="directorypress-box">
				<div class="directorypress-box-head">
					<h1><?php esc_html_e('Premium Themes','foodirectory'); ?></h1>
					<p>
					<?php esc_html_e('Desinginvento aim to provided ultimate solutions for directory listing and classified niche businesses.','DIRECTORYPRESS'); ?></br>
					<?php esc_html_e('We have wide range of premium themes crafted based on DirectoryPress.','DIRECTORYPRESS'); ?></br>
					<?php esc_html_e('Explore our theme library and choose your desired one.','DIRECTORYPRESS'); ?>
					</p>
				</div>
				<div class="directorypress-box-content clearfix">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="directorypress-extensions-content">
							<?php DirectoryPress_Admin_Panel::dicode_products('category=themes&number=-1'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>