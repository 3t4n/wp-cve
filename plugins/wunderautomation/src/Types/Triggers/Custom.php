<?php

namespace WunderAuto\Types\Triggers;

/**
 * Class Custom
 */
class Custom extends BaseTrigger
{
    /**
     * @var object[]
     */
    public $requiredObjectTypes;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('Custom trigger', 'wunderauto');
        $this->group       = __('Advanced', 'wunderauto');
        $this->description = __(
            'This trigger is fired by another workflow via bulk/mass handling',
            'wunderauto'
        );

        $this->supportsOnlyOnce = false;

        $this->requiredObjectTypes = [
            'post'    => (object)[
                'name'        => 'Post',
                'description' => 'A post object',
                'secondary'   => [
                    'user' => (object)[
                        'name'        => 'User',
                        'description' => 'The owner / author of the post',
                    ],
                ],
            ],
            'order'   => (object)[
                'name'        => 'Order',
                'description' => 'A WooCommerce oder',
                'secondary'   => [
                    'user' => (object)[
                        'name'        => 'User',
                        'description' => 'The WordPress user that owns the order (customer)',
                    ],
                ],
            ],
            'user'    => (object)[
                'name'        => 'User',
                'description' => 'A WordPress user object',
                'secondary'   => [],
            ],
            'comment' => (object)[
                'name'        => 'Comment',
                'description' => 'A comment object',
                'secondary'   => [
                    'post' => (object)[
                        'name'        => 'Post',
                        'description' => 'The WordPress post that the comment is for',
                    ],
                ]
            ],
            'coupon'  => (object)[
                'name'        => 'Coupon',
                'description' => 'A WooCommerce coupon',
                'secondary'   => [],
            ],
        ];
    }
}
