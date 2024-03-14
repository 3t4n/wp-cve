<div class="tsl_loop_wrapper <?php echo $layout ?>">

	<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>

		<?php include $this->get_view_path( "single-loop.php","template" ); ?>

	<?php endwhile; ?>

	<?php if ( $pagination == 'yes' ) : ?>
		<?php
		$big = 999999999;
		$args = array(
			'base'            => str_replace( $big, '%#%', get_pagenum_link($big) ),
			'format'          => '?tsl_paged=%#%',
			'total'           => $the_query->max_num_pages,
			'current'         => max( 1, $paged ),
			'show_all'        => false,
			'end_size'        => 1,
			'mid_size'        => $paged,
			'prev_next'       => true,
			'prev_text'       => __('&laquo;'),
			'next_text'       => __('&raquo;'),
			'type'            => 'plain',
			'add_args'        => true,
			'add_fragment'    => ''
		);
		if ( is_front_page() )
			$args['format'] = '?page=%#%';
		echo '<div class="tsl_pagination">' . paginate_links( $args ) . '</div>';
		?>

	<?php endif; ?>

	<?php wp_reset_postdata(); ?>

</div><!-- /tsl_loop_wrapper -->