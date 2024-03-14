<?php
/*
 * Template Name: Youzify - Bbpress Template
 * Description: Youzify Plugin Pages Template.
 */
get_header();

do_action( 'youzify_before_youzify_template_content' );

?>

<div class="youzify <?php echo youzify_forums_page_class(); ?>">

	<main class="youzify-page-main-content">

		<div class="youzify-main-column">
			<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
				    the_content();
					endwhile;
				endif;
			?>
		</div>

		<?php if ( youzify_show_forum_sidebar() ) : ?>
		<div class="youzify-sidebar-column youzify-forum-sidebar youzify-sidebar">
			<div class="youzify-column-content">
				<?php do_action( 'youzify_forum_sidebar' ); ?>
			</div>
		</div>
		<?php endif; ?>

	</main>

</div>

<?php

do_action( 'youzify_after_youzify_template_content' );

get_footer();