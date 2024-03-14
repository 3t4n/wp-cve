<?php

namespace Premmerce\WoocommerceMulticurrency\Model;

/**
 * Class Result
 *
 * Represents result of the Model actions like adding new currency, update rates etc.
 *
 * @package Premmerce\WoocommerceMulticurrency\Model
 */
class Result
{
    /**
     * Action name, for which this class represents result
     *
     * @var string
     *
     * @todo: this property needs getter
     */
    private $action;

    /**
     * Messages to display in admin area. Each message includes 'message' and 'type' parts.
     * Message type can be 'info', 'success', 'warning' or 'error'
     *
     * @see AdminNotifier
     *
     * @var array
     */
    private $messages = array();

    /**
     * @var bool
     */
    private $success;

    /**
     * Result constructor.
     *
     * @param string    $action
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * @param $messageText
     * @param $messageType
     */
    public function setMessage($messageText, $messageType)
    {
        $newMessage = array(
            'message' => $messageText,
            'type'    => $messageType
        );

        $this->messages[] = apply_filters('premmerce_multicurrency_set_result_message', $newMessage, $this);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return apply_filters('premmerce_multicurrency_get_result_messages', $this->messages, $this);
    }

    /**
     * @param bool $result
     */
    public function setSuccess($result)
    {
        $this->success = apply_filters('premmerce_multicurrency_set_result_success', boolval($result), $this);
    }

    /**
     * @return bool
     */
    public function getSuccess()
    {
        return apply_filters('premmerce_multicurrency_get_result_success', $this->success, $this);
    }
}
