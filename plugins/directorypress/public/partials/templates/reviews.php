<?php

	if(!function_exists('directorypress_comments')){
		function directorypress_comments( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment; 
			global $post;
			if( $comment->user_id === $post->post_author ) {
				$userClass = 'selfresponse';
			}else{
				$userClass = 'userresponse';
			}

			if(have_comments()){
				ob_start();
					comment_class(empty( $args['has_children'] ) ? '' : 'parent-comment');
				$comment_class = ob_get_clean();
				
				echo '<li '. esc_attr($comment_class) .' id="li-comment-'. esc_attr($comment->comment_ID) .'">';
					echo '<div class="directorypress-single-comment '. esc_attr($userClass) .' clearfix" id="comment-'. $comment->comment_ID .'">';
						echo '<div class="comment-content">';
							$rating = get_comment_meta($comment->comment_ID, 'dirrater', true );
							$dirrater_title = get_comment_meta($comment->comment_ID, 'dirrater_title', true );
							if ( !empty( $dirrater_title )  ) {
								echo '<p class="dirrater_title">' . $dirrater_title . '</p>';
							}
							if ( ! empty( $rating ) ) {
								echo '<div class="review_rate" data-dirrater="' . $rating . '"></div>';
							}
							
							comment_text();
							
							if ( $comment->comment_approved == '0' ){
								echo '<span class="unapproved">';
									esc_html_e( 'Your comment is awaiting moderation.', 'DIRECTORYPRESS' );
								echo '</span>';
							}
						echo '</div>';
						echo '<div class="comment-meta-main clearfix">';
							echo '<div class="author-img">';
								$avatar_id = get_user_meta( $comment->user_id, 'avatar_id', true );
								if(!empty($avatar_id) && is_numeric($avatar_id)) {
									$author_avatar_url = wp_get_attachment_image_src( $avatar_id, 'full' ); 
									$image_src_array = $author_avatar_url[0];
									$params = array( 'width' => 70, 'height' => 70, 'crop' => true );
									echo '<img src="' . bfi_thumb( $image_src_array, $params ) . '"  alt="'.get_the_author_meta('display_name', $comment->user_id).'" />';
								}else{
									$avatar_url = get_avatar_url($comment->user_id, ['size' => '70']);
									echo '<img src="'.esc_url($avatar_url).'" alt="author" />';
								}
							echo '</div>';
							echo '<div class="comment-meta">';
								printf( '<span class="comment-author">%s</span>', get_comment_author_link() );	
								echo '<time class="comment-time">'. get_comment_date() .', '. get_comment_time() .'</time>';
								echo '<span class="comment-reply">';
									$current_user = wp_get_current_user();
									if(is_user_logged_in()) {
										$usercomment = get_comments( array (
											'user_id' => $current_user->ID,
											'post_id' => $post->ID,
											//'comment__in' => $comment->comment_ID
										) );	
										//print_r($usercomment);
										if(!empty($usercomment) && $current_user->display_name != get_comment_author() && $current_user->display_name == get_the_author()) {
											comment_reply_link( array_merge( $args, array( 'depth' => 1, 'max_depth' => 2, 'reply_text' => esc_html__('reply', 'DIRECTORYPRESS') ) ) );
										}
									}
								echo '</span>';
							echo '</div>';	
						echo '</div>';    
					echo '</div>';	
				echo '</li>';
			}
		}
	}
	echo '<section id="comments">';
		if ( post_password_required() ):
				echo '<p class="nopassword">';
					esc_html_e( 'This post is password protected. Enter the password to view any comments.', 'DIRECTORYPRESS' );
				echo '</p>';
		
			echo '</section>';
			return;
		endif;

		if ( have_comments() ){ 

			$comments_label = esc_html__('User Reviews', 'DIRECTORYPRESS');
			echo '<div class="single-post-fancy-title comments-heading-label">';
				echo '<span>'. $comments_label .' <span class="comments_numbers">'. number_format_i18n( get_comments_number() ).'</span></span>';
			echo '</div>';
			echo '<ul class="directorypress-commentlist">';
					wp_list_comments( 'callback=directorypress_comments&type=comment' );
			echo '</ul>';

		}else{
			if (!comments_open()) {}
		}

		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ){
			echo '<nav class="comments-navigation">';
				echo '<div class="comments-previous">';
					previous_comments_link();
				echo '</div>';
				echo '<div class="comments-next">';
					next_comments_link();
				echo '</div>';
			echo '</nav>';
		}

		if ( comments_open() ){
			echo '<div class="inner-content">';
				$fields =  array(
					'author'=> '<div class="comment-form-name comment-form-row"><i class="fas fa-icon-user"></i><input type="text" name="author" class="text-input" id="author" tabindex="54" placeholder="'.esc_html__('FULL NAME', 'DIRECTORYPRESS').'"  /></div>',
					'email' => '<div class="comment-form-email comment-form-row"><i class="pas fa-icon-envelope-o"></i><input type="text" name="email" class="text-input" id="email" tabindex="56" placeholder="'.esc_html__('EMAIL ADDRESS', 'DIRECTORYPRESS').'" /></div>',
				);
				$comment_form_label = esc_html__('Post New Review', 'DIRECTORYPRESS');
				$comment_form_submit = esc_html__('Submit Review','DIRECTORYPRESS');

				//Comment Form Args
				$comments_args = array(
					'class_form' => 'clearfix',
					'fields' => $fields,
					'title_reply'=>'<div class="leave-comment-heading"><div>'.$comment_form_label.'</div></div>',
					'comment_field' => '<div class="comment-textarea"><textarea placeholder="'.esc_html__('Your Comment', 'DIRECTORYPRESS').'" class="textarea" name="comment" rows="3" id="comment" tabindex="58"></textarea></div>',
					'comment_notes_before' => '',
					'comment_notes_after' => '',
					'label_submit' => $comment_form_submit,
				);
				global $current_user, $post;

				if ( !is_user_logged_in() ) {
					$current_user = wp_get_current_user();
					comment_form($comments_args); 
				} elseif(is_user_logged_in()) {
					$usercomment = get_comments( array (
							'user_id' => $current_user->ID,
							'post_id' => $post->ID,
					) );
					
					if ( $usercomment && ($current_user->display_name != get_comment_author() && $current_user->display_name != get_the_author())) { 
						// hide comment form
					} else {
						 comment_form($comments_args); 
					}
				}
			echo '</div>';
		}
	echo '</section>';