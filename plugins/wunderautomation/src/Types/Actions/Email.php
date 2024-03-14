<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class Email
 */
class Email extends EmailBaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Send a text email ', 'wunderauto');
        $this->description = __('Send a plain text email', 'wunderauto');
        $this->group       = 'Email';

        $this->docLink = 'https://www.wundermatics.com/docs/built-in-actions/#0-toc-title';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'convertLineBreaks', 'bool');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $this->readConfig();
        $convertLineBreaks = $this->getResolved('value.convertLineBreaks');

        if (!$this->to && !$this->bcc) {
            return false;
        }

        if ($convertLineBreaks) {
            $this->body = str_replace("\n", "<br>\n", $this->body);
        }

        $this->sendEmail();

        return true;
    }
}
