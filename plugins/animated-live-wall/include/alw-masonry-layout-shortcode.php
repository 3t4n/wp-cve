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
	
	
	@keyframes start {
		from {
			transform: scale(0);
		}
		to {
			transform: scale(1);
		}
	}


	@-webkit-keyframes start {
		from {
			-webkit-transform: scale(0);
		}
		to {
			-webkit-transform: scale(1);
		}
	}

	.free-wall .brick[data-state="init"] {
		display: none;
	}

	.free-wall .brick[data-state="start"]  {
		display: block;
		animation: start 0.5s;
		-webkit-animation: start 0.5s;
	}

	.free-wall .brick[data-state="move"]  {
		transition: top 0.5s, left 0.5s, width 0.5s, height 0.5s;
		-webkit-transition: top 0.5s, left 0.5s, width 0.5s, height 0.5s;
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
		if ( isset( $alw_get_settings['image-ids'] ) && count( $alw_get_settings['image-ids'] ) > 0 ) {
			$alw_total_images = count( $alw_get_settings['image-ids'] );
				$count        = 0;
			foreach ( $alw_get_settings['image-ids'] as $attachment_id ) {
				// $attachment_id;
				$image_link_url = $alw_get_settings['image-link'][ $count ];

				$thumb              = wp_get_attachment_image_src( $attachment_id, 'thumb', true );
				$thumbnail          = wp_get_attachment_image_src( $attachment_id, 'thumbnail', true );
				$medium             = wp_get_attachment_image_src( $attachment_id, 'medium', true );
				$large              = wp_get_attachment_image_src( $attachment_id, 'large', true );
				$full               = wp_get_attachment_image_src( $attachment_id, 'full', true );
				$custum_500x500     = wp_get_attachment_image_src( $attachment_id, 'custum_500x500', true );
				$custum_800x800     = wp_get_attachment_image_src( $attachment_id, 'custum_800x800', true );
				$postthumbnail      = wp_get_attachment_image_src( $attachment_id, 'post-thumbnail', true );
				$attachment_details = get_post( $attachment_id );
				$href               = get_permalink( $attachment_details->ID );
				$src                = $attachment_details->guid;
				$title              = $attachment_details->post_title;
				$description        = $attachment_details->post_content;

				// set thumbnail size
				if ( $alw_thumb_size == 'thumbnail' ) {
					$thumbnail_url = $thumbnail[0]; }
				if ( $alw_thumb_size == 'medium' ) {
					$thumbnail_url = $medium[0]; }
				if ( $alw_thumb_size == 'large' ) {
					$thumbnail_url = $large[0]; }
				if ( $alw_thumb_size == 'full' ) {
					$thumbnail_url = $full[0]; }

				?>
					 
					<div class="brick">
					<?php
						// get video id from youtube vemio
					if ( strpos( $image_link_url, 'youtube' ) !== false ) {
						$lightboxop  = 'pw-lightbox';
						$vedio_class = 'youtube';
						$video_icon  = 'fab fa-youtube';
						if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $image_link_url, $match ) ) {
							$vedio_id = $match[1];
							$link_url = 'https://www.youtube.com/embed/' . $vedio_id;
						}
					} elseif ( strpos( $image_link_url, 'vimeo' ) !== false ) {
						$lightboxop  = 'pw-lightbox';
						$vedio_class = 'vemio';
						$video_icon  = 'fab fa-vimeo';
						if ( preg_match( '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $image_link_url, $regs ) ) {
							$vedio_id = $regs[3];
							$link_url = 'https://player.vimeo.com/video/' . $vedio_id;
						}
					} elseif ( $image_link_url == '' ) {
						$lightboxop  = 'pw-lightbox';
						$link_url    = $full[0];;
						$video_icon  = '';
						$vedio_class = '';
					} else {
						$lightboxop  = '';
						$video_icon  = 'fas fa-link';
						$link_url    = $image_link_url;
						$vedio_class = '';
					}
					?>
						<a href="<?php echo esc_url( $link_url ); ?>" <?php if ( $image_link_url == '' && $alw_lightbox != 'true' ) { ?> style="pointer-events:none" <?php } ?> class="<?php echo esc_attr( $lightboxop ); ?> <?php echo esc_attr( $vedio_class ); ?>" target="<?php echo esc_attr( $alw_maso_img_redirection ); ?>">
							<div class="img-wrapper">
								<img src="<?php echo esc_url( $thumbnail_url ); ?>" width="100%" alt="<?php echo esc_html( $title ); ?>"/>
								<i class="alw-icon <?php echo esc_attr( $video_icon ); ?>"></i>
							</div>
						<?php if ( $title ) { ?>
							<div class="pw-caption">
								<p><?php echo esc_html( $title ); ?></p>
							</div>
							<?php } ?>
						</a>
					</div>
					<?php
					$count++;
			}// end of attachment foreach
		} else {
			_e( 'Sorry! No image gallery found.', 'animated-live-wall' );
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
jQuery('#freewall').hide();
jQuery('#awl-loader').show();
	jQuery( document ).ready(function() {	
		jQuery('#freewall').show();
		jQuery('#awl-loader').hide();
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
			keepOrder: true, // images show as uploaded
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
		
		// lightbox
		<?php if ( $alw_lightbox == 'true' ) { ?>
		jQuery(".pw-lightbox").colorbox({
			rel:'pw-lightbox',
		});	
		jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
		jQuery(".vemio").colorbox({iframe:true, innerWidth:640, innerHeight:390});
		<?php } ?>
	});
	
</script>
