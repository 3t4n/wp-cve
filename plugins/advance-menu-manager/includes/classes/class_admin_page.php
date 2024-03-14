<?php

/**
 * Fired when admin page will loaded.
 *
 * This class executes when plugin admin page interface executes.
 *
 * @since      1.0.0
 * @package    Multidots Advance Menu Manager
 * @subpackage advance-menu-manager/includes/classes
 * @author     Multidots Solutions Pvt. Ltd. <info@multidots.com>
 */
class DSAMM_Admin_Interface
{
    private static  $depth_count_var = '' ;
    /**
     * This function runs when plugin activates. (use period)
     *
     * This function executes when plugin activates and object initialised.
     *
     * @since    1.0.0
     */
    /**
     * wordpress hook called in twise that whay we have made custom logic
     *
     */
    private  $past_revision = true ;
    /**
     * This function runs when menu deleted from the admin page. (use period)
     *
     * This function executes when menu deleted from the admin page.
     *
     * @since    1.0.0
     * @author   theDotstore
     */
    public static function dsamm_action_ajax_for_delete_menu()
    {
        // Security check
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        $delete_menu_id = filter_input( INPUT_POST, "delete_menu_id", FILTER_VALIDATE_INT );
        $delete_menu_id = ( !empty($delete_menu_id) ? intval( $delete_menu_id ) : 0 );
        
        if ( $delete_menu_id > 0 ) {
            $delete_menu_obj = wp_delete_nav_menu( $delete_menu_id );
            
            if ( $delete_menu_obj ) {
                $nav_menus = wp_get_nav_menus();
                if ( isset( $nav_menus[0]->term_id ) ) {
                    update_user_meta( get_current_user_id(), 'nav_menu_recently_edited', $nav_menus[0]->term_id );
                }
                wp_die( true );
            }
        
        }
        
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    
    /**
     * dsamm_save_existing_menu function
     *
     * This function is used to save existing menu items.
     *
     * @version 	1.0.0
     * @author 		Multidots
     * */
    function dsamm_save_existing_menu( $menu_items )
    {
        check_admin_referer( 'amm_pro_menu_action', 'amm_pro_menu_nonce_field' );
        // menu name and setting update
        $menu_name = filter_input( INPUT_POST, "menu-name", FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( empty($menu_name) ) {
            return $messages = '<div id="message" class="error notice is-dismissible"><p>' . __( 'Please enter menu name.', 'advance-menu-manager' ) . '</p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'advance-menu-manager' ) . '</span></button></div>';
        }
        /**
         * get location, menu id, current menu location for save location on menu
         */
        global  $wpdb ;
        $current_edit_menu_id = filter_input( INPUT_POST, "current_edit_menu_id", FILTER_VALIDATE_INT );
        if ( empty($current_edit_menu_id) || $current_edit_menu_id <= 0 ) {
            return $messages = '<div id="message" class="error notice is-dismissible"><p>' . __( 'Menu ID not found. Please try again.', 'advance-menu-manager' ) . '</p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'advance-menu-manager' ) . '</span></button></div>';
        }
        $locations = get_registered_nav_menus();
        $menu_locations = get_nav_menu_locations();
        $messages = '';
        // Remove menu locations that have been unchecked.
        foreach ( $locations as $location => $description ) {
            if ( (empty($_POST['menu-locations']) || empty($_POST['menu-locations'][$location])) && isset( $menu_locations[$location] ) && $menu_locations[$location] === $current_edit_menu_id ) {
                unset( $menu_locations[$location] );
            }
        }
        // Merge new and existing menu locations if any new ones are set.
        
        if ( isset( $_POST['menu-locations'] ) ) {
            $new_menu_locations = array_map( 'absint', $_POST['menu-locations'] );
            $menu_locations = array_merge( $menu_locations, $new_menu_locations );
        }
        
        // Set menu locations.
        set_theme_mod( 'nav_menu_locations', $menu_locations );
        //menu title and menu related other option will update
        $_menu_object = wp_get_nav_menu_object( $current_edit_menu_id );
        $menu_title = trim( esc_html( $menu_name ) );
        
        if ( !$menu_title ) {
            $messages = '<div id="message" class="error notice is-dismissible"><p>' . __( 'Please enter a valid menu name.', 'advance-menu-manager' ) . '</p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'advance-menu-manager' ) . '</span></button></div>';
            $menu_title = $_menu_object->name;
        }
        
        
        if ( !is_wp_error( $_menu_object ) ) {
            $old_menu_name = filter_input( INPUT_POST, 'old-menu-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            
            if ( $old_menu_name !== $menu_title ) {
                $menu_exists = wp_get_nav_menu_object( $menu_title );
                
                if ( empty($menu_exists) ) {
                    $current_edit_menu_id = wp_update_nav_menu_object( $current_edit_menu_id, array(
                        'menu-name' => $menu_title,
                    ) );
                    
                    if ( is_wp_error( $current_edit_menu_id ) ) {
                        $_menu_object = $current_edit_menu_id;
                        $messages = '<div id="message" class="error notice is-dismissible"><p>' . __( 'Please try again later.', 'advance-menu-manager' ) . '</p><button type="button" class="notice-dismiss"></div>';
                    } else {
                        $nav_menu_selected_title = $_menu_object->name;
                    }
                
                } else {
                    return $messages = '<div id="message" class="error notice is-dismissible"><p>' . $menu_title . ' ' . __( 'is already registered.', 'advance-menu-manager' ) . '</p><button type="button" class="notice-dismiss"></div>';
                }
            
            } else {
                $nav_menu_selected_title = $_menu_object->name;
            }
        
        }
        
        // Update menu items.
        
        if ( !is_wp_error( $_menu_object ) && !empty($nav_menu_selected_title) ) {
            $messages_wp = array();
            $messages_wp = array_merge( $messages_wp, wp_nav_menu_update_menu_items( $current_edit_menu_id, $nav_menu_selected_title ) );
            if ( !empty($messages_wp) ) {
                foreach ( $messages_wp as $mesg ) {
                    $messages .= $mesg;
                }
            }
        }
        
        if ( !is_wp_error( $current_edit_menu_id ) ) {
            update_user_meta( get_current_user_id(), 'nav_menu_recently_edited', $current_edit_menu_id );
        }
        // End menu name and setting update code
        $elements = array(
            'menu-item-db-id',
            'menu-item-object-id',
            'menu-item-object',
            'menu-item-parent-id',
            'menu-item-position',
            'menu-item-type'
        );
        $menu_items_obj = explode( ',', $menu_items );
        array_pop( $menu_items_obj );
        $menu_item_db_id = ( isset( $_POST['menu-item-db-id'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['menu-item-db-id'] ) ) : array() );
        $menu_item_db_id_count = ( is_array( $menu_item_db_id ) ? count( $menu_item_db_id ) : "0" );
        $end = 1 + $menu_item_db_id_count - 1;
        $keys = range( 1, $end );
        $deleted_nodes = ( isset( $_POST['delete_menu_items'] ) ? sanitize_text_field( $_POST['delete_menu_items'] ) : '' );
        $deleted_nodes = explode( ',', $deleted_nodes );
        if ( !empty($menu_item_db_id) ) {
            $menu_item_db_id = array_combine( $keys, $menu_item_db_id );
        }
        $defaults = array(
            'menu-item-db-id'       => 0,
            'menu-item-object-id'   => 0,
            'menu-item-object'      => '',
            'menu-item-parent-id'   => 0,
            'menu-item-position'    => 0,
            'menu-item-type'        => 'custom',
            'menu-item-title'       => '',
            'menu-item-url'         => '',
            'menu-item-description' => '',
            'menu-item-attr-title'  => '',
            'menu-item-target'      => '',
            'menu-item-classes'     => '',
            'menu-item-xfn'         => '',
            'menu-item-status'      => '',
        );
        $args = wp_parse_args( $_REQUEST, $defaults );
        $menu_order_node = 1;
        $loop_count = 0;
        
        if ( !empty($args['menu-item-object-id']) ) {
            foreach ( $args['menu-item-object-id'] as $key => $value ) {
                $current_post_id = $menu_item_db_id[$menu_order_node];
                
                if ( in_array( $current_post_id, $deleted_nodes, true ) ) {
                    wp_delete_post( $current_post_id );
                    delete_post_meta( $current_post_id, '_menu_item_type', sanitize_key( $args['menu-item-type'][$key] ) );
                    delete_post_meta( $current_post_id, '_menu_item_menu_item_parent', strval( (int) $args['menu-item-parent-id'][$key] ) );
                    delete_post_meta( $current_post_id, '_menu_item_object_id', strval( (int) $args['menu-item-object-id'][$key] ) );
                    delete_post_meta( $current_post_id, '_menu_item_object', sanitize_key( $args['menu-item-object'][$key] ) );
                } else {
                    
                    if ( isset( $args['menu-item-attr-title'][$loop_count] ) ) {
                        $update_my_nav_post = array(
                            'ID'           => $current_post_id,
                            'menu_order'   => sanitize_key( $menu_order_node ),
                            'post_excerpt' => sanitize_key( $args['menu-item-attr-title'][$loop_count] ),
                        );
                    } else {
                        $update_my_nav_post = array(
                            'ID'         => $current_post_id,
                            'menu_order' => sanitize_key( $menu_order_node ),
                        );
                    }
                    
                    wp_update_post( $update_my_nav_post );
                    update_post_meta( $current_post_id, '_menu_item_type', sanitize_key( $args['menu-item-type'][$key] ) );
                    update_post_meta( $current_post_id, '_menu_item_menu_item_parent', strval( (int) $args['menu-item-parent-id'][$key] ) );
                    update_post_meta( $current_post_id, '_menu_item_object_id', strval( (int) $args['menu-item-object-id'][$key] ) );
                    update_post_meta( $current_post_id, '_menu_item_object', sanitize_key( $args['menu-item-object'][$key] ) );
                    if ( !empty($args['menu-item-target'][$key]) ) {
                        update_post_meta( $current_post_id, '_menu_item_target', $args['menu-item-target'][$key] );
                    }
                }
                
                $menu_order_node++;
                $loop_count++;
            }
            // Foreach close
        }
        
        unset( $deleted_nodes );
        return $messages;
    }
    
    /**
     * dsamm_menu_container_print function
     *
     * This function is used to print the menu container in the plugin page.
     *
     * @version 	1.0.0
     * @author 		Multidots
     * */
    function dsamm_menu_container_print()
    {
        global  $gloable_all_author_array ;
        global  $gloable_all_template_array ;
        global  $gloable_all_category_array ;
        global  $gloable_all_current_menu_id ;
        $messages = array();
        //set all author globaly
        $allUsers = get_users( 'orderby=ID&order=ASC' );
        foreach ( $allUsers as $currentUser ) {
            if ( !in_array( 'subscriber', $currentUser->roles, true ) ) {
                $gloable_all_author_array[] = $currentUser;
            }
        }
        // set all template globaly
        $get_templates_all = get_page_templates();
        foreach ( $get_templates_all as $template_name => $template_filename ) {
            $gloable_all_template_array[$template_name] = $template_filename;
        }
        // set all category by globaly
        $all_category = get_categories( 'orderby=name&hide_empty=0' );
        foreach ( $all_category as $cat_data ) {
            $gloable_all_category_array[$cat_data->cat_ID] = $cat_data->cat_name;
        }
        $total_menu_items = filter_input( INPUT_POST, "total_menu_items", FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $form_submited_messages = ( isset( $_POST['save_menu'] ) ? DSAMM_Admin_Interface::dsamm_save_existing_menu( $total_menu_items ) : false );
        wp_nav_menu_post_type_meta_boxes();
        wp_nav_menu_taxonomy_meta_boxes();
        wp_enqueue_script( 'nav-menu' );
        if ( wp_is_mobile() ) {
            //phpcs:ignore
            wp_enqueue_script( 'jquery-touch-punch' );
        }
        $nav_menu_selected_id = ( isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0 );
        // Get recently edited nav menu.
        $recently_edited = absint( get_user_option( 'nav_menu_recently_edited', get_current_user_id() ) );
        if ( empty($recently_edited) && is_nav_menu( $nav_menu_selected_id ) ) {
            $recently_edited = $nav_menu_selected_id;
        }
        // Use $recently_edited if none are selected.
        if ( empty($nav_menu_selected_id) && !isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {
            $nav_menu_selected_id = $recently_edited;
        }
        
        if ( empty($nav_menu_selected_id) && !empty($nav_menus) && !$add_new_screen ) {
            // phpcs:ignore
            // if we have no selection yet, and we have menus, set to the first one in the list.
            $nav_menu_selected_id = $nav_menus[0]->term_id;
            // phpcs:ignore
        }
        
        // Update the user's setting.
        if ( $nav_menu_selected_id !== $recently_edited && is_nav_menu( $nav_menu_selected_id ) ) {
            update_user_meta( get_current_user_id(), 'nav_menu_recently_edited', $nav_menu_selected_id );
        }
        //if menu hase change on dropdwon  and save menu than recently menu option will update
        
        if ( !empty($_POST['page_on_front']) ) {
            $nav_menu_selected_id = (int) $_POST['page_on_front'];
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'nav_menu_recently_edited', $nav_menu_selected_id );
        }
        
        $locations = get_registered_nav_menus();
        $menu_locations = get_nav_menu_locations();
        $num_locations = count( array_keys( $locations ) );
        //submit themes location form
        if ( isset( $_GET['action'] ) && 'locations' === $_GET['action'] && isset( $_POST['menu-locations'] ) ) {
            
            if ( isset( $_POST['menu-locations'] ) ) {
                check_admin_referer( 'save-menu-locations' );
                $new_menu_locations = array_map( 'absint', $_POST['menu-locations'] );
                $menu_locations = array_merge( $menu_locations, $new_menu_locations );
                // Set menu locations
                set_theme_mod( 'nav_menu_locations', $menu_locations );
                $messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . __( 'Menu locations updated.', 'advance-menu-manager' ) . '</p></div>';
            }
        
        }
        $menu_revision_tab = '';
        
        if ( isset( $_GET['action'] ) && 'locations' === $_GET['action'] ) {
            $menu_location_flag = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        } else {
            $menu_location_flag = '';
        }
        
        $locations_screen = ( isset( $_GET['action'] ) && 'locations' === $_GET['action'] ? true : false );
        // Get all nav menus.
        $nav_menus = wp_get_nav_menus();
        $menu_count = count( $nav_menus );
        wp_nav_menu_setup();
        wp_initial_nav_menu_meta_boxes();
        // On deletion of menu, if another menu exists, show it.
        if ( empty($nav_menu_selected_id) && !empty($nav_menus[0]->term_id) ) {
            $nav_menu_selected_id = $nav_menus[0]->term_id;
        }
        //menu lock functionality
        $amm_current_menu_locked = '';
        $amm_current_menu_locked = DSAMM_Admin_Interface::dsamm_is_current_menu_locked( $nav_menu_selected_id );
        ?>
        <?php 
        ?>
        <div id="Advance_menu_manager_messages">
            <?php 
        if ( str_contains( $form_submited_messages, 'updated notice is-dismissible' ) ) {
            echo  wp_kses_post( '<div class="notice-warning amm-cs-notice notice is-dismissible"><p><b>Note</b>: To reflect the menu changes on the front, you need to again select the menu from the full site editing navigation block.</p></div>' ) ;
        }
        ?>
            <?php 
        if ( !empty($form_submited_messages) ) {
            echo  wp_kses_post( $form_submited_messages ) ;
        }
        ?>
        </div>
        <div id="wpbody">
            <div style="overflow: hidden;" id="wpbody-content" aria-label="Main content" tabindex="0">
                <div class="wrap">
                    <!-- <h2 class="nav-tab-wrapper">
                        <a href="<?php 
        echo  esc_url( site_url() ) ;
        ?>/wp-admin/themes.php?page=advance-menu-manager" class="nav-tab <?php 
        if ( "locations" !== $menu_location_flag ) {
            echo  'nav-tab-active' ;
        }
        ?> "> <?php 
        esc_html_e( 'Menus', 'advance-menu-manager' );
        ?></a>
                        <a href="<?php 
        echo  esc_url( site_url() ) ;
        ?>/wp-admin/themes.php?page=advance-menu-manager&action=locations" class="nav-tab <?php 
        if ( "locations" === $menu_location_flag ) {
            echo  'nav-tab-active' ;
        }
        ?>"><?php 
        esc_html_e( 'Manage Locations', 'advance-menu-manager' );
        ?></a>
                    </h2> -->
                    <?php 
        
        if ( $locations_screen ) {
            
            if ( 1 === $num_locations ) {
                echo  '<p>' . esc_html__( 'Your theme supports one menu. Select which menu you would like to use.', 'advance-menu-manager' ) . '</p>' ;
            } else {
                echo  '<p>' . sprintf( _n(
                    'Your theme supports %s menu. Select which menu appears in each location.',
                    'Your theme supports %s menus. Select which menu appears in each location.',
                    esc_html( $num_locations ),
                    'advance-menu-manager'
                ), esc_html( number_format_i18n( $num_locations ) ) ) . '</p>' ;
            }
            
            ?>
                        <div id="menu-locations-wrap">
                            <form method="post" action="">
                                <table class="widefat fixed" id="menu-locations-table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="manage-column column-locations"><?php 
            esc_html_e( 'Theme Location', 'advance-menu-manager' );
            ?></th>
                                            <th scope="col" class="manage-column column-menus"><?php 
            esc_html_e( 'Assigned Menu', 'advance-menu-manager' );
            ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="menu-locations">
                                        <?php 
            foreach ( $locations as $_location => $_name ) {
                ?>
                                            <tr class="menu-locations-row">
                                                <td class="menu-location-title"><label for="locations-<?php 
                echo  esc_attr( $_location ) ;
                ?>"><?php 
                echo  esc_html( $_name ) ;
                ?></label></td>
                                                <td class="manu-manager-plus-locations-container">
                                                    <select name="menu-locations[<?php 
                echo  esc_attr( $_location ) ;
                ?>]" id="locations-<?php 
                echo  esc_attr( $_location ) ;
                ?>">
                                                        <option value="0"><?php 
                printf( '&mdash; %s &mdash;', esc_html__( 'Select a Menu', 'advance-menu-manager' ) );
                ?></option>
                                                        <?php 
                foreach ( $nav_menus as $menu ) {
                    ?>
                                                            <?php 
                    $selected = isset( $menu_locations[$_location] ) && $menu_locations[$_location] === $menu->term_id;
                    ?>
                                                            <option <?php 
                    if ( $selected ) {
                        echo  'data-orig="true"' ;
                    }
                    ?> <?php 
                    selected( $selected );
                    ?> value="<?php 
                    echo  esc_attr( $menu->term_id ) ;
                    ?>">
                                                                <?php 
                    echo  esc_html( wp_html_excerpt( $menu->name, 40, '&hellip;' ) ) ;
                    ?>
                                                            </option>
                                                        <?php 
                }
                ?>
                                                    </select>
                                                    <div class="locations-row-links">
                                                        <?php 
                
                if ( isset( $menu_locations[$_location] ) && 0 !== $menu_locations[$_location] ) {
                    ?>
                                                            <span class="">
                                                                <a href="<?php 
                    echo  esc_url( add_query_arg( array(
                        'page' => 'advance-menu-manager',
                        'menu' => $menu_locations[$_location],
                    ), admin_url( 'themes.php' ) ) ) ;
                    ?>">
                                                                    <span aria-hidden="true"><?php 
                    echo  esc_html_x( 'Edit', 'menu', 'advance-menu-manager' ) ;
                    ?></span><span class="screen-reader-text"><?php 
                    esc_html_e( 'Edit selected menu', 'advance-menu-manager' );
                    ?></span>
                                                                </a>
                                                            </span>
                                                        <?php 
                }
                
                ?>
                                                    </div><!-- .locations-row-links -->
                                                </td><!-- .menu-location-menus -->
                                            </tr><!-- .menu-locations-row -->
                                        <?php 
            }
            // foreach
            ?>
                                    </tbody>
                                </table>
                                <p class="button-controls"><?php 
            submit_button(
                __( 'Save Changes', 'advance-menu-manager' ),
                'primary left',
                'nav-menu-locations',
                false
            );
            ?></p>
                                <?php 
            wp_nonce_field( 'save-menu-locations' );
            ?>
                                <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php 
            echo  esc_attr( $nav_menu_selected_id ) ;
            ?>" />
                            </form>
                        </div><!-- #menu-locations-wrap -->
                        <?php 
            /**
             * Fires after the menu locations table is displayed.
             *
             * @since 3.6.0
             */
            ?>
                    <?php 
        } else {
            ?>
                        <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php 
            echo  esc_attr( $nav_menu_selected_id ) ;
            ?>" />
                        <div class="manage-menus" <?php 
            if ( !empty($menu_location_flag) ) {
                echo  'style="display:none"' ;
            }
            ?>>
                            <form action="" name="menu_select" method="POST" >
                                <label for="menu" class="selected-menu"><?php 
            esc_html_e( 'Select a menu to edit', 'advance-menu-manager' );
            ?>:</label>

                                <select name="page_on_front" id="page_on_front">
                                    <?php 
            $current_selected_menu_name = '';
            foreach ( $nav_menus as $list_nav_menus ) {
                ?>
                                        <option value='<?php 
                echo  esc_attr( $list_nav_menus->term_id ) ;
                ?>' <?php 
                if ( $nav_menu_selected_id === $list_nav_menus->term_id ) {
                    echo  'selected=selected' ;
                }
                ?> class='level-0'>
                                                <?php 
                echo  esc_html( $list_nav_menus->name ) ;
                
                if ( !empty($menu_locations) && in_array( $list_nav_menus->term_id, $menu_locations, true ) ) {
                    $locations_assigned_to_this_menu = array();
                    foreach ( array_keys( $menu_locations, $list_nav_menus->term_id, true ) as $menu_location_key ) {
                        if ( isset( $locations[$menu_location_key] ) ) {
                            $locations_assigned_to_this_menu[] = $locations[$menu_location_key];
                        }
                    }
                    /**
                     * Filter the number of locations listed per menu in the drop-down select.
                     *
                     * @since 3.6.0
                     *
                     * @param int $locations Number of menu locations to list. Default 3.
                     */
                    $assigned_locations = array_slice( $locations_assigned_to_this_menu, 0, absint( apply_filters( 'wp_nav_locations_listed_per_menu', 3 ) ) );
                    // Adds ellipses following the number of locations defined in $assigned_locations.
                    if ( !empty($assigned_locations) ) {
                        printf( ' (%1$s%2$s)', implode( ', ', $assigned_locations ), ( count( $locations_assigned_to_this_menu ) > count( $assigned_locations ) ? ' &hellip;' : '' ) );
                    }
                }
                
                ?>
                                        </option>
                                        <?php 
                if ( $nav_menu_selected_id === $list_nav_menus->term_id ) {
                    $current_selected_menu_name = $list_nav_menus->name;
                }
            }
            ?>
                                </select>
                                <span class="submit-btn">
                                    <button type="submit" id="menu_submit_button" class="button-secondary" value="<?php 
            echo  esc_attr( 'Select' ) ;
            ?>"><?php 
            esc_html_e( 'Select', 'advance-menu-manager' );
            ?></button>
                                    <label for="menu" class="selected-menu_mymenu"><?php 
            esc_html_e( 'or', 'advance-menu-manager' );
            ?>
                                        &nbsp;&nbsp;<span class="add-new-menu-action_custom"><?php 
            esc_html_e( 'create a new menu', 'advance-menu-manager' );
            ?></span>
                                    </label>
                                </span>
                            </form>
                        </div><!-- /manage-menus -->
                        <?php 
            
            if ( $menu_count <= 0 ) {
                $display_none_property = 'style="display:none"';
                $display_block_property = 'style="display:block"';
            } else {
                $display_none_property = '';
                $display_block_property = '';
            }
            
            ?>
                        <div class="manage-menus" id="manage-menus_add_new_menu" <?php 
            if ( !empty($menu_location_flag) ) {
                echo  'style="display:none"' ;
            }
            echo  esc_attr( $display_block_property ) ;
            ?>>
                            <label for="menu" class="selected-menu_custom"><?php 
            esc_html_e( 'Menu Name', 'advance-menu-manager' );
            ?> &nbsp;</label>
                            <input name="custom-new-menu-name" id="custom-new-menu-name" class="menu-name regular-text menu-item-textbox input-with-default-title" placeholder="<?php 
            esc_attr_e( 'Enter menu name here', 'advance-menu-manager' );
            ?>" value="" type="text">
                            <span class="submit-btn_save_custom_menu">
                                <button name="save_custom_menu" id="save_menu_custom" class="button button-primary menu-save"><?php 
            esc_html_e( 'Create Menu', 'advance-menu-manager' );
            ?></button></span>
                        </div><!-- manage-menus add new menu -->

                        <div id="nav-menus-frame" class="menu_manager_plus" <?php 
            echo  esc_attr( $display_none_property ) ;
            ?>>
                            <div id="menu-management">


                                <form action="" method="post" enctype="multipart/form-data" id="md_amm_menu_form">

                                    <div id="nav-menu-header">
                                        <div class="major-publishing-actions">
                                            <label class="menu-name-label howto open-label" for="menu-name">
                                                <span><?php 
            esc_html_e( 'Menu Name', 'advance-menu-manager' );
            ?></span>
                                                <input name="menu-name" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" title="<?php 
            esc_attr_e( 'Enter menu name here', 'advance-menu-manager' );
            ?>" value="<?php 
            if ( !empty($current_selected_menu_name) ) {
                echo  esc_attr( $current_selected_menu_name ) ;
            }
            ?>">
                                                <input type="hidden"  name="old-menu-name" value="<?php 
            if ( !empty($current_selected_menu_name) ) {
                echo  esc_attr( $current_selected_menu_name ) ;
            }
            ?>">
                                            </label>
                                            <div class="publishing-action">
                                                <!-- <input type="submit" name="save_menu" id="amm_save_menu_header" class="button button-primary menu-save" value="Save Menu"> -->
                                                <button type="submit" name="save_menu" id="amm_save_menu_header" class="button button-primary menu-save" value="<?php 
            echo  esc_attr( 'Save Menu' ) ;
            ?>"><?php 
            esc_html_e( 'Save Menu', 'advance-menu-manager' );
            ?></button>
                                            </div><!-- END .publishing-action -->
                                        </div><!-- END .major-publishing-actions -->
                                    </div><!-- end nav-menu-header -->
                                    <?php 
            $menu_items = wp_get_nav_menu_items( $nav_menu_selected_id );
            $data = array();
            $mydata = $menu_items;
            ?>
                                    <div id="menu_container" <?php 
            
            if ( empty($menu_location_flag) ) {
                echo  'style="display:block"' ;
            } else {
                echo  'style="display:none"' ;
            }
            
            ?>>
                                        <div class="manage-menus"  id="menu_list_id">
                                            <div id="nav_menu_frame">

                                                <div id="menu-management-liquid">
                                                    <div id="" style="background:none;">
                                                        <h3><?php 
            esc_html_e( 'Menu Structure', 'advance-menu-manager' );
            ?></h3>
                                                        <div class="drag-instructions post-body-plain"><?php 
            esc_html_e( 'Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.', 'advance-menu-manager' );
            ?></div>
                                                        
                                                        <?php 
            
            if ( !empty($menu_items) ) {
                ?>
                                                            <div class="amm_top_menu_wrapper">
                                                                <!--p id="amm_menu_item_id" title="Toggle Menu item id">&nbsp;</p -->
                                                                <p id="nestable-menu">
                                                                    <span class="toggle_plus" id="toggle_plus_action" data-action="expand-all" title="<?php 
                esc_attr_e( 'Expand child menu item', 'advance-menu-manager' );
                ?>" style="display:none;"><?php 
                esc_html_e( 'Expand', 'advance-menu-manager' );
                ?></span><span class="toggle_minus" id="toggle_minus_action" data-action="collapse-all" title="<?php 
                esc_attr_e( 'Collapse child menu item', 'advance-menu-manager' );
                ?>"><?php 
                esc_html_e( 'Collapse', 'advance-menu-manager' );
                ?></span> | <span id="amm_menu_item_id" title="<?php 
                esc_attr_e( 'Toggle Menu item id', 'advance-menu-manager' );
                ?>"><?php 
                esc_html_e( 'Show Menu Id', 'advance-menu-manager' );
                ?></span>
                                                                </p>
                                                            </div>
                                                            <?php 
            }
            
            static  $depth = 0 ;
            $depth1 = 0;
            $flag_array = array();
            $flag_array_one = array();
            $menu_item_ids = '';
            
            if ( empty($menu_items) ) {
                echo  '<div class="myh2"><h3> ' . esc_html__( 'No Menu Found', 'advance-menu-manager' ) . '</h3></div>' ;
                echo  '<div class="myh2"><span class="add_first_menu_item"></span></div>' ;
                echo  '<ul id="menu-to-edit" class="menu ui-sortable menu-manager-plus-menu-wrapper"></ul>' ;
            } else {
                ?>
                                                            <ul id="menu-to-edit" class="menu ui-sortable menu-manager-plus-menu-wrapper">
                                                                <?php 
                $items_depth = $this->dsamm_calculate_menu_item_depth( $menu_items );
                if ( !empty($items_depth) && is_array( $items_depth ) ) {
                    foreach ( $items_depth as $current_item => $item_depth ) {
                        for ( $amm = 0 ;  $amm < count( $menu_items ) ;  $amm++ ) {
                            
                            if ( (int) $menu_items[$amm]->ID === $current_item ) {
                                // add menu id as globle
                                $gloable_all_current_menu_id[] = $menu_items[$amm]->object_id;
                                $depth = $item_depth;
                                $menu_item_ids .= $menu_items[$amm]->object_id . ",";
                                if ( !empty($menu_items[$amm + 1]->menu_item_parent) && $menu_items[$amm]->ID === $menu_items[$amm + 1]->menu_item_parent ) {
                                    $flag_array[] = $menu_items[$amm]->ID;
                                }
                                $current_menu_item_id = $menu_items[$amm]->db_id;
                                $current_menu_item_type = $menu_items[$amm]->type;
                                $current_menu_item_url = $menu_items[$amm]->url;
                                ?>
                                                                                <li id="menu-item-<?php 
                                if ( isset( $menu_items[$amm]->db_id ) ) {
                                    echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                }
                                ?>" class="menu-item menu-item-depth-<?php 
                                echo  esc_attr( $depth ) ;
                                ?> menu-item-page menu-item-edit-inactive" data-depth="<?php 
                                echo  esc_attr( $depth ) ;
                                ?>" >
                                                                                    <div class="menu-item-bar">
                                                                                        <div class="menu-item-handle ui-sortable-handle">
                                                                                            <span class="item-title">
                                                                                                <?php 
                                $menu_not_exist = '';
                                
                                if ( 'post_type' === $menu_items[$amm]->type ) {
                                    $post_status = get_post_status( $menu_items[$amm]->object_id );
                                    if ( 'publish' !== $post_status ) {
                                        //post exist or not
                                        $menu_not_exist = 'post_item_deleted';
                                    }
                                    ?>
                                                                                                    <span class="menu-item-title <?php 
                                    echo  esc_attr( $menu_not_exist ) ;
                                    ?> ">
                                                                                                        <?php 
                                    echo  esc_html( $menu_items[$amm]->title ) ;
                                    ?>
                                                                                                        <span class="amm_main_menu_item_edit <?php 
                                    echo  esc_attr( $menu_not_exist ) ;
                                    ?>" title="<?php 
                                    esc_attr_e( 'Edit this item', 'advance-menu-manager' );
                                    ?>">&nbsp;</span>
                                                                                                    </span>
                                                                                                <?php 
                                } else {
                                    ?>
                                                                                                    <span class="menu-item-title"><?php 
                                    echo  esc_html( $menu_items[$amm]->title ) ;
                                    ?></span>
                                                                                                    <?php 
                                }
                                
                                ?>
                                                                                                <span class="is-submenu"><?php 
                                if ( $menu_items[$amm]->menu_item_parent !== 0 ) {
                                    esc_html_e( 'sub item', 'advance-menu-manager' );
                                }
                                ?></span>
                                                                                            </span>
                                                                                            <span class="item-controls">
                                                                                                <span class="view_menu_id">#<?php 
                                echo  esc_html( $menu_items[$amm]->db_id ) ;
                                ?> </span>
                                                                                                <span class="menu_item_type"><?php 
                                echo  esc_html( $menu_items[$amm]->type_label ) ;
                                ?></span>
                                                                                                <span class="menu_sub_details" title="<?php 
                                esc_attr_e( 'View Attributes', 'advance-menu-manager' );
                                ?>">&nbsp;</span>
                                                                                                <span data-attr-menu-item='<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>' class="delete_node" title="<?php 
                                esc_attr_e( 'Delete this item', 'advance-menu-manager' );
                                ?>">X</span>
                                                                                            </span>
                                                                                        </div>

                                                                                        <span class="my-menu-controls">
                                                                                            <span class="my-menu-controls-groups">
                                                                                                <?php 
                                $menu_exisit_class_name = '';
                                if ( !empty($menu_items[$amm + 1]->menu_item_parent) && !empty($menu_items[$amm]->ID) ) {
                                    if ( $menu_items[$amm]->ID === $menu_items[$amm + 1]->menu_item_parent ) {
                                        $menu_exisit_class_name = 'chiled-hide';
                                    }
                                }
                                ?>
                                                                                                <span id="" class="click block_hide_show <?php 
                                echo  esc_attr( $menu_exisit_class_name ) ;
                                ?>" title="<?php 
                                esc_attr_e( 'Hide/Show child menu item', 'advance-menu-manager' );
                                ?>"></span>
                                                                                                <span id="" class="child_items" title="" ></span><span class="amm_highlighter" >&nbsp;</span>
                                                                                            </span>
                                                                                            <span class="add_menu_item_in_nav_menu" title="<?php 
                                esc_attr_e( 'Add new menu item below this menu item', 'advance-menu-manager' );
                                ?>">&nbsp;</span>
                                                                                        </span>

                                                                                    </div>
                                                                                    <div id="menu-item-settings-<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>" class="menu-item-settings menu-manager-plus-setting">
                                                                                        <?php 
                                
                                if ( 'custom' === $current_menu_item_type ) {
                                    ?>
                                                                                            <p class="field-url description description-wide">
                                                                                                <label for="edit-menu-item-url-<?php 
                                    echo  esc_attr( $current_menu_item_id ) ;
                                    ?>">
                                                                                                    <?php 
                                    esc_html_e( 'URL', 'advance-menu-manager' );
                                    ?><br />
                                                                                                    <input type="text" id="edit-menu-item-url-<?php 
                                    echo  esc_attr( $current_menu_item_id ) ;
                                    ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php 
                                    echo  esc_attr( $current_menu_item_id ) ;
                                    ?>]" value="<?php 
                                    echo  esc_attr( $current_menu_item_url ) ;
                                    ?>" />
                                                                                                </label>
                                                                                            </p>
                                                                                        <?php 
                                }
                                
                                ?>
                                                                                        <p class="description description-wide">
                                                                                            <label for="edit-menu-item-title-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>">
                                                                                                <?php 
                                esc_html_e( 'Navigation Label', 'advance-menu-manager' );
                                ?><br />
                                                                                                <input type="text" id="edit-menu-item-title-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->title ) ;
                                ?>" />
                                                                                            </label>
                                                                                        </p>
                                                                                        <p class="field-title-attribute description description-wide">
                                                                                            <label for="edit-menu-item-attr-title-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>">
                                                                                                <?php 
                                esc_html_e( 'Title Attribute', 'advance-menu-manager' );
                                ?><br />
                                                                                                <input type="text" id="edit-menu-item-attr-title-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->post_excerpt ) ;
                                ?>" />
                                                                                            </label>
                                                                                        </p>
                                                                                        <p class="field-link-target description">
                                                                                            <label for="edit-menu-item-target-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>">
                                                                                                <input type="checkbox" id="edit-menu-item-target-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>" value="_blank" name="menu-item-target[<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>]"<?php 
                                checked( $menu_items[$amm]->target, '_blank' );
                                ?> />
                                                                                                <?php 
                                esc_html_e( 'Open link in a new window/tab', 'advance-menu-manager' );
                                ?>
                                                                                            </label>
                                                                                        </p>
                                                                                        <p class="field-css-classes description description-thin">
                                                                                            <label for="edit-menu-item-classes-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>">
                                                                                                <?php 
                                esc_html_e( 'CSS Classes (optional)', 'advance-menu-manager' );
                                ?><br />
                                                                                                <input type="text" id="edit-menu-item-classes-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( implode( ' ', $menu_items[$amm]->classes ) ) ;
                                ?>" />
                                                                                            </label>
                                                                                        </p>
                                                                                        <p class="field-xfn description description-thin">
                                                                                            <label for="edit-menu-item-xfn-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>">
                                                                                                <?php 
                                esc_html_e( 'Link Relationship (XFN)', 'advance-menu-manager' );
                                ?><br />
                                                                                                <input type="text" id="edit-menu-item-xfn-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->xfn ) ;
                                ?>" />
                                                                                            </label>
                                                                                        </p>
                                                                                        <p class="field-description description description-wide">
                                                                                            <label for="edit-menu-item-description-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>">
                                                                                                <?php 
                                esc_html_e( 'Description', 'advance-menu-manager' );
                                ?><br />
                                                                                                <textarea id="edit-menu-item-description-<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php 
                                echo  esc_attr( $current_menu_item_id ) ;
                                ?>]"><?php 
                                echo  esc_html( $menu_items[$amm]->description ) ;
                                // textarea_escaped
                                ?></textarea>
                                                                                                <span class="description"><?php 
                                esc_html_e( 'The description will be displayed in the menu if the current theme supports it.', 'advance-menu-manager' );
                                ?></span>
                                                                                            </label>
                                                                                        </p>
                                                                                        <div class="menu-item-actions description-wide submitbox">
                                                                                            <a href="#" class="item-delete submitdelete deletion submitdelete menu_manager-plus-setting-delete"><?php 
                                esc_html_e( 'Remove', 'advance-menu-manager' );
                                ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a href="#" class="item-cancel submitcancel hide-if-no-js menu_manager-plus-setting-cancel"><?php 
                                esc_html_e( 'Cancel', 'advance-menu-manager' );
                                ?></a>
                                                                                        </div>

                                                                                        <input class="menu-item-data-db-id" name="menu-item-db-id[<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>" type="hidden">
                                                                                        <input class="menu-item-data-object-id" name="menu-item-object-id[<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->object_id ) ;
                                ?>" type="hidden">

                                                                                        <input class="menu-item-data-object" name="menu-item-object[<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->object ) ;
                                ?>" type="hidden">
                                                                                        <input class="menu-item-data-parent-id" name="menu-item-parent-id[<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->menu_item_parent ) ;
                                ?>" type="hidden">
                                                                                        <input class="menu-item-data-position" name="menu-item-position[<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->menu_order ) ;
                                ?>" type="hidden">
                                                                                        <input class="menu-item-data-type" name="menu-item-type[<?php 
                                echo  esc_attr( $menu_items[$amm]->db_id ) ;
                                ?>]" value="<?php 
                                echo  esc_attr( $menu_items[$amm]->type ) ;
                                ?>" type="hidden">
                                                                                    </div>
                                                                                    <ul class="menu-item-transport"></ul>
                                                                                    <div style="display: none;" class="amm_block_highlight"></div>
                                                                                </li>
                                                                                <?php 
                                if ( !empty($menu_items[$amm + 1]->menu_item_parent) && $menu_items[$amm + 1]->menu_item_parent !== $menu_items[$amm]->ID ) {
                                    
                                    if ( in_array( $menu_items[$amm + 1]->menu_item_parent, $flag_array, true ) ) {
                                        $a = array_search( $menu_items[$amm + 1]->menu_item_parent, $flag_array, true );
                                        $depth = $a - -1;
                                    }
                                
                                }
                            }
                        
                        }
                        // for loop end
                    }
                }
                ?>
                                                            </ul>
                                                            <?php 
            }
            
            // No menus found
            ?>
                                                    </div>
                                                </div><!-- menu-management-liquid-->
                                            </div>
                                        </div><!--- Manage menus -->
                                        <div class="menu-settings" <?php 
            if ( !empty($one_theme_location_no_menus) ) {
                ?>style="display: none;"<?php 
            }
            // phpcs:ignore
            ?>>
                                            <h3><?php 
            esc_html_e( 'Menu Settings', 'advance-menu-manager' );
            ?></h3>
                                            <?php 
            $auto_add = get_option( 'nav_menu_options' );
            
            if ( !isset( $auto_add['auto_add'] ) ) {
                $auto_add = false;
            } elseif ( false !== array_search( $nav_menu_selected_id, $auto_add['auto_add'], true ) ) {
                $auto_add = true;
            } else {
                $auto_add = false;
            }
            
            ?>

                                            <dl class="auto-add-pages">
                                                <dt class="howto"><?php 
            esc_html_e( 'Auto add pages', 'advance-menu-manager' );
            ?></dt>
                                                <dd class="checkbox-input"><input type="checkbox"<?php 
            checked( $auto_add );
            ?> name="auto-add-pages" id="auto-add-pages" value="1" /> <label for="auto-add-pages"><?php 
            printf( esc_html__( 'Automatically add new top-level pages to this menu', 'advance-menu-manager' ), esc_url( admin_url( 'edit.php?post_type=page' ) ) );
            ?></label></dd>
                                            </dl>

                                            <?php 
            
            if ( current_theme_supports( 'menus' ) ) {
                ?>
                                                <dl class="menu-theme-locations">
                                                    <dt class="howto"><?php 
                esc_html_e( 'Theme locations', 'advance-menu-manager' );
                ?></dt>
                                                    <?php 
                foreach ( $locations as $location => $description ) {
                    ?>
                                                        <dd class="checkbox-input">
                                                            <input type="checkbox"<?php 
                    checked( isset( $menu_locations[$location] ) && $menu_locations[$location] === $nav_menu_selected_id );
                    ?> name="menu-locations[<?php 
                    echo  esc_attr( $location ) ;
                    ?>]" id="locations-<?php 
                    echo  esc_attr( $location ) ;
                    ?>" value="<?php 
                    echo  esc_attr( $nav_menu_selected_id ) ;
                    ?>" /> <label for="locations-<?php 
                    echo  esc_attr( $location ) ;
                    ?>"><?php 
                    echo  esc_html( $description ) ;
                    ?></label>
                                                            <?php 
                    
                    if ( !empty($menu_locations[$location]) && $menu_locations[$location] !== $nav_menu_selected_id ) {
                        ?>
                                                                <span class="theme-location-set"> <?php 
                        printf( esc_html__( "(Currently set to: %s)", 'advance-menu-manager' ), esc_html( wp_get_nav_menu_object( $menu_locations[$location] )->name ) );
                        ?> </span>
                                                            <?php 
                    }
                    
                    ?>
                                                        </dd>
                                                    <?php 
                }
                ?>
                                                </dl>
                                            <?php 
            }
            
            ?>
                                        </div>
                                        <div class="submit-btn_save_menu">
                                            <input type="hidden" name="current_edit_menu_id" value="<?php 
            echo  esc_attr( $nav_menu_selected_id ) ;
            ?>" />
                                            <input type="hidden" name="delete_menu_items" value="" id="delete_menu_items">
                                            <input type="hidden" name="total_menu_items" value="<?php 
            echo  esc_attr( $menu_item_ids ) ;
            ?>" id="total_menu_items">
                                            <?php 
            wp_nonce_field( 'amm_pro_menu_action', 'amm_pro_menu_nonce_field' );
            ?>
                                            <span class="submit-btn_save_menu_span">
                                                <button type="submit" name="save_menu" id="amm_save_menu_header" class="button button-primary menu-save" value="<?php 
            echo  esc_attr( 'Save Menu' ) ;
            ?>"><?php 
            esc_html_e( 'Save Menu', 'advance-menu-manager' );
            ?></button>
                                            </span>
                                            <span id="amm_menu_delete" class="submit_delete"><?php 
            esc_html_e( 'Delete Menu', 'advance-menu-manager' );
            ?> </span>
                                            <input readonly="readonly" name="amm_form_revision_form" value="amm_form" type="hidden">
                                            <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php 
            echo  esc_attr( $nav_menu_selected_id ) ;
            ?>" />
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div> <!-- / menu_manager_plus -->
                    </div><!-- / wrap-->
                </div><!-- /#wpbody-content -->
            </div><!-- /wpbody -->

            <!-- popup -->
            <div id="menu_manager_popup">
                <div id="menu_manager_popup_container">
                    <div id="mm_cancel">X</div>
                    <div id="menu_manager_popup_main-wrapper" class="menu_manager_plus">
                        <div class="menu_page_cat_tag_form_wrapper">
                            <div id="nav-menus-frame">

                                <div id="menu-settings-column" class="metabox-holder<?php 
            if ( isset( $_GET['menu'] ) && '0' === $_GET['menu'] ) {
                echo  ' metabox-holder-disabled' ;
            }
            ?>">
                                    <div class="clear"></div>
                                    <form id="nav-menu-meta" class="nav-menu-meta" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php 
            echo  esc_attr( $nav_menu_selected_id ) ;
            ?>" />
                                        <input type="hidden" name="action" value="add-menu-item" />
                                        <?php 
            wp_nonce_field( 'add-menu_item', 'menu-settings-column-nonce' );
            ?>
                                        <?php 
            dsamm_accordion_sections_own( 'nav-menus', 'side', null );
            ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end popup -->
        <?php 
        }
    
    }
    
    /**
     * This function is used to calculate menu items depth.
     *
     * @version     1.0.0
     * @author      Multidots
     * */
    public function dsamm_calculate_menu_item_depth( $menu_items )
    {
        $item_depth = array();
        $stack = array();
        foreach ( $menu_items as $key => $item ) {
            $item_depth[$item->ID] = 0;
            
            if ( (int) $item->menu_item_parent !== 0 ) {
                while ( !empty($stack) && (int) $item->menu_item_parent !== $stack[count( $stack ) - 1]->ID ) {
                    array_pop( $stack );
                }
                $parent = $stack[count( $stack ) - 1];
                $item_depth[$item->ID] = $item_depth[$parent->ID] + 1;
            }
            
            $stack[] = $item;
        }
        return $item_depth;
    }
    
    /**
     * dsamm_action_ajax_for_create_menu function
     *
     * This function will add your newly entered menu name in textbox.
     *
     * @version		1.0.0
     * @author 		theDotstore
     */
    public static function dsamm_action_ajax_for_create_menu()
    {
        // Security check
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        // Check if the menu exists
        $menu_name = filter_input( INPUT_POST, 'new_menu_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $menu_exists = wp_get_nav_menu_object( $menu_name );
        // If it doesn't exist, let's create it.
        
        if ( !$menu_exists ) {
            $menu_id = wp_create_nav_menu( $menu_name );
            $user_details = wp_get_current_user();
            update_user_meta( $user_details->ID, 'nav_menu_recently_edited', $menu_id );
            echo  1 ;
        } else {
            /* New menu allreday there */
            echo  2 ;
        }
        
        exit;
    }
    
    /**
     * dsamm_amm_duplicate_menu function
     * 
     * This function will duplicate the selected menu.
     * 
     * @version     1.0.0
     * @author      Multidots
     */
    public static function dsamm_amm_duplicate_menu()
    {
        // Security check
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        $menuid = filter_input( INPUT_POST, 'menuid', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $menuname = filter_input( INPUT_POST, 'menuname', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $ncm = '';
        
        if ( !empty($menuid) && !empty($menuname) ) {
            $menu_exists = wp_get_nav_menu_object( $menuname . ' (copy)' );
            
            if ( !$menu_exists ) {
                $menu_items = wp_get_nav_menu_items( $menuid );
                $menu_id = wp_create_nav_menu( $menuname . ' (copy)' );
                $ncm_arry = array();
                foreach ( $menu_items as $item ) {
                    
                    if ( !empty($item->menu_item_parent) && '0' !== $item->menu_item_parent ) {
                        
                        if ( array_key_exists( $item->menu_item_parent, $ncm_arry ) ) {
                            $ncm_arryval = $ncm_arry[$item->menu_item_parent];
                        } else {
                            $ncm_arryval = '';
                        }
                        
                        $childid = $ncm_arryval;
                    } else {
                        $childid = '0';
                    }
                    
                    $pid = get_post_meta( $item->ID, '_menu_item_object_id', true );
                    $ppod = get_permalink( $pid );
                    
                    if ( 'post_type' === $item->type ) {
                        $type_label = ( $item->type_label === 'page' || $item->type_label === 'post' ? $item->type_label : 'page' );
                        $ncm = wp_update_nav_menu_item( $menu_id, 0, array(
                            'menu-item-db-id'       => $item->db_id,
                            'menu-item-target'      => $item->target,
                            'menu-item-attr-title'  => $item->post_excerpt,
                            'menu-item-xfn'         => $item->xfn,
                            'menu-item-parent-id'   => $childid,
                            'menu-item-title'       => $item->title,
                            'menu-item-object'      => $type_label,
                            'menu-item-object-id'   => $pid,
                            'menu-item-type'        => $item->type,
                            'menu-item-classes'     => esc_attr( implode( ' ', $item->classes ) ),
                            'menu-item-status'      => 'publish',
                            'menu-item-description' => $item->description,
                        ) );
                    } else {
                        $ncm = wp_update_nav_menu_item( $menu_id, 0, array(
                            'menu-item-db-id'       => $item->db_id,
                            'menu-item-parent-id'   => $childid,
                            'menu-item-target'      => $item->target,
                            'menu-item-attr-title'  => $item->post_excerpt,
                            'menu-item-xfn'         => $item->xfn,
                            'menu-item-title'       => $item->title,
                            'menu-item-object'      => $item->type_label,
                            'menu-item-type'        => 'custom',
                            'menu-item-classes'     => esc_attr( implode( ' ', $item->classes ) ),
                            'menu-item-status'      => 'publish',
                            'menu-item-url'         => $item->url,
                            'menu-item-description' => $item->description,
                        ) );
                    }
                    
                    $ncm_arry[$item->ID] = $ncm;
                }
            } else {
                echo  0 ;
            }
        
        }
        
        exit;
    }
    
    /**
     * 
     * AMM lock menu feature
     * 
     * @version		1.0.0
     * @author 		theDotstore
     */
    function dsamm_is_current_menu_locked( $menu_id = NULL )
    {
        
        if ( empty($menu_id) ) {
            return false;
        } else {
            $user_details = wp_get_current_user();
            if ( empty($user_details->ID) ) {
                return false;
            }
            $menu_lock_status = get_option( 'menu_lock_status_' . $menu_id );
            
            if ( 'Enabled' === $menu_lock_status ) {
                $amm_whitelisted_user_for_menu = get_option( 'amm_user_' . $menu_id );
                if ( empty($amm_whitelisted_user_for_menu) ) {
                    return true;
                }
                $user_list = maybe_unserialize( $amm_whitelisted_user_for_menu );
                
                if ( in_array( $user_details->ID, $user_list, true ) ) {
                    return false;
                } else {
                    return true;
                }
            
            } else {
                return false;
            }
        
        }
    
    }
    
    /**
     * 
     * Allow HTML tags
     * 
     * @version		1.0.0
     * @author 		theDotstore
     */
    function dsamm_allowed_html_tags( $tags = array() )
    {
        $allowed_tags = array(
            'a'        => array(
            'href'    => array(),
            'title'   => array(),
            'data-id' => array(),
            'class'   => array(),
        ),
            'p'        => array(
            'href'  => array(),
            'title' => array(),
            'class' => array(),
        ),
            'span'     => array(
            'href'  => array(),
            'title' => array(),
            'class' => array(),
        ),
            'ul'       => array(
            'class' => array(),
        ),
            'img'      => array(
            'href'  => array(),
            'title' => array(),
            'class' => array(),
            'src'   => array(),
        ),
            'li'       => array(
            'class' => array(),
        ),
            'h1'       => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'h2'       => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'h3'       => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'h4'       => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'div'      => array(
            'class'     => array(),
            'id'        => array(),
            "data-max"  => array(),
            "data-min"  => array(),
            "stlye"     => array(),
            "data-name" => array(),
            "data-type" => array(),
            "data-key"  => array(),
        ),
            'select'   => array(
            'id'       => array(),
            'name'     => array(),
            'class'    => array(),
            'multiple' => array(),
            'style'    => array(),
        ),
            'input'    => array(
            'id'    => array(),
            'value' => array(),
            'name'  => array(),
            'class' => array(),
            'type'  => array(),
        ),
            'textarea' => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'td'       => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'tr'       => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'tbody'    => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'table'    => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'option'   => array(
            'id'       => array(),
            'selected' => array(),
            'name'     => array(),
            'value'    => array(),
        ),
            'br'       => array(),
            'em'       => array(),
            'strong'   => array(),
            'label'    => array(
            'for' => array(),
        ),
        );
        if ( !empty($tags) ) {
            foreach ( $tags as $key => $value ) {
                $allowed_tags[$key] = $value;
            }
        }
        return $allowed_tags;
    }

}