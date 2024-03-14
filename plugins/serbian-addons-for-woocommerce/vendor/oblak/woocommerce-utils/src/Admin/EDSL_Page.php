<?php
/**
 * EDSL_Page class file.
 *
 * @package WooCommerce Utils
 */

namespace Oblak\WooCommerce\Admin;

use Oblak\WooCommerce\Data\Extended_Data_List_Table;

/**
 * Extended data store list page
 */
class EDSL_Page {

    /**
     * Page namespace
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Page base
     *
     * This is the first part of the URL, after the admin URL.
     *
     * @var string
     */
    protected $base = '';

    /**
     * Page ID
     *
     * @var string
     */
    protected $id = '';

    /**
     * Page title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Page menu title
     *
     * @var string
     */
    protected $menu_title = null;

    /**
     * Page capability
     *
     * @var string
     */
    protected $capability = '';

    /**
     * Page hook
     *
     * @var string
     */
    protected $hook;

    /**
     * EDS entity
     *
     * @var string
     */
    protected $entity;

    /**
     * List table object
     *
     * @var Extended_Data_List_Table
     */
    protected $table;

    /**
     * List table class
     *
     * @var string
     */
    protected $table_class;
    /**
     * Whether to enable inline edit
     *
     * @var bool
     */
    protected $inline_edit;


    /**
     * Class constructor
     *
     * @param bool $inline_edit Whether to enable inline edit.
     */
	public function __construct( $inline_edit = false ) {
        $this->menu_title  = $this->menu_title ?? $this->title;
        $this->inline_edit = $inline_edit;

        $this->load_hooks();
	}

    /**
     * Loads hooks needed for the page to function
     */
    private function load_hooks() {
		add_filter( 'admin_body_class', array( $this, 'add_page_class' ) );
        add_action( 'admin_menu', array( $this, 'add_menu_page' ), 100 );
        add_filter( 'set-screen-option', array( $this, 'save_screen_options' ), 50, 3 );
    }

    /**
     * Add page class to body
     *
     * @param  string $classes Body classes.
     * @return string
     */
	public function add_page_class( $classes ) {
		global $pagenow;

		$page = wc_clean( wp_unslash( $_GET['page'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! str_contains( $this->base, $pagenow ) || $page !== $this->id ) {
            return $classes;
		}

        return sprintf(
            '%s %s-%s',
            $classes,
            $this->namespace,
            $this->id
        );
	}

    /**
     * Returns the screen options for the page
     *
     * Needs to be implemented by the child class
     *
     * @return array
     */
    protected function get_screen_options() {
        return array(
            'per_page' => array(
                'default' => 20,
                'option'  => str_replace( '-', '_', "edit_{$this->entity}s_per_page" ),
            ),
        );
    }

    /**
     * Adds the menu page to the admin menu
     */
    public function add_menu_page() {
        $this->hook = add_submenu_page(
            $this->base,
            $this->title,
            $this->menu_title,
            $this->capability,
            "{$this->namespace}-{$this->id}",
            array( $this, 'output' )
        );

        add_action( "load-{$this->hook}", array( $this, 'set_screen_options' ) );
    }

    /**
     * Sets the screen options
     */
    public function set_screen_options() {
        $options = $this->get_screen_options();

        foreach ( $options as $option => $args ) {
            add_screen_option( $option, $args );
        }

        // Include the list table class.
		! class_exists( 'WP_List_Table' ) &&
        require ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

        $this->table = new $this->table_class(
            $this->entity,
            array(
                'singular' => str_replace( '-', '_', $this->entity ),
                'plural'   => str_replace( '-', '_', $this->entity ) . 's',
                'ajax'     => $this->inline_edit,
            )
        );

        if ( $this->inline_edit ) {
            wp_enqueue_script( 'inline-edit-post' );
        }
    }

    /**
     * Undocumented function
     *
     * @param  mixed  $screen_option The value to save.
     * @param  string $option        The option name.
     * @param  int    $value         The option value.
     * @return mixed                 The value to save.
     */
    public function save_screen_options( $screen_option, $option, $value ) {
        foreach ( $this->get_screen_options() as $args ) {
            if ( $args['option'] === $option ) {
                $screen_option = $value;
            }
        }

        return $screen_option;
    }

    /**
     * Outputs the page content
     */
    public function output() {
        require __DIR__ . '/Views/html-admin-page-edsl.php';
    }
}
