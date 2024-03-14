<?php
/**
 * Google classroom Integration Addon.
 *
 * @since 1.8.3
 *
 * @package Masteriyo\Addons\GoogleClassroomIntegration
 */

namespace Masteriyo\Addons\GoogleClassroomIntegration;

use Masteriyo\Addons\GoogleClassroomIntegration\Controllers\GoogleClassroomIntegrationController;
use Masteriyo\Addons\GoogleClassroomIntegration\Controllers\GoogleClassroomSettingController;
use Masteriyo\Addons\GoogleClassroomIntegration\Models\GoogleClassroomSetting;
use Masteriyo\Constants;
use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Query\CourseProgressQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Google Classroom Integration addon class.
 *
 * @since 1.8.3
 */
class GoogleClassroomIntegrationAddon {


	/**
	 * @var Setting
	 *
	 * @since 1.8.3
	 */
	public $setting;

	/**
	 * constructor
	 *
	 * @since 1.8.3
	 */
	public function __construct() {
	}

	/**
	 * Init addon.
	 *
	 * @since 1.8.3
	 *
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.8.3
	 *
	 */
	public function init_hooks() {
		add_filter( 'masteriyo_single_course_start_text', array( $this, 'google_classroom_enroll_course_button' ), 10, 2 );
		add_action( 'masteriyo_after_learn_page_process', array( $this, 'learn_page_handle' ) );
		add_filter( 'masteriyo_rest_api_get_rest_namespaces', array( $this, 'register_rest_namespaces' ) );
		add_filter( 'masteriyo_admin_submenus', array( $this, 'add_google_classroom_submenu' ) );
		add_filter( 'admin_init', array( $this, 'redirect_google_classroom' ) );
		add_filter( 'masteriyo_rest_response_course_data', array( $this, 'get_google_classroom_data_for_course' ), 10, 3 );
		add_action( 'masteriyo_new_course', array( $this, 'set_google_classroom_data_for_course' ), 10, 3 );
		add_action( 'masteriyo_after_course_content', array( $this, 'render_google_classroom_course_code' ), 10, 1 );
		add_filter( 'masteriyo_rest_response_user_course_data', array( $this, 'user_course_google_classroom_data' ), 10, 3 );
		add_filter( 'create_google_client', array( $this, 'create_google_client', 10 ) );
		add_filter( 'masteriyo_localized_public_scripts', array( $this, 'localize_single_course_page_scripts' ) );
	}

	/**
	 * Localize single-course page scripts.
	 *
	 * @since 1.8.3
	 *
	 * @param array $scripts Array of scripts.
	 *
	 * @return array
	 */
	public function localize_single_course_page_scripts( $scripts ) {

		if ( ! masteriyo_is_single_course_page() ) {
			return $scripts;
		}
		$scripts['single-course']['data']['course_complete_nonce'] = wp_create_nonce( 'masteriyo_course_completion_nonce' );

		return $scripts;
	}

	/**
	 * add data to user course.
	 *
	 * @since 1.8.3
	 */
	public function user_course_google_classroom_data( $data, $course, $context ) {
		if ( get_post_meta( $course->get_id(), '_google_course_url', true ) ) {
			$data['google_classroom_course_url'] = get_post_meta( $course->get_id(), '_google_course_url', true );
		}
		return $data;
	}

	/**
	 * learn page redirect on progress started match.
	 *
	 * @since
	 */
	public function learn_page_handle( $course ) {
		if ( masteriyo_is_google_classroom_course( $course ) ) {
			wp_redirect( get_post_meta( $course->get_id(), '_google_course_url', true ) ); //phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
			exit;
		}
	}



	/**
	 * Set google classroom data inside the course,
	 *
	 * @since 1.8.3
	 */
	public function set_google_classroom_data_for_course( $id, $course ) {
		$request = masteriyo_current_http_request();

		if ( null === $request ) {
			return;
		}

		if ( ! isset( $request['google_classroom_course_id'] ) ) {
			return;
		}

		if ( isset( $request['google_classroom_course_id'] ) ) {
			$course->update_meta_data( '_google_classroom_course_id', sanitize_text_field( $request['google_classroom_course_id'] ) );
		}

		if ( isset( $request['google_classroom_enrollment_code'] ) ) {
			$course->update_meta_data( '_google_classroom_enrollment_code', sanitize_text_field( $request['google_classroom_enrollment_code'] ) );
		}

		if ( isset( $request['google_course_url'] ) ) {
			$course->update_meta_data( '_google_course_url', sanitize_text_field( $request['google_course_url'] ) );
		}
		$course->save_meta_data();
	}

	/**
	 * Insert google classroom data inside the course,
	 *
	 * @since 1.8.3
	 */
	public function get_google_classroom_data_for_course( $data, $course, $context ) {
		$data['google_classroom_course_id']       = get_post_meta( $course->get_id(), '_google_classroom_enrollment_code', true );
		$data['google_classroom_enrollment_code'] = get_post_meta( $course->get_id(), '_google_classroom_course_id', true );
		$data['google_classroom_course_url']      = get_post_meta( $course->get_id(), '_google_course_url', true );
		return $data;
	}


	/**
	 * get google classroom setting data.
	 *
	 * @since 1.8.3
	 *
	 */
	public function google_classroom_setting_data() {
		$google_classroom_setting = ( new GoogleClassroomSetting() )->get_data();
		return $google_classroom_setting;
	}

	/**
	 * shows the google classroom code templates when user clicks the google class room tab.
	 *
	 * @since 1.8.3
	 *
	 * @param \Masteriyo\Models\Course $course The course objects.
	 */
	public function render_google_classroom_course_code( $course ) {
		$google_classroom_id            = get_post_meta( $course->get_id(), '_google_classroom_course_id', true );
		$google_setting                 = $this->google_classroom_setting_data();
		$course_code                    = get_post_meta( $course->get_id(), '_google_classroom_enrollment_code', true );
		$progress                       = '';
		$user_already_enrolled_n_active = get_current_user_id() && masteriyo_is_google_classroom_course( $course ) && masteriyo_is_user_already_enrolled( get_current_user_id(), $course->get_id(), 'active' );

		if ( $user_already_enrolled_n_active ) {
			$query   = new CourseProgressQuery(
				array(
					'course_id' => $course->get_id(),
					'user_id'   => get_current_user_id(),
					'status'    => array( CourseProgressStatus::COMPLETED ),
				)
			);
			$current = current( $query->get_course_progress() );

			$progress = $current ? $current->get_status() : '';
		}

		if ( get_option( 'masteriyo_google_classroom_access_code_enabled' ) && ! is_user_logged_in() ) {
			$course_code = '';
		}

		$is_view_button = $user_already_enrolled_n_active && 'completed' !== $progress;

		require_once Constants::get( 'MASTERIYO_GOOGLE_CLASSROOM_INTEGRATION_DIR' ) . '/templates/google-classroom.php';
	}

	/**
	 * google classroom enroll course button if course is imported through google classroom.
	 *
	 * @since 1.8.3
	 *
	 * @param string[] $class An array of class names.
	 * @param \Masteriyo\Models\Course $course Course object.
	 * @param \Masteriyo\Models\CourseProgress $progress Course progress object.
	 */
	public function google_classroom_enroll_course_button( $text, $course ) {
		$google_course_id = get_post_meta( $course->get_id(), '_google_classroom_course_id', true );
		$current_user     = masteriyo_get_current_user_id();

		if ( ! $google_course_id ) {
			return $text;
		}

		if ( ! is_user_logged_in() ) {
			return $text;
		}

		$is_user_course_active = masteriyo_is_user_already_enrolled( $current_user, $course->get_id(), 'active' );

		if ( $is_user_course_active ) {
			$text = __( 'Continue', 'masteriyo' );
		}

		return $text;
	}

	/**
	 * When user clicks the Connect Google Account button in setting of google class room, and provides the google access, it
	 * redirects to google classroom page in backend page. if the code is valid.
	 *
	 * @since 1.8.3
	 */
	public function redirect_google_classroom() {
		if ( ! empty( $_GET['code'] ) && ! empty( $_GET['page'] ) && isset( $_GET['state'] ) && 'masteriyo_google_classroom' === $_GET['state'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$code                = $_GET['code'];// phpcs:ignore WordPress.Security.NonceVerification
			$google_setting_data = ( new GoogleClassroomSetting() )->get_data();
			$setting             = new GoogleClassroomSetting();

			$google_provider = create_google_client( $google_setting_data );

			try {

				$access_token = $google_provider->getAccessToken(
					'authorization_code',
					array(
						'code' => $code,
					)
				);

				$refresh_token = $access_token->getRefreshToken();

				if ( $access_token ) {
					$token = json_decode( json_encode( $access_token ) );//PHPCS(WordPress.WP.AlternativeFunctions.json_encode_json_encode)

					if ( $refresh_token ) {
						$setting->set( 'refresh_token', $refresh_token );
					} else {
						$setting->set( 'refresh_token', '' );
					}
					$setting->set( 'access_token', $token->access_token );
					$setting->save();

					$data = masteriyo_google_classroom_course_data_insertion( $access_token, $google_provider );

					update_option( 'masteriyo_google_classroom_data_' . masteriyo_get_current_user_id(), $data );
				}
				$site_url = get_site_url();
				wp_safe_redirect(
					$site_url . '/wp-admin/admin.php?page=masteriyo#/google-classrooms'
				);
			} catch ( \League\OAuth2\Client\Provider\Exception\IdentityProviderException $e ) {
				$setting->set( 'refresh_token', '' );
				$setting->save();
				wp_die( esc_html__( 'There was an error with Google Classroom authentication. Please ensure your credentials are correct and try again.', 'masteriyo' ) );
			} catch ( \Exception $e ) {
				$setting->set( 'refresh_token', '' );
				$setting->save();
				if ( $e->getCode() === 403 ) {
					wp_die( esc_html__( 'Google Classroom API has not been used in the given project, Please Enable Google Classroom API.', 'masteriyo' ) );
				}
					wp_die( esc_html__( 'There was an error connecting to Google Classroom. Please try again later.', 'masteriyo' ) );
			}
		}
	}

	/**
	 * Register namespaces.
	 *
	 * @since 1.8.3
	 *
	 * @param array $namespaces Rest namespaces.
	 * @return array
	 */
	public function register_rest_namespaces( $namespaces ) {
		$namespaces['masteriyo/v1']['google-classroom']         = GoogleClassroomIntegrationController::class;
		$namespaces['masteriyo/v1']['google-classroom_setting'] = GoogleClassroomSettingController::class;
		return $namespaces;
	}

	/**
	 * Add google classroom submenu.
	 *
	 * @since 1.8.3
	 *
	 * @param array $submenus Submenus.
	 */
	public function add_google_classroom_submenu( $submenus ) {
		$submenus['google-classrooms'] = array(
			'page_title' => __( 'Google Classroom', 'masteriyo' ),
			'menu_title' => __( 'Google Classroom', 'masteriyo' ),
			'capability' => 'edit_google_classrooms',
			'position'   => 85,
		);

		return $submenus;
	}


}
