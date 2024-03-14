<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="wrap" id="woo_image_seo">
	<div class="wrap__inner">
		<?php require_once WOO_IMAGE_SEO['root_dir'] . 'views/partials/postbox/settings.php' ?>

		<?php require_once WOO_IMAGE_SEO['root_dir'] . 'views/partials/help-modals.php' ?>
	</div><!-- /.wrap__inner -->

	<div class="wrap__inner">
        <?php require_once WOO_IMAGE_SEO['root_dir'] . 'views/partials/postbox/news.php' ?>

		<?php require_once WOO_IMAGE_SEO['root_dir'] . 'views/partials/postbox/feedback.php' ?>

		<?php require_once WOO_IMAGE_SEO['root_dir'] . 'views/partials/postbox/share.php' ?>
	</div><!-- /.wrap__inner -->
</div>
