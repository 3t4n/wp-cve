<?php if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'MENU_OPTION_Functions' ) ) {

	class MENU_OPTION_Functions {

		function __construct() {

			add_action( 'wp_nav_menu_item_custom_fields', array($this, 'MO_add_menu_item_option'), 10, 2 );
			add_action( 'wp_update_nav_menu_item', array($this, 'MO_save_menu_item_option'), 10, 2 );

			add_filter( 'wp_nav_menu_objects', array($this, 'MO_filtered_nav_menu_item'), 9999, 2 );
			add_filter( 'wp_get_nav_menu_items', array($this, 'MO_filtered_get_nav_menu_items'), 9999, 3 );
		}

		/**
		 * Add mo option to menu item - Admin
		 *
		 * @since 1.0
		 */
		function MO_add_menu_item_option( $item_id, $item ) {

		    wp_nonce_field( 'mo_option_nonce', '_mo_option_nonce_name' );
		    $mo_option = get_post_meta($item_id, '_mo-option', true);
		    $mo_option_roles = get_post_meta($item_id, '_mo-option-roles', true);
		    $mo_option_redirect = get_post_meta($item_id, '_mo-option-redirect', true);
		    if(empty($mo_option_roles)){
		        $mo_option_roles = array();
		    }
		    ?>
		    <div class="mo-option-fields">
		        <div class="mo-option description description-wide" style="margin:10px 0;">
		            <label for="mo-menu-item-option-<?php _e( $item_id ); ?>" >
		                <?php _e('Who can see this menu link?', 'menuoption'); ?><br/>
		                <select
		                    id="mo-menu-item-option-<?php _e( $item_id ); ?>" 
		                    name="mo-menu-item-option[<?php _e( $item_id ); ?>]"
		                >
		                    <option value="0" <?php selected( $mo_option, 0 ); ?>><?php _e( 'Everyone', 'menuoption' ); ?></option>
		                    <option value="1" <?php selected( $mo_option, 1 ); ?>><?php _e( 'Logged out users', 'menuoption' ); ?></option>
		                    <option value="2" <?php selected( $mo_option, 2 ); ?>><?php _e( 'Logged in users', 'menuoption' ); ?></option>
		                    <option value="3" <?php selected( $mo_option, 3 ); ?>><?php _e( 'Logout Link', 'menuoption' ); ?></option>
		                </select>
		            </label>
		        </div>
		        <div class="mo-option-roles description description-wide" style="margin-bottom:10px;display: none;">
		            <?php _e('Select the roles that can see this menu', 'menuoption'); ?><br/>
		            
		            <?php
		            global $wp_roles;
		            $roles = $wp_roles->roles;
		            
		            $counter = 0;
		            $fieldhtml = '<table style="width:100%"><tr>';
		            
		                foreach ( $roles as $key=>$role ) {
		                    $id_attr = ' id="mo-menu-item-option-roles-' . $item_id . '_' . $key . '" ';
		                    $for_attr = ' for="mo-menu-item-option-roles-' . $item_id . '_' . $key . '" ';
		                    $checked_attr = checked( in_array($key,$mo_option_roles), true, false );
		                    $fieldhtml .= "<td width='50%'><label {$for_attr}> <input type='checkbox' {$id_attr} name='mo-menu-item-option-roles[{$item_id}][{$key}]' value='{$key}' {$checked_attr} /> <span>{$role['name']}</span> </label></td>";

		                    $counter++;
		                    if ($counter % 2 == 0) {
		                        $fieldhtml .= '</tr><tr>';
		                    }
		                }
		            $fieldhtml .= '</tr></table>';
		            _e( $fieldhtml );
		            ?>

		        </div>
		        <p class="mo-option-redirect description description-wide" style="margin-bottom:10px;display: none;">
		            		            
		            <label for="edit-menu-item-title-17">
						<?php _e('Redirect URL', 'menuoption'); ?><br/>
						<input type="text" id="mo-menu-item-option-redirect" name="mo-menu-item-option-redirect[<?php _e( $item_id ); ?>]" value="<?php _e($mo_option_redirect);?>" class="widefat">
					</label>
		        </p>
		    </div>
		    <?php
		}

		/**
		 * Save mo option of menu item - Admin
		 *
		 * @since 1.0
		 */
		function MO_save_menu_item_option( $menu_id, $menu_item_db_id ) {

		    // Verify none for proper authorization.
		    if ( ! isset( $_POST['_mo_option_nonce_name'] ) || ! wp_verify_nonce( $_POST['_mo_option_nonce_name'], 'mo_option_nonce' ) ) {
		        return $menu_id;
		    }

		    if ( isset( $_POST['mo-menu-item-option'][$menu_item_db_id]  )  && $_POST['mo-menu-item-option'][$menu_item_db_id] != 0 ) {
		        $sanitized_data = sanitize_text_field( $_POST['mo-menu-item-option'][$menu_item_db_id] );
		        update_post_meta( $menu_item_db_id, '_mo-option', $sanitized_data );
		    } else {
		        delete_post_meta( $menu_item_db_id, '_mo-option' );
		    }

		    if ( isset( $_POST['mo-menu-item-option-roles'][$menu_item_db_id]  ) && isset( $_POST['mo-menu-item-option'][$menu_item_db_id]  )  && $_POST['mo-menu-item-option'][$menu_item_db_id] == 2 ) {
		    	$mo_roles = array_map( 'sanitize_text_field', wp_unslash( array_keys($_POST['mo-menu-item-option-roles'][$menu_item_db_id]) ) );
		    	update_post_meta( $menu_item_db_id, '_mo-option-roles', $mo_roles );
		    } else {
		        delete_post_meta( $menu_item_db_id, '_mo-option-roles' );
		    }

		    if ( isset( $_POST['mo-menu-item-option-redirect'][$menu_item_db_id]  ) && isset( $_POST['mo-menu-item-option'][$menu_item_db_id]  )  && $_POST['mo-menu-item-option'][$menu_item_db_id] == 3 ) {
		    	$mo_redirect = sanitize_text_field( $_POST['mo-menu-item-option-redirect'][$menu_item_db_id] );
		    	update_post_meta( $menu_item_db_id, '_mo-option-redirect', $mo_redirect );
		    } else {
		        delete_post_meta( $menu_item_db_id, '_mo-option-redirect' );
		    }
		}

		/**
		 * Manage menu item - Frontend
		 *
		 * @since 1.0
		 */
		function MO_filtered_nav_menu_item( $menu_items, $args ) {
		    
		    if ( is_admin() || empty( $menu_items ) ) {
		        return $menu_items;
		    }

		    $filteredmenu = array();
		    $childrenmenu = array();

		    foreach ( $menu_items as $item ) {

		        $mooption = get_post_meta( $item->ID, '_mo-option', true );
		        $moroles = get_post_meta( $item->ID, '_mo-option-roles', true );
		        $moredirect = get_post_meta( $item->ID, '_mo-option-redirect', true );

		        $visible = true;

		        if ( in_array( $item->menu_item_parent, $childrenmenu ) ) {
		            $visible = false;
		            $childrenmenu[] = $item->ID;
		        }

		        if ( isset( $mooption ) && $visible ) {

		            switch( $mooption ) {

		                case 2:
		                    if ( is_user_logged_in() && ! empty( $moroles ) ) {
		                        if ( current_user_can( 'administrator' ) ) {
		                            $visible = true;
		                        } else {
		                            global $current_user;
		                            $current_user_roles = $current_user->roles;
		                            if ( empty( $current_user_roles ) ) {
		                                $visible = false;
		                            } else {
		                                $visible = ( count( array_intersect( $current_user_roles, (array)$moroles ) ) > 0 ) ? true : false;
		                            }
		                        }
		                    } else {
		                        $visible = is_user_logged_in() ? true : false;
		                    }
		                    break;

		                case 1:
		                    $visible = ! is_user_logged_in() ? true : false;
		                    break;

		                case 3:
		                	if ( is_user_logged_in() ) {
		                		$item->url = wp_logout_url($moredirect);
		                	}else{
		                		$visible = false;
		                	}
		                	break;

		            }

		        }

		        $visible = apply_filters( 'MO_menu_item_visibility', $visible, $item );

		        if ( ! $visible ) {
		            $childrenmenu[] = $item->ID;
		        } else {
		            $filteredmenu[] = $item;
		            continue;
		        }

		    }
		    return $filteredmenu;
		}

		/**
		 * Manage menu item - Frontend
		 *
		 * @since 1.0
		 */
		function MO_filtered_get_nav_menu_items( $items, $menu, $args ) {
		    return $this->MO_filtered_nav_menu_item( $items, $args );
		}

	}

}