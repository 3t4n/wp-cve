<?php
/**
 * The template for displaying the videos in the video archives.
 *
 * This template can be overridden by copying it to your-theme/vimeotheque/templates/archive/content.php
 *
 * @version 1.0
 */
?>
<div class="vimeotheque container-archive">
    <div class="vimeotheque video-archive">
            <?php if ( have_posts() ) : ?>
                <header class="page-header">
                    <?php
                    the_archive_title( '<h1 class="page-title">', '</h1>' );
                    the_archive_description( '<div class="taxonomy-description">', '</div>' );
                    ?>
                </header><!-- .page-header -->

                <div class="videos page-content">

                    <?php
                    // Start the loop.
                    while ( have_posts() ) :
                        the_post();

                        /*
                         * Include the post format-specific template for the content.
                         */
                        vimeotheque_get_template_part( 'archive/content', 'video-post' );

                        // End the loop.
                    endwhile;
                    ?>

                </div><!-- .videos -->

                    <?php
                    // Previous/next page navigation.
                    the_posts_pagination(
                        array(
                            'class'              => 'pagination',
                            'prev_text'          => __( 'Previous page', 'codeflavors-vimeo-video-post-lite' ),
                            'next_text'          => __( 'Next page', 'codeflavors-vimeo-video-post-lite' ),
                            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'codeflavors-vimeo-video-post-lite' ) . ' </span>',
                        )
                    );

                // If no content, include the "No posts found" template.
                else :
                    get_template_part( 'content', 'none' );

                endif;
                ?>
    </div>
</div>