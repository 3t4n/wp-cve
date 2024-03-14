<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

/**
 * The main class for controllers
 *
 */
class ABH_Classes_FrontController {

    /** @var object of the model class */
    public $model;

    /** @var boolean */
    public $flush = true;

    /** @var object of the view class */
    public $view;

    /** @var name of the  class */
    private $name;

    public function __construct() {

        /* Load error class */
        ABH_Classes_ObjController::getController('ABH_Classes_Error');

        /* Load Tools */
        ABH_Classes_ObjController::getController('ABH_Classes_Tools');

        /* get the name of the current class */
        $this->name = get_class($this);

        /* load the model and hooks here for wordpress actions to take efect */
        /* create the model and view instances */
        $this->model = ABH_Classes_ObjController::getModel($this->name);
        //IMPORTANT TO LOAD HOOKS HERE
        /* check if there is a hook defined in the controller clients class */

        ABH_Classes_ObjController::getController('ABH_Classes_HookController')->setHooks($this);
        ABH_Classes_ObjController::getController('ABH_Classes_HookController')->getShortcodes($this);
    }

    /**
     * load sequence of classes
     * Function called usualy when the controller is loaded in WP
     *
     * @return void
     */
    public function init() {

        $this->view = ABH_Classes_ObjController::getController('ABH_Classes_DisplayController');

        if ($this->flush){
            $this->output();
        }
    }

    protected function output() {
        /* view is called from theme directory with the class name by defauls */
        if ($class = ABH_Classes_ObjController::getClassPath($this->name))
            $this->view->output($class['name'], $this);
    }

    /**
     * initialize settings
     * Called from index
     *
     * @return void
     */
    public function run() {

        /** check the admin condition */
        if (!is_admin())
            return;
        /* Load the Submit Actions Handler */
        ABH_Classes_ObjController::getController('ABH_Classes_Action');
        ABH_Classes_ObjController::getController('ABH_Classes_DisplayController');

        /* show the admin menu and post actions */
        $this->loadMenu();
    }

    /**
     * initialize menu
     *
     * @return void
     */
    private function loadMenu() {
        /* get the menu from controller */
        ABH_Classes_ObjController::getController('ABH_Controllers_Menu');
    }

    /**
     * first function call for any class
     *
     */
    protected function action() {
    }

    /**
     * This function will load the media in the header for each class
     *
     * @return void
     */
    public function hookHead() {
        if (!is_admin()) //this hook is for admin panel only
            return;

        //execute this only if admin
        if ($class = ABH_Classes_ObjController::getClassPath($this->name)) {
            ABH_Classes_ObjController::getController('ABH_Classes_DisplayController')
                ->loadMedia($class['name']);
        }
    }

}