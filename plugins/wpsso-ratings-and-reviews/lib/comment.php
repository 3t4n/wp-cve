<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarComment' ) ) {

	class WpssoRarComment {

		private $p;	// Wpsso class object.
		private $a;	// WpssoRar class object.

		/*
		 * Instantiated by WpssoRar->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			add_filter( 'comment_form_defaults', array( __CLASS__, 'update_comment_form_defaults' ), PHP_INT_MAX );
			add_filter( 'comment_form_field_comment', array( __CLASS__, 'update_form_comment_field' ), PHP_INT_MAX );
			add_filter( 'comment_form_submit_button', array( __CLASS__, 'update_form_submit_button' ), PHP_INT_MAX );
			add_filter( 'comment_text', array( __CLASS__, 'add_rating_to_comment_text' ) );

			add_action( 'comment_post', array( __CLASS__, 'save_rating_comment_post' ), 10, 2 );
			add_action( 'comment_post', array( __CLASS__, 'update_cache_comment_post' ), 100, 2 );
			add_action( 'transition_comment_status', array( __CLASS__, 'update_cache_transition_comment_status' ), 100, 3 );
		}

		/*
		 * Check if ratings are allowed for this post ID.
		 */
		public static function is_rating_enabled( $post_id ) {

			static $local_cache = array();

			if ( empty( $post_id ) ) {	// Just in case.

				return false;

			} elseif ( isset( $local_cache[ $post_id ] ) ) {

				return $local_cache[ $post_id ];
			}

			$wpsso     = Wpsso::get_instance();
			$post_type = get_post_type( $post_id );
			$default   = empty( $wpsso->options[ 'rar_add_to_' . $post_type ] ) ? 0 : 1;
			$disabled  = empty( $wpsso->options[ 'rar_add_to_' . $post_type . ':disabled' ] ) ? false : true;

			if ( $disabled ) {

				$rating_enabled = 0;

			} else {

				/*
				 * Returns an array of values if $single is false, the value of the meta field if $single is true,
				 * false for an invalid $post_id (non-numeric, zero, or negative value), or an empty string if a
				 * valid but non-existing post ID is passed.
				 */
				$current = get_metadata( 'post', $post_id, WPSSORAR_META_ALLOW_RATINGS, $single = true );

				if ( '' === $current ) {

					$rating_enabled = $default;	// Default is 0 or 1.

				} elseif ( empty( $current ) ) {	// 0 or invalid $post_id.

					$rating_enabled = 0;

				} else {

					$rating_enabled = 1;
				}
			}

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'rating for post id ' . $post_id . ' is ' . ( $rating_enabled ? 'enabled' : 'disabled' ) );
			}

			return $local_cache[ $post_id ] = $rating_enabled;
		}

		private static function get_rating_disabled_html( $post_id, $html ) {

			/*
			 * Do not add a newline as newlines are converted to line-breaks in $comment_text.
			 */
			return '<!-- wpsso-rar comment rating disabled for post ID ' . $post_id . ' -->' . $html;
		}

		/*
		 * Update the title, comment field, and submit button to toggle review/comment labels. Note that custom theme
		 * values may be merged by WordPress and overwrite these defaults.
		 */
		public static function update_comment_form_defaults( $defaults ) {

			$post_id = get_the_ID();

			if ( ! self::is_rating_enabled( $post_id ) ) {

				$defaults[ 'comment_field' ] = self::get_rating_disabled_html( $post_id, $defaults[ 'comment_field' ] );

				return $defaults;
			}

			$is_comment_reply   = empty( $_GET[ 'replytocom' ] ) ? false : true;
			$review_begin_html  = "\n" . '<span class="comment-toggle-review"' . ( $is_comment_reply ? ' style="display:none;"' : '' ) . '>';
			$review_end_html    = '</span><!-- .comment-toggle-review -->';
			$comment_begin_html = "\n" . '<span class="comment-toggle-comment"' . ( $is_comment_reply ? '' : ' style="display:none;"' ) . '>';
			$comment_end_html   = '</span><!-- .comment-toggle-comment -->';

			/*
			 * Title
			 */
			$defaults[ 'title_reply' ] =  _x( 'Leave a Review', 'form label', 'wpsso-ratings-and-reviews' );

			$defaults[ 'title_reply_before' ] = '<span class="wpsso-rar title-reply">' .
				$review_begin_html . '<!-- form label: Leave a Review --><h3 id="review-title" class="comment-review-title">' .
					_x( 'Leave a Review', 'form label', 'wpsso-ratings-and-reviews' ) . '</h3>' . $review_end_html .
						$comment_begin_html . '<!-- form label: Leave a Reply -->' . $defaults[ 'title_reply_before' ];

			$defaults[ 'title_reply_after' ] .= $comment_end_html . '</span><!-- .wpsso-rar.title-reply -->' . "\n";

			/*
			 * Comment Box
			 */
			$defaults[ 'comment_field' ] = self::update_form_comment_field( $defaults[ 'comment_field' ] );

			/*
			 * Submit Button
			 */
			$defaults[ 'submit_button' ] = self::update_form_comment_field( $defaults[ 'submit_button' ] );

			return $defaults;
		}

		/*
		 * Also hooked to the 'comment_form_field_comment' filter to modify a theme custom value.
		 */
		public static function update_form_comment_field( $comment_field ) {

			if ( false !== strpos( $comment_field, '.wpsso-rar.comment-field' ) ||
				false !== strpos( $comment_field, 'wpsso-rar comment rating disabled' ) ) {

				return $comment_field;
			}

			$post_id = get_the_ID();

			if ( ! self::is_rating_enabled( $post_id ) ) {

				return self::get_rating_disabled_html( $post_id, $comment_field );
			}

			$is_comment_reply   = empty( $_GET[ 'replytocom' ] ) ? false : true;
			$required_html      = ' <span class="required">*</span>';
			$review_begin_html  = "\n" . '<span class="comment-toggle-review"' . ( $is_comment_reply ? ' style="display:none;"' : '' ) . '>';
			$review_end_html    = '</span><!-- .comment-toggle-review -->';
			$comment_begin_html = "\n" . '<span class="comment-toggle-comment"' . ( $is_comment_reply ? '' : ' style="display:none;"' ) . '>';
			$comment_end_html   = '</span><!-- .comment-toggle-comment -->';

			if ( preg_match( '/^(.*)(<label ([^>]*)for="comment"([^>]*)>.*<\/label>)(.*)$/Uim', $comment_field, $matches ) ) {

				list( $comment_field, $label_before, $comment_label, $attr_before, $attr_after, $label_after ) = $matches;

				$label_attr = ' ' . $attr_before . ' ' . $attr_after;
				$label_attr = preg_replace( '/ id=["\'][^"\']+["\']/', '', $label_attr );
				$label_attr = trim( $label_attr );

				if ( ! empty( $label_attr ) ) {

					$label_attr .= ' ';
				}

				$comment_field = $label_before . $review_begin_html .
					'<!-- form label: Your Review --><label ' . $label_attr . 'for="review"' . '>' .
						_x( 'Your Review', 'form label', 'wpsso-ratings-and-reviews' ) . $required_html . '</label>' . $review_end_html .
							$comment_begin_html . '<!-- form label: Comment -->' . $comment_label . $comment_end_html . $label_after;

				$comment_field = '<span class="wpsso-rar comment-field">' .
					self::get_form_rating_field( $label_attr ) .
						$comment_field . '</span><!-- .wpsso-rar.comment-field -->' . "\n";

			} else {

				$comment_field = '<!-- wpsso-rar comment label attribute missing in \'comment_field\' value -->' . $comment_field;
			}

			return $comment_field;
		}

		/*
		 * Also hooked to the 'comment_form_submit_button' filter to modify a theme custom value.
		 */
		public static function update_form_submit_button( $submit_button ) {

			if ( false !== strpos( $submit_button, '.wpsso-rar.submit-button' ) ||
				false !== strpos( $submit_button, 'wpsso-rar comment rating disabled' ) ) {

				return $submit_button;
			}

			$post_id = get_the_ID();

			if ( ! self::is_rating_enabled( $post_id ) ) {

				return self::get_rating_disabled_html( $post_id, $submit_button );
			}

			$is_comment_reply   = empty( $_GET[ 'replytocom' ] ) ? false : true;
			$required_html      = ' <span class="required">*</span>';
			$review_begin_html  = "\n" . '<span class="comment-toggle-review"' . ( $is_comment_reply ? ' style="display:none;"' : '' ) . '>';
			$review_end_html    = '</span><!-- .comment-toggle-review -->';
			$comment_begin_html = "\n" . '<span class="comment-toggle-comment"' . ( $is_comment_reply ? '' : ' style="display:none;"' ) . '>';
			$comment_end_html   = '</span><!-- .comment-toggle-comment -->';

			$submit_button = '<span class="wpsso-rar submit-button">' . $review_begin_html .
				'<!-- form label: Post Review --><input name="%1$s" type="submit" id="%2$s" class="%3$s" value="' .
					_x( 'Post Review', 'form label', 'wpsso-ratings-and-reviews' ) . '"/>' . $review_end_html .
						$comment_begin_html . '<!-- form label: Post Comment -->' . $submit_button . $comment_end_html .
							'</span><!-- .wpsso-rar.submit-button -->' . "\n";

			return $submit_button;
		}

		private static function get_form_rating_field( $label_attr = '' ) {

			$wpsso = Wpsso::get_instance();

			$is_comment_reply   = empty( $_GET[ 'replytocom' ] ) ? false : true;
			$is_rating_required = empty( $wpsso->options[ 'rar_rating_required' ] ) ? false : true;
			$required_html      = $is_rating_required ? ' <span class="required">*</span>' : '';
			$review_begin_html  = "\n" . '<span class="comment-toggle-review"' . ( $is_comment_reply ? ' style="display:none;"' : '' ) . '>';
			$review_end_html    = '</span><!-- .comment-toggle-review -->';
			$comment_begin_html = "\n" . '<span class="comment-toggle-comment"' . ( $is_comment_reply ? '' : ' style="display:none;"' ) . '>';
			$comment_end_html   = '</span><!-- .comment-toggle-comment -->';

			/*
			 * Auto-hide the paragraph for replies.
			 */
			$select_html = "\n" . '<p class="comment-form-rating"' . ( $is_comment_reply ? ' style="display:none;">' : '>' ) . "\n";

			/*
			 * Auto-disable the select for replies.
			 */
			$select_html .= '<!-- form label: Your Rating --><label ' . $label_attr . 'for="rating"' . '>' .
				_x( 'Your Rating', 'form label', 'wpsso-ratings-and-reviews' ) . $required_html . '</label>
<select name="' . WPSSO_META_RATING_NAME . '" id="rating"' . ( $is_comment_reply ? ' disabled' : '' ) . '>
	<option value="">' . _x( 'Rating&hellip;', 'option value', 'wpsso-ratings-and-reviews' ) . '</option>
	<option value="5">' . _x( 'Excellent', 'option value', 'wpsso-ratings-and-reviews' ) . '</option>
	<option value="4">' . _x( 'Good', 'option value', 'wpsso-ratings-and-reviews' ) . '</option>
	<option value="3">' . _x( 'Average', 'option value', 'wpsso-ratings-and-reviews' ) . '</option>
	<option value="2">' . _x( 'Not Good', 'option value', 'wpsso-ratings-and-reviews' ) . '</option>
	<option value="1">' . _x( 'Awful', 'option value', 'wpsso-ratings-and-reviews' ) . '</option>
</select></p><!-- .comment-form-rating -->' . "\n";

			return $select_html;
		}

		/*
		 * Save the rating value on comment submit, unless it's a reply (replies should not have ratings).
		 */
		public static function save_rating_comment_post( $comment_id, $comment_approved ) {

			if ( ! empty( $_GET[ 'replytocom' ] ) || ! empty( $_POST[ 'replytocom' ] ) ) {	// Don't try to save reply ratings.

				return;
			}

			$rating_value = (int) SucomUtil::get_request_value( WPSSO_META_RATING_NAME, 'POST' );

			if ( $rating_value ) {

				add_comment_meta( $comment_id, WPSSO_META_RATING_NAME, $rating_value );
			}
		}

		public static function update_cache_comment_post( $comment_id, $comment_approved ) {

			if ( $comment_id && $comment_approved ) {

				$comment = get_comment( $comment_id );

				if ( ! empty( $comment->comment_post_ID ) ) {

					self::update_cache_post_meta( $comment->comment_post_ID );
				}
			}
		}

		public static function update_cache_transition_comment_status( $new_status, $old_status, $comment ) {

			if ( 'approved' === $new_status || 'approved' === $old_status ) {

				if ( ! empty( $comment->comment_post_ID ) ) {

					self::update_cache_post_meta( $comment->comment_post_ID );
				}
			}
		}

		public static function update_cache_post_meta( $post_id ) {

			if ( empty( $post_id ) || ! is_numeric( $post_id ) ) {	// Just in case.

				return;
			}

			self::sync_review_count( $post_id );	// Returns an integer.

			/*
			 * Update the rating count before the average rating.
			 */
			self::sync_rating_counts( $post_id );	// Returns an array.

			self::sync_average_rating( $post_id );	// Returns a float.
		}

		/*
		 * Append the rating value to the comment text. This filter is called on both the front and back-end.
		 */
		public static function add_rating_to_comment_text( $comment_text ) {

			/*
			 * Make sure we only add the star rating once (ours or from another plugin).
			 */
			if ( false !== strpos( $comment_text, 'class="star-rating"' ) ) {

				return $comment_text;
			}

			$comment_id  = get_comment_ID();
			$comment_obj = get_comment( $comment_id );

			if ( empty( $comment_obj->comment_post_ID ) ) {

				return '<!-- wpsso-rar comment post ID is empty -->' . $comment_text;

			} elseif ( ! self::is_rating_enabled( $comment_obj->comment_post_ID ) ) {

				return self::get_rating_disabled_html( $comment_obj->comment_post_ID, $comment_text );
			}

			$rating_value = get_metadata( 'comment', $comment_id, WPSSO_META_RATING_NAME, true );

			if ( $rating_value ) {

				$comment_text = '<div class="wpsso-rar">' . self::get_star_rating_html( $rating_value ) . '</div><!-- .wpsso-rar -->' . $comment_text;
			}

			$comment_text = '<!-- wpsso-rar ' . __FUNCTION__ . ' begin -->' . $comment_text . '<!-- wpsso-rar ' . __FUNCTION__ . ' end -->';

			return $comment_text;
		}

		/*
		 * Create the rating stars HTML for the rating value provided.
		 */
		private static function get_star_rating_html( $rating_value ) {

			$rating_html  = '';
			$rating_value = (int) $rating_value;

			if ( empty( $rating_value ) ) {

				$rating_html .= '<!-- wpsso-rar star rating skipped shown for empty rating value -->';

			} else {

				$rating_html .= '<div class="star-rating" title="' . sprintf( __( 'Rated %d out of 5', 'wpsso-ratings-and-reviews' ), $rating_value ) . '">';
				$rating_html .= '<span style="width:' . ( ( $rating_value / 5 ) * 100 ) . '%;">';
				$rating_html .= sprintf( __( 'Rated %d out of 5', 'wpsso-ratings-and-reviews' ), $rating_value );
				$rating_html .= '</span>';
				$rating_html .= '</div><!-- .star-rating -->';
			}

			return $rating_html;
		}

		/*
		 * Average Rating.
		 */
		public static function get_average_rating( $post_id ) {

			$wpsso = Wpsso::get_instance();

			if ( ! metadata_exists( 'post', $post_id, WPSSORAR_META_AVERAGE_RATING ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'syncing ' . WPSSORAR_META_AVERAGE_RATING . ' metadata value' );
				}

				return (float) self::sync_average_rating( $post_id );	// Calculate the average rating.
			}

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'returning ' . WPSSORAR_META_AVERAGE_RATING . ' metadata value' );
			}

			/*
			 * Returns an array of values if $single is false, the value of the meta field if $single is true,
			 * false for an invalid $post_id (non-numeric, zero, or negative value), or an empty string if a
			 * valid but non-existing post ID is passed.
			 */
			return (float) get_metadata( 'post', $post_id, WPSSORAR_META_AVERAGE_RATING, $single = true );
		}

		private static function sync_average_rating( $post_id ) {

			if ( $count_total = self::get_rating_count( $post_id ) ) {

				global $wpdb;

				$rating_total = $wpdb->get_var( $wpdb->prepare( "
					SELECT SUM( meta_value ) FROM $wpdb->commentmeta
					LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
					WHERE meta_key = 'rating'
					AND comment_post_ID = %d
					AND comment_parent = '0'
					AND comment_approved = '1'
					AND meta_value > 0", $post_id ) );

				$average_rating = $rating_total / $count_total;

			} else {

				$average_rating = 0;
			}

			$average_rating = number_format( $average_rating, 2, '.', '' );

			/*
			 * Returns the meta ID if the key didn't exist, true on successful update, false on failure or if the value
			 * passed to the function is the same as the one that is already in the database.
			 */
			update_metadata( 'post', $post_id, WPSSORAR_META_AVERAGE_RATING, $average_rating );

			return (float) $average_rating;
		}

		/*
		 * Return a rating count (ie. the number of ratings for a rating value, or all rating values).
		 *
		 * The WPSSORAR_META_RATING_COUNTS post meta array acts as a cache, which is deleted by the
		 * 'wp_update_comment_count' action.
		 */
		public static function get_rating_count( $post_id, $rating_value = null ) {

			if ( ! metadata_exists( 'post', $post_id, WPSSORAR_META_RATING_COUNTS ) ) {	// Just in case.

				$rating_counts = self::sync_rating_counts( $post_id );

			} else {

				/*
				 * Returns an array of values if $single is false, the value of the meta field if $single is true,
				 * false for an invalid $post_id (non-numeric, zero, or negative value), or an empty string if a
				 * valid but non-existing post ID is passed.
				 */
				$rating_counts = get_metadata( 'post', $post_id, WPSSORAR_META_RATING_COUNTS, $single = true );
			}

			$rating_counts = array_filter( $rating_counts );

			if ( null === $rating_value ) {

				return (int) array_sum( $rating_counts );	// Return a count for all rating values.

			} elseif ( isset( $rating_counts[ $rating_value ] ) ) {	// Return the count for a specific rating.

				return (int) $rating_counts[ $rating_value ];
			}

			return 0;
		}

		private static function sync_rating_counts( $post_id ) {

			global $wpdb;

			$count_meta = $wpdb->get_results( $wpdb->prepare( "
				SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_parent = '0'
				AND comment_approved = '1'
				AND meta_value > 0
				GROUP BY meta_value", $post_id ) );

			$rating_counts = array();

			foreach ( $count_meta as $count ) {

				$rating_counts[ $count->meta_value ] = $count->meta_value_count;
			}

			/*
			 * Returns the meta ID if the key didn't exist, true on successful update, false on failure or if the value
			 * passed to the function is the same as the one that is already in the database.
			 */
			update_metadata( 'post', $post_id, WPSSORAR_META_RATING_COUNTS, $rating_counts );

			return $rating_counts;
		}

		/*
		 * Review Count.
		 */
		public static function get_review_count( $post_id ) {

			if ( ! metadata_exists( 'post', $post_id, WPSSORAR_META_REVIEW_COUNT ) ) {

				return (int) self::sync_review_count( $post_id );
			}

			/*
			 * Returns an array of values if $single is false, the value of the meta field if $single is true,
			 * false for an invalid $post_id (non-numeric, zero, or negative value), or an empty string if a
			 * valid but non-existing post ID is passed.
			 */
			return (int) get_metadata( 'post', $post_id, WPSSORAR_META_REVIEW_COUNT, $single = true );
		}

		private static function sync_review_count( $post_id ) {

			global $wpdb;

			$review_count = $wpdb->get_var( $wpdb->prepare( "
				SELECT COUNT(*) FROM $wpdb->comments
				WHERE comment_parent = 0
				AND comment_post_ID = %d
				AND comment_parent = '0'
				AND comment_approved = '1'", $post_id ) );

			/*
			 * Returns the meta ID if the key didn't exist, true on successful update, false on failure or if the value
			 * passed to the function is the same as the one that is already in the database.
			 */
			update_metadata( 'post', $post_id, WPSSORAR_META_REVIEW_COUNT, $review_count );

			return (int) $review_count;
		}
	}
}
