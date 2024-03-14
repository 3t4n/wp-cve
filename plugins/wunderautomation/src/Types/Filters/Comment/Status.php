<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Status
 */
class Status extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Comment status', 'wunderauto');
        $this->description = __('Filter comments based on post status.', 'wunderauto');
        $this->objects     = ['comment'];

        $this->operators = $this->setOperators();

        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $this->compareValues = [
            ['value' => '0', 'label' => __('Unapproved')],
            ['value' => '1', 'label' => _x('Approved', 'comment status')],
            ['value' => 'spam', 'label' => _x('Spam', 'comment status')],
            ['value' => 'trash', 'label' => _x('Trash', 'comment status')],
        ];
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $comment = $this->getObject();
        if (!($comment instanceof \WP_Comment)) {
            return false;
        }

        $actualValue = $comment->comment_approved;

        return $this->evaluateCompare($actualValue);
    }
}
