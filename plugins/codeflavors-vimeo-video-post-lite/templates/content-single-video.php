<?php
/**
 * Template part for displaying a single video post.
 *
 */
?>

<?php
/**
 * The video embed content part.
 */
vimeotheque_get_template_part( 'single-video/media', 'video' );
?>

<article id="video-<?php the_ID();?>" <?php post_class( 'vimeotheque-video-post-content' );?>>
    <header class="entry-header">
        <?php
            if ( is_single() ) {
                the_title( '<h1 class="entry-title">', '</h1>' );
            } elseif ( is_front_page() && is_home() ) {
                the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
            } else {
                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            }
        ?>

        <div class="video-stats">
            <?php vimeotheque_the_video_duration( '<span class="video-duration">' . __( 'Duration: ', 'codeflavors-vimeo-video-post-lite' ), '</span><span class="sep"> | </span>' ); ?>
            <?php vimeotheque_the_video_views( '<span class="video-views">' . __( 'Views: ', 'codeflavors-vimeo-video-post-lite' ), '</span> <span class="sep"> | </span>' ); ?>
            <?php vimeotheque_the_video_likes( '<span class="video-likes">' . __( 'Likes: ', 'codeflavors-vimeo-video-post-lite' ) ); ?>
        </div>

    </header><!-- .entry-header -->

    <div class="entry-content">

        <?php
            the_content(
                sprintf(
                /* translators: %s: Post title. */
                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'vimeotheque' ),
                    get_the_title()
                )
            );

            wp_link_pages(
                array(
                    'before'      => '<div class="page-links">' . __( 'Pages:', 'vimeotheque' ),
                    'after'       => '</div>',
                    'link_before' => '<span class="page-number">',
                    'link_after'  => '</span>',
                )
            );
        ?>

    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
            vimeotheque_get_template_part( 'single-video/post', 'meta' );
        ?>

        <?php
            edit_post_link(
                sprintf(
                /* translators: %s: Name of current post */
                    __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
        ?>
    </footer><!-- .entry-footer -->

</article>