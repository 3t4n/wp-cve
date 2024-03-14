<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the Coupon templates module logic.
 * Public Model.
 *
 * @since 4.6.0
 */
class Coupon_Templates extends Base_Model implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.6.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        parent::__construct( $main_plugin, $constants, $helper_functions );
        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );
    }

    /**
     * Register the coupon templates app page.
     *
     * @since 4.6.0
     * @access public
     *
     * @param array $pages App pages.
     * @return array App pages.
     */
    public function register_coupon_templates_app_page( $pages ) {
        $pages = array_merge(
            array(
                'acfw-coupon-templates' => array(
                    'slug'  => 'acfw-coupon-templates',
                    'label' => __( 'Coupon Templates', 'advanced-coupons-for-woocommerce-free' ),
                    'page'  => 'coupon_templates',
                ),
            ),
            $pages,
        );

        return $pages;
    }

    /**
     * Add coupon templates module setting.
     *
     * @since 4.6.0
     * @access public
     *
     * @param array $modules Modules.
     * @return array Modules.
     */
    public function coupon_templates_module_setting( $modules ) {
        $modules[] = array(
            'title'   => __( 'Coupon Templates', 'advanced-coupons-for-woocommerce-free' ),
            'type'    => 'checkbox',
            'desc'    => __( 'Effortlessly create coupons based on a wide-variety of pre defined templates in your store.', 'advanced-coupons-for-woocommerce-free' ),
            'id'      => Plugin_Constants::COUPON_TEMPLATES_MODULE,
            'default' => 'yes',
        );

        return $modules;
    }

    /**
     * Register the coupon templates localized data.
     *
     * @since 4.6.0
     * @access public
     *
     * @param array $data Localized data.
     * @return array Localized data.
     */
    public function register_coupon_templates_localized_data( $data ) {

        $data['coupon_templates_page'] = array(
            'title'             => __( 'Coupon Templates', 'advanced-coupons-for-woocommerce-free' ),
            'labels'            => array(
                'recently_used_templates' => __( 'Recently Used Templates', 'advanced-coupons-for-woocommerce-free' ),
                'available_templates'     => __( 'Available Templates', 'advanced-coupons-for-woocommerce-free' ),
                'review_templates'        => __( 'Review Templates', 'advanced-coupons-for-woocommerce-free' ),
                'categories'              => __( 'Categories', 'advanced-coupons-for-woocommerce-free' ),
                'all_templates'           => __( 'All templates', 'advanced-coupons-for-woocommerce-free' ),
                'premium'                 => __( 'Premium', 'advanced-coupons-for-woocommerce-free' ),
                'form_instruction'        => __( 'Please fill in the highlighted fields', 'advanced-coupons-for-woocommerce-free' ),
                'create_coupon'           => __( 'Create Coupon', 'advanced-coupons-for-woocommerce-free' ),
                'cancel'                  => __( 'Cancel', 'advanced-coupons-for-woocommerce-free' ),
                'go_back'                 => __( 'Go back', 'advanced-coupons-for-woocommerce-free' ),
                'generate'                => __( 'Generate', 'advanced-coupons-for-woocommerce-free' ),
                'premium_modal_text'      => __( 'The selected template requires that you have the Advanced Coupons premium plugin installed and activated.', 'advanced-coupons-for-woocommerce-free' ),
                'premium_modal_btn'       => __( 'See all features and pricing ⟶', 'advanced-coupons-for-woocommerce-free' ),
                'success_page_desc'       => __( 'Below are the details for the coupon:', 'advanced-coupons-for-woocommerce-free' ),
                'create_another_coupon'   => __( 'Create Another Coupon', 'advanced-coupons-for-woocommerce-free' ),
                'using_the_same_template' => __( 'Using the same template', 'advanced-coupons-for-woocommerce-free' ),
                'edit_coupon'             => __( 'Edit Coupon', 'advanced-coupons-for-woocommerce-free' ),
                'view_templates_list'     => __( 'View Templates List', 'advanced-coupons-for-woocommerce-free' ),
                'template_not_found'      => __( 'The selected template doesn’t exist.', 'advanced-coupons-for-woocommerce-free' ),
                'no_templates_found'      => __( 'No templates found.', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'enable_review_tab' => defined( 'ACFW_COUPON_TEMPLATES_REVIEWER' ) && ACFW_COUPON_TEMPLATES_REVIEWER,
        );

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute Role_Restrictions class.
     *
     * @since 4.6.0
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        // BETA FEATURE: Coupon Templates.
        if ( ! defined( 'ACFW_COUPON_TEMPLATES' ) ) {
            return;
        }

        if ( ! $this->_helper_functions->is_module( Plugin_Constants::COUPON_TEMPLATES_MODULE ) ) {
            return;
        }

        add_filter( 'acfw_admin_app_pages', array( $this, 'register_coupon_templates_app_page' ), 99 );
        add_filter( 'acfw_modules_settings', array( $this, 'coupon_templates_module_setting' ) );
        add_filter( 'acfwf_admin_app_localized', array( $this, 'register_coupon_templates_localized_data' ) );
    }
}
