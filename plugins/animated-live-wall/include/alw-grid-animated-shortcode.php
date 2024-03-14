<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$allimages = array(
	'p'         => $alw_id,
	'post_type' => 'animated_live_wall',
	'orderby'   => 'ASC',
);
$loop = new WP_Query( $allimages );
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
			<ul class="">
			<?php
			if ( isset( $alw_get_settings['image-ids'] ) && count( $alw_get_settings['image-ids'] ) > 0 ) {
				$count = 0;
				$no    = 1;
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
					if ( $alw_grid_thumb_size == 'thumbnail' ) {
						$thumbnail_url = $thumbnail[0]; }
					if ( $alw_grid_thumb_size == 'medium' ) {
						$thumbnail_url = $medium[0]; }
					if ( $alw_grid_thumb_size == 'large' ) {
						$thumbnail_url = $large[0]; }
					if ( $alw_grid_thumb_size == 'full' ) {
						$thumbnail_url = $full[0]; }
					if ( $alw_grid_thumb_size == 'custum_500x500' ) {
						$thumbnail_url = $custum_500x500[0]; }
					if ( $alw_grid_thumb_size == 'custum_800x800' ) {
						$thumbnail_url = $custum_800x800[0]; }
					// lightbox
					if ( $alw_lightbox_thumb_size == 'thumbnail' ) {
						$thumbnail_url_lightbox = $thumbnail[0]; }
					if ( $alw_lightbox_thumb_size == 'medium' ) {
						$thumbnail_url_lightbox = $medium[0]; }
					if ( $alw_lightbox_thumb_size == 'large' ) {
						$thumbnail_url_lightbox = $large[0]; }
					if ( $alw_lightbox_thumb_size == 'full' ) {
						$thumbnail_url_lightbox = $full[0]; }
					if ( $alw_lightbox_thumb_size == 'custum_500x500' ) {
						$thumbnail_url_lightbox = $custum_500x500[0]; }
					if ( $alw_lightbox_thumb_size == 'custum_800x800' ) {
						$thumbnail_url_lightbox = $custum_800x800[0]; }
					?>
					<li class="brick">
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
							$link_url    = $full[0];
							$video_icon  = '';
							$vedio_class = '';
						} else {
							$lightboxop  = '';
							$video_icon  = 'fas fa-link';
							$link_url    = $image_link_url;
							$vedio_class = '';
						}
						?>
						<a href="<?php echo esc_url( $link_url ); ?>" class="<?php echo esc_attr( $lightboxop ); ?> <?php echo esc_attr( $vedio_class ); ?>" rel="next img-wrapper" <?php if ( $image_link_url == '' && $alw_lightbox != 'true' ) { ?> style="pointer-events:none" <?php } ?> target="<?php echo esc_attr( $alw_img_redirection ); ?>">
							<img src="<?php echo esc_url( $thumbnail_url ); ?>" width="100%" alt="<?php echo esc_html( $title ); ?>"/>
							<i class="alw-icon <?php echo esc_attr( $video_icon ); ?>"></i>
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
		w768 : {rows : <?php echo esc_js( $alw_grid_rows ); ?>, columns : <?php echo esc_js( $alw_grid_columns ); ?> },
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
		animType : '<?php echo esc_js( $alw_grid_animation ); ?>',
		// animation speed
		animSpeed : 1700, // 100 to 3000
		// animation easings
		animEasingOut : 'linear',
		animEasingIn: 'linear',
		// the item(s) will be replaced every 3 seconds
		// note: for performance issues, the time "can't" be < 300 ms
		interval : 700, //100 to 3000
		// if false the animations will not start
		// use false if onhover is true for example
		slideshow : true,
		// if true the items will switch when hovered
		onhover : false,
		// ids of elements that shouldn't change
		nochange : [] // 
	} );
	<?php if ( $alw_lightbox == 'true' ) { ?>
	// lightbox
	jQuery(".pw-lightbox").colorbox({
		rel:'pw-lightbox',
	});	
	jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
	jQuery(".vemio").colorbox({iframe:true, innerWidth:640, innerHeight:390});
	<?php } ?>
});
</script>
