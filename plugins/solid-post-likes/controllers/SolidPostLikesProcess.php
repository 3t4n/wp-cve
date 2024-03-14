<?php
namespace OACS\SolidPostLikes\Controllers;

use OACS\SolidPostLikes\Controllers\SolidPostLikesChecker as Checker;
use OACS\SolidPostLikes\Controllers\SolidPostLikesUsers as Users;
use OACS\SolidPostLikes\Controllers\SolidPostLikesIcon as Icon;
use OACS\SolidPostLikes\Controllers\SolidPostLikesCounter as Counter;
use OACS\SolidPostLikes\Controllers\SolidPostLikesGuests as Guest;
use OACS\SolidPostLikes\Controllers\SolidPostLikesText as Text;

if ( ! defined( 'WPINC' ) ) { die; }
/** Processes request when the like button is clicked. */

class SolidPostLikesProcess
{
	function oacs_spl_process_like()
	{
		$nonce = isset($_REQUEST['nonce']) ? sanitize_text_field($_REQUEST['nonce']) : 0;

		if (!wp_verify_nonce($nonce, 'oacs_spl_likes_nonce')) {
			exit(esc_html_e('<h3>Session expired. Please login again or reload the page.</h3>', 'oaspl'));
		}
        // Javascript tester: Did my hardcoded attribute make it over?
        // If it did, there was no ajax call to absorb it so Javascript is off.
		$disabled = (isset($_REQUEST['disabled']) && $_REQUEST['disabled'] == '1') ? 1 : 0;
        // Test if this is a comment
		$is_comment = (isset($_REQUEST['is_comment']) && $_REQUEST['is_comment'] == 1) ? 1 : 0;


        // Base variables
		$post_id = (isset($_REQUEST['post_id']) && is_numeric($_REQUEST['post_id'])) ? $_REQUEST['post_id'] : '';
		$result = array();
		$post_users = NULL;
		$like_count = 0;

		if ($post_id != '') {
			$count = ($is_comment == 1) ? get_comment_meta((int)$post_id, "_oacs_spl_comment_like_count", true) : get_post_meta((int)$post_id, "_oacs_spl_post_like_count", true);
			$count = (isset($count) && is_numeric($count)) ? $count : 0;

			$checker = new Checker;
			$post_like_users = new Users;

			if (!$checker->oacs_spl_already_liked($post_id, $is_comment)) {
                // Like the post
				if (is_user_logged_in()) {
                    // user is logged in
					$user_id = get_current_user_id();

					$post_users = $post_like_users->oacs_spl_post_user_likes($user_id, $post_id, $is_comment);
                    // This is a comment.
					if ($is_comment == 1) {
                        // Update User & Comment
						$user_like_count = get_user_option("_oacs_spl_comment_like_count", $user_id);

						$user_like_count = (isset($user_like_count) && is_numeric($user_like_count)) ? $user_like_count : 0;
						update_user_option($user_id, "_oacs_spl_comment_like_count", ++$user_like_count);

						if ($post_users) {
							update_comment_meta($post_id, "_oacs_spl_user_comment_liked", $post_users);
						}
					} else {
                        // This is a post.
                        // Update User & Post
						$user_like_count = get_user_option("_oacs_spl_user_like_count", $user_id);
						$user_like_count = (isset($user_like_count) && is_numeric($user_like_count)) ? $user_like_count : 0;
						update_user_option($user_id, "_oacs_spl_user_like_count", ++$user_like_count);
						if ($post_users) {
							update_post_meta($post_id, "_oacs_spl_user_liked", $post_users);
						}
					}
				} else {
                    // user is anonymous
					$guest = new Guest;
					$user_ip = $guest->oacs_spl_get_ip_and_encrypt();
					// dd($user_ip);
					$post_users = $guest->oacs_spl_post_ip_likes($user_ip, $post_id, $is_comment);
                    // Update Post
					if ($post_users) {
						if ($is_comment == 1) {
							update_comment_meta($post_id, "_oacs_spl_user_comment_IP", $post_users);
						} else {
							update_post_meta($post_id, "_oacs_spl_user_IP", $post_users);
						}
					}
				}
				$like_count           = ++$count;
				$response['status']   = "liked";
				$post_like_icon       = new Icon;
				$response['icon']     = $post_like_icon->oacs_spl_get_liked_icon();
				$post_like_text       = new Text;
				$response['text']     = $post_like_text->oacs_spl_get_unlike_text();
			} else {
                // Unlike the post
				if (is_user_logged_in()) {
                    // user is logged in
					$user_id = get_current_user_id();
					$post_like_users = new Users;
					$post_users = $post_like_users->oacs_spl_post_user_likes($user_id, $post_id, $is_comment);
                    // Update User
					if ($is_comment == 1) {
						$user_like_count = get_user_option("_oacs_spl_comment_like_count", $user_id);
						$user_like_count = (isset($user_like_count) && is_numeric($user_like_count)) ? $user_like_count : 0;
						if ($user_like_count > 0) {
							update_user_option($user_id, "_oacs_spl_comment_like_count", --$user_like_count);
						}
					} else {
						$user_like_count = get_user_option("_oacs_spl_user_like_count", $user_id);
						$user_like_count = (isset($user_like_count) && is_numeric($user_like_count)) ? $user_like_count : 0;
						if ($user_like_count > 0) {
							update_user_option($user_id, '_oacs_spl_user_like_count', --$user_like_count);
						}
					}
                    // Update Post
					if ($post_users) {
						$uid_key = array_search($user_id, $post_users);
						unset($post_users[$uid_key]);
						if ($is_comment == 1) {
							update_comment_meta($post_id, "_oacs_spl_user_comment_liked", $post_users);
						} else {
							update_post_meta($post_id, "_oacs_spl_user_liked", $post_users);
						}
					}
				} else {
                    // user is anonymous
					$guest = new Guest;

					$user_ip = $guest->oacs_spl_get_ip_and_encrypt();
					$post_users = $guest->oacs_spl_post_ip_likes($user_ip, $post_id, $is_comment);
                    // Update Post
					if ($post_users) {
						$uip_key = array_search($user_ip, $post_users);
						unset($post_users[$uip_key]);
						if ($is_comment == 1) {
							update_comment_meta($post_id, "_oacs_spl_user_comment_IP", $post_users);
						} else {
							update_post_meta($post_id, "_oacs_spl_user_IP", $post_users);
						}
					}
				}
				$like_count           = ($count > 0) ? --$count : 0; // Prevent negative number
				$response['status']   = "unliked";
				$post_like_icon       = new Icon;
				$response['icon']     = $post_like_icon->oacs_spl_get_unliked_icon();
				$post_like_text       = new Text;
				$response['text']     = $post_like_text->oacs_spl_get_like_text();
			}
			if ($is_comment == 1) {
				update_comment_meta($post_id, "_oacs_spl_comment_like_count", $like_count);
				update_comment_meta($post_id, "_oacs_spl_comment_like_modified", date('Y-m-d H:i:s'));
			} else {
				update_post_meta($post_id, "_oacs_spl_post_like_count", $like_count);
				update_post_meta($post_id, "_oacs_spl_comment_post_like_modified", date('Y-m-d H:i:s'));
			}
			$post_like_counter = new Counter;
			$response['count'] = $post_like_counter->oacs_spl_get_like_count($like_count). ' ';;
			$response['comment'] = $is_comment;

			if ($disabled === true) {
				if ($is_comment == 1) {
					$comment_id        = get_comment($post_id);
					$comment_post_id   = $comment_id->comment_post_ID;
					wp_redirect(get_permalink($comment_post_id));

					exit();
				} else {
					wp_redirect(get_permalink($post_id));
					exit();
				}
			} else {
				wp_send_json($response);
			}
		}
	}
}