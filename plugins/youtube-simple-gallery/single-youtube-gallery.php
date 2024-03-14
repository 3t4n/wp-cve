<?php get_header(); ?>
<div id="primary" class="content-area">
	
	<main id="main" class="site-main" role="main">

	<?php
		
	global $ysg_options;

		$ysg_settings = get_option( 'ysg_options', $ysg_options );

		while ( have_posts() ) : the_post();

			// Values YSG
			$desc_value = get_post_meta( get_the_ID(), 'valor_desc', true );
			$idvideo = get_post_meta( get_the_ID(), 'valor_url', true );
			$ysg_autoplay = $ysg_settings['ysg_autoplay'];
			$embed_code = ysg_youtubeEmbedFromUrl($idvideo);
			
			$quality_video = get_post_meta( get_the_ID(), 'custom_element_grid_quality_meta_box', true );
			if ($quality_video == null){
				$quality_video = 'default';
			}else{
				$quality_video = $quality_video;
			}
			
			$similar_video = get_post_meta( get_the_ID(), 'radio_similiar', true );
			if ($similar_video == null){
				$similar_video = '1';
			}else{
				$similar_video = $similar_video;
			}

			$controles_video = get_post_meta( get_the_ID(), 'radio_controles', true );
			if ($controles_video == null){
				$controles_video = '1';
			}else{
				$controles_video = $controles_video;
			}

			$title_video = get_post_meta( get_the_ID(), 'radio_title', true );
			if ($title_video == null){
				$title_video = '1';
			}else{
				$title_video = $title_video;
			}

		?>

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<a id="intro-video-id-<?php the_ID(); ?>" class="img-video-id-<?php the_ID(); ?>">
		<?php
			if ( has_post_thumbnail()) {
				the_post_thumbnail('chr-thumb-youtube', array('class' => 'img-YoutubeGallery chr-size-image'));
			} else {
				echo '<img src="http://img.youtube.com/vi/'.$embed_code.'/sddefault.jpg" class="img-YoutubeGallery chr-size-image" alt="'.get_the_title().'" title="'.get_the_title().'" />';
			}
		?>
			<br />
			<strong><?php _e('Clique na imagem, para iniciar o v&iacute;deo','youtube-simple-gallery');?></strong>
		</a>
		
		<div id="video-id-<?php the_ID(); ?>"></div>

		<style type="text/css">
			.img-video-id-<?php the_ID(); ?>{
				cursor: pointer;
			}
			.chr-size-image{
				width: <?php echo $ysg_settings['ysg_size_wight'] . 'px'; ?>;
				height: <?php echo $ysg_settings['ysg_size_height'] . 'px'; ?>;
			}
		</style>

		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('a#intro-video-id-<?php the_ID(); ?>').click(function(){
			  	ysg_autoPlayVideo('<?php echo $embed_code;?>',"<?php echo $ysg_settings['ysg_size_wight']; ?>", "<?php echo $ysg_settings['ysg_size_height']; ?>");
			  	$('.img-video-id-<?php the_ID(); ?>').remove();
			});
			/*--------------------------------
			  Swap video with autoplay video
			---------------------------------*/
			function ysg_autoPlayVideo(vcode, width, height){
			  "use strict";
			  $("#video-id-<?php the_ID(); ?>").html('<iframe width="'+width+'" height="'+height+'" src="https://www.youtube.com/embed/'+vcode+'?rel=<?php echo $similar_video;?>&amp;vq=<?php echo $quality_video;?>&amp;controls=<?php echo $controles_video;?>&amp;showinfo=<?php echo $title_video;?>&amp;autoplay=1" frameborder="0" allowfullscreen></iframe>');
			}
		});
		</script>

	<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>