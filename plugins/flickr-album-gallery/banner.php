<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style( 'wpfrank-fag-feature-notice-css', FAG_PLUGIN_URL . 'css/fag-feature-notice.css', array(), '1.2', 'all' );
wp_enqueue_style( 'wpfrank-fag-bootstrap-css', FAG_PLUGIN_URL . 'css/bootstrap-admin.css' );
?>
<div class="row col-md-12 wpfrank_banner">
	<div class="col-md-6 col-sm-12 wpfrank_banner_img">
		<a href="http://wpfrank.com/account/signup/flickr-album-gallery-pro" target="_blank"><img class="img-fluid" src="<?php echo esc_url( FAG_PLUGIN_URL . 'img/products/Flickr-Album-Gallery-Pro.jpg' ); ?>"></a>
	</div>
	<div class="col-md-6 col-sm-12 wpfrank_banner_features">
		<h1 style="color: #FFFFFF;">Flickr Album Gallery Pro</h1>
		<ul>
			<li>8 Light Box</li>
			<li>Multiple Column Layouts</li>
			<li>8 Mouse Hover Effect</li>
			<li>Various Thumbnail Settings</li>
			<li>Lightbox Image Preview</li>
			<li>Album Gallery Widget</li>
		</ul>
		<div class="col-md-12 wpfrank_banner_actions">
			<a class="button-primary button-hero" href="http://wpfrank.com/demo/flickr-album-gallery-pro/" target="_blank">Live Demo</a>
			<a class="button-primary button-hero" href="http://wpfrank.com/account/signup/flickr-album-gallery-pro" target="_blank">Buy Now Just $29</a>
		</div>
		<div class="plugin_version">
			<span><b><?php echo FAG_PLUGIN_VER; ?></b>Version</span>
		</div>
	</div>
</div>
