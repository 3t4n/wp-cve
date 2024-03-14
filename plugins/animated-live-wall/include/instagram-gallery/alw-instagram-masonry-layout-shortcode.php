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
	$loop->the_post();
	?>
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
		if ( isset( $instagram_data['data'] ) ) {
			$alw_total_images = count( $instagram_data['data'] );
			$count            = 0;
			$no               = 1;
			// If user want to show load more
			foreach ( $instagram_data['data'] as $attachment_id ) {
				$insta_photos_link   	 = $attachment_id['permalink'];
				$insta_photos_caption 	= $attachment_id['caption'];
				$insta_media_type    	 = $attachment_id['media_type'];
				$thumbnail_url     		  = $attachment_id['media_url'];
				if(isset($attachment_id['thumbnail_url'])){
					$thumbnail_video_image     	= $attachment_id['thumbnail_url'];
				} else {
					$thumbnail_video_image = '';
				}
				
				
				//Lightbox class 
					if($alw_lightbox == 'true') {
						$lightboxop = 'pw-lightbox';
						$link_url = $thumbnail_url;
					} else {
						$lightboxop = '';
						$link_url = $insta_photos_link;
					}
					if($insta_media_type == 'VIDEO') {
						$lightboxop = '';
						$link_url = $insta_photos_link;
					}
				
				?>
				<div class="brick">
					<a class='grid <?php echo $lightboxop; ?>' target='<?php echo esc_attr( $alw_maso_img_redirection ); ?>' href='<?php echo esc_url( $link_url ); ?>'>
						<div class='snip1467'><img src='<?php if ( $insta_media_type == 'VIDEO' ) { echo esc_url( $thumbnail_video_image ); } else { echo esc_url( $thumbnail_url );} ?>' width='100%'>
							<?php if ( $insta_media_type == 'VIDEO' ) { ?>
							<span class='instagram-video fas fa-video'></span>
							<?php } ?>
							<?php
							if ( $alw_insta_icon == 'instagram' ) {
								echo "<i class='pw-instagram fab fa-instagram'></i>";
							} ?>
							<figcaption>
								<p class="pw-caption"><?php echo $insta_photos_caption; ?></p>
							</figcaption>
								
						</div>
					</a>
				</div>
				<?php
				$no++;
				$count++;

			}// end of attachment foreach
		} else {
			_e( 'Sorry! No image gallery found ', 'animated-live-wall' );
			echo ":[ALW id=$alw_id]";
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
	
	<?php if($alw_lightbox == 'true') { ?>
	// lightbox
	jQuery(".pw-lightbox").colorbox({
		rel:'pw-lightbox',
		title: true,
		scalePhotos: true,
		//maxHeight: '500px',
		scrolling: false,
		fixed: true,
		retinaImage: true
	});	
	jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
	jQuery(".vemio").colorbox({iframe:true, innerWidth:640, innerHeight:390});
	<?php } ?>
});
</script>
