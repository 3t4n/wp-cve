<?php
/**
 * Struct that represents an UpStream Comment.
 *
 * @package UpStream
 */

namespace UpStream;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Struct that represents an UpStream Comment.
 *
 * @since   1.13.0
 */
class Comment extends Struct {
	/**
	 * Comment ID.
	 *
	 * @since   1.13.0
	 *
	 * @var     int $id
	 */
	public $id;

	/**
	 * Project ID where the comments belongs to.
	 *
	 * @since   1.13.0
	 *
	 * @var     int $project_id
	 */
	public $project_id;

	/**
	 * Comment parent ID.
	 *
	 * @since   1.13.0
	 *
	 * @var     int $parent_id
	 */
	public $parent_id;

	/**
	 * Comment content.
	 *
	 * @since   1.13.0
	 *
	 * @var     string $content
	 */
	public $content;

	/**
	 * Comment current status.
	 * Valid values are: -2, -1, 0, 1 ~ representing spam, trash, unapproved, approved.
	 *
	 * @since   1.13.0
	 *
	 * @var     int $state
	 */
	public $state;

	/**
	 * Comment author.
	 *
	 * @since   1.13.0
	 *
	 * @var     object $created_by
	 */
	public $created_by;

	/**
	 * Date info where the comment was added.
	 *
	 * @since   1.13.0
	 *
	 * @var     object $created_at
	 */
	public $created_at;

	/**
	 * Current cached user capabilities related to comments.
	 *
	 * @since   1.13.0
	 *
	 * @var     object $current_user_cap
	 */
	public $current_user_cap;

	/**
	 * Cached author info.
	 *
	 * @since   1.13.0
	 * @access  protected
	 *
	 * @var     object $author
	 */
	protected $author;

	/**
	 * Class constructor.
	 *
	 * @since   1.13.0
	 *
	 * @param   string $content    Comment content.
	 * @param   int    $project_id Project ID.
	 * @param   int    $user_id    Author ID.
	 */
	public function __construct( $content = '', $project_id = 0, $user_id = 0 ) {
		if ( ! empty( $content ) ) {
			// Make sure the comment content is always filtered.
			$allowed_tags  = apply_filters( 'upstream_allowed_tags_in_comments', array() );
			$this->content = wp_kses( $content, wp_kses_allowed_html( $allowed_tags ) );
		}

		if ( (int) $project_id <= 0 ) {
			$this->project_id = upstream_post_id();
		} else {
			$this->project_id = (int) $project_id;
		}

		if ( (int) $user_id > 0 ) {
			$author = get_user_by( 'id', $user_id );
		}

		$user = wp_get_current_user();

		$user_has_admin_capabilities = upstream_is_user_either_manager_or_admin( $user );
		$user_can_moderate_comments  = ! $user_has_admin_capabilities ? user_can( $user, 'moderate_comments' ) : true;
		$this->current_user_cap      = (object) array(
			'can_reply'    => ! $user_has_admin_capabilities ? user_can( $user, 'publish_project_discussion' ) : true,
			'can_moderate' => $user_can_moderate_comments,
			'can_delete'   => ! $user_has_admin_capabilities ? $user_can_moderate_comments || user_can(
				$user,
				'delete_project_discussion'
			) : true,
		);

		$this->author = isset( $author ) ? $author : $user;

		$this->created_by = (object) array(
			'id'     => $author->ID,
			'name'   => $author->display_name,
			'avatar' => upstream_get_user_avatar_url( $author->ID ),
			'email'  => $author->user_email,
		);

		$this->created_at = (object) array(
			'timestamp' => 0,
			'utc'       => '',
			'localized' => '',
			'humanized' => '',
		);

		$this->parent_id = 0;
		$this->state     = 1;
	}

	/**
	 * Fill missing comment info from a given comment array.
	 *
	 * @since   1.13.0
	 * @static
	 *
	 * @param   array $custom_data Associative array with comment info.
	 *
	 * @return  array
	 */
	public static function arrayToWPPatterns( $custom_data ) {
		$default_data = array(
			'comment_post_ID'      => 0,
			'comment_author'       => '',
			'comment_author_email' => '',
			'comment_author_IP'    => '',
			'comment_date'         => '',
			'comment_date_gmt'     => '',
			'comment_content'      => null,
			'comment_agent'        => '',
			'user_id'              => 0,
			'comment_approved'     => 1,
			'comment_type'         => '',
		);

		$data = array_merge( $default_data, (array) $custom_data );

		return $data;
	}

	/**
	 * Load a given comment data into its own instance based on ID.
	 *
	 * @since   1.13.0
	 * @static
	 *
	 * @param   int $comment_id Comment ID to be loaded.
	 *
	 * @return   Comment
	 */
	public static function load( $comment_id ) {
		$data = get_comment( $comment_id );

		if ( empty( $data ) ) {
			return null;
		}

		$comment                        = new Comment( $data->comment_content, $data->comment_post_ID, $data->user_id );
		$comment->id                    = (int) $data->comment_ID;
		$comment->created_at->timestamp = strtotime( $data->comment_date_gmt );
		$comment->created_at->utc       = $data->comment_date_gmt;
		$comment->created_at->localized = $data->comment_date;
		$comment->updateHumanizedDate();
		$comment->state     = self::convertStateToInt( $data->comment_approved );
		$comment->parent_id = (int) $data->comment_parent;

		return $comment;
	}

	/**
	 * Convert a given comment state into its equivalent int value.
	 *
	 * @since   1.13.0
	 * @static
	 *
	 * @param   string $state State to be converted.
	 *
	 * @return  int
	 */
	public static function convertStateToInt( $state ) {
		if ( is_numeric( $state ) ) {
			$state = (int) $state;
		} elseif ( 'approve' === $state ) {
			$state = 1;
		} elseif ( 'hold' === $state ) {
			$state = 0;
		} elseif ( 'trash' === $state ) {
			$state = -1;
		} elseif ( 'spam' === $state ) {
			$state = -2;
		}

		return $state;
	}

	/**
	 * Either insert/update the comment into DB.
	 *
	 * @since   1.13.0
	 *
	 * @return  mixed      int if inserted or bool if updated
	 * @throws  \Exception Unable to save the data into database.
	 */
	public function save() {
		if ( $this->isNew() ) {
			$this->doFilters();

			$data = $this->toWpPatterns();

			// Commented to avoid comment rejection by WordPress on simmilar comments made across different items on the same project.
			// if (is_wp_error($integrity_check)) {
			// throw new \Exception($integrity_check->get_error_message());
			// }.

			$this->created_at->timestamp = time();
			$this->created_at->utc       = gmdate( 'Y-m-d H:i:s', $this->created_at->timestamp );
			$data['comment_date_gmt']    = $this->created_at->utc;
			$integrity_check             = wp_allow_comment( $data, true );
			$this->state                 = 'spam' !== $integrity_check ? (int) $integrity_check : $integrity_check;
			$date_format                 = get_option( 'date_format' );
			$time_format                 = get_option( 'time_format' );
			$the_date_time_format        = $date_format . ' ' . $time_format;
			$date                        = \DateTime::createFromFormat( 'Y-m-d H:i:s', $this->created_at->utc );
			$this->created_at->localized = $date->format( $the_date_time_format );
			$data['comment_date']        = $date->format( 'Y-m-d H:i:s' );
			$this->created_at->humanized = _x( 'just now', 'Comment was very recently added.', 'upstream' );
			$allowed_tags                = apply_filters( 'upstream_allowed_tags_in_comments', array() );
			$data['comment_content']     = wp_kses( $data['comment_content'], wp_kses_allowed_html( $allowed_tags ) );
			$a                           = $this->html2text( $data['comment_content'] );
			$comment_id                  = wp_insert_comment( $data );

			if ( ! $comment_id ) {
				throw new \Exception( __( 'Unable to save the data into database.', 'upstream' ) );
			}

			$this->id = $comment_id;

			return $this->id;
		} else {
			$data    = $this->toWpPatterns();
			$success = (bool) wp_update_comment( $data );

			if ( ! $success ) {
				throw new \Exception( __( 'Unable to save the data into database.', 'upstream' ) );
			}

			return true;
		}
	}

	/**
	 * HTML to text
	 *
	 * @since 1.13.0
	 *
	 * @param string $document The document to convert.
	 */
	public function html2text( $document ) {
		$rules = array(
			'@<script[^>]*?>.*?</script>@si',
			'@<[\/\!]*?[^<>]*?>@si',
			'@([\r\n])[\s]+@',
			'@&(quot|#34);@i',
			'@&(amp|#38);@i',
			'@&(lt|#60);@i',
			'@&(gt|#62);@i',
			'@&(nbsp|#160);@i',
			'@&(iexcl|#161);@i',
			'@&(cent|#162);@i',
			'@&(pound|#163);@i',
			'@&(copy|#169);@i',
			'@&(reg|#174);@i',
			'@&#(d+);@i',
		);

		$replace = array(
			'',
			'',
			'',
			'',
			'&',
			'<',
			'>',
			' ',
			chr( 161 ),
			chr( 162 ),
			chr( 163 ),
			chr( 169 ),
			chr( 174 ),
			'chr()',
		);

		return preg_replace( $rules, $replace, $document );
	}

	/**
	 * Check if instance represents a new or an existent comment.
	 *
	 * @since   1.13.0
	 *
	 * @return  bool
	 */
	public function isNew() {
		return (int) $this->id <= 0;
	}

	/**
	 * Apply WordPress comment filters to the instance data.
	 *
	 * @since   1.13.0
	 *
	 * @uses    wp_filter_comment
	 */
	public function doFilters() {
		$data = $this->toWpPatterns();

		// TODO: why did it filter here and then later elsewhere.
		$safe_data = $data; // code: wp_filter_comment( $data ).

		$this->created_by->id    = (int) $safe_data['user_id'];
		$this->created_by->agent = $safe_data['comment_agent'];
		$this->created_by->name  = $safe_data['comment_author'];
		$this->created_by->email = $safe_data['comment_author_email'];
		$this->created_by->ip    = $safe_data['comment_author_IP'];
		$this->content           = $safe_data['comment_content'];
	}

	/**
	 * Retrieve an associative array following WordPress Comments design pattern with the instance's data.
	 *
	 * @since   1.13.0
	 *
	 * @return  array
	 */
	public function toWpPatterns() {
		$data = array(
			'comment_id'           => (int) $this->id,
			'comment_post_ID'      => (int) $this->project_id,
			'comment_author_url'   => '',
			'user_id'              => (int) $this->created_by->id,
			'comment_author'       => $this->created_by->name,
			'comment_author_email' => $this->created_by->email,
			'comment_content'      => $this->content,
			'comment_approved'     => self::convertStateToWpPatterns( $this->state ),
			'comment_author_IP'    => isset( $this->created_by->ip ) ? $this->created_by->ip : '',
			'comment_agent'        => isset( $this->created_by->agent ) ? $this->created_by->agent : '',
			'comment_parent'       => (int) $this->parent_id > 0 ? $this->parent_id : 0,
			'comment_type'         => '',
		);

		return $data;
	}

	/**
	 * Convert a given comment state into its equivalent WordPress value.
	 *
	 * @since   1.13.0
	 * @static
	 *
	 * @param   mixed $state The state to be translated.
	 *
	 * @return  mixed
	 */
	public static function convertStateToWpPatterns( $state ) {
		if ( is_numeric( $state ) ) {
			$state = (int) $state;

			if ( -1 === $state ) {
				$state = 'trash';
			}
		} elseif ( 'approve' === $state ) {
			$state = 1;
		} elseif ( 'hold' === $state ) {
			$state = 0;
		}

		return $state;
	}

	/**
	 * Unapprove the comment.
	 *
	 * @since   1.13.0
	 *
	 * @return  bool
	 */
	public function unapprove() {
		if ( ! $this->isNew() ) {
			$success = self::updateApprovalState( $this->id, 0 );

			if ( $success ) {
				$this->state = 0;
			}

			return $success;
		}

		return false;
	}

	/**
	 * Update comment state statically.
	 *
	 * @since   1.13.0
	 * @access  protected
	 * @static
	 *
	 * @param   int   $comment_id  Comment ID to be updated.
	 * @param   mixed $new_state   Comment's new state.
	 *
	 * @return  bool
	 */
	protected static function updateApprovalState( $comment_id, $new_state ) {
		if ( ! in_array( strtolower( (string) $new_state ), array( '1', '0', 'spam', 'trash' ), true ) ) {
			return false;
		}

		$data = array(
			'comment_ID'       => (int) $comment_id,
			'comment_approved' => $new_state,
		);

		$success = (bool) wp_update_comment( $data );

		return $success;
	}

	/**
	 * Approve the comment.
	 *
	 * @since   1.13.0
	 *
	 * @return  bool
	 */
	public function approve() {
		if ( ! $this->isNew() ) {
			$success = self::updateApprovalState( $this->id, 1 );

			if ( $success ) {
				$this->state = 1;
			}

			return $success;
		}

		return false;
	}

	/**
	 * Render the comment as HTML.
	 *
	 * @since   1.13.0
	 *
	 * @param   bool  $return           Either return the HTML or render it instead.
	 * @param   bool  $use_admin_layout Either use admin/frontend layout.
	 * @param   array $comments_cache   Array of comments passed to render functions.
	 *
	 * @return  string  Will only return something if $return is true.
	 */
	public function render( $return = false, $use_admin_layout = true, $comments_cache = array() ) {
		if ( empty( $this->current_user_cap ) ) {
			$user                              = wp_get_current_user();
			$user_has_admin_capabilities       = upstream_is_user_either_manager_or_admin();
			$this->current_user_cap->can_reply = ! $user_has_admin_capabilities ? user_can(
				$user,
				'publish_project_discussion'
			) : true;

			$user_can_moderate                   = ! $user_has_admin_capabilities ? user_can(
				$user,
				'moderate_comments'
			) : true;
			$this->current_user_cap->can_moderate = $user_can_moderate;
			$this->current_user_cap->can_delete   = ! $user_has_admin_capabilities ? ( $user_can_moderate || user_can(
				$user,
				'delete_project_discussion'
			) || $user->ID === (int) $created_by->id ) : true;
		}

		$this->updateHumanizedDate();

		if ( true === (bool) $return ) {
			ob_start();

			if ( true === (bool) $use_admin_layout ) {
				upstream_admin_display_message_item( $this, $comments_cache );
			} else {
				upstream_display_message_item( $this, $comments_cache );
			}

			$html = ob_get_contents();

			ob_end_clean();

			return $html;
		} else {
			if ( true === (bool) $use_admin_layout ) {
				upstream_admin_display_message_item( $this, $comments_cache );
			} else {
				upstream_display_message_item( $this, $comments_cache );
			}
		}
	}

	/**
	 * Update comment's dates to human format.
	 *
	 * @since   1.13.0
	 */
	public function updateHumanizedDate() {
		$date_format          = get_option( 'date_format' );
		$time_format          = get_option( 'time_format' );
		$the_date_time_format = $date_format . ' ' . $time_format;
		$current_timestamp    = time();

		$date           = \DateTime::createFromFormat( 'Y-m-d H:i:s', $this->created_at->utc );
		$date_timestamp = $date->getTimestamp();

		$this->created_at->localized = $date->format( $the_date_time_format );
		$this->created_at->humanized = sprintf(
			// translators: %s = human-readable time difference.
			_x( '%s ago', '%s = human-readable time difference', 'upstream' ),
			human_time_diff( $date_timestamp, $current_timestamp )
		);
	}
}
