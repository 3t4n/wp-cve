<?php

class LPagerySubstitutionHandler
{
    public static function lpagery_substitute( $params, $content )
    {
        $json = false;
        
        if ( self::is_json( $content ) ) {
            $json = true;
            $content = json_decode( $content, true );
        }
        
        
        if ( is_object( $content ) ) {
            $content = (object) self::lpagery_substituteArray( $params, (array) $content );
        } elseif ( is_array( $content ) ) {
            $content = self::lpagery_substituteArray( $params, (array) $content );
        } else {
            $source_attachment_ids = $params["source_attachment_ids"] ?? array();
            $target_attachment_ids = $params["target_attachment_ids"] ?? array();
            
            if ( is_string( $content ) ) {
                $keys = $params["keys"];
                $values = $params["values"];
                if ( self::is_HTML( $content ) ) {
                    if ( str_contains( $content, "<img" ) && $params["image_processing_enabled"] && lpagery_fs()->is_plan_or_trial__premium_only( "extended" ) ) {
                        $content = self::replace_images(
                            $content,
                            $keys,
                            $values,
                            $source_attachment_ids,
                            $target_attachment_ids
                        );
                    }
                }
                $content = self::lpagery_replace( $keys, $values, $content );
                $content = self::fix_newlines( $content );
                $content = self::fix_newlines( $content );
                $content = self::handle_spintax( $params, $content );
                if ( self::is_HTML( $content ) ) {
                    foreach ( $keys as $index => $key ) {
                        $replaced_key = str_replace( array( "{", "}" ), "", $key );
                        if ( empty($replaced_key) ) {
                            continue;
                        }
                        
                        if ( str_contains( $content, $replaced_key ) ) {
                            $pattern = "/{(<[^<]*?>)" . $replaced_key . "<.*?>}/";
                            $replacement = $values[$index];
                            if ( $replacement == null ) {
                                $replacement = "";
                            }
                            $content = preg_replace( $pattern, $replacement, $content );
                        }
                    
                    }
                }
                $content = self::escape_css_vars( $content );
            }
            
            $content = self::replace_attachment_ids( $content, $source_attachment_ids, $target_attachment_ids );
        }
        
        $return_value = $content;
        if ( $json ) {
            $return_value = json_encode( $content, JSON_HEX_QUOT );
        }
        return $return_value;
    }
    
    private static function is_HTML( $string )
    {
        
        if ( $string != strip_tags( $string ) ) {
            return true;
        } else {
            return false;
        }
    
    }
    
    private static function replace_images(
        $content,
        $keys,
        $values,
        $source_attachment_ids,
        $target_attachment_ids
    )
    {
        $dom = new DomDocument();
        if ( !fs_starts_with( $content, "<?xml encoding" ) ) {
            $content = '<?xml encoding="utf-8" ?>' . $content;
        }
        libxml_use_internal_errors( true );
        $dom->loadHTML( $content, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED );
        $images = $dom->getElementsByTagName( "img" );
        foreach ( $images as $image ) {
            $src_set = false;
            
            if ( $image->getAttribute( "src" ) ) {
                $index_src = array_search( $image->getAttribute( "src" ), $keys );
                
                if ( $index_src ) {
                    $image->setAttribute( "src", $values[$index_src] );
                    $src_set = true;
                }
            
            }
            
            $attachment_postid = self::find_post_id_from_path( $image->getAttribute( "src" ) );
            $source_index = array_search( $attachment_postid, $source_attachment_ids );
            
            if ( is_numeric( $source_index ) ) {
                $target_attachment_id = $target_attachment_ids[$source_index];
                
                if ( $target_attachment_id ) {
                    if ( !$src_set ) {
                        $image->setAttribute( "src", wp_get_attachment_url( $target_attachment_id ) );
                    }
                    $image_alt = get_post_meta( $target_attachment_id, '_wp_attachment_image_alt', true );
                    $image->setAttribute( "alt", $image_alt );
                    $title = get_the_title( $target_attachment_id );
                    $image->setAttribute( "title", $title );
                    if ( $image->getAttribute( "srcset" ) ) {
                        $image->setAttribute( "srcset", wp_get_attachment_image_srcset( $target_attachment_id ) );
                    }
                    if ( $image->getAttribute( "data-img-src" ) ) {
                        $image->setAttribute( "data-img-src", wp_get_attachment_url( $target_attachment_id ) );
                    }
                    if ( $image->getAttribute( "data-attachment-id" ) ) {
                        $image->setAttribute( "data-attachment-id", $target_attachment_id );
                    }
                    if ( $image->getAttribute( "sizes" ) ) {
                        $image->setAttribute( "sizes", wp_get_attachment_image_sizes( $target_attachment_id, "large" ) );
                    }
                }
            
            }
        
        }
        return self::fix_newlines( $dom->saveHTML() );
    }
    
    /**
     * @param $content
     * @param $source_attachment_ids
     * @param $target_attachment_ids
     *
     * @return mixed
     */
    private static function replace_attachment_ids( $content, $source_attachment_ids, $target_attachment_ids )
    {
        if ( is_numeric( $content ) ) {
            foreach ( $source_attachment_ids as $key => $source_attachment_id ) {
                $target_attachment_id = $target_attachment_ids[$key];
                if ( $content == $source_attachment_id ) {
                    $content = $target_attachment_id;
                }
            }
        }
        return $content;
    }
    
    private static function is_json( $content ) : bool
    {
        return is_string( $content ) && is_array( json_decode( $content, true ) );
    }
    
    public static function lpagery_substituteArray( $params, $array )
    {
        array_walk_recursive( $array, function ( &$value ) use( $params ) {
            $value = self::lpagery_substitute( $params, $value );
        } );
        return $array;
    }
    
    /**
     * @param $content
     *
     * @return array|mixed|string|string[]|null
     */
    private static function handle_spintax( $params, $content )
    {
        return $content;
    }
    
    /**
     * @param $content
     *
     * @return array|mixed|string|string[]
     */
    private static function fix_newlines( $content )
    {
        if ( !self::is_HTML( $content ) ) {
            return $content;
        }
        if ( str_contains( $content, "\\\\n" ) ) {
            $content = str_replace( "\\\\n", '', $content );
        }
        if ( str_contains( $content, "\\\n" ) ) {
            $content = str_replace( "\\\n", '', $content );
        }
        if ( str_contains( $content, "\\n" ) ) {
            $content = str_replace( "\\n", '', $content );
        }
        return $content;
    }
    
    private static function find_post_id_from_path( $path )
    {
        
        if ( substr( $path, 0, 4 ) !== "http" ) {
            $path = strstr( $path, '/' );
            $path = trim( $path, '/' );
        }
        
        // detect if is a media resize, and strip resize portion of file name
        if ( preg_match( '/(-\\d{1,4}x\\d{1,4})\\.(jpg|jpeg|png|gif)$/i', $path, $matches ) ) {
            $path = str_ireplace( $matches[1], '', $path );
        }
        // process and include the year / month folders so WP function below finds properly
        
        if ( preg_match( '/uploads\\/(\\d{1,4}\\/)?(\\d{1,2}\\/)?(.+)$/i', $path, $matches ) ) {
            unset( $matches[0] );
            $path = implode( '', $matches );
        }
        
        // at this point, $path contains the year/month/file name (without resize info)
        // call WP native function to find post ID properly
        return attachment_url_to_postid( $path );
    }
    
    /**
     * @param mixed $content
     * @return array|mixed|string|string[]
     */
    private static function escape_css_vars( $content )
    {
        if ( str_contains( $content, "var(\\u002d\\u002d" ) ) {
            $content = str_replace( "var(\\u002d\\u002d", "var(\\\\u002d\\\\u002d", $content );
        }
        return $content;
    }
    
    /**
     * @param $keys
     * @param $values
     * @param $content
     * @return array
     */
    private static function lpagery_replace( $keys, $values, $content )
    {
        foreach ( $keys as $index => $key_value ) {
            $currentValue = $values[$index];
            if ( !$currentValue ) {
                $currentValue = "";
            }
            $content = str_ireplace( $key_value, $currentValue, $content );
        }
        return $content;
    }

}