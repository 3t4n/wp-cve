<?php get_header(); ?>

<div id="gallery" class="content">

<?php if ( post_password_required() ) : ?>
    <?php print get_the_password_form(); ?>
<?php else : ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div <?php post_class(); ?>>

    <h2><?php the_title(); ?></h2>

    <?php gpp_gallery_images(); ?>

    <p class="gpp-gallery-description"><?php echo get_post_meta( $post->ID, 'gpp_gallery_description', true ); ?></p>

    </div><!-- .post -->

    <?php endwhile; endif; ?>
<?php endif; ?>
</div><!-- #gallery .content -->

<!-- Begin Footer -->
<?php get_footer(); ?>