<?php
namespace FormInteg\IZCRMEF\Core\Hooks;

use FormInteg\IZCRMEF\Core\Util\Request;
use FormInteg\IZCRMEF\Admin\AdminAjax;
use FormInteg\IZCRMEF\Core\Util\Hooks;
use FilesystemIterator;

class HookService
{
    public function __construct()
    {
        $this->loadTriggersAjax();
        $this->loadAppHooks();
        $this->loadActionsHooks();
        $this->loadAdminAjax();
        Hooks::add('rest_api_init', [$this, 'loadApi']);
    }

    /**
     * Helps to register admin side ajax
     *
     * @return null
     */
    public function loadAdminAjax()
    {
        (new AdminAjax())->register();
    }

    /**
     * Helps to register App hooks
     *
     * @return null
     */
    protected function loadAppHooks()
    {
        if (Request::Check('ajax') && is_readable(IZCRMEF_PLUGIN_BASEDIR . 'includes' . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'ajax.php')) {
            include IZCRMEF_PLUGIN_BASEDIR . 'includes' . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'ajax.php';
        }
        if (is_readable(IZCRMEF_PLUGIN_BASEDIR . 'includes' . DIRECTORY_SEPARATOR . 'hooks.php')) {
            include IZCRMEF_PLUGIN_BASEDIR . 'includes' . DIRECTORY_SEPARATOR . 'hooks.php';
        }
    }

    /**
     * Helps to register Triggers ajax
     *
     * @return null
     */
    protected function loadTriggersAjax()
    {
        $this->_includeTaskHooks('Triggers');
    }

    /**
     * Helps to register integration ajax
     *
     * @return void
     */
    public function loadActionsHooks()
    {
        $this->_includeTaskHooks('Actions');
    }

    /**
     * Includes Routes and Hooks
     *
     * @param string $task_name Triggers|Actions
     *
     * @return void
     */
    private function _includeTaskHooks($task_name)
    {
        $task_dir = IZCRMEF_PLUGIN_BASEDIR . 'includes' . DIRECTORY_SEPARATOR . $task_name;
        $dirs = new FilesystemIterator($task_dir);
        foreach ($dirs as $dirInfo) {
            if ($dirInfo->isDir()) {
                $task_name = basename($dirInfo);
                $task_path = $task_dir . DIRECTORY_SEPARATOR . $task_name . DIRECTORY_SEPARATOR;
                if (is_readable($task_path . 'Routes.php') && Request::Check('ajax') && Request::Check('admin')) {
                    include $task_path . 'Routes.php';
                }
                if (is_readable($task_path . 'Hooks.php')) {
                    include $task_path . 'Hooks.php';
                }
            }
        }
    }

    /**
     * Loads API routes
     *
     * @return null
     */
    public function loadApi()
    {
        if (is_readable(IZCRMEF_PLUGIN_BASEDIR . 'includes' . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'api.php')) {
            include IZCRMEF_PLUGIN_BASEDIR . 'includes' . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'api.php';
        }
    }
}
