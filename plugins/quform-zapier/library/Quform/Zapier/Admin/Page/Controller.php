<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Admin_Page_Controller
{
    /**
     * The current page
     *
     * @var Quform_Admin_Page
     */
    protected $page;

    /**
     * @var Quform_Zapier_Admin_Page_Factory
     */
    protected $pageFactory;

    /**
     * @param Quform_Zapier_Admin_Page_Factory $pageFactory
     */
    public function __construct(Quform_Zapier_Admin_Page_Factory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * Process the current page
     *
     * @param object $screen The current screen object
     */
    public function process($screen)
    {
        if ($this->isCorePage($screen->id)) {
            $this->page = $this->pageFactory->create($this->screenIdToPageName($screen->id));
            $this->page->bootstrap()->process();
        }
    }

    /**
     * Enqueue the page assets
     */
    public function enqueueAssets()
    {
        if ($this->page instanceof Quform_Admin_Page) {
            $this->page->enqueueAssets();
        }
    }

    /**
     * Override the admin page title
     *
     * @param   string  $adminTitle
     * @return  string
     */
    public function setAdminTitle($adminTitle)
    {
        if ($this->page instanceof Quform_Admin_Page) {
            return $this->page->setAdminTitle($adminTitle);
        }

        return $adminTitle;
    }

    /**
     * Set a custom body class
     *
     * @param   string  $classes
     * @return  string
     */
    public function addBodyClass($classes)
    {
        if ($this->page instanceof Quform_Admin_Page) {
            $classes .= sprintf(' %s', sanitize_title(get_class($this->page)));
        }

        return $classes;
    }

    /**
     * Render the page
     */
    public function display()
    {
        echo $this->page->display();
    }

    /**
     * Add the WordPress administration menu pages
     */
    public function createMenus($position)
    {
        if ($position == 25) {
            add_submenu_page(
                'quform.dashboard',
                __('Zapier', 'quform-zapier'),
                __('Zapier', 'quform-zapier'),
                'quform_zapier_list_integrations',
                'quform.zapier',
                array($this, 'display')
            );
        }
    }

    /**
     * Get the menu icon color
     *
     * @param   string  The current menu icon hex color
     * @return  string
     */
    public function getMenuIconColor($color)
    {
        if (Quform::get($_GET, 'page') == 'quform.zapier') {
            $color = '#ffffff';
        }

        return $color;
    }

    /**
     * Convert the given screen ID into a page name
     *
     * @param   string  $id  The screen ID, e.g. toplevel_page_quform.forms
     * @return  string       The last part of the page class e.g. Forms
     */
    protected function screenIdToPageName($id)
    {
        $name = preg_replace('/^.+_page_quform\.(.+)$/', '$1', $id);

        return Quform::studlyCase($name);
    }

    /**
     * Is the given screen ID one of the plugin core pages
     *
     * @param   string  $id  The screen ID, e.g. toplevel_page_quform.forms
     * @return  bool
     */
    protected function isCorePage($id)
    {
        return (bool) preg_match('/_page_quform\.(zapier)$/', $id);
    }

    /**
     * Get the subpage query var
     *
     * @return string|null
     */
    protected function getSubpage()
    {
        return Quform::get($_GET, 'sp');
    }

    /**
     * Set the Material Design Icons prefix to 'mdi' for Quform versions 2.17.0 and earlier
     *
     * @param   string  $prefix
     * @return  string
     */
    public function mdiIconPrefix($prefix)
    {
        if (version_compare(QUFORM_VERSION, '2.17.0', '<=')) {
            $prefix = 'mdi';
        }

        return $prefix;
    }
}
