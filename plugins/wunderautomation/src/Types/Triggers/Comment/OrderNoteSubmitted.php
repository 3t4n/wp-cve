<?php

namespace WunderAuto\Types\Triggers\Comment;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class OrderNoteSubmitted
 */
class OrderNoteSubmitted extends BaseTrigger
{
    /**
     * @var bool
     */
    private $isOrderNote = false;

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

        $this->title       = __('Order Note submitted', 'wunderauto');
        $this->group       = __('Orders', 'wunderauto');
        $this->description = __(
            'Fires when a WooCommerce order note is submitted',
            'wunderauto'
        );

        $this->addProvidedObject(
            'comment',
            'comment',
            __('The order note', 'wunderauto'),
            true
        );
        $this->addProvidedObject(
            'order',
            'order',
            __('The WooCommerce order', 'wunderauto'),
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
     * @param array<string, mixed>  $orderNote
     *
     * @return array<string, string>
     */
    public function newOrderNote($postData, $orderNote)
    {
        $this->isOrderNote    = true;
        $this->isCustomerNote = (bool)$orderNote['is_customer_note'];
        return $postData;
    }

    /**
     * Handle new order note.
     *
     * @param int    $id
     * @param string $approved
     *
     * @return void
     */
    public function commentPost($id, $approved)
    {
        if (!$this->isOrderNote) {
            return;
        }

        $comment = get_comment($id);
        if (!($comment instanceof \WP_Comment)) {
            return;
        }

        $comment->isCustomerNote = $this->isCustomerNote;    // @phpstan-ignore-line
        $orderId                 = $comment->comment_post_ID;
        $order                   = wc_get_order($orderId);
        if (!($order instanceof \WC_Order)) {
            return;
        }

        $this->doTrigger(['comment' => $comment, 'order' => $order]);
    }
}
