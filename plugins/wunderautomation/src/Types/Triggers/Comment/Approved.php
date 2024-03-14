<?php

namespace WunderAuto\Types\Triggers\Comment;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Approved
 */
class Approved extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('Approved', 'wunderauto');
        $this->group       = __('Comments', 'wunderauto');
        $this->description = __(
            'Fires when a comment is approved',
            'wunderauto'
        );

        $this->addProvidedObject(
            'comment',
            'comment',
            __('The comment', 'wunderauto'),
            true
        );
        $this->addProvidedObject(
            'post',
            'post',
            __('The commented post', 'wunderauto'),
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
            add_action('transition_comment_status', [$this, 'commentTransitionStatus'], 20, 3);
        }
        $this->registered = true;
    }

    /**
     * Handler for the transition_comment_status action
     *
     * @param string      $new
     * @param string      $old
     * @param \WP_Comment $comment
     *
     * @return void
     */
    public function commentTransitionStatus($new, $old, $comment)
    {
        if (in_array($new, ['approved'])) {
            $postId = (int)$comment->comment_post_ID;
            $post   = get_post($postId);
            if ($post instanceof \WP_Post) {
                $this->doTrigger(['comment' => $comment, 'post' => $post]);
            }
        }
    }
}
