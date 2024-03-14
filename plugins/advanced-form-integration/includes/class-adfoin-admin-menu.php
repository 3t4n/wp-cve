<?php

/**
 * Class Admin_Menu
 *
 */
class Advanced_Form_Integration_Admin_Menu {
    /**
     * Class constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Register the admin menu.
     *
     * @return void
     */
    public function admin_menu() {
        global $submenu;

        $hook1 = add_menu_page( esc_html__( 'Advanced Form Integration', 'advanced-form-integration' ), esc_html__( 'AFI', 'advanced-form-integration' ), 'manage_options', 'advanced-form-integration', array( $this, 'adfoin_routing' ), 'data:image/svg+xml;base64,' . base64_encode( '<svg width="33.36" height="33.961" viewBox="0 0 33.36 33.961" xmlns="http://www.w3.org/2000/svg"><g id="svgGroup" stroke-linecap="round" fill-rule="evenodd" font-size="9pt" stroke="#000000" stroke-width="0" fill="#000" style="stroke: rgb(0, 0, 0); stroke-width: 0; fill: rgb(0, 0, 0);" transform="matrix(0.74813, 0, 0, 0.74813, 3.999914, 4)"><path d="M 27.84 6.54 L 27.84 0.54 L 33.36 0.54 L 33.36 33.42 L 27.84 33.42 L 27.84 27.3 Q 26.22 30.18 23.01 32.07 A 13.615 13.615 0 0 1 17.854 33.811 A 17.399 17.399 0 0 1 15.54 33.96 A 15.239 15.239 0 0 1 9.568 32.799 A 14.631 14.631 0 0 1 7.62 31.8 A 15.076 15.076 0 0 1 2.202 26.042 A 17.94 17.94 0 0 1 2.04 25.74 A 17.923 17.923 0 0 1 0.144 19.43 A 22.393 22.393 0 0 1 0 16.86 A 21.081 21.081 0 0 1 0.495 12.197 A 16.579 16.579 0 0 1 2.04 8.01 Q 4.08 4.2 7.65 2.1 A 15.33 15.33 0 0 1 15.327 0.002 A 18.115 18.115 0 0 1 15.6 0 Q 19.92 0 23.1 1.86 Q 26.28 3.72 27.84 6.54 Z M 27.84 16.92 Q 27.84 13.2 26.34 10.44 A 10.988 10.988 0 0 0 23.885 7.344 A 10.241 10.241 0 0 0 22.29 6.21 Q 19.74 4.74 16.68 4.74 A 11.212 11.212 0 0 0 12.146 5.655 A 10.868 10.868 0 0 0 11.1 6.18 A 10.279 10.279 0 0 0 7.421 9.792 A 12.495 12.495 0 0 0 7.08 10.38 A 12.147 12.147 0 0 0 5.791 14.205 A 16.071 16.071 0 0 0 5.58 16.86 A 16.039 16.039 0 0 0 5.904 20.161 A 12.128 12.128 0 0 0 7.08 23.43 Q 8.58 26.22 11.1 27.69 Q 13.62 29.16 16.68 29.16 Q 19.74 29.16 22.29 27.69 A 10.407 10.407 0 0 0 26.05 23.942 A 12.581 12.581 0 0 0 26.34 23.43 A 12.444 12.444 0 0 0 27.661 19.381 A 16.307 16.307 0 0 0 27.84 16.92 Z" vector-effect="non-scaling-stroke"/></g></svg>' ) );
        add_submenu_page( 'advanced-form-integration', esc_html__( 'Advanced Form Integration', 'advanced-form-integration' ), esc_html__( 'Integrations', 'advanced-form-integration' ), 'manage_options', 'advanced-form-integration', array( $this, 'adfoin_routing' ) );
        $hook2 = add_submenu_page( 'advanced-form-integration', esc_html__( 'Integrations', 'advanced-form-integration' ), esc_html__( 'Add New', 'advanced-form-integration' ), 'manage_options', 'advanced-form-integration-new', array( $this, 'adfoin_new_integration' ) );
        $hook3 = add_submenu_page( 'advanced-form-integration', esc_html__( 'Settings', 'advanced-form-integration' ), esc_html__( 'Settings', 'advanced-form-integration'), 'manage_options', 'advanced-form-integration-settings', array( $this,'adfoin_settings') );
        $hook4 = add_submenu_page( 'advanced-form-integration', esc_html__( 'Log', 'advanced-form-integration' ), esc_html__( 'Log', 'advanced-form-integration'), 'manage_options', 'advanced-form-integration-log', array( $this,'adfoin_log') );
        add_submenu_page( 'advanced-form-integration', esc_html__( 'Get Help', 'advanced-form-integration' ), esc_html__( 'Get Help', 'advanced-form-integration'), 'manage_options', 'advanced-form-integration-help', array( $this,'adfoin_get_help') );

        add_action( 'admin_head-' . $hook1, array( $this, 'enqueue_assets' ) );
        add_action( 'admin_head-' . $hook2, array( $this, 'enqueue_assets' ) );
        add_action( 'admin_head-' . $hook3, array( $this, 'enqueue_assets' ) );
        add_action( 'admin_head-' . $hook4, array( $this, 'enqueue_assets' ) );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'adfoin-main-style' );
        wp_enqueue_script( 'adfoin-vuejs' );
        do_action( 'adfoin_custom_script' );
        wp_enqueue_script( 'adfoin-main-script' );
    }

    /**
     * Display the Tasks page.
     *
     * @return void
     */
    public function adfoin_routing() {
        include ADVANCED_FORM_INTEGRATION_INCLUDES . '/class-adfoin-list-table.php';
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {
            case 'edit':
                $this->adfoin_edit( $id );
                break;
            case 'duplicate':
                $this->adfoin_duplicate_integration($id);
                break;
            default:
                $this->adfoin_list_page() ;
                break;
        }
    }

    /*
     * This function generates the list of connections
     */
    public function adfoin_list_page() {
        if ( isset( $_GET['status'] ) ) {
            $status = $_GET['status'];
        }

        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">
                <?php _e( 'Integrations', 'advanced-form-integration' ); ?>
            </h1>
            <a href="<?php echo admin_url( 'admin.php?page=advanced-form-integration-new' ); ?>" class="page-title-action"><?php _e( 'Add New', 'advanced-form-integration' ); ?></a>

            <form id="form-list" method="post">
                <input type="hidden" name="page" value="advanced-form-integration"/>

                <?php
                $list_table = new Advanced_Form_Integration_List_Table();
                $list_table->prepare_items();
                $list_table->display();
                ?>
            </form>
        </div>
        <?php
    }

    /*
     * Handles new connection
     */
    public function adfoin_new_integration(){

        $form_providers   = adfoin_get_form_providers();
        $action_providers = adfoin_get_action_porviders();
        ksort( $action_providers );

        require_once ADVANCED_FORM_INTEGRATION_VIEWS . '/new_integration.php';
    }

    /*
     * Handles connection view
     */
    public function adfoin_view( $id='' ) {
    }

    /*
     * Handles connection edit
     */
    public function adfoin_edit( $id='' ) {

        if ( $id ) {
            require_once ADVANCED_FORM_INTEGRATION_VIEWS . '/edit_integration.php';
        }
    }

    /*
     * Settings Submenu View
     */
    public function adfoin_settings( $value = '' ) {
        $tabs = adfoin_get_settings_tabs();

        include ADVANCED_FORM_INTEGRATION_VIEWS . '/settings.php';
    }

    /*
     * Log Submenu View
     */
    public function adfoin_log( $value = '' ) {
        include ADVANCED_FORM_INTEGRATION_INCLUDES . '/class-adfoin-log-table.php';
        
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {
            case 'view':
                $this->adfoin_log_view( $id );
                break;
            default:
                $this->adfoin_log_list_page() ;
                break;
        }

    }

    /*
     * Get Help Submenu View
     */
    public function adfoin_get_help( $value = '' ) {
        include ADVANCED_FORM_INTEGRATION_VIEWS . '/get_help.php';
    }

    /*
    * This function generates the list of connections
    */
    public function adfoin_log_list_page() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">
                <?php _e( 'Log', 'advanced-form-integration' ); ?>
            </h1>

            <form id="form-list" method="post">
                <input type="hidden" name="page" value="advanced-form-integration-log"/>

                <?php
                $list_table = new Advanced_Form_Integration_Log_Table();
                $list_table->prepare_items();
                $list_table->search_box( __( 'Search Log', 'advanced-form-integration' ), 'afi-log-search' );
                $list_table->views();
                $list_table->display();
                ?>
            </form>
        </div>
        <?php
    }

    /*
     * Handles log view
     */
    public function adfoin_log_view( $id='' ) {

        if ( $id ) {
            require_once ADVANCED_FORM_INTEGRATION_VIEWS . '/view_log.php';
        }
    }

    /*
     * Relation Status Change adfoin_status
     */
    public function adfoin_duplicate_integration( $id = '' ) {

        global $wpdb;

        $table         = $wpdb->prefix . "adfoin_integration";
        $sql           = $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id );
        $data          = $wpdb->get_row( $sql, ARRAY_A );
        $data['title'] =  __( 'Copy of ', 'advanced-form-integration') . $data['title'];
        $result        = $wpdb->insert(
            $table,
            array(
                'title'           => $data['title'],
                'form_provider'   => $data['form_provider'],
                'form_id'         => $data['form_id'],
                'form_name'       => $data['form_name'],
                'action_provider' => $data['action_provider'],
                'task'            => $data['task'],
                'data'            => $data['data'],
                'status'          => 0
            )
        );

        wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration' ) );
    }
}