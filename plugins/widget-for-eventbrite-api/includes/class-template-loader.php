<?php

/**
 * helper Class to set up an Gamajo's template loader
 */
namespace WidgetForEventbriteAPI\Includes;

use  Gamajo_Template_Loader ;
require_once dirname( __FILE__ ) . '/vendor/gamajo/template-loader/class-gamajo-template-loader.php';
/**
 * Template loader
 *
 * Only need to specify class properties here.
 *
 */
class Template_Loader extends Gamajo_Template_Loader
{
    /**
     * Prefix for filter names.
     *
     */
    protected  $filter_prefix = 'widget-for-eventbrite-api' ;
    /**
     * Directory name where custom templates for this plugin should be found in the theme.
     *
     */
    protected  $theme_template_directory = 'widget-for-eventbrite-api' ;
    /**
     * Reference to the root directory path of this plugin.
     *
     * Can either be a defined constant, or a relative reference from where the subclass lives.
     *
     *
     */
    protected  $plugin_directory = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR ;
    /**
     * Directory name where templates are found in this plugin.
     *
     * Can either be a defined constant, or a relative reference from where the subclass lives.
     *
     * e.g. 'templates' or 'includes/templates', etc.
     *
     */
    protected  $plugin_template_directory = 'templates' ;
    public function __construct()
    {
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global  $wfea_fs ;
        add_filter( 'widget-for-eventbrite-api_template_paths', function ( $file_paths ) {
            
            if ( isset( $file_paths[1] ) ) {
                $file_paths[2] = trailingslashit( $file_paths[1] ) . 'parts';
                $file_paths[3] = trailingslashit( $file_paths[1] ) . 'loops';
                $file_paths[4] = trailingslashit( $file_paths[1] ) . 'scripts';
            }
            
            $file_paths[11] = trailingslashit( $file_paths[10] ) . 'parts';
            $file_paths[12] = trailingslashit( $file_paths[10] ) . 'loops';
            $file_paths[13] = trailingslashit( $file_paths[10] ) . 'scripts';
            $file_paths[20] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api';
            $file_paths[21] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api/parts';
            $file_paths[22] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api/loops';
            $file_paths[23] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api/scripts';
            global  $wfea_fs ;
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free';
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free/parts';
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free/loops';
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free/scripts';
            ksort( $file_paths );
            return $file_paths;
        }, 0 );
        add_filter(
            'widget-for-eventbrite-api_get_template_part',
            function ( $templates, $slug, $name ) {
            /**
             * @var \Freemius $wfea_fs Object for freemius.
             */
            global  $wfea_fs ;
            // also convert new format to legacy format to cover custom template
            if ( 'layout_widget' == $slug ) {
                array_unshift( $templates, 'widget.php' );
            }
            return $templates;
        },
            0,
            3
        );
    }
    
    public function get_file_paths()
    {
        return $this->get_template_paths();
    }

}