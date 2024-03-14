<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$allimages = array(
	'p'         => $alw_id,
	'post_type' => 'animated_live_wall',
	'orderby'   => 'ASC',
);
$loop      = new WP_Query( $allimages );
while ( $loop->have_posts() ) :
	$loop->the_post(); ?>
	<section class="main">
		<div id="ri-grid-<?php echo esc_attr( $alw_id ); ?>" class="ri-grid ri-grid-size-2">
			<div id="alw-loader" style="display:none; text-align:center; width:100%;">
				<!-- begin folding modal -->
				<div class="alw-pre-loader folding">
					<div class="sk-cube1 sk-cube"></div>
					<div class="sk-cube2 sk-cube"></div>
					<div class="sk-cube4 sk-cube"></div>
					<div class="sk-cube3 sk-cube"></div>
				</div>
			</div>
			<ul>
			<?php
			if ( $flickr_data ) {
				$count = 0;
				$no    = 0;
				foreach ( $flickr_data as $flickr_data_res ) {
					$photostream_title_fetch = $flickr_data_res['title'];
					// thumbnail image size
					$thumb_img_size = 'url_q';
					if ( $thumb_img_size == 'url_sq' ) {
						if ( isset( $flickr_data_res['url_sq'] ) ) {
							$thumbnail_url = $flickr_data_res['url_sq'];  // Square          - 75x75
						}
					}
					if ( $thumb_img_size == 'url_q' ) {
						if ( isset( $flickr_data_res['url_q'] ) ) {
							$thumbnail_url = $flickr_data_res['url_q'];     // Large Square    - 150x150
						}
					}
					if ( $thumb_img_size == 'url_t' ) {
						if ( isset( $flickr_data_res['url_t'] ) ) {
							$thumbnail_url = $flickr_data_res['url_t'];     // Thumbnail       - 100x75
						}
					}
					if ( $thumb_img_size == 'url_s' ) {
						if ( isset( $flickr_data_res['url_s'] ) ) {
							$thumbnail_url = $flickr_data_res['url_s'];     // Small           - 240x180
						}
					}

					// light box image size
					$lightbox_img_size = 'url_l';
					if ( $lightbox_img_size == 'url_m' ) {
						if ( isset( $flickr_data_res['url_m'] ) ) {
							$lightboxl_url = $flickr_data_res['url_m'];      // Medium          - 500x375
						}
					}
					if ( $lightbox_img_size == 'url_l' ) {
						if ( isset( $flickr_data_res['url_l'] ) ) {
							$lightboxl_url = $flickr_data_res['url_l'];      // Large           - 1024x768
						}
					}

					// set thumbnail size
					if ( $alw_grid_thumb_size == 'thumbnail' ) {
						$thumbnail_url = $flickr_data_res['url_q']; }
					if ( $alw_grid_thumb_size == 'medium' ) {
						$thumbnail_url = $flickr_data_res['url_s']; }
					if ( $alw_grid_thumb_size == 'large' ) {
						$thumbnail_url = $flickr_data_res['url_m']; }
					if ( $alw_grid_thumb_size == 'full' ) {
						$thumbnail_url = $flickr_data_res['url_l']; }

					$photo_id = $flickr_data_res['id'];

					?>
					<li class="brick">
						<a class="snip1467" href="https://www.flickr.com/photos/<?php echo esc_attr( $alw_flickr_user_id ); ?>/<?php echo esc_attr( $photo_id ); ?>/">
							<img src="<?php echo esc_url( $thumbnail_url ); ?>"/>
							<!--<span class='pw-instagram fas fa-video'></span>-->
							<i class='pw-flickr fab fa-flickr'></i>
						</a>
					</li>					
					<?php
					$no++;
					$count++;
				}// end of attachment foreach
			} else {
				_e( 'Sorry! No image gallery found.', 'animated-live-wall' );
				echo ":[ALW id=$alw_id]";
			} // end of if else of images available check into image
			?>
			</ul>
		</div>
	</section>	
	<?php
endwhile;
wp_reset_query();
?>
<script type="text/javascript">	
jQuery( document ).ready(function() {
	jQuery( '#ri-grid-<?php echo esc_js( $alw_id ); ?>' ).gridrotator( {
		rows : <?php echo esc_js( $alw_grid_rows ); ?>,
		// number of columns 
		columns : <?php echo esc_js( $alw_grid_columns ); ?>,
		w1024 : { rows : <?php echo esc_js( $alw_grid_rows ); ?>, columns : <?php echo esc_js( $alw_grid_columns ); ?> },
		w768 : {rows : <?php echo esc_js( $alw_grid_rows ); ?>,columns : <?php echo esc_js( $alw_grid_columns ); ?> },
		w480 : {rows : 3,columns : 1 },
		w320 : {rows : 3,columns : 1 },
		w240 : {rows : 3,columns : 1 },
		// step: number of items that are replaced at the same time
		// random || [some number]
		// note: for performance issues, the number "can't" be > options.maxStep
		<?php if ( $alw_grid_stop_anim == 'yes' ) { ?>
		step : [0],
		<?php } else { ?>
		step : 'random',
		<?php } ?>
		// change it as you wish..
		maxStep : 3,
		// prevent user to click the items
		preventClick : false,
		// animation type
		// showHide || fadeInOut || 
		// slideLeft || slideRight || slideTop || slideBottom || 
		// rotateBottom || rotateLeft || rotateRight || rotateTop || 
		// scale ||
		// rotate3d ||
		// rotateLeftScale || rotateRightScale || rotateTopScale || rotateBottomScale || 
		// random
		animType : '<?php echo esc_js( $alw_grid_animation ); ?>',
		// animation speed
		animSpeed : 700, // 100 to 3000
		// animation easings
		animEasingOut : 'linear',
		animEasingIn: 'linear',
		// the item(s) will be replaced every 3 seconds
		// note: for performance issues, the time "can't" be < 300 ms
		interval : 1200, //100 to 3000
		// if false the animations will not start
		// use false if onhover is true for example
		slideshow : true,
		// if true the items will switch when hovered
		onhover : false,
		// ids of elements that shouldn't change
		nochange : [] // 
	} );

	
	// lightbox
	jQuery(function(){
		jQuery('.brick a').simpleLightbox();
	});
});
</script>
