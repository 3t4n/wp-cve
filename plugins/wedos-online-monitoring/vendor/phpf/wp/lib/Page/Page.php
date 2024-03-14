<?php
namespace PHPF\WP\Page;

/**
 * Base class for pages
 *
 * @author  Petr Stastny <petr@stastny.eu>
 * @license GPLv3
 */
abstract class Page
{
    /**
     * Register admin page and its actions
     *
     * @return void
     */
    public static function addPage()
    {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', function () { static::registerAdmin(); });
        static::registerActions();
    }


    /**
     * Register admin page
     *
     * @return void
     */
    protected static function registerAdmin()
    {
        die('Page::registerAdmin() not redefined');
    }


    /**
     * Create object instance and render
     *
     * @return void
     */
    protected static function renderStatic()
    {
        $page = new static;
        $page->render();
    }


    /**
     * Render page content
     *
     * @return void
     */
    abstract protected function render();


    /**
     * Register submit actions
     *
     * @return void
     */
    protected static function registerActions()
    {
        // nothing by default
        // to be implemented by derived class
    }


    /**
     * Register POST submit action
     *
     * @return void
     */
    protected static function registerPostAction($action)
    {
        add_action('admin_post_'.$action, function () { static::execStatic(); });
    }


    /**
     * Main method for executing actions
     *
     * @return void
     */
    protected static function execStatic()
    {
        if (empty($_POST['action']) || !is_string($_POST['action'])) {
            return;
        }

        // CSRF protection
        check_admin_referer($_REQUEST['action']);

        $page = new static;

        $methodName = 'exec'.ucfirst($_REQUEST['action']);

        if (method_exists($page, $methodName)) {
            $page->$methodName();
        }
    }
}
