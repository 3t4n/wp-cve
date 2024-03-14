<?php

class CRP_Ajax {

    public function __construct()
    {
        add_action( 'wp_ajax_crp_search_posts', array( $this, 'ajax_search_posts' ) );
        add_action( 'wp_ajax_crp_link_posts', array( $this, 'ajax_link_posts' ) );
        add_action( 'wp_ajax_crp_remove_relation', array( $this, 'ajax_remove_relation' ) );
    }

    public function url()
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
        $ajaxurl = admin_url( 'admin-ajax.php', $scheme );
        $ajaxurl .= '?crp_ajax=1';

        // WPML AJAX Localization Fix
        global $sitepress;
        if( isset( $sitepress) ) {
            $ajaxurl .= '&lang='.$sitepress->get_current_language();
        }

        return $ajaxurl;
    }

    public function ajax_search_posts()
    {
        if( check_ajax_referer( 'crp_admin', 'security', false ) )
        {
            $post_type = sanitize_key( $_POST['post_type'] );
            $term = sanitize_text_field( $_POST['term'] );
            $base_id = intval( $_POST['base'] );

            // Sanitize Post Type.
            $search_post_types = CustomRelatedPosts::setting( 'general_post_types' );

            if ( ! in_array( $post_type, $search_post_types ) ) {
                $post_type = $search_post_types;
            }

            $args = array(
                's' => $term,
                'post_type' => $post_type,
                'post_status' => CustomRelatedPosts::setting( 'search_post_status' ),
                'posts_per_page' => intval( CustomRelatedPosts::setting( 'search_number_of_posts' ) ),
                'orderby' => 'date',
                'order' => 'DESC',
            );

            $args = apply_filters( 'crp_search_args', $args );
            $query = new WP_Query( $args );

            $html = '';
            if( $query->have_posts() ) {
                $relations_to = CustomRelatedPosts::get()->relations_to( $base_id );
                $relations_from = CustomRelatedPosts::get()->relations_from( $base_id );

                $posts = $query->posts;

                foreach( $posts as $post ) {
                    $post_type = get_post_type_object( $post->post_type );

                    $html .= '<tr id="crp_post_' . $post->ID . '">';
                    $html .= '<td>' . $post_type->labels->singular_name . '</td>';
                    $html .= '<td>' . mysql2date( "j M 'y", $post->post_date ) . '</td>';
                    $html .= '<td id="crp_post_' . $post->ID . '_title">' . $post->post_title . '</td>';
                    $html .= '<td id="crp_post_' . $post->ID . '_actions">';

                    if( $post->ID == $base_id ) {
                        $html .= '<div class="button" disabled>' . __( 'Current post', 'custom-related-posts' ) . '</div>';
                    } elseif( array_key_exists( $post->ID, $relations_to ) && array_key_exists( $post->ID, $relations_from ) ) {
                        $html .= '<div class="button" disabled>' . __( 'Already linked', 'custom-related-posts' ) . '</div>';
                    } else {
                        $disabled = array_key_exists( $post->ID, $relations_to ) || array_key_exists( $post->ID, $relations_from ) ? ' disabled' : '';
                        $html .= '<button class="button" onclick="event.preventDefault(); CustomRelatedPosts.admin.Metabox.linkTo(' . $post->ID . ')"' . $disabled . '>' . __( 'To', 'custom-related-posts' ) . '</button>';
                        $html .= '<button class="button button-primary" onclick="event.preventDefault(); CustomRelatedPosts.admin.Metabox.linkBoth(' . $post->ID . ')">' . __( 'Both', 'custom-related-posts' ) . '</button>';
                        $html .= '<button class="button" onclick="event.preventDefault(); CustomRelatedPosts.admin.Metabox.linkFrom(' . $post->ID . ')"' . $disabled . '>' . __( 'From', 'custom-related-posts' ) . '</button>';
                    }

                    $html .= '</td>';
                    $html .= '</tr>';
                }
            }

            echo $html;
        }

        die();
    }

    public function ajax_link_posts()
    {
        if( check_ajax_referer( 'crp_admin', 'security', false ) )
        {
            $base_id = intval( $_POST['base'] );
            $target_id = intval( $_POST['target'] );

            $from = $_POST['from'] == 'true' ? true : false;
            $to = $_POST['to'] == 'true' ? true : false;

            CustomRelatedPosts::get()->helper( 'relations' )->add_relation( $base_id, $target_id, $from, $to );
        }

        die();
    }

    public function ajax_remove_relation()
    {
        if( check_ajax_referer( 'crp_admin', 'security', false ) )
        {
            $base_id = intval( $_POST['base'] );
            $target_id = intval( $_POST['target'] );

            $from = $_POST['from'] == 'true' ? true : false;
            $to = $_POST['to'] == 'true' ? true : false;

            CustomRelatedPosts::get()->helper( 'relations' )->remove_relation( $base_id, $target_id, $from, $to );
        }

        die();
    }
}