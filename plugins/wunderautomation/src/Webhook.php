<?php

namespace WunderAuto;

use WunderAuto\Types\Internal\WorkflowState;
use WunderAuto\Types\Triggers\Webhook\BaseWebhook;
use WunderAuto\Types\Workflow;

/**
 * Class Webhook
 */
class Webhook
{
    /**
     * @var array<string, BaseWebhook>
     */
    private $webhookHandlers = [];

    /**
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction(
            'wunderautomation_register_webhook',
            $this,
            'registerWebhook',
            10,
            1
        );
        $loader->addAction(
            'wunderautomation_init_done',
            $this,
            'initDone',
            1,
            0
        );
    }

    /**
     * Handler for wunderautomation_init_done
     *
     * @return void
     */
    public function initDone()
    {
        $wpdb       = wa_get_wpdb();
        $wunderAuto = wa_wa();

        // If no workflows are using a webhook, just exit
        if (count($this->webhookHandlers) === 0) {
            return;
        }

        // Does the request URL contain our webhook slug and a char code?
        $parseStatus = $this->parseUrl();
        if (!$parseStatus->hasSlug || !$parseStatus->hasCode) {
            return;
        }

        // Ok. We're handling one of our requests, safe to set our wp_die handler
        add_filter('wp_die_handler', [$this, 'setWpDieHandler']);

        // Do we have a workflow with this code/id?
        /** @var string $sql */
        $sql = $wpdb->prepare(
            "SELECT * FROM $wpdb->postmeta WHERE meta_key ='webhook_code' and meta_value = %s",
            $parseStatus->code
        );

        /** @var \stdClass $row */
        $row = $wpdb->get_row($sql, 'OBJECT');

        if (!is_object($row)) {
            wp_die('Unkown hook code', '', ['response' => 404, 'code' => 'fail']);
            return;
        }

        // A row found
        $workflowId = (int)$row->post_id;
        $workflow   = new Workflow($workflowId);
        $state      = $workflow->getState();
        assert($state instanceof WorkflowState);
        if (!isset($state->trigger->value->code)) {
            wp_die('Unknown or invalid workflow', '', ['response' => 404, 'code' => 'fail']);
            return;
        }

        // Determine which webhook handler to use
        $workflowTriggerClass = ltrim($state->trigger->trigger, '\\');
        if (!isset($this->webhookHandlers[$workflowTriggerClass])) {
            wp_die('Unknown webhook handler class', '', ['response' => 404, 'code' => 'fail']);
            return;
        }
        $handler = $this->webhookHandlers[$workflowTriggerClass];
        $handler->handleWebhookRequest($workflow);
    }

    /**
     * Returned object will have
     * hasSlug = true if the request URL begins with our slug, false otherwise
     *
     * hasCode = true if the next element in the request URL is a code consisiting
     * of only allowed characters (a..z, 0..9, _, -).
     *
     * code = the value of the code if it's deemed valid (hasCode = true)
     *
     * @return \stdClass
     */
    protected function parseUrl()
    {
        $ret = (object)[
            'hasSlug' => false,
            'hasCode' => false,
            'code'    => '',
        ];

        $requestUri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field($_SERVER['REQUEST_URI']) : '';
        $options    = get_option('wunderauto-general');
        $slug       = isset($options['webhookslug']) ?
            $options['webhookslug'] :
            'wa-hook';
        $slug       = preg_quote($slug, "/");

        $pattern = "/$slug\/([a-z0-9_\-]*)/";
        if (preg_match($pattern, $requestUri, $match)) {
            $ret->hasSlug = true;
            $code         = $match[1];
            $ret->hasCode = true;
            $ret->code    = $code;
        }

        return $ret;
    }

    /**
     * Save an instance of each unique webhook handler class
     *
     * @param BaseWebhook $webhookHandler
     *
     * @return void
     */
    public function registerWebhook($webhookHandler)
    {
        $class = get_class($webhookHandler);
        if (!isset($this->webhookHandlers[$class])) {
            $this->webhookHandlers[$class] = $webhookHandler;
        }
    }

    /**
     * @param callable $handler
     *
     * @return string
     */
    public function setWpDieHandler($handler)
    {
        return 'wa_die_default_requests';
    }
}
