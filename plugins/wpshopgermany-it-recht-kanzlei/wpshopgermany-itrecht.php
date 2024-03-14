<?php

    /*
    Plugin Name: wpShopGermany - IT-Recht Kanzlei
    Plugin URI: http://wpshopgermany.maennchen1.de/
    Description: IT-Recht Kanzlei Integration in Wordpress/wpShopGermany
    Author: maennchen1.de
    Text Domain: wpsgit
    Version: 1.8
    Author URI: http://maennchen1.de/
    */
 
    require_once dirname(__FILE__).'/functions.php';
    require_once dirname(__FILE__).'/classes/wpsg_itrecht.class.php';

    function wpsg_itrecht__install() {

    } // function wpsg_itrecht__install()

    function wpsg_itrecht_uninstall() {

    } // function wpsg_itrecht_uninstall()

    $wpsg_itrecht = new wpsg_itrecht();

    // Shortcodes
    $arPageTypes = $wpsg_itrecht->getPageTypes();

    foreach ($arPageTypes as $page_key => $pagevar) {

        add_shortcode('wpsg_itrecht_'.$page_key, array($wpsg_itrecht, 'sc_wpsg_itrecht_'.$page_key));

    }

    if (is_admin()) {

        add_action('admin_menu', array(&$wpsg_itrecht, "admin_menu"));

    }

    function wpsg_itrecht_woocommerce_email_attachments($attachments, $email_id, $order) {

        if (!is_a($order, 'WC_Order') || !isset($email_id)) {

            return $attachments;

        }

        $wpsg_itrecht = new wpsg_itrecht();

        if ($email_id === 'customer_processing_order' || $email_id === 'new_order' || $email_id === 'customer_on_hold_order' || $email_id === 'customer_completed_order') {

            $arPageTypes = $wpsg_itrecht->getPageTypes();

            foreach ($arPageTypes as $page_key => $page) {

                if (\get_option('wpsgitrecht_woomail_'.$page_key) === '1') {

                    $lang = 'de';
                    $page_id = intval(\get_option('wpsgitrecht_page_'.$page_key));

                    if (defined('ICL_LANGUAGE_CODE') && function_exists('icl_object_id')) {

                        $trans_page_id = intval(icl_object_id($page_id, 'page' , false, ICL_LANGUAGE_CODE));

                        if ($trans_page_id > 0) {

                            $lang = ICL_LANGUAGE_CODE;
                            $page_id = $trans_page_id;

                        }

                    }

                    if ($page_id > 0) {

                        $wp_upload_dir = \wp_upload_dir();
                        $attachment_file = $wp_upload_dir['basedir'].'/it-recht/'.$page_id.'/'.$lang.'/'.get_option('wpsgitrecht_file_'.$lang.'_'.$page_key);

                        if (file_exists($attachment_file)) {

                            $attachments[] = $attachment_file;

                        }

                    }

                }

            }


        }

        return $attachments;

    }

    function wpsg_itrecht_wpsg_sendMail($ar) {

        $wpsg_itrecht = new wpsg_itrecht();

        $arPageTypes = $wpsg_itrecht->getPageTypes();

        foreach ($arPageTypes as $page_key => $page) {

            if (\get_option('wpsgitrecht_wpsgmail_'.$page_key) === '1') {

                $lang = 'de';
                $page_id = intval(\get_option('wpsgitrecht_page_'.$page_key));

                if (defined('ICL_LANGUAGE_CODE') && function_exists('icl_object_id')) {

                    $trans_page_id = intval(icl_object_id($page_id, 'page' , false, ICL_LANGUAGE_CODE));

                    if ($trans_page_id > 0) {

                        $lang = ICL_LANGUAGE_CODE;
                        $page_id = $trans_page_id;

                    }

                }

                if ($page_id > 0) {

                    $wp_upload_dir = \wp_upload_dir();
                    $attachment_file = $wp_upload_dir['basedir'].'/it-recht/'.$page_id.'/'.$lang.'/'.get_option('wpsgitrecht_file_'.$lang.'_'.$page_key);

                    if (file_exists($attachment_file)) {

                        $ar[7][] = $attachment_file;

                    }

                }

            }

        }

        return $ar;

    }

    add_action('wp_loaded', array(&$wpsg_itrecht, 'wp_loaded'));

    register_activation_hook(__FILE__, 'wpsg_itrecht__install');
    register_deactivation_hook(__FILE__, 'wpsg_itrecht_uninstall');


	 