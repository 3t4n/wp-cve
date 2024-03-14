<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style( 'awplife-npg-light-gallery-css', NPG_PLUGIN_URL . 'lightbox/light-gallery/css/lightgallery.css' );
// transition effects css
if ( $transition_effects != 'none' ) {
	wp_enqueue_style( 'awplife-npg-transitions-css', NPG_PLUGIN_URL . 'lightbox/light-gallery/css/lg-transitions.css' );
}

wp_enqueue_script( 'awplife-npg-light-gallery-js', NPG_PLUGIN_URL . 'lightbox/light-gallery/js/lightgallery.js' );
wp_enqueue_script( 'awplife-npg-all-plugins-js', NPG_PLUGIN_URL . 'lightbox/light-gallery/js/lightgallery-all.js' );

$allslides = array(
	'p'         => $light_image_gallery_id,
	'post_type' => '_light_image_gallery',
	'orderby'   => 'ASC',
);
$loop      = new WP_Query( $allslides );
while ( $loop->have_posts() ) :
	$loop->the_post();
	$post_id          = get_the_ID();
	$gallery_settings = unserialize( base64_decode( get_post_meta( $post_id, 'awl_lg_settings_' . $post_id, true ) ) );
	// start the image gallery contents
	?>
	<div id="animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?>" class="row all-images-<?php echo esc_attr( $light_image_gallery_id ); ?>">
		<?php
		if ( isset( $gallery_settings['slide-ids'] ) && count( $gallery_settings['slide-ids'] ) > 0 ) {
			$count = 0;
			// print_r($gallery_settings['slide-ids']);
			foreach ( $gallery_settings['slide-ids'] as $attachment_id ) {
				$thumb              = wp_get_attachment_image_src( $attachment_id, 'thumb', true );
				$thumbnail          = wp_get_attachment_image_src( $attachment_id, 'thumbnail', true );
				$medium             = wp_get_attachment_image_src( $attachment_id, 'medium', true );
				$large              = wp_get_attachment_image_src( $attachment_id, 'large', true );
				$full               = wp_get_attachment_image_src( $attachment_id, 'full', true );
				$postthumbnail      = wp_get_attachment_image_src( $attachment_id, 'post-thumbnail', true );
				$attachment_details = get_post( $attachment_id );
				$href               = get_permalink( $attachment_details->ID );
				$src                = $attachment_details->guid;
				$title              = $attachment_details->post_title;
				$description        = $attachment_details->post_content;
				$image_type         = $gallery_settings['slide-type'][ $count ];
				$image_link         = $gallery_settings['slide-link'][ $count ];
				$image_alt          = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

				if ( $image_alt == '' ) {
					$image_alt = $title;
				}

				if ( $thumbnails_spacing == 1 ) {
					$spacing_class = 'thumbnail';
				} else {
					$spacing_class = '';
				}

				// set thumbnail size
				if ( $gal_thumb_size == 'thumbnail' ) {
					$thumbnail_url = $thumbnail[0]; }
				if ( $gal_thumb_size == 'medium' ) {
					$thumbnail_url = $medium[0]; }
				if ( $gal_thumb_size == 'large' ) {
					$thumbnail_url = $large[0]; }
				if ( $gal_thumb_size == 'full' ) {
					$thumbnail_url = $full[0]; }
				?>
					<?php if ( $image_type == 'image' ) { ?>
					<a href="<?php echo esc_url( $full[0] ); ?>" class="single-image-<?php echo esc_attr( $light_image_gallery_id ); ?> <?php echo esc_attr( $col_large_desktops ); ?> <?php echo esc_attr( $col_desktops ); ?> <?php echo esc_attr( $col_tablets ); ?> <?php echo esc_attr( $col_phones ); ?>" data-sub-html="<h4 class=pg-title><?php esc_html_e( $title, 'new-photo-gallery' ); ?></h4>">
						<img class="<?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $image_hover_effect ); ?>" src="<?php echo esc_url( $thumbnail_url ); ?>"  alt="<?php esc_html_e( $image_alt, 'new-photo-gallery' ); ?>"/>
					</a>
					<?php } ?>
					
					<?php if ( $image_type == 'video' ) { ?>
						<?php
						// make YouTube thumbnail URL
						if ( $pos = strpos( $image_link, 'youtube' ) ) {
							parse_str( parse_url( $image_link, PHP_URL_QUERY ), $url_pars );
							$Yvid          = $url_pars['v'];
							$thumbnail_url = "https://i1.ytimg.com/vi/$Yvid/0.jpg";
						}

						// get Vimeo thumbnail by id
						if ( $pos = strpos( $image_link, 'vimeo' ) ) {
							// echo (int) substr(parse_url($image_link, PHP_URL_PATH), 1);
							$Vvid          = (int) substr( parse_url( $image_link, PHP_URL_PATH ), 1 );
							$hash          = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$Vvid.php" ) );
							$thumbnail_url = $hash[0]['thumbnail_medium'];
						}
						?>
					<a href="<?php echo esc_attr( $image_link ); ?>" data-poster="<?php echo esc_attr( $thumbnail_url ); ?>" class="single-image-<?php echo esc_attr( $light_image_gallery_id ); ?> <?php echo esc_attr( $col_large_desktops ); ?> <?php echo esc_attr( $col_desktops ); ?> <?php echo esc_attr( $col_tablets ); ?> <?php echo esc_attr( $col_phones ); ?>" href="<?php echo $full[0]; ?>" data-sub-html="<h4 class=pg-title><?php esc_html_e( $title, 'new-photo-gallery' ); ?></h4>">
						<img class="<?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $image_hover_effect ); ?>" src="<?php echo esc_url( $thumbnail_url ); ?>"  alt="<?php esc_html_e( $image_alt, 'new-photo-gallery' ); ?>"/>
					</a>
					<?php } ?>
					
				<?php
				$count++;
			}// end of attachment for each
		} else {
			esc_html_e( 'Sorry! No photo gallery found ', 'new-photo-gallery' );
			echo ": [NPG id=" . esc_attr( $post_id ) . "]";
		} // end of if else of slides available check into slider
		?>
	</div>
	<?php
endwhile;
wp_reset_query();
?>
<script>
	//thumbnail or fixed Size thumbnail
		jQuery( window ).load(function() {
			jQuery('#animated-thumbnails-<?php echo esc_js( $light_image_gallery_id ); ?>').lightGallery({
				thumbnail:true,
				animateThumb: true,
				//show and hide thumb setting
				showThumbByDefault: true,
				subHtmlSelectorRelative: true,
				<?php if ( $transition_effects != 'none' ) { ?>
					mode : '<?php echo esc_attr( $transition_effects ); ?>',
				<?php } ?>
			});
		});	
</script>
<style>
.lg-backdrop.in {
	opacity: 0.85;
}
.fixed-size.lg-outer .lg-inner {
	background-color: #FFF;
}
.fixed-size.lg-outer .lg-sub-html {
	position: absolute;
	text-align: left;
}
.fixed-size.lg-outer .lg-toolbar {
	background-color: transparent;
	height: 0;
}
.fixed-size.lg-outer .lg-toolbar .lg-icon {
	color: #FFF;
}
.fixed-size.lg-outer .lg-img-wrap {
	padding: 12px;
}
</style>
<script>
jQuery(document).ready(function () {
	// isotope effect function
	// Method 1 - Initialize Isotope, then trigger layout after each image loads.
	var $grid = jQuery('.all-images-<?php echo esc_js( $light_image_gallery_id ); ?>').isotope({
		// options...
		itemSelector: '.single-image-<?php echo esc_js( $light_image_gallery_id ); ?>',
	});
	// layout Isotope after each image loads
	$grid.imagesLoaded().progress( function() {
		$grid.isotope('layout');
	});
});
</script>
