<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$socials = require_once WOO_IMAGE_SEO['root_dir'] . 'data/socials.php';

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="postbox postbox--share">
	<h2><?php _e( 'Share the plugin', 'woo-image-seo' ) ?></h2>

	<span>
		<?php foreach ( $socials as $social ) : ?>
			<a
				href="<?php echo $social['href'] ?>"
				title="<?php _e( 'Share on', 'woo-image-seo' ) ?> <?php echo $social['title'] ?>"
				class="fa fa-<?php echo $social['class'] ?>"
				target="_blank"
			></a>
		<?php endforeach ?>
	</span>
</div><!-- /.postbox -->