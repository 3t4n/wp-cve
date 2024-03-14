<?php
/**
 * Template Name: Archive WPB
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

    <div class="wrap vi-wpb_wrapper">
        <?php
        $woo_pb = array(
            'post_type' => 'woo_product_builder',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'posts_per_archive_page' => 10,
            'ignore_sticky_posts' => 1,
            'paged' => get_query_var('page'),
            'orderby' => 'date'
        );
        $archive = new WP_Query($woo_pb);
        ?>
        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="taxonomy-description">', '</div>');
            ?>
        </header><!-- .page-header -->
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php
                if ($archive->have_posts()):
                    while ($archive->have_posts()):$archive->the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('vi-wpb_container'); ?>>
                            <?php
                            if (has_post_thumbnail()) { ?>
                                <div class="vi-wpb_post_thumbnail">
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                                </div>
                            <?php }
                            ?>
                            <div class="vi-wpb_content">
                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                                <div class="article-date"><?php echo get_the_date(); ?></div>
                                <p><?php the_content(); ?></p>
                            </div>
                        </article>
                    <?php endwhile;
                    if ($archive->max_num_pages > 1): ?>
                        <div id="vi-wpb_nav_below" class="navigation">
                            <div
                                class="nav-previous"><?php next_posts_link(__('Next <span class="meta-nav">&larr;</span>', 'woo-product-builder')); ?></div>
                            <div
                                class="nav-next"><?php previous_post_link(__('<span class="meta-nav">&larr;</span> Previous', 'woo-product-builder')); ?></div>
                        </div>
                    <?php endif;
                    wp_reset_postdata();
                else:
                    echo '';
                endif; ?>
            </main>
        </div>
        <aside class="vi-wpb_sidebar_archive">
            <?php get_sidebar(); ?>
        </aside>
    </div>

<?php get_footer(); ?>