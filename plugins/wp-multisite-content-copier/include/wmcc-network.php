<?php
if ( ! function_exists( 'WMCC_add_network_admin_menu' ) ) {
    add_action( 'network_admin_menu', 'WMCC_add_network_admin_menu' );
    function WMCC_add_network_admin_menu() {
        
        add_menu_page( 'WordPress Multisite Content Copier/Updater', 'Content Copier', 'manage_options', 'wordpress-multisite-content-copier', 'WMCC_wordpress_multisite_content_copier' );
        add_submenu_page( 'wordpress-multisite-content-copier', 'Content Copier: Bulk Copy/Update', 'Bulk Copy/Update', 'manage_options', 'wordpress-multisite-content-copier', 'WMCC_wordpress_multisite_content_copier' );
        add_submenu_page( 'wordpress-multisite-content-copier', 'Content Copier: Settings', 'Settings', 'manage_options', 'wordpress-multisite-content-copier-settings', 'WMCC_wordpress_multisite_content_copier_settings' );
    }
}

if ( ! function_exists( 'WMCC_wordpress_multisite_content_copier' ) ) {
    function WMCC_wordpress_multisite_content_copier() {
        
        global $wpdb;
        
        $current_blog_id = get_current_blog_id();
        $page_url = network_admin_url( '/admin.php?page=wordpress-multisite-content-copier' );        
        $wmcc_content_type = ( isset( $_REQUEST['wmcc_content_type'] ) ? sanitize_text_field( $_REQUEST['wmcc_content_type'] ) : '' );
        $wmcc_source_blog = ( isset( $_REQUEST['wmcc_source_blog'] ) ? (int) $_REQUEST['wmcc_source_blog'] : 0 );
        $wmcc_record_per_page = ( isset( $_REQUEST['wmcc_record_per_page'] ) ? (int) $_REQUEST['wmcc_record_per_page'] : 10 );        
        $wmcc_records = ( isset( $_REQUEST['wmcc_records'] ) ? $_REQUEST['wmcc_records'] : array() );
        $wmcc_destination_blogs = ( isset( $_REQUEST['wmcc_destination_blogs'] ) ? $_REQUEST['wmcc_destination_blogs'] : array() );
        $copy_media = ( isset( $_REQUEST['copy_media'] ) ? (int) $_REQUEST['copy_media'] : 0 );
        $copy_terms = ( isset( $_REQUEST['copy_terms'] ) ? (int) $_REQUEST['copy_terms'] : 0 );
        if ( $wmcc_content_type && $wmcc_source_blog && $wmcc_destination_blogs != null && $wmcc_records != null && isset( $_REQUEST['wmcc_submit'] ) ) {
            $blogs = $wmcc_destination_blogs;
            $source_blog_id = (int) $wmcc_source_blog;            
            $type = 'post_type';
            $type_name = $wmcc_content_type;
            
            foreach ( $wmcc_records as $wmcc_record ) {
                $source_item_id = (int) $wmcc_record;
                if ( $type == 'post_type' ) {
                    if ( $blogs != null ) {
                        $taxonomies = array();
                        if ( $type_name == 'post' && $copy_terms ) {
                            if ( $source_blog_id != $current_blog_id ) {                
                                switch_to_blog( $source_blog_id );
                            }
                            
                            $category = wp_get_post_terms( $source_item_id, 'category' );
                            $post_tag = wp_get_post_terms( $source_item_id, 'post_tag' );
                            $post_format = wp_get_post_terms( $source_item_id, 'post_format' );

                            if ( $category ) {
                                $taxonomies['category'] = $category;
                            }

                            if ( $post_tag ) {
                                $taxonomies['post_tag'] = $post_tag;
                            }

                            if ( $post_format ) {
                                $taxonomies['post_format'] = $post_format;
                            }
                            
                            if ( $source_blog_id != $current_blog_id ) {                
                                restore_current_blog();
                            }
                        }

                        foreach ( $blogs as $blog ) {
                            $destination_blog_id = (int) $blog;
                            $destination_post_id = wmcc_copy_post( $source_item_id, $source_blog_id, $type, $type_name, $destination_blog_id, $copy_media );

                            if ( $taxonomies != null && $destination_post_id ) {
                                foreach ( $taxonomies as $taxonomy => $terms ) {
                                    if ( $terms != null ) {
                                        $destination_terms = array();
                                        foreach ( $terms as $term ) {
                                            $destination_term_id = wmcc_copy_term( $term, $source_blog_id, 'taxonomy', $taxonomy, $destination_blog_id );
                                            if ( $destination_term_id ) {
                                                $destination_terms[] = (int) $destination_term_id;
                                            }
                                        }

                                        if ( $destination_terms != null ) {
                                            wmcc_set_destination_post_terms( $destination_post_id, $destination_terms, $taxonomy, $destination_blog_id );
                                        }
                                    }
                                }
                            }
                        }
                    }                       
                }
            }
            
            ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e( 'Records successfully copy/update.' ); ?></p>
                </div>
            <?php
        }
        ?>
        <div class="wrap">
            <h2><?php _e( 'Bulk Copy/Update' ); ?></h2>
            <hr>
            <form method="post" action="<?php echo $page_url; ?>">                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php _e( 'Content Type' ); ?></th>
                            <td>                     
                                <select name="wmcc_content_type" required="required">
                                <?php
                                    $post_types = array(
                                        ''      => __( 'Select content type' ),
                                        'page'  => __( 'Pages' ),
                                        'post'  => __( 'Posts' ),
                                    );
                                    if ( $post_types != null ) {
                                        foreach ( $post_types as $key => $value ) {
                                            $selected = '';
                                            if ( $wmcc_content_type == $key ) {
                                                $selected = ' selected="$selected"';
                                            }
                                            ?>
                                                <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>                                                
                                            <?php
                                        }
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Source Site' ); ?></th>
                            <td>     
                                <select name="wmcc_source_blog" required="required">
                                <?php
                                    $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );
                                    $blog_list = array();
                                    if ( $sites != null ) {
                                        ?><option value=""><?php _e( 'Select source site' ); ?></option><?php
                                        foreach ( $sites as $key => $value ) {
                                            $blog_list[$value->blog_id] = $value->domain;
                                            $selected = '';
                                            if ( $wmcc_source_blog == $value->blog_id ) {
                                                $selected = ' selected="$selected"';
                                            }
                                            $blog_details = get_blog_details( $value->blog_id );
                                            ?>
                                                <option value="<?php echo $value->blog_id; ?>"<?php echo $selected; ?>><?php echo $value->domain; echo $value->path; echo ' ('.$blog_details->blogname.')'; ?></option>                                                
                                            <?php
                                        }
                                    }
                                ?> 
                                </select>
                            </td>
                        </tr>    
                        <tr>
                            <th scope="row"><?php _e( 'Number of records per page' ); ?></th>
                            <td>
                                <input type="number" name="wmcc_record_per_page" min="1" value="<?php echo $wmcc_record_per_page; ?>" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input name="submit" class="button button-secondary" value="<?php _e( 'Filter' ); ?>" type="submit">
                    &nbsp;&nbsp;&nbsp;&nbsp;<a class="button button-secondary" href="<?php echo $page_url; ?>"><?php _e( 'Clear' ); ?></a>
                </p>
            </form>
            <?php
                if ( $wmcc_content_type && $wmcc_source_blog ) {
                    if ( $wmcc_source_blog != get_current_blog_id() ) {
                        $wmcc_source_blog = (int) $wmcc_source_blog;
                        switch_to_blog( $wmcc_source_blog );
                    }
                    
                    $paged = ( isset( $_REQUEST['paged'] ) ) ? (int) $_REQUEST['paged'] : 1;
                    $args = array(
                        'post_type'         => $wmcc_content_type,
                        'posts_per_page'    => $wmcc_record_per_page,
                        'paged'             => $paged,
                    );
                    
                    $add_args = array(
                        'wmcc_content_type'     => $wmcc_content_type,
                        'wmcc_source_blog'      => $wmcc_source_blog,
                        'wmcc_record_per_page'  => $wmcc_record_per_page,
                    );
                    
                    if ( isset( $_REQUEST['s'] ) ) {
                        $args['s'] = sanitize_text_field( $_REQUEST['s'] );
                        $add_args['s'] = sanitize_text_field( $_REQUEST['s'] );
                    }
                    
                    $records = new WP_Query( $args );
                    ?>
                    <form method="post">
                        <p class="search-box wmcc-search-box">
                            <label class="screen-reader-text" for="post-search-input"><?php _e( 'Search Records:' ); ?></label>
                            <input id="post-search-input" name="s" value="<?php echo ( isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : ''  ); ?>" type="search">
                            <input id="search-submit" class="button" value="<?php _e( 'Search Records' ); ?>" type="submit">
                        </p>                       
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <td class="manage-column column-cb check-column"><input type="checkbox"></td>
                                    <th><?php _e( 'Title' ); ?></th>                                  
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td class="manage-column column-cb check-column"><input type="checkbox"></td>
                                    <th><?php _e( 'Title' ); ?></th>                                   
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            if ( $records->have_posts() ) {
                                while ( $records->have_posts() ) {
                                    $records->the_post();
                                    ?>
                                    <tr>
                                        <th class="check-column"><input type="checkbox" name="wmcc_records[]" value="<?php echo get_the_ID(); ?>"></th>
                                        <td class="title column-title page-title">
                                            <strong><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></strong>
                                            <?php
                                                $type = 'post_type';
                                                $type_name = get_post_type();
                                                $current_blog_id = get_current_blog_id();
                                                $current_item_id = get_the_ID();
                                                $current_relationship = $wpdb->get_row( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE type='$type' AND type_name='$type_name' AND ((source_item_id='$current_item_id' AND source_blog_id='$current_blog_id') || (destination_item_id='$current_item_id' AND destination_blog_id='$current_blog_id'))" );
                                                $blog_relationships = array();
                                                if ( $current_relationship != null ) {
                                                    $relationship_id = $current_relationship->relationship_id;
                                                    $relationships = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE relationship_id='$relationship_id'");
                                                    if ( $relationships != null ) {
                                                        foreach ( $relationships as $relationship ) {
                                                            if ( $current_blog_id == $relationship->source_blog_id && $current_item_id == $relationship->source_item_id ) {
                                                                $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                                                            } else if ( $current_blog_id == $relationship->destination_blog_id && $current_item_id == $relationship->destination_item_id ) {
                                                                $blog_relationships[$relationship->source_blog_id] = $relationship->source_item_id;
                                                            } else {
                                                                if ( ! isset( $blog_relationships[$relationship->source_blog_id] ) ) {
                                                                    $blog_relationships[$relationship->source_blog_id] = $relationship->source_item_id;
                                                                }

                                                                if ( ! isset( $blog_relationships[$relationship->destination_blog_id] ) ) {
                                                                    $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                                                                }
                                                            }
                                                        }
                                                    }                            
                                                }
                                                if ( $blog_relationships != null ) {
                                                    echo '<b>'; _e( 'Synced: ' ); echo '</b>';
                                                    $count_blog_list = count( $blog_relationships );
                                                    $count_blog = 0;
                                                    foreach ( $blog_relationships as $key => $value ) {
                                                        $blog_details = get_blog_details( $key );
                                                        echo $blog_list[$key]; echo $blog_details->path; echo ' ('.$blog_details->blogname.')';
                                                        if ( $count_blog != ( $count_blog_list - 1) ) {
                                                            echo ', ';
                                                        }
                                                        $count_blog ++;
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                    <tr class="no-items">                                       
                                        <td class="colspanchange" colspan="2"><?php _e( 'No records found.' ); ?></td>
                                    </tr>
                                <?php
                            }
                            $big = 999999999;                            
                            ?>
                            </tbody>
                        </table>
                        <div class="wmcc-pagination">
                            <span class="pagination-links">
                                <?php
                                $paginate_url = network_admin_url( '/admin.php?page=wordpress-multisite-content-copier&paged=%#%' );
                                echo paginate_links( array(
                                    'base'      => str_replace( $big, '%#%', $paginate_url ),
                                    'format'    => '?paged=%#%',
                                    'current'   => max( 1, $paged ),
                                    'total'     => $records->max_num_pages,
                                    'add_args'  => $add_args,    
                                    'prev_text' => __( '&laquo;' ),
                                    'next_text' => __( '&raquo;' ),
                                ) );
                                ?>
                            </span>
                        </div>
                        <br class="clear">
                        <input type="hidden" name="wmcc_content_type" value="<?php echo $wmcc_content_type; ?>">
                        <input type="hidden" name="wmcc_source_blog" value="<?php echo $wmcc_source_blog; ?>">
                        <input type="hidden" name="wmcc_record_per_page" value="<?php echo $wmcc_record_per_page; ?>">
                        <?php wp_reset_postdata(); ?>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php _e( 'Destination Sites' ); ?></th>
                                    <td>
                                        <fieldset>                                            
                                            <?php                                                                                       
                                                if ( $sites != null ) {
                                                    foreach ( $sites as $key => $value ) { 
                                                        if ( $wmcc_source_blog != $value->blog_id ) {
                                                            $blog_details = get_blog_details( $value->blog_id );
                                                            ?>
                                                                <label><input name="wmcc_destination_blogs[]" type="checkbox" value="<?php echo $value->blog_id; ?>"><?php echo $value->domain; echo $value->path; echo ' ('.$blog_details->blogname.')'; ?></label><br>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>                                                                          				
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e( 'Extra Options' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <label><input value="1" type="checkbox" name="copy_media"> <?php _e( 'Copy or update media ( Attachments )' ); ?></label><br>
                                            <label><input value="1" type="checkbox" name="copy_terms"> <?php _e( 'Copy or update terms ( Categories & Tags )' ); ?></label>
                                        </fieldset>
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <p class="submit"><input name="wmcc_submit" class="button button-primary" value="<?php _e( 'Copy/Update' ); ?>" type="submit"></p>
                    </form>
                    <?php                    
                    if ( $wmcc_source_blog != get_current_blog_id() ) {
                        restore_current_blog();
                    }
                }
            ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'WMCC_wordpress_multisite_content_copier_settings' ) ) {
    function WMCC_wordpress_multisite_content_copier_settings() {
        
        global $wpdb;
        
        if ( isset( $_POST['submit'] ) ) {
            if ( isset( $_POST['wmcc_post_types'] ) ) {
                if ( is_array( $_POST['wmcc_post_types'] ) && $_POST['wmcc_post_types'] != null ) {
                    foreach ( $_POST['wmcc_post_types'] as $key => $value ) {
                        $_POST['wmcc_post_types'][$key] = sanitize_text_field( $value );
                    }

                    update_site_option( 'wmcc_post_types', $_POST['wmcc_post_types'] );
                } else {
                    update_site_option( 'wmcc_post_types', (int) $_POST['wmcc_post_types'] );
                }
            }

            if ( isset( $_POST['wmcc_user_roles'] ) ) {
                if ( is_array( $_POST['wmcc_user_roles'] ) && $_POST['wmcc_user_roles'] != null ) {
                    foreach ( $_POST['wmcc_user_roles'] as $key => $value ) {
                        $_POST['wmcc_user_roles'][$key] = sanitize_text_field( $value );
                    }

                    update_site_option( 'wmcc_user_roles', $_POST['wmcc_user_roles'] );
                } else {
                    update_site_option( 'wmcc_user_roles', (int) $_POST['wmcc_user_roles'] );
                }
            }

            if ( isset( $_POST['wmcc_blogs'] ) ) {
                if ( is_array( $_POST['wmcc_blogs'] ) && $_POST['wmcc_blogs'] != null ) {
                    foreach ( $_POST['wmcc_blogs'] as $key => $value ) {
                        $_POST['wmcc_blogs'][$key] = (int) $value;
                    }

                    update_site_option( 'wmcc_blogs', $_POST['wmcc_blogs'] );
                } else {
                    update_site_option( 'wmcc_blogs', (int) $_POST['wmcc_blogs'] );
                }
            }

            ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e( 'Settings saved.' ); ?></p>
                </div>
            <?php
        }
        
        $wmcc_user_roles = get_site_option( 'wmcc_user_roles' );
        $wmcc_post_types = get_site_option( 'wmcc_post_types' );
        $wmcc_blogs = get_site_option( 'wmcc_blogs' );        
        ?>
        <div class="wrap">      
            <h2><?php _e( 'Settings' ); ?></h2>
            <hr>
            <form method="post">                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php _e( 'Post Types' ); ?></th>
                            <td>
                                <fieldset>
                                    <?php
                                        $checked = '';
                                        if ( ! $wmcc_post_types ) {
                                            $checked = ' checked="checked"';
                                        }
                                    ?>
                                    <label><input name="wmcc_post_types" type="checkbox" value="0"<?php echo $checked; ?>><?php _e( 'None' ); ?></label><br>
                                    <?php
                                        $post_types = array(
                                            'page'  => __( 'Pages' ),
                                            'post'  => __( 'Posts' ),
                                        );
                                        if ( $post_types != null ) {
                                            foreach ( $post_types as $key => $value ) {
                                                $checked = '';
                                                if ( $wmcc_post_types && in_array( $key, $wmcc_post_types ) ) {
                                                    $checked = ' checked="checked"';
                                                }
                                                ?>
                                                    <label><input name="wmcc_post_types[]" type="checkbox" value="<?php echo $key; ?>"<?php echo $checked; ?>><?php echo $value; ?></label><br>
                                                <?php
                                            }
                                        }
                                    ?>                                                                          				
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'User Roles' ); ?></th>
                            <td>
                                <fieldset>
                                    <?php
                                        $checked = '';
                                        if ( ! $wmcc_user_roles ) {
                                            $checked = ' checked="checked"';
                                        }
                                    ?>
                                    <label><input name="wmcc_user_roles" type="checkbox" value="0"<?php echo $checked; ?>><?php _e( 'None' ); ?></label><br>
                                    <?php
                                        $roles = get_editable_roles();
                                        if ( $roles != null ) {
                                            foreach ( $roles as $key => $value ) {
                                                if ( $key != 'subscriber' ) {
                                                    $checked = '';
                                                    if ( $wmcc_user_roles && in_array( $key, $wmcc_user_roles ) ) {
                                                        $checked = ' checked="checked"';
                                                    }
                                                    ?>
                                                        <label><input name="wmcc_user_roles[]" type="checkbox" value="<?php echo $key; ?>"<?php echo $checked; ?>><?php echo $value['name']; ?></label><br>
                                                    <?php
                                                }
                                            }
                                        }
                                    ?>                                                                          				
                                </fieldset>
                            </td>
                        </tr>	
                        <tr>
                            <th scope="row"><?php _e( 'Sites' ); ?></th>
                            <td>
                                <fieldset>
                                    <?php
                                        $checked = '';
                                        if ( ! $wmcc_blogs ) {
                                            $checked = ' checked="checked"';
                                        }
                                    ?>
                                    <label><input name="wmcc_blogs" type="checkbox" value="0"<?php echo $checked; ?>><?php _e( 'None' ); ?></label><br>
                                    <?php
                                        $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );                                       
                                        if ( $sites != null ) {
                                            foreach ( $sites as $key => $value ) {
                                                $checked = '';
                                                if ( $wmcc_blogs && in_array( $value->blog_id, $wmcc_blogs ) ) {
                                                    $checked = ' checked="checked"';
                                                }
                                                $blog_details = get_blog_details( $value->blog_id );
                                                ?>
                                                    <label><input name="wmcc_blogs[]" type="checkbox" value="<?php echo $value->blog_id; ?>"<?php echo $checked; ?>><?php echo $value->domain; echo $value->path; echo ' ('.$blog_details->blogname.')'; ?></label><br>
                                                <?php
                                            }
                                        }
                                    ?>                                                                          				
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type='submit' name="submit" class='button-primary' value="<?php _e( 'Save Changes' ); ?>" />
                            </th>
                        </tr>                        
                    </tbody>
                </table>
            </form>
            <script type="text/javascript">
                jQuery( document ).ready( function( $ ) {
                    $( 'input[type="checkbox"]' ).on( 'change', function() {
                        if ( $( this ).val() != 0 ) {
                            var fieldset = $( this ).closest( 'fieldset' );
                            $( 'input[type="checkbox"]', fieldset ).each( function() {
                                if ( $( this ).val() == 0 ) {
                                    $( this ).prop('checked', false);
                                }
                            });
                        } else {
                            var fieldset = $( this ).closest( 'fieldset' );
                            $( 'input[type="checkbox"]', fieldset ).each( function() {
                                if ( $( this ).val() != 0 ) {
                                    $( this ).prop('checked', false);
                                }
                            });
                        }                        
                    });
                });
            </script>
        </div>
        <?php
    }
}