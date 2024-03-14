<?php get_header(); ?>

<div id="gallery" class="content">

	<h1><?php gpp_gallery_slug(); ?></h1>

	<div class="gppss-grids">

		<?php
		global $wp_query,$post;
		$i = 0;
		$temp = $wp_query;
		$wp_query = NULL;
		$wp_query = new WP_Query();
		//$slug = gpp_gallery_slug();
		$wp_query->query( 'post_type=gallery' );
		while ( $wp_query->have_posts() ) : $wp_query->the_post(); $i++; ?>
		<div class="gppss-grid<?php if ( ( $i % 3 ) == 0 ) echo ' last'; ?>">
			<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ); ?>">
				<?php
					if ( get_the_post_thumbnail( $id, array( 275,200 ) ) != "" )
						the_post_thumbnail( '275x200' );
					else
						the_post_thumbnail();
				?>
			</a>
		</div><!-- .third -->

		<?php endwhile; ?>

		</div><!-- .grids -->

		<?php previous_posts_link(); ?>

		<?php next_posts_link(); ?>

		<?php $wp_query = NULL; $wp_query = $temp; ?>

</div><!-- .content -->

<?php get_footer(); ?>