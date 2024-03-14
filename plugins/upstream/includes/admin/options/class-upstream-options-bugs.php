<?php
/**
 * UpStream_Options_Bugs
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Options_Bugs' ) ) :

	/**
	 * CMB2 Theme Options
	 *
	 * @version 0.1.0
	 */
	class UpStream_Options_Bugs {


		/**
		 * ID of metabox
		 *
		 * @var array
		 */
		public $id = 'upstream_bugs';

		/**
		 * Page title
		 *
		 * @var string
		 */
		protected $title = '';

		/**
		 * Menu Title
		 *
		 * @var string
		 */
		protected $menu_title = '';

		/**
		 * Menu Title
		 *
		 * @var string
		 */
		protected $description = '';

		/**
		 * Holds an instance of the object
		 *
		 * @var Myprefix_Admin
		 **/
		public static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 0.1.0
		 */
		public function __construct() {
			// Set our title.
			$this->title      = upstream_bug_label_plural();
			$this->menu_title = $this->title;
		}

		/**
		 * Returns the running object
		 *
		 * @return Myprefix_Admin
		 **/
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add the options metabox to the array of metaboxes
		 *
		 * @since  0.1.0
		 */
		public function options() {
			$options = apply_filters(
				$this->id . '_option_fields',
				array(
					'id'         => $this->id, // upstream_tasks.
					'title'      => $this->title,
					'menu_title' => $this->menu_title,
					'desc'       => $this->description,
					'show_on'    => array(
						'key'   => 'options-page',
						'value' => array( $this->id ),
					),
					'show_names' => true,
					'fields'     => array(
						array(
							'name' => __( 'Statuses', 'upstream' ),
							'id'   => 'status_title',
							'type' => 'title',
							'desc' => sprintf(
								// translators: %1$s: upstream_bug_label_plural.
								// translators: %2$s: upstream_bug_label.
								// translators: %3$s: upstream_bug_label.
								__(
									'The statuses and colors that can be used for the status of %1$s.<br>These will become available in the %2$s Status dropdown within each %3$s',
									'upstream'
								),
								upstream_bug_label_plural(),
								upstream_bug_label(),
								upstream_bug_label()
							),
						),
						array(
							'id'              => 'statuses',
							'type'            => 'group',
							'name'            => 'Statuses',
							'description'     => '',
							'options'         => array(
								'group_title'   => __( 'Status {#}', 'upstream' ),
								'add_button'    => __( 'Add Status', 'upstream' ),
								'remove_button' => __( 'Remove Entry', 'upstream' ),
								'sortable'      => true, // beta.
							),
							'sanitization_cb' => array( 'UpStream_Admin', 'on_before_save' ),
							'fields'          => array(
								array(
									'name' => __( 'Hidden', 'upstream' ),
									'id'   => 'id',
									'type' => 'hidden',
								),
								array(
									'name'       => __( 'Status Color', 'upstream' ),
									'id'         => 'color',
									'type'       => 'colorpicker',
									'attributes' => array(
										'data-colorpicker' => json_encode(
											array(
												// Iris Options set here as values in the 'data-colorpicker' array.
												'palettes' => upstream_colorpicker_default_colors(),
												'width'    => 300,
											)
										),
									),
								),
								array(
									'name' => __( 'Status Name', 'upstream' ),
									'id'   => 'name',
									'type' => 'text',
								),
								array(
									'name'    => __( 'Type of Status', 'upstream' ),
									'id'      => 'type',
									'type'    => 'radio',
									'default' => 'open',
									'desc'    => __(
										"A Status Name such as 'In Progress' or 'Overdue' would be considered Open.",
										'upstream'
									) . '<br>' . __(
										"A Status Name such as 'Complete' or 'Cancelled' would be considered Closed.",
										'upstream'
									),
									'options' => array(
										'open'   => __( 'Open', 'upstream' ),
										'closed' => __( 'Closed', 'upstream' ),
									),
								),
							),
						),
						array(
							'name' => __( 'Severity', 'upstream' ),
							'id'   => 'severity_title',
							'type' => 'title',
							'desc' => sprintf(
								// translators: %1$s: upstream_bug_label_plural.
								// translators: %2$s: upstream_bug_label.
								// translators: %3$s: upstream_bug_label.
								__(
									'The severity and colors that can be used for the severity of %1$s.<br>These will become available in the %2$s Severity dropdown within each %3$s',
									'upstream'
								),
								upstream_bug_label_plural(),
								upstream_bug_label(),
								upstream_bug_label()
							),
						),
						array(
							'id'              => 'severities',
							'type'            => 'group',
							'name'            => 'Severities',
							'description'     => '',
							'options'         => array(
								'group_title'   => __( 'Severity {#}', 'upstream' ),
								'add_button'    => __( 'Add Severity', 'upstream' ),
								'remove_button' => __( 'Remove Entry', 'upstream' ),
								'sortable'      => true, // beta.
							),
							'sanitization_cb' => array( 'UpStream_Admin', 'on_before_save' ),
							'fields'          => array(
								array(
									'name' => __( 'Hidden', 'upstream' ),
									'id'   => 'id',
									'type' => 'hidden',
								),
								array(
									'name'       => __( 'Severity Color', 'upstream' ),
									'id'         => 'color',
									'type'       => 'colorpicker',
									'attributes' => array(
										'data-colorpicker' => wp_json_encode(
											array(
												// Iris Options set here as values in the 'data-colorpicker' array.
												'palettes' => upstream_colorpicker_default_colors(),
												'width'    => 300,
											)
										),
									),
								),
								array(
									'name' => __( 'Severity Name', 'upstream' ),
									'id'   => 'name',
									'type' => 'text',
								),
							),
						),

					),
				)
			);

			return $options;
		}

		/**
		 * Create ids for all existent bugs statuses/severities.
		 *
		 * @since   1.17.0
		 * @static
		 */
		public static function create_bugs_statuses_ids() {
			$continue = ! (bool) get_option( 'upstream:created_bugs_args_ids' );
			if ( ! $continue ) {
				return;
			}

			$bugs = get_option( 'upstream_bugs' );
			if ( isset( $bugs['statuses'] ) && isset( $bugs['severities'] ) ) {
				$bugs['statuses']   = UpStream_Admin::create_missing_ids_in_set( $bugs['statuses'] );
				$bugs['severities'] = UpStream_Admin::create_missing_ids_in_set( $bugs['severities'] );

				update_option( 'upstream_bugs', $bugs );

				// Update existent Bugs statuses/severities across all Projects.
				global $wpdb;

				$metas = $wpdb->get_results(
					sprintf(
						'SELECT `post_id`, `meta_value`
                FROM `%s`
                WHERE `meta_key` = "_upstream_project_bugs"',
						$wpdb->prefix . 'postmeta'
					)
				);

				if ( count( $metas ) > 0 ) {
					$get_bug_arg_id_by_title = function ( $needle, $arg_name = 'statuses' ) use ( &$bugs ) {
						foreach ( $bugs[ $arg_name ] as $bug ) {
							if ( $needle === $bug['name'] ) {
								return $bug['id'];
							}
						}

						return false;
					};

					$replace_bug_args_with_its_ids = function ( $bug ) use ( &$get_bug_arg_id_by_title ) {
						if ( isset( $bug['status'] )
							&& ! empty( $bug['status'] )
						) {
							$bug_arg_id = $get_bug_arg_id_by_title( $bug['status'] );
							if ( false !== $bug_arg_id ) {
								$bug['status'] = $bug_arg_id;
							}
						}

						if ( isset( $bug['severity'] )
							&& ! empty( $bug['severity'] )
						) {
							$bug_arg_id = $get_bug_arg_id_by_title( $bug['severity'], 'severities' );
							if ( false !== $bug_arg_id ) {
								$bug['severity'] = $bug_arg_id;
							}
						}

						return $bug;
					};

					foreach ( $metas as $meta ) {
						if ( empty( $meta->meta_value ) ) {
							continue;
						}

						$project_id = (int) $meta->post_id;

						$data = array_filter( maybe_unserialize( (string) $meta->meta_value ) );
						$data = array_map( $replace_bug_args_with_its_ids, $data );

						update_post_meta( $project_id, '_upstream_project_bugs', $data );
					}
				}

				update_option( 'upstream:created_bugs_args_ids', 1 );
			}
		}
	}


endif;
