<?php
/**
 * Iubenda legal widget.
 *
 * It is used to attach, delete and render legal widget.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Iubenda_Legal_Widget
 */
class Iubenda_Legal_Widget extends WP_Widget {

	/**
	 * Widget_id
	 *
	 * @var string
	 */
	private $widget_id = 'iubenda_legal_widget';

	/**
	 * Default widget title
	 *
	 * @var string
	 */
	private $default_widget_title;

	/**
	 * Iubenda_Legal_Widget constructor.
	 */
	public function __construct() {
		$this->default_widget_title = esc_html__( 'Legal', 'iubenda' );
		parent::__construct(
			// Base ID of your widget.
			$this->widget_id,
			// Widget name will appear in UI.
			__( 'Iubenda legal', 'iubenda' ),
			// Widget description.
			array( 'description' => __( 'Iubenda legal widget for Privacy Policy and Terms & Conditions', 'iubenda' ) )
		);

		add_action( 'iubenda_assign_widget_to_first_sidebar', array( $this, 'assign_iubenda_widget' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_iubenda_elementor_widget' ) );
		$this->init();
	}

	/**
	 * Register HOOKS
	 *
	 * @return void
	 */
	private function init() {
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	/**
	 * Set default value for the first Iubenda Legal widget
	 *
	 * @return array|array[]|ArrayIterator|ArrayObject|false
	 */
	public function get_settings() {
		$settings = parent::get_settings();
		// Set default value for the first widget.
		if ( ! $settings ) {
			return array( 1 => array() );
		}

		return $settings;
	}

	/**
	 * Generates the actual widget content (Do NOT override).
	 *
	 * Finds the instance and calls WP_Widget::widget().
	 *
	 * @param array     $args        Display arguments. See WP_Widget::widget() for information
	 *                               on accepted arguments.
	 * @param int|array $widget_args {
	 *     Optional. Internal order number of the widget instance, or array of multi-widget arguments.
	 *     Default 1.
	 *
	 *     @type int $number Number increment used for multiples of the same widget.
	 * }
	 */
	public function display_callback( $args, $widget_args = 1 ) {

		if ( is_numeric( $widget_args ) ) {
			$widget_args = array( 'number' => $widget_args );
		}

		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		$this->_set( $widget_args['number'] );
		$instances = $this->get_settings();

		// Additional code to add default title widget title if it's not set yet.
		if ( ! $instances ) {
			$instances[ $this->number ] = array( 'title' => $this->default_widget_title );
		}

		if ( isset( $instances[ $this->number ] ) ) {
			$instance = $instances[ $this->number ];

			/**
			 * Filters the settings for a particular widget instance.
			 *
			 * Returning false will effectively short-circuit display of the widget.
			 *
			 * @since 2.8.0
			 *
			 * @param array     $instance The current widget instance's settings.
			 * @param WP_Widget $widget   The current widget instance.
			 * @param array     $args     An array of default widget arguments.
			 */
			$instance = apply_filters( 'widget_display_callback', $instance, $this, $args );

			if ( false === $instance ) {
				return;
			}

			$was_cache_addition_suspended = wp_suspend_cache_addition();
			if ( $this->is_preview() && ! $was_cache_addition_suspended ) {
				wp_suspend_cache_addition( true );
			}

			$this->widget( $args, $instance );

			if ( $this->is_preview() ) {
				wp_suspend_cache_addition( $was_cache_addition_suspended );
			}
		}
	}

	/**
	 * Creating widget front-end.
	 *
	 * @param   array $args args.
	 * @param   array $instance instance.
	 *
	 * @return false|void
	 */
	public function widget( $args, $instance ) {
		$pp_status   = (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true';
		$pp_position = (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) === 'automatic';
		$tc_status   = (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true';
		$tc_position = (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'automatic';

		// Checking if there is no public id for current language.
		if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
			$lang_id = iubenda()->lang_current;
		} else {
			$lang_id = 'default';
		}

		$public_id = (string) iub_array_get( iubenda()->options['global_options'], "public_ids.{$lang_id}" );
		// Return false if there is no public id for current language.
		if ( empty( $public_id ) ) {
			return false;
		}

		$quick_generator_service = new Quick_Generator_Service();

		if ( ! ( $pp_status && $pp_position && boolval( $quick_generator_service->pp_button() ) ) && ! ( $tc_status && $tc_position && boolval( $quick_generator_service->tc_button() ) ) ) {
			return false;
		}

		$title = apply_filters( 'widget_title', iub_array_get( $instance, 'title', 'Legal' ) );

		// before and after widget arguments are defined by themes.
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
		}

		// Display TC or PP if activated.
		if ( ( $pp_status && $pp_position ) || ( $tc_status && $tc_position ) ) {

			$legal = '<section>';

			if ( $pp_status && $pp_position ) {
				$legal .= $quick_generator_service->pp_button();
			}
			if ( ( $pp_status && $pp_position ) && ( $tc_status && $tc_position ) ) {
				$legal .= '<br>';
			}
			if ( $tc_status && $tc_position ) {
				$legal .= $quick_generator_service->tc_button();
			}

			$legal .= '</section>';

			echo $legal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Widget Backend (Admin panel).
	 *
	 * @param   array $instance instance.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$title = __( 'Legal', 'iubenda' );

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}

		// Widget admin form.
		?>
		<p>
			<label for="<?php echo sanitize_key( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<?php
	}

	/**
	 * Updating widget replacing old instances with new.
	 *
	 * @param   array $new_instance new_instance.
	 * @param   array $old_instance old_instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		return $instance;
	}

	/**
	 * Assign iubenda widget to registered sidebar if exists and not registered before
	 *
	 * @return void
	 */
	public function assign_iubenda_widget() {
		global $wp_registered_sidebars;

		// Check if iubenda widget activated in any sidebar.
		if ( is_active_widget( false, false, $this->widget_id ) ) {
			return;
		}

		// If sidebar-1 not registered or not activated.
		if ( ! iub_array_get( $wp_registered_sidebars, 'sidebar-1' ) ) {
			return;
		}

		// Check if wp_assign_widget_to_sidebar is existing.
		if ( ! function_exists( 'wp_assign_widget_to_sidebar' ) ) {
			return;
		}

		// Iubenda widget in not activated in sidebar and sidebar-1 is registered and activated.
		wp_assign_widget_to_sidebar( "{$this->widget_id}-1", 'sidebar-1' );
	}

	/**
	 * Register current widget in WP
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( __CLASS__ );
	}

	/**
	 * Check current theme supports widget
	 */
	public function check_current_theme_supports_widget() {
		return (bool) current_theme_supports( 'widgets' );
	}

	/**
	 * Register iubenda elementor widget
	 */
	public function register_iubenda_elementor_widget() {
		require_once IUBENDA_PLUGIN_PATH . 'includes/widget/elementor/class-iubenda-elementor-legal-widget.php';
		if ( class_exists( '\Elementor\Plugin' ) && property_exists( \Elementor\Plugin::instance(), 'widgets_manager' ) ) {
			$widget  = new Iubenda_Elementor_Legal_Widget();
			$manager = \Elementor\Plugin::instance()->widgets_manager;

			if ( method_exists( $manager, 'register' ) ) {
				$manager->register( $widget );
			} elseif ( method_exists( $manager, 'register_widget_type' ) ) {
				$manager->register_widget_type( $widget );
			}
		}
	}
}



