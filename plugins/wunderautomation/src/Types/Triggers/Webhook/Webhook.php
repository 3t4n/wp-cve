<?php

namespace WunderAuto\Types\Triggers\Webhook;

/**
 * Class Webhook
 */
class Webhook extends BaseWebhook
{
    /**
     * @var string
     */
    public $urlBase;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group            = __('Advanced', 'wunderauto');
        $this->title            = __('Webhook', 'wunderauto');
        $this->description      = __(
            'This trigger fires when a request is made to the unique URL of this webhook',
            'wunderauto'
        );
        $this->supportsOnlyOnce = false;
        $this->addProvidedObject(
            'webhook',
            'webhook',
            'Transient object to make webhook parameters accessible to filters and parameters ',
            false
        );

        $code               = substr(md5(uniqid() . microtime(true)), 0, 12);
        $user               = substr(md5(uniqid() . microtime(true)), 0, 8);
        $pass               = substr(md5(uniqid() . microtime(true)), 0, 12);
        $this->defaultValue = (object)[
            'code'          => $code,
            'useBasicAuth'  => true,
            'basicAuthUser' => $user,
            'basicAuthPass' => $pass,
        ];

        $this->urlBase     = $this->getLink('');
        $this->objectTypes = apply_filters(
            'wunderauto/trigger/objectTypes/webhook',
            [
                'post'    => 'Post',
                'order'   => 'Order',
                'user'    => 'User',
                'comment' => 'Comment',
            ]
        );
    }
}
