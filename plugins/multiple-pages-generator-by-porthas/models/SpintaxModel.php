<?php

class MPG_SpintaxModel
{

    public static function mpg_generate_spintax_string($spintax_string)
    {
        try {
            // Spintax string.
            $spintax_text = preg_replace_callback(
                '/\{(((?>[^\{\}]+)|(?R))*?)\}/x',
                function( $text ) {
                    $text  = self::mpg_generate_spintax_string( $text[1] );
                    $parts = explode( '|', $text );
                    $parts = array_map(
                        function( $p ) {
                            if ( false === strpos( $p, 'mpg_' ) ) {
                                return $p;
                            }
                            return false === strpos( $p, '{{mpg_' ) ? "{{$p}}" : $p;
                        },
                        $parts
                    );
                    $parts = array_filter( $parts );
                    return $parts[ array_rand( $parts ) ];
                },
                $spintax_string
            );
            $spintax_text = str_replace( '\\\\', '\\', stripslashes( addslashes( $spintax_text ) ) );
            $spintax_text = str_replace( array( "\\'", '\\"' ), array( "'", '"' ), $spintax_text );
            return $spintax_text;
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            throw new Exception($e->getMessage());
        }
    }

    public static function flush_cache_by_project_id($project_id){

        try{

            global $wpdb;

            if (!$project_id) {
                throw new Exception(__('Project ID is missing', 'mpg'));
            }

            $table_name = $wpdb->prefix . MPG_Constant::MPG_SPINTAX_TABLE;
            
            $wpdb->delete($table_name, ['project_id' => $project_id]);

            return true;

        }catch(Exception $e){
            return new Exception($e->getMessage());
        }
    }
}
