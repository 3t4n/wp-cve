<?php
/**
 * This file contains the Reference class.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/extensions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class represents a reference in a post.
 *
 * References can be links that are included in a post's content, or
 * links that have been suggested by users in WordPress. Regardless of
 * the relationship between a link and a post, the reference will exist
 * as some information about the given link (title, author, and so on).
 *
 * @SuppressWarnings( PHPMD.ShortVariableName )
 */
class Nelio_Content_Reference {

	/**
	 * The reference (post) ID.
	 *
	 * @var int
	 */
	public $ID = 0;

	/**
	 * Stores post data.
	 *
	 * @var $post WP_Post
	 */
	public $post = null;

	/**
	 * The URL of the reference.
	 *
	 * @var $url string
	 */
	private $url;

	/**
	 * The name of the reference's author.
	 *
	 * @var $author_name string
	 */
	private $author_name;

	/**
	 * The email of the reference's author.
	 *
	 * @var $author_email string
	 */
	private $author_email;

	/**
	 * The Twitter username of the reference's author.
	 *
	 * @var $author_twitter string
	 */
	private $author_twitter;

	/**
	 * Publication date of the reference.
	 *
	 * @var $publication_date string
	 */
	private $publication_date;

	/**
	 * Whether this reference has to be considered a suggestion (for a certain
	 * post) or not.
	 *
	 * @var $is_suggestion string
	 */
	private $is_suggestion;

	/**
	 * Assuming someone suggested this reference, the name of the user who
	 * suggested it.
	 *
	 * @var $suggestion_advisor string
	 */
	private $suggestion_advisor;

	/**
	 * Assuming someone suggested this reference, the date in which the
	 * suggestion was made.
	 *
	 * @var $suggestion_date string
	 */
	private $suggestion_date;

	/**
	 * Creates a new instance of this class.
	 *
	 * @param integer|Nelio_Content_Reference|WP_Post $reference Optional. The
	 *                 identifier of a reference in the database, or a WP_Post
	 *                 instance that contains said reference. If no value is
	 *                 given, a reference object that has no counterpart in the
	 *                 database will be created.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct( $reference = 0 ) {

		if ( is_numeric( $reference ) ) {

			$this->ID = absint( $reference );

			if ( 0 === $this->ID ) {
				$this->post = json_decode(
					wp_json_encode(
						array(
							'post_title'  => '',
							'post_type'   => 'nc_reference',
							'post_status' => 'nc_pending',
						)
					)
				);
			} else {
				$this->post = get_post( $this->ID );
			}//end if
		} elseif ( $reference instanceof Nelio_Content_Reference ) {

			$this->ID   = absint( $reference->ID );
			$this->post = $reference->post;

		} elseif ( isset( $reference->ID ) ) {

			$this->ID   = absint( $reference->ID );
			$this->post = $reference;

		}//end if

		// Initialize variables.
		$this->build();

	}//end __construct()

	/**
	 * Initializes the private variables.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function build() {

		$this->is_suggestion = false;

		if ( $this->is_external() ) {

			$this->url              = get_post_meta( $this->ID, '_nc_url', true );
			$this->author_name      = get_post_meta( $this->ID, '_nc_author_name', true );
			$this->author_email     = get_post_meta( $this->ID, '_nc_author_email', true );
			$this->author_twitter   = $this->atify( get_post_meta( $this->ID, '_nc_author_twitter', true ) );
			$this->publication_date = get_post_meta( $this->ID, '_nc_publication_date', true );

		} else {

			$this->url              = get_permalink( $this->ID );
			$this->author_name      = get_the_author_meta( 'display_name', $this->post->post_author );
			$this->author_email     = get_the_author_meta( 'email', $this->post->post_author );
			$this->author_twitter   = '';
			$this->publication_date = mysql2date( 'Y-m-d', $this->post->post_date );

		}//end if

	}//end build()

	/**
	 * Whether this reference is external (points to an external page) or not
	 * (points to a post in this WordPress).
	 *
	 * @return boolean Whether this reference is external or not.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function is_external() {

		return 'nc_reference' === $this->post->post_type;

	}//end is_external()

	/**
	 * Returns the title of the reference.
	 *
	 * @return string The title of the reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_title() {

		return $this->post->post_title;

	}//end get_title()

	/**
	 * Sets the title of the reference to the given title.
	 *
	 * This function only works for external references.
	 *
	 * @param string $title the new title of the reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_title( $title ) {

		if ( $this->is_external() ) {

			$this->post->post_title = trim( $title );
			if ( $this->ID > 0 && ! $this->maybe_update_status() ) {
				wp_update_post( $this->post );
			}//end if
		}//end if

	}//end set_title()

	/**
	 * Returns the URL of this reference.
	 *
	 * @return string the URL of this reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_url() {

		return $this->url;

	}//end get_url()

	/**
	 * Sets the URL of this reference to the given URL.
	 *
	 * This function only works for external references.
	 *
	 * @param string $url the new URL of this reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_url( $url ) {

		$this->url = $url;
		if ( $this->ID > 0 ) {
			update_post_meta( $this->ID, '_nc_url', $url );
		}//end if
		$this->maybe_update_status();

	}//end set_url()

	/**
	 * Returns the name of the author of this reference.
	 *
	 * @return string the name of the author of this reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_author_name() {

		return $this->author_name;

	}//end get_author_name()

	/**
	 * Sets the name of the author to the given name.
	 *
	 * This function only works for external references.
	 *
	 * @param string $author_name the new name of the author.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_author_name( $author_name ) {

		if ( $this->is_external() ) {
			$this->author_name = $author_name;
			if ( $this->ID > 0 ) {
				update_post_meta( $this->ID, '_nc_author_name', $author_name );
			}//end if
			$this->maybe_update_status();
		}//end if

	}//end set_author_name()

	/**
	 * Returns the email of the author of this reference.
	 *
	 * @return string the author's email.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_author_email() {

		return $this->author_email;

	}//end get_author_email()

	/**
	 * Sets the author's email to the given email.
	 *
	 * This function only works for external references.
	 *
	 * @param string $author_email the new email address.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_author_email( $author_email ) {

		if ( $this->is_external() ) {
			$this->author_email = $author_email;
			if ( $this->ID > 0 ) {
				update_post_meta( $this->ID, '_nc_author_email', $author_email );
			}//end if
			$this->maybe_update_status();
		}//end if

	}//end set_author_email()

	/**
	 * Returns the author's Twitter username.
	 *
	 * @return string the author's Twitter username.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_author_twitter() {

		return $this->author_twitter;

	}//end get_author_twitter()

	/**
	 * Sets the author's Twitter to the given username.
	 *
	 * This function only works for external references.
	 *
	 * @param string $author_twitter the new author's Twitter username.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_author_twitter( $author_twitter ) {

		if ( ! $this->is_external() ) {
			return;
		}//end if

		$this->author_twitter = $this->atify( $author_twitter );
		if ( $this->ID > 0 ) {
			update_post_meta( $this->ID, '_nc_author_twitter', $author_twitter );
		}//end if
		$this->maybe_update_status();

	}//end set_author_twitter()

	/**
	 * Returns the publication date of this reference.
	 *
	 * @return string The publication date following the format YYYY-MM-DD.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_publication_date() {

		return $this->publication_date;

	}//end get_publication_date()

	/**
	 * Sets the publication date of this reference to the given date.
	 *
	 * This function only works for external references.
	 *
	 * @param string $publication_date the new publication date.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_publication_date( $publication_date ) {

		if ( $this->is_external() ) {
			$this->publication_date = $publication_date;
			if ( $this->ID > 0 ) {
				update_post_meta( $this->ID, '_nc_publication_date', $publication_date );
			}//end if
			$this->maybe_update_status();
		}//end if

	}//end set_publication_date()

	/**
	 * Returns the status of this reference.
	 *
	 * (See Reference status in the Register class).
	 *
	 * @return string The status of this reference. If the reference is internal,
	 *                its status is always `complete`.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_status() {

		if ( $this->is_external() ) {
			return str_replace( 'nc_', '', $this->post->post_status );
		} else {
			return 'complete';
		}//end if

	}//end get_status()

	/**
	 * Marks this concrete instance of a reference as suggested by someone on
	 * some date.
	 *
	 * @param integer $advisor ID of the user who suggested this reference.
	 * @param integer $date    UNIX timestamp in which the suggestion was made.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function mark_as_suggested( $advisor, $date ) {

		$this->is_suggestion = true;

		$this->suggestion_advisor = $advisor;
		$this->suggestion_date    = $date;

	}//end mark_as_suggested()

	/**
	 * This function updates the status of this reference based on the amount of
	 * information it contains. Thus, for example, a reference is complete if all
	 * data is properly set, improvable if some data is missing, or pending if
	 * noone has ever set any field of this reference.
	 *
	 * @return boolean Whether the status has been updated to a new value or not.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function maybe_update_status() {

		// Only external references may have a status different than "complete".
		if ( ! $this->is_external() ) {
			return false;
		}//end if

		// If the post status is "broken", we shouldn't update it.
		$status = $this->get_status();
		if ( 'broken' === $status || 'check' === $status ) {
			return false;
		}//end if

		// Finally, we simply need to set the reference to "complete" or "improvable".
		$values = array(
			$this->get_title(),
			$this->get_url(),
			$this->get_author_name(),
			$this->get_author_email(),
			$this->get_author_twitter(),
			$this->get_publication_date(),
		);

		// If one of the values is empty, the reference is "improvable".
		if ( in_array( '', $values, true ) ) {
			$new_status = 'nc_improvable';
		} else {
			$new_status = 'nc_complete';
		}//end if

		$old_status = $this->get_status();
		if ( $new_status !== $old_status ) {

			$this->post->post_status = $new_status;
			if ( $this->ID > 0 ) {
				wp_update_post( $this->post );
			}//end if
			return true;

		} else {

			return false;

		}//end if

	}//end maybe_update_status()

	/**
	 * Returns a Backbone-compatible, JSON encoded version of this reference.
	 *
	 * @return array a Backbone-compatible, JSON encoded version of this reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function json_encode() {

		$result = array(
			'id'           => $this->ID,
			'author'       => $this->get_author_name(),
			'date'         => $this->get_publication_date(),
			'email'        => $this->get_author_email(),
			'isExternal'   => $this->is_external(),
			'isSuggestion' => $this->is_suggestion,
			'status'       => $this->get_status(),
			'title'        => $this->get_title(),
			'twitter'      => $this->get_author_twitter(),
			'url'          => $this->get_url(),
		);

		if ( $this->is_suggestion ) {
			$advisor      = get_userdata( $this->suggestion_advisor );
			$advisor_name = $advisor->first_name;
			if ( empty( $advisor_name ) ) {
				$advisor_name = $advisor->display_name;
			}//end if
			$result['suggestionAdvisorId'] = $this->suggestion_advisor;
			$result['suggestionDate']      = $this->suggestion_date . 'T00:00:00';
		}//end if

		return $result;

	}//end json_encode()

	private function atify( $value ) {
		if ( mb_strlen( $value ) && '@' !== mb_substr( $value, 0, 1 ) ) {
			$value = '@' . $value;
		}//end if
		return $value;
	}//end atify()

}//end class
