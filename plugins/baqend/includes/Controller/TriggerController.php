<?php

namespace Baqend\WordPress\Controller;

use Baqend\SDK\Model\AssetFilter;
use Baqend\WordPress\Loader;
use WP_Post as Post;

/**
 * Class TriggerController created on 19.07.17.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Controller
 */
class TriggerController extends Controller {

    const COMMENT_APPROVED = 1;
    const COMMENT_DECLINED = 0;

    public function register( Loader $loader ) {
        // For posts
        $loader->add_action( 'save_post', [ $this, 'save_post' ] );
        $loader->add_action( 'delete_post', [ $this, 'delete_post' ] );
        $loader->add_action( 'add_attachment', [ $this, 'add_attachment' ] );
        $loader->add_action( 'attachment_updated', [ $this, 'attachment_updated' ] );
        $loader->add_action( 'delete_attachment', [ $this, 'delete_attachment' ] );
        $loader->add_action( 'set_object_terms', [ $this, 'set_object_terms' ] );

        // For comments
        $loader->add_action( 'comment_post', [ $this, 'comment_post' ] );
        $loader->add_action( 'edit_comment', [ $this, 'edit_comment' ] );
        $loader->add_action( 'delete_comment', [ $this, 'delete_comment' ] );
        $loader->add_action( 'transition_comment_status', [ $this, 'transition_comment' ], 10, 3 );

        // For terms
        $loader->add_action( 'edited_term', [ $this, 'edited_term' ] );
        $loader->add_action( 'delete_term', [ $this, 'delete_term' ] );

        // For users
        $loader->add_action( 'profile_update', [ $this, 'profile_update' ] );
        $loader->add_action( 'user_register', [ $this, 'user_register' ] );
        $loader->add_action( 'delete_user', [ $this, 'delete_user' ] );

        // For themes
        $loader->add_action( 'switch_theme', [ $this, 'switch_theme' ] );

        // For widgets
        $loader->add_filter( 'widget_update_callback', [ $this, 'widget_update_callback' ], 10, 4 );
        $loader->add_action( 'delete_widget', [ $this, 'delete_widget' ] );

        // For updates
        $loader->add_action( 'upgrader_process_complete', [ $this, 'upgrader_process_complete' ] );
        $loader->add_action( 'upgrader_process_complete', [ $this, 'update_speedkit_metadata' ] );

        // For revalidation jobs & widget updates
        $loader->add_action( 'shutdown', [ $this, 'process_filters' ] );
        $loader->add_action( 'shutdown', [ $this, 'process_widget_updates' ] );
    }

    public function save_post( $post_ID ) {
        $hook_name = 'save_post';
        $this->revalidate_post( $post_ID, $hook_name );
    }

    public function delete_post( $post_ID ) {
        $hook_name = 'delete_post';
        $this->revalidate_post( $post_ID, $hook_name );
    }

    public function add_attachment( $post_ID ) {
        $hook_name = 'add_attachment';
        $this->revalidate_post( $post_ID, $hook_name );
    }

    public function attachment_updated( $post_ID ) {
        $hook_name = 'attachment_updated';
        $this->revalidate_post( $post_ID, $hook_name );
    }

    public function delete_attachment( $post_ID ) {
        $hook_name = 'delete_attachment';
        $this->revalidate_post( $post_ID, $hook_name );
    }

    public function set_object_terms( $post_ID ) {
        $hook_name = 'set_object_terms';
        $this->revalidate_post( $post_ID, $hook_name );
    }

    public function comment_post( $comment_ID ) {
        $hook_name = 'comment_post';
        $this->revalidate_comment( $comment_ID, $hook_name );
    }

    public function edit_comment( $comment_ID ) {
        $hook_name = 'edit_comment';
        $this->revalidate_comment( $comment_ID, $hook_name );
    }

    public function delete_comment( $comment_ID ) {
        $hook_name = 'delete_comment';
        $this->revalidate_comment( $comment_ID, $hook_name );
    }

    public function edited_term( $term_ID ) {
        $hook_name = 'edited_term';
        $this->revalidate_term( $term_ID, $hook_name );
    }

    public function delete_term( $term_ID ) {
        $hook_name = 'delete_term';
        $this->revalidate_term( $term_ID, $hook_name );
    }

    public function profile_update( $user_ID ) {
        $hook_name = 'profile_update';
        $this->revalidate_user( $user_ID, $hook_name );
    }

    public function user_register( $user_ID ) {
        $hook_name = 'user_register';
        $this->revalidate_user( $user_ID, $hook_name );
    }

    public function delete_user( $user_ID ) {
        $hook_name = 'delete_user';
        $this->revalidate_user( $user_ID, $hook_name );
    }

    public function switch_theme() {
        $hook_name = 'switch_theme';
        $this->revalidate_site( $hook_name );
    }

    public function delete_widget() {
        $hook_name = 'delete_widget';
        $this->revalidate_site( $hook_name );
    }

    public function process_filters() {
        $this->plugin->revalidation_service->process_filters();
    }

    public function process_widget_updates() {
        $this->plugin->widget_update_service->process_waiting();
    }

    /**
     * @param array $instance
     * @param array $new_instance
     * @param array $old_instance
     * @param WP_Widget $current_widget
     *
     * @return array
     */
    public function widget_update_callback( $instance, $new_instance, $old_instance, $current_widget ) {
        $widget_id_base = $current_widget->id_base;
        if ( $this->plugin->widget_update_service->is_revalidation_allowed( $widget_id_base ) ) {
            $hook_name = 'widget_update_callback';
            $this->revalidate_site( $hook_name );
            $this->plugin->widget_update_service->update_after_revalidation( $widget_id_base );
            return $new_instance;
        }

        $this->plugin->widget_update_service->set_waiting( $widget_id_base );
        return $new_instance;
    }

    /**
     * Used by hooks to revalidate a post.
     *
     * @param int $post_ID
     * @param string $hook_name
     */
    public function revalidate_post( $post_ID, $hook_name ) {
        $post = get_post( (int) $post_ID );
        if ( ! $post ) {
            return;
        }

        // For updated posts, send a revalidation request to the server
        $this->do_revalidate_post( $post, $hook_name );
    }

    /**
     * Used by hooks to revalidate a comment.
     *
     * @param int $comment_ID
     * @param string $hook_name
     */
    public function revalidate_comment( $comment_ID, $hook_name ) {
        $comment = get_comment( $comment_ID );
        if ( ! $comment ) {
            return;
        }

        $post_id = $comment->comment_post_ID;
        $this->revalidate_post( $post_id, $hook_name );
    }

    /**
     * @param int|string $new_status
     * @param int|string $old_status
     * @param object $comment
     */
    public function transition_comment( $new_status, $old_status, $comment ) {
        $hook_name = 'transition_comment_status';
        $post_id = $comment->{'comment_post_ID'};
        $post    = get_post( $post_id );
        if ( ! $post ) {
            return;
        }

        $this->do_revalidate_post( $post, $hook_name );
    }

    /**
     * Revalidates a given term by its ID.
     *
     * @param int $term_ID
     * @param string $hook_name
     */
    public function revalidate_term( $term_ID, $hook_name ) {
        $term = get_term( (int) $term_ID );
        if ( ! $term ) {
            return;
        }

        $filter = new AssetFilter();
        // Invalidate the home URL
        $filter->addUrl( ensure_ending_slash( home_url() ) );
        // Invalidate the term's permalink
        $filter->addUrl( get_term_link( $term ) );
        // Invalidate the feed
        $filter->addUrl( get_feed_link() );
        // Invalidate all archives
        $archive_URLs = $this->get_all_archive_urls();
        if ( ! empty( $archive_URLs ) ) {
            $filter->addUrls( $archive_URLs );
        }

        // Add hook name as url for debugging
        $filter->addUrl('https://'.$hook_name.'/');

        $this->send_revalidation( $filter );
    }

    /**
     * Revalidates a given user by its ID.
     *
     * @param int $user_ID
     * @param string $hook_name
     */
    public function revalidate_user( $user_ID, $hook_name ) {
        $user = get_author_posts_url( (int) $user_ID );
        if ( ! $user ) {
            return;
        }

        $filter = new AssetFilter();
        // Invalidate the home URL
        $filter->addUrl( ensure_ending_slash( home_url() ) );
        // Invalidate the user's permalink
        $filter->addUrl( get_author_posts_url( $user_ID ) );
        // Invalidate the feed
        $filter->addUrl( get_feed_link() );
        // Invalidate all archives
        $archive_URLs = $this->get_all_archive_urls();
        if ( ! empty( $archive_URLs ) ) {
            $filter->addUrls( $archive_URLs );
        }

        // Add hook name as url for debugging
        $filter->addUrl('https://'.$hook_name.'/');

        $this->send_revalidation( $filter );
    }

    /**
     * Called when WordPress updated this plugin.
     */
    public function upgrader_process_complete() {
        try {
            // Check all files are downloaded after plugin update
            $this->plugin->speed_kit_service->ensure_files_up_to_date();

            if ( ! $this->plugin->baqend->isConnected() ) {
                return;
            }

            // Revalidate HTML
            $filter = new AssetFilter();
            $filter->addPrefix( ensure_ending_slash( site_url() ) );
            $filter->addContentTypes( [ AssetFilter::DOCUMENT ] );

            // Add hook name as url for debugging
            $filter->addUrl('https://upgrader_process_complete/');

            $this->plugin->revalidation_service->add_asset_filter( $filter );
            $this->logger->debug( 'Revalidation by plugin update succeeded', [ 'filter' => $filter->jsonSerialize() ] );
        } catch ( \Exception $e ) {
            $this->logger->error( 'Revalidation by plugin update failed with ' . get_class( $e ) . ': ' . $e->getMessage(), [ 'exception' => $e ] );
        }
    }

    /**
     * Revalidates the whole WordPress blog.
     *
     * @param string $hook_name
     */
    public function revalidate_site( $hook_name ) {
        $site_url = ensure_ending_slash( site_url() );

        $filter = new AssetFilter();
        $filter->addPrefix( $site_url );

        // Add hook name as url for debugging
        $filter->addUrl('https://'.$hook_name.'/');

        $this->send_revalidation( $filter );
    }

    /**
     * Updates the Speedkit metadata for the given domain
     */
    public function update_speedkit_metadata() {
        $this->plugin->speed_kit_service->save_speedkit_metadata();
    }

    /**
     * @param Post $post
     * @param string $hook_name
     */
    private function do_revalidate_post( Post $post, $hook_name ) {
        $filter = new AssetFilter();
        // Invalidate the home URL
        $filter->addUrl( ensure_ending_slash( home_url() ) );
        // Invalidate the post's permalink
        $filter->addUrl( get_permalink( $post ) );
        // Invalidate the feed
        $filter->addUrl( get_feed_link() );
        // Invalidate all archives
        $archive_URLs = $this->get_all_archive_urls();
        if ( ! empty( $archive_URLs ) ) {
            $filter->addUrls( $archive_URLs );
        }

        // Add hook name as url for debugging
        $filter->addUrl('https://'.$hook_name.'/');

        $this->send_revalidation( $filter );
    }

    /**
     * @return string[]
     */
    private function get_all_archive_urls() {
        $archives_html = wp_get_archives( [ 'echo' => false, 'format' => 'link' ] );
        if ( ! preg_match_all( '#href=\'([^\']+)\'#', $archives_html, $matches ) ) {
            return [];
        }

        list( , $urls ) = $matches;

        return $urls;
    }

    /**
     * Sends an asset filter to the assets API.
     *
     * @param AssetFilter $filter
     */
    private function send_revalidation( AssetFilter $filter ) {
        $this->plugin->revalidation_service->add_asset_filter( $filter );
    }
}
