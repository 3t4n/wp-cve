<?php
if($post_per_page > 0){
	$post_per_page = $post_per_page;
}else{
	$post_per_page =3;
}
$popular_posts_args = array(
   'posts_per_page' => $post_per_page,
   'meta_key' => 'walker_post_viewed',
   'orderby' => 'meta_value_num',
   'order'=> 'DESC'
);
 
$popular_posts_loop = new WP_Query( $popular_posts_args );
 
while( $popular_posts_loop->have_posts() ):
   $popular_posts_loop->the_post(); ?>
   <div class="popular-post-list">
	    <?php if($thumbnail_status && has_post_thumbnail()){ 
	    	$content_class='with-thumbnail';?>
		     <div class="post-thumbnail">
		     	<a href="<?php the_permalink()?>"> <?php if($thumbnail_status) the_post_thumbnail(); ?></a>
		     </div>
	    <?php } else{
	    	$content_class='without-thumbnail';
	    } ?>
	    <div class="<?php echo esc_attr($content_class);?>">
		   	<a href="<?php the_permalink();?>"><?php the_title();?> <small class="post-date">
		 	<?php if($date_status){?>
		 		<?php echo get_the_date();?>
		 	<?php } ?>
		 	</small></a>
		</div>
	</div>
<?php endwhile;
wp_reset_query();
?>