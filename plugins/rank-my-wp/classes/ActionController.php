<?php

/**
 * Set the ajax action and call for wordpress
 */
class RKMW_Classes_ActionController extends RKMW_Classes_FrontController {

    /** @var array with all form and ajax actions */
    var $actions = array();

    /** @var array from core config */
    private static $config;

    /**
     * The hookAjax is loaded as custom hook in hookController class
     *
     * @return void
     */
    public function hookInit() {
        /* Only if ajax */
        if (RKMW_Classes_Helpers_Tools::isAjax()) {
            $this->getActions();
        }
    }

    /**
     * The hookSubmit is loaded when action si posted
     *
     * @return void
     */
    public function hookMenu() {
        /* Only if post */
        if (!RKMW_Classes_Helpers_Tools::isAjax()) {
            $this->getActions();
        }
    }

    /**
     * The hookHead is loaded as admin hook in hookController class for script load
     * Is needed for security check as nonce
     *
     * @return void
     */
    public function hookHead() {
        echo '<script>
                  var rkmwQuery = {
                    "adminurl": "' . admin_url() . '",
                    "ajaxurl": "' . admin_url('admin-ajax.php') . '",
                    "adminposturl": "' . admin_url('post.php') . '",
                    "adminlisturl": "' . admin_url('edit.php') . '",
                    "nonce": "' . wp_create_nonce(RKMW_NONCE_ID) . '"
                  }
              </script>';
    }

    public function hookFronthead() {
        if (RKMW_Classes_Helpers_Tools::isFrontAdmin()) {
            echo '<script>
                  var rkmwQuery = {
                    "adminurl": "' . admin_url() . '",
                    "ajaxurl": "' . admin_url('admin-ajax.php') . '",
                    "nonce": "' . wp_create_nonce(RKMW_NONCE_ID) . '"
                  }
              </script>';
        }
    }

    /**
     * Get all actions from config.json in core directory and add them in the WP
     *
     */
    public function getActions() {
        global $wp_filesystem;
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();

        $this->actions = array();
        $cur_action = RKMW_Classes_Helpers_Tools::getValue('action', false);
        $http_referer = RKMW_Classes_Helpers_Tools::getValue('_wp_http_referer', false);
        $rkmw_nonce = RKMW_Classes_Helpers_Tools::getValue('rkmw_nonce', false);

        if (!function_exists('is_user_logged_in')) {
            return;
        }

        //Let only the logged users to access the actions
        if ($cur_action <> '' && $rkmw_nonce <> '' && is_admin()) {
            /* if config allready in cache */
            if (!isset(self::$config)) {
                $config_file = RKMW_ROOT_DIR . 'config.json';
                if (!$wp_filesystem->exists($config_file)) {
                    return;
                }

                /* load configuration blocks data from core config files */
                self::$config = json_decode($wp_filesystem->get_contents($config_file), 1);
            }

            if (is_array(self::$config))
                foreach (self::$config['blocks']['block'] as $block) {
                    if (isset($block['active']) && $block['active'] == 1) {
                        /* if there is a single action */
                        if (isset($block['actions']['action']))
                            if (isset($block['admin']) && (($block['admin'] == 1 && is_user_logged_in()) || $block['admin'] == 0)) {
                                /* if there are more actions for the current block */
                                if (!is_array($block['actions']['action'])) {
                                    /* add the action in the actions array */
                                    if ($block['actions']['action'] == $cur_action) {
                                        $this->actions[] = array('class' => $block['name']);
                                    }
                                } else {
                                    /* if there are more actions for the current block */
                                    foreach ($block['actions']['action'] as $action) {
                                        /* add the actions in the actions array */
                                        if ($action == $cur_action) {
                                            $this->actions[] = array('class' => $block['name']);
                                        }
                                    }
                                }
                            }

                    }
                }

            //If there is an action found in the config.js file
            if (!empty($this->actions)) {
                /* add the actions in WP */
                foreach ($this->actions as $actions) {
                    if (RKMW_Classes_Helpers_Tools::isAjax() && !$http_referer) {
                        check_ajax_referer(RKMW_NONCE_ID, 'rkmw_nonce');
                        add_action('wp_ajax_' . $cur_action, array(RKMW_Classes_ObjController::getClass($actions['class']), 'action'));
                    } else {
                        check_admin_referer($cur_action, 'rkmw_nonce');
                        RKMW_Classes_ObjController::getClass($actions['class'])->action();
                    }
                }
            }
        }

    }

}
