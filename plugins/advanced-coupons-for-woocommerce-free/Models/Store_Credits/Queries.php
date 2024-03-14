<?php
namespace ACFWF\Models\Store_Credits;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Activatable_Interface;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Store_Credit_Entry;
use Automattic\WooCommerce\Utilities\NumberUtil;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the Store Credits Admin module.
 *
 * @since 4.5.5
 */
class Queries {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of Store_Credits_Admin.
     *
     * @since 4.5.5
     * @access private
     * @var Admin
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 4.5.5
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 4.5.5
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
     * @since 4.5.5
     * @access public
     *
     * @param Plugin_Constants $constants        Plugin constants object.
     * @param Helper_Functions $helper_functions Helper functions object.
     */
    public function __construct( Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 4.5.5
     * @access public
     *
     * @param Plugin_Constants $constants        Plugin constants object.
     * @param Helper_Functions $helper_functions Helper functions object.
     * @return Admin
     */
    public static function get_instance( Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /**
     * Query store credit entries.
     *
     * @since 4.5.5
     * @access private
     *
     * @param array $params     Query parameters.
     * @param bool  $total_only Flag if to only return total count.
     * @return Store_Credit_Entry[] Store credit entries.
     */
    public function query_store_credit_entries( $params, $total_only = false ) {
        global $wpdb;

        $params = wp_parse_args(
            $params,
            array(
				'page'       => 1,
				'per_page'   => 10,
				'sort_by'    => 'date',
				'sort_order' => 'desc',
				'user_id'    => 0,
				'is_admin'   => false,
                'type'       => '',
                'action'     => '',
                'object_id'  => 0,
            )
        );

        $select_query = $total_only ? 'COUNT(e.entry_id)' : 'e.*';
        $type_query   = $params['type'] ? $wpdb->prepare( 'AND e.entry_type = %s', $params['type'] ) : '';
        $action_query = $params['action'] ? $wpdb->prepare( 'AND e.entry_action = %s', $params['action'] ) : '';
        $user_query   = $params['user_id'] ? $wpdb->prepare( 'AND e.user_id = %d', $params['user_id'] ) : '';
        $object_query = $params['object_id'] ? $wpdb->prepare( 'AND e.object_id = %d', $params['object_id'] ) : '';

        $query = "SELECT {$select_query} FROM {$wpdb->prefix}acfw_store_credits AS e
            WHERE 1
            {$type_query} {$action_query} {$user_query} {$object_query}
        ";

        if ( $total_only ) {
            $results = $wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

            if ( \is_null( $results ) ) {
                return new \WP_Error(
                    'acfw_query_store_credit_customers_fail',
                    __( 'There was an error loading total store credit entries data.', 'advanced-coupons-for-woocommerce-free' ),
                    array(
                        'status' => 400,
                        'data'   => $params,
                    )
                );
            }

            return (int) $results;
        }

        $offset       = ( $params['page'] - 1 ) * $params['per_page'];
        $sort_columns = array(
            'user_id' => 'e.entry_id',
            'date'    => 'e.entry_date',
            'type'    => 'e.entry_type',
            'action'  => 'e.entry_action',
        );

        // sort query.
        $sort_column = isset( $sort_columns[ $params['sort_by'] ] ) ? $sort_columns[ $params['sort_by'] ] : 'e.entry_date';
        $sort_type   = 'asc' === $params['sort_order'] ? 'ASC' : 'DESC';
        $sort_query  = "ORDER BY {$sort_column} {$sort_type}";

        // limit query.
        $limit_query = 1 <= $params['page'] ? $wpdb->prepare( 'LIMIT %d OFFSET %d', $params['per_page'], $offset ) : '';

        // run the query.
        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $results = $wpdb->get_results(
            "{$query}{$sort_query}
             {$limit_query}
            ",
            ARRAY_A
        );
        // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

        if ( ! is_array( $results ) ) {
            return new \WP_Error(
                'acfw_query_store_credit_entries_fail',
                __( 'There was an error loading store credit entries data.', 'advanced-coupons-for-woocommerce-free' ),
                array(
                    'status' => 400,
                    'data'   => $params,
                )
            );
        }

        return array_map(
            function ( $r ) {
                return new Store_Credit_Entry( $r );
            },
            $results
        );
    }

    /**
     * Query single store credit entry.
     *
     * @since 4.5.5
     * @access private
     *
     * @param array $params Query parameters.
     * @return Store_Credit_Entry|null Store credit entry.
     */
    public function query_single_store_credit_entry( $params ) {

        $params  = wp_parse_args( $params, array( 'per_page' => 1 ) );
        $entries = $this->query_store_credit_entries( $params );

        return ! empty( $entries ) ? current( $entries ) : null;
    }
}
