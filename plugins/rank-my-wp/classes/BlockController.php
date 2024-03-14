<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * The main class for core blocks
 *
 */
class RKMW_Classes_BlockController {

    /** @var object of the model class */
    protected $model;

    /** @var boolean */
    public $flush = true;

    /** @var object of the view class */
    protected $view;

    /** @var string name of the  class */
    private $name;

    public function __construct() {
        /* get the name of the current class */
        $this->name = get_class($this);

        /* create the model and view instances */
        $model_classname = str_replace('Core', 'Models', $this->name);
        if (RKMW_Classes_ObjController::getClassPath($model_classname)) {
            $this->model = RKMW_Classes_ObjController::getClass($model_classname);
        }
    }

    /**
     * load sequence of classes
     * Function called usualy when the controller is loaded in WP
     *
     * @return mixed
     */
    public function init() {
        /* check if there is a hook defined in the block class */
        RKMW_Classes_ObjController::getClass('RKMW_Classes_HookController')->setBlockHooks($this);
        //get the class path
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
     * This function is called from Ajax class as a wp_ajax_action
     *
     */
    protected function action() { }

    /**
     * This function will load the media in the header for each class
     *
     * @return void
     */
    protected function hookHead() { }

}
