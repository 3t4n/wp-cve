<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div id="help-background">
	<span><?php _e( 'Click anywhere to close overlay', 'woo-image-seo' ) ?></span>

	<span><?php _e( 'Click anywhere to close overlay', 'woo-image-seo' ) ?></span>
</div>

<div id="force-help" class="postbox postbox--help">
	<h2><?php _e( 'Force attributes', 'woo-image-seo' ) ?></h2>
	<strong><?php _e( 'If the setting is disabled', 'woo-image-seo' ) ?>:</strong><br>
	<?php _e( 'The plugin will only set the attribute to images that don\'t have one.', 'woo-image-seo' ) ?><br>
	<?php _e( 'This is useful if you wish to set your own attributes for individual images.', 'woo-image-seo' ) ?><br>
	<hr>
	<strong><?php _e( 'If the setting is enabled', 'woo-image-seo' ) ?>:</strong><br>
	<?php _e( 'The plugin will set the attribute to all images, even if they already have one.', 'woo-image-seo' ) ?><br>
	<?php _e( 'This is especially useful for the "title" attribute because WordPress generates title attributes automatically using the file name.', 'woo-image-seo' ) ?><br>
	<hr>
	<strong><?php _e( 'Example', 'woo-image-seo' ) ?>:</strong><br>
	<?php _e( 'You upload an image with the file name "pic3.jpg".', 'woo-image-seo' ) ?><br>
	<?php _e( 'WordPress will automatically set a title attribute of "pic3".', 'woo-image-seo' ) ?><br>
	<img src="<?php echo WOO_IMAGE_SEO['assets_url'] . 'force-help.png?version=' . WOO_IMAGE_SEO['version'] ?>" alt="">
</div>

<div id="attribute-builder-help" class="postbox postbox--help">
	<h2><?php _e( 'Attribute builder', 'woo-image-seo' ) ?></h2>
	<?php _e( 'The attribute builder lets you change how the image attributes are generated.', 'woo-image-seo' ) ?><br>
	<?php _e( 'You can use the three dropdown fields to include the product\'s name, first category, first tag, or a custom text in any order.', 'woo-image-seo' ) ?><br>
	<hr>
	<strong><?php _e( 'Example', 'woo-image-seo' ) ?>:</strong><br>
	<?php _e( 'Let\'s say we have a product called "Amazing Shirt", with main category "Movie-Inspired Clothing".', 'woo-image-seo' ) ?><br>
	<?php _e( 'By default, the plugin will build the image attribute using only the Product Name.', 'woo-image-seo' ) ?><br>
	<?php _e( 'If you want to include the product\'s category, you can use the following configuration', 'woo-image-seo' ) ?>:<br>
	<img src="<?php echo WOO_IMAGE_SEO['assets_url'] . 'attribute-builder-help-1.png?version=' . WOO_IMAGE_SEO['version'] ?>" alt=""><br>
	<?php _e( 'This will result in the attribute "Amazing Shirt Movie-Inspired Clothing".', 'woo-image-seo' ) ?><br>
	<hr>
	<?php _e( 'The Custom Text option allows you to enter your own texts.', 'woo-image-seo' ) ?><br>
	<?php _e( 'For example, the following configuration will result in "Amazing Shirt Movie-Inspired Clothing Free Shipping"', 'woo-image-seo' ) ?>:<br>
	<img src="<?php echo WOO_IMAGE_SEO['assets_url'] . 'attribute-builder-help-2.png?version=' . WOO_IMAGE_SEO['version'] ?>" alt="">
</div>

<div id="count-help" class="postbox postbox--help">
	<h2><?php _e( 'Add image number', 'woo-image-seo' ) ?></h2>
	<strong><?php _e( 'If the setting is enabled', 'woo-image-seo' ) ?>:</strong><br>
	<?php _e( 'The plugin will add the current image\'s number (index) at the end of the attribute.', 'woo-image-seo' ) ?><br>
	<?php _e( 'This is useful if you wish to avoid duplicate attributes on the page.', 'woo-image-seo' ) ?><br>
	<?php _e( 'Note that the first image won\'t be affected.', 'woo-image-seo' ) ?><br>
	<hr>
	<strong><?php _e( 'Example', 'woo-image-seo' ) ?>:</strong><br>
	<?php _e( 'Let\'s say we have a product with four images called "Amazing Shirt" and the Attribute Builder is configured to use the Product Name.', 'woo-image-seo' ) ?><br>
	<img src="<?php echo WOO_IMAGE_SEO['assets_url'] . 'attribute-builder-default.png?version=' . WOO_IMAGE_SEO['version'] ?>" alt=""><br>
	<?php _e( 'The first image will have the attribute "Amazing Shirt".', 'woo-image-seo' ) ?><br>
	<?php _e( 'The second image will have the attribute "Amazing Shirt 2".', 'woo-image-seo' ) ?><br>
	<?php _e( 'The third image will have the attribute "Amazing Shirt 3".', 'woo-image-seo' ) ?><br>
	<?php _e( 'The fourth image will have the attribute "Amazing Shirt 4".', 'woo-image-seo' ) ?><br>
</div>
