<?php
/**
 * Plugin load class.
 *
 * @author   ThimPress
 * @package  LearnPress/bbPress/Classes
 * @version  3.0.4
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Addon_bbPress' ) ) {
	/**
	 * Class LP_Addon_bbPress.
	 *
	 * @since 3.0.0
	 */
	class LP_Addon_bbPress extends LP_Addon {

		/**
		 * @var bool
		 */
		protected $_start_forum = false;

		/**
		 * LP_Addon_bbPress constructor.
		 */
		public function __construct() {
			$this->version         = LP_ADDON_BBPRESS_VER;
			$this->require_version = LP_ADDON_BBPRESS_REQUIRE_VER;

			parent::__construct();
		}

		/**
		 * Define constants.
		 */
		protected function _define_constants() {
			define( 'LP_ADDON_BBPRESS_PATH', dirname( LP_ADDON_BBPRESS_FILE ) );
			define( 'LP_ADDON_BBPRESS_TEMPLATE', LP_ADDON_BBPRESS_PATH . '/templates/' );
		}

		/**
		 * Includes files.
		 */
		protected function _includes() {
			include_once 'functions.php';
		}

		/**
		 * Init hooks.
		 */
		protected function _init_hooks() {

			// delete course and delete forum action
			add_action( 'before_delete_post', array( $this, 'delete_post' ) );
			add_action( 'bbp_template_before_single_topic', array( $this, 'before_single' ) );
			add_action( 'bbp_template_before_single_forum', array( $this, 'before_single' ) );
			add_action( 'bbp_template_after_single_topic', array( $this, 'after_single' ) );
			add_action( 'bbp_template_after_single_forum', array( $this, 'after_single' ) );
			add_action( 'learn-press/single-course-summary', array( $this, 'forum_link' ), 0 );

			if ( version_compare( LEARNPRESS_VERSION, '4.0.0-beta-0', '>=' ) ) {
				// Add admin js
				add_action( 'admin_enqueue_scripts', array( $this, 'lp_bbpress_enqueue_admin_script' ) );

				// Metabox course tab.
				add_filter(
					'lp_course_data_settings_tabs',
					function ( $data ) {
						$data['course_bbpress'] = array(
							'label'    => esc_html__( 'Forum', 'learnpress-certificates' ),
							'icon'     => 'dashicons-list-view',
							'target'   => 'lp_bbpress_course_data',
							'priority' => 60,
						);

						return $data;
					}
				);
				add_action( 'lp_course_data_setting_tab_content', array( $this, 'course_bbpress_meta_box_v4' ) );
				// Save option metabox
				add_action( 'learnpress_save_lp_course_metabox', array( $this, 'save' ), 100, 1 );
			} else {
				add_filter( 'learn-press/admin-course-tabs', array( $this, 'add_course_tab' ) );
				// save course action
				add_action( 'save_post', array( $this, 'save_post' ) );
			}

		}

		/**
		 * Add bbPress tab in admin course.
		 *
		 * @param $tabs
		 *
		 * @return array
		 */
		public function add_course_tab( $tabs ) {
			$forum = array( 'course_bbpress' => new RW_Meta_Box( self::course_bbpress_meta_box() ) );

			return array_merge( $tabs, $forum );
		}

		/**
		 * BBPress course meta box.
		 *
		 * @return mixed
		 */
		public static function course_bbpress_meta_box() {

			$args = array( 'post_type' => 'forum', 'post_status' => 'publish', 'posts_per_page' => - 1, );

			$forums = new WP_Query( $args );

			$options     = array();
			$options[''] = __( 'Create New', 'learnpress-bbpress' );
			if ( $forums->have_posts() ) {
				while ( $forums->have_posts() ) {
					$forums->the_post();

					$course_id = learn_press_bbp_get_course( get_the_ID() );
					$post_id   = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : '';

// 					if ( ! $course_id || $course_id == $post_id || LP_COURSE_CPT == get_post_type()) {
					$options[ get_the_ID() ] = get_the_title();
// 					}
				}
				wp_reset_postdata();

			}

			$meta_box = array(
				'id'       => 'course_bbpress',
				'title'    => __( 'Forum', 'learnpress-bbpress' ),
				'pages'    => array( LP_COURSE_CPT ),
				'priority' => 'high',
				'icon'     => 'dashicons-list-view',
				'fields'   => array(
					array(
						'name' => __( 'Enable', 'learnpress-bbpress' ),
						'id'   => '_lp_bbpress_forum_enable',
						'type' => 'yes-no',
						'desc' => __( 'Enable bbPress forum for this course.', 'learnpress-bbpress' ),
						'std'  => 'no'
					),
					array(
						'name'       => __( 'Course Forum', 'learnpress-bbpress' ),
						'id'         => '_lp_course_forum',
						'type'       => 'select',
						'desc'       => __( 'Select forum of this course, choose Create New to create new forum for course, uncheck Enable option to disable.',
							'learnpress-bbpress' ),
						'options'    => $options,
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => '_lp_bbpress_forum_enable',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
					array(
						'name'       => __( 'Restrict User', 'learnpress-bbpress' ),
						'id'         => '_lp_bbpress_forum_enrolled_user',
						'type'       => 'yes-no',
						'desc'       => __( 'Only user(s) enrolled course can access this forum.',
							'learnpress-bbpress' ),
						'std'        => 'no',
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => '_lp_bbpress_forum_enable',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					)
				)
			);

			return apply_filters( 'learn-press/course-bbpress/settings-meta-box-args', $meta_box );
		}

		/**
		 * Save post.
		 *
		 * @param $post_id
		 */
		public function save_post( $post_id ) {
			if ( get_post_type( $post_id ) != LP_COURSE_CPT || wp_is_post_revision( $post_id ) ) {
				return;
			}

			if ( get_post_meta( $post_id, '_lp_bbpress_forum_enable', true ) != 'yes' ) {
				return;
			}

			$course = get_post( $post_id );

			$forum_id = get_post_meta( $post_id, '_lp_course_forum', true );

			if ( ! $forum_id ) {
				$forum = array(
					'post_title'   => $course->post_title,
					'post_content' => '',
					'post_author'  => $course->post_author,
				);

				$forum_id = bbp_insert_forum( $forum, array() );
				update_post_meta( $post_id, '_lp_course_forum', $forum_id );
			}
		}


		/**
		 * BBPress course meta box.
		 *
		 * @return mixed
		 */
		public static function course_bbpress_meta_box_v4() {
			$args = array( 'post_type' => 'forum', 'post_status' => 'publish', 'numberposts' => -1, );
			$options     = array();
			$options[''] = __( 'Create New', 'learnpress-bbpress' );
			$forums_posts = get_posts( $args );
			if(!empty($forums_posts)){
				foreach ($forums_posts as $forums_post){
					$course_id = learn_press_bbp_get_course( get_the_ID() );
					$post_id   = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : '';

					if ( ! $course_id || $course_id == $post_id || LP_COURSE_CPT == get_post_type() ) {
						$options[ $forums_post->ID ] = $forums_post->post_title;
					}
				}
			}
			echo '<div id="lp_bbpress_course_data" class="lp-meta-box-course-panels">';
			$class              = 'off';
			$old_forum_enable   = '';
			$value_forum_enable = '';
			if ( isset( $post_id ) && $post_id != '' ) {
				$old_forum_enable   = get_post_meta( $post_id, '_lp_bbpress_forum_enable', true );
				$value_forum_enable = get_post_meta( $post_id, '_lp_course_forum', false );
				if ( $old_forum_enable == 'yes' ) {
					$class = 'on';
				} else {
					$class = 'off';
				}
			}
			//Show option
			lp_meta_box_checkbox_field(
				array(
					'id'          => '_lp_bbpress_forum_enable',
					'label'       => esc_html__( 'Enable', 'learnpress-bbpress' ),
					'description' => esc_html__(
						'Enable bbPress forum for this course.',
						'learnpress-bbpress'
					),
					'default'     => 'no',
				)
			);
			echo '<div class="lp_bbpress_course__wrapper ' . $class . '">';

			lp_meta_box_select_field(
				array(
					'id'          => '_lp_course_forum',
					'label'       => esc_html__( 'Course Forum', 'learnpress-bbpress' ),
					'description' => esc_html__( 'Select forum of this course, choose Create New to create new forum for course, uncheck Enable option to disable.',
						'learnpress-bbpress' ),
					'default'     => '',
					'options'     => $options,
					'value'       => $value_forum_enable
				)
			);

			lp_meta_box_checkbox_field(
				array(
					'id'          => '_lp_bbpress_forum_enrolled_user',
					'label'       => esc_html__( 'Restrict User', 'learnpress' ),
					'description' => esc_html__(
						'Only user(s) enrolled course can access this forum.',
						'learnpress-bbpress'
					),
					'default'     => 'no',
				)
			);

			echo '</div>';

			echo '</div>';

		}

		public static function save( $post_id ) {
			$opt_bbpress_enable       = ! empty( $_POST['_lp_bbpress_forum_enable'] ) ? 'yes' : 'no';
			$opt_bbpress_course_forum = isset( $_POST['_lp_course_forum'] ) ? wp_unslash( $_POST['_lp_course_forum'] ) : '';
			$opt_bbpress_restrictuser = ! empty( $_POST['_lp_bbpress_forum_enrolled_user'] ) ? 'yes' : 'no';

			update_post_meta( $post_id, '_lp_bbpress_forum_enable', $opt_bbpress_enable );
			update_post_meta( $post_id, '_lp_course_forum', $opt_bbpress_course_forum );
			update_post_meta( $post_id, '_lp_bbpress_forum_enrolled_user', $opt_bbpress_restrictuser );


			// Gom action

			// For update
			if ( get_post_meta( $post_id, '_lp_bbpress_forum_enable', true ) != 'yes' ) {
				return;
			}

			$course = get_post( $post_id );

			$forum_id = get_post_meta( $post_id, '_lp_course_forum', true );

			if ( ! $forum_id ) {
				$forum = array(
					'post_title'   => $course->post_title,
					'post_content' => '',
					'post_author'  => $course->post_author,
				);

				$forum_id = bbp_insert_forum( $forum, array() );
				update_post_meta( $post_id, '_lp_course_forum', $forum_id );
			}
		}

		/**
		 * Save post.
		 *
		 * @param $post_id
		 */

		/**
		 * Delete forum when delete parent course and disable forum for course when delete it's forum.
		 *
		 * @param $post_id
		 */
		public function delete_post( $post_id ) {

			$post_type = get_post_type( $post_id );

			switch ( $post_type ) {
				case LP_COURSE_CPT:
					$forum_id = get_post_meta( $post_id, '_lp_course_forum', true );

					if ( ! $forum_id ) {
						return;
					}

					wp_delete_post( $forum_id );
					break;

				case 'forum':
					$course_id = learn_press_bbp_get_course( $post_id );

					update_post_meta( $course_id, '_lp_bbpress_forum_enable', 'no' );
					break;
				default:
					break;
			}
		}

		/**
		 * Forum link in single course page.
		 */
		public function forum_link() {

			$course = LP_Global::course();

			if ( ! $course ) {
				return;
			}

			$forum_id = get_post_meta( $course->get_id(), '_lp_course_forum', true );

			if ( ! $forum_id ) {
				return;
			}

			if ( ! in_array( get_post_type( $forum_id ), array( 'topic', 'forum' ) ) ) {
				return;
			}

			if ( ! $this->can_access_forum( $forum_id, get_post_type( $forum_id ) ) ) {
				return;
			}

			if ( get_post_meta( $course->get_id(), '_lp_bbpress_forum_enable', true ) !== 'yes' ) {
				return;
			}

			learn_press_get_template( 'forum-link.php', array( 'forum_id' => $forum_id ),
				learn_press_template_path() . '/addons/bbpress/', LP_ADDON_BBPRESS_TEMPLATE );
		}

		/**
		 * Check allow user access forum.
		 *
		 * @param $id
		 * @param $type
		 *
		 * @return bool
		 */
		private function can_access_forum( $id, $type ) {

			// invalid forum
			if ( ! $id ) {
				return false;
			}

			// admin, moderator, key master always can access forum
			if ( current_user_can( 'manage_options' ) || current_user_can( 'bbp_moderator' ) || current_user_can( 'bbp_keymaster' ) ) {
				return true;
			}

			if ( $type == 'forum' ) {
				$forum_id = $id;
			} elseif ( $type == 'topic' ) {
				$forum_id = get_post_meta( $id, '_bbp_forum_id', true );
			} else {
				return false;
			}

			$forum = get_post( $forum_id );

			// restrict access bases on ancestor forums
			if ( $ancestor_forums = $forum->ancestors ) {
				foreach ( $ancestor_forums as $ancestor_forum_id ) {
					if ( ! $this->_restrict_access( $ancestor_forum_id ) ) {
						return false;
					}
				}
				$can_access = true;
			}

			$can_access = $this->_restrict_access( $forum_id );

			return $can_access;
		}

		/**
		 * Check forum accessibility.
		 *
		 * @param $forum_id
		 *
		 * @return bool
		 */
		private function _restrict_access( $forum_id ) {
			$course_id = learn_press_bbp_get_course( $forum_id );

			// normal publish forum which has no connecting with any courses
			if ( ! $course_id ) {
				return true;
			}

			if ( LP_COURSE_CPT !== get_post_type( $course_id ) ) {
				return;
			}

			$course = learn_press_get_course( $course_id );

			$required_enroll = $course->is_required_enroll();

			// allow access not require enroll course's forum
			if ( ! $required_enroll ) {
				return true;
			}

			if ( $this->is_public_forum( $course_id ) ) {
				return true;
			}

			$user = learn_press_get_current_user();

			if ( ! $user->get_id() ) {
				return false;
			}

			// allow post author access
			if ( $user->get_id() == get_post_field( 'post_author', $course_id ) ) {
				return true;
			}

			// restrict user not enroll
			$user_course_data = $user->get_course_data( $course_id );
			$status           = $user_course_data->get_data( 'status' );
			if ( in_array( $status, array( 'enrolled', 'finished' ) ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check forum public.
		 *
		 * @param $course_id
		 *
		 * @return bool
		 */
		public function is_public_forum( $course_id ) {
			$restrict = get_post_meta( $course_id, '_lp_bbpress_forum_enrolled_user', true );

			if ( is_null( $restrict ) || ( $restrict === false ) || ( $restrict == '' ) || ( $restrict == 'no' ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Before single topic and single forum.
		 */
		public function before_single() {
			global $post;
			if ( ! $this->can_access_forum( $post->ID, $post->post_type ) ) {
				$this->_start_forum = true;
				ob_start();
			}
		}

		/**
		 * After single topic and single forum.
		 */
		public function after_single() {
			global $post;
			if ( $this->_start_forum ) {
				ob_end_clean(); ?>
				<div id="restrict-access-form-message" style="clear: both;">
					<p><?php _e( 'You have to enroll the respective course!', 'learnpress-bbpress' ); ?></p>
					<?php if ( $course_id = learn_press_bbp_get_course( $post->ID ) ) { ?>
						<p><?php _e( 'Go back to ', 'learnpress-bbpress' ); ?>
							<a href="<?php echo get_permalink( $course_id ); ?>"> <?php echo get_the_title( $course_id ); ?></a>
						</p>
					<?php } ?>
				</div>
				<?php
			}
		}

		/**
		 * Enqueue a script in the WordPress admin on edit.php.
		 *
		 * @param int $hook Hook suffix for the current admin page.
		 */
		public function lp_bbpress_enqueue_admin_script() {
			wp_enqueue_script( 'lp_bbpress_script', plugin_dir_url( LP_ADDON_BBPRESS_FILE ) . 'assets/js/admin.js',
				array(), '1.0' );
			wp_enqueue_style( 'lp_bbpress_style', plugins_url( '/assets/css/admin.css', LP_ADDON_BBPRESS_FILE ) );
		}
	}
}
