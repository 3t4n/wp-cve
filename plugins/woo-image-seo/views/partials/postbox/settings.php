<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="postbox">
	<h1>Woo Image SEO - <?php _e( 'Global Settings', 'woo-image-seo' ) ?></h1>

	<?php require_once WOO_IMAGE_SEO['views_dir'] . 'partials/form-settings.php' ?>

	<div
		id="post-success"
		class="hidden bg-gray"
		data-saved="<?php _e( 'Settings Saved', 'woo-image-seo' ) ?>"
		data-saving="<?php _e( 'Saving...', 'woo-image-seo' ) ?>"
	></div>
</div>
