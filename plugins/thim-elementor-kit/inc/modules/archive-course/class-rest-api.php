<?php
namespace Thim_EL_Kit\Modules\ArchiveCourse;

use LP_Course;
use LP_Course_Filter;
use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\Utilities\Rest_Response;

class Rest_API {
	use SingletonTrait;

	const NAMESPACE = 'thim-ekit/archive-course';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	public function register_endpoints() {
		register_rest_route(
			self::NAMESPACE,
			'/get-courses',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'get_courses' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public function get_courses( \WP_REST_Request $request ) {
		$atts = $request->get_param( 'atts' );

		$response = new Rest_Response();

		try {
			if ( empty( $atts ) ) {
				throw new \Exception( 'Settings is empty' );
			}

			if ( ! class_exists( '\Elementor\Thim_Ekits_Course_Base' ) ) {
				include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/global/course-base.php';
			}

			if ( ! class_exists( '\Elementor\Thim_Ekit_Widget_Archive_Course' ) ) {
				include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/archive-course/archive-course.php';
			}

			$filter     = new LP_Course_Filter();
			$params_url = $request['params_url'] ?? [];
			$params_url = array_merge( $params_url, $request->get_params() );

			if ( method_exists( 'LP_Course', 'handle_params_for_query_courses' ) ) {
				LP_Course::handle_params_for_query_courses( $filter, $params_url );
			}

			$total_rows = 0;
			$courses    = LP_Course::get_courses( $filter, $total_rows );

			$archive = new \Elementor\Thim_Ekit_Widget_Archive_Course();

			$archive->is_skeleton = false;

			$atts = json_decode( $atts, true );

			$settings = isset( $atts['settings'] ) ? $atts['settings'] : array();

			$response->status = 'success';

			$response->data->page = $filter->page;

			foreach ( $settings['thim_header_repeater'] as $item ) {
				if ( $item['header_key'] === 'result' ) {
					ob_start();
					$archive->render_result_count( $filter, $total_rows, $item );
					$response->data->result_count = wp_strip_all_tags( ob_get_clean() );
				}
			}

			ob_start();
			$archive->render_loop_footer( $filter, $total_rows, $settings );
			$response->data->pagination = ob_get_clean();

			global $post;

			ob_start();
			if ( $courses ) {
				foreach ( $courses as $course_id ) {
					$post = get_post( $course_id );
					setup_postdata( $post );

					$archive->render_course( $settings, 'thim-ekits-course__item' );
				}
			}

			wp_reset_postdata();
			$response->data->courses = ob_get_clean();

		} catch ( \Throwable $th ) {
			$response->status  = 'error';
			$response->message = $th->getMessage();
		}

		return rest_ensure_response( $response );
	}
}

Rest_API::instance();
