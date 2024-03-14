<?php
/**
 * The template for the post meta: author, categories, tags.
 */
?>
<div class="post-meta">
	<span class="byline">
		<span class="author vcard">
			<?php
                echo get_avatar(
                        get_the_author_meta( 'user_email' ),
                        /**
                         * Author avatar size.
                         *
                         * Allows modification of the avatar size.
                         *
                         * @param int $size Size of avatar.
                         */
                        apply_filters( 'vimeotheque-author-avatar-size', 49 )
                );
            ?>
			<span class="screen-reader-text"><?php _x( 'Author', 'Used before post author name.', 'codeflavors-vimeo-video-post-lite' );?></span>
			<a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>">
                <?php the_author(); ?>
            </a>
		</span>
	</span>

    <time class="entry-date published">
        <?php the_date(); ?>
    </time>

    <?php
        /**
         * Display categories and tags.
         */
        vimeotheque_the_entry_taxonomies();
    ?>

</div>
