<?php

namespace WunderAuto\Types\Triggers\Comment;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Submitted
 */
class Submitted extends BaseTrigger
{
    /**
     * @var bool
     */
    private $isCustomerNote = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('Submitted', 'wunderauto');
        $this->group       = __('Comments', 'wunderauto');
        $this->description = __(
            'Fires when a new comment is submitted',
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
            add_action('wp_insert_comment', [$this, 'commentPost'], 20, 2);
            add_filter('woocommerce_new_order_note_data', [$this, 'newOrderNote'], 20, 2);
        }
        $this->registered = true;
    }

    /**
     * This is a bit of trickery specifically for WooCommerce
     * order notes. When the wp_insert_comment action fires, the
     * metadata for private / customer isn't written to the db yet
     * So we catch this action a bit earlier, and store the order note
     * meta
     *
     * @param array<string, string> $postData
     * @param array<string, bool>   $orderNote
     *
     * @return array<string, string>
     */
    public function newOrderNote($postData, $orderNote)
    {
        $this->isCustomerNote = $orderNote['is_customer_note'];
        return $postData;
    }

    /**
     * Handle new comment
     *
     * @param int    $id
     * @param string $approved
     *
     * @return void
     */
    public function commentPost($id, $approved)
    {
        $comment = get_comment($id);
        if (!($comment instanceof \WP_Comment)) {
            return;
        }

        $comment->isCustomerNote = $this->isCustomerNote; // @phpstan-ignore-line
        $postId                  = (int)$comment->comment_post_ID;
        $post                    = get_post($postId);

        $this->doTrigger(['comment' => $comment, 'post' => $post]);
    }
}
