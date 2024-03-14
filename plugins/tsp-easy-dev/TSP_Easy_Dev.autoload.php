<?php
    /*
    Name: 			LAPDI Easy Dev
    URI: 			https://letaprodoit.com/apps/plugins/wordpress/easy-dev-for-wordpress/
    Description: 	Easy Dev is a <strong>Framework</strong> for WordPress Plugin development. See <a target="_blank" href="https://lab.letaprodoit.com/public/wiki/wordpress-ed:MainPage">Framework Docs</a> for information and instructions. <a target="_blank" href="https://twitter.com/#bringbackOOD">#bringbackOOD</a>
    Author: 		Let A Pro Do IT!
    Author URI: 	https://letaprodoit.com/
    Version: 		1.0.3
    Copyright: 		Copyright © 2021 Let A Pro Do IT!, LLC (www.letaprodoit.com). All rights reserved
    License: 		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
    */

    require_once(ABSPATH . 'wp-admin/includes/plugin.php' );

    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's name/id
     *
     * @var string
     */
    @define('TSP_EASY_DEV_NAME', 				    'tsp-easy-dev');
    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's absolute path
     *
     * @var string
     */
    @define('TSP_EASY_DEV_PATH',				    plugin_dir_path( __FILE__ ) );
    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's URL
     *
     * @var string
     */
    @define('TSP_EASY_DEV_URL', 				    plugin_dir_url( __FILE__ ) );
    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's base file name
     *
     * @var string
     */
    @define('TSP_EASY_DEV_BASE_NAME', 			    TSP_EASY_DEV_NAME . '/' . TSP_EASY_DEV_NAME . '.php' );
    /**
     * Text Domain
     *
     * @var string
     */
    @define('TSP_EASY_DEV_DOMAIN', 				    'tsped');
    /**
     * Field Prefix
     *
     * @var string
     */
    @define('TSP_EASY_DEV_FIELD_PREFIX', 		    'tspedev');
    /**
     * The Software People Company Acronym
     *
     * @var string
     */
    @define('TSP_ACRONYM', 				            'tsp');
    /**
     * The Let A Pro Do IT! Company Acronym
     *
     * @var string
     */
    @define('LAPDI_ACRONYM', 				        'lapdi');
    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's name (not description but plugin title)
     *
     * @var string
     */
    @define('TSP_EASY_DEV_TITLE', 				    'Easy Dev');
    /**
     * The Company Name
     *
     * @var string
     */
    @define('TSP_COMPANY_NAME', 				    'Let A Pro Do IT!');
    /**
     * Every plugin that uses Easy Dev must define a UNIQUE variable that holds the plugin's required wordpress version
     *
     * @var string
     */
    @define('TSP_EASY_DEV_REQ_VERSION', 			"3.5.1");
    /**
     * The Lab URL
     *
     * @var string
     */
    @define('TSP_LAB_URL', 			                "https://lab.letaprodoit.com/public/");
    /**
     * The Lab URL
     *
     * @var string
     */
    @define('TSP_LAB_BUG_URL', 			            TSP_LAB_URL . "%PLUGIN%/issues/new");
    /**
     * The Support URL
     *
     * @var string
     */
    @define('TSP_SUPPORT_URL', 			            "https://support.letaprodoit.com/");
    /**
     * The Store URL
     *
     * @var string
     */
    @define('TSP_STORE_URL', 			            "https://letaprodoit.com/apps/plugins/wordpress/");
    /**
     * The Store URL
     *
     * @var string
     */
    @define('TSP_PLUGINS_URL', 			            "https://letaprodoit.com/plugins/wordpress/plugins.json");
    /**
     * The Ratings URL
     *
     * @var string
     */
    @define('TSP_WORDPRESS_RATE_URL', 			    "https://wordpress.org/support/view/plugin-reviews/%PLUGIN%");
    /**
     * The FAQ URL
     *
     * @var string
     */
    @define('TSP_WORDPRESS_FAQ_URL', 			    "http://wordpress.org/extend/plugins/%PLUGIN%/faq");
    /**
     * @ignore
     */
    @define('TSP_EASY_DEV_CLASS_PATH',				TSP_EASY_DEV_PATH . 'classes/');
    /**
     * @ignore
     */
    @define('TSP_EASY_DEV_LIB_PATH',				TSP_EASY_DEV_CLASS_PATH . 'lib/');
    /**
     * @ignore
     */
    @define('TSP_EASY_DEV_INCLUDES_PATH',			TSP_EASY_DEV_CLASS_PATH . 'includes/');

    /**
     * Assets absolute path
     *
     * @ignore
     */
    @define('TSP_EASY_DEV_VENDOR_PATH',				TSP_EASY_DEV_PATH . 'vendor/');
    /* @group Assets */
    /**
     * Assets absolute path
     *
     * @ignore
     */
    @define('TSP_EASY_DEV_ASSETS_PATH',				TSP_EASY_DEV_PATH . 'assets/');

    // Absolute directory paths
    /**
     * Full absolute path to the Easy Dev templates directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_TEMPLATES_PATH',   TSP_EASY_DEV_ASSETS_PATH . 'templates/');
    /**
     * Full absolute path to the Easy Dev css directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_CSS_PATH',		    TSP_EASY_DEV_ASSETS_PATH . 'css/');
    /**
     * Full absolute path to the Easy Dev javascript directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_JS_PATH',		    TSP_EASY_DEV_ASSETS_PATH . 'js/');
    /**
     * Full absolute path to the Easy Dev images directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_IMAGES_PATH',	    TSP_EASY_DEV_ASSETS_PATH . 'images/');

    /**
     * Vendor URL
     *
     * @ignore
     */
    @define('TSP_EASY_DEV_VENDOR_URL',				TSP_EASY_DEV_URL . 'vendor/');
    /**
     * Assets URL
     *
     * @ignore
     */
    @define('TSP_EASY_DEV_ASSETS_URL',				TSP_EASY_DEV_URL . 'assets/');

    /**
     * Full URL to the Easy Dev templates directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_TEMPLATES_URL',    TSP_EASY_DEV_ASSETS_URL . 'templates/');
    /**
     * Full URL to the Easy Dev css directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_CSS_URL',		    TSP_EASY_DEV_ASSETS_URL . 'css/');
    /**
     * Full URL to the Easy Dev javascript directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_JS_URL',		    TSP_EASY_DEV_ASSETS_URL . 'js/');
    /**
     * Full URL to the Easy Dev images directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_ASSETS_IMAGES_URL',	    TSP_EASY_DEV_ASSETS_URL . 'images/');
    /* @end */

    // Store smarty cache and compiled directories
    $upload_dir	= wp_upload_dir();
    /**
     * Full absolute path to the Easy Dev temp directory
     *
     * @var string
     */
    @define('TSP_EASY_DEV_TMP_PATH',				$upload_dir['basedir'] . '/tsp_plugins/' );


    include( TSP_EASY_DEV_VENDOR_PATH . 'autoload.php');

    //--------------------------------------------------------
    // Register classes
    //--------------------------------------------------------
    if ( !function_exists( TSP_EASY_DEV_FIELD_PREFIX . '_register_classes' ) )
    {
        /**
         * Hook implementation for spl_autoload_register
         *
         * @ignore
         *
         * @since 1.0
         *
         * @param string $class Required - the class name to include the class file for
         *
         * @return void
         */
        function tspedev_register_classes( $class )
        {
            if (file_exists( TSP_EASY_DEV_CLASS_PATH . $class . '.class.php' ))
            {
                include_once TSP_EASY_DEV_CLASS_PATH . $class . '.class.php';
            }//end if
        }//end tspedev_register_classes

        spl_autoload_register( TSP_EASY_DEV_FIELD_PREFIX . '_register_classes' );
    }//end if

    include( TSP_EASY_DEV_PATH . 'TSP_Easy_Dev.config.php');
    include( TSP_EASY_DEV_PATH . 'TSP_Easy_Dev.extend.php');
