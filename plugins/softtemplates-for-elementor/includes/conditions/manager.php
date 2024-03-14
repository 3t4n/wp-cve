<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Conditions_Manager' ) ) {

	/**
	 * Define Soft_template_Core_Conditions_Manager class
	 */
	class Soft_template_Core_Conditions_Manager {

		private $_conditions         = array();
		private $_matched_conditions = array();
		private $_processed_childs   = array();
		public  $conditions_key      = 'softtemplate_site_conditions';

		public function __construct() {

			$this->register_conditions();
			add_action( 'elementor/editor/after_save', array( $this, 'update_site_conditions' ) );
			add_action( 'wp_trash_post', array( $this, 'remove_post_from_site_conditions' ) );
			add_action( 'transition_post_status',      array( $this, 'remove_post_from_site_conditions_on_change_status' ), 10, 3 );

		}

		public function update_site_conditions( $post_id ) {

			$post = get_post( $post_id );		

			if ( soft_template_core()->templates->slug() !== $post->post_type ) {
				return;
			}

			if ( 'publish' !== $post->post_status ) {
				return;
			}

			$type      = get_post_meta( $post_id, '_elementor_template_type', true );

			$sanitized = $this->get_post_conditions( $post_id );
			$saved     = get_option( $this->conditions_key, array() );
			$saved     = $this->remove_post_from_conditions_array( $post_id, $saved );

			if ( ! isset( $saved[ $type ] ) ) {
				$saved[ $type ] = array();
			}

			$saved[ $type ][ $post_id ] = $sanitized;

			update_option( $this->conditions_key, $saved, true );

		}

		public function get_site_conditions() {
			return get_option( $this->conditions_key, array() );
		}

		public function get_post_conditions( $post_id ) {

			$group      = '';
			$conditions = get_post_meta( $post_id, '_elementor_page_settings', true );
			$sanitized  = array();

			if ( ! $conditions ) {
				$conditions = array();
			}

			if ( isset( $conditions['conditions_top'] ) ) {
				$group             = $conditions['conditions_top'];
				$sanitized['main'] = $group;

				unset( $conditions['conditions_top'] );
			}

			foreach ( $conditions as $condition => $value ) {

				if ( false === strpos( $condition, 'conditions_' ) ) {
					continue;
				}

				if ( 'conditions_sub_' . $group === $condition ) {
					$sanitized[ $value ] = $this->get_condition_args( $value, $conditions );
					continue;
				}

			}

			return $sanitized;

		}

		/**
		 * Check if post currently presented in conditions array and remove it if yes.
		 *
		 * @param  integer $post_id    [description]
		 * @param  array   $conditions [description]
		 * @return [type]              [description]
		 */
		public function remove_post_from_conditions_array( $post_id = 0, $conditions = array() ) {

			foreach ( $conditions as $type => $type_conditions ) {
				if ( array_key_exists( $post_id, $type_conditions ) ) {
					unset( $conditions[ $type ][ $post_id ] );
				}
			}

			return $conditions;

		}

		public function remove_post_from_site_conditions( $post_id = 0 ) {

			$conditions = get_option( $this->conditions_key, array() );
			$conditions = $this->remove_post_from_conditions_array( $post_id, $conditions );

			update_option( $this->conditions_key, $conditions, true );
		}

		public function remove_post_from_site_conditions_on_change_status( $new_status, $old_status, $post ) {

			if ( soft_template_core()->templates->slug() !== $post->post_type ) {
				return;
			}

			if ( in_array( $new_status, array( 'publish', 'trash' ) ) ) {
				return;
			}

			$this->remove_post_from_site_conditions( $post->ID );
		}

		/**
		 * Find condition arguments in saved data
		 *
		 * @param  [type] $cid        [description]
		 * @param  [type] $conditions [description]
		 * @return [type]             [description]
		 */
		public function get_condition_args( $cid, $conditions ) {

			$args   = array();
			$prefix = 'conditions_' . $cid . '_';

			foreach ( $conditions as $condition => $value ) {

				if ( false === strpos( $condition, $prefix ) ) {
					continue;
				}

				$args[ str_replace( $prefix, '', $condition ) ] = $value;
			}

			return $args;
		}

		public function register_conditions() {

			$base_path = soft_template_core()->plugin_path( 'includes/conditions/' );

			require $base_path . 'base.php';

			$default = array(

				// Singular conditions
				'Soft_template_Core_Conditions_Front'                       => $base_path . 'singular-front-page.php',
				'Soft_template_Core_Conditions_Singular_Post_Type'          => $base_path . 'singular-post-type.php',
				'Soft_template_Core_Conditions_Singular_Post'               => $base_path . 'singular-post.php',
				'Soft_template_Core_Conditions_Singular_Post_From_Category' => $base_path . 'singular-post-from-cat.php',
				'Soft_template_Core_Conditions_Singular_Post_From_Tag'      => $base_path . 'singular-post-from-tag.php',
				'Soft_template_Core_Conditions_Singular_Page'               => $base_path . 'singular-page.php',
				'Soft_template_Core_Conditions_Singular_Page_Child'         => $base_path . 'singular-page-child.php',
				'Soft_template_Core_Conditions_Singular_Page_Template'      => $base_path . 'singular-page-template.php',
				'Soft_template_Core_Conditions_Singular_404'                => $base_path . 'singular-404.php',

				// Archive conditions
				'Soft_template_Core_Conditions_Archive_All'                 => $base_path . 'archive-all.php',
				'Soft_template_Core_Conditions_Archive_Post_Type'           => $base_path . 'archive-post-type.php',
				'Soft_template_Core_Conditions_Archive_Category'            => $base_path . 'archive-category.php',
				'Soft_template_Core_Conditions_Archive_Tag'                 => $base_path . 'archive-tag.php',
				'Soft_template_Core_Conditions_Archive_Tax'                 => $base_path . 'archive-tax.php',
				'Soft_template_Core_Conditions_Archive_Search'              => $base_path . 'archive-search.php',
			);

			foreach ( $default as $class => $file ) {
				require $file;
				$this->register_condition( $class );
			}

			/**
			 * You could register custom conditions on this hook.
			 * Note - each condition should be presented like instance of class 'Soft_template_Core_Conditions_Base'
			 */
			do_action( 'soft-template-core/conditions/register', $this );

		}

		public function register_condition( $class ) {
			$instance = new $class;
			$this->_conditions[ $instance->get_id() ] = $instance;
		}

		public function get_condition( $condition_id ) {
			return isset( $this->_conditions[ $condition_id ] ) ? $this->_conditions[ $condition_id ] : false;
		}

		/**
		 * Returns conditions groups
		 *
		 * @return void
		 */
		public function get_groups() {
			return array(
				'entire'   => __( 'Entire Site', 'soft-template-core' ),
				'singular' => __( 'Singular', 'soft-template-core' ),
				'archive'  => __( 'Archive', 'soft-template-core' ),
			);
		}

		/**
		 * Regsiter apropriate condition controls
		 *
		 * @return [type] [description]
		 */
		public function register_condition_controls( $controls_manager ) {

			if ( ! $controls_manager ) {
				return;
			}

			$groups           = $this->get_groups();
			$available_groups = $controls_manager->get_conditions_groups();

			if ( ! empty( $available_groups ) ) {
				$groups = array_intersect_key( $groups, array_combine( $available_groups, $available_groups ) );
			}

			$prepared_data = $this->prepare_conditions_for_controls();
			$default       = array( '' => esc_html__( 'Select...', 'soft-template-core' ) );
			$general       = $default + $this->get_groups();

			$controls_manager->add_control(
				'conditions_top',
				array(
					'label'   => esc_html__( 'General', 'soft-template-core' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => $general,
				)
			);

			foreach ( $prepared_data as $group => $options ) {

				if ( empty( $options ) ) {
					continue;
				}

				if ( ! isset( $groups[ $group ] ) ) {
					continue;
				}

				$condition = array(
					'conditions_top' => $group,
				);

				$control_name = 'conditions_sub_' . $group;

				$controls_manager->add_control(
					$control_name,
					array(
						'label'     => $general[ $group ],
						'type'      => Elementor\Controls_Manager::SELECT,
						'default'   => '',
						'options'   => $this->esc_options( $options ),
						'condition' => $condition,
					)
				);

				$this->register_child_options_group( $options, $controls_manager, $control_name, $condition );

			}

		}

		/**
		 * Get options list from options data
		 *
		 * @param  [type] $data [description]
		 * @return [type]       [description]
		 */
		public function esc_options( $data ) {

			$result = array();

			foreach ( $data as $id => $item ) {
				$result[ $id ] = $item['label'];
			}

			return $result;
		}

		public function esc_child_options( $childs ) {

			$result = array();

			foreach ( $childs as $child ) {
				$instance = $this->get_condition( $child );
				$result[ $child ] = $instance->get_label();
			}

			return $result;
		}

		public function register_child_options_group( $options, $controls_manager, $parent_id, $parent_condition ) {

			foreach ( $options as $cid => $data ) {

				$this->register_child_controls( $cid, $controls_manager, $parent_id, $parent_condition );

				if ( empty( $data['childs'] ) ) {
					continue;
				}

				$condition = array_merge(
					$parent_condition,
					array(
						$parent_id => $cid
					)
				);

				$instance     = $this->get_condition( $cid );
				$control_name = 'conditions_sub_' . $cid;

				$controls_manager->add_control(
					$control_name,
					array(
						'label'     => $instance->get_label(),
						'type'      => Elementor\Controls_Manager::SELECT,
						'default'   => '',
						'options'   => $this->esc_child_options( $data['childs'] ),
						'condition' => $condition,
					)
				);

				$this->register_child_options_item( $data['childs'], $controls_manager, $control_name, $condition );


			}

		}

		public function register_child_options_item( $items, $controls_manager, $parent_id, $parent_condition ) {

			foreach ( $items as $cid ) {

				$instance = $this->get_condition( $cid );
				$childs   = $instance->get_childs();

				$this->register_child_controls( $cid, $controls_manager, $parent_id, $parent_condition );

				if ( empty( $childs ) ) {
					continue;
				}

				$condition = array_merge(
					$parent_condition,
					array(
						$parent_id => $cid
					)
				);

				$control_name = 'conditions_sub_' . $cid;

				$controls_manager->add_control(
					$control_name,
					array(
						'label'     => $instance->get_label(),
						'type'      => Elementor\Controls_Manager::SELECT,
						'default'   => '',
						'options'   => $this->esc_child_options( $childs ),
						'condition' => $condition,
					)
				);

				$this->register_child_options_item( $childs, $controls_manager, $control_name, $condition );

			}

		}

		public function register_child_controls( $condition_id, $controls_manager, $parent_id, $parent_condition ) {

			$instance = $this->get_condition( $condition_id );
			$controls = $instance->get_controls();

			if ( empty( $controls ) ) {
				return;
			}

			foreach ( $controls as $control_id => $control_options ) {

				$id         = 'conditions_' . $condition_id . '_' . $control_id;
				$child_cond = ! empty( $control_options['condition'] ) ? $control_options['condition'] : array();

				$control_options['condition'] = array_merge(
					$child_cond,
					array( $parent_id => $condition_id ),
					$parent_condition
				);

				$controls_manager->add_control( $id, $control_options );
			}

		}

		/**
		 * Prepare registerred conditions for controls
		 *
		 * @return array
		 */
		public function prepare_conditions_for_controls() {

			$sorted_conditions = array();

			foreach ( $this->_conditions as $cid => $instance ) {

				if ( in_array( $cid, $this->_processed_childs ) ) {
					continue;
				}

				$group  = $instance->get_group();
				$childs = $instance->get_childs();

				if ( ! isset( $sorted_conditions[ $group ] ) ) {
					$sorted_conditions[ $group ] = array();
				}

				$current = array(
					'label' => $instance->get_label(),
				);

				if ( ! empty( $childs ) ) {
					$current['childs'] = $this->add_condition_childs( $childs );
				}

				$sorted_conditions[ $group ][ $cid ] = $current;

			}

			return $sorted_conditions;
		}

		/**
		 * Add child conditions to stack
		 */
		public function add_condition_childs( $childs ) {

			$result = array();

			foreach ( $childs as $cid ) {
				$instance = $this->get_condition( $cid );
				$childs   = $instance->get_childs();
				$current  = array(
					'label' => $instance->get_label(),
				);

				if ( ! empty( $childs ) ) {
					$current['childs'] = $this->add_condition_childs( $childs );
				}

				$result[ $cid ] = $current;

				if ( ! in_array( $cid, $this->_processed_childs ) ) {
					$this->_processed_childs[] = $cid;
				}
			}

			return $result;

		}

		/**
		 * Run condtions check for passed type. Return {template_id} on firs condition match.
		 * If not matched - return false
		 *
		 * @return int|bool
		 */
		public function find_matched_conditions( $type ) {

			if ( isset( $this->_matched_conditions[ $type ] ) ) {
				return $this->_matched_conditions[ $type ];
			}

			$conditions = get_option( $this->conditions_key, array() );
			
			if ( empty( $conditions[ $type ] ) ) {
				$this->_matched_conditions[ $type ] = false;
				return false;
			}
			$entire = false;
			
			foreach ( $conditions[ $type ] as $template_id => $template_conditions ) {
				
				if ( empty( $template_conditions['main'] ) ) {
					continue;
				}
				
				// for multi-language plugins
				$template_id = apply_filters( 'soft-template-core/get_location_templates/template_id', $template_id );
				

				if ( 'entire' === $template_conditions['main'] ) {
					$this->_matched_conditions[ $type ] = $template_id;
					$entire = $template_id;
					continue;
				}		
				
				
				foreach ( $template_conditions as $cid => $args ) {

					$instance = $this->get_condition( $cid );

					if ( ! $instance ) {
						continue;
					}
					
					$check = call_user_func( array( $instance, 'check' ), $args );
					
					if ( true === $check ) {
						$this->_matched_conditions[ $type ] = $template_id;
						return $template_id;
					}
					
				}

			}

			if ( $entire ) {
				return $entire;
			}

			$this->_matched_conditions[ $type ] = false;
			return false;

		}

		/**
		 * Get active conditions for passed post
		 *
		 * @param  [type] $post_id [description]
		 * @return [type]          [description]
		 */
		public function post_conditions_verbose( $post_id = null ) {

			$conditions = $this->get_post_conditions( $post_id );

			if ( empty( $conditions['main'] ) ) {
				return;
			}

			if ( 'entire' === $conditions['main'] ) {
				return __( 'Entire Site', 'soft-template-core' );
			}		
			
			unset( $conditions['main'] );

			$condition_keys = array_keys( $conditions );
			$verbose        = '';

			foreach ( $condition_keys as $key ) {

				$instance     = $this->get_condition( $key );
				$verbose_args = $instance->verbose_args( $conditions[ $key ] );

				if ( ! empty( $verbose_args ) ) {
					$verbose_args = ': ' . $verbose_args;
				}

				$verbose .= sprintf( '<div>%1$s%2$s</div>', $instance->get_label(), $verbose_args );

			}

			return $verbose;

		}		
		
		public function post_conditions_terms( $post_id = null ) {

			$conditions = $this->get_post_conditions( $post_id );

			if ( empty( $conditions['main'] ) ) {
				return;
			}

			if ( 'entire' === $conditions['main'] ) {
				return __( 'Entire Site', 'soft-template-core' );
			}		
			
			unset( $conditions['main'] );

			$condition_keys = array_keys( $conditions );
			$verbose        = '';

			foreach ( $condition_keys as $key ) {

				$instance     = $this->get_condition( $key );
				$verbose_args = $instance->verbose_args( $conditions[ $key ] );

				if ( ! empty( $verbose_args ) ) {
					$verbose_args = $verbose_args;
				}

				var_dump($verbose_args);


				//$verbose .= sprintf( '<div>%1$s%2$s</div>', $instance->get_label(), $verbose_args );

			}

			return $verbose;

		}

	}

}
