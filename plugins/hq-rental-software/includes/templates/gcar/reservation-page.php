<?php

/**
 * Template Name: Reservation Page
 *
 * @package WordPress
 */

get_header();
//Include custom header feature
get_template_part("/templates/template-header");
?>

    <div class="inner">

        <!-- Begin main content -->
        <div class="inner_wrapper">

            <?php if (have_posts()) {
                while (have_posts()) :
                    the_post(); ?>

                <div class="page_content_wrapper"><?php the_content(); ?></div>

                <?php endwhile;
            }; ?>
        </div>
    </div>
    </div>
<?php get_footer(); ?>