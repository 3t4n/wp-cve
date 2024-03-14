<?php

namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

class Builder {

    public $ajax = false;

    /**
     * Constructor of the class.
     * 
     * @since 2.0.12
     */
    public function __construct() {
        
        add_action('admin_menu', array($this, 'register_shortcode_page'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

        add_action('wp_ajax_gsbeh_update_shortcode', array($this, 'update_shortcode'));
        add_action('wp_ajax_gsbeh_create_shortcode', array($this, 'create_shortcode'));
        add_action('wp_ajax_gsbeh_clone_shortcode', array($this, 'clone_shortcode'));
        add_action('wp_ajax_gsbeh_get_shortcode', array($this, 'get_shortcode'));
        add_action('wp_ajax_gsbeh_get_shortcodes', array($this, 'getShortcodes'));
        add_action('wp_ajax_gsbeh_delete_shortcodes', array($this, 'delete_shortcode'));
        add_action('wp_ajax_gsbeh_temp_save_shortcode_settings', array($this, 'save_temp_shortcode_settings'));

        add_action('wp_ajax_gsbeh_get_shortcode_pref', array($this, 'get_preferences'));
        add_action('wp_ajax_gsbeh_save_shortcode_pref', array($this, 'save_preferences'));

        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'template_include', array( $this, 'display' ) );
        add_action( 'show_admin_bar', array( $this, 'hide_adminbar' ) );
    }

    /**
     * Registers a submenu item in WordPress dashboard.
     * 
     * @since  2.0.12
     * @return void
     */
    public function register_shortcode_page() {
        add_menu_page(
            __('Behance Shortcode', 'gs-behance'),
            __('Behance Portfolio', 'gs-behance'),
            'manage_options',
            'gs-behance-shortcode',
            array($this, 'view'),
            GSBEH_PLUGIN_URI . '/assets/img/icon.png',
            35
        );

        add_submenu_page(
            'gs-behance-shortcode',
            __('Shortcode', 'gs-behance'),
            __('Shortcode', 'gs-behance'),
            'manage_options',
            'gs-behance-shortcode',
            array($this, 'view'),
            5
        );
    }

    /**
     * Includes view of shortcode builder.
     * 
     * @since  2.0.12
     * @return void
     */
    public function view() {
        include GSBEH_PLUGIN_DIR . 'includes/shortcode-builder/page.php';
    }

    /**
     * Enqueue admin scripts.
     * 
     * @since  2.0.12
     * @return void
     */
    public function admin_scripts($hook) {

        if ('toplevel_page_gs-behance-shortcode' !== $hook) {
            return;
        }

        wp_register_style( 'gs-zmdi-fonts', GSBEH_PLUGIN_URI . '/assets/libs/material-design-iconic-font/css/material-design-iconic-font.min.css', '', GSBEH_VERSION );

        wp_enqueue_style( 'gs-behance-builder-shortcode', GSBEH_PLUGIN_URI . '/assets/admin/css/shortcode.min.css', array('gs-zmdi-fonts'), GSBEH_VERSION );

        $data = array(
            'nonce' => array(
                'create_shortcode'                 => wp_create_nonce('_gsbeh_create_shortcode_gs_'),
                'clone_shortcode'                  => wp_create_nonce('_gsbeh_clone_shortcode_gs_'),
                'update_shortcode'                 => wp_create_nonce('_gsbeh_update_shortcode_gs_'),
                'delete_shortcodes'                => wp_create_nonce('_gsbeh_delete_shortcodes_gs_'),
                'temp_save_shortcode_settings'     => wp_create_nonce('_gsbeh_temp_save_shortcode_settings_gs_'),
                'save_shortcode_pref'              => wp_create_nonce('_gsbeh_save_shortcode_pref_gs_'),
                'sync_data'                        => wp_create_nonce('_gsbeh_sync_data_gs_')
            ),
            'ajaxurl'  => admin_url('admin-ajax.php'),
            'adminurl' => admin_url(),
            'siteurl'  => home_url()
        );

        $data['shortcode_settings'] = $this->get_shortcode_default_settings();
        $data['shortcode_options']  = $this->get_defaults();
        $data['translations']       = $this->get_strings();
        $data['preference']         = $this->get_prefs();
        $data['be_meta']            = get_option('be_meta', []);
        $data['preference_options'] = $this->get_shortcode_preference_options();

        wp_enqueue_script( 'gs-behance-shortcode', GSBEH_PLUGIN_URI . '/assets/admin/js/shortcode.min.js', array('jquery'), GSBEH_VERSION, true );

        wp_localize_script('gs-behance-shortcode', 'GS_BEHANCE_DATA', $data);
    }

    /**
     * Returns $wpdb global variable.
     * 
     * @since  1.10.14
     */
    public function get_wp_db() {
        global $wpdb;

        if (wp_doing_ajax()) {
            $wpdb->show_errors = false;
        }

        return $wpdb;
    }

    /**
     * Get defined database columns.
     * 
     * @since  1.10.14
     * @return array Shortcode table database columns.
     */
    public function get_db_columns() {
        return array(
            'shortcode_name'     => '%s',
            'shortcode_settings' => '%s'
        );
    }

    /**
     * Checks for database errors.
     * 
     * @since  1.10.14
     * @return bool true/false based on the error status.
     */
    public function error() {
        $wpdb = $this->get_wp_db();

        if ('' === $wpdb->last_error) {
            return false;
        }

        return true;
    }

    /**
     * Ajax endpoint for update shortcode.
     * 
     * @since  2.0.12
     * @return int The numbers of row affected.
     */
    public function update_shortcode() {

        if (!check_admin_referer('_gsbeh_update_shortcode_gs_') || !current_user_can('manage_options')) {
            wp_send_json_error( __('Unauthorised Request', 'gs-behance'), 401 );
        }

        $shortcode_id = !empty($_POST['id']) ? absint($_POST['id']) : null;

        if ( empty( $shortcode_id ) ) {
            wp_send_json_error(__('Shortcode ID missing', 'gs-behance'), 400);
        }

        $shortcode    = $this->_get_shortcode($shortcode_id, false);

        if (empty($shortcode)) wp_send_json_error(__('No shortcode found to update', 'gs-behance'), 404);        

        $shortcode_name      = !empty($_POST['shortcode_name']) ? sanitize_text_field($_POST['shortcode_name']) : sanitize_text_field($shortcode['shortcode_name']);
        $shortcode_settings  = !empty($_POST['shortcode_settings']) ? $_POST['shortcode_settings'] : $shortcode['shortcode_settings'];

        $shortcode_settings  = $this->validate_shortcode_settings($shortcode_settings);

        $tableName           = plugin()->db->get_shortcodes_table();
        $wpdb                = $this->get_wp_db();

        $data = array(
            "shortcode_name"     => $shortcode_name,
            "shortcode_settings" => json_encode($shortcode_settings)
        );

        $updateId = $wpdb->update(
            $tableName,
            $data,
            array(
                'id' => $shortcode_id
            ),
            $this->get_db_columns()
        );

        if ($this->error()) {
            wp_send_json_error(
                sprintf(
                    __('Database Error: %1$s', 'gs-behance'),
                    $wpdb->last_error
                ),
                500
            );
        }

        wp_cache_delete('gsbeh_shortcodes', 'gs_behance');        

        do_action( 'gsp_shortcode_created', $updateId );        
        do_action( 'gsp_shortcode_updated', $updateId );

        wp_send_json_success(array(
            'message'      => __('Shortcode updated', 'gs-behance'),
            'shortcode_id' => $updateId
        ));
    }

    /**
     * Ajax endpoint for create shortcode.
     * 
     * @since  2.0.12
     * @return json WP json response.
     */
    public function create_shortcode() {
        if (!check_admin_referer('_gsbeh_create_shortcode_gs_') || !current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorised Request', 'gs-behance'), 401);
        }

        $shortcode_settings = !empty($_POST['shortcode_settings']) ? $_POST['shortcode_settings'] : '';
        $shortcode_name     = !empty($_POST['shortcode_name']) ? sanitize_text_field($_POST['shortcode_name']) : __('Untitled', 'gs-behance');

        if (empty($shortcode_settings) || !is_array($shortcode_settings)) {
            wp_send_json_error(__('Please configure the settings properly', 'gs-behance'), 206);
        }

        $shortcode_settings = $this->validate_shortcode_settings($shortcode_settings);

        $wpdb               = $this->get_wp_db();
        $tableName          = plugin()->db->get_shortcodes_table();

        $data = array(
            "shortcode_name"     => $shortcode_name,
            "shortcode_settings" => json_encode($shortcode_settings)
        );

        $wpdb->insert($tableName, $data, $this->get_db_columns());

        // check for database error
        if ($this->error()) {
            wp_send_json_error(sprintf(__('Database Error: %s'), $wpdb->last_error), 500);
        }

        wp_cache_delete('gsbeh_shortcodes', 'gs_behance');
        
        do_action('gsp_shortcode_created', $wpdb->insert_id);

        // send success response with inserted id
        wp_send_json_success(array(
            'message'      => __('Shortcode created successfully', 'gs-behance'),
            'shortcode_id' => $wpdb->insert_id
        ));
    }

    /**
     * Ajax endpoint for clone a shortcode.
     * 
     * @since  2.0.12
     * @return json WP json response.
     */
    // TODO: check if the cloning is properly?
    public function clone_shortcode() {
        if (!check_admin_referer('_gsbeh_clone_shortcode_gs_') || !current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorised Request', 'gs-behance'), 401);
        }

        $clone_id  = !empty($_POST['clone_id']) ? absint($_POST['clone_id']) : '';

        if (empty($clone_id)) {
            wp_send_json_error(__('Clone Id not provided', 'gs-behance'), 400);
        }

        $clone_shortcode = $this->_get_shortcode($clone_id, false);

        if (empty($clone_shortcode)) {
            wp_send_json_error(__('No shortcode found to clone.', 'gs-behance'), 404);
        }

        $shortcode_settings = $clone_shortcode['shortcode_settings'];
        $shortcode_name     = $clone_shortcode['shortcode_name'] . ' ' . __('- Cloned', 'gs-behance');
        $shortcode_settings = $this->validate_shortcode_settings($shortcode_settings);

        $wpdb      = $this->get_wp_db();
        $tableName = plugin()->db->get_shortcodes_table();

        $data = array(
            "shortcode_name"     => $shortcode_name,
            "shortcode_settings" => json_encode($shortcode_settings)
        );

        $wpdb->insert(
            $tableName,
            $data,
            $this->get_db_columns()
        );

        if ($this->error()) {
            wp_send_json_error(sprintf(__('Database Error: %s'), $wpdb->last_error), 500);
        }

        // Get the cloned shortcode
        $shotcode = $this->_get_shortcode($wpdb->insert_id, false);

        wp_cache_delete('gsbeh_shortcodes', 'gs_behance');        
        do_action('gsp_shortcode_created', $wpdb->insert_id);

        // send success response with inserted id
        wp_send_json_success(array(
            'message'   => __('Shortcode cloned successfully', 'gs-behance'),
            'shortcode' => $shotcode,
        ));
    }

    /**
     * Ajax endpoint for get a shortcode.
     * 
     * @since  1.10.14
     * @return void
     */
    public function get_shortcode() {
        $shortcode_id = !empty($_GET['id']) ? absint($_GET['id']) : null;
        return $this->_get_shortcode($shortcode_id, wp_doing_ajax());
    }

    /**
     * Ajax endpoint for get shortcodes.
     * 
     * @since  2.0.12
     * @return JSON The response as json object.
     */
    public function getShortcodes() {
        return $this->fetch_shortcodes(null, wp_doing_ajax());
    }

    /**
     * Ajax endpoint for deleting shortcode.
     * 
     * @since  2.0.12
     * @return JSON The response as json object.
     */
    public function delete_shortcode() {
        if (!check_admin_referer('_gsbeh_delete_shortcodes_gs_') || !current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorised Request', 'gs-behance'), 401);
        }

        $ids = isset($_POST['ids']) ? $_POST['ids'] : null;

        if (empty($ids)) {
            wp_send_json_error(__('No shortcode ids provided', 'gs-behance'), 400);
        }

        $wpdb  = $this->get_wp_db();
        $count = count($ids);

        $ids = implode(',', array_map('absint', $ids));
        $tableName = plugin()->db->get_shortcodes_table();
        $wpdb->query("DELETE FROM {$tableName} WHERE ID IN($ids)");

        if ($this->error()) {
            wp_send_json_error(sprintf(__('Database Error: %s'), $wpdb->last_error), 500);
        }

        $m = _n("Shortcode has been deleted", "Shortcodes have been deleted", $count, 'gs-behance');

        wp_cache_delete('gsbeh_shortcodes', 'gs_behance');        

        do_action( 'gsp_shortcode_created' );        
        do_action( 'gsp_shortcode_deleted' );
        
        wp_send_json_success(['message' => $m]);
    }

    /**
     * Save shortcode temporary default settings.
     * 
     * @since  2.0.12
     * 
     * @return saved status.
     */
    public function save_temp_shortcode_settings() {
        
        if ( !check_admin_referer('_gsbeh_temp_save_shortcode_settings_gs_') || !current_user_can('manage_options') )
        wp_send_json_error( __('Unauthorised Request', 'gs-behance'), 401 );
        
        $temp_key = isset( $_POST['temp_key'] ) ? $_POST['temp_key'] : null;
        $shortcode_settings = isset( $_POST['shortcode_settings'] ) ? $_POST['shortcode_settings'] : null;

        if ( empty($temp_key) ) wp_send_json_error( __('No temp key provided', 'gs-behance'), 400 );
        if ( empty($shortcode_settings) ) wp_send_json_error( __('No temp settings provided', 'gs-behance'), 400 );

        delete_transient( $temp_key );

        $shortcode_settings = $this->validate_shortcode_settings( $shortcode_settings );
        set_transient( $temp_key, $shortcode_settings, DAY_IN_SECONDS ); // save the transient for 1 day

        wp_send_json_success([
            'message' => __('Temp data saved', 'gs-behance'),
        ]);
    }

    /**
     * Returns the shortcode default settings.
     * 
     * @since  2.0.12
     * @return array The predefined default settings for each shortcode.
     */
    public function get_shortcode_default_settings() {
        return [
            'userid'                  => '',
            'count'                   => 9,
            'theme'                   => 'gs_beh_theme1',
            'enable_autoplay'         => true,
            'speed'                   => 1400,
            'delay'                   => 3000,
            'field'                   => '',
            'link_target'             => '_blank',
            'columns'                 => 3,
            'columns_tablet'          => 4,
            'columns_mobile_portrait' => 6,
            'columns_mobile'          => 12
        ];
    }

    /**
     * Returns the shortcode by given id.
     * 
     * @since  2.0.12
     * 
     * @param mixed $shortcode_id The shortcode id.
     * @param bool  $is_ajax       Ajax status.
     * 
     * @return array|JSON The shortcode.
     */
    public function _get_shortcode($shortcode_id, $is_ajax = false) {

        if (empty($shortcode_id)) {
            if ($is_ajax) wp_send_json_error( __('Shortcode ID missing', 'gs-behance'), 400 );            

            return false;
        }

        $shortcode = wp_cache_get( 'gs_beh_shortcode' . $shortcode_id );

        // Return the cache if found
        if ($shortcode !== false) {
            if ($is_ajax) wp_send_json_success($shortcode);
            return $shortcode;
        }

        $wpdb      = $this->get_wp_db();
        $tableName = plugin()->db->get_shortcodes_table();
        $shortcode = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tableName} WHERE id = %d LIMIT 1", absint($shortcode_id)), ARRAY_A);


        if ($shortcode) {
            $shortcode["shortcode_settings"] = json_decode($shortcode["shortcode_settings"], true);

            wp_cache_add( 'gsbeh_shortcode' . $shortcode_id, $shortcode );

            if ($is_ajax)  wp_send_json_success($shortcode);            

            return $shortcode;
        }

        if ($is_ajax) wp_send_json_error(__('No shortcode found', 'gs-behance'), 404);        

        return false;
    }

    /**
     * Ajax endpoint for get shortcodes.
     * 
     * @since  2.0.12
     * @return JSON The response as json object.
     */
    public function get_shortcodes_as_list() {
        return $this->fetch_shortcodes(null, false);
    }

    /**
     * Fetch shortcodes by given shortcode ids.
     * 
     * @since  2.0.12
     * 
     * @param mixed $shortcode_ids Shortcode ids.
     * @param bool  $is_ajax       Ajax status.
     * @param bool  $minimal        Shortcode minimal result.
     * 
     * @return array|json Fetched shortcodes.
     */
    public function fetch_shortcodes($shortcode_ids = [], $is_ajax = false, $minimal = false) {
        $wpdb      = $this->get_wp_db();
        $fields    = $minimal ? 'id, shortcode_name' : '*';
        $tableName = plugin()->db->get_shortcodes_table();

        if (empty($shortcode_ids)) {            
            $shortcodes = $wpdb->get_results("SELECT {$fields} FROM {$tableName} ORDER BY id DESC", ARRAY_A);
            wp_cache_add('gsbeh_shortcodes', $shortcodes, 'gs_behance');
            
        } else {
            $how_many     = count($shortcode_ids);
            $placeholders = array_fill(0, $how_many, '%d');
            $format       = implode(', ', $placeholders);
            $query        = "SELECT {$fields} FROM {$tableName} WHERE id IN($format)";
            $shortcodes   = $wpdb->get_results($wpdb->prepare($query, $shortcode_ids), ARRAY_A);
            wp_cache_add( 'gsbeh_shortcodes', $shortcodes, 'gs_behance');
        }

        // check for database error
        if ($this->error()) {
            wp_send_json_error(sprintf(__('Database Error: %s'), $wpdb->last_error));
        }

        if ($is_ajax) {
            wp_send_json_success($shortcodes);
        }

        return $shortcodes;
    }

    /**
     * Validate given shortcode settings.
     * 
     * @since  2.0.12
     * 
     * @param  array $settings
     * @return array Shortcode settings.
     */
    public function validate_shortcode_settings($shortcode_settings) {

        $shortcode_settings['userid']                           = sanitize_text_field( $shortcode_settings['userid'] );
        $shortcode_settings['count']                            = intval( $shortcode_settings['count'] );
        $shortcode_settings['theme']                            = sanitize_text_field( $shortcode_settings['theme'] );
        $shortcode_settings['enable_autoplay']                  = wp_validate_boolean( $shortcode_settings['enable_autoplay'] );
        $shortcode_settings['speed']                            = intval( $shortcode_settings['speed'] );
        $shortcode_settings['delay']                            = intval( $shortcode_settings['delay'] );
        $shortcode_settings['field']                            = sanitize_text_field( $shortcode_settings['field'] );
        $shortcode_settings['link_target']                      = sanitize_text_field( $shortcode_settings['link_target'] );
        $shortcode_settings['columns']                          = sanitize_text_field( $shortcode_settings['columns'] );
        $shortcode_settings['columns_tablet']                   = sanitize_text_field( $shortcode_settings['columns_tablet'] );
        $shortcode_settings['columns_mobile_portrait']          = sanitize_text_field( $shortcode_settings['columns_mobile_portrait'] );
        $shortcode_settings['columns_mobile']                   = sanitize_text_field( $shortcode_settings['columns_mobile'] );


        $shortcode_settings = shortcode_atts( $this->get_shortcode_default_settings(), $shortcode_settings );
        return (array) $shortcode_settings;
    }

    // Options
    /**
     * Returns themes options.
     * 
     * @since  2.0.12
     * @return array Themes options.
     */
    public function get_themes() {

        $free_themes = [
            [
                'value' => 'gs_beh_theme1',
                'label' => __('Projects', 'gs-behance')
            ]
        ];

        $pro_themes = [
            [
                'value' => 'gs_beh_theme2',
                'label' => __('Stats Style 1', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme2_hover',
                'label' => __('Stats Style 2', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme3',
                'label' => __('Hover Style 1', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme3_style2',
                'label' => __('Hover Style 2', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme3_style3',
                'label' => __('Hover Style 3', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme3_style4',
                'label' => __('Hover Style 4', 'gs-behance')
            ],
            [
                'value' => 'gs_popup_style_1',
                'label' => __('Popup Style 1', 'gs-behance')
            ],
            [
                'value' => 'gs_popup_style_2',
                'label' => __('Popup Style 2', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme5',
                'label' => __('Slider Style 1', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme6',
                'label' => __('Profile Style 1', 'gs-behance')
            ],
            [
                'value' => 'gs_beh_theme7',
                'label' => __('Filter Style 1', 'gs-behance')
            ]
        ];

        if (!wbp_fs()->is_paying_or_trial()) {

            $pro_themes = array_map(function ($item) {
                $item['pro'] = true;
                return $item;
            }, $pro_themes);
        }

        return array_merge($free_themes, $pro_themes);
    }

    /**
     * Returns filtered themes options.
     * 
     * @since  2.0.12
     * @return array Themes options.
     */
    public function get_filtered_themes() {
        return array(
            array(
                'value' => 'gs_beh_theme1',
                'label' => __('Theme 1 (Projects)', 'gs-behance')
            ),
            array(
                'value' => 'gs_beh_theme2',
                'label' => __('Theme 2 (Stats)', 'gs-behance')
            ),
            array(
                'value' => 'gs_beh_theme2_hover',
                'label' => __('Theme 2 (Stats Hover)', 'gs-behance')
            ),
            array(
                'value' => 'gs_beh_theme3',
                'label' => __('Theme 3 (Hover)', 'gs-behance')
            ),
            array(
                'value' => 'gs_popup_style_1',
                'label' => __('Theme 4 (Popup)', 'gs-behance')
            ),
            array(
                'value' => 'gs_beh_theme5',
                'label' => __('Theme 5 (Slider)', 'gs-behance')
            )
        );
    }

    /**
     * Returns link types options.
     * 
     * @since  2.0.12
     * @return array Link type options.
     */
    public function get_link_types() {
        return array(
            array(
                'value' => '_blank',
                'label' => __('New Tab', 'gs-behance')
            ),
            array(
                'value' => '_self',
                'label' => __('Same Window', 'gs-behance')
            )
        );
    }

    /**
     * Retrives WP registered possible thumbnail sizes.
     * 
     * @since  1.10.14
     * @return array   image sizes.
     */
    public function get_possible_thumbnail_sizes() {
        $sizes = get_intermediate_image_sizes();

        if (empty($sizes)) {
            return [];
        }

        $result = [];
        foreach ($sizes as $size) {
            $result[] = [
                'label' => ucwords(preg_replace('/_|-/', ' ', $size)),
                'value' => $size
            ];
        }

        return $result;
    }

    /**
     * Returns predefined columns
     * 
     * @since  2.0.12
     * @return array Predefined columns.
     */
    public function get_columns() {
        return array(
            array(
                'label' => __('1 Column', 'gs-behance'),
                'value' => '12'
            ),
            array(
                'label' => __('2 Columns', 'gs-behance'),
                'value' => '6'
            ),
            array(
                'label' => __('3 Columns', 'gs-behance'),
                'value' => '4'
            ),
            array(
                'label' => __('4 Columns', 'gs-behance'),
                'value' => '3'
            ),
            array(
                'label' => __('5 Columns', 'gs-behance'),
                'value' => '2_4'
            ),
            array(
                'label' => __('6 Columns', 'gs-behance'),
                'value' => '2'
            )
        );
    }

    /**
     * Returns default options.
     * 
     * @since  2.0.12
     * @return array Default options.
     */
    public function get_defaults() {
        return [
            'userid'                      => '',
            'themes'                      => $this->get_themes(),
            'filteredThemes'              => $this->get_filtered_themes(),
            'link_targets'                => $this->get_link_types(),
            'gs_member_thumbnail_sizes'   => $this->get_possible_thumbnail_sizes(),
            // responsive
            'columns'                 => $this->get_columns(),
            'columns_tablet'          => $this->get_columns(),
            'columns_mobile_portrait' => $this->get_columns(),
            'columns_mobile'          => $this->get_columns(),
            'order' => array(
                array(
                    'label' => __('DESC', 'gs-behance'),
                    'value' => 'DESC'
                ),
                array(
                    'label' => __('ASC', 'gs-behance'),
                    'value' => 'ASC'
                )
            )
        ];
    }

    /**
     * Ajax endpoint for retriving shortcode preferences.
     * 
     * @since 2.0.12
     */
    public function get_preferences() {
        $this->ajax = true;
        return $this->get();
    }

    /**
     * Ajax endpoint for saving shortcode preferences.
     * 
     * @since 2.0.12
     */
    public function save_preferences($nonce = null) {
        if (!$nonce) {
            $nonce = wp_create_nonce('_gsbeh_save_shortcode_pref_gs_');
        }

        if (empty($_POST['prefs'])) {
            wp_send_json_error(__('No preference provided', 'gs-behance'), 400);
        }

        $this->save($nonce, $_POST['prefs'], true);
    }

    /**
     * Get shortcode preferences options.
     * 
     * @since  2.0.12
     * @return array
     */
    public function get_shortcode_preference_options() {
        return [];
    }

    /**
     * Get default shortcode preferences.
     * 
     * @since  2.0.12
     * @return array
     */
    public function get_prefs() {
        return array(
            'gsbeh_custom_css'  => '',
            'disable_beh_lazy_load' => true,
            'lazy_load_class'   => 'skip-lazy'
        );
    }

    /**
     * Helper method for saving shortcode preferences.
     * 
     * @since  2.0.12
     * @return wp_json response.
     */
    public function save($nonce, $settings, $is_ajax) {
        if (!wp_verify_nonce($nonce, '_gsbeh_save_shortcode_pref_gs_')) {
            if ($is_ajax) wp_send_json_error(__('Unauthorised Request', 'gs-behance'), 401);
            return false;
        }

        // Maybe add validation?
        update_option('gsbeh_shortcode_prefs', $settings, 'yes');

        do_action('gsp_preference_update');

        if ($is_ajax) {
            wp_send_json_success(__('Preference saved', 'gs-behance'));
        }
    }

    function get_shortcode_settings( $id, $is_preview = false ) {
    
        $default_settings = array_merge( ['id' => $id, 'is_preview' => $is_preview], $this->get_shortcode_default_settings() );
    
        if ( $is_preview ) return shortcode_atts( $default_settings, get_transient($id) );
        
        $shortcode = $this->_get_shortcode($id);
    
        return shortcode_atts( $default_settings, (array) $shortcode['shortcode_settings'] );
    
    }

    /**
     * Get shortcode preferences.
     * 
     * @since 2.0.12
     * 
     * @param  bool         $is_ajax If want the reponse as ajax response.
     * @return wp_json|array 
     */
    public function get( $key = '', $default = '' ) {
        $pref = get_option( 'gsbeh_shortcode_prefs' );

        if ( empty( $pref ) ) {
            $pref = $this->get_prefs();
            $this->save( wp_create_nonce('_gsbeh_save_shortcode_pref_gs_'), $pref, false );
        }

        if ( $this->ajax ) {
            wp_send_json_success( $pref );
        }

        if ( ! $this->ajax && ! empty( $key ) ) {
            return ! empty( $pref[ $key ] ) ? $pref[ $key ] : $default;
        }

        return $pref;
    }

    /**
     * Enqueue scripts for the preview only.
     * 
     * @since  2.0.12
     * @return void
     */
    public function scripts($hook) {
        if ( ! plugin()->helpers->is_preview() ) {
            return;
        }
        wp_enqueue_style( 'gs-behance-shortcode-preview', GSBEH_PLUGIN_URI . '/assets/admin/css/shortcode-preview.min.css', '', GSBEH_VERSION );
    }

    /**
     * Displays the shortcode preview.
     * 
     * @since  2.0.12
     * @return void
     */
    public function display($template) {
        global $wp, $wp_query;

        if ( plugin()->helpers->is_preview() ) {
            // Create our fake post
            $post_id              = rand(1, 99999) - 9999999;
            $post                 = new \stdClass();
            $post->ID             = $post_id;
            $post->post_author    = 1;
            $post->post_date      = current_time('mysql');
            $post->post_date_gmt  = current_time('mysql', 1);
            $post->post_title     = __('Shortcode Preview', 'gs-behance');
            $post->post_content   = '[gs_behance preview="yes" id="' . $_REQUEST['gsbeh_shortcode_preview'] . '"]';
            $post->post_status    = 'publish';
            $post->comment_status = 'closed';
            $post->ping_status    = 'closed';
            $post->post_name      = 'fake-page-' . rand(1, 99999); // append random number to avoid clash
            $post->post_type      = 'page';
            $post->filter         = 'raw'; // important!

            // Convert to WP_Post object
            $wp_post = new \WP_Post($post);

            // Add the fake post to the cache
            wp_cache_add($post_id, $wp_post, 'posts');

            // Update the main query
            $wp_query->post                 = $wp_post;
            $wp_query->posts                = array($wp_post);
            $wp_query->queried_object       = $wp_post;
            $wp_query->queried_object_id    = $post_id;
            $wp_query->found_posts          = 1;
            $wp_query->post_count           = 1;
            $wp_query->max_num_pages        = 1;
            $wp_query->is_page              = true;
            $wp_query->is_singular          = true;
            $wp_query->is_single            = false;
            $wp_query->is_attachment        = false;
            $wp_query->is_archive           = false;
            $wp_query->is_category          = false;
            $wp_query->is_tag               = false;
            $wp_query->is_tax               = false;
            $wp_query->is_author            = false;
            $wp_query->is_date              = false;
            $wp_query->is_year              = false;
            $wp_query->is_month             = false;
            $wp_query->is_day               = false;
            $wp_query->is_time              = false;
            $wp_query->is_search            = false;
            $wp_query->is_feed              = false;
            $wp_query->is_comment_feed      = false;
            $wp_query->is_trackback         = false;
            $wp_query->is_home              = false;
            $wp_query->is_embed             = false;
            $wp_query->is_404               = false;
            $wp_query->is_paged             = false;
            $wp_query->is_admin             = false;
            $wp_query->is_preview           = false;
            $wp_query->is_robots            = false;
            $wp_query->is_posts_page        = false;
            $wp_query->is_post_type_archive = false;

            // Update globals
            $GLOBALS['wp_query'] = $wp_query;
            $wp->register_globals();

            include GSBEH_PLUGIN_DIR . 'includes/shortcode-builder/preview.php';

            return;
        }

        return $template;
    }

    /**
     * Hide admin bar from the preview window.
     * 
     * @since  2.0.12
     * @return bool
     */
    public function hide_adminbar($visibility) {

        if ( plugin()->helpers->is_preview() ) {
            return false;
        }

        return $visibility;
    }

    public function get_strings() {
        return [
            'userid'                          => __('User ID', 'gs-behance'),
            'userid-details'                  => __('Enter behance username', 'gs-behance'),
            'count'                           => __('Total Projects', 'gs-behance'),
            'count-details'                   => __('Set number of projects to display. Default 6', 'gs-behance'),
            'theme'                           => __('Theme', 'gs-behance'),
            'theme-details'                   => __('Select preffered styled theme', 'gs-behance'),
            'link-targets'                    => __('Projects Link Target', 'gs-behance'),
            'link-targets-details'            => __('Specify target to load the Links, Default new tab', 'gs-behance'),
            'field'                           => __('Field', 'gs-behance'),
            'field--help'                     => __('Set valid field name to display field wise projects. Note: Please make sure that you have inserted a valid field name otherwise It will show you nothing.', 'gs-behance'),
            'filter-all-label'                => __('Filter All Label', 'gs-behance'),
            'disable-lazy-load'               => __('Disable Lazy Load', 'gs-behance'),
            'lazy-load-class'                 => __('Lazy Load Class', 'gs-behance'),
            'filter-all-label-details'        => __('All filter text for filter templates, Default is All', 'gs-behance'),
            'disable-lazy-load--help'         => __('Disable Lazy Load for Shots', 'gs-behance'),
            'lazy-load-class--help'           => __('Add class to disable Lazy Loading, multiple classes should be separated by space', 'gs-behance'),
            'custom-css'                      => __('Custom CSS', 'gs-behance'),
            'sync-data'                       => __('Sync Behance Data', 'gs-behance'),
            'sync-data--help'                 => __('Manually syncing your Behance data can be incredibly helpful in a variety of scenarios. For instance, you may notice that certain images are missing from your profile, or you may have just uploaded a new project and want to ensure that it is immediately synced. In either case, manually syncing your data can quickly resolve the issue.', 'gs-behance'),
            'sync-data-button'                => __('Sync Data Now', 'gs-behance'),
            'preference'                      => __('Preference', 'gs-behance'),
            'save-preference'                 => __('Save Preference', 'gs-behance'),
            'shortcodes'                      => __('Shortcodes', 'gs-behance'),
            'shortcode'                       => __('Shortcode', 'gs-behance'),
            'global-settings-label'           => __('Global settings which are going to work on the whole plugin.', 'gs-behance'),
            'all-shortcodes'                  => __('All shortcodes', 'gs-behance'),
            'create-shortcode'                => __('Create Shortcode', 'gs-behance'),
            'create-new-shortcode'            => __('Create New Shortcode', 'gs-behance'),
            'name'                            => __('Name', 'gs-behance'),
            'action'                          => __('Action', 'gs-behance'),
            'actions'                         => __('Actions', 'gs-behance'),
            'edit'                            => __('Edit', 'gs-behance'),
            'clone'                           => __('Clone', 'gs-behance'),
            'delete'                          => __('Delete', 'gs-behance'),
            'delete-all'                      => __('Delete All', 'gs-behance'),
            'create-a-new-shortcode-and'      => __('Create a new shortcode & save it to use globally in anywhere', 'gs-behance'),
            'edit-shortcode'                  => __('Edit Shortcode', 'gs-behance'),
            'general-settings'                => __('General Settings', 'gs-behance'),
            'style-settings'                  => __('Style Settings', 'gs-behance'),
            'query-settings'                  => __('Query Settings', 'gs-behance'),
            'name-of-the-shortcode'           => __('Shortcode Name', 'gs-behance'),
            'save-shortcode'                  => __('Save Shortcode', 'gs-behance'),
            'preview-shortcode'               => __('Preview Shortcode', 'gs-behance'),
            'enable_autoplay'                 => __( 'Enable Autoplay', 'gs-behance' ),
            'speed'                           => __( 'Speed', 'gs-behance' ),
            'delay'                           => __( 'Delay', 'gs-behance' ),
            // Should be inside preferences.
            'gs_member_link_type'             => __('Link Type', 'gs-behance'),
            'gs_member_link_type__details'    => __('Choose the link type of projects', 'gs-behance'),
            // responsive labels
            'columns'                       => __('Desktop Columns', 'gs-behance'),
            'columns--help'                 => __('Enter the columns number for desktop', 'gs-behance'),
            'columns-tablet'                => __('Tablet Columns', 'gs-behance'),
            'columns-tablet--help'          => __('Enter the columns number for tablet', 'gs-behance'),
            'columns-mobile-portrait'       => __('Portrait Mobile Columns', 'gs-behance'),
            'columns-mobile-portrait--help' => __('Enter the columns number for portrait or large display mobile', 'gs-behance'),
            'columns-mobile'                => __('Mobile Columns', 'gs-behance'),
            'columns-mobile--help'          => __('Enter the columns number for mobile', 'gs-behance'),
        ];
    }

    public function maybe_upgrade_data( $old_version ) {
        if ( version_compare( $old_version, '3.0.3', '<' ) ) $this->upgrade_to_3_0_3();
        // if ( version_compare( $old_version, '3.0.3', '<' ) ) $this->upgrade_to_3_0_3();
    }

    public function upgrade_to_3_0_3() {

        global $wpdb;
        $shortcodes = $this->fetch_shortcodes();

        // Update Shortcode Data
        foreach ( $shortcodes as $shortcode ) {

            if ( isset( $shortcode['userid'] ) && isset( $shortcode['count'] ) ) {

                $shortcode['shortcode_settings'] = json_decode( $shortcode['shortcode_settings'], true );
                
                $shortcode['shortcode_settings']['userid']  = $shortcode['userid'];
                $shortcode['shortcode_settings']['count']   = $shortcode['count'];
                $shortcode['shortcode_settings']['field']   = $shortcode['field'];

                $shortcode['shortcode_settings']  = $this->validate_shortcode_settings( $shortcode['shortcode_settings'] );
                
                $data = array(
                    "shortcode_settings" => json_encode( $shortcode['shortcode_settings'] )
                );
                
                $tableName = plugin()->db->get_shortcodes_table();
                $wpdb->update( $tableName, $data, array( 'id' => $shortcode['id'] ), $this->get_db_columns() );

                wp_cache_delete('gsbeh_shortcodes', 'gs_behance');
                
            }            
        }

        // Removed field, count, userid columns
        $shortcodeTableName = plugin()->db->get_shortcodes_table();
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '%s' AND column_name = %s";
        if ( ! empty( $wpdb->get_results( $wpdb->prepare($query, $shortcodeTableName, 'field') ) ) ) $wpdb->query("ALTER TABLE $shortcodeTableName DROP field" );
        if ( ! empty( $wpdb->get_results( $wpdb->prepare($query, $shortcodeTableName, 'count') ) ) ) $wpdb->query("ALTER TABLE $shortcodeTableName DROP count" );
        if ( ! empty( $wpdb->get_results( $wpdb->prepare($query, $shortcodeTableName, 'userid') ) ) ) $wpdb->query("ALTER TABLE $shortcodeTableName DROP userid" );

        // Changed beid column from Uniqie to Nonunique
        $dataTableName = plugin()->db->get_data_table();
        $wrong_index = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_NAME = '%s' AND INDEX_NAME = 'beid' AND INDEX_SCHEMA = '%s'", $dataTableName, $wpdb->dbname ) );
        if ( $wrong_index ) $wpdb->query( "ALTER TABLE $dataTableName DROP INDEX beid" );

    }

}
