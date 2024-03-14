<?php
namespace BDroppy\Services\Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Loader {

    protected $actions;

    protected $filters;

    public function __construct()
    {
        $this->actions = array();
        $this->filters = array();
        $this->shortCodes = array();
    }

    private function add( $hooks, $hook, $component, $callback, $priority = null, $accepted_args = null) {

        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        );

        return $hooks;
    }

    public function addFilter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
    }

    public function addAction( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
        return $this;
    }

    public function addShortCode($shortCode,$component,$callback)
    {
        $this->shortCodes = $this->add( $this->shortCodes, $shortCode, $component, $callback);
    }

    public function addScript( $fileName, $dependencies = ['jquery'], $version = false, $inFooter = true ) {

       wp_enqueue_script(
            BDROPPY_NAME . '_'.$fileName.'_js',
            BDROPPY_JS . $fileName.'.js',
            $dependencies,
            $version? $version :time(),
            $inFooter
        );
        return $this;

    }

    public function addScriptObject($fileName,$options,$name='options')
    {
        wp_localize_script( BDROPPY_NAME . '_'.$fileName.'_js', $name, $options);
        return $this;
    }


    public function addStyle($fileName,$dependencies=[],$version = false,$media = 'all')
    {

        wp_enqueue_style(
            BDROPPY_NAME . '_' . $fileName . '_css',
            BDROPPY_CSS . $fileName .'.css',
            $dependencies,
            $version? $version :time(),
            $media
        );
        return $this;
    }

    public function run() {

        foreach ( $this->filters as $hook ) {
            add_filter( $hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args'] );
        }

        foreach ( $this->actions as $hook ) {
            add_action( $hook['hook'],[$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args'] );
        }

        foreach ( $this->shortCodes as $hook ) {
            add_shortcode( $hook['hook'],[$hook['component'], $hook['callback']]);
        }

    }
}

