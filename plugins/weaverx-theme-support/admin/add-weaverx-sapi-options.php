<?php
/* This loads the Admin stuff. It is invoked from functions.php.
 *
 * This ultimately will be used to load different admin interfaces -
 * like the a default Customizer version for WP.org, or the traditional Theme Options version (which it does now)
 */

if (current_user_can('edit_posts')) {


    add_action('admin_head', 'weaverx_admin_ts_head');

    function weaverx_admin_ts_head(): void
    {    // action definition
        require_once(dirname(__FILE__) . '/admin-lib-ts.php');
        require_once(dirname(__FILE__) . '/admin-lib-ts-2.php');

        add_action('weaverx_admin_saverestore', 'weaverx_ts_weaverx_admin_saverestore');
        add_action('weaverx_admin_subthemes', 'weaverx_ts_weaverx_admin_subthemes');
        add_action('weaverx_admin_mainopts', 'weaverx_ts_weaverx_admin_mainopts');
        add_action('weaverx_admin_advancedopts', 'weaverx_ts_weaverx_admin_advancedopts');
    }

    function weaverx_ts_weaverx_admin_subthemes(): void
    {

        require_once(dirname(__FILE__) . '/admin-subthemes.php');

        weaverx_admin_subthemes();
    }

    function weaverx_ts_weaverx_admin_mainopts(): void
    {

        require_once(dirname(__FILE__) . '/admin-mainopts.php');

        weaverx_admin_mainopts();
    }

    function weaverx_ts_weaverx_admin_advancedopts(): void
    {

        require_once(dirname(__FILE__) . '/admin-advancedopts.php');

        weaverx_admin_advancedopts();
    }

    function weaverx_ts_weaverx_admin_saverestore(): void
    {

        require_once(dirname(__FILE__) . '/admin-saverestore.php');

        weaverx_ts_admin_saverestore();
    }

}    // END IF CAN EDIT POSTS ---------------------------------------------------------------------

