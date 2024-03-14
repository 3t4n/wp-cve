<?php 
	$wp_query = null;
	if($select_category > 0){
		$selected_category = $select_category;
	
		$args = array(
			'posts_per_page' => $post_per_page,
			'cat'			=>$selected_category,

		);
		$wp_query = new WP_Query( $args );
		echo ' <ul class="categories-posts-widget">';
		while ($wp_query->have_posts() ) : $wp_query->the_post(); ?>
	    
		     <li class="widget-post-item-wraper">
		     	<?php if($thumbnail_status): ?>
				     <div class="post-thumbnail">
				     	<a href="<?php the_permalink()?>"> <?php if($thumbnail_status) the_post_thumbnail(); ?></a>
				     </div>
				     <?php endif; ?>
				  <?php if($thumbnail_status && has_post_thumbnail()): ?>
				  	<div class="post-content">
				  <?php else: ?>
					<div class="post-content without-thumbnail">
				  <?php endif; ?>
		    
		     	<a href="<?php the_permalink()?>"><h4><?php the_title();?></h4> <small class="post-date">
		 	<?php if($date_status){?>
		 		<?php echo get_the_date();?>
		 	<?php } ?>
		 	</small> </a>
		     </li>
	    
	<?php 
	endwhile;
	wp_reset_postdata();
}else{?>
	<h3>Please Select Catrgory!!!</h3>
<?php }
?>
 </ul>