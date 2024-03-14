<?php

class ContactListAdminList
{
    function contact_custom_columns( $defaults )
    {
        $s = get_option( 'contact_list_settings' );
        $defaults['contact_id'] = 'ID';
        if ( !isset( $s['af_hide_first_name'] ) ) {
            $defaults['first_name'] = ( isset( $s['first_name_title'] ) && $s['first_name_title'] ? sanitize_text_field( $s['first_name_title'] ) : sanitize_text_field( __( 'First name', 'contact-list' ) ) );
        }
        $defaults['last_name'] = ( isset( $s['last_name_title'] ) && $s['last_name_title'] ? sanitize_text_field( $s['last_name_title'] ) : sanitize_text_field( __( 'Last name', 'contact-list' ) ) );
        $defaults['menu_order'] = sanitize_text_field( __( 'Order', 'contact-list' ) );
        if ( !isset( $s['af_hide_job_title'] ) ) {
            $defaults['job_title'] = ( isset( $s['job_title_title'] ) && $s['job_title_title'] ? sanitize_text_field( $s['job_title_title'] ) : sanitize_text_field( __( 'Job title', 'contact-list' ) ) );
        }
        if ( !isset( $s['af_hide_email'] ) ) {
            $defaults['email'] = sanitize_text_field( __( 'Email', 'contact-list' ) );
        }
        if ( !isset( $s['af_hide_phone'] ) ) {
            $defaults['phone'] = ( isset( $s['phone_title'] ) && $s['phone_title'] ? sanitize_text_field( $s['phone_title'] ) : sanitize_text_field( __( 'Phone', 'contact-list' ) ) );
        }
        if ( !isset( $s['af_hide_linkedin_url'] ) ) {
            $defaults['linkedin_url'] = ( isset( $s['linkedin_url_title'] ) && $s['linkedin_url_title'] ? sanitize_text_field( $s['linkedin_url_title'] ) : '<img src="' . esc_url_raw( plugins_url( '../img/linkedin.svg', __FILE__ ) ) . '" width="28" height="28" alt="" />' );
        }
        if ( !isset( $s['af_hide_twitter_url'] ) ) {
            $defaults['twitter_url'] = ( isset( $s['twitter_url_title'] ) && $s['twitter_url_title'] ? sanitize_text_field( $s['twitter_url_title'] ) : '<img src="' . esc_url_raw( plugins_url( '../img/x.svg', __FILE__ ) ) . '" width="28" height="28" alt="" />' );
        }
        if ( !isset( $s['af_hide_facebook_url'] ) ) {
            $defaults['facebook_url'] = ( isset( $s['facebook_url_title'] ) && $s['facebook_url_title'] ? sanitize_text_field( $s['facebook_url_title'] ) : '<img src="' . esc_url_raw( plugins_url( '../img/facebook.svg', __FILE__ ) ) . '" width="28" height="28" alt="" />' );
        }
        if ( !isset( $s['af_hide_instagram_url'] ) ) {
            $defaults['instagram_url'] = ( isset( $s['instagram_url_title'] ) && $s['instagram_url_title'] ? sanitize_text_field( $s['instagram_url_title'] ) : '<img src="' . esc_url_raw( plugins_url( '../img/instagram.svg', __FILE__ ) ) . '" width="28" height="28" alt="" />' );
        }
        return $defaults;
    }
    
    function contact_custom_columns_content( $column_name, $post_ID )
    {
        global  $post ;
        if ( $column_name == 'contact_id' ) {
            echo  esc_html( $post_ID ) ;
        }
        
        if ( $column_name == 'first_name' ) {
            $first_name = sanitize_text_field( get_post_meta( $post_ID, '_cl_first_name', true ) . '' );
            echo  esc_html( $first_name ) ;
        }
        
        
        if ( $column_name == 'last_name' ) {
            $last_name = sanitize_text_field( get_post_meta( $post_ID, '_cl_last_name', true ) );
            echo  esc_html( $last_name ) ;
            echo  '<span class="contact-list-shortcode-admin-list contact-list-shortcode-admin-list-contact contact-list-shortcode-' . esc_attr( $post_ID ) . '" title="[contact_list contact=' . esc_attr( $post_ID ) . ']">[contact_list contact=' . esc_attr( $post_ID ) . ']</span>' ;
            echo  '<button class="contact-list-copy contact-list-copy-admin-list" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-' . esc_attr( $post_ID ) . '">' . esc_html__( 'Copy', 'contact-list' ) . '</button>' ;
        }
        
        if ( $column_name == 'menu_order' ) {
            echo  esc_html( $post->menu_order ) ;
        }
        
        if ( $column_name == 'job_title' ) {
            $job_title = sanitize_text_field( get_post_meta( $post_ID, '_cl_job_title', true ) );
            echo  esc_html( $job_title ) ;
        }
        
        
        if ( $column_name == 'email' ) {
            $email = sanitize_email( get_post_meta( $post_ID, '_cl_email', true ) );
            
            if ( $email ) {
                echo  esc_html( $email ) ;
                $is_premium = 0;
                if ( !$is_premium ) {
                    echo  '<button class="contact-list-request-update contact-list-request-update-free contact-list-request-update-' . esc_attr( $post_ID ) . '" data-contact-id="' . esc_attr( $post_ID ) . '">' . esc_html__( 'Request update', 'contact-list' ) . '</button><div class="contact-list-request-update-info contact-list-request-update-info-' . esc_attr( $post_ID ) . '"></div>' ;
                }
            }
        
        }
        
        
        if ( $column_name == 'phone' ) {
            $phone = sanitize_text_field( get_post_meta( $post_ID, '_cl_phone', true ) );
            echo  esc_html( $phone ) ;
        }
        
        if ( $column_name == 'linkedin_url' ) {
            if ( get_post_meta( $post_ID, '_cl_linkedin_url', true ) ) {
                echo  '<div style="text-align: center;">x</div>' ;
            }
        }
        if ( $column_name == 'twitter_url' ) {
            if ( get_post_meta( $post_ID, '_cl_twitter_url', true ) ) {
                echo  '<div style="text-align: center;">x</div>' ;
            }
        }
        if ( $column_name == 'facebook_url' ) {
            if ( get_post_meta( $post_ID, '_cl_facebook_url', true ) ) {
                echo  '<div style="text-align: center;">x</div>' ;
            }
        }
        if ( $column_name == 'instagram_url' ) {
            if ( get_post_meta( $post_ID, '_cl_instagram_url', true ) ) {
                echo  '<div style="text-align: center;">x</div>' ;
            }
        }
    }
    
    function set_custom_contact_list_sortable_columns( $columns )
    {
        $columns['last_name'] = 'last_name';
        $columns['menu_order'] = 'menu_order';
        return $columns;
    }
    
    function contact_list_custom_orderby( $query )
    {
        if ( !is_admin() ) {
            return;
        }
        $orderby = $query->get( 'orderby' );
        $order = ( $query->get( 'order' ) == 'asc' ? 'ASC' : 'DESC' );
        
        if ( $orderby == 'last_name' ) {
            $query->set( 'meta_query', array(
                'relation'         => 'AND',
                'last_name_clause' => array(
                'key'     => '_cl_last_name',
                'compare' => 'EXISTS',
            ),
            ) );
            $query->set( 'orderby', array(
                'last_name_clause' => $order,
                'title'            => $order,
            ) );
        }
    
    }

}