<?php

namespace WunderAuto\PostTypes;

use WP_Post;
use WunderAuto\Loader;
use WunderAuto\WunderAuto;

/**
 * Class Handler
 */
class Handler
{
    /**
     * @var WunderAuto
     */
    private $wunderAuto;

    /**
     * @param WunderAuto $wunderAuto
     */
    public function __construct($wunderAuto)
    {
        $this->wunderAuto = $wunderAuto;
    }

    /**
     * Register to WordPRess hooks
     *
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('save_post', $this, 'savePost', 20, 2);
        $loader->addAction('deleted_post', $this, 'deletePost', 20, 2);
    }

    /**
     * Routes save_post actions to the appropriate class
     *
     * @param int          $postId
     * @param WP_Post|null $post
     *
     * @return void
     */
    public function savePost($postId, $post)
    {
        if (is_null($post) || !in_array($post->post_type, $this->wunderAuto->postTypes)) {
            return;
        }

        if (wa_doing_autosave()) {
            return;
        }

        if (!isset($_POST['wunderautomation_save_post'])) {
            return;
        }

        $savedFrom = sanitize_key($_POST['wunderautomation_save_post']);
        $nonce     = isset($_POST['wunderautomation_save_post_nonce']) ?
            wp_unslash(sanitize_key($_POST['wunderautomation_save_post_nonce'])) :
            '';
        $nonce     = is_array($nonce) ? (string)$nonce[0] : $nonce;

        switch ($savedFrom) {
            case 'quick_edit':
                if (!wp_verify_nonce($nonce, 'wunderautomation_quick_edit_nonce_' . $post->post_type)) {
                    return;
                }
                break;
            case 'edit_page':
                if (!wp_verify_nonce($nonce, 'wunderautomation_edit_page_nonce_' . $post->post_type)) {
                    return;
                }
                break;
            default:
                return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        if (wp_doing_ajax() && !check_admin_referer('inlineeditnonce', '_inline_edit')) {
            return;
        }

        remove_action('save_post', [$this, 'savePost'], 20);
        switch ($post->post_type) {
            case 'automation-workflow':
                $workflow = $this->wunderAuto->createWorkflowObject($postId);
                $workflow->parsePosted($post);
                $workflow->save();
                break;
            case 'automation-retrigger':
                $reTrigger = $this->wunderAuto->createReTriggerObject($postId);
                $reTrigger->parsePosted($post);
                $reTrigger->save();
                break;
        }
        add_action('save_post', [$this, 'savePost'], 20, 2);
    }

    /**
     * House keeping after a workflow or retrigger has been deleted
     *
     * @handles delete_post
     *
     * @param int          $postId
     * @param WP_Post|null $post
     *
     * @return void
     */
    public function deletePost($postId, $post)
    {
        if (is_null($post) || !in_array($post->post_type, $this->wunderAuto->postTypes)) {
            return;
        }

        switch ($post->post_type) {
            case 'automation-workflow':
                $workflow = $this->wunderAuto->createWorkflowObject($postId);
                $workflow->deleted();
                break;
            case 'automation-retrigger':
                $reTrigger = $this->wunderAuto->createReTriggerObject($postId);
                $reTrigger->deleted();
                break;
        }
    }
}
