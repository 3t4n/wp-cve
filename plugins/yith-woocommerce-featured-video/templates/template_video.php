<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_script( 'ywcfav_video' );
$aspect_ratio = get_option( 'ywcfav_aspectratio', '4_3' );
$aspect_ratio = '_' . $aspect_ratio;

$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );

$thumbnail_size = apply_filters(
	'woocommerce_gallery_thumbnail_size',
	array(
		$gallery_thumbnail['width'],
		$gallery_thumbnail['height'],
	)
);


$thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size );

$thumbnail_url = isset( $thumbnail_url[0] ) ? $thumbnail_url[0] : '';
global $product;
if ( 'youtube' === $host ) {
	$video_class = 'youtube';
	$url         = 'https://www.youtube.com/embed/' . $video_id . '/?enablejsapi=1&origin=' . get_site_url();

} elseif ( 'vimeo' === $host ) {
	$video_class = 'vimeo';
	$url         = '//player.vimeo.com/video/' . $video_id;
}

$gallery_item_class = ywcfav_get_gallery_item_class()
?>
<div class="<?php echo esc_attr( $gallery_item_class ); ?> yith_featured_content" data-thumb="<?php echo esc_attr( $thumbnail_url ); ?>">
	<div class="ywcfav-video-content <?php echo esc_attr( $video_class . ' ' . $aspect_ratio ); ?>">
		<iframe id="video_<?php echo esc_attr( $product->get_id() ); ?>" src="<?php echo esc_attr( $url ); ?>" type="text/html" frameborder="0" allowfullscreen>
		</iframe>
	</div>
</div>
