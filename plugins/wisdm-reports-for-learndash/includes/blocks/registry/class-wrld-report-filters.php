<?php
/**
 * Report Filters
 *
 * @package learndash-reports-by-wisdmlabs
 */

namespace WisdmReportsLearndashBlockRegistry;

require_once 'class-wrld-register-block.php';
if ( ! class_exists( '\WisdmReportsLearndashBlockRegistry\WRLD_Report_Filters' ) ) {
	/**
	 * This class contains the Functionality required to register the Report Filters Block
	 */
	class WRLD_Report_Filters extends WRLD_Register_Block {
		/**
		 * Constructor.
		 *
		 * @param string $block_name           Set block name during construct.
		 * @param string $block_title          To be displayed in the WP-Admin.
		 * @param string $description          Description of the block.
		 * @param string $server_side_callback Function name, the child class must implement the method specified as this arguement.
		 * @param int    $api_version           Block API Version , default 2.
		 */
		public function __construct( $block_name = 'report-filters', $block_title = 'Report Tools', $description = 'Tools to filter and rearrange the reports', $server_side_callback = false, $api_version = 2 ) {
			$this->block_name  = $block_name ? $block_name : $this->block_name;
			$this->api_version = $api_version;
			$this->description = $description;
			$this->block_title = $block_title;
			// add_filter( 'wisdm_learndash_reports_front_end_script_report_filters', array( $this, 'localize_additional_data' ), 11, 1 );
			$this->wrld_register_block_assets();
			$this->wrld_register_block_type();
			$this->server_side_callback = 'server_side_render_function';
		}

		/**
		 * The function can be used to render the block contenet on the server side.
		 */
		public function server_side_render_function() {
			return 'Html if required';
		}

		/**
		 * This function can be used to localizes the additional data required for the parent block.
		 *
		 * @param array $data Default data to be localized in the script.
		 */
		public function localize_additional_data( $previous_data ) {
			$data = get_transient( 'wrld_localized_data_' . get_current_user_id() );
			if ( false === $data || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
				$data['ld_api_settings']   = get_option( 'learndash_settings_rest_api', array() );
				$data['course_categories'] = array();
				$data['course_groups']     = array();
				$data['exclude_courses']   = get_option( 'exclude_courses', '' );
				$qre_request_params        = array();

				if ( $data ) {
					$course_categories = get_terms( 'ld_course_category', array( 'hide_empty' => false ) );
					if ( ! empty( $course_categories ) && ! is_wp_error( $course_categories ) ) {
						foreach ( $course_categories as $category ) {
							$data['course_categories'][] = array(
								'value' => $category->term_id,
								'label' => $category->name,
								'slug'  => $category->slug,
							);
						}
					}
					// Groups.
					$current_user = wp_get_current_user();
					if ( empty( $current_user ) || 0 == $current_user->ID ) {
						return $data;
					}

					$group_ids         = array();
					$groups_query_args = array(
						'post_type'      => 'groups',
						'posts_per_page' => -1,
					);

					if ( ! in_array( 'administrator', $current_user->roles ) ) {
						$group_ids = learndash_get_administrators_group_ids( $current_user->ID, true );
					}

					if ( ! empty( $group_ids ) ) {
						$groups_query_args = array(
							'post_type'      => 'groups',
							'post__in'       => $group_ids,
							'posts_per_page' => -1,
						);
					}

					if ( ! in_array( 'administrator', $current_user->roles ) && empty( $group_ids ) ) {
						$groups_query = array();
					} else {
						$groups_query = new \WP_Query( $groups_query_args );
					}
					if ( isset( $groups_query->posts ) && ! empty( $groups_query->posts ) ) {
						foreach ( $groups_query->posts as $post ) {
							$group_courses = learndash_group_enrolled_courses( $post->ID, true );
							$group_users   = self::get_ld_group_users( $post->ID );

							$data['course_groups'][] = array(
								'value'            => $post->ID,
								'label'            => $post->post_title,
								'courses_enrolled' => $group_courses,
								'group_users'      => $group_users,
							);
						}
					}

					$data['courses'] = array();
					$data['quizes']  = array();

					$course_query_args = array(
						'post_type'   => 'sfwd-courses',
						'post_status' => 'publish',
						'nopaging'    => true,
						'orderby'     => 'title',
						'order'       => 'ASC',
					);
					$quiz_query_args   = array(
						'post_type'   => 'sfwd-quiz',
						'post_status' => 'publish',
						'nopaging'    => true,
						'orderby'     => 'title',
						'order'       => 'ASC',
					);

					if ( ! empty( $data['exclude_courses'] ) ) {
						$course_query_args['post__not_in'] = $data['exclude_courses'];
					}

					// Only fetch the self authored courses for instructor users.
					if ( in_array( 'wdm_instructor', $current_user->roles ) && ! in_array( 'administrator', $current_user->roles ) && ! in_array( 'group_leader', $current_user->roles ) ) {
						$author                          = array( $current_user->ID );
						$course_query_args['author__in'] = $author;
						$quiz_query_args['author__in']   = $author;
					}
					$query = new \WP_Query( $course_query_args );
					if ( ! empty( $query->posts ) ) {
						foreach ( $query->posts as $course ) {
							$data['courses'][] = array(
								'value' => $course->ID,
								'label' => $course->post_title,
							);
						}
					}

					$query = new \WP_Query( $quiz_query_args );
					if ( ! empty( $query->posts ) ) {
						foreach ( $query->posts as $quiz ) {
							$course_id        = get_post_meta( $quiz->ID, 'course_id', true );
							$data['quizes'][] = array(
								'value'     => $quiz->ID,
								'label'     => $quiz->post_title,
								'course_id' => $course_id,
							);
						}
					}
				}

				if ( isset( $_GET['ld_report_type'] ) && 'quiz-reports' == $_GET['ld_report_type'] ) {
					if ( isset( $_GET['report'] ) && '' != $_GET['report'] ) {
						$qre_request_params['report'] = sanitize_text_field( wp_unslash( $_GET['report'] ) );
					}
				}

				$qre_filters = array();
				if ( is_user_logged_in() ) {
					$qre_filters = get_user_meta( get_current_user_id(), 'qre_custom_reports_saved_query', true );
				}
				$data['qre_filters'] = $qre_filters;

				$data['qre_request_params'] = $qre_request_params;
				set_transient( 'wrld_localized_data_' . get_current_user_id(), $data, 1 * HOUR_IN_SECONDS );
			}
			$data = array_merge( $data, $previous_data );

			return $data;
		}

		/**
		 * This function fetches the users of the group specified by the parameter group_id,
		 * extracts their user Id, displayname & nicename and returns an array.
		 *
		 * @param int $group_id Id of the learndash group.
		 */
		public static function get_ld_group_users( $group_id = 0 ) {
			if ( empty( $group_id ) || $group_id < 1 ) {
				return array();
			}

			$group_users_data = array();
			$group_users = array();
			if ( get_option( 'migrated_group_access_data', false ) ) {
				$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $group_id ) ) );
			} else {
				$group_users      = learndash_get_groups_users( $group_id );
			}

			if ( ! empty( $group_users ) ) {
				foreach ( $group_users as $user ) {
					if ( ! is_object( $user ) ) {
						$user = get_userdata( $user );
					}
					$group_users_data[] = array(
						'id'            => $user->ID,
						'display_name'  => $user->display_name,
						'user_nicename' => $user->user_nicename,
					);
				}
			}
			return $group_users_data;
		}
	}
}
