<?php get_header() ?>

<?php if (have_posts()) { ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php if ($post->post_type == MAXGALLERIA_POST_TYPE) { ?>
			<div class="mg-container">
				<h1 class="mg-title"><?php echo esc_html(the_title()) ?></h1>
				<?php echo do_shortcode('[maxgallery id="' . esc_attr($post->ID) . '"]') ?>
        <?php         
        if ( function_exists( 'sharing_display' ) ) {          
          echo wp_kses_post(sharing_display()); 
        }       
        ?>        
			</div>
		<?php } ?>
	<?php endwhile; ?>
<?php } ?>

<?php get_footer() ?>