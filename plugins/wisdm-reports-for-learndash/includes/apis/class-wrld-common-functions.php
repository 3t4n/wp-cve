<?php
/**
 * This file contains a class 'WRLD_Common_Functions' which contains common menthods used.
 *
 * @package learndash-reports-by-wisdmlabs
 */

if ( ! class_exists( 'WRLD_Common_Functions' ) ) {
	/**
	 * Class that sets up all the LearnDash endpoints
	 *
	 * @author     WisdmLabs
	 * @since      1.0.0
	 * @subpackage LearnDash API
	 */
	class WRLD_Common_Functions {

		/**
		 * This function fetches the users of the learndash group specified by the parameter group_id,
		 * extracts their user Ids returns an array.
		 *
		 * @param int $group_id Id of the learndash group.
		 * @return array $group_user_ids array of user ids.
		 */
		public static function get_ld_group_user_ids( $group_id = 0 ) {
			if ( empty( $group_id ) || $group_id < 1 ) {
				return array();
			}

			$group_user_ids = array();
			$group_users    = learndash_get_groups_users( $group_id );

			if ( ! empty( $group_users ) ) {
				foreach ( $group_users as $user ) {
					$group_user_ids[] = $user->ID;
				}
			}
			return $group_user_ids;
		}


		/**
		 * The function gets a $ date in format "Y-m-d H:i:s".
		 * and tells wether the date is todays or not.
		 *
		 * @param string $date - in Y-m-d H:i:s format
		 * @return bool whether the its a todays date.
		 */
		public static function is_today( $date ) {
			if ( ! empty( $date ) ) {
				$today = new \DateTime(); // This object represents current date/time
				$today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison
				$match_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $date );

				if ( empty( $match_date ) ) {
					return false;
				}

				$match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison
				$diff      = $today->diff( $match_date );
				$diff_days = (int) $diff->format( '%R%a' );

				if ( 0 === $diff_days ) {
					return true;
				}
			}
			return false;
		}


		/**
		 * This function when passed with the request data ($_POST/$_GET)
		 * picks up the report related request data & returns an associative array
		 * of request parameters.
		 *
		 * @param array $request
		 * @return array $params an associative array of request parameteres for reporting plugin.
		 */
		public static function get_request_params( $request ) {
			$request_params = array();

			$reporting_filters = array(
				'start_date',
				'end_date',
				'category',
				'group',
				'course',
				'lesson',
				'topic',
				'learner',
				'duration',
				'datapoint',
				'page',
				'user_id',
				'course_id',
				'quiz_id',
				'rtype',
				'timeperiod',
				'sort',
				'upper_limit',
				'lower_limit',
				'disable_cache'
			);

			foreach ( $reporting_filters as $filter ) {
				$request_params[ $filter ] = ! empty( $request[ $filter ] ) && 'null' != $request[ $filter ] ? sanitize_text_field( $request[ $filter ] ) : '';
			}

			return $request_params;
		}

		/**
		 * This function when passed with the start date & end date timestamps
		 * returns the duration i.e array of start date & end date along with the
		 * past duration start date & end dates.
		 *
		 * @param string $start_date
		 * @param string $end_date
		 * @return array $duration_data array('start_date','end_date','prev_start_date', 'prev_end_date')
		 */
		public static function get_duration_data( $start_date, $end_date, $format = 'Y-m-d H:i:s' ) {
			$duration_data = array();
			// if start date or end dates are not specified consider the duration of past 1 month.
			if ( empty( $start_date ) || empty( $end_date ) ) {
				$start_date = strtotime( '-1 month' );
				$end_date   = current_time( 'timestamp' );
			}

			$duration_data['start_date'] = date( $format , (int) $start_date );// phpcs:ignore.
			$duration_data['end_date']   = date( $format , (int) $end_date );// phpcs:ignore.
			$duration_data['end_date']   = self::is_today( $duration_data['end_date'] ) ? date( $format, current_time( 'timestamp' ) ) : $duration_data['end_date']; // phpcs:ignore.

			// Calculate previous similar period date range.
			$start_date_obj = new DateTime( $duration_data['start_date'] );
			$end_date_obj   = new DateTime( $duration_data['end_date'] );
			$date_range     = $end_date_obj->diff( $start_date_obj )->format( '%a' );
			$date_range     = ++$date_range;// because difference between 2021-11-29 18:30:00 and 2021-11-30 18:29:59 is 1 day not 0.

			$duration_data['prev_start_date'] = date( $format, strtotime( '-' . $date_range . ' days', strtotime( $duration_data['start_date'] ) ) );// phpcs:ignore.
			$duration_data['prev_end_date']   = date( $format, strtotime( '-' . $date_range . ' days', strtotime( $duration_data['end_date'] ) ) );// phpcs:ignore.

			return $duration_data;
		}


		/**
		 * This function when called gets the user roles for currently logged in user,
		 * categorize the roles in 'Administrator', 'Group Leader', 'Instructor' & 'Learner'
		 * & return the category in order to define data accesibility.
		 *
		 * @return string administrator | group Leader | instructor | learner
		 */
		public static function get_current_user_role_access() {
			$user  = wp_get_current_user(); // getting & setting the current user
			$roles = ! empty( $user ) ? (array) $user->roles : array();
			$role  = false;
			if ( ! empty( $roles ) ) {
				if ( in_array( 'administrator', $roles, true ) ) {
					$role = 'administrator';
				} elseif ( in_array( 'group_leader', $roles, true ) && in_array( 'wdm_instructor', $roles, true ) ) {
					$role = 'group_leader_instructor';
				} elseif ( in_array( 'group_leader', $roles, true ) ) {
					$role = 'group_leader';
				} elseif ( in_array( 'wdm_instructor', $roles, true ) ) {
					$role = 'instructor';
				} else {
					$role = 'learner';
				}
			}

			return apply_filters( 'wrld_filter_accessibility_user_role', $role );
		}

		/**
		 * Returns the Ids of the groups managed by the user specified by the
		 * user Id
		 *
		 * @param int $user_id
		 * @return array $group_ids
		 */
		public static function get_managed_group_ids( $user_id ) {
			$group_ids = array();
			if ( ! empty( $user_id ) ) {
				$group_ids = learndash_get_administrators_group_ids( $user_id );
			}
			return $group_ids;
		}


		/**
		 * Finds the users enrolled in each of the group Ids  & returns the list of unique
		 * user Ids from results.
		 *
		 * @param array $group_ids array of learndash course group Ids
		 * @return array $users array of users combinely present in group Ids mentioned.
		 */
		public static function get_users_enrolled_in_groups( $group_ids ) {
			$user_ids = array();
			if ( ! empty( $group_ids ) ) {
				foreach ( $group_ids as $group_id ) {
					if ( get_option( 'migrated_group_access_data', false ) ) {
						$user_ids = array_unique( array_merge( $user_ids, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $group_id ) ) );
					} else {
						$user_ids = array_unique( array_merge( $user_ids, self::get_ld_group_user_ids( $group_id ) ) );
					}
				}
			}
			return $user_ids;
		}


		/**
		 * Finds the courses in each of the group Ids  & returns the list of unique
		 * course Ids from results.
		 *
		 * @param array $group_ids array of learndash course group Ids
		 * @return array $courses array of course Ids present in group Ids mentioned.
		 */
		public static function get_list_of_courses_in_groups( $group_ids ) {
			$course_ids = array();
			if ( ! empty( $group_ids ) ) {
				foreach ( $group_ids as $group_id ) {
					$course_ids = array_unique( array_merge( $course_ids, learndash_group_enrolled_courses( $group_id ) ) );
				}
			}
			return $course_ids;
		}

		/**
		 * Finds the users enrolled in each of the course Ids  & returns the list of unique
		 * user Ids from results.
		 *
		 * @param array $course_ids array of learndash course Ids
		 * @return array $enrolled_user_ids array of user Ids enrolled in all the courses.
		 */
		public static function get_list_of_users_enrolled_in_courses( $course_ids = array() ) {
			$enrolled_user_ids = array();
			if ( ! empty( $course_ids ) ) {
				foreach ( $course_ids as $course_id ) {
					$course_price_type = learndash_get_course_meta_setting( $course_id, 'course_price_type' );
					if ( 'open' === $course_price_type ) {
						continue;
					}
					$course_learners = get_transient( 'wrld_course_learners_data_' . $course_id );
					if ( false === $course_learners || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || ( isset( $_GET['disable_cache'] ) && ! empty( $_GET['disable_cache'] ) ) ) {
						if ( get_option( 'migrated_course_access_data', false ) ) {
							$course_learners = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course_id );
						} else {
							$course_learners   = learndash_get_users_for_course( $course_id, array(), false );
						}
						$course_learners   = is_array( $course_learners ) ? $course_learners : $course_learners->get_results();
						delete_transient( 'wrld_course_learners_data_' . $course_id );
						set_transient( 'wrld_course_learners_data_' . $course_id, $course_learners, 1 * HOUR_IN_SECONDS );

					}
					$enrolled_user_ids = array_unique( array_merge( $enrolled_user_ids, $course_learners ) );
				}
			}
			return $enrolled_user_ids;
		}


		/**
		 * Based on the user & user role for accessing reports provided this function returns the lst of course ids whose report can be
		 * accessible by the currently logged in user or the user specified with the $user_id
		 *
		 * @param int    $user_id Id of the user for which we require to get the list of accessible users.
		 * @param string $user_role_for_access User role according to which we need to find the accesibility default:student.
		 * @param string $report_name name of the report for which we need accesssible users (Used while applying filter)
		 * @return array $accessible_courses arry of course ids for which the specified user can access the data.
		 */
		public static function get_accessible_courses_for_the_user( $user_id, $user_role_for_access = 'student', $report_name = null ) {

			if ( empty( $user_id ) ) {
				$user_id = get_current_user_id();
			}
	
			$accessible_courses = get_transient( 'wrld_accessible_courses_data_' . $user_id );
			if ( false === $accessible_courses || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || ( isset( $_GET['disable_cache'] ) && ! empty( $_GET['disable_cache'] ) ) ) {
				$accessible_courses = array();
				switch ( $user_role_for_access ) {
					case 'administrator':
						$accessible_courses = -1;
						break;
					case 'group_leader_instructor':
						// The user is both a group leader & an Instructor hence Can access data of group users and courses users of the instructor.
						$groups_managed     = self::get_managed_group_ids( $user_id );
						$accessible_courses = self::get_list_of_courses_in_groups( $groups_managed );
						$accessible_courses = function_exists( 'ir_get_instructor_complete_course_list' ) ? array_unique( array_merge( $accessible_courses, ir_get_instructor_complete_course_list( $user_id ) ) ) : $accessible_courses;
						break;
					case 'group_leader':
						// Can access data of limited users and courses belongs to the groups managed by user
						$groups_managed     = self::get_managed_group_ids( $user_id );
						$accessible_courses = self::get_list_of_courses_in_groups( $groups_managed );
						break;
					case 'instructor':
						$accessible_courses = function_exists( 'ir_get_instructor_complete_course_list' ) ? ir_get_instructor_complete_course_list( $user_id ) : learndash_user_get_enrolled_courses( $user_id, array(), false );
						break;
					case 'student':
						$accessible_courses = learndash_user_get_enrolled_courses( $user_id, array(), false );
						break;
					default:
						// No access to the data
						break;
				}
				$accessible_courses = apply_filters( 'wrld_course_accessibility_for_reports', $accessible_courses, $user_id, $user_role_for_access, $report_name );
				delete_transient( 'wrld_accessible_courses_data_' . $user_id );
				set_transient( 'wrld_accessible_courses_data_' . $user_id, $accessible_courses, 1 * HOUR_IN_SECONDS );
			}
			return $accessible_courses;
		}


		/**
		 * Based on the user & user role for accessing reports provided this function returns the lst of users whose report can be
		 * accessible by the currently logged in user or the user specified with the $user_id
		 *
		 * @param int    $user_id Id of the user for which we require to get the list of accessible users.
		 * @param string $user_role_for_access User role according to which we need to find the accesibility default:student.
		 * @param string $report_name name of the report for which we need accesssible users (Used while applying filter)
		 * @return array $accessible_users arry of user Ids for which the specified user can access the data.
		 */
		public static function get_accessible_users_for_the_user( $user_id, $user_role_for_access = 'student', $report_name = null ) {

			if ( empty( $user_id ) ) {
				$user_id = get_current_user_id();
			}

			$accessible_users = get_transient( 'wrld_accessible_users_data_' . $user_id );
			if ( false === $accessible_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || ( isset( $_GET['disable_cache'] ) && ! empty( $_GET['disable_cache'] ) ) ) {
				$accessible_users = array();
				switch ( $user_role_for_access ) {
					case 'administrator':
						$accessible_users = -1;
						break;
					case 'group_leader_instructor':
						// The user is both a group leader & an Instructor hence Can access data of group users and courses users of the instructor.
						$groups_managed     = self::get_managed_group_ids( get_current_user_id() );
						$accessible_users   = self::get_users_enrolled_in_groups( $groups_managed );
						$instructor_courses = function_exists( 'ir_get_instructor_complete_course_list' ) ? ir_get_instructor_complete_course_list( get_current_user_id() ) : array();
						$accessible_users   = array_unique(
							array_merge(
								$accessible_users,
								self::get_list_of_users_enrolled_in_courses( $instructor_courses )
							)
						);
						break;
					case 'group_leader':
						// Can access data of limited users and courses belongs to the groups managed by user
						$groups_managed   = self::get_managed_group_ids( get_current_user_id() );
						$accessible_users = self::get_users_enrolled_in_groups( $groups_managed );
						break;
					case 'instructor':
						$accessible_courses = function_exists( 'ir_get_instructor_complete_course_list' ) ? ir_get_instructor_complete_course_list( get_current_user_id() ) : learndash_user_get_enrolled_courses( $user_id, array(), false );
						$accessible_users   = self::get_list_of_users_enrolled_in_courses( $accessible_courses );
						break;
					case 'student':
						$accessible_users = array( $user_id );
						break;
					default:
						// No access to the data
						break;
				}
				$accessible_users = apply_filters( 'wrld_user_accessibility_for_reports', $accessible_users, $user_id, $user_role_for_access, $report_name );
				delete_transient( 'wrld_accessible_users_data_' . $user_id );
				set_transient( 'wrld_accessible_users_data_' . $user_id, $accessible_users, 1 * HOUR_IN_SECONDS );
			}
			return $accessible_users;
		}

		public static function get_values_for_request_params( $request_data = array() ) {
			$request_data_values = array();
			if ( ! empty( $request_data ) ) {
				foreach ( $request_data as $key => $value ) {
					if ( ( 'course' == $key || 'group' == $key || 'lesson' == $key || 'topic' == $key ) && '' != $value ) {
						$request_data_values[ $key ] = str_replace( '&#8211;', '-', get_the_title( $value ) );
					} elseif ( 'category' == $key && '' != $value ) {
						$category                    = get_term_by( 'id', $value, 'ld_course_category' );
						$request_data_values[ $key ] = ! empty( $category ) ? $category->name : '';
					} elseif ( 'learner' == $key ) {
						$user_data                   = get_userdata( $value );
						$request_data_values[ $key ] = ! empty( $user_data ) ? $user_data->display_name : '';
					} else {
						$request_data_values[ $key ] = $value;
					}
				}
			}

			return apply_filters( 'wrld_filter_values_for_request_params', $request_data_values, $request_data );
		}

		public static function wrld_dropdown_users( $args = '' ) {
			$defaults = array(
				'show_option_all'         => '',
				'show_option_none'        => '',
				'hide_if_only_one_author' => '',
				'orderby'                 => 'display_name',
				'order'                   => 'ASC',
				'include'                 => '',
				'exclude'                 => '',
				'multi'                   => 0,
				'show'                    => 'display_name',
				'echo'                    => 1,
				'selected'                => 0,
				'name'                    => 'user',
				'class'                   => '',
				'id'                      => '',
				'blog_id'                 => get_current_blog_id(),
				'who'                     => '',
				'include_selected'        => false,
				'option_none_value'       => -1,
				'role'                    => '',
				'role__in'                => array(),
				'role__not_in'            => array(),
				'capability'              => '',
				'capability__in'          => array(),
				'capability__not_in'      => array(),
				'disabled'                => 0,
				'multiselect'             => array(),
				'number'                  => -1,
				'paged'                   => 1,
			);

			$defaults['selected'] = is_author() ? get_query_var( 'author' ) : 0;

			$parsed_args = wp_parse_args( $args, $defaults );

			$query_args = wp_array_slice_assoc(
				$parsed_args,
				array(
					'blog_id',
					'include',
					'exclude',
					'orderby',
					'order',
					'who',
					'role',
					'role__in',
					'role__not_in',
					'capability',
					'capability__in',
					'capability__not_in',
					'number',
					'paged',
				)
			);

			$fields = array( 'ID', 'user_login' );

			$show = ! empty( $parsed_args['show'] ) ? $parsed_args['show'] : 'display_name';
			if ( 'display_name_with_login' === $show ) {
				$fields[] = 'display_name';
			} else {
				$fields[] = $show;
			}

			$query_args['fields'] = $fields;

			$show_option_all   = $parsed_args['show_option_all'];
			$show_option_none  = $parsed_args['show_option_none'];
			$option_none_value = $parsed_args['option_none_value'];

			/**
			 * Filters the query arguments for the list of users in the dropdown.
			 *
			 * @param array $query_args  The query arguments for get_users().
			 * @param array $parsed_args The arguments passed to wrld_dropdown_users() combined with the defaults.
			 */
			$query_args = apply_filters( 'wrld_dropdown_users_args', $query_args, $parsed_args );

			$users = get_users( $query_args );

			$output = '';
			if ( ! empty( $users ) && ( empty( $parsed_args['hide_if_only_one_author'] ) || count( $users ) > 1 ) ) {
				$name = esc_attr( $parsed_args['name'] );
				if ( $parsed_args['multi'] && ! $parsed_args['id'] ) {
					$id = '';
				} else {
					$id = $parsed_args['id'] ? " id='" . esc_attr( $parsed_args['id'] ) . "'" : " id='$name'";
				}
				$disabled = $parsed_args['disabled'] ? "disabled='disabled'" : '';
				$output   = "<select name='{$name}'{$id} class='" . $parsed_args['class'] . "'" . $disabled . ">\n";

				if ( $show_option_all ) {
					$output .= "\t<option value='0'>$show_option_all</option>\n";
				}

				if ( $show_option_none ) {
					$_selected = selected( $option_none_value, $parsed_args['selected'], false );
					$output   .= "\t<option value='" . esc_attr( $option_none_value ) . "'$_selected>$show_option_none</option>\n";
				}

				if ( $parsed_args['include_selected'] && ( $parsed_args['selected'] > 0 || ! empty( $parsed_args['multiselect'] ) ) ) {
					$found_selected          = false;
					$parsed_args['selected'] = (int) $parsed_args['selected'];
					$found                   = array();
					foreach ( (array) $users as $user ) {
						$user->ID = (int) $user->ID;
						if ( $user->ID === $parsed_args['selected'] ) {
							$found_selected = true;
						}
						if ( in_array( $user->ID, $parsed_args['multiselect'] ) ) {
							$found[] = $user->ID;
						}
					}
					if ( ! $found_selected ) {
						if ( $parsed_args['selected'] > 0 ) {
							$selected_user = get_userdata( $parsed_args['selected'] );
							if ( $selected_user ) {
								$users[] = $selected_user;
							}
						}
					}
					if ( ! empty( $parsed_args['multiselect'] ) || ( count( $found ) < count( $parsed_args['multiselect'] ) ) ) {
						$not_found = array_diff( $parsed_args['multiselect'], $found );
						foreach ( $not_found as $user_id ) {
							$users[] = get_userdata( $user_id );
						}
					}
				}
				foreach ( (array) $users as $user ) {
					if ( 'display_name_with_login' === $show ) {
						/* translators: 1: User's display name, 2: User login. */
						$display = sprintf( _x( '%1$s (%2$s)', 'user dropdown' ), $user->display_name, $user->user_login );
					} elseif ( ! empty( $user->$show ) ) {
						$display = $user->$show;
					} else {
						$display = '(' . $user->user_login . ')';
					}

					$_selected = selected( $user->ID, $parsed_args['selected'], false );
					$selected  = '';
					if ( in_array( $user->ID, $parsed_args['multiselect'] ) ) {
						$selected = 'selected="selected"';
					}
					$output .= "\t<option value='$user->ID'$_selected $selected>" . esc_html( $display ) . "</option>\n";
				}

				$output .= '</select>';
			}

			/**
			 * Filters the wrld_dropdown_users() HTML output.
			 *
			 * @param string $output HTML output generated by wrld_dropdown_users().
			 */
			$html = apply_filters( 'wrld_dropdown_users', $output );

			if ( $parsed_args['echo'] ) {
				echo $html;// phpcs:ignore
			}
			return $html;
		}

		public static function wrld_get_report_filters_data() {
			$data = get_transient( 'wrld_localized_data_' . get_current_user_id() );
			if ( false === $data || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || ( isset( $_GET['disable_cache'] ) && ! empty( $_GET['disable_cache'] ) ) ) {
				$data['ld_api_settings']   = get_option( 'learndash_settings_rest_api', array() );
				$data['course_categories'] = array();
				$data['course_groups']     = array();
				$data['exclude_courses']   = get_option( 'exclude_courses', '' );
				$qre_request_params        = array();
				do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
				if ( $data ) {
					$course_categories = get_terms( 'ld_course_category', array( 'hide_empty' => true ) );
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
						'suppress_filters' => 0,
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
					$group_courses_all = array();
					if ( isset( $groups_query->posts ) && ! empty( $groups_query->posts ) ) {
						foreach ( $groups_query->posts as $post ) {
							$group_courses = learndash_group_enrolled_courses( $post->ID, true );
							$group_users   = \WisdmReportsLearndashBlockRegistry\WRLD_Report_Filters::get_ld_group_users( $post->ID );
							$group_courses_all = array_merge($group_courses_all, $group_courses);
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
					if ( ! in_array( 'wdm_instructor', $current_user->roles ) && in_array( 'administrator', $current_user->roles ) || in_array( 'group_leader', $current_user->roles ) ) {
						// $author                          = array( $current_user->ID );
						// $course_query_args['author__in'] = $author;
						// $quiz_query_args['author__in']   = $author;
						if ( in_array( 'group_leader', $current_user->roles ) ) {
							foreach ($group_courses_all as $course_id) {
								$data['courses'][] = array(
									'value' => $course_id,
									'label' => get_the_title( $course_id ),
								);
								$quiz_query_args['meta_query'] = array(
									array(
										'key' => 'course_id',
										'value' => $course_id,
									)
								);
								$query = new \WP_Query( $quiz_query_args );
								if ( ! empty( $query->posts ) ) {
									foreach ( $query->posts as $quiz ) {
										// $course_id        = get_post_meta( $quiz->ID, 'course_id', true );
										$data['quizes'][] = array(
											'value'     => $quiz->ID,
											'label'     => $quiz->post_title,
											'course_id' => $course_id,
										);
									}
								}
							}
						} else {
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

					} else {
						$courses = ir_get_instructor_complete_course_list( $current_user->ID );
						if ( ! empty( $courses ) ) {
							foreach ( $courses as $course_id ) {
								$data['courses'][] = array(
									'value' => $course_id,
									'label' => get_the_title( $course_id ),
								);
								$quiz_query_args['meta_query'] = array(
									array(
										'key' => 'course_id',
										'value' => $course_id,
									)
								);
								$query = new \WP_Query( $quiz_query_args );
								if ( ! empty( $query->posts ) ) {
									foreach ( $query->posts as $quiz ) {
										// $course_id        = get_post_meta( $quiz->ID, 'course_id', true );
										$data['quizes'][] = array(
											'value'     => $quiz->ID,
											'label'     => $quiz->post_title,
											'course_id' => $course_id,
										);
									}
								}
							}
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
				delete_transient( 'wrld_localized_data_' . get_current_user_id() );
				set_transient( 'wrld_localized_data_' . get_current_user_id(), $data, 1 * HOUR_IN_SECONDS );
			}
			return new \WP_REST_Response(
				array(
					'wisdm_learndash_reports_front_end_script_report_filters' => $data,
				),
				200
			);
		}
	}
}
