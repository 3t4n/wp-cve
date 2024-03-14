<?php
/**
 * Masteriyo WooCommerce Integration setup.
 *
 * @package Masteriyo\WcIntegration
 *
 * @since 1.8.1
 */

namespace Masteriyo\Addons\WcIntegration;

use Masteriyo\Enums\OrderStatus;
use Masteriyo\Query\UserCourseQuery;
use Masteriyo\Enums\CourseAccessMode;
use Masteriyo\Enums\UserCourseStatus;
use Masteriyo\Pro\Enums\SubscriptionStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo WcIntegration class.
 *
 * @class Masteriyo\Addons\WcIntegration
 * @since 1.8.1
 */

class WcIntegrationAddon {

	/**
	 * Instance of Setting class.
	 *
	 * @since 1.8.1
	 *
	 * @var \Masteriyo\Addons\WcIntegration\Setting
	 */
	public $setting = null;

	/**
	 * The single instance of the class.
	 *
	 * @since 1.8.1
	 *
	 * @var \Masteriyo\Addons\WcIntegration\WcIntegrationAddon|null
	 */
	protected static $instance = null;

	/**
	 * Constructor.
	 *
	 * @since 1.8.1
	 */
	protected function __construct() {}

	/**
	 * Get class instance.
	 *
	 * @since 1.8.1
	 *
	 * @return \Masteriyo\Addons\WcIntegration\WcIntegrationAddon Instance.
	 */
	final public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prevent cloning.
	 *
	 * @since 1.8.1
	 */
	public function __clone() {}

	/**
	 * Prevent unserializing.
	 *
	 * @since 1.8.1
	 */
	public function __wakeup() {}

	/**
	 * Initialize module.
	 *
	 * @since 1.8.1
	 */
	public function init() {
		$this->setting = new Setting();
		$this->setting->init();

		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.8.1
	 */
	public function init_hooks() {
		add_filter( 'masteriyo_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'masteriyo_localized_admin_scripts', array( $this, 'localize_admin_scripts' ) );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_masteriyo_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'display_masteriyo_tab_content' ) );
		add_action( 'admin_head', array( $this, 'add_masteriyo_tab_icon' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_masteriyo_data' ), 10, 2 );
		add_filter( 'masteriyo_ajax_handlers', array( $this, 'register_ajax_handlers' ) );
		add_filter( 'masteriyo_course_add_to_cart_url', array( $this, 'change_add_to_cart_url' ), 10, 2 );

		// Handle WooCommerce order events to Masteriyo order events.
		add_action( 'woocommerce_new_order', array( $this, 'create_user_course' ), 10 );
		add_action( 'woocommerce_update_order', array( $this, 'create_user_course' ), 10 );
		add_action( 'woocommerce_order_status_changed', array( $this, 'change_order_status' ), 10, 4 );
		add_action( 'product_type_selector', array( $this, 'add_course_product_type' ) );
		add_filter( 'woocommerce_product_class', array( $this, 'register_course_product_class' ), 10, 4 );
		add_action( 'woocommerce_mto_course_add_to_cart', array( $this, 'use_simple_add_to_cart_template' ) );
		add_action( 'admin_footer', array( $this, 'print_inline_scripts' ) );

		// Update the start course for course connected with WC product.
		add_filter( 'masteriyo_can_start_course', array( $this, 'update_can_start_course' ), 10, 3 );

		add_action( 'profile_update', array( $this, 'add_student_role_to_wc_customer' ) );
		add_action( 'user_register', array( $this, 'add_student_role_to_wc_customer' ) );

		if ( Helper::is_wc_subscriptions_active() ) {
			add_action( 'woocommerce_mto_course_recurring_add_to_cart', array( $this, 'use_simple_add_to_cart_template' ) );
			add_filter( 'woocommerce_is_subscription', array( $this, 'modify_is_subscription' ), 10, 3 );
			add_filter( 'wcs_admin_is_subscription_product_save_request', array( $this, 'modify_is_subscription_product_save_request' ), 10, 3 );
		}
	}

	/**
	 * Modify is subscription.
	 *
	 * @since 1.8.1
	 * @param boolean $is_subscription Is subscription.
	 * @param int $id Product id.
	 * @param \WC_Product $product WC Product object
	 * @return boolean
	 */
	public function modify_is_subscription( $is_subscription, $id, $product ) {
		if ( $product->is_type( 'mto_course_recurring' ) ) {
			$is_subscription = true;
		}
		return $is_subscription;
	}

	/**
	 * Mark request if for subscription.
	 *
	 * @since 1.8.1
	 * @param bool $is_subscription_product_save_request Is subscription product save request.
	 * @param int $post_id Post ID.
	 * @param array $product_types Product types.
	 */
	public function modify_is_subscription_product_save_request( $is_subscription_product_save_request, $post_id, $product_types ) {
		if ( isset( $_POST['product-type'] ) && 'mto_course_recurring' === sanitize_key( $_POST['product-type'] ) ) { // phpcs:ignore
			$is_subscription_product_save_request = true;
		}
		return $is_subscription_product_save_request;
	}

	/**
	 * Print inline scripts.
	 *
	 * @since 1.8.1
	 */
	public function print_inline_scripts() {
		if ( 'product' !== get_post_type() ) {
			return;
		}
		$scripts = '
		(function($) {
			$( "div.downloadable_files" ).parent().addClass( "hide_if_mto_course hide_if_mto_course_recurring" ).hide();
			$( ".options_group.pricing" ).addClass( "show_if_mto_course" );
			$( ".options_group.pricing, ._subscription_sign_up_fee_field, ._subscription_trial_length_field" ).addClass( "hide_if_mto_course_recurring" );
			$( ".options_group.subscription_pricing" ).addClass( "show_if_mto_course_recurring" );
			if ( $( \'#product-type\' ).val() === \'mto_course_recurring\' ) {
				$(\'option[value="mto_course_recurring"]\').show();
				$(\'option[value="mto_course"]\').hide();
			} else {
				$(\'option[value="mto_course_recurring"]\').hide();
				$(\'option[value="mto_course"]\').show();
			}
		})(jQuery);
		';

		wp_print_inline_script_tag( $scripts );
	}

	/**
	 * Use simple add to cart template for Masteriyo course product type.
	 *
	 * @since 1.8.1
	 */
	public function use_simple_add_to_cart_template() {
		wc_get_template( 'single-product/add-to-cart/simple.php' );
	}

	/**
	 * Register custom course product class.
	 *
	 * @since 1.8.1
	 *
	 * @param string $class_name Class name.
	 * @param string $product_type Product type
	 * @return array
	 */
	public function register_course_product_class( $class_name, $product_type ) {
		if ( 'mto_course' === $product_type ) {
			$class_name = CourseProduct::class;
		}

		if ( 'mto_course_recurring' === $product_type && Helper::is_wc_subscriptions_active() ) {
			$class_name = \Masteriyo\Addons\WcIntegration\CourseRecurringProduct::class;
		}

		return $class_name;
	}

	/**
	 * Add course product type in the product type selector.
	 *
	 * @since 1.8.1
	 *
	 * @param array $types WooCommerce product types.
	 * @return array
	 */
	public function add_course_product_type( $types ) {
		$types['mto_course'] = __( 'Masteriyo Course', 'masteriyo' );

		if ( Helper::is_wc_subscriptions_active() ) {
			$types['mto_course_recurring'] = __( 'Masteriyo Course', 'masteriyo' );
		}

		return $types;
	}

	/**
	 * Convert WC status to Masteriyo status.
	 *
	 * @since 1.8.1
	 *
	 * @param string $status WC order status.
	 *
	 * @return string
	 */
	public function convert_wc_status( $status ) {
		$map = array(
			'processing'    => OrderStatus::PROCESSING,
			'pending'       => OrderStatus::PENDING,
			'cancelled'     => OrderStatus::CANCELLED,
			'on-hold'       => OrderStatus::ON_HOLD,
			'completed'     => OrderStatus::COMPLETED,
			'refunded'      => OrderStatus::REFUNDED,
			'failed'        => OrderStatus::FAILED,
			'wc-processing' => OrderStatus::PROCESSING,
			'wc-pending'    => OrderStatus::PENDING,
			'wc-cancelled'  => OrderStatus::CANCELLED,
			'wc-on-hold'    => OrderStatus::ON_HOLD,
			'wc-completed'  => OrderStatus::COMPLETED,
			'wc-refunded'   => OrderStatus::REFUNDED,
			'wc-failed'     => OrderStatus::FAILED,
		);

		$new_status = isset( $map[ $status ] ) ? $map[ $status ] : OrderStatus::PENDING;

		return $new_status;
	}

	/**
	 * Update user course status according to WooCommerce order status.
	 *
	 * @since 1.8.1
	 *
	 * @param int $wc_order_id WC order ID.
	 * @param string $from WC order from status.
	 * @param string $to WC order to status.
	 * @param \WC_Order $wc_order WC order object.
	 */
	public function change_order_status( $wc_order_id, $from, $to, $wc_order ) {
		if ( $from === $to ) {
			return;
		}

		// Return only WC_Order_Item_Product.
		$order_items = array_filter(
			$wc_order->get_items(),
			function( $order_item ) {
				return is_a( $order_item, 'WC_Order_Item_Product' );
			}
		);

		foreach ( $order_items as $order_item ) {
			$course = masteriyo_get_course( $order_item->get_meta( '_masteriyo_course_id' ) );

			if ( ! $course ) {
				continue;
			}

			// Get user courses.
			$query = new UserCourseQuery(
				array(
					'course_id' => $course->get_id(),
					'user_id'   => $wc_order->get_customer_id(),
				)
			);

			$user_course = current( $query->get_user_courses() );

			if ( empty( $user_course ) ) {
				continue;
			}

			if ( OrderStatus::COMPLETED === $to ) {
				$user_course->set_status( UserCourseStatus::ACTIVE );
			} elseif ( in_array( $to, $this->setting->get( 'unenrollment_status' ), true ) ) {
				$user_course->set_status( UserCourseStatus::INACTIVE );
			}

			$user_course->save();
		}
	}

	/**
	 * Change to WooCommerce Add to Cart URL.
	 *
	 * @since 1.8.1
	 *
	 * @param string $url
	 * @param Masteriyo\Models\Course $course
	 *
	 * @return string
	 */
	public function change_add_to_cart_url( $url, $course ) {
		// Bail early if WC is not active.
		if ( ! function_exists( 'wc_get_product' ) ) {
			return $url;
		}

		$product_id = get_post_meta( $course->get_id(), '_wc_product_id', true );
		$product    = wc_get_product( $product_id );

		if ( ! $product ) {
			return $url;
		}

		$url = $product->add_to_cart_url();

		if ( '' === get_option( 'permalink_structure' ) && 'no' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			$url = $product->get_permalink();
		} elseif ( '' !== get_option( 'permalink_structure' ) ) {
			$url = wp_http_validate_url( $url ) ? $url : $product->get_permalink() . $url;
		}

		return $url;
	}

	/**
	 * Register ajax handlers.
	 *
	 * @since 1.8.1
	 *
	 * @param array $handlers
	 * @return array
	 */
	public function register_ajax_handlers( $handlers ) {
		$handlers[] = ListCoursesAjaxHandler::class;

		return $handlers;
	}

	/**
	 * Save masteriyo data.
	 *
	 * @since 1.8.1
	 *
	 * @param int $product_id
	 * @param WP_Post $product
	 */
	public function save_masteriyo_data( $product_id, $product ) {
		// phpcs:disable
		if ( isset( $_POST['masteriyo_course_id'] ) ) {
			$course_id = absint( $_POST['masteriyo_course_id'] );
			$course    = masteriyo_get_course( $course_id );

			if ( $course ) {
				update_post_meta( $course_id, '_wc_product_id', $product_id );
				update_post_meta( $product_id, '_masteriyo_course_id', $course_id );
			}
		}
		//phpcs:enable
	}

	/**
	 * Add icon to masteriyo tab.
	 *
	 * @since 1.8.1
	 */
	public function add_masteriyo_tab_icon() {
		echo '<style>
			#woocommerce-product-data ul.wc-tabs li.masteriyo_options.masteriyo_tab a:before {
				content: "\1F4D6";
			}
		</style>';
	}

	/**
	 * Add masteriyo tab to product tabs.
	 *
	 * @since 1.8.1
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_masteriyo_tab( $tabs ) {
		$tabs['mto_course'] = array(
			'label'    => __( 'Course', 'masteriyo' ),
			'target'   => 'mto_course_options',
			'class'    => array( 'show_if_mto_course', 'show_if_mto_course_recurring' ),
			'priority' => 1,
		);

		// Show general in course.
		$tabs['general']['class'][] = 'show_if_simple';
		$tabs['general']['class'][] = 'show_if_external';
		$tabs['general']['class'][] = 'show_if_mto_course show_if_mto_course_recurring';

		$tabs['inventory']['class'][] = 'show_if_mto_course show_if_mto_course_recurring';

		// Hide shipping attributes.
		$tabs['shipping']['class'][]  = 'hide_if_mto_course hide_if_mto_course_recurring';
		$tabs['attribute']['class'][] = 'hide_if_mto_course hide_if_mto_course_recurring';
		$tabs['advanced']['class'][]  = 'hide_if_mto_course hide_if_mto_course_recurring';

		return $tabs;
	}

	/**
	 * Display masteriyo tab content.
	 *
	 * @since 1.8.1
	 */
	public function display_masteriyo_tab_content() {
		if ( ! function_exists( 'woocommerce_wp_select' ) ) {
			return;
		}

		$options   = array(
			'' => esc_html__( 'Please select a course', 'masteriyo' ),
		);
		$course_id = get_post_meta( get_the_ID(), '_masteriyo_course_id', true );
		$course    = masteriyo_get_course( $course_id );

		if ( $course ) {
			$options[ $course_id ] = $course->get_name();
		}

		echo '<div id="mto_course_options" class="panel woocommerce_options_panel hidden">';

		\woocommerce_wp_select(
			array(
				'id'                => 'masteriyo_course_id',
				'value'             => $course_id,
				'wrapper_class'     => 'show_if_mto_course show_if_mto_course_recurring',
				'label'             => esc_html__( 'Course', 'masteriyo' ),
				'desc_tip'          => true,
				'description'       => esc_html__( 'Select a course to connect with the product.', 'masteriyo' ),
				'options'           => $options,
				'custom_attributes' => array(
					'data-course-access-mode' => $course ? $course->get_access_mode() : '',
				),
			)
		);

		echo '</div>';
	}

	/**
	 * Enqueue necessary scripts.
	 *
	 * @since 1.8.1
	 *
	 * @param array $scripts
	 * @return array
	 */
	public function enqueue_scripts( $scripts ) {
		$scripts['wc-integration'] = array(
			'src'      => plugin_dir_url( MASTERIYO_WC_INTEGRATION_ADDON_FILE ) . '/assets/js/wc-integration.js',
			'context'  => 'admin',
			'deps'     => array( 'selectWoo' ),
			'callback' => function() {
				return $this->is_wc_product_add_page() || $this->is_wc_product_edit_page();
			},
		);

		return $scripts;
	}

	/**
	 * Localize admin scripts.
	 *
	 * @since 1.8.1
	 *
	 * @param array $scripts
	 * @return array
	 */
	public function localize_admin_scripts( $scripts ) {
		$scripts['wc-integration'] = array(
			'name' => '_MASTERIYO_WC_INTEGRATION_',
			'data' => array(
				'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
				'nonces'                 => array(
					'listCourses' => wp_create_nonce( 'masteriyo_wc_integration_list_courses' ),
				),
				'isWCSubscriptionActive' => Helper::is_wc_subscriptions_active(),
			),
		);

		return $scripts;
	}

	/**
	 * Return true if the page is WC product add page.
	 *
	 * @since 1.8.1
	 *
	 * @return boolean
	 */
	public function is_wc_product_add_page() {
		global $pagenow, $typenow;

		if ( 'post-new.php' === $pagenow && 'product' === $typenow ) {
			return true;
		}

		return false;
	}

	/**
	 * Return true if the page is WC product edit page.
	 *
	 * @since 1.8.1
	 *
	 * @return boolean
	 */
	public function is_wc_product_edit_page() {
		global $pagenow, $typenow;

		if ( 'post.php' === $pagenow && 'product' === $typenow ) {
			return true;
		}

		return false;
	}

	/**
	 * Create Masteriyo order when WooCommerce order is created.
	 *
	 * @since 1.8.1
	 *
	 * @param int $wc_order_id
	 */
	public function create_user_course( $wc_order_id ) {
		// Bail early if WC is not active.
		if ( ! ( function_exists( 'wc_get_product' ) && function_exists( 'wc_get_order' ) ) ) {
			return;
		}

		$wc_order = wc_get_order( $wc_order_id );

		// Return only WC_Order_Item_Product.
		$order_items = array_filter(
			$wc_order->get_items(),
			function( $order_item ) {
				return is_a( $order_item, 'WC_Order_Item_Product' );
			}
		);

		foreach ( $order_items as $order_item ) {
			$product = wc_get_product( $order_item->get_product_id() );

			// Bail early if product doesn't exist.
			if ( ! $product ) {
				continue;
			}

			$course = masteriyo_get_course( $product->get_meta( '_masteriyo_course_id', true ) );

			// Bail early if course doesn't exist.
			if ( ! $course ) {
				continue;
			}

			// Save course id in the order item as meta.
			$order_item->update_meta_data( '_masteriyo_course_id', $course->get_id() );
			$order_item->save_meta_data();

			// Get user courses.
			$query = new UserCourseQuery(
				array(
					'course_id' => $course->get_id(),
					'user_id'   => $wc_order->get_customer_id(),
				)
			);

			$user_courses = $query->get_user_courses();
			$user_course  = empty( $user_courses ) ? masteriyo( 'user-course' ) : current( $user_courses );

			$user_course->set_course_id( $course->get_id() );
			$user_course->set_user_id( $wc_order->get_customer_id() );
			$user_course->set_price( $product->get_price() );

			if ( OrderStatus::COMPLETED === $wc_order->get_status() ) {
				$user_course->set_status( UserCourseStatus::ACTIVE );
				$user_course->set_date_start( current_time( 'mysql', true ) );
			} elseif ( in_array( $wc_order->get_status(), $this->setting->get( 'unenrollment_status' ), true ) ) {
				$user_course->set_status( UserCourseStatus::INACTIVE );
				$user_course->set_date_start( null );
				$user_course->set_date_modified( null );
				$user_course->set_date_end( null );
			}

			$user_course->save();

			if ( $user_course->get_id() ) {
				$user_course->update_meta_data( '_wc_order_id', $wc_order_id );
				$user_course->save_meta_data();
			}
		}
	}

	/**
	 * Update masteriyo_can_start_course() for course connected with WC product.
	 *
	 * @since 1.8.1
	 *
	 * @param bool $can_start_course Whether user can start the course.
	 * @param \Masteriyo\Models\Course $course Course object.
	 * @param \Masteriyo\Models\User $user User object.
	 * @return boolean
	 */
	public function update_can_start_course( $can_start_course, $course, $user ) {
		// Bail early if WC is not active.
		if ( ! function_exists( 'wc_get_product' ) ) {
			return;
		}

		if ( ! $course ) {
			return;
		}

		$product = wc_get_product( $course->get_meta( '_wc_product_id' ) );

		if ( ! $product ) {
			return $can_start_course;
		}

		// Bail early if the course is open
		if ( CourseAccessMode::OPEN === $course->get_access_mode() ) {
			return $can_start_course;
		}

		// Bail early iif the user is not logged in
		if ( ! is_user_logged_in() ) {
			return $can_start_course;
		}

		$query = new UserCourseQuery(
			array(
				'course_id' => $course->get_id(),
				'user_id'   => $user->get_id(),
				'per_page'  => 1,
			)
		);

		$user_course = current( $query->get_user_courses() );

		if ( empty( $user_course ) ) {
			return $can_start_course;
		}

		$wc_order_id = $user_course->get_meta( '_wc_order_id' );
		$wc_order    = wc_get_order( $wc_order_id );

		if ( ! $wc_order ) {
			return $can_start_course;
		}

		if ( CourseAccessMode::RECURRING === $course->get_access_mode() ) {
			$subscription = function_exists( 'wcs_get_subscriptions_for_order' ) ? current( wcs_get_subscriptions_for_order( $wc_order_id ) ) : false;
			if ( ! $subscription ) {
				return $can_start_course;
			}
			$can_start_course = SubscriptionStatus::ACTIVE === $subscription->get_status();
		} else {
			$can_start_course = OrderStatus::COMPLETED === $wc_order->get_status();
		}

		return $can_start_course;
	}

	/**
	 * Add student role to WC customer.
	 *
	 * @since 1.8.1
	 *
	 * @param int $user_id User ID.
	 */
	public function add_student_role_to_wc_customer( $user_id ) {
		remove_action( 'profile_update', array( $this, 'add_student_role_to_wc_customer' ) );
		remove_action( 'user_register', array( $this, 'add_student_role_to_wc_customer' ) );

		try {
			$user  = masteriyo( 'user' );
			$store = masteriyo( 'user.store' );

			$user->set_id( $user_id );
			$store->read( $user );

			if ( $user->has_role( 'customer' ) && ! $user->has_role( 'masteriyo_student' ) ) {
				$user->add_role( 'masteriyo_student' );
				$user->save();
			}
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
		}

		add_action( 'profile_update', array( $this, 'add_student_role_to_wc_customer' ) );
		add_action( 'user_register', array( $this, 'add_student_role_to_wc_customer' ) );
	}
}
