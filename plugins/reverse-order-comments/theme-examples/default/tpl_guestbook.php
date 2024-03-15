<?php
/*
Template Name: Guestbook
*/
?>

<?php get_header(); ?>

	<div id="content" class="narrowcolumn">
				
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
		</div>
		
  <?php if(function_exists('ro_comments_template')) ro_comments_template("/comments-topinput.php"); else comments_template(); ?>
	
	<?php endwhile;endif;  ?>

	</div>
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>
