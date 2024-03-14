<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

if ( ! function_exists( 'wmcc_display_content_copier' ) ) {
    add_action( 'wp_ajax_display_content_copier', 'wmcc_display_content_copier' );
    function wmcc_display_content_copier() {
        
        global $wpdb;
        
        $current_blog_id = get_current_blog_id();
        $current_item_id = (int) $_POST['item_id'];
        $type = sanitize_text_field( $_POST['type'] );
        $type_name = sanitize_text_field( $_POST['type_name'] );
        
        $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );                                       
        if ( $sites != null ) {
            $wmcc_blogs = get_site_option( 'wmcc_blogs' );
            if ( ! $wmcc_blogs ) {
                $wmcc_blogs = array();
            } 
            
            if ( $wmcc_blogs != null ) {                
                ?>
                <p><strong><?php _e( 'Select destination sites you want to copy or update.' ); ?></strong></p>
                <div id="wmcc-message"></div>
                <div id="wmcc-sites">                    
                    <?php
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
                        
                        foreach ( $sites as $key => $value ) {
                            if ( in_array( $value->blog_id, $wmcc_blogs ) && $value->blog_id != get_current_blog_id() ) {
                                $checked = '';
                                $post_modified = '';
                                if ( isset( $blog_relationships[$value->blog_id] ) ) {
                                    $checked = ' checked="checked"';
                                    
                                    switch_to_blog( $value->blog_id );
                                        $blog_post = get_post( $blog_relationships[$value->blog_id] );
                                        if ( $blog_post != null ) {                                            
                                            $date_format = get_option('date_format');
                                            $time_format = get_option('time_format');                                            
                                            $post_modified = '( '.date( $date_format.' @ '.$time_format, strtotime( $blog_post->post_modified ) ).' )';                                           
                                        }
                                    restore_current_blog();
                                }
                                $blog_details = get_blog_details( $value->blog_id );                                
                                ?>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="checkbox" value="<?php echo $value->blog_id; ?>"<?php echo $checked; ?>><?php echo $value->domain; echo $value->path; echo ' ('.$blog_details->blogname.')'; ?> <strong><?php echo $post_modified; ?></strong></label></p>
                                <?php
                            }
                        }
                    ?>
                </div>
                <p><strong><?php _e( 'Extra Options' ); ?></strong></p>
                <?php
                    if ( $type == 'post_type' ) {
                        ?>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input id="is-copy-media" type="checkbox" value="1"><?php _e( ' Copy or update media ( Attachments )' ); ?></label></p>
                        <?php
                        if ( $type_name == 'post' ) {
                            ?>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input id="is-copy-terms" type="checkbox" value="1"><?php _e( ' Copy or update terms ( Categories & Tags )' ); ?></label></p>
                            <?php
                        }
                    }
                ?>                
                <button type="button" id="wmcc-copy" class="button-primary"><?php _e( 'Copy/Update' ); ?></button>            
                <?php      
            } else {
                ?>
                <div id="wmcc-notice">
                    <div class="wmcc-notice-warning">
                        <p><?php _e( 'Sites is not selected. Please go to Network Admin->WMCC and select sites.' ); ?></p>
                    </div>
                </div>
                <?php
            }           
        }
        
        wp_die();
    }
}

if ( ! function_exists( 'wmcc_send_content_copier' ) ) {
    add_action( 'wp_ajax_send_content_copier', 'wmcc_send_content_copier' );
    function wmcc_send_content_copier() {
        
        global $wpdb;
        
        $current_user = wp_get_current_user();
        
        $blogs = $_POST['sites'];
        $source_blog_id = get_current_blog_id();
        $source_item_id = (int) $_POST['item_id'];
        $type = sanitize_text_field( $_POST['type'] );
        $type_name = sanitize_text_field( $_POST['type_name'] );
        $copy_media = (int) $_POST['copy_media'];
        $copy_terms = (int) $_POST['copy_terms'];
        
        if ( $type == 'post_type' ) {
            if ( $blogs != null ) {                
                $taxonomies = array();
                if ( $type_name == 'post' && $copy_terms ) {
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
        
        wp_die();
    }
}

if ( ! function_exists( 'wmcc_copy_post' ) ) {
    function wmcc_copy_post( $source_item_id = 0, $source_blog_id = 0, $type = '', $type_name = '', $destination_blog_id = 0, $copy_media = 0 ) {
        
        if ( $source_item_id && $source_blog_id && $type && $type_name && $destination_blog_id ) {
            $current_blog_id = get_current_blog_id();
            if ( $source_blog_id != $current_blog_id ) {                
                switch_to_blog( $source_blog_id );
            }
            
            global $wpdb;
            $item = get_post( $source_item_id );
            if ( $item != null ) {
                $current_user = wp_get_current_user();
                $post_data = array(
                    'post_author'           => $current_user->ID,
                    'post_date'             => $item->post_date,
                    'post_author'           => $current_user->ID,
                    'post_content'          => $item->post_content,
                    'post_title'            => wp_strip_all_tags( $item->post_title ),
                    'post_excerpt'          => $item->post_excerpt,            
                    'post_status'           => $item->post_status,
                    'comment_status'        => $item->comment_status,
                    'ping_status'           => $item->ping_status,
                    'post_password'         => $item->post_password,
                    'to_ping'               => $item->to_ping,
                    'pinged'                => $item->pinged,
                    'post_content_filtered' => $item->post_content_filtered,                    
                    'menu_order'            => $item->menu_order,
                    'post_type'             => $item->post_type,
                    'post_mime_type'        => $item->post_mime_type,                    
                );
                
                $postmeta_fields = array();
                $edit_lock = get_post_meta( $source_item_id, '_edit_lock', true );
                if ( $edit_lock ) {
                    $postmeta_fields['_edit_lock'] = $edit_lock;
                }
                
                $edit_last = get_post_meta( $source_item_id, '_edit_last', true );
                if ( $edit_last ) {
                    $postmeta_fields['_edit_last'] = $edit_last;
                }
                
                $thumbnail_id = get_post_meta( $source_item_id, '_thumbnail_id', true );
                if ( $thumbnail_id && $copy_media ) {
                    $thumbnail = get_post( $thumbnail_id );
                    $thumbnail_path = get_attached_file( $thumbnail_id );                                
                    if ( $thumbnail_path != null ) {
                        $postmeta_fields['_thumbnail_id'] = array(
                            'post_title'                => wp_strip_all_tags( $thumbnail->post_title ),
                            'post_content'              => $thumbnail->post_content,                            
                            'post_excerpt'              => $thumbnail->post_excerpt,
                            'wp_attachment_image_alt'   => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
                            'post_name'                 => $thumbnail->post_name, 
                            'path'                      => $thumbnail_path,
                        );
                    }                    
                }
                
                if ( $item->post_type == 'page' ) {
                    $wp_page_template = get_post_meta( $source_item_id, '_wp_page_template', true );
                    if ( $wp_page_template ) {
                        $postmeta_fields['_wp_page_template'] = $wp_page_template;
                    }
                }
                
                $blog_relationships = array();
                $current_relationship = $wpdb->get_row( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE type='$type' AND type_name='$type_name' AND ((source_item_id='$source_item_id' AND source_blog_id='$source_blog_id') || (destination_item_id='$source_item_id' AND destination_blog_id='$source_blog_id'))" );  
                if ( $current_relationship != null ) {
                    $relationship_id = $current_relationship->relationship_id;
                    $relationships = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE relationship_id='$relationship_id'");
                    if ( $relationships != null ) {
                        foreach ( $relationships as $relationship ) {
                            if ( $source_blog_id == $relationship->source_blog_id && $source_item_id == $relationship->source_item_id ) {
                                $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                            } else if ( $source_blog_id == $relationship->destination_blog_id && $source_item_id == $relationship->destination_item_id ) {
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
                
                if ( $blog_relationships == null ) {
                    $relationship_id = uniqid();
                }
                
                $post_data['post_parent'] = 0;
                if ( $item->post_parent ) {
                    $item_parent_id = wmcc_copy_post( $item->post_parent, $source_blog_id, $type, $type_name, $destination_blog_id );
                    if ( $item_parent_id ) {
                        $post_data['post_parent'] = $item_parent_id;
                    }
                }
                
                if ( $source_blog_id != $current_blog_id ) {                
                    restore_current_blog();
                }
                
                switch_to_blog( $destination_blog_id );
                    if ( isset( $blog_relationships[$destination_blog_id] ) ) {
                        $destination_item_id = $blog_relationships[$destination_blog_id];
                        $post_data['ID'] = $destination_item_id;
                        wp_update_post( $post_data );
                    } else {
                        $destination_item_id = wp_insert_post( $post_data ); 
                        if( $destination_item_id ) {
                            $wpdb->insert(
                                $wpdb->base_prefix . 'wmcc_relationships',
                                array( 
                                    'source_item_id'        => $source_item_id,
                                    'source_blog_id'        => $source_blog_id,
                                    'destination_item_id'   => $destination_item_id,
                                    'destination_blog_id'   => $destination_blog_id,
                                    'relationship_id'       => $relationship_id,
                                    'type'                  => $type,
                                    'type_name'             => $type_name,
                                )
                            );                       
                        }
                    }
                    
                    if ( $destination_item_id && $postmeta_fields ) {
                        if ( isset( $postmeta_fields['_edit_lock'] ) ) {
                            update_post_meta( $destination_item_id, '_edit_lock', $postmeta_fields['_edit_lock'] );
                        } else {
                            delete_post_meta( $destination_item_id, '_edit_lock' );
                        }
                        
                        if ( isset( $postmeta_fields['_edit_last'] ) ) {
                            update_post_meta( $destination_item_id, '_edit_last', $postmeta_fields['_edit_last'] );
                        } else {
                            delete_post_meta( $destination_item_id, '_edit_last' );
                        }
                        
                        if ( isset( $postmeta_fields['_wp_page_template'] ) ) {
                            update_post_meta( $destination_item_id, '_wp_page_template', $postmeta_fields['_wp_page_template'] );
                        } else {
                            delete_post_meta( $destination_item_id, '_wp_page_template' );
                        }
                        
                        if ( $copy_media ) {
                            if ( isset( $postmeta_fields['_thumbnail_id'] ) ) {
                                $postmeta_field_value = $postmeta_fields['_thumbnail_id'];
                                if ( isset( $postmeta_field_value['path'] ) && $postmeta_field_value != null ) { 
                                    $check_attachment_args = array(
                                        'name'              => $postmeta_field_value['post_name'],
                                        'post_type'         => 'attachment',
                                        'post_status'       => 'inherit',
                                        'posts_per_page'    => 1,
                                    );
                                    $check_attachment = get_posts( $check_attachment_args );
                                    if ( $check_attachment ) {       
                                        $attach_id = $check_attachment[0]->ID;
                                        update_post_meta( $destination_item_id, '_thumbnail_id', $attach_id );
                                        $update_attachment = array(
                                            'ID'            => $attach_id,
                                            'post_title'    => $postmeta_field_value['post_title'],
                                            'post_content'  => $postmeta_field_value['post_content'],
                                            'post_excerpt'  => $postmeta_field_value['post_excerpt'],
                                        );                                            
                                        wp_update_post( $update_attachment );
                                        update_post_meta( $attach_id, '_wp_attachment_image_alt', $postmeta_field_value['wp_attachment_image_alt'] );
                                    } else {                                        
                                        $file = $postmeta_field_value['path'];
                                        $upload_file = wp_upload_bits( basename( $file ), null, file_get_contents( $file ) );
                                        if ( ! $upload_file['error'] ) {
                                            $filetype = wp_check_filetype( basename( $file ), null );
                                            $attachment = array(         
                                                'post_mime_type' => $filetype['type'],
                                                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),                                            
                                                'post_status'    => 'inherit'
                                            );
                                            $attach_id = wp_insert_attachment( $attachment, $upload_file['file'] );
                                            if ( $attach_id ) {
                                                update_post_meta( $destination_item_id, '_thumbnail_id', $attach_id );
                                                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                                                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                                                $attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );
                                                wp_update_attachment_metadata( $attach_id, $attach_data ); 

                                                $update_attachment = array(
                                                    'ID'            => $attach_id,
                                                    'post_title'    => $postmeta_field_value['post_title'],
                                                    'post_content'  => $postmeta_field_value['post_content'],
                                                    'post_excerpt'  => $postmeta_field_value['post_excerpt'],
                                                );                                            
                                                wp_update_post( $update_attachment );
                                                update_post_meta( $attach_id, '_wp_attachment_image_alt', $postmeta_field_value['wp_attachment_image_alt'] );
                                            } 
                                        } 
                                    }
                                }
                            } else {
                                delete_post_meta( $destination_item_id, '_thumbnail_id' );
                            }
                        }
                    }
                restore_current_blog();
                
                return $destination_item_id;
            }
        }
    }
}

if ( ! function_exists( 'wmcc_copy_term' ) ) {
    function wmcc_copy_term( $term = null, $source_blog_id = 0, $type = '', $type_name = '', $destination_blog_id = 0 ) {
        
        if ( $term != null && $source_blog_id && $type && $type_name && $destination_blog_id ) {
            $current_blog_id = get_current_blog_id();
            if ( $source_blog_id != $current_blog_id ) {                
                switch_to_blog( $source_blog_id );
            }
            
            global $wpdb;
            $source_item_id = $term->term_id;
            $blog_relationships = array();
            $current_relationship = $wpdb->get_row( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE type='$type' AND type_name='$type_name' AND ((source_item_id='$source_item_id' AND source_blog_id='$source_blog_id') || (destination_item_id='$source_item_id' AND destination_blog_id='$source_blog_id'))" );  
            if ( $current_relationship != null ) {
                $relationship_id = $current_relationship->relationship_id;
                $relationships = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE relationship_id='$relationship_id'");
                if ( $relationships != null ) {
                    foreach ( $relationships as $relationship ) {
                        if ( $source_blog_id == $relationship->source_blog_id && $source_item_id == $relationship->source_item_id ) {
                            $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                        } else if ( $source_blog_id == $relationship->destination_blog_id && $source_item_id == $relationship->destination_item_id ) {
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

            if ( $blog_relationships == null ) {
                $relationship_id = uniqid();
            }
            
            $item_parent_id = 0;
            if ( $term->parent ) {
                $term_parent = get_term( $term->parent, $term->taxonomy );
                if ( $term_parent != null ) {
                    $item_parent_id = wmcc_copy_term( $term_parent, $source_blog_id, $type, $type_name, $destination_blog_id );
                }
            }
            
            if ( $source_blog_id != $current_blog_id ) {                
                restore_current_blog();
            }
            
            switch_to_blog( $destination_blog_id );
                if ( isset( $blog_relationships[$destination_blog_id] ) ) {
                    $destination_item_id = $blog_relationships[$destination_blog_id];    
                    wp_update_term(
                        $destination_item_id,                            
                        $term->taxonomy,
                        array(
                            'name'          => $term->name,
                            'description'   => $term->description,
                            'parent'        => $item_parent_id,
                        )
                    ); 
                } else {
                    $insert_term = wp_insert_term(
                        $term->name,
                        $term->taxonomy,
                        array(
                            'description'   => $term->description,
                            'parent'        => $item_parent_id,
                        )
                    );
                    
                    if ( is_wp_error( $insert_term ) ) {  
                        if ( isset( $insert_term->error_data ) ) {
                            $error_data = $insert_term->error_data;
                            if ( isset( $error_data['term_exists'] ) ) {
                                $destination_item_id = $error_data['term_exists'];                               
                            }
                        }
                    } else {
                        if ( isset( $insert_term['term_id'] ) ) {
                            $destination_item_id = $insert_term['term_id'];
                        }
                    }
                    
                    if( $destination_item_id ) {
                        $wpdb->insert(
                            $wpdb->base_prefix . 'wmcc_relationships',
                            array( 
                                'source_item_id'        => $source_item_id,
                                'source_blog_id'        => $source_blog_id,
                                'destination_item_id'   => $destination_item_id,
                                'destination_blog_id'   => $destination_blog_id,
                                'relationship_id'       => $relationship_id,
                                'type'                  => $type,
                                'type_name'             => $type_name,
                            )
                        );                       
                    }                               
                }
            restore_current_blog();
           
            return $destination_item_id;
        }
    }
}

if ( ! function_exists( 'wmcc_set_destination_post_terms' ) ) {
    function wmcc_set_destination_post_terms( $destination_post_id = 0, $destination_terms = array(), $taxonomy = '', $destination_blog_id = 0 ) {
        
        if ( $destination_post_id && $destination_terms != null && $taxonomy && $destination_blog_id ) {
            switch_to_blog( $destination_blog_id );
                wp_set_post_terms( $destination_post_id, $destination_terms, $taxonomy ); 
            restore_current_blog();
        }
    }
}