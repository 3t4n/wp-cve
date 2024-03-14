<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Tools {

    private static $_instance = NULL;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'wp_ajax_wpsc_import', array( $this, 'ajax_process_import' ) );
        add_action( 'admin_init',          array( $this, 'admin_init' ) );
        add_action( 'admin_menu',          array( $this, 'admin_menu' ), 100 );
    }

    /**
     * retrieve singleton class instance
     * @return instance reference to plugin
     */
    public static function instance() {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function admin_init() {
        global $wpdb;
        
        if ( isset( $_REQUEST['page'] ) && 'wpsc-tools' === $_REQUEST['page'] ) {
            if ( isset( $_REQUEST['step'] ) && isset( $_REQUEST['chk'] ) && 'import' === $_REQUEST['step'] && wp_verify_nonce( $_REQUEST['chk'], 'wpsc_import_processing' ) && false === get_transient( 'wpsc_import_data' ) ) {
                wp_redirect( remove_query_arg( array( 'step', 'chk' ), wp_get_referer() ) );
                exit;
            }
            
            if ( isset( $_POST['wpsc_import'] ) && 'Y' === $_POST['wpsc_import'] && isset( $_FILES['file']['name'] ) && '' !== $_FILES['file']['name'] ) {
                check_admin_referer( 'wpsc_import' );

                $ext = strtolower( pathinfo( sanitize_text_field( wp_unslash( $_FILES['file']['name'] ) ), PATHINFO_EXTENSION ) );

                if ( $ext !== 'json' ) {
                    wp_die( esc_html__( 'Please upload a valid .json export file.', 'wp-school-calendar' ), esc_html__( 'Error', 'wp-school-calendar' ), array( 'response' => 400 ) );
                }

                $tmp_name        = isset( $_FILES['file']['tmp_name'] ) ? sanitize_text_field( $_FILES['file']['tmp_name'] ) : '';
                $import_data     = json_decode( file_get_contents( $tmp_name ), true );
                $remove_existing = isset( $_POST['remove_existing'] ) && 'Y' === $_POST['remove_existing'] ? 'Y' : 'N';

                set_transient( 'wpsc_import_data', $import_data, 360 );
                set_transient( 'wpsc_import_remove_existing', $remove_existing, 360 );
                
                wp_redirect( add_query_arg( array( 'step' => 'import', 'chk' => wp_create_nonce( 'wpsc_import_processing' ) ), wp_get_referer() ) );
                exit;
            }
            
            elseif ( isset( $_POST['wpsc_export'] ) && 'Y' === $_POST['wpsc_export'] ) {
                check_admin_referer( 'wpsc_export' );

                $filename = sprintf( 'wpsc-export-%s.json', date( 'Y-m-d' ) );

                header( 'Content-Type: application/json' );
                header( 'Content-Disposition: attachment; filename=' . $filename );
                header( 'Pragma: no-cache' );
                
                $important_dates = array();
                $groups          = array();
                $categories      = array();
                $calendars       = array();
                
                // Settings
                
                $settings = get_option( 'wpsc_options' );
                
                // Get All Groups
                
                $terms = get_terms( array( 'taxonomy' => 'important_date_group', 'hide_empty' => false ) );
                
                foreach ( $terms as $term ) {
                    $groups[] = array( 
                        'term_id'          => $term->term_id, 
                        'term_taxonomy_id' => $term->term_taxonomy_id,
                        'name'             => $term->name,
                        'count'            => $term->count
                    );
                }
                
                // Calendars, important dates, categories
                
                $sql  = "SELECT p.ID AS post_id, p.post_type, p.post_title FROM {$wpdb->posts} p ";
                $sql .= "WHERE p.post_type IN ('school_calendar', 'important_date', 'important_date_cat') ";
                $sql .= "AND p.post_status = 'publish'";

                $results = $wpdb->get_results( $sql, ARRAY_A );
                
                $post_ids = array();
                
                foreach ( $results as $result ) {
                    $post_ids[] = $result['post_id'];
                    
                    if ( 'school_calendar' === $result['post_type'] ) {
                        $calendars[] = array(
                            'post_id'    => $result['post_id'],
                            'post_title' => $result['post_title']
                        );
                    } elseif ( 'important_date' === $result['post_type'] ) {
                        $important_dates[] = array(
                            'post_id'    => $result['post_id'],
                            'post_title' => $result['post_title']
                        );
                    } elseif ( 'important_date_cat' === $result['post_type'] ) {
                        $categories[] = array(
                            'post_id'    => $result['post_id'],
                            'post_title' => $result['post_title']
                        );
                    }
                }
                
                $valid_post_meta_keys = array(
                    '_bgcolor',
                    '_order',
                    '_start_date',
                    '_end_date',
                    '_exclude_weekend',
                    '_category_id',
                    '_enable_recurring',
                    '_recurring_type',
                    '_recurring_options',
                    '_additional_notes',
                    '_calendar_options'
                );

                $post_ids = implode( ',', $post_ids );
                
                $placeholders = array_fill( 0, count( $valid_post_meta_keys ), '%s' );
                
                $sql  = "SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta} ";
                $sql .= "WHERE post_id IN ({$post_ids}) AND ";
                $sql .= sprintf( "meta_key IN (%s) ", implode( ', ', $placeholders ) );
                
                $results = $wpdb->get_results( $wpdb->prepare( $sql, $valid_post_meta_keys ), ARRAY_A );
                
                $post_metas = array();
                
                foreach ( $results as $result ) {
                    $post_metas[$result['post_id']][$result['meta_key']] = $result['meta_value'];
                }
                
                $calendar_post_metas = array();
                
                foreach ( $calendars as $i => $calendar ) {
                    $post_id = $calendar['post_id'];
                    $calendars[$i]['post_meta'] = $post_metas[$post_id];
                }
                
                $important_date_post_metas = array();
                
                foreach ( $important_dates as $i => $important_date ) {
                    $post_id = $important_date['post_id'];
                    $important_dates[$i]['post_meta'] = $post_metas[$post_id];
                }
                
                $category_post_metas = array();
                
                foreach ( $categories as $i => $category ) {
                    $post_id = $category['post_id'];
                    $categories[$i]['post_meta'] = $post_metas[$post_id];
                }
                
                // Important Date Groups
                
                $post_groups = array();
                
                $sql  = "SELECT tr.object_id, tt.term_id, tr.term_taxonomy_id FROM {$wpdb->term_relationships} tr ";
                $sql .= "JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id ";
                $sql .= sprintf( "WHERE tr.object_id IN (%s) ", $post_ids );

                $results = $wpdb->get_results( $sql, ARRAY_A );
                
                foreach ( $results as $result ) {
                    $post_groups[$result['object_id']][] = array(
                        'term_id'          => $result['term_id'],
                        'term_taxonomy_id' => $result['term_taxonomy_id']
                    );
                }
                
                foreach ( $important_dates as $i => $important_date ) {
                    $post_group = isset( $post_groups[$important_date['post_id']] ) ? $post_groups[$important_date['post_id']] : array();
                    $important_dates[$i]['groups'] = $post_group;
                }
                
                $output = array(
                    'settings'        => $settings,
                    'groups'          => $groups,
                    'categories'      => $categories,
                    'important-dates' => $important_dates,
                    'calendars'       => $calendars
                );
                
                echo json_encode( $output );

                exit;
            }
        }
    }
    
    /**
     * Add Tools menu page
     * 
     * @since 1.0
     */
    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=school_calendar', __( 'School Calendar Tools', 'wp-school-calendar' ), __( 'Tools', 'wp-school-calendar' ), 'manage_options', 'wpsc-tools', array( $this, 'admin_page' ) );
    }
    
    /**
     * Display admin page
     * 
     * @since 1.0
     */
    public function admin_page() {
        global $wpsc_import_step;
        
        $remove_existing = get_transient( 'wpsc_import_remove_existing' );
        
        $wpsc_import_step = 1;
        $hide_section = true;
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Tools', 'wp-school-calendar' );?></h1>
            
            <div id="wpsc-tools-import" class="wpsc-tools-page">
                <h2><?php echo __( 'Import Calendars and Important Dates', 'wp-school-calendar' ) ?></h2>
                <?php if ( isset( $_REQUEST['step'] ) && isset( $_REQUEST['chk'] ) && 'import' === $_REQUEST['step'] && wp_verify_nonce( $_REQUEST['chk'], 'wpsc_import_processing' ) ): ?>
                <?php if ( false === $remove_existing ): elseif ( 'Y' === $remove_existing ): ?>
                <h3><?php printf( __( 'Step %d: Remove Existing Data', 'wp-school-calendar' ), $wpsc_import_step++ ) ?></h3>
                <div id="wpsc_remove_existing_section" class="wpsc_import_process_section">
                    <form method="post">
                        <input type="hidden" name="wpsc_import_current_step" value="remove_existing">
                        <p><?php echo __( 'Click the button below to remove existing calendars, groups, categories and important dates.', 'wp-school-calendar' ) ?></p>
                        <p><input type="button" class="button button-primary wpsc-button-import" value="<?php echo __( 'Remove and Continue to Next Step', 'wp-school-calendar' ) ?>" data-processing="<?php echo __( 'Please wait...', 'wp-school-calendar' ) ?>"></p>
                    </form>
                </div>
                <?php else: $hide_section = false; ?>
                <?php endif ?>
                <h3><?php printf( __( 'Step %d: Import Groups', 'wp-school-calendar' ), $wpsc_import_step++ ) ?></h3>
                <div id="wpsc_import_groups_section" class="wpsc_import_process_section" style="<?php if ( $wpsc_import_step > 1 ) echo 'display:none' ?>">
                    <form method="post">
                        <input type="hidden" name="wpsc_import_current_step" value="import_groups">
                        <p><?php echo __( 'Click the button below to import groups and continue to the next step.', 'wp-school-calendar' ) ?></p>
                        <p><input type="button" class="button button-primary wpsc-button-import" value="<?php echo __( 'Import and Continue to Next Step', 'wp-school-calendar' ) ?>" data-processing="<?php echo __( 'Please wait...', 'wp-school-calendar' ) ?>"></p>
                    </form>
                </div>
                <?php $hide_section = true; ?>
                <h3><?php printf( __( 'Step %d: Import Categories', 'wp-school-calendar' ), $wpsc_import_step++ ) ?></h3>
                <div id="wpsc_import_categories_section" class="wpsc_import_process_section" style="<?php if ( $wpsc_import_step > 1 ) echo 'display:none' ?>">
                    <form method="post">
                        <input type="hidden" name="wpsc_import_current_step" value="import_categories">
                        <p><?php echo __( 'Click the button below to import categories and continue to the next step.', 'wp-school-calendar' ) ?></p>
                        <p><input type="button" class="button button-primary wpsc-button-import" value="<?php echo __( 'Import and Continue to Next Step', 'wp-school-calendar' ) ?>" data-processing="<?php echo __( 'Please wait...', 'wp-school-calendar' ) ?>"></p>
                    </form>
                </div>
                <h3><?php printf( __( 'Step %d: Import Important Dates', 'wp-school-calendar' ), $wpsc_import_step++ ) ?></h3>
                <div id="wpsc_import_important_dates_section" class="wpsc_import_process_section" style="<?php if ( $wpsc_import_step > 1 ) echo 'display:none' ?>">
                    <form method="post">
                        <input type="hidden" name="wpsc_import_current_step" value="import_important_dates">
                        <p><?php echo __( 'Click the button below to import important dates and continue to the next step.', 'wp-school-calendar' ) ?></p>
                        <p><input type="button" class="button button-primary wpsc-button-import" value="<?php echo __( 'Import and Continue to Next Step', 'wp-school-calendar' ) ?>" data-processing="<?php echo __( 'Please wait...', 'wp-school-calendar' ) ?>"></p>
                    </form>
                </div>
                <h3><?php printf( __( 'Step %d: Import Calendars', 'wp-school-calendar' ), $wpsc_import_step++ ) ?></h3>
                <div id="wpsc_import_calendars_section" class="wpsc_import_process_section" style="<?php if ( $wpsc_import_step > 1 ) echo 'display:none' ?>">
                    <form method="post">
                        <input type="hidden" name="wpsc_import_current_step" value="import_calendars">
                        <p><?php echo __( 'Click the button below to import calendars and continue to the next step.', 'wp-school-calendar' ) ?></p>
                        <p><input type="button" class="button button-primary wpsc-button-import" value="<?php echo __( 'Import and Continue to Next Step', 'wp-school-calendar' ) ?>" data-processing="<?php echo __( 'Please wait...', 'wp-school-calendar' ) ?>"></p>
                    </form>
                </div>
                
                <?php do_action( 'wpsc_tools_import_page_section', $wpsc_import_step ) ?>
                
                <h3><?php printf( __( 'Step %d: Update Settings', 'wp-school-calendar' ), $wpsc_import_step++ ) ?></h3>
                <div id="wpsc_update_settings_section" class="wpsc_import_process_section" style="<?php if ( $wpsc_import_step > 1 ) echo 'display:none' ?>">
                    <form method="post">
                        <input type="hidden" name="wpsc_import_current_step" value="update_settings">
                        <p><?php echo __( 'Click the button below to update settings and finish.', 'wp-school-calendar' ) ?></p>
                        <p><input type="button" class="button button-primary wpsc-button-import" value="<?php echo __( 'Update Settings and Finish', 'wp-school-calendar' ) ?>" data-processing="<?php echo __( 'Please wait...', 'wp-school-calendar' ) ?>"></p>
                    </form>
                </div>
                <?php else: ?>
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field( 'wpsc_import' ); ?>
                    <input type="hidden" name="wpsc_import" value="Y">
                    <p><?php echo __( 'Select WP School Calendar export file.', 'wp-school-calendar' ) ?></p>
                    <p><input type="file" name="file" accept=".json"></p>
                    <p><label><input type="checkbox" name="remove_existing" value="Y" checked="checked"> <?php echo __( 'Remove all existing important dates, groups, categories and calendars', 'wp-school-calendar' ) ?></label></p>
                    <p><input type="submit" class="button button-primary" value="<?php echo __( 'Import', 'wp-school-calendar' ) ?>"></p>
                </form>
                <?php endif ?>
            </div>
            
            <div id="wpsc-tools-export" class="wpsc-tools-page" style="margin-top: 50px">
                <h2><?php echo __( 'Export Calendars and Important Dates', 'wp-school-calendar' ) ?></h2>
                <form method="post">
                    <?php wp_nonce_field( 'wpsc_export' ); ?>
                    <input type="hidden" name="wpsc_export" value="Y">
                    <p><?php echo __( 'When you click the button below WordPress will create a JSON file for you to save to your computer.', 'wp-school-calendar' ) ?></p>
                    <p><?php echo __( "Once you've saved the download file, you can use the Import function in another WordPress installation to import the calendars and important dates from this site.", 'wp-school-calendar' ) ?></p>
                    <p><input type="submit" class="button button-primary" value="<?php echo __( 'Export', 'wp-school-calendar' ) ?>"></p>
                </form>
            </div>
            
        </div>
        <?php
    }
    
    private function process_remove_existing() {
        global $wpdb;
        
        $sql  = "SELECT p.ID AS post_id FROM {$wpdb->posts} p ";
        $sql .= "WHERE p.post_type IN ('school_calendar', 'important_date', 'rec_important_date', 'important_date_cat')";

        $results = $wpdb->get_results( $sql, ARRAY_A );

        $post_ids = array();

        foreach ( $results as $result ) {
            $post_ids[] = $result['post_id'];
        }

        if ( count( $post_ids ) > 0 ) {
            $post_ids = implode( ',', $post_ids );

            $sql = "DELETE FROM {$wpdb->postmeta} WHERE post_id IN ({$post_ids})";
            $wpdb->query( $sql );

            $sql = "DELETE FROM {$wpdb->posts} WHERE ID IN ({$post_ids})";
            $wpdb->query( $sql );
        }

        $term_ids = array();
        $term_taxonomy_ids = array();

        $sql  = "SELECT t.term_id, tt.term_taxonomy_id FROM {$wpdb->terms} t ";
        $sql .= "LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id ";
        $sql .= "WHERE tt.taxonomy = 'important_date_group'";

        $results = $wpdb->get_results( $sql, ARRAY_A );

        foreach ( $results as $result ) {
            $term_ids[] = $result['term_id'];
            $term_taxonomy_ids[] = $result['term_taxonomy_id'];
        }

        if ( count( $term_ids ) > 0 ) {
            $term_ids = implode( ',', $term_ids );
            $term_taxonomy_ids = implode( ',', $term_taxonomy_ids );

            $sql = "DELETE FROM {$wpdb->terms} WHERE term_id IN ({$term_ids})";
            $wpdb->query( $sql );

            $sql = "DELETE FROM {$wpdb->term_taxonomy} WHERE term_id IN ({$term_ids})";
            $wpdb->query( $sql );
            
            $sql = "DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ({$term_taxonomy_ids})";
            $wpdb->query( $sql );
        }
    }
    
    private function process_import_groups() {
        global $wpdb;
        
        $data = get_transient( 'wpsc_import_data' );
        
        // Create dummy term to get last_insert_id
                
        $sql = sprintf( "INSERT INTO {$wpdb->terms} (name) VALUES ('wpsc-dummy-term-%s') ", time() );
        $wpdb->query( $sql );

        // Get last term insert ID

        $last_term_id = (int) $wpdb->insert_id;
        $next_term_id = $last_term_id + 1;

        // Delete dummy term

        $sql = sprintf( "DELETE FROM {$wpdb->terms} WHERE term_id = %d", $last_term_id );
        $wpdb->query( $sql );

        // Create dummy term taxonomy to get last_insert_id

        $sql = "INSERT INTO {$wpdb->term_taxonomy} (term_id, taxonomy) VALUES (0, 'important_date_group') ";
        $wpdb->query( $sql );

        // Get last term taxonomy insert ID

        $last_term_taxonomy_id = (int) $wpdb->insert_id;
        $next_term_taxonomy_id = $last_term_taxonomy_id + 1;

        // Delete dummy term taxonomy

        $sql = sprintf( "DELETE FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id = %d", $last_term_taxonomy_id );
        $wpdb->query( $sql );

        // Create pair of old_term_id => new_term_id

        $pair_group_ids = array();

        if ( isset( $data['groups'] ) ) {
            foreach ( $data['groups'] as $group ) {
                $old_term_id = $group['term_id'];
                $pair_group_ids[$old_term_id]['new_term_id'] = $next_term_id;
                $pair_group_ids[$old_term_id]['new_term_taxonomy_id'] = $next_term_taxonomy_id;
                $next_term_id++;
                $next_term_taxonomy_id++;
            }
        }

        // Import Groups

        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->terms} (term_id, name, slug) VALUES ";

        if ( isset( $data['groups'] ) ) {
            foreach ( $data['groups'] as $group ) {
                $data_params[] = '(%d, %s, %s)';

                $old_term_id = $group['term_id'];
                $new_term_id = $pair_group_ids[$old_term_id]['new_term_id'];

                $data_values[] = $new_term_id;
                $data_values[] = $group['name'];
                $data_values[] = 'term-' . $new_term_id;
            }
        }

        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }

        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->term_taxonomy} (term_taxonomy_id, term_id, taxonomy, count) VALUES ";

        if ( isset( $data['groups'] ) ) {
            foreach ( $data['groups'] as $group ) {
                $data_params[] = '(%d, %d, %s, %d)';

                $old_term_id          = $group['term_id'];
                $new_term_id          = $pair_group_ids[$old_term_id]['new_term_id'];
                $new_term_taxonomy_id = $pair_group_ids[$old_term_id]['new_term_taxonomy_id'];

                $data_values[] = $new_term_taxonomy_id;
                $data_values[] = $new_term_id;
                $data_values[] = 'important_date_group';
                $data_values[] = $group['count'];
            }
        }

        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        set_transient( 'wpsc_pair_group_ids', $pair_group_ids, 360 );
    }
    
    private function process_import_categories() {
        global $wpdb;
        
        $data = get_transient( 'wpsc_import_data' );
        
        // Create dummy post to get last_insert_id
                
        $sql = sprintf( "INSERT INTO {$wpdb->posts} (post_name) VALUES ('wpsc-dummy-post-%s') ", time() );
        $wpdb->query( $sql );

        // Get last post insert ID

        $last_post_id = (int) $wpdb->insert_id;
        $next_post_id = $last_post_id + 1;

        // Delete dummy post

        $sql = sprintf( "DELETE FROM {$wpdb->posts} WHERE ID = %d", $last_post_id );
        $wpdb->query( $sql );
        
        // Create pair of old_post_id => new_post_id
                
        $pair_category_ids = array();

        if ( isset( $data['categories'] ) ) {
            foreach ( $data['categories'] as $category ) {
                $old_post_id = $category['post_id'];
                $pair_category_ids[$old_post_id] = $next_post_id;
                $next_post_id++;
            }
        }
        
        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->posts} (ID, post_type, post_title, post_name, post_status, post_author, post_date, post_date_gmt, post_modified, post_modified_gmt) VALUES ";

        if ( isset( $data['categories'] ) ) {
            foreach ( $data['categories'] as $category ) {
                $old_post_id = $category['post_id'];
                $new_post_id = $pair_category_ids[$old_post_id];

                $data_params[] = '(%d, %s, %s, %s, %s, %d, %s, %s, %s, %s)';

                $data_values[] = $new_post_id;
                $data_values[] = 'important_date_cat';
                $data_values[] = $category['post_title'];
                $data_values[] = sprintf( 'category-%d', $new_post_id );
                $data_values[] = 'publish';
                $data_values[] = get_current_user_id();
                $data_values[] = current_time( 'mysql' );
                $data_values[] = current_time( 'mysql', 1 );
                $data_values[] = current_time( 'mysql' );
                $data_values[] = current_time( 'mysql', 1 );
            }
        }
        
        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        $valid_post_meta_keys = array(
            '_bgcolor',
            '_order',
        );
        
        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";

        if ( isset( $data['categories'] ) ) {
            foreach ( $data['categories'] as $category ) {
                $old_post_id = $category['post_id'];
                $new_post_id = $pair_category_ids[$old_post_id];

                foreach ( $category['post_meta'] as $meta_key => $meta_value ) {
                    if ( in_array( $meta_key, $valid_post_meta_keys ) ) {
                        $data_params[] = '(%d, %s, %s)';

                        $data_values[] = $new_post_id;
                        $data_values[] = $meta_key;
                        $data_values[] = $meta_value;
                    }
                }
            }
        }
        
        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        set_transient( 'wpsc_pair_category_ids', $pair_category_ids, 360 );
    }
    
    private function process_import_important_date() {
        global $wpdb;
        
        $data              = get_transient( 'wpsc_import_data' );
        $pair_group_ids    = get_transient( 'wpsc_pair_group_ids' );
        $pair_category_ids = get_transient( 'wpsc_pair_category_ids' );
        
        // Create dummy post to get last_insert_id
                
        $sql = sprintf( "INSERT INTO {$wpdb->posts} (post_name) VALUES ('wpsc-dummy-post-%s') ", time() );
        $wpdb->query( $sql );

        // Get last post insert ID

        $last_post_id = (int) $wpdb->insert_id;
        $next_post_id = $last_post_id + 1;

        // Delete dummy post

        $sql = sprintf( "DELETE FROM {$wpdb->posts} WHERE ID = %d", $last_post_id );
        $wpdb->query( $sql );
        
        // Create pair of old_post_id => new_post_id
                
        $pair_post_ids = array();
        
        if ( isset( $data['important-dates'] ) ) {
            foreach ( $data['important-dates'] as $important_date ) {
                $old_post_id = $important_date['post_id'];
                $pair_post_ids[$old_post_id] = $next_post_id;
                $next_post_id++;
            }
        }
        
        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->posts} (ID, post_type, post_title, post_name, post_status, post_author, post_date, post_date_gmt, post_modified, post_modified_gmt) VALUES ";

        if ( isset( $data['important-dates'] ) ) {
            foreach ( $data['important-dates'] as $important_date ) {
                $old_post_id = $important_date['post_id'];
                $new_post_id = $pair_post_ids[$old_post_id];

                $data_params[] = '(%d, %s, %s, %s, %s, %d, %s, %s, %s, %s)';

                $data_values[] = $new_post_id;
                $data_values[] = 'important_date';
                $data_values[] = $important_date['post_title'];
                $data_values[] = sprintf( 'important-date-%d', $new_post_id );
                $data_values[] = 'publish';
                $data_values[] = get_current_user_id();
                $data_values[] = current_time( 'mysql' );
                $data_values[] = current_time( 'mysql', 1 );
                $data_values[] = current_time( 'mysql' );
                $data_values[] = current_time( 'mysql', 1 );
            }
        }
        
        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        $valid_post_meta_keys = array(
            '_start_date',
            '_end_date',
            '_exclude_weekend',
            '_category_id',
            '_enable_recurring',
            '_recurring_type',
            '_recurring_options',
            '_additional_notes'
        );
        
        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";

        if ( isset( $data['important-dates'] ) ) {
            foreach ( $data['important-dates'] as $important_date ) {
                $old_post_id = $important_date['post_id'];
                $new_post_id = $pair_post_ids[$old_post_id];

                foreach ( $important_date['post_meta'] as $meta_key => $meta_value ) {
                    if ( in_array( $meta_key, $valid_post_meta_keys ) ) {
                        $data_params[] = '(%d, %s, %s)';

                        $data_values[] = $new_post_id;
                        $data_values[] = $meta_key;

                        if ( '_category_id' === $meta_key ) {
                            $data_values[] = $pair_category_ids[$meta_value];
                        } else {
                            $data_values[] = $meta_value;
                        }
                    }
                }
            }
        }
        
        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        // Delete Old Group Relationship
                
        $post_ids = array();

        if ( isset( $data['important-dates'] ) ) {
            foreach ( $data['important-dates'] as $important_date ) {
                $post_ids[] = $important_date['post_id'];
            }
        }

        if ( count( $post_ids ) > 0 ) {
            $post_ids = implode( ',', $post_ids );

            $sql = "DELETE FROM {$wpdb->term_relationships} WHERE object_id IN ({$post_ids})";
            $wpdb->query( $sql );
        }
        
        // Update Important Date Groups
                
        $data_params = array();
        $data_values = array();

        $sql = "INSERT INTO {$wpdb->term_relationships} (object_id, term_taxonomy_id) VALUES ";

        if ( isset( $data['important-dates'] ) ) {
            foreach ( $data['important-dates'] as $important_date ) {
                $old_post_id = $important_date['post_id'];
                $new_post_id = $pair_post_ids[$old_post_id];

                foreach ( $important_date['groups'] as $group ) {
                    $data_params[] = '(%d, %d)';

                    $old_term_id          = $group['term_id'];
                    $new_term_taxonomy_id = $pair_group_ids[$old_term_id]['new_term_taxonomy_id'];

                    $data_values[] = $new_post_id;
                    $data_values[] = $new_term_taxonomy_id;
                }
            }
        }

        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        // Update Groups Count
                
        $sql  = "UPDATE {$wpdb->term_taxonomy} tt SET count = ";
        $sql .= "( ";
        $sql .= "SELECT COUNT(*) FROM {$wpdb->term_relationships} tr ";
        $sql .= "LEFT JOIN {$wpdb->posts} p ON (p.ID = tr.object_id ";
        $sql .= "AND p.post_type = 'important_date' AND p.post_status = 'publish') ";
        $sql .= "WHERE tr.term_taxonomy_id = tt.term_taxonomy_id ";
        $sql .= ") ";
        $sql .= "WHERE tt.taxonomy = 'important_date_group'";

        $wpdb->query( $sql );
        
        set_transient( 'wpsc_pair_important_date_ids', $pair_post_ids, 360 );
    }
    
    private function process_import_calendars() {
        global $wpdb;
        
        $data              = get_transient( 'wpsc_import_data' );
        $pair_group_ids    = get_transient( 'wpsc_pair_group_ids' );
        $pair_category_ids = get_transient( 'wpsc_pair_category_ids' );
        
        // Create dummy post to get last_insert_id
                
        $sql = sprintf( "INSERT INTO {$wpdb->posts} (post_name) VALUES ('wpsc-dummy-post-%s') ", time() );
        $wpdb->query( $sql );

        // Get last post insert ID

        $last_post_id = (int) $wpdb->insert_id;
        $next_post_id = $last_post_id + 1;

        // Delete dummy post

        $sql = sprintf( "DELETE FROM {$wpdb->posts} WHERE ID = %d", $last_post_id );
        $wpdb->query( $sql );
        
        // Create pair of old_post_id => new_post_id
                
        $pair_post_ids = array();

        if ( isset( $data['calendars'] ) ) {
            foreach ( $data['calendars'] as $calendar ) {
                $old_post_id = $calendar['post_id'];
                $pair_post_ids[$old_post_id] = $next_post_id;
                $next_post_id++;
            }
        }
        
        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->posts} (ID, post_type, post_title, post_name, post_status, post_author, post_date, post_date_gmt, post_modified, post_modified_gmt) VALUES ";

        if ( isset( $data['calendars'] ) ) {
            foreach ( $data['calendars'] as $calendar ) {
                $old_post_id = $calendar['post_id'];
                $new_post_id = $pair_post_ids[$old_post_id];

                $data_params[] = '(%d, %s, %s, %s, %s, %d, %s, %s, %s, %s)';

                $data_values[] = $new_post_id;
                $data_values[] = 'school_calendar';
                $data_values[] = $calendar['post_title'];
                $data_values[] = sprintf( 'calendar-%d', $new_post_id );
                $data_values[] = 'publish';
                $data_values[] = get_current_user_id();
                $data_values[] = current_time( 'mysql' );
                $data_values[] = current_time( 'mysql', 1 );
                $data_values[] = current_time( 'mysql' );
                $data_values[] = current_time( 'mysql', 1 );
            }
        }
        
        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        $valid_post_meta_keys = array(
            '_calendar_options'
        );
        
        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";

        if ( isset( $data['calendars'] ) ) {
            foreach ( $data['calendars'] as $calendar ) {
                $old_post_id = $calendar['post_id'];
                $new_post_id = $pair_post_ids[$old_post_id];

                foreach ( $calendar['post_meta'] as $meta_key => $meta_value ) {
                    if ( in_array( $meta_key, $valid_post_meta_keys ) ) {
                        $data_params[] = '(%d, %s, %s)';

                        $data_values[] = $new_post_id;
                        $data_values[] = $meta_key;

                        if ( '_calendar_options' === $meta_key ) {
                            $options = maybe_unserialize( $meta_value );

                            if ( count( $options['groups'] ) > 0 ) {
                                $new_groups = array();

                                foreach ( $options['groups'] as $group ) {
                                    $new_groups[] = $pair_group_ids[$group]['new_term_id'];
                                }

                                $options['groups'] = $new_groups;
                            }

                            if ( count( $options['categories'] ) > 0 ) {
                                $new_categories = array();

                                foreach ( $options['categories'] as $category ) {
                                    $new_categories[] = $pair_category_ids[$category];
                                }

                                $options['categories'] = $new_categories;
                            }
                        }

                        $data_values[] = maybe_serialize( $options );
                    }
                }
            }
        }
        
        if ( count( $data_values ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
    }
    
    private function process_update_settings() {
        global $wpdb;
        
        $data              = get_transient( 'wpsc_import_data' );
        $pair_category_ids = get_transient( 'wpsc_pair_category_ids' );
        
        $data['settings']['default_category'] = $pair_category_ids[$data['settings']['default_category']];
        update_option( 'wpsc_options', $data['settings'] );
        
        delete_transient( 'wpsc_import_data' );
        delete_transient( 'wpsc_import_remove_existing' );
        delete_transient( 'wpsc_pair_group_ids' );
        delete_transient( 'wpsc_pair_category_ids' );
        delete_transient( 'wpsc_pair_important_date_ids' );
    }
    
    public function ajax_process_import() {
        check_ajax_referer( 'wpsc_admin', 'nonce' );
        
        $output = array();
        
        $valid_steps = apply_filters( 'wpsc_tools_import_valid_steps', array( 
            'remove_existing', 
            'import_groups', 
            'import_categories', 
            'import_important_dates', 
            'import_calendars', 
            'update_settings' 
        ) );
        
        $current_step = in_array( $_POST['current_step'], $valid_steps ) ? $_POST['current_step'] : 'invalid_step';
        
        switch ( $current_step ) {
            case 'remove_existing':
                $this->process_remove_existing();
                $output['next_step'] = 'import_groups';
                break;
            case 'import_groups':
                $this->process_import_groups();
                $output['next_step'] = 'import_categories';
                break;
            case 'import_categories':
                $this->process_import_categories();
                $output['next_step'] = 'import_important_dates';
                break;
            case 'import_important_dates':
                $this->process_import_important_date();
                $output['next_step'] = 'import_calendars';
                break;
            case 'import_calendars':
                $this->process_import_calendars();
                $output['next_step'] = 'update_settings';
                break;
            case 'update_settings':
                $this->process_update_settings();
                $output['next_step'] = '';
                break;
            default:
                do_action( 'wpsc_tools_' . $current_step );
                break;
        }
        
        wp_send_json_success( array( 'output' => apply_filters( 'wpsc_tools_import_ajax_output', $output, $current_step ) ) );
    }
}

WP_School_Calendar_Tools::instance();
