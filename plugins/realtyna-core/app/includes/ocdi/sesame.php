<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_OCDI_Sesame')):

/**
 * Sesame One Click Demo Importer Class.
 *
 * @class RTCORE_OCDI_Sesame
 * @version	1.0.0
 */
class RTCORE_OCDI_Sesame extends RTCORE_OCDI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        // Demo Importer
        add_filter('pt-ocdi/import_files', array($this, 'files'));

        // Plugins
        add_filter('pt-ocdi/register_plugins', array($this, 'plugins'));

        // Before Import
        add_action('pt-ocdi/before_widgets_import', array($this, 'prepare'));

        // After Import
        add_action('pt-ocdi/after_import', array($this, 'config'), 10);
        add_action('pt-ocdi/after_import', array($this, 'activities'), 14);
        add_action('pt-ocdi/after_import', array($this, 'listings'), 15);

        // Menu Setup
        add_filter('pt-ocdi/plugin_page_setup', array($this, 'menu'));

        // Page
        add_filter('pt-ocdi/plugin_intro_text', array($this, 'intro'));
        add_filter('pt-ocdi/plugin_page_title', array($this, 'title'));
    }

    /**
     * Register Import Files for Demo Importer
     * @return array
     */
    public function files()
    {
        return array(
            array(
                'import_file_name'             => esc_html__('Sesame Demo 1', 'realtyna-core'),
                'categories'                   => array(esc_html__('Real Estate', 'realtyna-core')),
                'local_import_file'            => RTCORE_ABSPATH.'/ocdi/sesame/demo1/content.xml',
                'local_import_widget_file'     => RTCORE_ABSPATH.'/ocdi/sesame/demo1/widgets.wie',
                'local_import_customizer_file' => RTCORE_ABSPATH.'/ocdi/sesame/demo1/customizer.dat',
                'local_import_redux'           => array(
                    array(
                        'file_path'   => RTCORE_ABSPATH.'/ocdi/sesame/demo1/redux.json',
                        'option_name' => 'sesame_options',
                    ),
                ),
                'import_preview_image_url'     => 'http://sesame.realtyna.com/wp-content/themes/sesame/screenshot.png',
                'preview_url'                  => 'http://sesame.realtyna.com',
            )
        );
    }

    public function config()
    {
        // Assign Menus
        $main_menu = get_term_by('name', 'Menu 1', 'nav_menu');

        set_theme_mod('nav_menu_locations', array(
            'main-menu' => $main_menu->term_id,
        ));

        // Assign front page and posts page (blog page).
        $front_page_id = get_page_by_title('Home');
        $blog_page_id = get_page_by_title('Blog');

        update_option('show_on_front', 'page');
        update_option('page_on_front', $front_page_id->ID);
        update_option('page_for_posts', $blog_page_id->ID);

        // Only When WPL is activated
        if(class_exists('wpl_db'))
        {
            // Set Public Agent
            wpl_db::update('wpl_users', array(
                'access_public_profile' => 1
            ), 'id', 1);
        }
    }

    public function activities()
    {
        // WPL is not activated
        if(!class_exists('wpl_db')) return;

        // Listing Gallery Activity
        $JSON = wpl_db::get('`params`', 'wpl_activities', 'id', 13);

        $params = json_decode($JSON, true);
        $params['image_width'] = '350';
        $params['image_height'] = 'auto';

        wpl_db::update('wpl_activities', array(
            'params' => json_encode($params)
        ), 'id', 13);
    }

    public function title($title)
    {
        return '<div class="ocdi__title-container">
			<h1 class="ocdi__title-container-title">'.esc_html__('Sesame Demo Importer', 'realtyna-core').'</h1>
			<a href="https://ocdi.com/user-guide/" target="_blank" rel="noopener noreferrer">
				<img class="ocdi__title-container-icon" src="'.plugins_url().'/one-click-demo-import/assets/images/icons/question-circle.svg" alt="Questionmark icon">
			</a>
		</div>';
    }
}

endif;