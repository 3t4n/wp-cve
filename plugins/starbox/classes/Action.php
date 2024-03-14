<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

/**
 * Set the ajax action and call for wordpress
 */
class ABH_Classes_Action extends ABH_Classes_FrontController {

    /** @var array with all form and ajax actions  */
    var $actions = array();

    /** @var array from core config */
    private static $config;

    /**
     * The hookAjax is loaded as custom hook in hookController class
     *
     * @return void
     */
    function hookInit() {
        /* Only if ajax */
        if (ABH_Classes_Tools::isAjax()) {
            $this->getActions();
        }
    }

    /**
     * The hookSubmit is loaded when action si posted
     *
     * @return void
     */
    function hookMenu() {
        if(!ABH_Classes_Tools::isAjax()) {
            $this->getActions();
        }
    }

    /**
     * The hookHead is loaded as admin hook in hookController class for script load
     * Is needed for security check as nonce
     *
     * @return void
     */
    function hookHead() {

        echo '<script type="text/javascript">
                  var abh_Query = {
                    "ajaxurl": "' . admin_url('admin-ajax.php') . '",
                    "adminposturl": "' . admin_url('post.php') . '",
                    "adminlisturl": "' . admin_url('edit.php') . '",
                    "abh_nonce": "' . wp_create_nonce(_ABH_NONCE_ID_) . '"
                  }
              </script>';
    }

    /**
     * Get all actions from config.xml in core directory and add them in the WP
     *
     * @return void
     */
    public function getActions() {
        $this->actions = array();
        $cur_action = ABH_Classes_Tools::getValue('action', false);
        $nonce = ABH_Classes_Tools::getValue('abh_nonce', false);

        if($cur_action && $nonce) {
            /* if config allready in cache */
            if (!isset(self::$config)) {
                $config_file = _ABH_ROOT_DIR_ . 'config.json';
                if (!file_exists($config_file))
                    return;

                /* load configuration blocks data from core config files */
                self::$config = json_decode(file_get_contents($config_file), 1);
            }

            if (is_array(self::$config))
                foreach (self::$config['blocks']['block'] as $block) {
                    if (isset($block['active']) && $block['active'] == 1) {
                        /* if there is a single action */
                        if (isset($block['actions']['action']))

                            /* if there are more actions for the current block */
                            if (!is_array($block['actions']['action'])) {
                                /* add the action in the actions array */
                                if ($block['actions']['action'] == $cur_action)
                                    $this->actions[] = array('class' => $block['name']);
                            } else {
                                /* if there are more actions for the current block */
                                foreach ($block['actions']['action'] as $action) {
                                    /* add the actions in the actions array */
                                    if ($action == $cur_action)
                                        $this->actions[] = array('class' => $block['name']);
                                }
                            }
                    }
                }

            if (ABH_Classes_Tools::isAjax()) {
                check_ajax_referer(_ABH_NONCE_ID_, 'abh_nonce');
            } else {
                check_admin_referer($cur_action, 'abh_nonce');
            }

            /* add the actions in WP */
            foreach ($this->actions as $actions) {
                ABH_Classes_ObjController::getController($actions['class'])->action();
            }
        }
    }

}