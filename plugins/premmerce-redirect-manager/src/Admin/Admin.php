<?php namespace Premmerce\Redirect\Admin;

use Premmerce\Redirect\RedirectModel;
use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class Admin
 *
 * @package Premmerce\Redirect\Admin
 */
class Admin
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var RedirectModel
     */
    private $model;

    /**
     * Admin constructor.
     *
     * @param FileManager $fileManager
     * @param RedirectModel $model
     */
    public function __construct(FileManager $fileManager, RedirectModel $model)
    {
        $this->registerHooks();

        $this->fileManager = $fileManager;
        $this->model       = $model;
    }

    /**
     * Add Premmerce redirect in main menu
     */
    public function addMenuPage()
    {
        global $admin_page_hooks;

        $premmerceMenuExists = isset($admin_page_hooks['premmerce']);

        $svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="16" style="fill:#82878c" viewBox="0 0 20 16"><g id="Rectangle_7"> <path d="M17.8,4l-0.5,1C15.8,7.3,14.4,8,14,8c0,0,0,0,0,0H8h0V4.3C8,4.1,8.1,4,8.3,4H17.8 M4,0H1C0.4,0,0,0.4,0,1c0,0.6,0.4,1,1,1 h1.7C2.9,2,3,2.1,3,2.3V12c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1V1C5,0.4,4.6,0,4,0L4,0z M18,2H7.3C6.6,2,6,2.6,6,3.3V12 c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1v-1.7C8,10.1,8.1,10,8.3,10H14c1.1,0,3.2-1.1,5-4l0.7-1.4C20,4,20,3.2,19.5,2.6 C19.1,2.2,18.6,2,18,2L18,2z M14,11h-4c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4c0.6,0,1-0.4,1-1C15,11.4,14.6,11,14,11L14,11z M14,14 c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C15,14.4,14.6,14,14,14L14,14z M4,14c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1 c0.6,0,1-0.4,1-1C5,14.4,4.6,14,4,14L4,14z"/></g></svg>';
        $svg = 'data:image/svg+xml;base64,' . base64_encode($svg);

        if (!$premmerceMenuExists) {
            add_menu_page(
                'Premmerce',
                'Premmerce',
                'manage_options',
                'premmerce',
                '',
                $svg
            );
        }

        add_submenu_page(
            'premmerce',
            __('Redirects', 'premmerce-redirect'),
            __('Redirects', 'premmerce-redirect'),
            'manage_options',
            'premmerce_redirect',
            array($this, 'menuContent')
        );


        if (!$premmerceMenuExists) {
            global $submenu;
            unset($submenu['premmerce'][0]);
        }
    }

    public function addFullPack()
    {
        global $submenu;

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        $premmerceInstalled = array_key_exists('premmerce-premium/premmerce.php', $plugins)
                              || array_key_exists('premmerce/premmerce.php', $plugins);

        if (!$premmerceInstalled) {
            $submenu['premmerce'][999] = array(
                'Get premmerce full pack',
                'manage_options',
                admin_url('plugin-install.php?tab=plugin-information&plugin=premmerce'),
            );
        }
    }

    /**
     * Register backend hooks
     */
    private function registerHooks()
    {
        add_action('admin_menu', array($this, 'addMenuPage'));
        add_action('admin_menu', array($this, 'addFullPack'), 100);

        if (get_option('premmerce_redirect_delete_product') == 'on') {
            add_filter('pre_trash_post', array($this, 'createRedirectOnTrashProduct'), 0, 2);
            add_action('untrashed_post', array($this, 'deleteRedirectOnUntrashProduct'));
        }

        add_action('save_post', array($this, 'createRedirectOnSavePost'), 10, 3);

        add_action('admin_post_premmerce_delete_redirect', array($this, 'deleteRedirect'));

        add_action('wp_ajax_get_posts_by_string', array($this, 'getPostsByString'));
    }

    /**
     * Include redirects page template
     */
    public function menuContent()
    {
        $this->switchPage();
    }

    /**
     * Create redirect when change product status
     *
     * @param int $pid
     * @param \WP_Post $post
     * @param bool $update
     */
    public function createRedirectOnSavePost($pid, \WP_Post $post, $update)
    {
        if ($post->post_type == 'product') {
            $optionStatus = get_option('premmerce_redirect_change_status_product') == 'on'? true : false;

            if (in_array($post->post_status, array('draft', 'pending')) && $optionStatus && $update) {
                if ($post->post_name) {
                    $url = get_post_permalink($pid, false, true);
                    $this->model->deleteRedirect(array('old_url' => str_replace(get_home_url(), '', $url)));

                    $categories = get_the_terms($pid, 'product_cat');

                    if ($categories) {
                        $redirectType    = 'product_category';
                        $redirectContent = $categories[0]->term_id;
                    } else {
                        $redirectType    = 'url';
                        $redirectContent = get_home_url();
                    }

                    $this->model->createRedirect(array(
                        'old_url'          => str_replace(get_home_url(), '', $url),
                        'redirect_type'    => $redirectType,
                        'redirect_content' => $redirectContent,
                        'redirect_method'  => 302,
                    ));
                }
            } elseif ($post->post_status == 'publish') {
                $this->model->deleteRedirect(array('old_url' => str_replace(get_home_url(), '', get_permalink($pid))));
            }
        }
    }

    /**
     * Create redirect when trash product
     *
     * @param null $null
     * @param \WP_Post $post
     */
    public function createRedirectOnTrashProduct($null, $post)
    {
        if ($post && $post->post_type == 'product') {
            if (!$this->model->getOneRedirectByOldUrl(str_replace(get_home_url(), '', get_permalink($post->ID)))) {
                if (!in_array($post->post_status, array('draft', 'pending'))) {
                    $oldUrl = str_replace(get_home_url(), '', get_permalink($post->ID));

                    $categories = get_the_terms($post->ID, 'product_cat');

                    if ($categories) {
                        $redirectContent = $this->getRedirectContent($categories);

                        if ($redirectContent) {
                            $this->model->createRedirect(array(
                                'old_url'          => $oldUrl,
                                'redirect_type'    => 'product_category',
                                'redirect_content' => $redirectContent->term_id,
                                'redirect_method'  => 301,
                            ));
                        }
                    } else {
                        $this->model->createRedirect(array(
                            'old_url'          => $oldUrl,
                            'redirect_type'    => 'url',
                            'redirect_content' => get_home_url(),
                            'redirect_method'  => 301,
                        ));
                    }
                }
            }
        }
    }

    /**
     * Delete page in edit page
     */
    public function deleteRedirect()
    {
        if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce($_GET['_wpnonce'], 'premmerce-redirect-delete')) {
            wp_redirect(admin_url('admin.php') . '?page=premmerce_redirect');
            exit;
        }

        if (! isset($_GET['_wp_http_referer'])) {
            wp_redirect(admin_url('admin.php') . '?page=premmerce_redirect');
            exit;
        }

        $url = str_replace(home_url(), '', admin_url('admin.php') . '?page=premmerce_redirect');
        if (false === strpos($_GET['_wp_http_referer'], $url)) {
            wp_redirect(admin_url('admin.php') . '?page=premmerce_redirect');
            exit;
        }

        $this->model->deleteRedirect(array('id' => $_GET['id']));

        wp_redirect(admin_url('admin.php') . '?page=premmerce_redirect', 302);
        exit;
    }

    /**
     * Delete redirect when untrash product
     *
     * @param int $pid
     */
    public function deleteRedirectOnUntrashProduct($pid)
    {
        $post = get_post($pid);

        if ($post->post_type == 'product') {
            $this->model->deleteRedirect(array('old_url' => str_replace(get_home_url(), '', get_permalink($pid))));
        }
    }

    /**
     * Search products by stings
     */
    public function getPostsByString()
    {
        if (isset($_POST['type'])) {
            $objects = $this->model->getPostsByString($_POST);

            wp_send_json($objects);
        }
    }

    public static function getDeleteURL($id)
    {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'action' => 'premmerce_delete_redirect',
                    'id'     => $id,
                    '_wp_http_referer' => $_SERVER['REQUEST_URI']
                ),
                admin_url('admin-post.php')
            ),
            'premmerce-redirect-delete'
        );
    }

    /**
     * Control admin panel actions
     * @return array|void
     */
    private function switchAction()
    {
        $action = isset($_POST['action'])? $_POST['action'] : null;

        if (empty($action)) {
            return;
        }

        $nonce_action = 'premmerce-redirect-' . $action;
        if (isset($_POST['action2'])) {
            $nonce_action = 'bulk-type';
        }
        if (! isset($_POST['_wpnonce']) || ! wp_verify_nonce($_POST['_wpnonce'], $nonce_action)) {
            return;
        }

        if (! isset($_POST['_wp_http_referer'])) {
            return;
        }

        switch ($action) {
            case 'create':
                $url = str_replace(home_url(), '', admin_url('admin.php') . '?page=premmerce_redirect');
                if (false === strpos($_POST['_wp_http_referer'], $url)) {
                    return;
                }

                return $this->processingCreate();
            case 'edit':
                $url = str_replace(home_url(), '', admin_url('admin.php') . '?page=premmerce_redirect&tab=edit&id=' . $_POST['id']);
                if ($url !== $_POST['_wp_http_referer']) {
                    return;
                }

                return $this->processingEdit();
            case 'delete':
            case '-1':
                $url = str_replace(home_url(), '', admin_url('admin.php') . '?page=premmerce_redirect');
                if (false === strpos($_POST['_wp_http_referer'], $url)) {
                    return;
                }

                return $this->processingDelete();
        }
    }

    /**
     * Controll admin panel pages
     */
    private function switchPage()
    {
        wp_enqueue_script('select2', $this->fileManager->locateAsset('admin/js/select2.min.js'));
        wp_enqueue_script('premmerce-redirect', $this->fileManager->locateAsset('admin/js/premmerce-redirect.js'));
        wp_enqueue_style('select2', $this->fileManager->locateAsset('admin/css/select2.min.css'));
        wp_enqueue_style('premmerce-redirect', $this->fileManager->locateAsset('admin/css/premmerce-redirect.css'));

        $page = isset($_GET['tab'])? $_GET['tab'] : null;

        $htmlTabs = $this->renderTabs($page);

        switch ($page) {
            case 'edit':
                $this->pageEdit();
                break;

            case 'settings':
                $this->pageSettings($htmlTabs);
                break;

            case 'contact':
            case 'account':
                $this->pageFreemius($page, $htmlTabs);
                break;

            default:
                $this->pageDefault($htmlTabs);
        }
    }

    /**
     * Render list of tabs
     *
     * @param string $current
     *
     * @return string
     */
    private function renderTabs($current)
    {
        $tabs['redirects'] = __('Redirects', 'premmerce-redirect');
        $tabs['settings']  = __('Settings', 'premmerce-redirect');

        if (function_exists('premmerce_pr_fs')) {
            $tabs['contact'] = __('Contact Us', 'premmerce-redirect');
            if (premmerce_pr_fs()->is_registered()) {
                $tabs['account'] = __('Account', 'premmerce-redirect');
            }
        }

        $htmlTabs = $this->fileManager->renderTemplate('admin/tabs.php', array(
            'current' => $current? $current : 'redirects',
            'tabs'    => $tabs,
        ));

        return $htmlTabs;
    }

    /**
     * Render freemius pages
     *
     * @param string $current
     * @param string $htmlTabs
     */
    private function pageFreemius($current, $htmlTabs)
    {
        $this->fileManager->includeTemplate("admin/tabs/{$current}.php", array('htmlTabs' => $htmlTabs));
    }

    /**
     * Include redirects settings template
     *
     * @param string $htmlTabs
     */
    private function pageSettings($htmlTabs)
    {
        $this->fileManager->includeTemplate('admin/menu-settings-page.php', array('htmlTabs' => $htmlTabs));
    }

    /**
     * Default action in menu page
     *
     * @param string $htmlTabs
     */
    private function pageDefault($htmlTabs)
    {
        $actionData = $this->switchAction();

        $redirects = $this->model->getRedirects();

        $redirectsTable = new RedirectsTable($this->model, $this->fileManager);

        $this->fileManager->includeTemplate('admin/menu-page.php', array(
            'redirects'      => $redirects,
            'redirectsTable' => $redirectsTable,
            'oldValues'      => isset($actionData['oldValues'])? $actionData['oldValues'] : array(),
            'errorMessage'   => isset($actionData['errors'])? $actionData['errors'] : '',
            'htmlTabs'       => $htmlTabs,
        ));
    }

    /**
     * Edit action in menu page
     */
    private function pageEdit()
    {
        $actionData = $this->switchAction();

        $redirect = $this->model->getOneRedirectById($_GET['id']);

        if ($redirect) {
            $this->fileManager->includeTemplate('admin/menu-page-edit.php', array(
                'redirect'       => $redirect,
                'errorMessage'   => isset($actionData['errors'])? $actionData['errors'] : '',
                'successMessage' => isset($actionData['successMessage'])? $actionData['successMessage'] : '',
            ));
        } else {
            $this->fileManager->includeTemplate('admin/error-page.php', array(
                'errorMessage' => __('You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?'),
            ));
        }
    }

    /**
     * Delete action in menu page
     *
     * @return array
     */
    private function processingDelete()
    {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $id) {
                $this->model->deleteRedirect(array('id' => $id));
            }
        }

        return array();
    }

    /**
     * Processing create request
     *
     * @return array
     */
    private function processingCreate()
    {
        $oldValues = $_POST;
        $data      = $_POST;

        $data['old_url'] = filter_input(INPUT_POST, 'old_url', FILTER_SANITIZE_URL);

        $checkOldURL = $this->model->getOneRedirectByOldUrl($data['old_url']);

        $message = $this->checkData($data, $checkOldURL);
        if (empty($message)) {
            $oldValues = array();

            $data['redirect_content'] = $_POST[ $_POST['redirect_type'] . '_content' ];
            if ('url' === $_POST['redirect_type']) {
                $data['redirect_content'] = filter_input(INPUT_POST, $_POST['redirect_type'] . '_content', FILTER_SANITIZE_URL);
            }

            $this->model->createRedirect($data);
        }

        return array(
            'oldValues' => $oldValues,
            'errors'    => $message,
        );
    }

    /**
     * Processing edit request
     *
     * @return array
     */
    private function processingEdit()
    {
        $successMessage = '';
        $data           = $_POST;

        $data['old_url'] = filter_input(INPUT_POST, 'old_url', FILTER_SANITIZE_URL);

        $checkOldURL = $this->model->getOneRedirectByOldUrlAndOtherId($data['old_url'], $_POST['id']);

        $message = $this->checkData($data, $checkOldURL);
        if (empty($message)) {
            $data['redirect_content'] = $_POST [ $_POST['redirect_type'] . '_content' ];

            if ('url' === $_POST['redirect_type']) {
                $data['redirect_content'] = filter_input(INPUT_POST, $_POST['redirect_type'] . '_content', FILTER_SANITIZE_URL);
            }

            $this->model->updateRedirect($data, $_POST['id']);

            $successMessage = __('Redirect updated.', 'premmerce-redirect');
        }

        return array(
            'oldValues'      => $_POST,
            'errors'         => $message,
            'successMessage' => $successMessage,
        );
    }

    /**
     * Check data is it correct
     *
     * @param $data
     * @param $oldUrl
     *
     * @return string
     */
    private function checkData($data, $oldUrl)
    {
        $message = '';

        if (!$data['old_url'] || !$data['redirect_type'] ||
           !$data['redirect_method'] || !$data[ $data['redirect_type'] . '_content' ]
        ) {
            $message = __('You did not fill all fields', 'premmerce-redirect');
        } elseif ($oldUrl) {
            $message = __('The redirect with same URL already exists', 'premmerce-redirect');
        } elseif ($data['old_url'] == $data[ $data['redirect_type'] . '_content' ]) {
            $message = __('Source URL cannot be same as Target URL', 'premmerce-redirect');
        }

        return $message;
    }

    /**
     * @param array $categories
     *
     * @return \WP_Term
     */
    private function getRedirectContent($categories)
    {
        $levels = array();

        foreach ($categories as $category) {
            $level                   = get_ancestors($category->term_id, 'category');
            $levels[ count($level) ] = $category;
        }

        return $levels[ max(array_keys($levels)) ];
    }
}
