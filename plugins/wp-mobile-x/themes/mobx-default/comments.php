<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="entry-comments">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			printf(__('Comments(%s)', 'wpcom'), number_format_i18n( $comments_number ));
			?>
		</h3>

		<?php the_comments_navigation(); ?>

		<ul class="comments-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ul',
				'short_ping'  => true,
				'type'        => 'comment',
				'avatar_size' => '60',
				'callback'    => 'mobx_comment'
			) );
			?>
		</ul><!-- .comment-list -->

		<?php the_comments_navigation(); ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
	$fields =  array(
		'author' => '<div class="comment-form-author"><label for="author">'.( $req ? '<span class="required">*</span>' : '' ).__('Name: ', 'wpcom').'</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"'.( $req ? ' required' : '' ).'></div>',
		'email'  => '<div class="comment-form-email"><label for="email">'.( $req ? '<span class="required">*</span>' : '' ).__('Email: ', 'wpcom').'</label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"'.( $req ? ' required' : '' ).'></div>'
	);
	comment_form( array(
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h3>',
		'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field' =>  '<div class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true" required rows="4"></textarea></div>'
	) );
	?>
</div><!-- .comments-area -->