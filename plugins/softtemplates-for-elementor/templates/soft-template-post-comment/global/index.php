<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$skin = $GLOBALS['post_comment_template_classic'];

if ( post_password_required() ) { ?>
	<p class="nocomments"><?php esc_html_e( 'This post is password protected. Enter the password to view comments.', 'elementor-pro' ); ?></p>
	<?php
	return;
}
?>

<?php

$comment_count = get_comment_count();

if ( $comment_count ) :
	?>
	<h3 id="comments">
        <?php /* translators: %s: search term */ ?>
        <?php echo esc_html( sprintf( _nx( '%s Comment', '%s Comments', get_comments_number(), 'comments title', 'anno' ), number_format_i18n( get_comments_number() ) ) ); ?></h3><!--/.comments-title-->
	</h3>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
    <?php endif; // check for comment navigation ?>

	<ol class="commentlist">
		<?php
		wp_list_comments( [
			'callback' => [ $skin, 'comment_callback' ],
		] );
		?>
	</ol>
    
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
    <?php endif; // check for comment navigation ?>
<?php else : ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'anno' ); ?></p>
    <?php endif; // have_comments()
endif;

comment_form();