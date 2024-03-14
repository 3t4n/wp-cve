<?php get_header(); ?>

<div id="gallery" class="content">

	<h1><?php $i = 0; $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?></h1>

	<div class="gppss-grids">

		<?php  while (have_posts()) : the_post(); $i++; ?>
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
		</div><!-- .grid -->

		<?php endwhile; ?>

		</div><!-- .grids -->

		<?php previous_posts_link(); ?>

		<?php next_posts_link(); ?>


</div><!-- .content -->

<?php get_footer(); ?>