<?php

require_once plugin_dir_path( __FILE__ ) . 'MetaDataHandler.php';
require_once plugin_dir_path( __FILE__ ) . 'SubstitutionHandler.php';
require_once plugin_dir_path( __FILE__ ) . 'PagebuilderHandler.php';
require_once plugin_dir_path( __FILE__ ) . 'SeoPluginHandler.php';
require_once plugin_dir_path( __FILE__ ) . 'InputParamProvider.php';
require_once plugin_dir_path( __FILE__ ) . 'WpmlHandler.php';
require_once plugin_dir_path( __FILE__ ) . 'FifuHandler.php';
require_once plugin_dir_path( __FILE__ ) . '../data/LPageryDao.php';
require_once plugin_dir_path( __FILE__ ) . '../utils/Utils.php';
class LPageryPageCreator
{
    /**
     * @throws Exception
     */
    public static function lpagery_create_or_update_post(
        $json_data,
        $process_id,
        WP_Post $source_post,
        $post_settings,
        $operations
    )
    {
        if ( !in_array( "create", $operations ) ) {
            return "ignored";
        }
        list( $parent_id_from_dashboard, $categories, $tags, $slug, $status ) = LPageryUtils::lpagery_extract_post_settings( $post_settings );
        $params = LPageryInputParamProvider::lpagery_provide_input_params( $json_data, $process_id, $source_post->ID );
        $content = $source_post->post_content;
        $replaced = LPagerySubstitutionHandler::lpagery_substitute( $params, $content );
        $replaced_filtered = LPagerySubstitutionHandler::lpagery_substitute( $params, $source_post->post_content_filtered );
        $replaced_title = LPagerySubstitutionHandler::lpagery_substitute( $params, $source_post->post_title );
        $replaced_slug = LPagerySubstitutionHandler::lpagery_substitute( $params, $slug );
        $replaced_excerpt = LPagerySubstitutionHandler::lpagery_substitute( $params, $source_post->post_excerpt );
        $author_id = $params["author_id"];
        if ( $status == "-1" ) {
            $status = $source_post->post_status;
        }
        $parent_post = LPageryDao::lpagery_find_post_by_id( $parent_id_from_dashboard );
        $publish_datetime = null;
        $new_post = [
            'post_content'          => $replaced,
            'post_content_filtered' => $replaced_filtered,
            'post_title'            => strip_tags( $replaced_title ),
            'post_excerpt'          => $replaced_excerpt,
            'post_type'             => $source_post->post_type,
            'comment_status'        => $source_post->comment_status,
            'ping_status'           => $source_post->ping_status,
            'post_password'         => $source_post->post_password,
            'post_name'             => ( $replaced_slug != null ? $replaced_slug : null ),
            'post_parent'           => ( $parent_post != null ? $parent_post["id"] : null ),
            'post_mime_type'        => $source_post->post_mime_type,
            'post_status'           => $status,
            'post_author'           => $author_id,
            'post_date'             => $publish_datetime,
            'post_date_gmt'         => ( $publish_datetime != null ? get_gmt_from_date( $publish_datetime ) : null ),
        ];
        $transient_key = "lpagery_{$process_id}'_'{$replaced_slug}";
        $process_slug_transient = get_transient( $transient_key );
        
        if ( $process_slug_transient ) {
            error_log( "LPagery Ignoring Post is already processing {$replaced_slug}" );
            return "ignored";
        }
        
        set_transient( $transient_key, true, 10 );
        global  $wpdb ;
        $wpdb->query( 'START TRANSACTION' );
        $post_id = wp_insert_post( $new_post, true );
        
        if ( is_wp_error( $post_id ) ) {
            error_log( $post_id->get_error_message() );
            $wpdb->query( 'ROLLBACK' );
            delete_transient( $transient_key );
            throw new Exception( $post_id->get_error_message() );
        }
        
        try {
            LPageryDao::lpagery_add_post_to_process(
                $process_id,
                $params,
                $post_id,
                $replaced_slug
            );
        } catch ( Exception $e ) {
            error_log( "LPagery Rolling Back Transaction During creation slug : {$replaced_slug}, Process : {$process_id} " . $e->getMessage() );
            $wpdb->query( 'ROLLBACK' );
            delete_transient( $transient_key );
            return "ignored";
        }
        LPageryMetaDataHandler::lpagery_copy_post_meta_info(
            $post_id,
            $source_post,
            array( "_lpagery_page_source", "_lpagery_data" ),
            $params
        );
        $wpdb->query( 'COMMIT' );
        LPageryPagebuilderHandler::lpagery_handle_pagebuilder( $source_post->ID, $post_id, $params );
        LPagerySeoPluginHandler::lpagery_handle_seo_plugin( $source_post->ID, $post_id, $params );
        LPageryWpmlHandler::lpagery_handle_wpml( $source_post->ID, $post_id );
        LPageryFifuHandler::lpagery_handle_fifu( $post_id, $params["raw_data"] );
        delete_transient( $transient_key );
        return "created";
    }

}