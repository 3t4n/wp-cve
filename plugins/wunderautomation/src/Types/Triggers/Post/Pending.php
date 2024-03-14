<?php

namespace WunderAuto\Types\Triggers\Post;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Pending
 */
class Pending extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('Post pending', 'wunderauto');
        $this->group       = __('Posts', 'wunderauto');
        $this->description = __(
            'This trigger fires when the status of a post object (post, page, custom post type etc.) ' .
            'is changed from any other status to pending.',
            'wunderauto'
        );

        $this->addProvidedObject(
            'post',
            'post',
            __('The post that was set to pending', 'wunderauto'),
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

        if (in_array($old, ['pending'])) {
            return;
        }

        if (in_array($new, ['pending'])) {
            $this->triggeredIds[] = $post->ID;
            $user                 = get_user_by('id', $post->post_author);
            $this->doTrigger(['post' => $post, 'user' => $user]);
        }
    }
}
