<?php get_header(); ?>
<div class="wpc-container">
	<div class="wpc-row">
		<?php
			if(have_posts()){
				while(have_posts()){
					the_post(); ?>
					<div class="course-container wpc-light-box">
						<?php the_post_thumbnail(); ?>
						<?php $permalink = get_the_permalink(); ?>
						<h2 class="course-title"><a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
						<a class="wpc-button start-button" href="<?php echo esc_url( $permalink ); ?>"><?php _e('Read More', 'wp-courses'); ?></a>
					</div>
				<?php }
					echo '<br><div class="wpc-paginate-links">' . paginate_links() . '</div>';
			} else {
				esc_html_e("There are no teachers", "wp-courses") . '.';
			}
			
		?>
	</div>
</div>
<?php get_footer(); ?>