<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}
?>

<div class="wrap about-wrap es">
	<h1>
		<?php _e('Welcome to Owl carousel responsive', 'owl-carousel-responsive'); ?>
	</h1>
	<div>
		<h3><?php echo __( 'Description', 'owl-carousel-responsive' ); ?></h3>
		<p><?php echo __('This wordpress plugin is using Owl Carousel jQuery script and that lets you create a beautiful responsive carousel slider and its fully customizable carousel. Please use the menus to create your gallery and upload your images. This plugin is fully responsive plugin.', 'owl-carousel-responsive' ); ?></p>
		<h3><?php echo __( 'Feature', 'owl-carousel-responsive' ); ?></h3>
		<ul>
			<li><?php echo __( 'Free plugin.', 'owl-carousel-responsive' ); ?></li>
			<li><?php echo __( 'It supports all major browsers.', 'owl-carousel-responsive' ); ?></li>
			<li><?php echo __( 'Admin option to control carousel speed.', 'owl-carousel-responsive' ); ?></li>
			<li><?php echo __( 'Option to set number of images based on screen size.', 'owl-carousel-responsive' ); ?></li>
			<li><?php echo __( 'Option to set auto Width/Height alignment.', 'owl-carousel-responsive' ); ?></li>
			<li></li>
		</ul>
		<h3><?php echo __( 'Menu', 'owl-carousel-responsive' ); ?></h3>
		<ul>
			<li><?php echo __('How to create gallery?', 'owl-carousel-responsive'); ?>
			<a target="_blank" href="<?php echo OWLC_ADMINURL; ?>?page=owlc-gallery"><?php _e('click here', 'owl-carousel-responsive'); ?></a></li>
			<li><?php echo __('How to upload images to gallery?', 'owl-carousel-responsive'); ?>
			<a target="_blank" href="<?php echo OWLC_ADMINURL; ?>?page=owlc-images"><?php _e('click here', 'owl-carousel-responsive'); ?></a></li>
			<li><?php echo __('For more information about this plugin', 'owl-carousel-responsive'); ?>
			<a target="_blank" href="<?php echo OWLC_FAVURL; ?>"><?php _e('click here', 'owl-carousel-responsive'); ?></a></li>
		</ul>
	</div>
</div>