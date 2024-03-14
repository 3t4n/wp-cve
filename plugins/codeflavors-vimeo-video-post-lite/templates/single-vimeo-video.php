<?php
/**
 * The template for displaying all single videos.
 *
 * This template can be overridden by copying it to your-theme/vimeotheque/single-video.php
 *
 * @version 1.0
 */
if( !defined( 'ABSPATH' ) ){
    exit; // exit if accessed directly
}

get_header(); ?>

    <?php
        /**
         * Before content hooks.
         *
         * Runs before the content is displayed into the page.
         */
        do_action( 'vimeotheque_before_main_content' );
    ?>

        <?php while( have_posts() ):?>
            <?php the_post(); ?>

            <div class="video-post-content single vimeotheque-video-post-main">

                <?php vimeotheque_get_template_part( 'content', 'single-video' ); ?>

                <?php if ( comments_open() || get_comments_number() ) : // If comments are open or there's at least one comment, show the comments ?>

                    <div class="vimeotheque-comments">
                        <?php comments_template(); ?>
                    </div>

                <?php endif; ?>

            </div>

        <?php endwhile;  // end loop. ?>

    <?php
    /**
     * After content hooks.
     *
     * Runs after the content is displayed into the page.
     */
    do_action( 'vimeotheque_after_main_content' );
    ?>

    <?php
        /**
        * Action for sidebar.
        *
        * Action that runs for the sidebar display.
        */
        do_action('vimeotheque_sidebar');
    ?>

<?php
get_footer();

/* Omit the closing PHP tag to avoid "headers already sent" issues */
