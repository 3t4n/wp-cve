<?php
global $post;
$placeholder = '';

if ( FLBuilderModel::is_builder_active() ) {
	$placeholder = '<img src =' . XPRO_ADDONS_FOR_BB_URL . 'assets/images/placeholder-sm.webp alt="placeholder ">';
}
?>
<div class="xpro-featured-image">
	<?php echo ( get_post_thumbnail_id( $post->ID ) ) ? wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), $settings->featured_image_thumbnail ) : $placeholder; ?>
</div>
