<?php get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<?php
	while ( have_posts() ) : the_post();
		get_template_part( 'content', 'single' );
		$html = lbs_shelveBooks( get_the_id() );
		echo $html;
	endwhile;
	?>
	</main>
</div>

<?php get_footer(); ?>
