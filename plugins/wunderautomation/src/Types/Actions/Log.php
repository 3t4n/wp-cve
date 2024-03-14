<?php

namespace WunderAuto\Types\Actions;

/**
 * Class Log
 */
class Log extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Write to a log file', 'wunderauto');
        $this->description = __('Write to a log file', 'wunderauto');
        $this->group       = 'Advanced';
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $path = $this->getResolved('value.path');
        $data = $this->getResolved('value.data');

        $data = date('Y-m-d H:i:s') . ' ' . $data;

        if (substr($path, 0, 1) !== '/') {
            $path = trailingslashit(WP_CONTENT_DIR) . $path;
        }

        file_put_contents($path, $data . PHP_EOL, FILE_APPEND | LOCK_EX);

        return true;
    }
}
