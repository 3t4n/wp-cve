<?php namespace Premmerce\UsersRoles\Admin;

use Behat\Transliterator\Transliterator;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\UsersRoles\Models\AdminModel;

/**
 * Class Admin
 * @package Premmerce\UsersRoles\Admin
 */
class Admin
{
    /**
     * List with default roles
     */
    const DEFAULT_ROLES = array(
        'administrator',
        'editor',
        'author',
        'contributor',
        'subscriber',
    );

    const MENU_SLUG = 'premmerce-users-roles';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var AdminModel
     */
    private $model;

    /**
     * List of all role capabilities
     *
     * @var array
     */
    public $capabilities = array();

    /**
     * @var string
     */
    private $flashKey;

    /**
     * @var AdminNotifier
     */
    private $notifier;

    /**
     * Admin constructor.
     *
     * Register menu items and handlers
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->flashKey    = 'premmerce_user_roles_' . get_current_user_id() . '_';
        $this->fileManager = $fileManager;

        $this->model    = new AdminModel();
        $this->notifier = new AdminNotifier();

        $this->capabilities = get_role('administrator')->capabilities;
        ksort($this->capabilities);

        add_action('admin_menu', array($this, 'addMenuPage'));


        add_action('wp_ajax_getRoleCapabilities', array($this, 'getRoleCapabilities'));

        add_action('admin_post_premmerce_create_role', array($this, 'createRole'));
        add_action('admin_post_premmerce_update_role', array($this, 'updateRole'));
        add_action('admin_post_premmerce_delete_role', array($this, 'deleteRole'));
    }

    /**
     * Add submenu to premmerce menu page
     *
     * @return false|string
     */
    public function addMenuPage()
    {
        global $admin_page_hooks;

        $premmerceMenuExists = isset($admin_page_hooks['premmerce']);


        if (! $premmerceMenuExists) {
            $svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="16" style="fill:#82878c" viewBox="0 0 20 16"><g id="Rectangle_7"> <path d="M17.8,4l-0.5,1C15.8,7.3,14.4,8,14,8c0,0,0,0,0,0H8h0V4.3C8,4.1,8.1,4,8.3,4H17.8 M4,0H1C0.4,0,0,0.4,0,1c0,0.6,0.4,1,1,1 h1.7C2.9,2,3,2.1,3,2.3V12c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1V1C5,0.4,4.6,0,4,0L4,0z M18,2H7.3C6.6,2,6,2.6,6,3.3V12 c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1v-1.7C8,10.1,8.1,10,8.3,10H14c1.1,0,3.2-1.1,5-4l0.7-1.4C20,4,20,3.2,19.5,2.6 C19.1,2.2,18.6,2,18,2L18,2z M14,11h-4c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4c0.6,0,1-0.4,1-1C15,11.4,14.6,11,14,11L14,11z M14,14 c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C15,14.4,14.6,14,14,14L14,14z M4,14c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1 c0.6,0,1-0.4,1-1C5,14.4,4.6,14,4,14L4,14z"/></g></svg>';
            $svg = 'data:image/svg+xml;base64,' . base64_encode($svg);

            add_menu_page(
                'Premmerce',
                'Premmerce',
                'manage_options',
                'premmerce',
                '',
                $svg
            );
        }

        $page = add_submenu_page(
            'premmerce',
            __('User Roles', 'premmerce-users-roles'),
            __('User Roles', 'premmerce-users-roles'),
            'manage_options',
            Admin::MENU_SLUG,
            array($this, 'controller')
        );

        if (! $premmerceMenuExists) {
            global $submenu;
            unset($submenu['premmerce'][0]);
        }

        return $page;
    }

    /**
     * Control all actions in module
     */
    public function controller()
    {
        $this->registerAssets();

        if (isset($_GET['edit_role'])) {
            $this->controllerEditRole($_GET['edit_role']);
        } else {
            $this->controllerList();
        }
    }

    /**
     * Control edit role
     *
     * @param string $editRole
     */
    public function controllerEditRole($editRole)
    {
        global $wp_roles;

        $errorMessage = null;

        if (! empty($editRole) && array_key_exists($editRole, $wp_roles->roles)) {
            $editRoleArr = $wp_roles->roles[$editRole];

            $this->fileManager->includeTemplate('admin/edit.php', array(
                'curKey'          => $editRole,
                'backUrl'         => $this->getListUrl(),
                'curName'         => $editRoleArr['name'],
                'curCapabilities' => $editRoleArr['capabilities'],
                'roles'           => $wp_roles->roles,
                'capabilities'    => $this->capabilities,
                'dName'           => $this->flashGet('display_name'),
                'deleteUrl'       => str_replace('__role__', $editRole, $this->getDeleteUrl()),
            ));
        } else {
            $this->notifier->push(__(
                'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?',
                'premmerce-users-roles'
            ), 'error');
        }
    }

    /**
     * Control roles list
     */
    private function controllerList()
    {
        global $wp_roles;

        $current = isset($_GET['tab']) ? $_GET['tab'] : 'list';

        $tabs['list'] = __('Roles list', 'premmerce-users-roles');

        if (function_exists('premmerce_re_fs')) {
            $tabs['contact'] = __('Contact Us', 'premmerce-users-roles');
            if (premmerce_re_fs()->is_registered()) {
                $tabs['account'] = __('Account', 'premmerce-users-roles');
            }
        }

        $this->fileManager->includeTemplate('admin/main.php', array(
            'current'      => $current,
            'tabs'         => $tabs,
            'roles'        => $wp_roles->roles,
            'defaultRoles' => Admin::DEFAULT_ROLES,
            'editUrl'      => $this->getEditUrl(),
            'deleteUrl'    => $this->getDeleteUrl(),
            'dName'        => $this->flashGet('display_name'),
        ));
    }

    /**
     * Create new role and add capabilities for role
     */
    public function createRole()
    {
        $this->authorizeRequest();

        $displayName = $_POST['display_name'];
        $inheritRole = $_POST['role'];

        $roleName = $this->transliterate($displayName);
        $roleName = $this->checkRoleName($roleName);

        if ($this->validation($roleName, $displayName, true)) {
            $newCapabilities = array();

            if ($inheritRole != 'null') {
                $role = $this->model->getRoles($inheritRole);

                if (! empty($role)) {
                    $newCapabilities = $role['capabilities'];
                }
            }

            add_role($roleName, $displayName, $newCapabilities);
        }

        wp_redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Delete role data
     */
    public function deleteRole()
    {
        $this->authorizeRequest();

        $redirectUrl   = $this->getListUrl();

        if (isset($_GET['delete_role']) && ! empty($_GET['delete_role'])) {
            $roleName = $_GET['delete_role'];

            if (! in_array($roleName, Admin::DEFAULT_ROLES)) {
                remove_role($roleName);

                $this->notifier->flash(__('Role deleted. ', 'premmerce-users-roles'));
            } else {
                $redirectUrl = $this->getEditUrl() . $roleName;

                $this->notifier->flash(
                    __('Standard roles cannot be deleted or modified.', 'premmerce-users-roles'),
                    'error'
                );
            }
        }

        wp_redirect($redirectUrl);
    }

    /**
     * Update role data
     */
    public function updateRole()
    {
        $this->authorizeRequest();

        $displayName = $_POST['display_name'];
        $roleName    = $_POST['role_name'];

        $capabilities = array();
        if (isset($_POST['capabilities'])) {
            $capabilities = $_POST['capabilities'];
        }

        if ($this->validation($roleName, $displayName)) {
            $role = $this->model->getRoles($roleName);

            if (! empty($role)) {
                $newCapabilities = array();
                foreach ($capabilities as $c) {
                    $newCapabilities[$c] = true;
                }

                $role['name']         = $displayName;
                $role['capabilities'] = $newCapabilities;

                $this->setDBRole($roleName, $role);

                $this->notifier->flash(__('Role updated.', 'premmerce-users-roles'), 'success');
            }
        }

        wp_redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Check nonce and referer from admin url
     *
     * @return void
     */
    protected function authorizeRequest()
    {
        $redirectUrl   = add_query_arg('edit_role', isset($_REQUEST[ 'role_name' ]) ? $_REQUEST[ 'role_name' ]: false, $this->getListUrl()); // if edit role - redirect to edit page
        $referer       = wp_get_referer();
        $notAuthorized = ! isset($_REQUEST['_premmerce_edit_user_role_nonce']) ||
            false === wp_verify_nonce($_REQUEST['_premmerce_edit_user_role_nonce'], 'premmerce-edit-user-role') ||
            false === strpos($redirectUrl, $referer); // check nonce and referer from admin url

        if ($notAuthorized) {
            $this->notifier->flash(__('Sorry, You are not authorized to do this action.', 'premmerce-users-roles'), 'error');
            wp_redirect($redirectUrl);
            exit;
        }
    }

    /**
     * Check entered data to valid
     *
     * @param string $roleName
     * @param string $displayName
     *
     * @param bool $create
     *
     * @return bool
     */
    protected function validation($roleName, $displayName, $create = false)
    {
        global $wp_roles;

        if (in_array($roleName, Admin::DEFAULT_ROLES)) {
            $this->notifier->flash(
                __('Standard roles cannot be deleted or modified.', 'premmerce-users-roles'),
                'error'
            );

            return false;
        }

        if (empty($displayName)) {
            $this->notifier->flash(__('Name is empty. Please enter Name.', 'premmerce-users-roles'), 'error');

            return false;
        }

        $nameMessage = __('Name already exist. Choose another Name.', 'premmerce-users-roles');

        if ($create) {
            if (array_search($displayName, $wp_roles->role_names)) {
                $this->notifier->flash($nameMessage, 'error');

                return false;
            }
        } else {
            foreach ($wp_roles->role_names as $key => $value) {
                if ($displayName == $value && $roleName != $key) {
                    $this->notifier->flash($nameMessage, 'error');
                    $this->flashSet('display_name', $displayName);

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * AJAX Return list of role capabiliries
     */
    public function getRoleCapabilities()
    {
        global $wp_roles;

        $roleKey = $_POST['roleKey'];

        $role = array();
        if (isset($roleKey)) {
            $role = $wp_roles->roles[$roleKey];
        }

        wp_send_json($role);

        wp_die();
    }

    /**
     * Get URL for edit role
     *
     * @return string
     */
    private function getListUrl()
    {
        return admin_url('admin.php') . '?page=' . Admin::MENU_SLUG;
    }

    /**
     * Get URL for edit role
     *
     * @return string
     */
    private function getEditUrl()
    {
        return admin_url('admin.php') . '?page=' . Admin::MENU_SLUG . '&edit_role=';
    }

    /**
     * Get URL for delete role
     *
     * @return string
     */
    private function getDeleteUrl()
    {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'action'      => 'premmerce_delete_role',
                    'delete_role' => '__role__',
                    '_wp_http_referer' => $_SERVER['REQUEST_URI']
                ),
                admin_url('admin-post.php')
            ),
            'premmerce-edit-user-role',
            '_premmerce_edit_user_role_nonce'
        );
    }

    /**
     * Save role data to BD
     *
     * @param string $roleName
     * @param array $roleData
     */
    private function setDBRole($roleName, $roleData = array())
    {
        if (! empty($roleName) && ! empty($roleData)) {
            if (isset($roleData['name']) && isset($roleData['capabilities'])) {
                $this->model->setRoles($roleName, $roleData);
            }
        }
    }

    /**
     * Register assets for admin page
     */
    private function registerAssets()
    {
        wp_enqueue_script(
            Admin::MENU_SLUG . '-script',
            $this->fileManager->locateAsset('admin/js/users-roles-script.js')
        );
        wp_enqueue_style(Admin::MENU_SLUG . '-style', $this->fileManager->locateAsset('admin/css/style.css'));
    }

    /**
     * Check rle name and add ***-N if key exists
     *
     * @param $name
     * @param int $i
     *
     * @return string
     */
    private function checkRoleName($name, $i = 0)
    {
        global $wp_roles;

        $n = $i > 0 ? $name . '-' . $i : $name;
        if (array_key_exists($n, $wp_roles->role_names)) {
            return $this->checkRoleName($name, ++$i);
        } else {
            return $n;
        }
    }

    /**
     * Get transliterate word
     *
     * @param string $word
     *
     * @return string
     */
    private function transliterate($word)
    {
        return Transliterator::transliterate($word);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function flashSet($key, $value)
    {
        return set_transient($this->flashKey . $key, $value, MINUTE_IN_SECONDS);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function flashGet($key)
    {
        $value = get_transient($this->flashKey . $key);

        delete_transient($this->flashKey . $key);

        return $value;
    }
}
