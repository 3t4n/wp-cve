<?php

namespace WunderAuto\Types\Triggers\Webhook;

use WunderAuto\Types\Internal\WorkflowState;
use WunderAuto\Types\Triggers\BaseTrigger;
use WunderAuto\Types\Workflow;

/**
 * Class BaseWebhook
 */
class BaseWebhook extends BaseTrigger
{
    /**
     * @var array<string, \stdClass>
     */
    public $objectTypes = [];

    /**
     * @var object
     */
    public $defaultValue;

    /**
     * @var string
     */
    public $inputStream = 'php://input';

    /**
     * @var string
     */
    public $payload;

    /**
     * Create
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param int           $postId
     * @param WorkflowState $workflowSettings
     *
     * @return void
     */
    public function saveWunderAutomationWorkflow($postId, $workflowSettings)
    {
        $code = isset($workflowSettings->trigger->value->code) ? $workflowSettings->trigger->value->code : null;
        if ($code) {
            update_post_meta($postId, 'webhook_code', $code);
        }
    }

    /**
     * Register our hooks with WordPress
     *
     * @return void
     */
    public function registerHooks()
    {
        do_action('wunderautomation_register_webhook', $this);
    }

    /**
     * Called by the webhook manager if it has detected
     * a webhook that should be handled by this class
     *
     * @param Workflow $workflow
     *
     * @return void
     */
    public function handleWebhookRequest($workflow)
    {
        $wunderAuto = wa_wa();

        $this->payload = (string)file_get_contents($this->inputStream);
        /** @var WorkflowState $state */
        $state = $workflow->getState();

        // Check authentication
        if (!$this->checkAuthentication($state)) {
            wp_die('Authentication failed', '', ['response' => 401, 'code' => 'fail']);
            return;
        }

        // Bootstrap the resolver
        $resolver = $wunderAuto->createResolver([]);

        // Add the Webhook object
        $resolver->addObject('webhook', 'webhook', $_REQUEST);

        // Add objects from the request parameters
        $success = $this->parseRequest($state, $resolver);
        if (!$success) {
            wp_die('All required objects not found', '', ['response' => 400, 'code' => 'fail']);
            return;
        }
        $resolver->maybeAddCurrentUser();

        $workflow->executeSteps($resolver);
        wp_die('success', 'ok', ['response' => 200, 'code' => 'ok']);
        return;
    }

    /**
     * @param WorkflowState $state
     *
     * @return bool
     */
    protected function checkAuthentication($state)
    {
        $useBasicAuth         = isset($state->trigger->value->useBasicAuth) ?
            (bool)$state->trigger->value->useBasicAuth :
            true;
        $useHeaderKey         = isset($state->trigger->value->useHeaderKey) ?
            (bool)$state->trigger->value->useHeaderKey :
            false;
        $useHMACSignedPayload = isset($state->trigger->value->useHMACSignedPayload) ?
            (bool)$state->trigger->value->useHMACSignedPayload :
            false;

        if ($useBasicAuth) {
            $basicAuthUser = isset($state->trigger->value->basicAuthUser) ?
                trim($state->trigger->value->basicAuthUser) :
                uniqid() . microtime(true);
            $basicAuthPass = isset($state->trigger->value->basicAuthPass) ?
                trim($state->trigger->value->basicAuthPass) :
                uniqid() . microtime(true);

            // If no auth was sent, return false
            if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
                return false;
            }

            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $correct    = 'Basic ' . base64_encode("$basicAuthUser:$basicAuthPass");
            if (!hash_equals($correct, $authHeader)) {
                return false;
            }
        }

        if ($useHeaderKey) {
            $headerAPIKey    = trim($state->trigger->value->headerAPIKey);
            $headerAPISecret = trim($state->trigger->value->headerAPISecret);
            $header          = $this->formatHttpHeader($headerAPIKey);
            if (!isset($_SERVER[$header])) {
                return false;
            }

            $passedSecret = $_SERVER[$header];
            if (!hash_equals($headerAPISecret, $passedSecret)) {
                return false;
            }
        }

        if ($useHMACSignedPayload) {
            $HMACSignatureHeader = trim($state->trigger->value->HMACSignatureHeader);
            $HMACSignatureSecret = trim($state->trigger->value->HMACSignatureSecret);
            $header              = $this->formatHttpHeader($HMACSignatureHeader);
            if (!isset($_SERVER[$header])) {
                return false;
            }

            $passedSecret = $_SERVER[$header];
            $correct      = hash_hmac('sha1', $this->payload, $HMACSignatureSecret);
            if (!hash_equals($correct, $passedSecret)) {
                return false;
            }
        }

        // All configured auth methods passed. Return true.
        return true;
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected function formatHttpHeader($str)
    {
        return str_replace('-', '_', 'HTTP_' . strtoupper($str));
    }

    /**
     * Parse the request and populate the resolver
     * Can (should) be overridden by webhooks that handle
     * non genereic webhoks, like MailChimp
     *
     * @param WorkflowState        $state
     * @param \WunderAuto\Resolver $resolver
     *
     * @return bool|void
     */
    protected function parseRequest($state, $resolver)
    {
        $ret = true;
        $this->parseJson();

        $objects = isset($state->trigger->value->objects) ? $state->trigger->value->objects : [];
        foreach ($objects as $object) {
            $id      = isset($_REQUEST[$object->parameter]) ?
                sanitize_text_field($_REQUEST[$object->parameter]) :
                -1;
            $success = $id !== -1 ?
                $resolver->addObjectById($object->type, $object->name, $id) :
                false;

            if (!$success && $object->required === true) {
                $ret = false;
            }
        }

        return $ret;
    }

    /**
     * If the request has content type = application/json we'll
     * parse the passed json body and assign it to the $_REQUEST
     * object
     *
     * @return void
     */
    protected function parseJson()
    {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : '';
        if (empty($contentType)) {
            return;
        }
        if (strpos('application/json', $contentType) !== false) {
            $obj = json_decode($this->payload);
            foreach ($obj as $key => $value) {
                $_REQUEST[$key] = $value;
            }
        }
    }

    /**
     * Get the webhook link
     *
     * @param string $code
     *
     * @return string
     */
    protected function getLink($code)
    {
        $options = get_option('wunderauto-general');
        $slug    = isset($options['webhookslug']) ?
            $options['webhookslug'] :
            'wa-hook';

        return site_url() . '/' . $slug . '/' . $code;
    }
}
