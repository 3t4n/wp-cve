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
	<style type="text/css">
		.brick {
			background: #f5f5f5;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
			
			color: #333;
			border: none;
		}
		
		.brick {
			width: 221.2px;
		}
		.info {
			padding: 15px;
			color: #333;
		}
		.brick img {
			margin: 0px;
			padding: 0px;
			display: block;
		}
	</style>
	<div id="alw-loader" style="display:none; text-align:center; width:100%;">
		<!-- begin folding modal -->
		<div class="alw-pre-loader folding">
			<div class="sk-cube1 sk-cube"></div>
			<div class="sk-cube2 sk-cube"></div>
			<div class="sk-cube4 sk-cube"></div>
			<div class="sk-cube3 sk-cube"></div>
		</div>
	</div>
	<div id="freewall" class="free-wall">
		<?php
		if ( $flickr_data ) {
			$alw_total_images = count( $flickr_data );
			$count            = 0;
			$no               = 1;
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
				if ( $alw_thumb_size == 'thumbnail' ) {
					$thumbnail_url = $flickr_data_res['url_q']; }
				if ( $alw_thumb_size == 'medium' ) {
					$thumbnail_url = $flickr_data_res['url_s']; }
				if ( $alw_thumb_size == 'large' ) {
					$thumbnail_url = $flickr_data_res['url_m']; }
				if ( $alw_thumb_size == 'full' ) {
					$thumbnail_url = $flickr_data_res['url_l']; }

				$photo_id = $flickr_data_res['id'];
				?>
				<div class="brick">
					<a class='grid' target='<?php echo esc_attr( $alw_insta_link ); ?>' href='https://www.flickr.com/photos/<?php echo esc_attr( $alw_flickr_user_id ); ?>/<?php echo esc_attr( $photo_id ); ?>'>
						<figure class='snip1467'><img src='<?php echo esc_url( $thumbnail_url ); ?>' width='100%'>
							<i class='pw-flickr fab fa-flickr'></i>
							<!--<i class='pw-heart far fa-heart'>  </i>
							<i class='pw-comment far fa-comment'>  </i>
							<figcaption><p class='pw-caption'> ABC XYZ</p></figcaption>-->
						</figure>
					</a>
				</div>
				<?php
				$no++;
				$count++;
			}// end of attachment foreach
		} else {
			_e( 'Sorry! No image gallery found.', 'animated-live-wall' );
			echo ":[PFG id=$alw_id]";
		} // end of if else of images available check into image
		?>
			
	</div>
	
	<?php
endwhile;
wp_reset_query();

// column settins
$ma_column = 300;
if ( $column_setting == 'small' ) {
	$ma_column = 200; }
if ( $column_setting == 'large' ) {
	$ma_column = 300; }
?>
<script type="text/javascript">
jQuery( document ).ready(function() {
	var wall = new Freewall("#freewall");
	wall.reset({
		animate: false,
		cellW: <?php echo esc_js( $ma_column ); ?>, // function(container) {return 100;} 200 to 300
		cellH: 'auto', // function(container) {return 100;}
		delay: 50, // slowdown active block;
		engine: 'giot',
		fixSize: null, // resize + adjust = fill gap;
		//fixSize: 0, resize but keep ratio = no fill gap;
		//fixSize: 1, no resize + no adjust = no fill gap;
		gutterX: <?php echo esc_js( $alw_images_gap ); ?>, // width spacing between blocks;
		gutterY: <?php echo esc_js( $alw_images_gap ); ?>, // height spacing between blocks;
		keepOrder: false,
		selector: '.brick',
		draggable: false,
		cacheSize: true, // caches the original size of block;
		rightToLeft: false,
		bottomToTop: false,
		onGapFound: function() {},
		onComplete: function() {},
		onResize: function() {
			wall.fitWidth();
		},
		onBlockDrag: function() {},
		onBlockMove: function() {},
		onBlockDrop: function() {},
		onBlockReady: function() {},
		onBlockFinish: function() {},
		onBlockActive: function() {},
		onBlockResize: function() {}		
	});

	wall.container.find('.brick a').load(function() {
		wall.fitWidth();
	});
	
	wall.container.find('.brick a').resize();
});
</script>
