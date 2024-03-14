<?php

require_once plugin_dir_path( __FILE__ ) . '../controller/PageCreator.php';
require_once plugin_dir_path( __FILE__ ) . '../data/LPageryDao.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/CustomSanitizer.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/SettingsController.php';
require_once plugin_dir_path( __FILE__ ) . '../utils/Utils.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/SubstitutionDataPreparator.php';
require_once plugin_dir_path( __FILE__ ) . '../controller/InputParamProvider.php';
class LPageryCreatePostDelegate
{
    /**
     * @throws Exception
     */
    public static function lpagery_create_post( $post, $operations = array( "create", "update" ) )
    {
        $process_id = (int) $post['process_id'];
        if ( $process_id <= 0 ) {
            throw new Exception( "Process ID must be set. This might be an issue with your Database-Version. Please check and consider updating the Database-Version" );
        }
        $process_by_id = LPageryDao::lpagery_get_process_by_id( $process_id );
        $templatePath = $process_by_id->post_id;
        $page = get_post( $templatePath );
        if ( !$page ) {
            throw new Exception( "Post with ID " . $templatePath . " not found" );
        }
        $processed_slugs = $post['processed_slugs'] ?? [];
        $data = $post['data'];
        
        if ( is_string( $data ) ) {
            $json_decode = LPagerySubstitutionDataPreparator::prepare_data( $data );
        } else {
            $json_decode = $data;
        }
        
        $categories = array();
        $tags = array();
        $status = 'publish';
        $slug = LPageryCustomSanitizer::lpagery_sanitize_title_with_dashes( $page->post_title );
        $parent_path = null;
        $datetime = null;
        $params = LPageryInputParamProvider::lpagery_get_input_params_without_images( $json_decode );
        $replaced_slug = LPagerySubstitutionHandler::lpagery_substitute( $params, $slug );
        $replaced_slug = sanitize_title( $replaced_slug );
        
        if ( in_array( $replaced_slug, $processed_slugs ) || isset( $json_decode["lpagery_ignore"] ) && filter_var( $json_decode["lpagery_ignore"], FILTER_VALIDATE_BOOLEAN ) ) {
            syslog( LOG_INFO, "Ignoring Post " . $replaced_slug );
            return array(
                "mode"          => "ignored",
                "replaced_slug" => $replaced_slug,
            );
        }
        
        $post_settings = array(
            "parent"           => $parent_path,
            "categories"       => $categories,
            "tags"             => $tags,
            "slug"             => $slug,
            "status"           => $status,
            "publish_datetime" => $datetime,
        );
        $mode = LPageryPageCreator::lpagery_create_or_update_post(
            $json_decode,
            $process_id,
            $page,
            $post_settings,
            $operations
        );
        return array(
            "page"          => $page,
            "mode"          => $mode,
            "slug"          => $slug,
            "replaced_slug" => $replaced_slug,
        );
    }

}