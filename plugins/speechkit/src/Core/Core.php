<?php

declare(strict_types=1);

namespace Beyondwords\Wordpress\Core;

use Beyondwords\Wordpress\Component\Post\PostMetaUtils;
use Beyondwords\Wordpress\Component\Settings\SettingsUtils;
use Beyondwords\Wordpress\Core\CoreUtils;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 **/
class Core
{
    /**
     * API Client.
     *
     * @since 3.0.0
     */
    private $apiClient;

    /**
     * Constructor.
     *
     * @since 3.0.0
     * @since 3.7.1 Remove the "X BeyondWords errors found" notice after a reported slow MySQL query.
     * @since 3.9.0 Add actions for deleting/trashing/restoring posts.
     * @since 4.0.0 Moved side-effects into init() method.
     */
    public function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Init.
     *
     * @since 4.0.0
     */
    public function init()
    {
        // Actions
        add_action('enqueue_block_editor_assets', array($this, 'enqueueBlockEditorAssets'), 1, 0);
        add_action('init', array($this, 'loadPluginTextdomain'));
        add_action('init', array($this, 'registerMeta'), 99, 3);

        // Actions for adding/updating posts
        add_action('wp_after_insert_post', array($this, 'onAddOrUpdatePost'), 99);

        // Actions for deleting/trashing/restoring posts
        add_action('before_delete_post', array($this, 'onTrashOrDeletePost'));
        add_action('trashed_post', array($this, 'onTrashOrDeletePost'));
        add_action('untrashed_post', array($this, 'onUntrashPost'), 10);

        // Actions for WPGraphQL
        add_action('graphql_register_types', array($this, 'graphqlRegisterTypes'));

        add_filter('is_protected_meta', array($this, 'isProtectedMeta'), 10, 2);
    }

    /**
     * Should process post status?
     *
     * @since 3.5.0
     * @since 3.7.0 Process audio for posts with 'pending' status
     *
     * @param string $status WordPress post status (e.g. 'pending', 'publish', 'private', 'future', etc).
     *
     * @return boolean
     */
    public function shouldProcessPostStatus($status)
    {
        $statuses = ['pending', 'publish', 'private', 'future'];

        /**
         * Filters the post statuses that we consider for audio processing.
         *
         * When a post is saved with any other post status we will not send
         * any data to the BeyondWords API.
         *
         * The default values are "pending", "publish", "private" and "future".
         *
         * Scheduled for removal in plugin version 5.0.0.
         *
         * @since 3.3.3
         * @since 3.7.0 Process audio for posts with 'pending' status
         *
         * @deprecated 4.3.0 Replaced with beyondwords_settings_post_statuses.
         *
         * @param string[] $statuses The post statuses that we consider for audio processing.
         */
        $statuses = apply_filters('beyondwords_post_statuses', $statuses);

        /**
         * Filters the post statuses that we consider for audio processing.
         *
         * When a post is saved with any other post status we will not send
         * any data to the BeyondWords API.
         *
         * The default values are "pending", "publish", "private" and "future".
         *
         * @since 3.3.3 Introduced as beyondwords_post_statuses.
         * @since 3.7.0 Process audio for posts with 'pending' status.
         * @since 4.3.0 Renamed from beyondwords_post_statuses to beyondwords_settings_post_statuses.
         *
         * @param string[] $statuses The post statuses that we consider for audio processing.
         */
        $statuses = apply_filters('beyondwords_settings_post_statuses', $statuses);

        // Only generate audio for certain post statuses
        if (is_array($statuses) && in_array($status, $statuses)) {
            return true;
        }

        return false;
    }

    /**
     * Should generate audio for post?
     *
     * @since 3.5.0
     * @since 3.10.0 remove wp_is_post_revision check.
     *
     * @param int $postId WordPress Post ID.
     *
     * @return boolean
     */
    public function shouldGenerateAudioForPost($postId)
    {
        // Bail if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        $status = get_post_status($postId);

        // Bail if the post status is invalid
        if (! $this->shouldProcessPostStatus($status)) {
            return false;
        }

        $generateAudio = PostMetaUtils::hasGenerateAudio($postId);

        // Bail if 'Generate Audio' has not been selected
        if (! $generateAudio) {
            return false;
        }

        return true;
    }

    /**
     * Generate audio for post.
     *
     * @since 3.0.0
     * @since 3.2.0 Added speechkit_post_statuses filter
     * @since 3.5.0 Refactored, adding $this->shouldGenerateAudioForPost()
     *
     * @param int $postId WordPress Post ID.
     *
     * @return array|false Response from API, or false if audio was not generated.
     */
    public function generateAudioForPost($postId)
    {
        // Perform checks to see if this post should be processed
        if (! $this->shouldGenerateAudioForPost($postId)) {
            return false;
        }

        $projectId = PostMetaUtils::getProjectId($postId);

        // Bail if we cannot determine a Project ID
        if (! $projectId) {
            return false;
        }

        // Does this post already have audio?
        $contentId = PostMetaUtils::getContentId($postId);

        // Has autoregeneration for Post updates been disabled?
        if ($contentId) {
            if (defined('BEYONDWORDS_AUTOREGENERATE') && ! BEYONDWORDS_AUTOREGENERATE) {
                return false;
            }

            $response = $this->apiClient->updateAudio($postId);
        } else {
            $response = $this->apiClient->createAudio($postId);
        }

        $this->processResponse($response, $projectId, $postId);

        return $response;
    }

    /**
     * Delete audio for post.
     *
     * @since 4.0.5
     *
     * @param int $postId WordPress Post ID.
     *
     * @return array|false Response from API, or false if audio was not generated.
     */
    public function deleteAudioForPost($postId)
    {
        $projectId = PostMetaUtils::getProjectId($postId);
        $contentId = PostMetaUtils::getContentId($postId);

        // Bail if we cannot determine a Project ID or Content ID
        if (! $projectId || ! $contentId) {
            return false;
        }

        return $this->getApiClient()->deleteAudio($postId);
    }

    /**
     * Batch delete audio for posts.
     *
     * @since 4.1.0
     *
     * @param int[] $postIds Array of WordPress Post IDs.
     *
     * @return array|false Response from API, or false if audio was not generated.
     */
    public function batchDeleteAudioForPosts($postIds)
    {
        return $this->getApiClient()->batchDeleteAudio($postIds);
    }

    /**
     * Process the response body of a BeyondWords REST API response.
     *
     * @since 3.0.0
     * @since 3.7.0 Stop saving response.access_key, we don't currently use it.
     * @since 4.0.0 Replace Podcast IDs with Content IDs
     * @since 4.5.0 Save response.preview_token to support post scheduling.
     */
    public function processResponse($response, $projectId, $postId)
    {
        if (! is_array($response)) {
            return $response;
        }

        if (array_key_exists('id', $response)) {
            // Save Project ID
            update_post_meta($postId, 'beyondwords_project_id', $projectId);

            // Save Content ID
            update_post_meta($postId, 'beyondwords_content_id', $response['id']);

            // Temporarily save into Podcast ID field to support downgrades to < 4.0.0
            update_post_meta($postId, 'beyondwords_podcast_id', $response['id']);

            if (array_key_exists('preview_token', $response)) {
                // Save Preview Key
                update_post_meta($postId, 'beyondwords_preview_token', $response['preview_token']);
            }
        }

        return $response;
    }

    /**
     * Enqueue Core (built & minified) JS for Block Editor.
     */
    public function enqueueBlockEditorAssets()
    {
        if (CoreUtils::isGutenbergPage()) {
            $postType = get_post_type();

            $postTypes = SettingsUtils::getCompatiblePostTypes();

            if (in_array($postType, $postTypes, true)) {
                $assetFile = include BEYONDWORDS__PLUGIN_DIR . 'build/index.asset.php';

                // Register the Block Editor JS
                wp_enqueue_script(
                    'beyondwords-block-js',
                    BEYONDWORDS__PLUGIN_URI . 'build/index.js',
                    $assetFile['dependencies'],
                    $assetFile['version'],
                    true
                );
            }
        }
    }

    /**
     * Load plugin textdomain.
     *
     * @since 3.5.0
     *
     * @return void
     */
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain('speechkit');
    }

    /**
     * Register meta fields for REST API output.
     *
     * It is recommended to register meta keys for a specific combination
     * of object type and object subtype.
     *
     * @since 2.5.0
     * @since 3.9.0 Don't register speechkit_status - downgrades to plugin v2.x are no longer expected.
     *
     * @return void
     **/
    public function registerMeta()
    {
        $postTypes = SettingsUtils::getCompatiblePostTypes();

        if (is_array($postTypes)) {
            $keys = CoreUtils::getPostMetaKeys('all');

            foreach ($postTypes as $postType) {
                $options = array(
                    'show_in_rest' => true,
                    'single' => true,
                    'type' => 'string',
                    'default' => '',
                    'object_subtype' => $postType,
                    'prepare_callback' => 'sanitize_text_field',
                    'sanitize_callback' => 'sanitize_text_field',
                    'auth_callback' => function () {
                        return current_user_can('edit_posts');
                    },
                );

                foreach ($keys as $key) {
                    register_meta('post', $key, $options);
                }
            }
        }
    }

    /**
     * Make all of our custom fields private, so they don't appear in the
     * "Custom Fields" panel, which can cause conflicts for the Block Editor.
     *
     * https://github.com/WordPress/gutenberg/issues/23078
     *
     * @since 4.0.0
     */
    public function isProtectedMeta($protected, $metaKey)
    {
        $keysToProtect = CoreUtils::getPostMetaKeys('all');

        if (in_array($metaKey, $keysToProtect, true)) {
            $protected = true;
        }

        return $protected;
    }

    /**
     * WP Trash/Delete Post action.
     *
     * Fires before a post has been trashed or deleted.
     *
     * We want to send a DELETE HTTP request when a post is either trashed or deleted, so the
     * audio no longer appears in playlists, or in the publishers BeyondWords dashboard.
     *
     * @since 3.9.0
     *
     * @param int $postId Post ID.
     *
     * @return bool
     **/
    public function onTrashOrDeletePost($postId)
    {
        // Bail if this post has no Project ID / Content ID
        if (! PostMetaUtils::getProjectId($postId) || ! PostMetaUtils::getContentId($postId)) {
            return false;
        }

        $response = $this->apiClient->deleteAudio($postId);

        if (
            ! is_array($response) ||
            ! array_key_exists('deleted', $response) ||
            ! $response['deleted'] === true
        ) {
            $errorMessage = __('Unable to delete audio from BeyondWords dashboard');

            if (is_array($response) && array_key_exists('message', $response)) {
                $errorMessage .= ': ' . $response['message'];
            }

            update_post_meta($postId, 'beyondwords_error_message', $errorMessage);

            return false;
        }

        return $response;
    }

    /**
     * WP Untrash ("Restore") Post action.
     *
     * Fires before a post is restored from the Trash.
     *
     * We want to send a PUT HTTP request when a post is Untrashed, to "undelete" it from the BeyondWords dashboard.
     *
     * @since 3.9.0
     *
     * @param int    $postId         Post ID.
     * @param string $previousStatus The status of the post at the point where it was trashed.
     *
     * @return bool|Response
     **/
    public function onUntrashPost($postId)
    {
        // Bail if this post has no Project ID / Content ID
        if (! PostMetaUtils::getProjectId($postId) || ! PostMetaUtils::getContentId($postId)) {
            return false;
        }

        $response = $this->apiClient->updateAudio($postId);

        if (
            ! is_array($response) ||
            ! array_key_exists('id', $response) ||
            ! array_key_exists('deleted', $response) ||
            ! $response['deleted'] === false
        ) {
            $errorMessage = __('Unable to restore audio to BeyondWords dashboard');

            if (is_array($response) && array_key_exists('message', $response)) {
                $errorMessage .= ': ' . $response['message'];
            }

            update_post_meta($postId, 'beyondwords_error_message', $errorMessage);

            return false;
        }

        return $response;
    }

    /**
     * WP Save Post action.
     *
     * Fires after a post, its terms and meta data has been saved.
     *
     * @since 3.0.0
     * @since 3.2.0 Added beyondwords_post_statuses filter.
     * @since 3.6.1 Improve $postBefore hash comparison.
     * @since 3.9.0 Renamed method from wpAfterInsertPost to onAddOrUpdatePost.
     * @since 4.0.0 Removed hash comparison.
     * @since 4.4.0 Delete audio if beyondwords_delete_content custom field is set.
     * @since 4.5.0 Remove unwanted debugging custom fields.
     *
     * @param int          $postId     Post ID.
     *
     * @return bool|Response
     **/
    public function onAddOrUpdatePost($postId)
    {
        // Has the "Remove" feature been used?
        if (get_post_meta($postId, 'beyondwords_delete_content', true) === '1') {
            // Make DELETE API request
            $this->deleteAudioForPost($postId);

            // Remove custom fields
            PostMetaUtils::removeAllBeyondwordsMetadata($postId);

            return false;
        }

        $status = get_post_status($postId);

        // Bail if the post status is invalid
        if (! $this->shouldProcessPostStatus($status)) {
            return false;
        }

        // Generate Audio for the updated post
        $this->generateAudioForPost($postId);

        return true;
    }

    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * GraphQL: Register types.
     *
     * @since 3.6.0
     * @since 4.0.0 Register contentId field, and contentId/podcastId are now String, not Int
     */
    public function graphqlRegisterTypes()
    {
        register_graphql_object_type('Beyondwords', [
            'description' => __('BeyondWords audio details. Use this data to embed an audio player using the BeyondWords JavaScript SDK.', 'speechkit'), // phpcs:ignore Generic.Files.LineLength.TooLong
            'fields' => [
                'projectId' => [
                    'description' => __('BeyondWords project ID', 'speechkit'),
                    'type' => 'Int'
                ],
                'contentId' => [
                    'description' => __('BeyondWords content ID', 'speechkit'),
                    'type' => 'String'
                ],
                'podcastId' => [
                    'description' => __('BeyondWords legacy podcast ID', 'speechkit'),
                    'type' => 'String'
                ],
            ],
        ]);

        $beyondwordsPostTypes = SettingsUtils::getCompatiblePostTypes();

        $graphqlPostTypes = \WPGraphQL::get_allowed_post_types();

        $postTypes = array_intersect($beyondwordsPostTypes, $graphqlPostTypes);

        if (! empty($postTypes) && is_array($postTypes)) {
            foreach ($postTypes as $postType) {
                $postTypeObject = get_post_type_object($postType);

                register_graphql_field($postTypeObject->graphql_single_name, 'beyondwords', [
                    'type'        => 'Beyondwords',
                    'description' => __('BeyondWords audio details', 'speechkit'),
                    'resolve'     => function (\WPGraphQL\Model\Post $post) {
                        $beyondwords = [];

                        $contentId = PostMetaUtils::getContentId($post->ID);

                        if (! empty($contentId)) {
                            $beyondwords['contentId'] = $contentId;
                            $beyondwords['podcastId'] = $contentId; // legacy
                        }

                        $projectId = PostMetaUtils::getProjectId($post->ID);

                        if (! empty($projectId)) {
                            $beyondwords['projectId'] = $projectId;
                        }

                        return ! empty($beyondwords) ? $beyondwords : null;
                    }
                ]);
            }
        }
    }
}
