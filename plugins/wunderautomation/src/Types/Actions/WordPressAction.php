<?php

namespace WunderAuto\Types\Actions;

/**
 * Class WordPressAction
 */
class WordPressAction extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('WordPress action', 'wunderauto');
        $this->description = __('Fire a WordPress action to trigger custom functionality', 'wunderauto');
        $this->group       = 'WordPress';
    }
}
