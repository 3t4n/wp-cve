<?php
/**
 * @package talentlms-wordpress
 */

namespace TalentlmsIntegration;

use TalentlmsIntegration\Services\PluginService;
use TalentlmsIntegration\Pages\Admin;

class Enqueue implements PluginService
{

    public function register(): void
    {
        add_action(
            'admin_enqueue_scripts',
            array($this, 'tlms_enqueueAdminScripts')
        );
        add_action(
            'wp_enqueue_scripts',
            array($this, 'tlms_enqueueFrontScripts')
        );
        add_action(
            'wp_enqueue_scripts',
            array($this, 'tlms_enqueueStyles')
        );
    }

    private function tlms_commonLibs(): void
    {
        //Register styles
        wp_register_style(
            'tlms-datatables-css',
            TLMS_BASEURL . 'assets/css/jquery.dataTables.min.css'
        );

        //Register scripts
        wp_register_script(
            'bootstrap-js',
            TLMS_BASEURL . 'assets/js/bootstrap.min.js',
            ['jquery'],
            false,
            true
        );

        wp_register_script(
            'tlms-font-awesome',
            TLMS_BASEURL . 'assets/js/font-awesome.min.js',
            false,
            TLMS_VERSION
        );

        wp_register_script(
            'tlms-font-awesome-solid',
            TLMS_BASEURL . 'assets/js/solid.min.js',
            false,
            TLMS_VERSION
        );

        wp_register_script(
            'tlms-datatables-js',
            TLMS_BASEURL . 'assets/js/jquery.dataTables.min.js',
            ['jquery']
        );

        //Enqueue styles
        wp_enqueue_style('tlms-datatables-css');

        //Enqueue scripts
        wp_enqueue_script('bootstrap-js');
        wp_enqueue_script('tlms-font-awesome');
        wp_enqueue_script('tlms-font-awesome-solid');
        wp_enqueue_script('tlms-datatables-js');
    }

    public function tlms_enqueueAdminScripts(): void
    {
        $this->tlms_commonLibs();

        //Register styles
        wp_register_style(
            'tlms-admin',
            TLMS_BASEURL . 'assets/css/tlms-admin.css',
            false,
            TLMS_VERSION
        );

        //Register scripts
        wp_register_script(
            'tlms-admin',
            TLMS_BASEURL . 'assets/js/tlms-admin.js',
            false,
            TLMS_VERSION
        );

        $translations_array = array(
            'progress_message' => esc_html__('Please wait while syncing..', 'talentlms'),
            'success_message' => esc_html__('The operation completed successfully', 'talentlms'),
            'select_all_message' => esc_html__('Select all', 'talentlms'),
            'unselect_all_message' => esc_html__('Unselect all', 'talentlms'),
        );

        //Enqueue styles
        wp_enqueue_style('tlms-admin');


        //Enqueue scripts
        wp_localize_script('tlms-admin', 'translations', $translations_array);
        wp_enqueue_script('tlms-admin');
    }

    public function tlms_enqueueFrontScripts(): void
    {
        $this->tlms_commonLibs();

        //Register styles
        wp_register_style(
            'tlms-front',
            TLMS_BASEURL . 'assets/css/talentlms.css',
            false,
            TLMS_VERSION
        );

        wp_enqueue_style('tlms-front');
    }


    public function tlms_enqueueStyles(): void
    {
        wp_register_style(
            'tlms-custom-css',
            Admin::getCustomCssFilePath(),
            false,
            TLMS_VERSION
        );
        wp_enqueue_style('tlms-custom-css');
    }
}
