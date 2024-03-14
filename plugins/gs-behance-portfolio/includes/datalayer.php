<?php
namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Handle database utils
 * 
 * @since 2.0.12
 */
class DataLayer {

    /**
     * Saves Behance user id if it's already not in the options.
     * 
     * @since  2.0.12
     * @return void
     */
    public function maybe_save_user_id( $userId, $offsetClock = 1 ) {
        $savedUserIds = (array) get_option( 'be_meta', [] );
        $savedUserIds[ $userId ] = $offsetClock;
        update_option( 'be_meta', $savedUserIds );
    }

    /**
     * Delete saved user ids.
     * 
     * @since 2.0.12
     * 
     * @param string $userId The behance user id.
     */
    public function delete_saved_user_ids( $userId = null ) {

        $savedUserIds = (array) get_option( 'be_meta', [] );
    
        if ( empty( $savedUserIds ) ) return;
        
        if ( empty( $userId ) ) delete_option( 'be_meta' );

        if ( array_key_exists( $userId, $savedUserIds ) ) {
            unset( $savedUserIds[ $userId ] );
            update_option( 'be_meta', $savedUserIds );
        }
    
    }

    /**
     * Get all the saved shots from DB by behance user ID and limit.
     * 
     * @since 2.0.12
     * 
     * @param string $userId Behance user id.
     * @param int    $count  Result limit.
     * 
     * @return string|array returns saved shots by the user id or the status if shots are empty.
     */
    public function get_shots( $userId, $count = '', $orderby = '', $order = '' ) {
        global $wpdb;
        $table = plugin()->db->get_data_table();

        $query = sprintf( "SELECT * FROM `%s` WHERE beusername='%s' ", $table, $userId );

        if ( ! empty( $orderby ) ) {
            $query .= sprintf( "ORDER BY `%s` ", $orderby );
        }

        if ( ! empty( $order ) ) {
            $query .= "ASC ";
        }

        if ( ! empty( $count ) ) {
            $query .= sprintf( "LIMIT %d ", $count );
        }

        $shots = $wpdb->get_results( $query, ARRAY_A );

        return empty( $shots ) ? [] : $shots;
    }

    /**
     * Get all the saved shots from db.
     * 
     * @since 2.0.12
     * 
     * @param int    $count Result limit.
     * @return string|array Returns saved shots by the user id or the status if shots are empty.
     */
    public function get_all( $count = '' ) {
        global $wpdb;
		$table = plugin()->db->get_data_table();

        if ( empty( $limit ) ) {
            $query = "SELECT * FROM {$table}";
        } else {
            $query = $wpdb->prepare(
                "SELECT * FROM {$table} LIMIT %d ",
                $count
            );
        }

        $shots = $wpdb->get_results( $query, ARRAY_A );

        return empty( $shots ) ? [] : $shots;
    }

    /**
     * Saves given data to the database.
     * 
     * @since 2.0.12
     * 
     * @param mixed $userId Behance user id.
     * @param array $shots  Associate user behance shots.
     * 
     * @return void
     */
    public function save( $userId, $shots ) {

        global $wpdb;
        $table_name = plugin()->db->get_data_table();

        if ( is_array( $shots ) && count( $shots ) > 0 ) {

            // Delete everything with this username before saving new data
            $wpdb->delete(
                $table_name,
                array( 'beusername' => $userId ),
                array( '%s' )
            );

            foreach ( $shots as $shot ) {

                if( empty( $shot['id'] ) ) return;

                $beid       = $shot['id'];
                $b_name     = $shot['name'] ?? '';
                $b_fields   = serialize( $shot['fields'] );
                $b_url      = $shot['url'];
                $blike      = $shot['stats']['appreciations'];
                $bview      = $shot['stats']['views'];
                $bcomment   = $shot['stats']['comments'];
                $created_on = $shot['created_on'];

                if ( isset( $shot[ 'covers' ][404] ) ) {
                    $thum_image = $shot[ 'covers' ][404];
                } else {
                    $thum_image = $shot[ 'covers' ][ 'max_808' ];
                }

                if ( isset( $shot[ 'covers' ][ 'original' ] ) ) {
                    $big_img = $shot[ 'covers' ][ 'original' ];
                }

                $data = array(
                    'beid'       => $beid,
                    'beusername' => $userId,
                    'name'       => $b_name,
                    'url'        => $b_url,
                    'bview'      => $bview,
                    'blike'      => $blike,
                    'bcomment'   => $bcomment,
                    'bfields'    => $b_fields,
                    'big_img'    => $big_img,
                    'thum_image' => $thum_image,
                    'time'       => date( 'Y-m-d H:i:s', $created_on ),
                );

                // Insert to the db.
                $wpdb->insert( $table_name, $data );
            }

        }
    }

    /**
     * Saves or Updates data based on the user id.
     * 
     * @since 2.0.12
     * 
     * @author apurba
     * 
     * @param  string $gs_beh_user The behance user id.
     * @return void
     */
    public function update_data( $gs_beh_user ) {

        $behance_url   = "https://www.behance.net/";
        $be_meta   	   = (array) get_option( 'be_meta', [] );
    
        if ( empty( $be_meta ) || ! array_key_exists( $gs_beh_user, $be_meta ) ) {
            return;
        }

        $gsbeh_baseurl   = $behance_url . $gs_beh_user;
        $collectedShots  = [];

        // Update offset
        $gsbeh_url = $gsbeh_baseurl . "/projects";

        
        // Scrape and filter projects
        $gs_behance_shots = plugin()->scrapper->scrape( $gsbeh_url );
        $gs_behance_shots = plugin()->scrapper->filter_shots( $gs_behance_shots );
        
        $collectedShots = array_merge( $collectedShots, $gs_behance_shots );
        
        // Save data
        if ( $collectedShots ) {
            plugin()->data->save( $gs_beh_user, $collectedShots );
        }
    
    }
}