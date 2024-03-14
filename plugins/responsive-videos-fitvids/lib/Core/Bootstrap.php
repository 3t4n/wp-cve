<?php

namespace SGI\Fitvids\Core;

use SGI\Fitvids\{
    Admin    as Admin,
    Frontend as Frontend
};

/**
 * Main Plugin Bootstraper class
 * 
 * @static
 * @since 3.0
 * @author Sibin Grasic <sibin.grasic@oblak.studio>
 */
class Bootstrap
{

    /**
     * @var null|Bootstrap Class instance
     * @since 3.0
     */
    private static $instance = null;

    /**
     * Plugin options
     * @var array plugin options
     * @since 3.0
     */
    private $opts;

    private function __construct()
    {

        $this->opts = get_option(
            'sgi_fitvids_opts',
            [
                'core'     => [
                    'autoconfig' => true,
                    'selector'   => '.entry-content'
                ],
                'active'   => [
                    'post' => true,
                    'page' => true,
                    'fp'   => true,
                    'arch' => true,
                ],
            ]
        );

        if (is_admin()) :
            add_action('plugins_loaded', [&$this, 'initAdmin']);
        endif;

        add_action('init', [&$this, 'initFrontend']);

    }

    /**
     * Returns the class instance
     *
     * @return Bootstrap Class instance
     * @since 3.0
     */
    public static function getInstance()
    {

        if (self::$instance === null) :
            self::$instance = new Bootstrap();
        endif;

        return self::$instance;

    }

    /**
     * Returns plugin options
     *
     * @return array Plugin options
     * 
     * @since 3.0
     */
    public function getOpts() : array
    {
        return $this->opts;
    }

    /**
     * Initializes wp-admin functions
     * 
     * @since 3.0
     */
    public function initAdmin()
    {

        new Admin\Settings;

    }

    public function initFrontend()
    {
        new Frontend\Engine();
    }


}