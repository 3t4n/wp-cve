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
					<h1><?php esc_html_e('Extensions','DIRECTORYPRESS'); ?></h1>
					<p>
					<?php esc_html_e('DirectoryPress offer wide range of extensions to enhance functionality and flexibility.','DIRECTORYPRESS'); ?></br>
					<?php esc_html_e('Choose from premium extensions below or request a custom one suit your project.','DIRECTORYPRESS'); ?>
					</p>
				</div>
				<div class="directorypress-box-content clearfix">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="directorypress-extensions-content">
							<?php DirectoryPress_Admin_Panel::dicode_products('category=directorypress-addons&number=-1'); ?>
							<div class="row">
								<div class="col-md-3 col-sm-6 col-xs-12 mt-30">
									<a href="https://designinvento.net/request-a-quote/" target="_blank"><img src="<?php echo esc_url(DIRECTORYPRESS_URL .'admin/assets/images/req-extension.png'); ?>" alt="Request Extensions" /></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>