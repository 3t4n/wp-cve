<?php

/**
 * The file that defines class for registering metaboxes
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes
 */

/**
 *  Class furgonetka_admin_metaboxes - Manage custom metaboxes in single orders and custom columns in orders page
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class furgonetka_admin_metaboxes
{
    /**
     * View
     *
     * @var \Furgonetka_Admin_Metaboxes_View
     */
    private $view;

    /**
     * Model
     *
     * @var \Furgonetka_Update_Order_Model
     */
    private $model;

    /**
     * Admin class
     *
     * @var \Furgonetka_Admin
     */
    private $furgonetka_admin;

    /**
     * Include view and model
     *
     * @param Furgonetka_Admin $furgonetka_admin - admin class.
     */
    public function __construct( Furgonetka_Admin $furgonetka_admin )
    {
        $this->furgonetka_admin = $furgonetka_admin;

        require_once FURGONETKA_PLUGIN_DIR . 'includes/view/class-furgonetka-admin-metaboxes-view.php';
        $this->view = new Furgonetka_Admin_Metaboxes_View();

        // require_once FURGONETKA_PLUGIN_DIR . 'includes/rest_api/models/class-furgonetka-returns-model.php'.
        require_once FURGONETKA_PLUGIN_DIR . 'includes/rest_api/models/class-furgonetka-update-order-model.php';
        $this->model = new Furgonetka_Update_Order_Model();
    }

    /**
     * Get package link in meta box
     *
     *  1.0.0
     */
    public function other_package_link_callback( $order_id )
    {
        $this->other_package_link( $order_id );

        $order      = wc_get_order( $order_id );

        $point      = esc_html( $order->get_meta( '_furgonetkaPoint' ) );
        $point_name = esc_html( $order->get_meta( '_furgonetkaPointName' ) );
        $service    = esc_html( $order->get_meta( '_furgonetkaService' ) );

        if ( $service ) {
            echo esc_html( $this->get_name_of_service( $service ) ) . '<br/>' . ( esc_html( $point_name ) ? esc_html( $point_name ) : esc_html( $point ) );
        }
    }

    /**
     * Get name of service
     *
     * @param string $service - service slug.
     *
     * @return string
     */
    public function get_name_of_service( string $service )
    {
        $result = '';
        switch ( $service ) {
            case 'inpost':
                $result = 'Inpost paczkomat';
                break;
            case 'poczta':
                $result = 'Poczta';
                break;
            case 'kiosk':
                $result = 'Paczka w ruchu';
                break;
            case 'uap':
                $result = 'UPS Access Point';
                break;
            case 'dpd':
                $result = 'DPD Pickup';
                break;
            case 'dhl':
                $result = 'DHL Parcel';
                break;
            case 'fedex':
                $result = 'FedEx Punkt';
                break;
            case 'gls':
                $result = 'GLS Szybka Paczka';
                break;
            case 'orlen':
                $result = 'ORLEN Paczka';
                break;
            default:
                break;
        }
        return $result;
    }

    /**
     * Add furgonetka meta boxes for order
     *
     * @since 1.0.0
     */
    public function furgonetka_meta_boxes()
    {
        $screen = 'shop_order';

        if ( Furgonetka_Admin::is_hpos_enabled() ) {
            $screen = wc_get_page_screen_id( 'shop-order' );
        }

        add_meta_box( $this->furgonetka_admin->get_plugin_name() . '_delivery', __( 'Furgonetka.pl', 'furgonetka' ), array( $this, 'render_metabox_for_product_page' ), $screen, 'side', 'core' );
    }

    /**
     * Render html for metaboxes
     *
     * @param mixed $order - order
     */
    public function render_metabox_for_product_page( $order = null )
    {
        global $post;

        if ( $order instanceof \WC_Order ) {
            $order_id = $order->get_id();
        } else {
            $order_id = $post->ID;
        }

        $this->other_package_link_callback( $order_id );
        $this->package_information( $order_id );
    }

    /**
     * Render html for package info
     *
     * @param int $order_id - order ID
     */
    public function package_information( $order_id )
    {
        $this->view->render_packages_tracking_info( $this->render_packages_numbers( $order_id ) );
    }

    /**
     * Renders link to furgonetka iframe
     *
     * @param string|int $order_id - order ID.
     */
    public function other_package_link( $order_id )
    {
        $this->view->render_package_link(
            Furgonetka_Admin::get_plugin_admin_url(
                Furgonetka_Admin::PAGE_FURGONETKA,
                Furgonetka_Admin::ACTION_GET_PACKAGE_FORM,
                array( 'order_id' => $order_id )
            ),
            plugin_dir_url( __DIR__ ) . 'admin/img/furgonetka.svg',
            $order_id
        );
    }

    /**
     * Adding new columns to orders page
     *
     * @param array $columns - columns array.
     *
     * @return array
     */
    public function extra_order_column( array $columns ): array
    {
        $columns[ $this->furgonetka_admin->get_plugin_name() ] = __( 'Furgonetka.pl', 'furgonetka' );
        $columns['package_number']                             = __( 'Packages tracking numbers', 'furgonetka' );
        return $columns;
    }

    /**
     * Render package number.
     *
     * @param int $order_id - order ID
     *
     * @return string|void
     */
    public function render_packages_numbers( $order_id )
    {
        $package_numbers = $this->model
            ->set_order_id( $order_id )
            ->get_tracking_info();
        if ( empty( $package_numbers ) ) {
            return;
        }
        return $package_numbers;
    }

    /**
     * Get package numbers
     *
     * @param int $order_id - order ID
     *
     * @return array
     */
    public function get_packages_numbers( $order_id )
    {
        $package_numbers = $this->model
            ->set_order_id( $order_id )
            ->get_tracking_info();
        if ( empty( $package_numbers ) ) {
            return array();
        }
        return $package_numbers;
    }

    /**
     * Adding content to new columns in orders page
     *
     * @param string $column - column name.
     * @params \WC_Order|null $order - order
     *
     * @return void
     */
    public function extra_order_column_content( $column, $order = null )
    {
        global $post;

        if ( $order instanceof \WC_Order ) {
            $order_id = $order->get_id();
        } else {
            $order_id = $post->ID;
        }

        switch ( $column ) {
            case $this->furgonetka_admin->get_plugin_name():
                $this->other_package_link( $order_id );
                break;
            case 'package_number':
                $package_numbers = $this->get_packages_numbers( $order_id );
                foreach ( $package_numbers as $package_number => $package_data ) {

                    if ( Furgonetka_Admin::get_test_mode() ) {
						echo '<a style="text-decoration : underline" target="_blank" href="https://sandbox.furgonetka.pl/zlokalizuj/' . esc_html( $package_number ) . '">' . esc_html( $package_number ) . '</a><br>';
                    } else {
                        echo '<a style="text-decoration : underline" target="_blank" href="https://furgonetka.pl/zlokalizuj/' . esc_html( $package_number ) . '">' . esc_html( $package_number ) . '</a><br>';
                    }
                }
                break;
        }
    }
}
