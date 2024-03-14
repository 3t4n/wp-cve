<?php

namespace WunderAuto\Types\Triggers\Post;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Created
 */
class Created extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('Post created', 'wunderauto');
        $this->group       = __('Posts', 'wunderauto');
        $this->description = __(
            'This trigger fires when a post object (post, page, custom post type etc.) is first created. Note that ' .
            'at this stage the post will most likely be in a draft status. ' .
            'Setting a trigger at creation might be too early for many workflows.',
            'wunderauto'
        );

        $this->addProvidedObject(
            'post',
            'post',
            __('The post that was created', 'wunderauto'),
            true
        );
        $this->addProvidedObject(
            'user',
            'user',
            __('The creator of the post', 'wunderauto'),
            true
        );
    }

    /**
     * Register our hooks with WordPress
     *
     * @return void
     */
    public function registerHooks()
    {
        if (!$this->registered) {
            add_action('transition_post_status', [$this, 'postTransitionStatus'], 20, 3);
            add_action('wp_insert_post', [$this, 'insertPost'], 20, 3);
        }
        $this->registered = true;
    }

    /**
     * Handler for the transition_post_status action
     *
     * @param string   $new
     * @param string   $old
     * @param \WP_Post $post
     *
     * @return void
     */
    public function postTransitionStatus($new, $old, $post)
    {
        if ($post->post_type == 'revision') {
            return;
        }

        if (in_array($new, ['trash', 'auto-draft'])) {
            return;
        }

        if (in_array($post->ID, $this->triggeredIds)) {
            return;
        }

        if (in_array($old, ['auto-draft', 'new'])) {
            $this->triggeredIds[] = $post->ID;
            $user                 = get_user_by('id', $post->post_author);
            $this->doTrigger(['post' => $post, 'user' => $user]);
        }
    }

    /**
     * @param int      $postId
     * @param \WP_Post $post
     * @param bool     $update
     *
     * @return void
     */
    public function insertPost($postId, $post, $update)
    {
        if ($post->post_type == 'revision') {
            return;
        }

        if (in_array($post->post_status, ['trash', 'auto-draft'])) {
            return;
        }

        if ($update) {
            return;
        }

        if (in_array($post->ID, $this->triggeredIds)) {
            return;
        }

        $this->triggeredIds[] = $post->ID;
        $user                 = get_user_by('id', $post->post_author);
        $this->doTrigger(['post' => $post, 'user' => $user]);
    }
}
