<?php
/**
 * Template part for displaying comic's blog post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$companion = new Toocheke_Companion_Comic_Features();
?>
<!--blog post-->
<?php if (!empty(get_post_meta($post->ID, 'comic_blog_post_editor', true))): ?>
<div class="comic-blog-post">
    <header class="entry-header">
                        <?php

echo '<span class="default-lang">';
the_title('<h1 class="entry-title">', '</h1>');
echo '</span>';
echo '<span class="alt-lang"><h1>';
echo esc_html(get_post_meta($post->ID, 'comic-title-2nd-language-display', true));
echo '</h1></span>';

?>
	<div class="entry-meta">
									<?php
$companion->toocheke_posted_on();
$companion->toocheke_posted_by();
?>
								</div><!-- .entry-meta -->

                    </header><!-- .entry-header -->
                    <article class="post type-post ">

<?php
echo '<span class="default-lang">';
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo get_post_meta($post->ID, 'comic_blog_post_editor', true);
echo '</span>';
echo '<span class="alt-lang">';
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo get_post_meta($post->ID, 'comic_2nd_language_blog_post_editor', true);
echo '</span>';
?>
</article>
</div>
    <?php endif?>
<!--./blog post-->