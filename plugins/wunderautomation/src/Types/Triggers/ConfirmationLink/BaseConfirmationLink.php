<?php

namespace WunderAuto\Types\Triggers\ConfirmationLink;

use WunderAuto\Resolver;
use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Post
 */
class BaseConfirmationLink extends BaseTrigger
{
    /**
     * Create
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Confirmation Links', 'wunderauto');
        $this->description = __(
            'This trigger fires when a unique link created via the confirmationlink parameter type is clicked.',
            'wunderauto'
        );
    }

    /**
     * Register our hooks with WordPress
     *
     * @return void
     */
    public function registerHooks()
    {
        $wpdb       = wa_get_wpdb();
        $wunderAuto = wa_wa();

        // Does the URL contain our slug and a 32 char code?
        $parseStatus = $this->parseUrl();
        if (!$parseStatus->hasSlug) {
            return;
        }

        /** @var string $sql */
        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wa_confirmationlinks WHERE code = %s",
            $parseStatus->code
        );

        // Does the code exist in the DB
        /** @var \stdClass $row */
        $row = $wpdb->get_row($sql, OBJECT);

        if (!is_object($row)) {
            $this->printErrorMessage(null, __('Unknown link code', 'wunderauto'));
        }

        // A row found, bootstrap the resolver.
        $resolver = $wunderAuto->createResolver([]);
        $objects  = json_decode($row->args);
        foreach ($objects as $object) {
            $name = isset($object->name) ? $object->name : $object->type;
            $resolver->addObjectById($object->type, $name, $object->id);
        }
        $resolver->addObject('link', 'link', $row);
        $type = (string)$resolver->getFirstObjectType();
        if (empty($type)) {
            $this->printErrorMessage(null, __('Invalid link code', 'wunderauto'));
        }

        // Check if this link is expired
        $maxClicks   = $row->click_limit > -1 && $row->clicked >= $row->click_limit;
        $timeExpired = (int)$row->expires > 0 && (int)$row->expires < time();
        if ($maxClicks || $timeExpired) {
            $this->printErrorMessage(
                $resolver,
                __('This link has expired', 'wunderauto'),
                $row->on_expired
            );
        }

        /** @var string $sql */
        $sql = $wpdb->prepare(
            "UPDATE {$wpdb->prefix}wa_confirmationlinks SET clicked = clicked +1 WHERE id=%d",
            $row->id
        );
        $wpdb->query($sql);

        $className = '\\' . get_class($this);
        $scheduler = $wunderAuto->getScheduler();
        $scheduler->doTrigger($className, $resolver->getObjects(), []);

        if (strlen(trim($row->on_success)) > 0) {
            $url = $this->resolveUrlParameters($type, $row->on_success, $resolver);
            wp_redirect($url);
            exit;
        }

        $url         = site_url();
        $firstObject = $resolver->getFirstObjectByType($type);
        if ($firstObject !== false) {
            switch ($type) {
                case 'user':
                    $url = get_author_posts_url((int)$resolver->getObjectId($firstObject));
                    break;
                case 'comment':
                    $url = get_comment_link((int)$resolver->getObjectId($firstObject));
                    break;
                default:
                    $url = get_permalink((int)$resolver->getObjectId($firstObject));
                    break;
            }
        }

        if (is_string($url)) {
            wp_redirect($url);
        }
        exit;
    }

    /**
     * If the URL contains our slug + a 32 char code, return the code
     * Otherwise return an empty string
     *
     * @return \stdClass
     */
    private function parseUrl()
    {
        $ret = (object)[
            'hasSlug' => false,
            'hasCode' => false,
            'code'    => '',
        ];

        $rawUri     = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $requestUri = sanitize_text_field($rawUri);
        $options    = get_option('wunderauto-general');
        $slug       = isset($options['confirmationslug']) ?
            $options['confirmationslug'] :
            'wa-confirm';
        $slug       = preg_quote($slug, "/");

        $pattern = "/$slug\/([a-f0-9]*)/";
        if (preg_match($pattern, $requestUri, $match)) {
            $ret->hasSlug = true;
            $code         = $match[1];
            if (strlen($code) === 32) {
                $ret->hasCode = true;
                $ret->code    = $code;
            }
        }

        return $ret;
    }

    /**
     * Print a WP error message and exit
     *
     * @param Resolver|null $resolver
     * @param string        $message
     * @param string        $redirect
     *
     * @return void
     */
    protected function printErrorMessage($resolver, $message, $redirect = '')
    {
        $message .= '<br><br>';

        if (!is_null($resolver) && strlen(trim($redirect)) > 0) {
            $message .= sprintf(
                '<a href="%s">%s</a>',
                trim($redirect),
                trim($redirect)
            );
        } else {
            $message .= sprintf(
                '<a href="%s">%s</a>',
                home_url(),
                home_url()
            );
        }

        wp_die($message);
    }

    /**
     * Search replace special placehlders for the redirect URL
     *
     * @param string   $type
     * @param string   $url
     * @param Resolver $resolver
     *
     * @return string
     */
    private function resolveUrlParameters($type, $url, $resolver)
    {
        $replace = [];
        $object  = $resolver->getObject($type);
        if (is_null($object)) {
            return $url;
        }

        $id = $resolver->getObjectId($object);
        if (is_null($id)) {
            return $url;
        }

        switch ($type) {
            case 'post':
                $replace = ['[POSTID]' => $id];
                break;
            case 'order':
                /** @var \WC_Order $order */
                $order        = $resolver->getObject('order');
                $billingEmail = $order->get_billing_email();
                $replace      = [
                    '[ORDERID]'     => $id,
                    '[POSTID]'      => $id,
                    '[ORDERSTATUS]' => "orderid=$id" .
                        "&_wpnonce=" . wp_create_nonce('woocommerce-order_tracking') .
                        "&order_email=$billingEmail",
                ];
                break;
            case 'user':
                $replace = ['[USERID]' => $id];
                break;
            case 'comment':
                $post = $resolver->getObject('post');
                if ($post instanceof \WP_Post) {
                    $replace = [
                        '[COMMENTID]' => $id,
                        '[POSTID]'    => $post->ID,
                    ];
                }
                break;
        }
        foreach ($replace as $needle => $replaceWith) {
            $url = str_replace($needle, (string)$replaceWith, $url);
        }

        return $url;
    }
}
