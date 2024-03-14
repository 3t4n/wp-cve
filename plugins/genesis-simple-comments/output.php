<?php
/*
 * Filters the Comment args on front end
 */

add_filter( 'genesis_title_comments', 'gsc_title_comments' );
/*
 * Switches Comment Title to title from plugin options.
 */
function gsc_title_comments( $args ){
    if( gsc_get_option('title_wrap') )
        return str_replace( '%s', esc_attr( gsc_get_option('genesis_title_comments') ), gsc_get_option('title_wrap') );
    else
        return gsc_get_option('genesis_title_comments');
}

add_filter( 'genesis_no_comments_text', 'gsc_no_comments_text' );
/*
 * Switches No Comment Text  to text from plugin options.
 */
function gsc_no_comments_text( $args ){
    return gsc_get_option('genesis_no_comments_text');
}

add_filter( 'genesis_comments_closed_text', 'gsc_comments_closed_text' );
/*
 * Switches Comments Closed Text to text from plugin options.
 */
function gsc_comments_closed_text( $args ){
    return gsc_get_option('genesis_comments_closed_text');
}

add_filter( 'genesis_title_pings', 'gsc_title_pings' );
/*
 * Switches Ping Title to title from plugin options.
 */
function gsc_title_pings( $args ){
    if( gsc_get_option('title_wrap') )
        return str_replace( '%s', esc_attr( gsc_get_option('genesis_title_pings') ), gsc_get_option('title_wrap') );
    else
        return gsc_get_option('genesis_title_pings');
}

add_filter( 'genesis_no_pings_text', 'gsc_no_pings_text' );
/*
 * Switches No Pings Text to text from plugin options.
 */
function gsc_no_pings_text( $args ){
    return gsc_get_option('genesis_no_pings_text');
}

add_filter( 'genesis_comment_list_args', 'gsc_comment_list_args' );
/*
 * Switches Avatar Size to size from plugin options.
 */
function gsc_comment_list_args( $args ){

    $args['avatar_size'] = intval( gsc_get_option('genesis_comment_list_args_avatar_size') );

    return $args;
}

add_filter( 'comment_author_says_text', 'gsc_comment_author_says_text' );
/*
 * Switches Comment Author Says Text to text from plugin options.
 */
function gsc_comment_author_says_text( $args ){
    return gsc_get_option('comment_author_says_text');
}

add_filter( 'genesis_comment_awaiting_moderation', 'gsc_comment_awaiting_moderation' );
/*
 * Switches Comment Awaiting Moderation Text to text from plugin options.
 */
function gsc_comment_awaiting_moderation( $args ){
    return gsc_get_option('genesis_comment_awaiting_moderation');
}

add_filter( 'genesis_comment_form_args', 'gsc_comment_form_args' );
/*
 * Changes Comment Form args from teh arguments set in the plugin options.
 */
function gsc_comment_form_args( $args ){
    $commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req && gsc_get_option('genesis_comment_form_args_fields_aria_display') ? ' aria-required="true"' : '' );

	$args = array(
		'fields' => array(
			'author' =>	'<p class="comment-form-author">' .
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1"' . $aria_req . ' />' .
						'<label for="author">' . esc_attr( gsc_get_option('genesis_comment_form_args_fields_author_label') ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'</p><!-- #form-section-author .form-section -->',

			'email' =>	'<p class="comment-form-email">' .
						'<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
						'<label for="email">' . esc_attr( gsc_get_option('genesis_comment_form_args_fields_email_label') ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'</p><!-- #form-section-email .form-section -->',

			'url' =>		'<p class="comment-form-url">' .
							'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
							'<label for="url">' . esc_attr( gsc_get_option('genesis_comment_form_args_fields_url_label') ) . '</label>' .
							'</p><!-- #form-section-url .form-section -->'
		),

		'comment_field' =>	'<p class="comment-form-comment">' .
							'<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>' .
							'</p><!-- #form-section-comment .form-section -->',

		'title_reply' => gsc_get_option('genesis_comment_form_args_title_reply'),
		'comment_notes_before' => gsc_get_option('genesis_comment_form_args_comment_notes_before'),
		'comment_notes_after' => gsc_get_option('genesis_comment_form_args_comment_notes_after'),
                'label_submit' => esc_attr( gsc_get_option('genesis_comment_form_args_label_submit') )
	);

        $args['fields']['author'] = gsc_get_option('genesis_comment_form_args_fields_author_display') ? $args['fields']['author'] : '';
        $args['fields']['email'] = gsc_get_option('genesis_comment_form_args_fields_email_display') ? $args['fields']['email'] : '';
        $args['fields']['url'] = gsc_get_option('genesis_comment_form_args_fields_url_display') ? $args['fields']['url'] : '';

        return $args;
}
