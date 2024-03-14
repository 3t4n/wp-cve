<?php

require_once plugin_dir_path( __FILE__ ) . '../controller/SettingsController.php';
class LPageryInputParamProvider
{
    public static function lpagery_provide_input_params( $json_data, $process_id, $source_post_id )
    {
        $params = self::lpagery_get_input_params_without_images( $json_data );
        $source_attachment_ids = array();
        $target_attachment_ids = array();
        $keys = $params["keys"];
        $values = $params["values"];
        $params = array(
            "keys"                     => $keys,
            "values"                   => $values,
            "spintax_enabled"          => LPagerySettingsController::lpagery_get_spintax_enabled( $process_id ),
            "image_processing_enabled" => LPagerySettingsController::lpagery_get_image_processing_enabled( $process_id ),
            "author_id"                => LPagerySettingsController::lpagery_get_author_id( $process_id ),
            "source_attachment_ids"    => $source_attachment_ids,
            "target_attachment_ids"    => $target_attachment_ids,
            "raw_data"                 => $json_data,
            "process_id"               => $process_id,
        );
        return $params;
    }
    
    private static function add_image_replacements( array $size_replacements, array $keys, array $values ) : array
    {
        $source_srcsets = $size_replacements["source_srcsets"];
        $target_srcsets = $size_replacements["target_srcsets"];
        $keys = array_merge( $keys, $source_srcsets );
        $values = array_merge( $values, $target_srcsets );
        $source_urls = $size_replacements["source_urls"];
        $target_urls = $size_replacements["target_urls"];
        $keys = array_merge( $keys, $source_urls );
        $values = array_merge( $values, $target_urls );
        return array( $keys, $values );
    }
    
    /**
     * @param $source_post_id
     * @param array|object $source_attachments
     * @param array $source_attachment
     * @return array
     */
    private static function get_translated_attachment( $source_post_id, $source_attachments )
    {
        $source_attachment = null;
        $source_language_details = apply_filters( 'wpml_post_language_details', null, $source_post_id );
        
        if ( $source_language_details ) {
            $source_language_code = $source_language_details["language_code"];
            foreach ( $source_attachments as $one_source_attachment ) {
                $attachment_lang_details = apply_filters( 'wpml_post_language_details', null, $one_source_attachment->ID );
                
                if ( $attachment_lang_details["language_code"] == $source_language_code ) {
                    $source_attachment = (array) $one_source_attachment;
                    break;
                }
            
            }
            if ( !$source_attachment ) {
                $source_attachment = (array) $source_attachments[0];
            }
        }
        
        return $source_attachment;
    }
    
    /**
     * @param $json_data
     * @return array
     */
    public static function lpagery_get_input_params_without_images( $json_data )
    {
        $keys = array();
        $values = array();
        $index = 0;
        $max_placeholders = lpagery_get_placeholder_counts();
        foreach ( $json_data as $key => $value ) {
            if ( $key == "" ) {
                continue;
            }
            if ( !$value ) {
                $value = "";
            }
            if ( $key !== 'lpagery_id' ) {
                $index++;
            }
            if ( $max_placeholders && $max_placeholders["placeholders"] ) {
                if ( $index > $max_placeholders["placeholders"] ) {
                    break;
                }
            }
            $prefix = ( !str_starts_with( $key, "{" ) ? "{" : "" );
            $suffix = ( !str_ends_with( $key, "}" ) ? "}" : "" );
            array_push( $keys, $prefix . $key . $suffix );
            array_push( $values, $value );
        }
        $params = array(
            "keys"                     => $keys,
            "values"                   => $values,
            "spintax_enabled"          => false,
            "image_processing_enabled" => false,
        );
        return $params;
    }

}