<?php
// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'chr-youtube-gallery', 'ysg_chr_youtube_gallery_shortcode' );
function ysg_chr_youtube_gallery_shortcode( $atts ) {
    ob_start();
 
    // define attributes and their defaults
    extract( shortcode_atts( array (
        'orderby' => 'date',
        'order' => 'DESC',
        'posts' => 6,
        'category' => 'all',
    ), $atts ) );

    add_thickbox();

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // define query parameters based on attributes
    
    if($category != 'all'){
	    $options = array(
	        'post_type' => 'youtube-gallery',
	        'order' => $order,
	        'orderby' => $orderby,
	        'posts_per_page' => $posts,
	        'paged' => $paged,
	        'tax_query' => array(
                array(
                    'taxonomy' => 'youtube-videos',
                    'field' => 'slug',
                    'terms' => array($category)
                )
            )
	    );
    }else{
    	$options = array(
	        'post_type' => 'youtube-gallery',
	        'order' => $order,
	        'orderby' => $orderby,
	        'posts_per_page' => $posts,
	        'paged' => $paged,
	    );
    };

    global $ysg_options; $ysg_settings = get_option( 'ysg_options', $ysg_options );

    $loop_youtube_gallery = new WP_Query( $options );
    
    // run the loop based on the query
    if($loop_youtube_gallery->have_posts()) {
    
	echo '<ul class="ul-YoutubeGallery">';
		while ( $loop_youtube_gallery->have_posts() ) : $loop_youtube_gallery->the_post();
		
		// Values YSG
		$desc_value = get_post_meta( get_the_ID(), 'valor_desc', true );
		$idvideo = get_post_meta( get_the_ID(), 'valor_url', true );
		
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

		$embed_code = ysg_youtubeEmbedFromUrl($idvideo);
		$size_thumb_w = $ysg_settings['ysg_thumb_wight'];
		$size_thumb_h = $ysg_settings['ysg_thumb_height']; 
	?>
		<li class="li-YoutubeGallery" id="post-<?php the_ID(); ?>">
			<h3 class="title-YoutubeGallery"><?php the_title();?></h3>
			<div id="video-id-<?php the_ID(); ?>" style="display:none;">
				<iframe width="<?php echo $ysg_settings['ysg_size_wight']; ?>" height="<?php echo $ysg_settings['ysg_size_height']; ?>" src="http://www.youtube.com/embed/<?php echo $embed_code;?>?rel=<?php echo $similar_video;?>&amp;vq=<?php echo $quality_video;?>&amp;controls=<?php echo $controles_video;?>&amp;showinfo=<?php echo $title_video;?>&amp;autoplay=<?php echo $ysg_settings['ysg_autoplay']; ?>" frameborder="0" allowfullscreen></iframe>
			</div>
			<a href="#TB_inline?width=<?php echo $ysg_settings['ysg_size_wight'] + '15'; ?>&height=<?php echo $ysg_settings['ysg_size_height'] + '20'; ?>&inlineId=video-id-<?php the_ID(); ?>" title="<?php the_title();?>" class="thickbox">
				<?php if ( has_post_thumbnail()) { the_post_thumbnail('chr-thumb-youtube', array('class' => 'img-YoutubeGallery chr-size-thumb')); }else{ echo '<img src="http://img.youtube.com/vi/'.$embed_code.'/mqdefault.jpg" class="img-YoutubeGallery chr-size-thumb" alt="'.get_the_title().'" title="'.get_the_title().'" />'; } ?>
			</a>
			<?php if( ! empty( $desc_value ) ) { echo '<blockquote class="blockquote-YoutubeGallery">' . $desc_value . '</blockquote>'; } ?>
		</li>
	<?php endwhile;
	echo '</ul>
	<style>
	.chr-size-thumb{
		width:'.$size_thumb_w.'px!important;
		height:'.$size_thumb_h.'px!important; 
	}
	</style>
	';
		
	if(function_exists('wp_pagenavi')) {
		wp_pagenavi( array( 'query' => $loop_youtube_gallery ));
	} else {
		if($loop_youtube_gallery->max_num_pages>1){
	?>
	<div class="chr-default-pagination">
	    <?php if ($paged > 1) { ?>
	    	<a href="<?php echo '?paged=' . ($paged -1); //prev link ?>">&laquo;</a>
	    <?php } for($i=1;$i<=$loop_youtube_gallery->max_num_pages;$i++){ ?>
	    	<a href="<?php echo '?paged=' . $i; ?>" <?php echo ($paged==$i)? 'class="selected"':'';?>><?php echo $i;?></a>
	    <?php } if($paged < $loop_youtube_gallery->max_num_pages){ ?>
	    	<a href="<?php echo '?paged=' . ($paged + 1); //next link ?>">&raquo;</a>
	    <?php } ?>
	</div>
	<?php }
	}
        $myvariable = ob_get_clean();
        return $myvariable;
    }else{
	    echo '<h6>' . __( 'N&atilde;o existe nenhum v&iacute;deo cadastrado...', 'youtube-simple-gallery' ) . '</h6>';
    }
}