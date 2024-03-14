<?php

/**
 * Template Name: Homepage - HQ Wheelsberry
 */

get_header();
?>
    <style>
        .short-des{
            background-color: transparent !important;
        }
    </style>
    <?php echo do_shortcode('[hq_wheelsberry_reservation_form]'); ?>
    <div class="content">
        <div class="content-columns om-container">
            <div class="content-columns__content">
                <div class="content-columns__content-inner">

                </div>
            </div>
        </div>
    </div>
    <?php while (have_posts()) :
        the_post(); ?>
        <article>
            <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
<?php get_footer(); ?>