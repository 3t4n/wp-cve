<?php

#namespace WilokeTest;

/**
 * Class MessageFactory
 * @package Proomoland\Helpers
 */
class MessageFactory
{
    /**
     * @param string $type
     *
     * @return AjaxMessage|NormalMessage|RestMessage
     */
    public static function factory(string $type = 'normal')
    {
        switch ($type) {
            case 'rest':
                $oInstance = new RestMessage();
                break;
            case 'ajax':
                $oInstance = new AjaxMessage();
                break;
            case 'sc':
                $oInstance = new ShortcodeMessage();
                break;
	        default:
		        $oInstance = new NormalMessage();
		        break;
        }

        if (!empty($oInstance)) {
            return $oInstance;
        }

        throw new \InvalidArgumentException('Unknown message type');
    }
}
