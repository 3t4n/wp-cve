<?php
/**
 * Page Base class
 */

namespace FDSUS\Controller\Admin;

use FDSUS\Model\Data;
use FDSUS\Model\Sheet as SheetModel;

class PageBase
{
    /** @var Data */
    protected $data;

    /** @var string */
    protected $menuSlug = '';

    /** @var string */
    protected $currentScreen;

    /** @var string */
    protected $hiddenFieldName;

    /** @var string */
    protected $hiddenFieldValue;

    public function __construct()
    {
        $this->data = new Data();
        $this->currentScreen = SheetModel::POST_TYPE . '_page_' . $this->menuSlug;
        $this->hiddenFieldName = 'fdsus_submit_screen';
        $this->hiddenFieldValue = $this->currentScreen;
    }

    /**
     * Is this the current screen for the page
     *
     * @param $currentScreen
     *
     * @return bool
     */
    protected function isCurrentScreen($currentScreen = false)
    {
        if (!$currentScreen) {
            $currentScreen = get_current_screen();
        }

        return $currentScreen->id === $this->currentScreen;
    }
}
