<?php
/*
    Admin page for Ultimate floating widgets plugin
*/

class UFW_Admin{
    
    public static $pagehook = 'toplevel_page_ultimate_floating_widgets';
    
    public static $wpoptzr;
    
    public static function init(){
        
        // Register the admin menu
        add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
        
        // Enqueue the scripts and styles
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
        
        // Register the action for admin ajax features
        add_action( 'wp_ajax_ufw_admin_ajax', array( __CLASS__, 'admin_ajax' ) );
        
        // Footer text
        add_filter( 'admin_footer_text', array( __CLASS__, 'footer_text' ) );

        // Plugin admin action links
        add_filter( 'plugin_action_links_' . UFW_BASE_NAME, array( __CLASS__, 'action_links' ) );

        self::$wpoptzr = new WP_Optionizer();
        
    }
    
    public static function add_menu(){
        
        add_menu_page( 'Ultimate floating widgets', 'Ultimate floating widgets', 'manage_options', 'ultimate_floating_widgets', array( __CLASS__, 'admin_page' ), 'dashicons-welcome-widgets-menus' );

        add_submenu_page( 'ultimate_floating_widgets', 'Ultimate Floating Widgets - Upgrade', '<span style="color: #ff8c29">Upgrade to PRO</span>', 'manage_options', 'https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=menu&utm_campaign=ufw-pro#pro', null );

    }
    
    public static function enqueue_scripts( $hook ){
        
        if( $hook == self::$pagehook ){
            
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'ufw-admin-style', UFW_ADMIN_URL . 'css/style.css', array(), UFW_VERSION );
            wp_enqueue_style( 'ufw-animate', UFW_URL . 'public/css/animate.min.css', array(), UFW_VERSION );
            
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'jquery-conditioner', UFW_ADMIN_URL . 'js/jquery.conditioner.js', array( 'jquery' ) );
            wp_enqueue_script( 'ufw-admin-script', UFW_ADMIN_URL . 'js/script.js', array( 'jquery', 'wp-color-picker' ) );
            
            self::$wpoptzr->enqueue_resources();
            
        }
        
        if( $hook == 'widgets.php' ){
            
            wp_enqueue_style( 'ufw-admin-widget-style', UFW_ADMIN_URL . 'css/style-widgets.css', array(), UFW_VERSION );
            wp_enqueue_script( 'ufw-admin-widget-script', UFW_ADMIN_URL . 'js/script-widgets.js', array( 'jquery' ), UFW_VERSION );
            
        }
        
    }
    
    public static function admin_page(){
        
        echo '<div class="wrap">';
        echo '<div class="ufw_head">';
        echo '<h1 class="ufw_title"><span class="dashicons dashicons-welcome-widgets-menus"></span> Ultimate floating widgets <span class="title-count">' . UFW_VERSION . '</h1>';
        echo '</div>';
        
        $g = self::clean_get();
        
        self::print_notice();
        
        echo '<div id="content">';
        
        if( !isset( $g[ 'action' ] ) ){
            $g[ 'action' ] = 'list';
        }
        
        if( $g[ 'action' ] == 'list' ){
            self::list_widget_box();
        }
        
        if( $g[ 'action' ] == 'edit' ){
            self::edit_widget_box();
        }
        
        if( $g[ 'action' ] == 'new' ){
            self::new_widget_box();
        }
        
        echo '</div>';
        
    }
    
    public static function list_widget_box(){
        
        $widget_boxes = Ultimate_Floating_Widgets::list_all();
        $total = count( $widget_boxes );
        $active = 0;
        
        echo '<div class="ufw_action_bar">';
        echo '<a href="' . esc_url( self::get_link( array( 'action' => 'new' ) ) ) . '" class="button button-primary ufw_new_btn"><span class="dashicons dashicons-plus"></span> ' . __( 'Create a new widget box', 'ultimate-floating-widgets' ) . '</a>';
        echo '<div class="ufw_action_rg"><input type="search" class="ufw_search" placeholder="Filter ..." /></div>';
        echo '</div>';
        
        echo '<table class="wp-list-table widefat fixed striped ufw_table"><thead>';
        echo '<tr><th width="40px">ID</th>';
        echo '<th class="column-primary">Name</th>';
        echo '<th width="10%">Type</th>';
        echo '<th width="10%">Status</th>';
        echo '<th width="15%">Last modified</th>';
        echo '<th width="15%"></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach( $widget_boxes as $id => $data ){
            
            echo '<tr data-id="' . esc_attr( $id ) . '">';
            
            echo '<th>' . esc_html( $id ) . '</th>';
            
            echo '<td class="title column-title has-row-actions column-primary page-title">';
            
            $data = wp_parse_args( $data, Ultimate_Floating_Widgets::defaults() );
            
            $edit_link = self::get_link(array(
                'action' => 'edit',
                'id' => $id
            ));
            
            $delete_link = self::get_link(array(
                'action' => 'ufw_admin_ajax',
                'do' => 'delete',
                'id' => $id,
                '_wpnonce' => wp_create_nonce( 'ufw_delete_nonce' )
            ), 'admin-ajax.php' );
            
            echo '<a href="' . esc_url( $edit_link ) . '" class="row-title ufw_name">' . esc_html( $data[ 'name' ] ) . '</a>';
            echo '<div class="row-actions">';
            echo '<span><a href="' . esc_url( $edit_link ) . '">' . esc_html__( 'Edit', 'ultimate-floating-widgets' ) . '</a> | </span>';
            echo '<span class="trash"><a href="' . esc_url( $delete_link ) . '" class="ufw_delete_list">' . esc_html__( 'Delete', 'ultimate-floating-widgets' ) . '</a></span>';
            echo '</div>';
            echo '</td>';
            
            echo '<td>' . ( $data[ 'type' ] == 'popup' ? esc_html__( 'Popup', 'ultimate-floating-widgets' ) : esc_html__( 'Flyout', 'ultimate-floating-widgets' ) ) . '</td>';
            
            echo '<td>';
            if( $data[ 'status' ] == 'enabled' ){
                echo '<span class="ufw_active">' . esc_html__( 'Active', 'ultimate-floating-widgets' ) . '</span>';
                $active++;
            }else{
                echo '<span class="ufw_inactive">' . esc_html__( 'Inactive', 'ultimate-floating-widgets' ) . '</span>';
            }
            echo '</td>';
            
            echo '<td>' . self::get_date( $data[ 'modified' ] ) . '</td>';
            
            echo '<td><a href="' . esc_url( self::get_add_widgets_link( $id ) ) . '" target="_blank" class="button">' . esc_html__( 'Add widget', 'ultimate-floating-widgets' ) . '</a></td>';
            
            echo '</tr>';
            
        }
        
        if( count( $widget_boxes ) == 0 ){
            echo '<tr><td colspan="6"><p align="center" class="description">' . esc_html__( 'No widget boxes are created. Go ahead create one !', 'ultimate-floating-widgets' ) . '</p></td></tr>';
        }
        
        echo '</tbody>';
        echo '<tfoot><tr><td colspan="6">';
        echo '<span class="description">' . esc_html( $total ) . ' widget box' . ( $total == 1 ? '' : 'es' ) . ' created</span>';
        echo '<span class="description fright">' . esc_html( $active ) . ' Active</span>';
        echo '</td></tr></tfoot>';
        echo '</table>';
        
    }
    
    public static function new_widget_box(){
        
        self::edit_widget_box( 'new' );
        
    }
    
    public static function edit_widget_box( $action = 'edit' ){
        
        self::save_widget_box();
        
        $g = self::clean_get();
        $widget_boxes = Ultimate_Floating_Widgets::list_all();
        
        $page_title = __( 'New widget box', 'ultimate-floating-widgets' );
        $action_btn = __( 'Create widget box', 'ultimate-floating-widgets' );
        $page_icon = 'dashicons-plus-alt';
        $values = array();
        $id = isset( $g['id'] ) ? $g['id'] : false;
        
        if( $action == 'edit' ){
            
            $page_title = __( 'Edit widget box', 'ultimate-floating-widgets' );
            $action_btn = __( 'Save widget box', 'ultimate-floating-widgets' );
            $page_icon = 'dashicons-edit';
            
            if( $id === false ){
                echo '<p align="center">' . esc_html__( 'Invalid widget box id provided to edit', 'ultimate-floating-widgets' ) . '</p>';
                return false;
            }
            
            if( ! array_key_exists( $id, $widget_boxes ) ){
                echo '<p align="center">' . esc_html__( 'Invalid widget box id provided. No such widget box exists !', 'ultimate-floating-widgets' ) . '</p>';
                return false;
            }
            
            $values = $widget_boxes[ $id ];
            
        }
        
        $defaults = Ultimate_Floating_Widgets::defaults();
        $values = wp_parse_args( $values, $defaults );
        
        echo '<h2 class="ufw_edit_title"><span class="dashicons ' . esc_attr( $page_icon ) . '"></span> ' . esc_html( $page_title );
        
        echo '<a href="' . esc_url( self::get_link() ) . '" class="button right ufw_back_btn" title="' . esc_attr__( 'Back to list', 'ultimate-floating-widgets' ) . '"><span class="dashicons dashicons-arrow-left-alt2"></span> ' . esc_html__( 'Back to list', 'ultimate-floating-widgets' ) . '</a>';
        
        if( $action == 'edit' ){
            
            $delete_link = self::get_link(array(
                'action' => 'ufw_admin_ajax',
                'do' => 'delete',
                'id' => $id,
                '_wpnonce' => wp_create_nonce( 'ufw_delete_nonce' )
            ), 'admin-ajax.php' );
            
            echo '<a href="' . esc_url( self::get_add_widgets_link( $id ) ) . '" class="button button-primary right" target="_blank" title="' . esc_attr__( 'Add widgets', 'ultimate-floating-widgets' ) . '">';
            echo '<span class="dashicons dashicons-plus"></span> ' . esc_html__( 'Add widgets to the widget box', 'ultimate-floating-widgets' ) . '</a>';
            
            echo '<a href="' . esc_url( $delete_link ) . '" class="button ufw_delete_ep right" title="' . esc_attr__( 'Delete box', 'ultimate-floating-widgets' ) . '"><span class="dashicons dashicons-trash"></span></a>';
        }
        
        echo '</h2>';

        UFW_Admin_Edit_Form::init( self::$wpoptzr );

        echo '<form method="post">';
        
        UFW_Admin_Edit_Form::main_settings( $values );
        UFW_Admin_Edit_Form::form( $values );

        echo '<footer class="page_footer">';
        echo '<button class="button button-primary"><span class="dashicons dashicons-yes"></span> ' . esc_html( $action_btn ) . '</button>';
        echo '</footer>';

        wp_nonce_field( 'ufw_edit_nonce' );
        echo '<input type="hidden" name="ufw_id" value="' . ( $id === false ? '' : esc_attr( $id ) ) . '" />';

        echo '</form>';
        
    }
    
    public static function save_widget_box(){
        
        if( $_POST && check_admin_referer( 'ufw_edit_nonce' ) ){
            
            $p = self::clean_post();
            
            if( !trim( $p[ 'ufw_name' ] ) ){
                return false;
            }
            
            $widget_boxes = Ultimate_Floating_Widgets::list_all();
            
            $values = array(
                'name' => sanitize_text_field( $p[ 'ufw_name' ] ),
                'title' => wp_kses_post( $p[ 'ufw_title' ] ),
                'status' => sanitize_text_field( $p[ 'ufw_status' ] ),
                'init_state' => sanitize_text_field( $p[ 'ufw_init_state' ] ),
                'init_state_m' => sanitize_text_field( $p[ 'ufw_init_state_m' ] ),
                'type' => sanitize_text_field( $p[ 'ufw_type' ] ),
                'pp_position' => sanitize_text_field( $p[ 'ufw_pp_position' ] ),
                'fo_position' => sanitize_text_field( $p[ 'ufw_fo_position' ] ),
                'trigger' => sanitize_text_field( $p[ 'ufw_trigger' ] ),
                'auto_trigger' => sanitize_text_field( $p[ 'ufw_auto_trigger' ] ),
                
                'pp_anim_open' => sanitize_text_field( $p[ 'ufw_pp_anim_open' ] ),
                'pp_anim_close' => sanitize_text_field( $p[ 'ufw_pp_anim_close' ] ),
                'fo_anim_open' => sanitize_text_field( $p[ 'ufw_fo_anim_open' ] ),
                'fo_anim_close' => sanitize_text_field( $p[ 'ufw_fo_anim_close' ] ),
                'anim_duration' => sanitize_text_field( $p[ 'ufw_anim_duration' ] ),
                'fo_btn_position' => sanitize_text_field( $p[ 'ufw_fo_btn_position' ] ),

                'save_state' => sanitize_text_field( $p[ 'ufw_save_state' ] ),
                'save_state_duration' => absint( $p[ 'ufw_save_state_duration' ] ),

                'auto_close' => sanitize_text_field( $p[ 'ufw_auto_close' ] ),
                'auto_close_time' => sanitize_text_field( $p[ 'ufw_auto_close_time' ] ),
                'wb_close_btn' => sanitize_text_field( $p[ 'ufw_wb_close_btn' ] ),
                'wb_close_icon' => sanitize_text_field( $p[ 'ufw_wb_close_icon' ] ),

                'wb_width' => sanitize_text_field( $p[ 'ufw_wb_width' ] ),
                'wb_height' => sanitize_text_field( $p[ 'ufw_wb_height' ] ),
                'wb_bg_color' => sanitize_text_field( $p[ 'ufw_wb_bg_color' ] ),
                'wb_bdr_size' => sanitize_text_field( $p[ 'ufw_wb_bdr_size' ] ),
                'wb_bdr_color' => sanitize_text_field( $p[ 'ufw_wb_bdr_color' ] ),
                'wb_text_color' => sanitize_text_field( $p[ 'ufw_wb_text_color' ] ),
                'wb_bdr_radius' => sanitize_text_field( $p[ 'ufw_wb_bdr_radius' ] ),
                
                'btn_type' => sanitize_text_field( $p[ 'ufw_btn_type' ] ),
                'btn_icon' => wp_kses_post( $p[ 'ufw_btn_icon' ] ),
                'btn_size' => sanitize_text_field( $p[ 'ufw_btn_size' ] ),
                'btn_text' => wp_kses_post( $p[ 'ufw_btn_text' ] ),
                'btn_bg_color' => sanitize_text_field( $p[ 'ufw_btn_bg_color' ] ),
                'btn_bdr_size' => sanitize_text_field( $p[ 'ufw_btn_bdr_size' ] ),
                'btn_bdr_color' => sanitize_text_field( $p[ 'ufw_btn_bdr_color' ] ),
                'btn_text_color' => sanitize_text_field( $p[ 'ufw_btn_text_color' ] ),
                'btn_radius' => sanitize_text_field( $p[ 'ufw_btn_radius' ] ),
                'btn_reveal' => sanitize_text_field( $p[ 'ufw_btn_reveal' ] ),
                'btn_close_icon' => wp_kses_post( $p[ 'ufw_btn_close_icon' ] ),
                'btn_close_text' => wp_kses_post( $p[ 'ufw_btn_close_text' ] ),
                
                'loc_rules_config' => 'basic',
                'loc_rules_basic' => self::sanitize_text_field_recursive( $p[ 'ufw_loc_rules_basic' ] ),
                'loc_rules' => self::sanitize_text_field_recursive( $p[ 'ufw_loc_rules' ] ),
                
                'before_widget' => wp_kses_post( $p[ 'ufw_before_widget' ] ),
                'after_widget' => wp_kses_post( $p[ 'ufw_after_widget' ] ),
                'before_title' => wp_kses_post( $p[ 'ufw_before_title' ] ),
                'after_title' => wp_kses_post( $p[ 'ufw_after_title' ] ),
                'additional_css' => wp_kses_post( $p[ 'ufw_additional_css' ] ),
                
                'modified' => current_time( 'timestamp' )
                
            );
            
            $id = $p[ 'ufw_id' ];
            $new_save = false;
            
            if( empty( $id ) || !array_key_exists( $id, $widget_boxes ) ){
                if( empty( $widget_boxes ) ){
                    $widget_boxes[1] = $values;
                }else{
                    array_push( $widget_boxes, $values );
                }
                $new_save = true;
            }else{
                $widget_boxes[ $id ] = $values;
                $new_save = false;
            }
            
            update_option( 'ufw_data', $widget_boxes );
            
            if( $new_save ){
                self::print_notice( 'Successfully created a new widget box !' );
            }else{
                self::print_notice( 'Successfully updated widget box settings !' );
            }
            
        }
        
    }
    
    public static function delete_widget_box( $id ){
        
        $widget_boxes = Ultimate_Floating_Widgets::list_all();
        
        if( array_key_exists( $id, $widget_boxes ) ){
            
            unset( $widget_boxes[ $id ] );
            update_option( 'ufw_data', $widget_boxes );
            return true;
            
        }else{
            
            return false;
            
        }
        
    }
    
    public static function admin_ajax(){
        
        $g = self::clean_get();
        
        if( $g[ 'do' ] == 'delete' && isset( $g[ 'id' ] ) && check_admin_referer( 'ufw_delete_nonce' ) ){
            $id = $g[ 'id' ];
            if( self::delete_widget_box( $id ) ){
                echo 'DELETED';
            }else{
                echo 'FAILED';
            }
        }
        
        die(0);
        
    }
    
    public static function get_link( $params = array(), $page = 'admin.php' ){
        
        $params[ 'page' ] = 'ultimate_floating_widgets';
        return add_query_arg( $params, admin_url( $page ) );
        
    }
    
    public static function get_add_widgets_link( $id ){
        
        return admin_url() . 'widgets.php#ufw_' . $id;
        
    }
    
    public static function print_notice( $msg = '', $type = 'success' ){
        
        $g = self::clean_get();
        
        if( isset( $g[ 'msg' ] ) ){
            if( $g[ 'msg' ] == 3 ){
                $msg = 'Successfully deleted the widget box !';
            }
        }
        
        if( $msg != '' ){
            echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible"><p>' . wp_kses_post( $msg ) . '</p></div>';
        }
        
    }
    
    public static function get_date( $timestamp ){
        
        if( $timestamp == '' ){
            return '-';
        }
        
        $full = date_i18n( get_option('date_format') . ' ' . get_option('time_format'), $timestamp );
        $short = date_i18n( get_option('date_format'), $timestamp );
        
        return '<abbr title="' . esc_attr( $full ) . '">' . esc_html( $short ) . '</abbr>';
        
    }
    
    public static function clean_get(){
        
        return self::sanitize_text_field_recursive( $_GET );
    }
    
    public static function clean_post(){
        
        // Post array is un slashed and sanitization is done for individual fields in the "save_widget_box" function
        return wp_unslash( $_POST );
        
    }
    
    public static function sanitize_text_field_recursive( $array ) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::sanitize_text_field_recursive( $value );
            } else {
                $value = sanitize_text_field( $value );
            }
        }
        return $array;
    }
    
    public static function footer_text( $text ){

        $screen = get_current_screen();

        if( self::$pagehook == $screen->id ){
            return '<span id="footer-thankyou">Thank you for using Ultimate floating widgets. 
            <a href="https://www.aakashweb.com/forum/discuss/wordpress-plugins/ultimate-floating-widgets/" target="_blank" title="Help">Support forum</a> &bull; 
            <a href="https://wordpress.org/support/plugin/ultimate-floating-widgets/reviews/?rate=5#new-post" target="_blank">Rate this plugin</a>';
        }

        return $text;

    }

    public static function action_links( $links ){
        array_unshift( $links, '<a href="'. esc_url( admin_url( 'admin.php?page=ultimate_floating_widgets') ) .'">' . esc_html__( 'Manage', 'ultimate-floating-widgets' ) . '</a>' );
        array_unshift( $links, '<a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=upgrade&utm_campaign=ufw-pro#pro" target="_blank"><b>' . esc_html__( 'Upgrade', 'ultimate-floating-widgets' ) . '</b></a>' );
        return $links;
    }

}

UFW_Admin::init();

?>