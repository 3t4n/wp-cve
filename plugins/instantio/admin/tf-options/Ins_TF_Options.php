<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ins_TF_Options' ) ) {
	class Ins_TF_Options {
		private static $instance = null;

		/**
		 * Singleton instance
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {

			// constant define
			$this->tf_options_define();

			//load files
			$this->Ins_classes_load_files();

			//load metaboxes
			$this->load_metaboxes();

			//load options
			$this->Ins_load_options();

			//load taxonomy


			//enqueue scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'Ins_tf_options_admin_enqueue_scripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'Ins_tf_options_wp_enqueue_scripts' ) );
		}

		// constant define
		public function tf_options_define() {
			if ( ! defined( 'INS_OPTIONS_PATH' ) ) {
				define( 'INS_OPTIONS_PATH', plugin_dir_path( __FILE__ ) );
			}


		}

		public function Ins_tf_options_version() {
			return '2.3.0';
		}

		public function Ins_tf_options_file_path( $file_path = '' ) {
			return plugin_dir_path( __FILE__ ) . $file_path;
		}

		public function Ins_tf_options_file_url( $file_url = '' ) {
			return plugin_dir_url( __FILE__ ) . $file_url;
		}

		/**
		 * Load files
		 * @author Foysal
		 */
		public function Ins_classes_load_files() {
			// Metaboxes Class
			require_once $this->Ins_tf_options_file_path( 'classes/INS_Metabox.php' );
			// Settings Class
			require_once $this->Ins_tf_options_file_path( 'classes/Ins_TF_Settings.php' );
			//Taxonomy Class
			require_once $this->Ins_tf_options_file_path( 'classes/TF_Taxonomy_Metabox.php' );

		}

		/**
		 * Load metaboxes
		 * @author Foysal
		 */
		public function load_metaboxes() {
			// if ( $this->is_ins_pro_active() ) {
			// 	$metaboxes = glob( INS_PRO_ADMIN_URL . '/tf-options/metaboxes/*.php' );
			// } else {
			// 	$metaboxes = glob( $this->Ins_tf_options_file_path( 'metaboxes/*.php' ) );
			// }
			$metaboxes = glob( $this->Ins_tf_options_file_path( 'metaboxes/*.php' ) );


			if ( ! empty( $metaboxes ) ) {
				foreach ( $metaboxes as $metabox ) {
					if ( file_exists( $metabox ) ) {
						require_once $metabox;
					}
				}
			}
		}

		/**
		 * Load Options
		 * @author Foysal
		 */
		public function Ins_load_options() {
			$license_status = apply_filters( 'ins_checked_license_status', 'false' );
			if ( $this->is_ins_pro_active() && $license_status != false ) {
				$options = glob( INS_PRO_ADMIN_PATH . '/tf-options/options/*.php' );
			} else {
				$options = glob( $this->Ins_tf_options_file_path( 'options/*.php' ) );
			}

			if ( ! empty( $options ) ) {
				foreach ( $options as $option ) {
					if ( file_exists( $option ) ) {
						require_once $option;
					}
				}
			}
		}



		/**
		 * Admin Enqueue scripts
		 * @author M Hemel Hasan
		 */
		public function Ins_tf_options_admin_enqueue_scripts( $screen ) {
			// var_dump($screen);
			global $post_type;
			$tf_options_screens = array( 'toplevel_page_wiopt', 'instantio_page_ins_dashboard', 'instantio_page_tf_license_info', 'instantio_page_ins_get_help', 'instantio_page_ins_whats_new', 'admin_page_tf-setup-wizard', 'instantio_page_ins-license-activation' );

			if ( in_array( $screen, $tf_options_screens ) ) {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'tf-fontawesome-4', '//cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'tf-fontawesome-5', '//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css', array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'tf-fontawesome-6', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'tf-remixicon', '//cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css', array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'tf-select2', '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'tf-flatpickr', '//cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css', array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'ins-tf-options', $this->Ins_tf_options_file_url( 'assets/css/tf-options.css' ), array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'notyf-css', $this->Ins_tf_options_file_url( 'assets/libs/notyf/notyf.min.css' ), array(), $this->Ins_tf_options_version() );
			}

			//Js
			if ( in_array( $screen, $tf_options_screens ) ) {

				wp_enqueue_script( 'tf-flatpickr', '//cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js', array( 'jquery' ), $this->Ins_tf_options_version() );
				wp_enqueue_script( 'tf-select2', '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), $this->Ins_tf_options_version() );
				wp_enqueue_script( 'wp-color-picker-alpha', '//raw.githubusercontent.com/kallookoo/wp-color-picker-alpha/master/src/wp-color-picker-alpha.js', array( 'jquery', 'wp-color-picker' ), $this->Ins_tf_options_version() );
				wp_enqueue_script( 'setup-wizard', $this->Ins_tf_options_file_url( 'assets/js/setup-wizard.js' ), array( 'jquery' ), $this->Ins_tf_options_version() );
				wp_enqueue_script( 'notyf-js', $this->Ins_tf_options_file_url( 'assets/libs/notyf/notyf.min.js' ), array( 'jquery' ), $this->Ins_tf_options_version() );
				//dashboard
				if ( $screen == 'instantio_page_ins_dashboard' ) {
					wp_enqueue_script( 'dashboard-js', $this->Ins_tf_options_file_url( 'assets/js/dashboard.js' ), array( 'jquery' ), $this->Ins_tf_options_version() );
				}
				wp_enqueue_script( 'ins-admin', $this->Ins_tf_options_file_url( 'assets/js/admin.js' ), array( 'jquery' ), $this->Ins_tf_options_version() );

				wp_enqueue_script( 'jquery-ui-autocomplete' );

				if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
					wp_enqueue_script( 'jquery-ui-sortable' );
				}
				wp_enqueue_media();
				wp_enqueue_editor();
			}


			wp_enqueue_script( 'ins_tf-options', $this->Ins_tf_options_file_url( 'assets/js/ins-options.js' ), array( 'jquery' ), $this->Ins_tf_options_version() );

			wp_localize_script( 'ins_tf-options', 'tf_options', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'tf_options_nonce' ),
				'option_id' => 'wiopt',
			) );

			wp_localize_script( 'ins-admin', 'ins_admin', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			) );
		}

		/**
		 * Enqueue scripts
		 * @author 
		 */
		public function Ins_tf_options_wp_enqueue_scripts() {
			wp_enqueue_style( 'tf-fontawesome-4', '//cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', array(), $this->Ins_tf_options_version() );
			wp_enqueue_style( 'tf-fontawesome-5', '//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css', array(), $this->Ins_tf_options_version() );
			wp_enqueue_style( 'tf-fontawesome-6', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), $this->Ins_tf_options_version() );
			wp_enqueue_style( 'tf-remixicon', '//cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css', array(), $this->Ins_tf_options_version() );
		}

		/*
		 * Field Base
		 * @author 
		 */
		public function field( $field, $value, $settings_id = '', $parent = '' ) {
			if ( $field['type'] == 'repeater' ) {
				$id = ( ! empty( $settings_id ) ) ? $settings_id . '[' . $field['id'] . '][0]' . '[' . $field['id'] . ']' : $field['id'] . '[0]' . '[' . $field['id'] . ']';
			} else {
				$id = $settings_id . '[' . $field['id'] . ']';
			}

			$class = isset( $field['class'] ) ? $field['class'] : '';

			$is_pro = isset( $field['is_pro'] ) ? $field['is_pro'] : '';
			$badge_up = isset( $field['badge_up'] ) ? $field['badge_up'] : '';

			if ( function_exists( 'is_ins_pro' ) && is_ins_pro() ) {
				$is_pro = false;
			}
			if ( $is_pro == true ) {
				$class .= ' tf-field-disable tf-field-pro';
			}
			if ( $badge_up == true ) {
				$class .= ' tf-field-disable tf-field-upcoming';
			}
			$tf_meta_box_dep_value = get_post_meta( get_the_ID(), $settings_id, true );


			$depend = '';
			if ( ! empty( $field['dependency'] ) ) {

				$dependency = $field['dependency'];
				$depend_visible = '';
				$data_controller = '';
				$data_condition = '';
				$data_value = '';
				$data_global = '';

				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition = implode( '|', array_column( $dependency, 1 ) );
					$data_value = implode( '|', array_column( $dependency, 2 ) );
					$data_global = implode( '|', array_column( $dependency, 3 ) );
					$depend_visible = implode( '|', array_column( $dependency, 4 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
					$depend_visible = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
				}

				$depend .= ' data-controller="' . esc_attr( $data_controller ) . '' . $parent . '"';
				$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
				$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

				$visible = 'tf-dependency-control';
				$visible = ( ! empty( $depend_visible ) ) ? ' tf-depend-visible' : ' tf-depend-hidden';
			}

			//field width
			$field_width = isset( $field['field_width'] ) && ! empty( $field['field_width'] ) ? esc_attr( $field['field_width'] ) : '100';
			if ( $field_width == '100' ) {
				$field_style = 'width:100%;';
			} else {
				$field_style = 'width:calc(' . $field_width . '% - 10px);';
			}
			?>

			<div class="tf-field tf-field-<?php echo esc_attr( $field['type'] ); ?> <?php echo esc_attr( $class ); ?> <?php echo ! empty( $visible ) ? $visible : ''; ?>"
				<?php echo ! empty( $depend ) ? $depend : ''; ?> style="<?php echo esc_attr( $field_style ); ?>">

				<?php if ( ! empty( $field['label'] ) ) : ?>
					<label for="<?php echo esc_attr( $id ) ?>" class="tf-field-label">
						<?php echo esc_html( $field['label'] ) ?>
						<?php if ( $is_pro ) : ?>
							<div class="tf-csf-badge"><span class="tf-pro">
									<?php _e( "Pro", "instantio" ); ?>
								</span></div>
						<?php endif; ?>
						<?php if ( $badge_up ) : ?>
							<div class="tf-csf-badge"><span class="tf-upcoming">
									<?php _e( "Upcoming", "instantio" ); ?>
								</span></div>
						<?php endif; ?>
					</label>
				<?php endif; ?>

				<?php if ( ! empty( $field['subtitle'] ) ) : ?>
					<span class="tf-field-sub-title">
						<?php echo wp_kses_post( $field['subtitle'] ) ?>
					</span>
				<?php endif; ?>

				<div class="tf-fieldset">
					<?php
					$fieldClass = 'INS_' . $field['type'];
					if ( class_exists( $fieldClass ) ) {
						$_field = new $fieldClass( $field, $value, $settings_id, $parent );
						$_field->render();
					} else {
						echo '<p>' . __( 'Field not found!', 'instantio' ) . '</p>';
					}
					?>
				</div>
				<?php if ( ! empty( $field['description'] ) ) : ?>
					<p class="description">
						<?php echo wp_kses_post( $field['description'] ) ?>
					</p>
				<?php endif; ?>
			</div>
			<?php
		}

		public function is_ins_pro_active() {
			if ( is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists( 'WOOINS' ) ) {
				return true;
			}
			return false;
		}

	}
}

Ins_TF_Options::instance();