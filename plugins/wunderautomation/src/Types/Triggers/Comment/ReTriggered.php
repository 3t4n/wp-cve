<?php

namespace WunderAuto\Types\Triggers\Comment;

use WunderAuto\Types\Triggers\BaseReTrigger;

/**
 * Class ReTriggered
 */
class ReTriggered extends BaseReTrigger
{
    /**
     * @var array<int, string>
     */
    private $triggeredComments;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

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

        $this->triggeredComments = [];
    }

    /**
     * @param \WP_Comment $comment
     *
     * @return array<string, mixed>|false
     */
    public function getObjects($comment)
    {
        if (in_array($comment->comment_ID, $this->triggeredComments)) {
            return false;
        }

        $this->triggeredComments[] = $comment->comment_ID;

        // @phpstan-ignore-next-line
        $comment->isCustomerNote = get_comment_meta(
            (int)$comment->comment_ID,
            'is_customer_note',
            true
        );

        if (!($comment instanceof \WP_Comment)) {
            return false;
        }

        $postId = (int)$comment->comment_post_ID;
        $post   = get_post($postId);
        if (!($post instanceof \WP_Post)) {
            return false;
        }

        return $this->getResolverObjects(['comment' => $comment, 'post' => $post]);
    }
}
