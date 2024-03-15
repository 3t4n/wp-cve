<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SF_Resource
{
    /**
     * Add common request arguments to argument list before WP_Query is run
     *
     * @since 2.1
     * @param array $base_args required arguments for the query (e.g. `post_type`, etc)
     * @param array $request_args arguments provided in the request
     * @return array
     */
    protected function merge_query_args( $base_args, $request_args ) {

        $args = array();

        // date
        if ( ! empty( $request_args['created_at_min'] ) || ! empty( $request_args['created_at_max'] ) || ! empty( $request_args['updated_at_min'] ) || ! empty( $request_args['updated_at_max'] ) ) {

            $args['date_query'] = array();

            // resources created after specified date
            if ( ! empty( $request_args['created_at_min'] ) ) {
                $args['date_query'][] = array( 'column' => 'post_date_gmt', 'after' => $this->server->parse_datetime( $request_args['created_at_min'] ), 'inclusive' => true );
            }

            // resources created before specified date
            if ( ! empty( $request_args['created_at_max'] ) ) {
                $args['date_query'][] = array( 'column' => 'post_date_gmt', 'before' => $this->server->parse_datetime( $request_args['created_at_max'] ), 'inclusive' => true );
            }

            // resources updated after specified date
            if ( ! empty( $request_args['updated_at_min'] ) ) {
                $args['date_query'][] = array( 'column' => 'post_modified_gmt', 'after' => $this->server->parse_datetime( $request_args['updated_at_min'] ), 'inclusive' => true );
            }

            // resources updated before specified date
            if ( ! empty( $request_args['updated_at_max'] ) ) {
                $args['date_query'][] = array( 'column' => 'post_modified_gmt', 'before' => $this->server->parse_datetime( $request_args['updated_at_max'] ), 'inclusive' => true );
            }
        }

        // search
        if ( ! empty( $request_args['q'] ) ) {
            $args['s'] = $request_args['q'];
        }

        // resources per response
        if ( ! empty( $request_args['limit'] ) ) {
            $args['posts_per_page'] = $request_args['limit'];
        }

        // resource offset
        if ( ! empty( $request_args['offset'] ) ) {
            $args['offset'] = $request_args['offset'];
        }

        // order (ASC or DESC, ASC by default)
        if ( ! empty( $request_args['order'] ) ) {
            $args['order'] = $request_args['order'];
        }

        // orderby
        if ( ! empty( $request_args['orderby'] ) ) {
            $args['orderby'] = $request_args['orderby'];

            // allow sorting by meta value
            if ( ! empty( $request_args['orderby_meta_key'] ) ) {
                $args['meta_key'] = $request_args['orderby_meta_key'];
            }
        }

        // allow post status change
        if ( ! empty( $request_args['post_status'] ) ) {
            $args['post_status'] = $request_args['post_status'];
            unset( $request_args['post_status'] );
        }

        // resource page
        $args['paged'] = ( isset( $request_args['page'] ) ) ? absint( $request_args['page'] ) : 1;

        return array_merge( $base_args, $args );
    }
}