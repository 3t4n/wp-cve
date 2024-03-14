<?php

namespace WunderAuto\Types\Triggers\ConfirmationLink;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Post
 */
class Post extends BaseConfirmationLink
{
    /**
     * Create
     */
    public function __construct()
    {
        parent::__construct();

        $this->title = __('Confirmation: Post', 'wunderauto');
        $this->addProvidedObject(
            'post',
            'post',
            __('The post associated with the clicked link', 'wunderauto'),
            true
        );
        $this->addProvidedObject(
            'user',
            'user',
            __('The creator of the post', 'wunderauto'),
            true
        );
        $this->addProvidedObject(
            'link',
            'link',
            __('The clicked link', 'wunderauto'),
            false
        );
    }
}
