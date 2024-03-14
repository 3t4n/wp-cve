<?php

namespace XCurrency\App\Repositories;

use XCurrency\WpMVC\App;

class SwitcherRepository {
    public function get( string $type = 'all', string $post_status = 'publish, draft' ) {
        $args = [
            'post_type'   => x_currency_config()->get( 'app.switcher_post_type' ),
            'post_status' => $post_status,
            'numberposts' => -1,
            'order'       => 'ASC'
        ];

        if ( $type !== 'all' ) {
            $args['meta_query'] = [
                [
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '='
                ]
            ];
        }

        return get_posts( $args );
    }

    public function get_side_sticky() {
        $posts = get_posts(
            [
                'post_type'   => x_currency_config()->get( 'app.switcher_post_type' ),
                'post_status' => 'publish',
                'numberposts' => -1,
                'meta_query'  => [
                    [
                        'key'     => 'type',
                        'value'   => 'sticky',
                        'compare' => '='
                    ]
                ]
            ] 
        );
        return $this->switcher_list_data( $posts );
    }

    public function create( array $data ) {
        unset( $data['action'] );
        $args = [
            'post_title'  => sanitize_text_field( $data['name'] ),
            'post_type'   => x_currency_config()->get( 'app.switcher_post_type' ),
            'post_status' => $data['active'] == true ? 'publish' : 'draft'
        ];

        $post_id = wp_insert_post( $args );
        unset( $data['name'] );
        unset( $data['active'] );

        // save custom css
        if ( isset( $data['custom_css'] ) ) {
            add_post_meta( $post_id, 'custom_css', $data['custom_css'] );
            unset( $data['custom_css'] );
        }

        foreach ( $data as $key => $value ) {
            add_post_meta( $post_id, sanitize_key( $key ), sanitize_text_field( $value ) );
        }

        return $post_id;
    }

    public function update( array $data ) {
        $post_id = $data['id'];

        $args = [
            'ID'          => sanitize_key( $data['id'] ),
            'post_title'  => sanitize_text_field( $data['name'] ),
            'post_type'   => x_currency_config()->get( 'app.switcher_post_type' ),
            'post_status' => $data['active'] == true ? 'publish' : 'draft'
        ];

        wp_update_post( $args );

        unset( $data['id'] );
        unset( $data['name'] );
        unset( $data['active'] );

        if ( isset( $data['custom_css'] ) ) {
            update_post_meta( $post_id, 'custom_css', $data['custom_css'] );
            unset( $data['custom_css'] );
        }

        foreach ( $data as $key => $value ) {
            update_post_meta( $post_id, sanitize_key( $key ), sanitize_text_field( $value ) );
        }

        return $post_id;
    }

    /**
     * @param $posts
     * @return mixed
     */
    public function switcher_list_data( $posts ) {
        $post_type   = x_currency_config()->get( 'app.switcher_post_type' );
        $final_posts = [];
        foreach ( $posts as $value ) {
            $meta_values               = $this->post_meta( $value->ID );
            $meta_values['id']         = $value->ID;
            $meta_values['name']       = $value->post_title;
            $meta_values['short_code'] = "[" . $post_type . " id=" . $value->ID . "]";
            $meta_values['active']     = $value->post_status == 'publish' ? true : false;
            $final_posts[$value->ID]   = $meta_values;
        }
        return $final_posts;
    }

    /**
     * @param $post_id
     */
    private function post_meta( $post_id ) {
        $switcher_type = get_post_meta( $post_id, 'type', true );
        $customizer_id = get_post_meta( $post_id, 'customizer_id', true );
        $package       = get_post_meta( $post_id, 'package', true );
        $page          = get_post_meta( $post_id, 'page', true );

        if ( empty( $page ) ) {
            $page = 'all';
        }

        return [
            'type'          => $switcher_type,
            'page'          => $page,
            'template'      => get_post_meta( $post_id, 'template', true ),
            'custom_css'    => get_post_meta( $post_id, 'custom_css', true ),
            'customizer_id' => empty( $customizer_id )  ? $switcher_type . '-default' : $customizer_id,
            'package'       => empty( $package )  ? 'free' : $package,
        ];
    }

    public function organizer( $ids, $type ) {
        switch ( $type ) {
            case 'active':
                foreach ( $ids as $id ) {
                    wp_update_post( ['ID' => $id, 'post_status' => 'publish'] );
                }
                break;
            case 'deactive':
                foreach ( $ids as $id ) {
                    wp_update_post( ['ID' => $id, 'post_status' => 'draft'] );
                }
                break;
            case 'delete':
                foreach ( $ids as $id ) {
                    wp_delete_post( $id, true );
                }
                break;
        }
    }
}