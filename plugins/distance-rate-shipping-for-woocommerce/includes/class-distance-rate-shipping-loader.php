<?php
/**
 * Distance_Rate_Shipping_Loader
 * This class defines all the required code to load all hooks.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/includes
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping_Loader{
    /**
     * variable to hold list of action hooks
     * @var array $actions
     */
    protected $actions;
    /**
     * variable to hold list of filter hooks
     * @var array $filters
     */
    protected $filters;
    /**
     * variable to hold list of shortcode hooks
     * @var array $shortcodes
     */
    protected $shortcodes;

    /**
     * __constructor function
     * To initiate class variables.
     * It run on object creation of class.
     * @return void
     * @since 1.0.0
     */
    public function __construct(){
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    /**
     * add function
     * function to add hooks into thier list.
     * @since 1.0.0
     * @param array $hooks class variable for hook's list. ie, actions, filters or shortcode
     * @param string $component
     * @param string $callback
     * @param int $priority
     * @param int $accepted_args
     */
    public function add($hooks, $hook, $component, $callback, $priority, $accepted_args){
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        );
        return $hooks;
    }

    /**
     * add_hook function 
     * function to add different type of hooks into thier list.
     * @since 1.0.0
     * @param string $hook_type type of hooks either action, filter or shortcode
     * @param string $hook name of the hook
     * @param string $component
     * @param string $callback function that is attached to hook. hook's callback function.
     */
    public function add_hook($hook_type, $hook, $component, $callback, $priority=10, $accepted_args=1){
        switch($hook_type){
            case "action":
                $this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args);
                break;
            case "filter":
                $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
                break;
            case "shortcode":
                $this->shortcodes = $this->add($this->shortcodes, $hook, $component, $callback, $priority, $accepted_args);
                break;
            default:
                break;
        }
    }

    /**
     * load function 
     * function to load and register hooks into WordPress
     * @since 1.0.0
     */
    public function load(){
        /**
         * load/register all action hook
         */
        foreach($this->actions as $hook){
            add_action(
                $hook['hook'], 
                array($hook['component'], $hook['callback']), 
                $hook['priority'], 
                $hook['accepted_args']
            );
        }
        /**
         * load/register all filter hook
         */
        foreach($this->filters as $hook){
            add_filter(
                $hook['hook'], 
                array($hook['component'], $hook['callback']), 
                $hook['priority'], 
                $hook['accepted_args']
            );
        }
        /**
         * load/register all shortcode hook
         */
        foreach($this->shortcodes as $hook){
            add_shortcode(
                $hook['hook'], 
                array($hook['component'], $hook['callback']), 
                $hook['priority'], 
                $hook['accepted_args']
            );
        }
    }

}