<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bricksable.com/about-us/
 * @since      1.0.0
 *
 * @package    Bricksable
 * @subpackage Bricksable/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'Bricksable_Review' ) ) :
	/**
	 * Bricksable_Review class.
	 */
	class Bricksable_Review {
		/**
		 * Private variables - Bricksable Slug.
		 *
		 * @var     object
		 * @access  private
		 * @since   1.0.0
		 */
		private $slug;

		/**
		 * Private variables - Bricksable name.
		 *
		 * @var     object
		 * @access  private
		 * @since   1.0.0
		 */
		private $name;

		/**
		 * Private variables - Bricksable time limit.
		 *
		 * @var     object
		 * @access  private
		 * @since   1.0.0
		 */
		private $time_limit;

		/**
		 * Variables
		 *
		 * @var     object
		 * @access  public
		 * @since   1.0.0
		 */
		public $nobug_option;

		/**
		 * Constructor funtion.
		 *
		 * @param string $args args.
		 */
		public function __construct( $args ) {
			$this->slug = $args['slug'];
			$this->name = $args['name'];
			if ( isset( $args['time_limit'] ) ) {
				$this->time_limit = $args['time_limit'];
			} else {
				$this->time_limit = WEEK_IN_SECONDS;
			}
			$this->nobug_option = $this->slug . '_no_bug';
			// Loading main functionality.
			add_action( 'admin_init', array( $this, 'check_installation_date' ) );
			add_action( 'admin_init', array( $this, 'set_no_bug' ), 5 );
		}

		/**
		 * Seconds to words.
		 *
		 * @param string $seconds seconds.
		 */
		public function seconds_to_words( $seconds ) {
			// Get the years.
			$years = (int) ( intval( $seconds ) / YEAR_IN_SECONDS ) % 100;

			if ( $years > 1 ) {
				/* translators: 1: years */
				return sprintf( __( '%s years', 'bricksable' ), $years );
			} elseif ( $years > 0 ) {
				return __( 'a year', 'bricksable' );
			}
			// Get the weeks.
			$weeks = (int) ( intval( $seconds ) / WEEK_IN_SECONDS ) % 52;
			if ( $weeks > 1 ) {
				/* translators: 1: weeks */
				return sprintf( __( '%s weeks', 'bricksable' ), $weeks );
			} elseif ( $weeks > 0 ) {
				return __( 'a week', 'bricksable' );
			}
			// Get the days.
			$days = (int) ( intval( $seconds ) / DAY_IN_SECONDS ) % 7;
			if ( $days > 1 ) {
				/* translators: 1: days */
				return sprintf( __( '%s days', 'bricksable' ), $days );
			} elseif ( $days > 0 ) {
				return __( 'a day', 'bricksable' );
			}
			// Get the hours.
			$hours = (int) ( intval( $seconds ) / HOUR_IN_SECONDS ) % 24;
			if ( $hours > 1 ) {
				/* translators: 1: hours */
				return sprintf( __( '%s hours', 'bricksable' ), $hours );
			} elseif ( $hours > 0 ) {
				return __( 'an hour', 'bricksable' );
			}
			// Get the minutes.
			$minutes = (int) ( intval( $seconds ) / MINUTE_IN_SECONDS ) % 60;
			if ( $minutes > 1 ) {
				/* translators: 1: minutes */
				return sprintf( __( '%s minutes', 'bricksable' ), $minutes );
			} elseif ( $minutes > 0 ) {
				return __( 'a minute', 'bricksable' );
			}
			// Get the seconds.
			$seconds = intval( $seconds ) % 60;
			if ( $seconds > 1 ) {
				/* translators: 1: seconds */
				return sprintf( __( '%s seconds', 'bricksable' ), $seconds );
			} elseif ( $seconds > 0 ) {
				return __( 'a second', 'bricksable' );
			}
		}

		/**
		 * Insert the install date
		 */
		public static function insert_install_date() {
			add_site_option( 'bricksable_activation_date', time() );
		}

		/**
		 * Check date on admin initiation and add to admin notice if it was more than the time limit.
		 */
		public function check_installation_date() {

			if ( false === get_site_option( $this->nobug_option ) ) {
				// If not installation date set, then add it.
				$install_date = get_site_option( $this->slug . '_activation_date' );

				if ( ! $install_date ) {
					add_site_option( $this->slug . '_activation_date', time() );
				}
				// If difference between install date and now is greater than time limit, then display notice.
				if ( ( time() - $install_date ) >= $this->time_limit ) {
					add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
				}
			}
		}

		/**
		 * Display Admin Notice, asking for a review.
		 */
		public function display_admin_notice() {

			$screen = get_current_screen();
			if ( isset( $screen->base ) && 'plugins' === $screen->base ) {

				$no_bug_url = wp_nonce_url( admin_url( 'plugins.php?' . $this->nobug_option . '=true' ), 'bricksable-review-nonce' );
				$time       = $this->seconds_to_words( time() - get_site_option( $this->slug . '_activation_date' ) );

				$no_bug_html = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( $no_bug_url ),
					__( 'No thanks.', 'bricksable' )
				);

				$message = sprintf(
					'You have been using the %1$s plugin for %2$s now, do you like it? If so, please do us a favor by leaving us a 5-stars rating with your feedback on WordPress.org.<br />A huge thanks in advance! Bricksable will remain free as always in WordPress plugin repo. <div class="bricksable-admin-go-pro" style="display: flex; align-items: center; padding-top: 10px;"><a onclick="location.href=\'' . esc_url( $no_bug_url ) . '\';" class="button button-primary" href="' . esc_url( 'https://wordpress.org/support/plugin/bricksable/reviews/?rate=5#new-post' ) . '" target="_blank">' . __( 'Leave A Review', 'bricksable' ) . '</a></div>
					%3$s',
					$this->name,
					$time,
					$no_bug_html
				);

				$html = '<div class="notice notice-info">' . wpautop( $message ) . '</div>';
				echo wp_kses_post( $html );
			}
		}

		/**
		 * Set the plugin to no longer bug users if user asks not to be.
		 */
		public function set_no_bug() {
			// Bail out if not on correct page.
			if ( ! isset( $_GET['_wpnonce'] ) || (
				! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'bricksable-review-nonce' )
				||
				! is_admin()
				||
				! isset( $_GET[ $this->nobug_option ] )
				||
				! current_user_can( 'manage_options' )
			)
			) {
				return;
			}
			add_site_option( $this->nobug_option, true );
		}
	}
endif;
