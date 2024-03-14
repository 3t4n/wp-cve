<?php

////////////////////////////////////////////////////////////////////////////////

header( 'Content-Type: application/json;charset=utf-8' );
header( 'X-Robots-Tag: noindex,nofollow' );

////////////////////////////////////////////////////////////////////////////////

try
{
    ////////////////////////////////////////////////////////////////////////////

    // Force SHORT INIT
    define( 'SHORTINIT', true );

    // Require the wp-load.php file
    require_once( realpath( __DIR__ .'/../../../../' ) . '/wp-load.php' );

    // Include global $wpdb Class for use
    global $wpdb;

    ////////////////////////////////////////////////////////////////////////////

    if( !isset($_GET['post_id']) || empty($_GET['post_id']) )
    {
        throw new Exception();
    }

    if( function_exists('filter_var') )
    {
        $post_id = intval( filter_var( $_GET['post_id'], FILTER_SANITIZE_NUMBER_INT ) );
    }
    else
    {
        $post_id = intval( preg_replace( '#[^0-9]#', '', $_GET['post_id'] ) );
    }

    if( empty($post_id) )
    {
        throw new Exception();
    }

    ////////////////////////////////////////////////////////////////////////////

    // get_post_meta
    $current_hits =
        intval(
            $wpdb->get_var(
                $wpdb->prepare(
                    "
                    SELECT
                        meta_value
                    FROM
                        $wpdb->postmeta
                    WHERE
                        post_id = %d
                        AND
                        meta_key = 'hits'
                    LIMIT
                        1
                    ",
                    $post_id
                )
            )
        );

    ////////////////////////////////////////////////////////////////////////////

    // TODO: dont_count_admins=1

    if( empty($current_hits) )
    {
        $current_hits = 1;

        // insert new
        $wpdb->query(
            $wpdb->prepare(
                "
                INSERT INTO
                    $wpdb->postmeta
                    (
                        post_id,
                        meta_key,
                        meta_value
                    )
                VALUES
                    (
                        %d,
                        'hits',
                        %d
                    )
		        ",
                $post_id,
                $current_hits
            )
        );
    }
    else
    {
        $current_hits++;

        // update_post_meta
        $wpdb->query(
            $wpdb->prepare(
                "
                UPDATE
                    $wpdb->postmeta
                SET
                    meta_value = %d
                WHERE
                    post_id = %d 
                    AND 
                    meta_key = 'hits'
		        ",
                $current_hits,
                $post_id
            )
        );
    }

    ////////////////////////////////////////////////////////////////////////////

    die(
        json_encode(
            array(
                'post_id'   => $post_id,
                'hits'      => intval($current_hits),
            )
        )
    );

    ////////////////////////////////////////////////////////////////////////////
}
catch( Exception $e )
{
    ////////////////////////////////////////////////////////////////////////////

    die(
        json_encode(
            array(
                'post_id'   => 0,
                'hits'      => 0
            )
        )
    );

    ////////////////////////////////////////////////////////////////////////////
}

////////////////////////////////////////////////////////////////////////////////
