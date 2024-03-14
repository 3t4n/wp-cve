<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * The main class for controllers
 *
 */
class RKMW_Classes_FrontController {

    /** @var object of the model class */
    public $model;

    /** @var boolean */
    public $flush = true;

    /** @var name of the  class */
    private $name;

    public function __construct() {
        // Load error class
        RKMW_Classes_ObjController::getClass('RKMW_Classes_Error');

        /* get the name of the current class */
        $this->name = get_class($this);

        /* load the model and hooks here for wordpress actions to take efect */
        /* create the model and view instances */
        $model_classname = str_replace('Controllers', 'Models', $this->name);
        if (RKMW_Classes_ObjController::getClassPath($model_classname)) {
            $this->model = RKMW_Classes_ObjController::getClass($model_classname);
        }

        //IMPORTANT TO LOAD HOOKS HERE
        /* check if there is a hook defined in the controller clients class */
        RKMW_Classes_ObjController::getClass('RKMW_Classes_HookController')->setHooks($this);

        /* Load the Submit Actions Handler */
        RKMW_Classes_ObjController::getClass('RKMW_Classes_ActionController');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController');

        // load the abstract classes
        RKMW_Classes_ObjController::getClass('RKMW_Models_Abstract_Domain');
        RKMW_Classes_ObjController::getClass('RKMW_Models_Abstract_Models');
        RKMW_Classes_ObjController::getClass('RKMW_Models_Abstract_Seo');
    }

    public function getClass() {
        return $this->name;
    }

    /**
     * load sequence of classes
     * Function called usualy when the controller is loaded in WP
     *
     * @return mixed
     */
    public function init() {
        $class = RKMW_Classes_ObjController::getClassPath($this->name);

        if ($this->flush) {
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia($class['name']);

            echo (string)$this->getView($class['name']);
        } else {
            return (string)$this->getView($class['name']);
        }

        return '';
    }

    /**
     * Get the block view
     *
     * @param  string $view Class name
     * @return mixed
     */
    public function getView($view) {
        return RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->getView($view, $this);
    }

    /**
     * Called as menu callback to show the block
     *
     */
    public function show() {
        $this->flush = true;

        echo $this->init();
    }

    /**
     * initialize settings
     * Called from index
     *
     * @return void
     */
    public function runAdmin() {
        // load the remote controller in admin
        RKMW_Classes_ObjController::getClass('RKMW_Classes_RemoteController');
        RKMW_Classes_ObjController::getClass('RKMW_Models_Abstract_Assistant');

        // show the admin menu and post actions
        RKMW_Classes_ObjController::getClass('RKMW_Controllers_Menu');
        RKMW_Classes_ObjController::getClass('RKMW_Models_RoleManager');

    }

    /**
     * first function call for any class
     *
     */
    protected function action() { }

    /**
     * This function will load the media in the header for each class
     *
     * @return void
     */
    public function hookHead() { }

    /**
     * Show the notification bar
     */
    public function getNotificationBar(){
        echo $this->getView('Blocks/VersionBar');
    }

    public function getNotificationCompatibility(){
        return RKMW_Classes_ObjController::getClass('RKMW_Models_Compatibility')->getNotificationBar();
    }
}
