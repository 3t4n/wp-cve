<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Vite_App;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the Upsell module logic.
 * Public Model.
 *
 * @since 1.0
 */
class Upsell implements Model_Interface, Initializable_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 1.0
     * @access private
     * @var Cart_Conditions
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 1.0
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 1.0
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 1.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 1.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Cart_Conditions
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $main_plugin, $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /*
    |--------------------------------------------------------------------------
    | Side metabox.
    |--------------------------------------------------------------------------
     */

    /**
     * Register upsell metabox.
     *
     * @since 1.0
     * @since 1.1 Add auto apply metabox upsell.
     * @access public
     *
     * @param string  $post_type Post type.
     * @param WP_Post $post      Post object.
     */
    public function register_upsell_metabox( $post_type, $post ) {
        if ( 'shop_coupon' !== $post_type ) {
            return;
        }

        add_meta_box(
            'acfw-auto-apply-coupon',
            __( 'Auto Apply Coupon (premium)', 'advanced-coupons-for-woocommerce-free' ),
            array( $this, 'display_auto_apply_upsell_metabox' ),
            'shop_coupon',
            'side'
        );

        add_meta_box(
            'acfw-virtual-coupon',
            __( 'Virtual Coupons (premium)', 'advanced-coupons-for-woocommerce-free' ),
            array( $this, 'display_virtual_coupons_upsell_metabox' ),
            'shop_coupon',
            'side'
        );

        add_meta_box(
            'acfw-premium-upsell',
            __( 'Upgrade to premium', 'advanced-coupons-for-woocommerce-free' ),
            array( $this, 'display_upsell_metabox' ),
            'shop_coupon',
            'side',
            'low'
        );
    }

    /**
     * Display upsell metabox content.
     *
     * @since 1.0
     * @access public
     *
     * @param \WP_Post $post Post object.
     */
    public function display_upsell_metabox( $post ) {
        $link = apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=sidebar' );
        echo wp_kses_post(
            '<a href="' . $link . '" target="_blank">
        <img style="margin-left: -12px;" src="' . $this->_constants->IMAGES_ROOT_URL . '/premium-add-on-sidebar.png" alt="Advanced Coupons Premium" />
        </a>'
        );
    }

    /**
     * Display  auto apply upsell metabox content.
     *
     * @since 1.0
     * @access public
     *
     * @param \WP_Post $post Post object.
     */
    public function display_auto_apply_upsell_metabox( $post ) {
        echo '<label>
            <input id="acfw_auto_apply_coupon_field" type="checkbox" value="yes">
            ' . esc_html( __( 'Enable auto apply for this coupon.', 'advanced-coupons-for-woocommerce-free' ) ) . '
        </label>';
    }

    /**
     * Display virtual coupons upsell metabox.
     *
     * @since 4.3.2
     * @access public
     *
     * @param \WP_Post $post Post object.
     */
    public function display_virtual_coupons_upsell_metabox( $post ) {
        echo wp_kses_post( sprintf( '<p class="description">%s</p>', __( 'Virtual coupons are other codes that are also valid for this coupon. It’s great when you need lots of unique codes for the same deal.', 'advanced-coupons-for-woocommerce-free' ) ) );

        echo '<label>
            <input id="acfw_enable_virtual_coupons" type="checkbox" value="yes">
            ' . esc_html( __( 'Enable virtual coupons', 'advanced-coupons-for-woocommerce-free' ) ) . '
        </label>';
    }

    /*
    |--------------------------------------------------------------------------
    | WooCommerce coupons metabox panels.
    |--------------------------------------------------------------------------
     */

    /**
     * Register upsell panels in WooCommerce coupons metabox.
     *
     * @since 1.0
     * @access public
     *
     * @param array $panels Array of coupon metabox panels.
     * @return array Modified array of coupon metabox panels.
     */
    public function register_upsell_panels( $panels ) {

        $is_role_restrictions = isset( $panels['acfw_role_restrictions'] );
        $filtered_panels      = array();
        $upsell_panels        = array(
            'acfw_add_products'                => array(
                'label'  => __( 'Add Products (Premium)', 'advanced-coupons-for-woocommerce-free' ),
                'target' => 'acfw_add_products',
                'class'  => '',
            ),
            'acfw_payment_method_restrictions' => array(
                'label'  => __( 'Payment Methods Restriction (Premium)', 'advanced-coupons-for-woocommerce-free' ),
                'target' => 'acfw_payment_methods_restriction',
                'class'  => '',
            ),
            'acfw_shipping_overrides'          => array(
                'label'  => __( 'Shipping Overrides (Premium)', 'advanced-coupons-for-woocommerce-free' ),
                'target' => 'acfw_shipping_overrides',
                'class'  => '',
            ),
            'acfw_apply_notification'          => array(
                'label'  => __( 'One Click Apply (Premium)', 'advanced-coupons-for-woocommerce-free' ),
                'target' => 'acfw_apply_notification',
                'class'  => '',
            ),
        );

        // try to add panels on optimal locations.
        foreach ( $panels as $key => $panel ) {

            $filtered_panels[ $key ] = $panel;

            // add panels after BOGO Deals.
            if ( 'usage_limit' === $key ) {
                $filtered_panels['acfw_add_products'] = $upsell_panels['acfw_add_products'];

                if ( ! $is_role_restrictions ) {
                    $filtered_panels['acfw_payment_method_restrictions'] = $upsell_panels['acfw_payment_method_restrictions'];
                }
            }

            // add panels after Role Restrictions.
            if ( $is_role_restrictions && 'acfw_role_restrictions' === $key ) {
                $filtered_panels['acfw_payment_method_restrictions'] = $upsell_panels['acfw_payment_method_restrictions'];
            }
        }

        // add all other panels, and ones that' weren't added due to the set previous module for it is inactive.
        foreach ( $upsell_panels as $key => $panel ) {
            if ( ! isset( $filtered_panels[ $key ] ) ) {
                $filtered_panels[ $key ] = $panel;
            }
        }

        return $filtered_panels;
    }

    /**
     * Display upsell panel views.
     *
     * @since 1.0
     * @access public
     *
     * @param int $coupon_id WC_Coupon ID.
     */
    public function display_upsell_panel_views( $coupon_id ) {
        include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-add-products-panel.php';
        include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-payment-methods-restriction-panel.php';
        include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-apply-notifications-panel.php';
        include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-shipping-overrides-panel.php';
    }

    /**
     * Display did you know notice under general tab in coupon editor.
     *
     * @since 1.6
     * @access public
     *
     * @param int $coupon_id Coupon ID.
     */
    public function display_did_you_know_notice_in_general( $coupon_id ) {
        \ACFWF()->Notices->display_did_you_know_notice(
            array(
				'classname'   => 'acfw-dyk-notice-general',
				'description' => __( 'You can unlock even more advanced coupon types & features.', 'advanced-coupons-for-woocommerce-free' ),
				'button_link' => 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=generaltabtiplink',
            )
        );
    }

    /**
     * Display did you know notice under ACFW generic panels.
     *
     * @since 1.6
     * @access public
     *
     * @param string $panel_id Coupon ID.
     */
    public function display_did_you_know_notice_in_generic_panel( $panel_id ) {
        if ( 'acfw_url_coupon' === $panel_id ) {
            \ACFWF()->Notices->display_did_you_know_notice(
                array(
					'classname'   => 'acfw-dyk-notice-url-coupons',
					'description' => __( 'You can also use auto apply or one-click apply notifications to apply coupons without manually typing.', 'advanced-coupons-for-woocommerce-free' ),
					'button_link' => 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=urlcouponstiplink',
                )
            );
        }
    }

    /**
     * Advanced usage limits fields.
     *
     * @since 1.1
     * @access public
     *
     * @param int $coupon_id Coupon ID.
     */
    public function upsell_advanced_usage_limits_fields( $coupon_id ) {
        woocommerce_wp_select(
            array(
				'id'          => 'reset_usage_limit_period',
				'label'       => __( 'Reset usage count every:', 'advanced-coupons-for-woocommerce-free' ),
				'options'     => array(
					'none'    => __( 'Never reset', 'advanced-coupons-for-woocommerce-free' ),
					'yearly'  => __( 'Every year (premium)', 'advanced-coupons-for-woocommerce-free' ),
					'monthly' => __( 'Every month (premium)', 'advanced-coupons-for-woocommerce-free' ),
					'weekly'  => __( 'Every week (premium)', 'advanced-coupons-for-woocommerce-free' ),
					'daily'   => __( 'Every day (premium)', 'advanced-coupons-for-woocommerce-free' ),
				),
				'description' => __( 'Set the time period to reset the usage limit count. <strong>Yearly:</strong> resets at start of the year. <strong>Monthly:</strong> resets at start of the month. <strong>Weekly:</strong> resets at the start of every week (day depends on the <em>"Week Starts On"</em> setting). <strong>Daily:</strong> resets everyday. Time is always set at 12:00am of the local timezone settings.', 'advanced-coupons-for-woocommerce-free' ),
				'desc_tip'    => true,
				'value'       => 'none',
            )
        );

        \ACFWF()->Notices->display_did_you_know_notice(
            array(
				'classname'   => 'acfw-dyk-notice-usage-limit',
				'description' => __( 'You can reset usage limits on a timer either daily, weekly, monthly, or yearly.', 'advanced-coupons-for-woocommerce-free' ),
				'button_link' => 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=usagelimitstiplink',
            )
        );
    }

    /**
     * Add exclude coupon upsell field in usage restrictions tab.
     *
     * @since 1.1
     * @access public
     *
     * @param int $coupon_id Coupon ID.
     */
    public function uspell_exclude_coupons_restriction( $coupon_id ) {
        woocommerce_wp_select(
            array(
				'id'                => 'acfw_exclude_coupons',
				'class'             => 'wc-product-search',
				'style'             => 'width:50%;',
				'label'             => __( 'Exclude coupons (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description'       => __( 'This is the advanced version of the "Individual use only" field. Coupons listed here cannot be used in conjunction with this coupon.', 'advanced-coupons-for-woocommerce-free' ),
				'desc_tip'          => true,
				'options'           => array(),
				'custom_attributes' => array(
					'multiple'         => true,
					'data-placeholder' => __( 'Search coupons&hellip;', 'advanced-coupons-for-woocommerce-free' ),
					'data-action'      => 'acfw_search_coupons',
				),
            )
        );
    }

    /**
     * Add allowed custmers upsell field in usage restrictions tab.
     *
     * @since 4.2.1
     * @access public
     *
     * @param int $coupon_id Coupon ID.
     */
    public function upsell_allowed_customers_restriction( $coupon_id ) {
        woocommerce_wp_select(
            array(
				'id'                => 'acfw_allowed_customers',
				'class'             => 'wc-product-search',
				'style'             => 'width:50%;',
				'label'             => __( 'Allowed customers (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description'       => __( 'Search and select customers that are eligible to only use this coupon.', 'advanced-coupons-for-woocommerce-free' ),
				'desc_tip'          => true,
				'options'           => array(),
				'custom_attributes' => array(
					'multiple'         => true,
					'data-placeholder' => __( 'Search customers&hellip;', 'advanced-coupons-for-woocommerce-free' ),
					'data-action'      => 'acfw_search_coupons',
					'readonly'         => true,
				),
            )
        );
    }

    /**
     * Cart condition premium field options upsell.
     *
     * @since 1.0
     * @since 1.5 Changed filter to 'acfw_condition_fields_localized_data'
     * @access public
     *
     * @param array $options Field options list.
     * @return array Filtered field options list.
     */
    public function cart_condition_premium_field_options( $options = array() ) {
        $premium = array(
            'cart_weight'                               => array(
                'group' => 'cart-items',
                'key'   => 'cart-weight',
                'title' => __( 'Cart Weight (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'product_quantity'                          => array(
                'group' => 'products',
                'key'   => 'product-quantity',
                'title' => __( 'Product Quantities Exists In Cart (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'customer_registration_date'                => array(
                'group' => 'customers',
                'key'   => 'customer-registration-date',
                'title' => __( 'Within Hours After Customer Registered (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'customer_last_ordered'                     => array(
                'group' => 'customers',
                'key'   => 'customer-last-ordered',
                'title' => __( 'Within Hours After Customer Last Order (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'total_customer_spend'                      => array(
                'group' => 'customers',
                'key'   => 'total-customer-spend',
                'title' => __( 'Total Customer Spend (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'total_customer_spend_on_product_category'  => array(
                'group' => 'product-categories',
                'key'   => 'total-customer-spend-on-product-category',
                'title' => __( 'Total Customer Spend on a certain product category (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'product_stock_availability_exists_in_cart' => array(
                'group' => 'products',
                'key'   => 'product-stock-availability-exists-in-cart',
                'title' => __( 'Product Stock Availability Exists In Cart (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'has_ordered_before'                        => array(
                'group' => 'products',
                'key'   => 'has-ordered-before',
                'title' => __( 'Customer Has Ordered Products Before (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'has_ordered_product_categories_before'     => array(
                'group' => 'product-categories',
                'key'   => 'has-ordered-product-categories-before',
                'title' => __( 'Has Ordered Product Categories Before (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'shipping_zone_region'                      => array(
                'group' => 'customers',
                'key'   => 'shipping-zone-region',
                'title' => __( 'Shipping Zone And Region (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'custom_taxonomy'                           => array(
                'group' => 'product-categories',
                'key'   => 'custom-taxonomy',
                'title' => __( 'Custom Taxonomy Exists In Cart (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'custom_user_meta'                          => array(
                'group' => 'advanced',
                'key'   => 'custom-user-meta',
                'title' => __( 'Custom User Meta (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'custom_cart_item_meta'                     => array(
                'group' => 'advanced',
                'key'   => 'custom-cart-item-meta',
                'title' => __( 'Custom Cart Item Meta (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            ),
        );

        // add WC Membership cart conditions when plugin is active.
        if ( $this->_helper_functions->is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
            $premium = array_merge(
                $premium,
                array(
					'wc_memberships_allowed'    => array(
						'group' => 'customers',
						'key'   => 'wc-memberships-allowed',
						'title' => __( 'WC Memberships: Allowed Membership Plans (Premium)', 'advanced-coupons-for-woocommerce-free' ),
					),
					'wc_memberships_disallowed' => array(
						'group' => 'customers',
						'key'   => 'wc-memberships-disallowed',
						'title' => __( 'WC Memberships: Disallowed Membership Plans (Premium)', 'advanced-coupons-for-woocommerce-free' ),
					),
                )
            );
        }

        return array_merge( $options, $premium );
    }

    /**
     * Register more cart conditions tab.
     *
     * @since 1.6
     * @access public
     *
     * @param array $tabs Cart condition panel tabs.
     * @return array Filtered cart condition panel tabs.
     */
    public function register_more_cart_conditions_tab( $tabs ) {
        $tabs['moreconditions'] = __( 'More Cart Conditions (Premium)', 'advanced-coupons-for-woocommerce-free' );
        return $tabs;
    }

    /**
     * Display more cart conditions panel.
     *
     * @since 1.6
     * @access public
     */
    public function display_more_cart_conditions_panel() {
        $cart_conditions = array(
			array(
				'title'       => __( 'Product Quantity In The Cart (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'Check for the product (or products) and measure their quantity to see if the customer is eligible to use that coupon based on that.', 'advanced-coupons-for-woocommerce-free' ),
			),
			array(
				'title'       => __( 'Custom Taxonomy In The Cart (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'If you have a custom taxonomy on Products, for example “Brands”, this would let you check on those before applying a coupon.', 'advanced-coupons-for-woocommerce-free' ),
			),
			array(
				'title'       => __( 'Within Hours After Customer Registered (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'It can be useful to check when a customer was registered on your store before applying a coupon.', 'advanced-coupons-for-woocommerce-free' ),
			),
			array(
				'title'       => __( 'Within Hours After Customer Last Order (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'Restrict a coupon for use within a certain time period to encourage a follow-up order.', 'advanced-coupons-for-woocommerce-free' ),
			),
			array(
				'title'       => __( 'Total Customer Spend (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'Create a coupon that is only allowed if they’ve spent a certain historical amount.', 'advanced-coupons-for-woocommerce-free' ),
			),
            array(
                'title'       => __( 'Product Stock Availability Exists In Cart (Premium)', 'advanced-coupons-for-woocommerce-free' ),
                'description' => __( 'Create a coupon that checks if cart quantity matches product stock to prevent overselling and stock management issues.', 'advanced-coupons-for-woocommerce-free' ),
            ),
			array(
				'title'       => __( 'Has Ordered Before (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'Check if a customer has ordered something before letting them apply a coupon.', 'advanced-coupons-for-woocommerce-free' ),
			),
            array(
                'title'       => __( 'Has Ordered Product Categories Before (Premium)', 'advanced-coupons-for-woocommerce-free' ),
                'description' => __( 'Create a coupon that checks if customer has ordered products from specified categories before to enable targeted marketing.', 'advanced-coupons-for-woocommerce-free' ),
            ),
			array(
				'title'       => __( 'Custom User Meta (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'Great for developers, test any extra user metadata on customer user records before applying a coupon.', 'advanced-coupons-for-woocommerce-free' ),
			),
			array(
				'title'       => __( 'Custom Cart Item Meta (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'Great for developers, in specific situations where custom cart item meta has been added and you need to target a coupon for that.', 'advanced-coupons-for-woocommerce-free' ),
			),
			array(
				'title'       => __( 'Shipping Zone And Region (Premium)', 'advanced-coupons-for-woocommerce-free' ),
				'description' => __( 'Restricting coupons based on the shipping zone is great when you need to apply coupons geographically.', 'advanced-coupons-for-woocommerce-free' ),
			),
		);

        // add WC Membership cart conditions when plugin is active.
        if ( $this->_helper_functions->is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
            $cart_conditions = array_merge(
                $cart_conditions,
                array(
					array(
						'title'       => __( 'WC Memberships: Allowed Membership Plans (Premium)', 'advanced-coupons-for-woocommerce-free' ),
						'description' => __( 'Restrict the coupon to be only applied to customers that are members of the specified membership plan(s).', 'advanced-coupons-for-woocommerce-free' ),
					),
					array(
						'title'       => __( 'WC Memberships: Disallowed Membership Plans (Premium)', 'advanced-coupons-for-woocommerce-free' ),
						'description' => __( 'Restrict the coupon to be only applied to customers that are not a member of the specified membership plan(s).', 'advanced-coupons-for-woocommerce-free' ),
					),
                )
            );
        }

        include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-more-cart-conditions-panel.php';
    }

    /**
     * Register did you know notice html attribute in cart conditions panel.
     *
     * @since 1.6
     * @access public
     *
     * @param array $atts List of attributes.
     */
    public function register_dyk_notice_html_attribute( $atts ) {
        $atts['premium-conditions'] = array_column( $this->cart_condition_premium_field_options(), 'key' );
        return $atts;
    }

    /**
     * BOGO Deals premium trigger and apply type descriptions.
     *
     * @since 1.0
     * @access public
     *
     * @param array $descs Descriptions.
     * @return array Filtered descriptions.
     */
    public function bogo_premium_trigger_apply_type_descs( $descs ) {
        $link    = sprintf( '<a href="%s" target="_blank" rel="noreferer noopener">%s</a>', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=bogodescriptionlink', __( 'Premium', 'advanced-coupons-for-woocommerce-free' ) );
        $premium = array(
            /* Translators: %s: Premium link markup */
            'combination-products' => sprintf( __( 'Combination of Products (%s) – good when dealing with variable products or multiple products', 'advanced-coupons-for-woocommerce-free' ), $link ),
            /* Translators: %s: Premium link markup */
            'product-categories'   => sprintf( __( 'Product Categories (%s) – good when you want to trigger or apply a range of products from a particular category or set of categories', 'advanced-coupons-for-woocommerce-free' ), $link ),
            /* Translators: %s: Premium link markup */
            'any-products'         => sprintf( __( 'Any Products (%s) – good when you want to trigger or apply all of the products present in the cart', 'advanced-coupons-for-woocommerce-free' ), $link ),
        );

        return array_merge( $descs, $premium );
    }

    /**
     * Get trigger and apply options.
     *
     * @since 2.6
     * @access private
     *
     * @param bool $is_apply Is apply flag.
     * @return array List of options.
     */
    private function _get_trigger_apply_options( $is_apply = false ) {
        $options = array(
            'combination-products' => __( 'Any Combination of Products (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            'product-categories'   => __( 'Product Categories (Premium)', 'advanced-coupons-for-woocommerce-free' ),
            'any-products'         => __( 'Any Products (Premium)', 'advanced-coupons-for-woocommerce-free' ),
        );

        return $options;
    }

    /**
     * BOGO Deals premium trigger type options.
     *
     * @since 1.0
     * @access public
     *
     * @param array $options Field options list.
     * @return array Filtered field options list.
     */
    public function bogo_premium_trigger_type_options( $options ) {
        return array_merge( $options, $this->_get_trigger_apply_options() );
    }

    /**
     * BOGO Deals premium trigger type options.
     *
     * @since 1.0
     * @access public
     *
     * @param array $options Field options list.
     * @return array Filtered field options list.
     */
    public function bogo_premium_apply_type_options( $options ) {
        return array_merge( $options, $this->_get_trigger_apply_options( true ) );
    }

    /**
     * Upsell BOGO automatically add deal products feature.
     *
     * @since 4.1
     * @access public
     *
     * @param array $bogo_deals Coupon BOGO Deals data.
     */
    public function upsell_automatically_add_deal_products_feature( $bogo_deals ) {
        $deals_type = isset( $bogo_deals['deals_type'] ) ? $bogo_deals['deals_type'] : 'specific-products';

        include $this->_constants->VIEWS_ROOT_PATH . 'premium/view-coupon-bogo-additional-settings.php';
    }

    /**
     * Display the day/time scheduler user interface upsell.
     *
     * @since 3.5
     * @access public
     *
     * @param Advanced_Coupon $coupon Coupon object.
     */
    public function display_day_time_scheduler_ui_upsell( $coupon ) {
        $day_time_fields = array(
            'monday'    => __( 'Monday', 'advanced-coupons-for-woocommerce-free' ),
            'tuesday'   => __( 'Tuesday', 'advanced-coupons-for-woocommerce-free' ),
            'wednesday' => __( 'Wednesday', 'advanced-coupons-for-woocommerce-free' ),
            'thursday'  => __( 'Thursday', 'advanced-coupons-for-woocommerce-free' ),
            'friday'    => __( 'Friday', 'advanced-coupons-for-woocommerce-free' ),
            'saturday'  => __( 'Saturday', 'advanced-coupons-for-woocommerce-free' ),
            'sunday'    => __( 'Sunday', 'advanced-coupons-for-woocommerce-free' ),
        );

        include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-daytime-schedules-panel.php';
    }

    /**
     * Display the admin notice bar upsell.
     *
     * @since 4.5.4
     * @access public
     */
    public function display_admin_notice_bar_lite() {

        $screen = get_current_screen();

        // Don't proceed when viewing non coupon related page, or when viewing the coupons list screen.
        if ( ! $this->_helper_functions->is_advanced_coupons_screen() || 'edit' === $screen->base ) {
            return;
        }

        include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-admin-notice-bar-lite.php';
    }

    /**
     * Register cashback coupon types upsell.
     *
     * @since 4.5.6
     * @access public
     *
     * @param array $types Coupon types.
     * @return array Filtered coupon types.
     */
    public function upsell_cashback_coupon_types( $types ) {
        $types['acfw_percentage_cashback'] = __( 'Percentage cashback (premium)', 'advanced-coupons-for-woocommerce-free' );
        $types['acfw_fixed_cashback']      = __( 'Fixed cashback (premium)', 'advanced-coupons-for-woocommerce-free' );

        return $types;
    }

    /*
    |--------------------------------------------------------------------------
    | Settings.
    |--------------------------------------------------------------------------
     */

    /**
     * Register upsell settings section.
     *
     * @since 1.0
     * @since 1.1 Add license placeholder settings page.
     * @access public
     *
     * @param array $sections ACFW settings sections.
     * @return array Filtered ACFW settings sections.
     */
    public function register_upsell_settings_section( $sections ) {
        $sections['acfw_slmw_settings_section'] = __( 'License', 'advanced-coupons-for-woocommerce-free' );
        $sections['acfw_premium']               = __( 'Upgrade', 'advanced-coupons-for-woocommerce-free' );

        return $sections;
    }

    /**
     * Get upsell settings section fields.
     *
     * @since 1.0
     * @since 1.1 Add display for license placeholder settings page.
     * @access public
     *
     * @param array  $settings        List of settings fields.
     * @param string $current_section Current section id.
     */
    public function get_upsell_settings_section_fields( $settings, $current_section ) {
        if ( ! in_array( $current_section, array( 'acfw_premium', 'acfw_slmw_settings_section' ), true ) ) {
            return $settings;
        }

        // hide save changes button.
        $GLOBALS['hide_save_button'] = true;

        $settings = array(
            array(
                'title' => '',
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_upsell_main_title',
            ),
        );

        // display premium upsell content.
        if ( 'acfw_premium' === $current_section ) {
            $settings[] = array(
                'type' => 'acfw_premium',
                'id'   => 'acfw_premium_content',
            );
        }

        // display license upsell content.
        if ( 'acfw_slmw_settings_section' === $current_section ) {
            $settings[] = array(
                'type' => 'acfw_license_placeholder',
                'id'   => 'acfw_license_placeholder_content',
            );
        }

        $settings[] = array(
            'type' => 'sectionend',
            'id'   => 'acfw_upsell_end',
        );

        return $settings;
    }

    /**
     * Append did you know notice data on BOGO Deals settings.
     *
     * @since 1.6
     * @access public
     *
     * @param array $settings Setting fields array.
     */
    public function bogo_settings_append_dyk_notice( $settings ) {
        $filtered = array();
        foreach ( $settings as $setting ) {
            $filtered[] = $setting;
            if ( Plugin_Constants::BOGO_DEALS_NOTICE_TYPE === $setting['id'] ) {
                $filtered[] = array(
                    'title'      => '',
                    'type'       => 'notice',
                    'id'         => 'acfw_bogo_dyk_notice',
                    'noticeData' => \ACFWF()->Notices->display_did_you_know_notice(
                        array(
                            'description' => __( 'You can apply BOGO deals on combinations of products, product categories, or even on any product in the store.', 'advanced-coupons-for-woocommerce-free' ),
                            'button_link' => 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=settingsbogotip',
                        ),
                        true
                    ),
                );
            }
        }

        return $filtered;
    }

    /**
     * Register premium modules.
     *
     * @since 1.6
     * @access public
     *
     * @param array $modules Modules settings list.
     * @return array Filtered modules settings list.
     */
    public function register_premium_modules_settings( $modules ) {

        $modules[] = array(
            'title'   => __( 'Auto Apply', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( "Have your coupon automatically apply once it's able to be applied.", 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::AUTO_APPLY_MODULE,
            'default' => 'yes',
        );

        $modules[] = array(
            'title'   => __( 'Advanced Usage Limits', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( 'Improves the usage limits feature of coupons, allowing you to set a time period to reset the usage counts.', 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::USAGE_LIMITS_MODULE,
            'default' => 'yes',
        );

        $modules[] = array(
            'title'   => __( 'Shipping Overrides', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( 'Lets you provide coupons that can discount shipping prices for any shipping method.', 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::SHIPPING_OVERRIDES_MODULE,
            'default' => 'yes',
        );

        $modules[] = array(
            'title'   => __( 'Add Products', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( 'On application of the coupon add certain products to the cart automatically after applying coupon.', 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::ADD_PRODUCTS_MODULE,
            'default' => 'yes',
        );

        $modules[] = array(
            'title'   => __( 'One Click Apply Notification', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( 'Lets you show a WooCommerce notice to a customer if the coupon is able to be applied with a button to apply it.', 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::APPLY_NOTIFICATION_MODULE,
            'default' => 'yes',
        );

        $modules[] = array(
            'title'   => __( 'Payment Methods Restriction', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( 'Restrict coupons to be used by certain payment method gateways only.', 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::PAYMENT_METHODS_RESTRICT,
            'default' => 'yes',
        );

        $modules[] = array(
            'title'   => __( 'Sort Coupons in Cart', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( 'Set priority for each coupon and automatically sort the applied coupons on cart/checkout. This will also sort coupons under auto apply and apply notifications.', 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::SORT_COUPONS_MODULE,
            'default' => '',
        );

        $modules[] = array(
            'title'   => __( 'Virtual Coupons', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'premiummodule',
            'desc'    => __( "Bulk generate 100's or 1000's of unique alternative coupon codes for a coupon to use in welcome sequences, abandoned cart sequences, and other scenarios.", 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::VIRTUAL_COUPONS_MODULE,
            'default' => '',
        );

        return $modules;
    }

    /**
     * Register upsell modal in settings localized data.
     *
     * @since 1.6
     * @access public
     *
     * @param array $data Localized data.
     * @return array Filtered localized data.
     */
    public function register_upsell_modal_settings_localized_data( $data ) {
        $data['upsellModal'] = array(
            'title'     => __( 'Premium Module', 'advanced-coupons-for-woocommerce-free' ),
            'content'   => array(
                __( 'You are currently using Advanced Coupons for WooCommerce (Free Version). This module is only available for Premium license holders.', 'advanced-coupons-for-woocommerce-free' ),
                __( 'Upgrade to premium today and gain access to this module & more!', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'buttonTxt' => __( 'Upgrade to Premium', 'advanced-coupons-for-woocommerce-free' ),
        );

        return $data;
    }

    /**
     * Register general license field.
     *
     * @since 1.6
     * @since 4.5.1 Move license field to the top.
     * @access public
     *
     * @param array $settings Setting fields.
     * @return array Filtered setting fields.
     */
    public function register_general_license_field( $settings ) {
        $section_title = $settings[0];

        // Remove the section title from the list of fields.
        unset( $settings[0] );

        // Add back the section title and then add the license field.
        array_unshift(
            $settings,
            $section_title,
            array(
                'title'          => __( 'License', 'advanced-coupons-for-woocommerce-free' ),
                'type'           => 'acfwflicense',
                'id'             => 'acfwf_license_field',
                'licenseContent' => array(
                    __( "You're using Advanced Coupons for WooCommerce Free - no license needed. Enjoy! 🙂", 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Link to upgrade to premium */
                        __( 'To unlock more features consider <a href="%s" rel="noopener noreferer" target="blank">upgrading to Premium</a>', 'advanced-coupons-for-woocommerce-free' ),
                        'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=generalsettingslicenselink'
                    ),
                    __( 'As a valued Advanced Coupons for WooCommerce Free user you receive up to <em>50% off</em>, automatically applied at checkout!', 'advanced-coupons-for-woocommerce-free' ),
                ),
            )
        );

        return $settings;
    }

    /**
     * Add upgrade section to help settings page.
     *
     * @since 1.0
     * @access public
     *
     * @param array $settings Setting fields.
     * @return array Filtered setting fields.
     */
    public function help_settings_upgrade_section( $settings ) {
        $section_start = array( $settings[0] );

        unset( $settings[0] );

        $upgrade_section = array(

            array(
                'title' => __( 'Upgrade', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'acfw_divider_row',
                'id'    => 'acfw_upgrade_divider_row',
            ),

            array(
                'title'     => __( 'Premium Add-on', 'advanced-coupons-for-woocommerce-free' ),
                'type'      => 'acfw_upgrade_setting_field',
                'desc'      => __( 'Advanced Coupons Premium adds even more advanced features to your coupons so you can market your store better.', 'advanced-coupons-for-woocommerce-free' ),
                'link_text' => __( 'Click here to read more and upgrade →', 'advanced-coupons-for-woocommerce-free' ),
                'link_url'  => apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=helppage' ),
            ),

        );

        return array_merge( $section_start, $upgrade_section, $settings );
    }

    /**
     * Render ACFW premium settings content.
     *
     * @since 1.0
     * @access public
     *
     * @param array $value Array of options data. May vary depending on option type.
     */
    public function render_acfw_premium_settings_content( $value ) {
        $img_logo = $this->_constants->IMAGES_ROOT_URL . '/acfw-logo-alt.png';?>
        <tr valign="top" class="<?php echo esc_attr( $value['id'] ) . '-row'; ?>">
            <td colspan="2">

                <?php include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-upgrade-settings-page.php'; ?>

            </td>
        </tr>
        <?php
}

    /**
     * Render ACFW license placeholder settings content.
     *
     * @since 1.1
     * @access public
     *
     * @param array $value Array of options data. May vary depending on option type.
     */
    public function render_acfw_license_placeholder_content( $value ) {
        $plugin_version = Plugin_Constants::VERSION;
        ?>
        <tr valign="top" class="<?php echo esc_attr( $value['id'] ) . '-row'; ?>">
            <td colspan="2">

                <?php include $this->_constants->VIEWS_ROOT_PATH . 'premium' . DIRECTORY_SEPARATOR . 'view-license-placeholder-settings-page.php'; ?>

            </td>
        </tr>
        <?php
}

    /**
     * Enqueue upgrade settings tab styles and scripts.
     *
     * @since 1.0
     * @access public
     *
     * @param WP_Screen $screen    Current screen object.
     * @param string    $post_type Screen post type.
     */
    public function enqueue_upgrade_settings_scripts( $screen, $post_type ) {
        $section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( 'woocommerce_page_wc-settings' === $screen->id && in_array( $section, array( 'acfw_premium', 'acfw_slmw_settings_section' ), true ) ) {
            wp_enqueue_style( 'acfwf_upgrade_settings', $this->_constants->CSS_ROOT_URL . 'acfw-upgrade-settings.css', array(), Plugin_Constants::VERSION, 'all' );
        }

        // wc-admin upsells.
        if ( 'woocommerce_page_wc-admin' === $screen->id || 'edit-shop_coupon' === $screen->id ) {
            $wc_admin_vite = new Vite_App(
                'acfw-wc-admin',
                'packages/acfwf-wc-admin/index.tsx',
                array( 'wc-components' )
            );
            $wc_admin_vite->enqueue();

            wp_localize_script(
                'acfw-wc-admin',
                'acfwWCAdmin',
                array(
					'sharedProps'         => array(
						'upgradePremium' => __( 'Upgrade To Premium', 'advanced-coupons-for-woocommerce-free' ),
						'premiumLink'    => admin_url( 'admin.php?page=acfw-premium' ),
						'bonusText'      => __( '<strong>Bonus:</strong> Advanced Coupons free version users get up to 50% off the regular price, automatically applied at checkout.', 'advanced-coupons-for-woocommerce-free' ),
					),
					'analyticsUpsell'     => array(
						'title'       => __( 'Unlock more coupon features with Advanced Coupons Premium', 'advanced-coupons-for-woocommerce-free' ),
						'description' => sprintf(
                            /* Translators: %s: 5 star icons markup */
							__( 'Advanced Coupons Premium is the 5-star %s add-on that adds even more features to your coupons. Gain access to premium Cart Conditions, advanced BOGO deals, adding products during coupon apply, one-click notices, auto apply coupons, better scheduling, and more!', 'advanced-coupons-for-woocommerce-free' ),
							'<span class="stars">★★★★★</span>'
						),
					),
					'recommendExtensions' => array(
						'title'       => __( 'Recommended coupon extensions', 'advanced-coupons-for-woocommerce-free' ),
						'description' => sprintf(
							/* Translators: %s: 5 star icons markup */
                            __( 'Advanced Coupons Premium is the 5-star %s add-on that adds even more features to your coupons. Gain access to premium Cart Conditions, advanced BOGO deals, adding products during coupon apply, one-click notices, auto apply coupons, better scheduling, and more!', 'advanced-coupons-for-woocommerce-free' ),
							'<span class="stars">★★★★★</span>'
						),
					),
                )
            );
        }
    }

    /**
     * Render help resources controls.
     *
     * @since 1.0
     * @access public
     *
     * @param array $value Array of options data. May vary depending on option type.
     */
    public function render_acfw_upgrade_setting_field( $value ) {
        ?>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for=""><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td class="forminp forminp-<?php echo esc_attr( $value['type'] ); ?>">
                <p><?php echo esc_html( $value['desc'] ); ?></p>
                <p><a class="button button-primary" id="<?php echo esc_attr( $value['id'] ); ?>" href="<?php echo esc_url( $value['link_url'] ); ?>" target="_blank">
                    <?php echo esc_html( $value['link_text'] ); ?>
                </a></p>
            </td>
        </tr>

        <?php
}

    /**
     * Add help link in usage restrictions tab.
     *
     * @since 1.5
     * @access public
     */
    public function usage_restrictions_add_help_link() {
        echo '<div class="acfw-help-link" data-module="usage-restrictions"></div>';
    }

    /**
     * Add help link in usage limits tab.
     *
     * @since 1.5
     * @access public
     */
    public function usage_limits_add_help_link() {
        echo '<div class="acfw-help-link" data-module="usage-limits"></div>';
    }

    /*
    |--------------------------------------------------------------------------
    | Edit advanced coupon JS upsell.
    |--------------------------------------------------------------------------
     */

    /**
     * Generate upsell popup content html markup.
     *
     * @since 4.5.1
     * @access private
     *
     * @param array $args Content arguments.
     * @return string Content markup.
     */
    private function _generate_upsell_popup_content( $args ) {

        $args = wp_parse_args(
            $args,
            array(
				'title'    => '',
				'contents' => array(),
				'links'    => array(),
            )
        );

        // Extracted variables are defined above.
        extract( $args ); // phpcs:ignore

        $html  = sprintf( '<img src="%1$s" alt="%2$s" />', $this->_constants->IMAGES_ROOT_URL . '/acfw-logo.png', __( 'Advanced Coupons Premium', 'advanced-coupons-for-woocommerce-free' ) );
        $html .= sprintf( '<h3>%s</h3>', $title );

        foreach ( (array) $contents as $content ) {
            $html .= sprintf( '<p>%s</p>', $content );
        }

        return wp_kses_post( $html );
    }

    /**
     * Add upsell localized data on edit advanced coupon JS.
     *
     * @since 1.0
     * @access public
     *
     * @param array $data Localized data.
     * @return array Filtered localized data.
     */
    public function add_upsell_localized_script_data_on_edit_advanced_coupon_js( $data ) {

        $popup_upsells = array(
            array(
                'key'      => 'cart_condition_field',
                'title'    => __( 'Premium Cart Condition', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'This premium cart condition and more are available in the Premium add-on for Advanced Coupons', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=cartcondition' )
                    ),
                ),
            ),
            array(
                'key'      => 'bogo_deals_type',
                'contents' => sprintf(
                    /* Translators: %s: Advanced coupons pricing link. */
                    __( 'You can do advanced BOGO deals in the <a href="%s" target="_blank">Premium add-on for Advanced Coupons</a>.', 'advanced-coupons-for-woocommerce-free' ),
                    apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=bogo' )
                ),
            ),
            array(
                'key'      => 'usage_limits',
                'title'    => __( 'Upgrade To Reset Coupon Usage On Timer', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'In Advanced Coupons Premium you can reset the usage counts of a coupon on a timer. This is great for running recurring deals such as daily deals, giving coupons to influencers to redeem samples and more.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=usagelimits' )
                    ),
                ),
            ),
            array(
                'key'      => 'usage_restriction',
                'title'    => __( 'Upgrade To Get Advanced Coupons Restrictions', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'In Advanced Coupons Premium you can restrict the usage of this coupon more granularly with other specific coupons and/or restrict it to a specifc list of customers.', 'advanced-coupons-for-woocommerce-free' ),
                    __( 'This is great if you have a coupon that is allowed to work with some coupons but not others and/or only allow it to be used by certain customers registered on your store.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=usagelimits' )
                    ),
                ),
            ),
            array(
                'key'      => 'auto_apply',
                'title'    => __( 'Upgrade To Apply Coupons Automatically', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'In Advanced Coupons Premium you can have coupons automatically apply to a customer’s cart once the Cart Conditions match! Surprise and delight your customers with auto apply coupons.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=autoapply' )
                    ),
                ),
            ),
            array(
                'key'      => 'virtual_coupons',
                'title'    => __( 'Upgrade To Get Virtual Coupons', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'In Advanced Coupons Premium you can have virtual coupons which are other codes that are also valid for this coupon. Bulk generate 100’s or 1000’s of unique alternative coupon codes for a coupon to use in welcome sequences, abandoned cart sequences, and other scenarios.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=virtualcoupons' )
                    ),
                ),
            ),
            array(
                'key'      => 'bogo_auto_add_get_products',
                'title'    => __( 'Upgrade To Apply "Get" Product Automatically', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'In Advanced Coupons Premium, BOGO coupons with the Specific Product "Get" type can automatically apply the product to a customer’s cart! This is only available for Specific Product type. It’s a great user experience upgrade for your customers to have it done for them.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=bogoautoadd' )
                    ),
                ),
            ),
            array(
                'key'      => 'day_time_schedules',
                'title'    => __( 'Day/Time Schedules', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'Set more advanced day and time schedules to control exactly when your coupon is valid using Advanced Coupons Premium.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=daytimeschedules' )
                    ),
                ),
            ),
            array(
                'key'      => 'cashback_coupon',
                'title'    => __( 'Cashback Coupon', 'advanced-coupons-for-woocommerce-free' ),
                'contents' => array(
                    __( 'In Advanced Coupons Premium you can create cashback coupons which give a customer a percentage of their order back as store credit. This is a great way to incentivize customers to come back and shop again.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Advanced coupons pricing link. */
                        __( '<a href="%s" target="_blank">See all features & pricing &rarr;</a>', 'advanced-coupons-for-woocommerce-free' ),
                        apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=cashbackcoupon' )
                    ),
                ),
            ),
        );

        $data['upsell'] = array();
        foreach ( $popup_upsells as $popup_upsell ) {

            if ( 'bogo_deals_type' === $popup_upsell['key'] ) {
                $data['upsell']['bogo_deals_type'] = $popup_upsell['contents'];
                continue;
            }

            $data['upsell'][ $popup_upsell['key'] ] = $this->_generate_upsell_popup_content( $popup_upsell );
        }

        // cart condition premium notice.
        $cart_condition_premium_notice_activation  = apply_filters(
            'acfw_condition_premium_notice_activation',
            array(
                'logo_img'      => $this->_constants->IMAGES_ROOT_URL . '/acfw-logo.png',
                'label_premium' => __( 'Premium', 'advanced-coupons-for-woocommerce-free' ),
                'label'         => array(
                    __( 'Hey there, thank you for choosing Advanced Coupons for WooCommerce! We wanted to remind you that you have the premium plugin installed, which offers even more advanced features for your WooCommerce coupons to help you market your products more effectively.', 'advanced-coupons-for-woocommerce-free' ),
                    __( 'We noticed that you have not yet activated your copy of Advanced Coupons for WooCommerce Premium. Once you activate the plugin, you can start creating unique and attractive coupons for your store, including {{premium}}.', 'advanced-coupons-for-woocommerce-free' ),
                    __( 'So, do not wait any longer! Activate your copy of Advanced Coupons for WooCommerce Premium today and take your marketing efforts to the next level.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                'actions'       => array(
                    array(
                        'label' => __( 'Click here to activate  →', 'advanced-coupons-for-woocommerce-free' ),
                        'url'   => wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . Plugin_Constants::PREMIUM_PLUGIN . '&amp;plugin_status=all&amp;s', 'activate-plugin_' . Plugin_Constants::PREMIUM_PLUGIN ),
                    ),
                ),
            )
        );
        $cart_condition_premium_notice_promotional = apply_filters(
            'acfw_condition_premium_notice_promotional',
            array(
                'logo_img'      => $this->_constants->IMAGES_ROOT_URL . '/acfw-logo.png',
                'label_premium' => __( 'Premium', 'advanced-coupons-for-woocommerce-free' ),
                'label'         => array(
                    __( 'Hey there, thank you for choosing Advanced Coupons for WooCommerce! We wanted to remind you that a premium cart condition {{premium}} has been applied to your coupon, but it appears that you have not yet installed the premium version of the plugin.', 'advanced-coupons-for-woocommerce-free' ),
                    __( 'Our premium plugin offers advanced features for WooCommerce coupons, helping store owners like you to market their products more effectively. Once you install & activate the plugin, you can start creating unique and attractive coupons for your store, including BOGO deals and percentage discounts, and many more.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                'actions'       => array(
                    array(
                        'label' => __( 'Learn more', 'advanced-coupons-for-woocommerce-free' ),
                        'url'   => 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=cartconditions&utm_campaign=premiumremovalwarning',
                    ),
                ),
            )
        );
        $cart_condition_field_premium_notice       = apply_filters(
            'acfw_condition_field_premium_notice',
            array(
                'label' => __( 'Please activate the premium plugin to continue using this cart condition', 'advanced-coupons-for-woocommerce-free' ),
            )
        );

        $data['cart_condition_field_premium_notice'] = $cart_condition_field_premium_notice;
        $data['cart_condition_premium_notice']       = ( $this->_helper_functions->is_plugin_installed( Plugin_Constants::PREMIUM_PLUGIN ) ) ?
            $cart_condition_premium_notice_activation : $cart_condition_premium_notice_promotional;

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Plugin action link.
    |--------------------------------------------------------------------------
     */

    /**
     * Add settings link to plugin actions links.
     *
     * @since 1.0
     * @access public
     *
     * @param array $links Plugin action links.
     * @return array Filtered plugin action links.
     */
    public function plugin_upgrade_action_link( $links ) {
        $upgrade_links = array(
			sprintf(
                /* Translators: %s: Upgrade to premium link */
                __( '<a href="%s" target="_blank"><b>Upgrade to Premium</b></a>', 'advanced-coupons-for-woocommerce-free' ),
                apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=pluginpage' )
            ),
		);

        return array_merge( $upgrade_links, $links );
    }

    /*
    |--------------------------------------------------------------------------
    | After two weeks upgrade notice.
    |--------------------------------------------------------------------------
     */

    /**
     * Schedule upgrade notice to be displayed two weeks after plugin is activated.
     *
     * @since 1.0
     * @access public
     */
    public function schedule_upgrade_notice_for_later() {
        if ( wp_next_scheduled( Plugin_Constants::UPRADE_NOTICE_CRON ) || get_option( Plugin_Constants::SHOW_UPGRADE_NOTICE ) ) {
            return;
        }

        wp_schedule_single_event( time() + ( WEEK_IN_SECONDS * 2 ), Plugin_Constants::UPRADE_NOTICE_CRON );
    }

    /**
     * Trigger to show upgrade notice.
     *
     * @since 1.0
     * @access public
     */
    public function trigger_show_upgrade_notice_for_later() {
        if ( get_option( Plugin_Constants::SHOW_UPGRADE_NOTICE ) === 'dismissed' ) {
            return;
        }

        update_option( Plugin_Constants::SHOW_UPGRADE_NOTICE, 'yes' );
    }

    /**
     * Display upgrade notice on admin notices.
     *
     * @since 1.0
     * @since 1.1 Don't show on ACFW settings upgrade page.
     * @access public
     *
     * @param array $notice_options List of notice options.
     * @return array Filtered list of notice options.
     */
    public function register_upgrade_notice_option( $notice_options ) {
        $tab     = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        if ( 'acfw_settings' !== $tab || 'acfw_premium' !== $section ) {
            $notice_options['upgrade'] = Plugin_Constants::SHOW_UPGRADE_NOTICE;
        }

        return $notice_options;
    }

    /**
     * Register upgrade notice data.
     *
     * @since 4.3.3
     * @access public
     *
     * @param mixed  $data       Notice data.
     * @param string $notice_key Notice key.
     * @return array             Filtered notice data.
     */
    public function register_upgrade_notice_data( $data, $notice_key ) {
        if ( 'upgrade' === $notice_key ) {

            if ( $this->_helper_functions->is_plugin_installed( Plugin_Constants::PREMIUM_PLUGIN ) ) {
                $primary_action = array(
                    'key'        => 'primary',
                    'link'       => wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . Plugin_Constants::PREMIUM_PLUGIN . '&amp;plugin_status=all&amp;s', 'activate-plugin_' . Plugin_Constants::PREMIUM_PLUGIN ),
                    'text'       => __( 'Click here to activate  →', 'advanced-coupons-for-woocommerce-free' ),
                    'extra_html' => sprintf( '<span class="plugin-detected"><em>%s</em></span>', __( 'Plugin detected', 'advanced-coupons-for-woocommerce-free' ) ),
                );
            } else {
                $primary_action = array(
                    'key'         => 'primary',
                    'link'        => apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=adminnotice' ),
                    'text'        => __( 'Click here to see pricing & features  →', 'advanced-coupons-for-woocommerce-free' ),
                    'is_external' => true,
                );
            }

            $data = array(
                'slug'                => 'upgrade',
                'id'                  => Plugin_Constants::SHOW_UPGRADE_NOTICE,
                'logo_img'            => $this->_constants->IMAGES_ROOT_URL . '/acfw-logo.png',
                'is_dismissable'      => true,
                'type'                => 'success',
                'heading'             => '',
                'content'             => array(
                    __( 'We hope you’ve been enjoying the free version of Advanced Coupons. Did you know there is a Premium add-on?', 'advanced-coupons-for-woocommerce-free' ),
                    __( 'It adds even more advanced features to your coupon so you can market your store better.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                'actions'             => array(
                    $primary_action,
                ),
                'hide_action_dismiss' => true,
            );
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | WC Marketing
    |--------------------------------------------------------------------------
     */

    /**
     * Filter through the WC Marketing recommended extensions transient and prepend our extensions.
     *
     * @deprecated 1.6
     *
     * @since 1.1
     * @access public
     *
     * @param array $recommended_plugins List of recommended plugins.
     * @return array Filtered list of recommended plugins.
     */
    public function filter_wc_marketing_recommended_plugins( $recommended_plugins ) {
        return $recommended_plugins;
    }

    /**
     * Filter through the WC Marketing knowledgebase articles transient and prepend our own articles.
     *
     * @since 1.1
     * @since 1.2.3 Add transient parameter.
     * @since 4.5.3 Remove force fetch WC knowledge base data if its not yet avaiable or when transient has expired.
     * @access public
     *
     * @param array  $knowledge_base List of WC kb articles.
     * @param string $transient      Current transient.
     * @return array Filtered list of WC kb articles.
     */
    public function filter_wc_marketing_knowledge_base( $knowledge_base, $transient ) {

        // Skip if knowledge base is not yet available.
        if ( false === $knowledge_base ) {
            return $knowledge_base;
        }

        $category = strpos( $transient, 'coupon' ) !== false ? 'coupons' : 'marketing';

        $wws_ebook_check = ! empty( $knowledge_base ) ? array_filter(
            $knowledge_base,
            function ( $kb ) {
            return ( isset( $kb['id'] ) && 'wwsebook' === $kb['id'] );
            }
        ) : array();

        if ( empty( $wws_ebook_check ) && 'coupons' !== $category ) {
            array_unshift(
                $knowledge_base,
                array(
                    'id'            => 'wwsebook',
                    'title'         => __( 'How To Setup Wholesale On Your WooCommerce Store', 'advanced-coupons-for-woocommerce-free' ),
                    'date'          => gmdate( 'Y-m-d\TH:i:s', time() ),
                    'link'          => 'https://wholesalesuiteplugin.com/free-guide/?utm_source=acfwf&utm_medium=wcmarketing&utm_campaign=knowledgebase',
                    'author_name'   => 'Josh Kohlbach',
                    'author_avatar' => 'https://secure.gravatar.com/avatar/2f2da8c07f7031a969ae1bd233437a29?s=32&amp;d=mm&amp;r=g',
                    'image'         => $this->_constants->IMAGES_ROOT_URL . 'wws-free-ebook.png',
                )
            );
        }

        $acfw_ebook_check = ! empty( $knowledge_base ) ? array_filter(
            $knowledge_base,
            function ( $kb ) {
            return ( isset( $kb['id'] ) && 'acfwebook' === $kb['id'] );
            }
        ) : array();

        if ( empty( $acfw_ebook_check ) ) {
            array_unshift(
                $knowledge_base,
                array(
                    'id'            => 'acfwebook',
                    'title'         => __( 'How To Grow A WooCommerce Store Using Coupon Deals', 'advanced-coupons-for-woocommerce-free' ),
                    'date'          => gmdate( 'Y-m-d\TH:i:s', time() ),
                    'link'          => 'https://advancedcouponsplugin.com/how-to-grow-your-woocommerce-store-with-coupons/?utm_source=acfwf&utm_medium=wcmarketing&utm_campaign=knowledgebase',
                    'author_name'   => 'Josh Kohlbach',
                    'author_avatar' => 'https://secure.gravatar.com/avatar/2f2da8c07f7031a969ae1bd233437a29?s=32&amp;d=mm&amp;r=g',
                    'image'         => $this->_constants->IMAGES_ROOT_URL . 'acfw-free-ebook.png',
                )
            );
        }

        return $knowledge_base;
    }

    /*
    |--------------------------------------------------------------------------
    | Upsell admin app page.
    |--------------------------------------------------------------------------
     */

    /**
     * Register upsell admin app page.
     *
     * @since 1.2
     * @access public
     *
     * @param array $app_pages List of app pages.
     * @param bool  $show_app  Flag to indicate if the app is shown or not.
     * @return array Filtered list of app pages.
     */
    public function register_upsell_admin_app_page( $app_pages, $show_app ) {
        $app_pages['acfw-premium'] = array(
            'label' => __( 'Upgrade to Premium', 'advanced-coupons-for-woocommerce-free' ),
            'slug'  => $show_app ? 'acfw-premium' : 'wc-settings&tab=acfw_settings&section=acfw_premium',
            'page'  => 'premium_upgrade',
        );

        return $app_pages;
    }

    /**
     * Register the advanced coupons premium link under WC Marketing top level menu.
     *
     * @since 1.6
     * @access public
     */
    public function register_acfwp_link_in_marketing_top_level_menu() {
        add_submenu_page(
            'woocommerce-marketing',
            __( 'Advanced Coupons Premium', 'advanced-coupons-for-woocommerce-free' ),
            __( 'Advanced Coupons Premium', 'advanced-coupons-for-woocommerce-free' ),
            'manage_woocommerce',
            'admin.php?page=acfw-premium'
        );
    }

    /**
     * Append upsell data for admin app page localized script.
     *
     * @since 1.2
     * @access public
     *
     * @param array $data Localized data.
     * @return array Filtered localized data.
     */
    public function upsell_localized_data_for_admin_app( $data ) {
        $data['coupon_nav']['premium'] = __( 'Upgrade to Premium', 'advanced-coupons-for-woocommerce-free' );

        $data['premium_page'] = array(
            'image'   => $this->_constants->IMAGES_ROOT_URL . '/acfw-logo-alt.png',
            'title'   => __( '<strong>Free</strong> vs <strong>Premium</strong>', 'advanced-coupons-for-woocommerce-free' ),
            'desc'    => __( 'If you are serious about growing your sales within your WooCommerce store then the Premium add-on to the free Advanced Coupons for WooCommerce plugin that you are currently using can help you.', 'advanced-coupons-for-woocommerce-free' ),
            'upgrade' => __( 'Upgrade', 'advanced-coupons-for-woocommerce-free' ),
            'header'  => array(
                'feature' => __( 'Features', 'advanced-coupons-for-woocommerce-free' ),
                'free'    => __( 'Free Plugin', 'advanced-coupons-for-woocommerce-free' ),
                'premium' => __( 'Premium Add-on', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'rows'    => array(
                array(
                    'feature' => __( 'Restrict Applying Coupons Using Cart Conditions', 'advanced-coupons-for-woocommerce-free' ),
                    'free'    => __( 'Basic set of cart conditions only', 'advanced-coupons-for-woocommerce-free' ),
                    'premium' => __( 'Advanced cart conditions to let you control exactly when coupons should be allowed to apply.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                array(
                    'feature' => __( 'Run BOGO deals with coupons', 'advanced-coupons-for-woocommerce-free' ),
                    'free'    => __( 'Simple BOGO deals only', 'advanced-coupons-for-woocommerce-free' ),
                    'premium' => __( 'Run advanced BOGO deals with multiple products or across product categories.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                array(
                    'feature' => __( 'Schedule coupon start and end date', 'advanced-coupons-for-woocommerce-free' ),
                    'free'    => __( 'Only WordPress scheduled post', 'advanced-coupons-for-woocommerce-free' ),
                    'premium' => __( 'Show a nice message before and after specific start/end dates so you can recapture lost sales.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                array(
                    'feature' => __( 'One-click Apply Notifications', 'advanced-coupons-for-woocommerce-free' ),
                    'free'    => __( 'Not available', 'advanced-coupons-for-woocommerce-free' ),
                    'premium' => __( 'Show a message at the cart with a one-click apply button when the customer is eligible for a coupon.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                array(
                    'feature' => __( 'Auto Apply Coupons', 'advanced-coupons-for-woocommerce-free' ),
                    'free'    => __( 'Not available', 'advanced-coupons-for-woocommerce-free' ),
                    'premium' => __( 'Automatically apply a coupon to the cart when a customer becomes eligible.', 'advanced-coupons-for-woocommerce-free' ),
                ),
                array(
                    'feature' => __( 'Shipping Override Coupons', 'advanced-coupons-for-woocommerce-free' ),
                    'free'    => __( 'Not available', 'advanced-coupons-for-woocommerce-free' ),
                    'premium' => __( "Run more creative discounts on your store's shipping methods.", 'advanced-coupons-for-woocommerce-free' ),
                ),
                array(
                    'feature' => __( 'Timed Usage Resets', 'advanced-coupons-for-woocommerce-free' ),
                    'free'    => __( 'Not available', 'advanced-coupons-for-woocommerce-free' ),
                    'premium' => __( 'Give coupons with usage limits that reset after a time - great for influencer marketing or daily deals.', 'advanced-coupons-for-woocommerce-free' ),
                ),
            ),
            'action'  => array(
                'title'    => __( "+ 100's of other premium features", 'advanced-coupons-for-woocommerce-free' ),
                'btn_text' => __( 'See the full feature list →', 'advanced-coupons-for-woocommerce-free' ),
                'btn_link' => apply_filters( 'acfwp_upsell_link', 'https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=upgradepage' ),
            ),
        );

        return $data;
    }

    /**
     * Highlight upgrade to premium submenu link.
     *
     * @since 1.6
     * @access public
     */
    public function highlight_upgrade_to_premium_submenu_link() {
    ?>
        <script type="text/javascript">
        (function($){
            $link = $('.toplevel_page_acfw-admin').find('a[href="admin.php?page=acfw-premium"]');
            $link.css({background: '#6bb738', color: '#fff', fontWeight: 'bold'});
        })(jQuery);
        </script>
    <?php
    }

    /*
    |--------------------------------------------------------------------------
    | Loyalty Program Upsell
    |--------------------------------------------------------------------------
     */

    /**
     * Register Loyalty Program settings upsell page.
     *
     * @since 4.3.1
     * @access public
     *
     * @param array $app_pages List of app pages.
     * @return array Filtered list of app pages.
     */
    public function register_loyalty_program_menu( $app_pages ) {
        $merged = array_merge(
            array(
				'acfw-loyalty-program' => array(
					'slug'  => 'acfw-loyalty-program',
					'label' => __( 'Loyalty Program', 'advanced-coupons-for-woocommerce-free' ),
					'page'  => 'loyalty_program',
				),
            ),
            $app_pages
        );

        return $merged;
    }

    /**
     * Register loyalty program upsell localized data on admin app.
     *
     * @since 4.3.1
     * @access public
     *
     * @param array $data Localized data.
     * @return array Filtered localized data.
     */
    public function register_loyalty_program_upsell_localized_data( $data ) {
        $data['loyalty_program'] = array(
            'title'         => __( 'Increase Customer Loyalty & Repeat Orders With A Loyalty Program', 'advanced-coupons-for-woocommerce-free' ),
            'description'   => __( 'Loyalty Program for WooCommerce is proven to increase customer loyalty and help you get more repeat orders. It’s a great way to incentivize your customers without having to give steep discounts.', 'advanced-coupons-for-woocommerce-free' ),
            'plugin_image'  => array(
                'src' => $this->_constants->IMAGES_ROOT_URL . 'lpfw-icon.png',
                'alt' => __( 'Loyalty Program plugin icon', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'features_list' => array(
                __( '❤️ Trusted by over 15,000+ stores', 'advanced-coupons-for-woocommerce-free' ),
                __( '⭐ 5-star customer satisfaction rating', 'advanced-coupons-for-woocommerce-free' ),
                __( '🚀️ Lots of options for customer to earn points', 'advanced-coupons-for-woocommerce-free' ),
                __( '📱 Control how points are valued', 'advanced-coupons-for-woocommerce-free' ),
                __( '🔍 Hooks into your existing store', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'steps_list'    => array(
                array(
                    'step_count'  => '1',
                    'title'       => __( 'Purchase & Install Loyalty Program for WooCommerce', 'advanced-coupons-for-woocommerce-free' ),
                    'description' => __( 'Your customers will love being able to earn points for their orders so they can redeem them for coupons on future orders. Get set up and running in a few minutes.', 'advanced-coupons-for-woocommerce-free' ),
                    'is_active'   => ! $this->_helper_functions->is_plugin_installed( Plugin_Constants::LOYALTY_PLUGIN ),
                    'action_text' => __( 'Get Loyalty Program', 'advanced-coupons-for-woocommerce-free' ),
                    'link'        => 'https://advancedcouponsplugin.com/pricing/?tab=loyalty&utm_source=acfwf&utm_medium=upsell&utm_campaign=loyaltyprogrampage',
                    'is_external' => true,
                ),
                array(
                    'step_count'  => '2',
                    'title'       => __( 'Configure Loyalty Program Settings', 'advanced-coupons-for-woocommerce-free' ),
                    'description' => __( 'Loyalty Program for WooCommerce lets you configure an amazing points & rewards program in minutes. It comes mostly configured out of the box, but there’s loads of great customizations you can deploy.', 'advanced-coupons-for-woocommerce-free' ),
                    'is_active'   => $this->_helper_functions->is_plugin_installed( Plugin_Constants::LOYALTY_PLUGIN ),
                    'action_text' => __( 'Start Setup', 'advanced-coupons-for-woocommerce-free' ),
                    'link'        => sprintf( 'plugins.php?action=activate&plugin=%s&plugin_status=all&s&_wpnonce=%s', 'loyalty-program-for-woocommerce%2Floyalty-program-for-woocommerce.php', wp_create_nonce( 'activate-plugin_' . Plugin_Constants::LOYALTY_PLUGIN ) ),
                    'is_external' => false,
                ),
            ),
        );

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Advanced Gift Cards Upsell
    |--------------------------------------------------------------------------
     */

    /**
     * Register Advanced Gift Cards upsell page.
     *
     * @since 4.3.1
     * @access public
     *
     * @param array $app_pages List of app pages.
     * @return array Filtered list of app pages.
     */
    public function register_advanced_gift_cards_menu( $app_pages ) {
        $merged = array_merge(
            array(
				'acfw-advanced-gift-cards' => array(
					'slug'  => 'acfw-advanced-gift-cards',
					'label' => __( 'Advanced Gift Cards', 'advanced-coupons-for-woocommerce-free' ),
					'page'  => 'advanced_gift_cards',
				),
            ),
            $app_pages
        );

        return $merged;
    }

    /**
     * Register loyalty program upsell localized data on admin app.
     *
     * @since 4.3.1
     * @access public
     *
     * @param array $data Localized data.
     * @return array Filtered localized data.
     */
    public function register_advanced_gift_cards_upsell_localized_data( $data ) {
        $data['advanced_gift_cards'] = array(
            'title'         => __( 'Sell WooCommerce Gift Cards On Your Store Easily', 'advanced-coupons-for-woocommerce-free' ),
            'description'   => __( 'Advanced Gift Cards for WooCommerce lets you sell digital gift cards on your WooCommerce store that are redeemable for store credits. It’s a great way to virally spread your store plus gain guaranteed sales.', 'advanced-coupons-for-woocommerce-free' ),
            'plugin_image'  => array(
                'src' => $this->_constants->IMAGES_ROOT_URL . 'agc-icon.png',
                'alt' => __( 'Advanced Gift Cards plugin icon', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'features_list' => array(
                __( '❤️ Trusted plugin, 5-star rating over 15,000+ stores', 'advanced-coupons-for-woocommerce-free' ),
                __( '💳 Sell digital gift cards for your store easily', 'advanced-coupons-for-woocommerce-free' ),
                __( '👩🏻‍❤️‍👨🏽 Let customers send gift cards to friends', 'advanced-coupons-for-woocommerce-free' ),
                __( '💝 Timed delivery option for special occasions', 'advanced-coupons-for-woocommerce-free' ),
                __( '🔥 Bonus: 85+ extra gift card designs', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'steps_list'    => array(
                array(
                    'step_count'  => '1',
                    'title'       => __( 'Purchase & Install Advanced Gift Cards plugin', 'advanced-coupons-for-woocommerce-free' ),
                    'description' => __( 'Your customers will love being able to purchase gift cards for their friends and family. It’s also a great way to virally spread your store while guaranteeing a future sale today.', 'advanced-coupons-for-woocommerce-free' ),
                    'is_active'   => ! $this->_helper_functions->is_plugin_installed( Plugin_Constants::GIFT_CARDS_PLUGIN ),
                    'action_text' => __( 'Get Advanced Gift Cards', 'advanced-coupons-for-woocommerce-free' ),
                    'link'        => 'https://advancedcouponsplugin.com/pricing/?tab=gift-cards&utm_source=acfwf&utm_medium=upsell&utm_campaign=advancedgiftcardspage',
                    'is_external' => true,
                ),
                array(
                    'step_count'  => '2',
                    'title'       => __( 'Create A Gift Card Product', 'advanced-coupons-for-woocommerce-free' ),
                    'description' => __( 'Advanced Gift Cards for WooCommerce lets you create new digital gift card products in minutes. You’ll be selling gift cards redeemable for store credit in no time.', 'advanced-coupons-for-woocommerce-free' ),
                    'is_active'   => $this->_helper_functions->is_plugin_installed( Plugin_Constants::GIFT_CARDS_PLUGIN ),
                    'action_text' => __( 'Start Setup', 'advanced-coupons-for-woocommerce-free' ),
                    'link'        => sprintf( 'plugins.php?action=activate&plugin=%s&plugin_status=all&s&_wpnonce=%s', 'advanced-gift-cards-for-woocommerce%2Fadvanced-gift-cards-for-woocommerce.php', wp_create_nonce( 'activate-plugin_' . Plugin_Constants::GIFT_CARDS_PLUGIN ) ),
                    'is_external' => false,
                ),
            ),
        );

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Uncanny Automator Upsell
    |--------------------------------------------------------------------------
     */

    /**
     * Append Uncanny Automator upsell data.
     *
     * @since 4.5.5
     * @access public
     *
     * @param array $data ACFWF admin app data.
     * @return array Filtered ACFWF admin app data.
     */
    public function append_uncanny_automator_upsell_data( $data ) {

        $data['uncanny_automator'] = array(
            'logo'               => $this->_constants->IMAGES_ROOT_URL . 'uncanny-automator-logo.svg',
            /* Translators: %s: <br/> html. */
            'main_content'       => sprintf( __( 'Put your store credits on autopilot with Uncanny Automator.%s It’s fully compatible with Advanced Coupons as one of our first-party integrations.', 'advanced-coupons-for-woocommerce-free' ), '<br/>' ),
            'action_text'        => __( 'Install & Activate (FREE)', 'advanced-coupons-for-woocommerce-free' ),
            'action_url'         => admin_url( 'post-new.php?post_type=uo-recipe' ),
            'nonce'              => wp_create_nonce( 'acfw_install_plugin' ),
            'is_plugin_active'   => $this->_helper_functions->is_plugin_active( Plugin_Constants::UNCANNY_AUTOMATOR_PLUGIN ),
            'img_path'           => $this->_constants->IMAGES_ROOT_URL,
            'labels'             => array(
                'success_message'    => __( 'Plugin installed and activated successfully!', 'advanced-coupons-for-woocommerce-free' ),
                'add_new_recipe'     => __( 'Add New Recipe', 'advanced-coupons-for-woocommerce-free' ),
                'pro'                => __( 'PRO', 'advanced-coupons-for-woocommerce-free' ),
                'sample_automations' => __( 'Sample automations:', 'advanced-coupons-for-woocommerce-free' ),
                'requires'           => __( 'Requires:', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'triggers_actions'   => array(
                array(
                    'type'   => __( 'Trigger', 'advanced-coupons-for-woocommerce-free' ),
                    'desc'   => __( 'A user receives greater than, less than, or equal to a specific amount of store credit', 'advanced-coupons-for-woocommerce-free' ),
                    'is_pro' => false,
                ),
                array(
                    'type'   => __( 'Trigger', 'advanced-coupons-for-woocommerce-free' ),
                    'desc'   => __( 'A user’s current store credit exceeds a specific amount', 'advanced-coupons-for-woocommerce-free' ),
                    'is_pro' => true,
                ),
                array(
                    'type'   => __( 'Trigger', 'advanced-coupons-for-woocommerce-free' ),
                    'desc'   => __( 'A user spends greater than, less than, or equal to a specific amount of store credit', 'advanced-coupons-for-woocommerce-free' ),
                    'is_pro' => false,
                ),
                array(
                    'type'   => __( 'Trigger', 'advanced-coupons-for-woocommerce-free' ),
                    'desc'   => __( 'A user’s lifetime store credit exceeds a specific amount', 'advanced-coupons-for-woocommerce-free' ),
                    'is_pro' => true,
                ),
                array(
                    'type'   => __( 'Action', 'advanced-coupons-for-woocommerce-free' ),
                    'desc'   => __( 'Add a specific amount of store credit to the user’s account', 'advanced-coupons-for-woocommerce-free' ),
                    'is_pro' => true,
                ),
                array(
                    'type'   => __( 'Action', 'advanced-coupons-for-woocommerce-free' ),
                    'desc'   => __( 'Remove a specific amount of store credit from the user’s account', 'advanced-coupons-for-woocommerce-free' ),
                    'is_pro' => true,
                ),
            ),
            'sample_automations' => array(
                array(
                    'title'    => __( 'When a users receive exactly $10 in store credit, notify them by SMS', 'advanced-coupons-for-woocommerce-free' ),
                    'requires' => array( 'advanced_coupons', 'twilio' ),
                ),
                array(
                    'title'    => __( 'When users spend any store credit, log the records in Google Sheets', 'advanced-coupons-for-woocommerce-free' ),
                    'requires' => array( 'advanced_coupons', 'google_sheets' ),
                ),
                array(
                    'title'    => __( 'When a user reviews a product, add $5 store credit to their account', 'advanced-coupons-for-woocommerce-free' ),
                    'requires' => array( 'woocommerce', 'advanced_coupons' ),
                ),
            ),
        );

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute codes that needs to run plugin activation.
     *
     * @since 1.0
     * @access public
     * @implements ACFWF\Interfaces\Initializable_Interface
     */
    public function initialize() {     }

    /**
     * Execute Upsell class.
     *
     * @since 1.0
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        add_filter( 'transient_wc_marketing_knowledge_base_marketing', array( $this, 'filter_wc_marketing_knowledge_base' ), 10, 2 );
        add_filter( 'transient_wc_marketing_knowledge_base_coupons', array( $this, 'filter_wc_marketing_knowledge_base' ), 10, 2 );

        // only run when premium plugin is not active.
        if ( ! $this->_helper_functions->is_plugin_active( Plugin_Constants::PREMIUM_PLUGIN ) ) {
            add_action( 'add_meta_boxes', array( $this, 'register_upsell_metabox' ), 10, 2 );
            add_filter( 'woocommerce_coupon_data_tabs', array( $this, 'register_upsell_panels' ), 99, 1 );
            add_action( 'woocommerce_coupon_options', array( $this, 'display_did_you_know_notice_in_general' ) );
            add_action( 'acfw_after_coupon_generic_panel', array( $this, 'display_did_you_know_notice_in_generic_panel' ) );
            add_filter( 'acfw_condition_fields_localized_data', array( $this, 'cart_condition_premium_field_options' ) );
            add_filter( 'acfw_cart_condition_panel_tabs', array( $this, 'register_more_cart_conditions_tab' ) );
            add_action( 'acfw_cart_condition_tabs_panels', array( $this, 'display_more_cart_conditions_panel' ) );
            add_filter( 'acfw_cart_conditions_panel_data_atts', array( $this, 'register_dyk_notice_html_attribute' ) );
            add_filter( 'acfw_bogo_trigger_apply_type_descs', array( $this, 'bogo_premium_trigger_apply_type_descs' ) );
            add_filter( 'acfw_bogo_trigger_type_options', array( $this, 'bogo_premium_trigger_type_options' ) );
            add_filter( 'acfw_bogo_apply_type_options', array( $this, 'bogo_premium_apply_type_options' ) );
            add_action( 'acfw_bogo_before_additional_settings', array( $this, 'upsell_automatically_add_deal_products_feature' ), 10, 2 );
            add_filter( 'woocommerce_get_sections_acfw_settings', array( $this, 'register_upsell_settings_section' ) );
            add_filter( 'woocommerce_get_settings_acfw_settings', array( $this, 'get_upsell_settings_section_fields' ), 10, 2 );
            add_action( 'acfw_settings_help_section_options', array( $this, 'help_settings_upgrade_section' ) );
            add_filter( 'acfw_setting_general_options', array( $this, 'register_general_license_field' ) );
            add_filter( 'acfw_setting_bogo_deals_options', array( $this, 'bogo_settings_append_dyk_notice' ) );
            add_filter( 'acfw_modules_settings', array( $this, 'register_premium_modules_settings' ) );
            add_filter( 'acfwf_admin_app_localized', array( $this, 'register_upsell_modal_settings_localized_data' ) );
            add_action( 'woocommerce_admin_field_acfw_premium', array( $this, 'render_acfw_premium_settings_content' ) );
            add_action( 'woocommerce_admin_field_acfw_license_placeholder', array( $this, 'render_acfw_license_placeholder_content' ) );
            add_action( 'woocommerce_admin_field_acfw_upgrade_setting_field', array( $this, 'render_acfw_upgrade_setting_field' ) );
            add_action( 'acfw_after_load_backend_scripts', array( $this, 'enqueue_upgrade_settings_scripts' ), 10, 2 );
            add_action( 'woocommerce_coupon_data_panels', array( $this, 'display_upsell_panel_views' ) );
            add_action( 'woocommerce_coupon_options_usage_limit', array( $this, 'upsell_advanced_usage_limits_fields' ) );
            add_action( 'woocommerce_coupon_options_usage_restriction', array( $this, 'uspell_exclude_coupons_restriction' ) );
            add_action( 'woocommerce_coupon_options_usage_restriction', array( $this, 'upsell_allowed_customers_restriction' ) );
            add_action( 'woocommerce_coupon_options_usage_restriction', array( $this, 'usage_restrictions_add_help_link' ) );
            add_action( 'woocommerce_coupon_options_usage_limit', array( $this, 'usage_limits_add_help_link' ) );
            add_action( 'acfw_after_scheduler_panel', array( $this, 'display_day_time_scheduler_ui_upsell' ) );
            add_action( 'in_admin_header', array( $this, 'display_admin_notice_bar_lite' ) );
            add_filter( 'woocommerce_coupon_discount_types', array( $this, 'upsell_cashback_coupon_types' ) );

            add_filter( 'acfw_edit_advanced_coupon_localize', array( $this, 'add_upsell_localized_script_data_on_edit_advanced_coupon_js' ) );
            add_filter( 'plugin_action_links_' . $this->_constants->PLUGIN_BASENAME, array( $this, 'plugin_upgrade_action_link' ), 20 );

            add_action( 'admin_init', array( $this, 'schedule_upgrade_notice_for_later' ) );
            add_action( Plugin_Constants::UPRADE_NOTICE_CRON, array( $this, 'trigger_show_upgrade_notice_for_later' ) );
            add_filter( 'acfw_admin_notice_option_names', array( $this, 'register_upgrade_notice_option' ) );
            add_filter( 'acfw_get_admin_notice_data', array( $this, 'register_upgrade_notice_data' ), 10, 2 );

            // admin app related.
            add_filter( 'acfw_admin_app_pages', array( $this, 'register_upsell_admin_app_page' ), 10, 2 );
            add_action( 'acfw_register_admin_submenus', array( $this, 'register_acfwp_link_in_marketing_top_level_menu' ) );
            add_filter( 'acfwf_admin_app_localized', array( $this, 'upsell_localized_data_for_admin_app' ) );

            add_action( 'admin_footer', array( $this, 'highlight_upgrade_to_premium_submenu_link' ) );
        }

        add_action( 'admin_footer', array( $this, 'highlight_upgrade_to_premium_submenu_link' ) );

        // only run when loyalty plugin is not active.
        if ( ! $this->_helper_functions->is_plugin_active( Plugin_Constants::LOYALTY_PLUGIN ) ) {
            add_filter( 'acfw_admin_app_pages', array( $this, 'register_loyalty_program_menu' ) );
            add_filter( 'acfwf_admin_app_localized', array( $this, 'register_loyalty_program_upsell_localized_data' ) );
        }

        // only run when advanced gift cards plugin is not active.
        if ( ! $this->_helper_functions->is_plugin_active( Plugin_Constants::GIFT_CARDS_PLUGIN ) ) {
            add_filter( 'acfw_admin_app_pages', array( $this, 'register_advanced_gift_cards_menu' ) );
            add_filter( 'acfwf_admin_app_localized', array( $this, 'register_advanced_gift_cards_upsell_localized_data' ) );
        }

        add_filter( 'acfwf_admin_app_localized', array( $this, 'append_uncanny_automator_upsell_data' ) );
    }
}
