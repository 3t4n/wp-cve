<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_PostinstallationModel {

    function updateInstallationStatusConfiguration(){
            $flag = get_option('majesticsupport_post_installation');
            if($flag == false){
                add_option( 'majesticsupport_post_installation', '1', '', 'yes' );
            }else{
                update_option( 'majesticsupport_post_installation', '1');
            }
    }

	function storeConfigurations($data){
        if (empty($data))
            return false;
        $error = false;
        unset($data['action']);
        unset($data['form_request']);
        foreach ($data as $key => $value) {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_config` SET `configvalue` = '" . esc_sql($value) . "' WHERE `configname`= '" . esc_sql($key) . "'";
            majesticsupport::$_db->query($query);
            if(majesticsupport::$_db->last_error == null){
                $status = 0;
            }else{
                $status = 1;
            }
        }
        if ($status == 0) {
            MJTC_message::MJTC_setMessage(esc_html(__('Configuration','majestic-support')).' '.esc_html(__('has been changed', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Configuration','majestic-support')).' '.esc_html(__('has not been changed', 'majestic-support')), 'error');
        }
        return;
    }

    function getConfigurationValues() {
        $this->updateInstallationStatusConfiguration();
        $query = "SELECT configname,configvalue
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` ";//WHERE configfor != 'ticketviaemail'";
        $data = majesticsupport::$_db->get_results($query);
        
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        foreach ($data AS $config) {
            majesticsupport::$_data[0][$config->configname] = $config->configvalue;
        }
        return;
    }


    function getPageList() {
        $query = "SELECT ID AS id, post_title AS text FROM `" . majesticsupport::$_db->prefix . "posts` WHERE post_type = 'page' AND post_status = 'publish' ";
        $pages = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $pages;
    }

}?>