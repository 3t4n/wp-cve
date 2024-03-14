<?php

namespace BingMapPro_OptionsManager;

if( ! defined('ABSPATH') ) die('No Access to this page');

class BingMapPro_OptionsManager {
    const CONTR_CAP  = 'contributor_cap';
    const AUTHOR_CAP = 'author_cap';
    const EDITOR_CAP = 'editor_cap';
    const HIDE_KEY   = 'hide_api_key';

    public function getOptionNamePrefix() {
        return 'BingMapPro' . '_';
    }

    public function getOptionMetaData() {
        return array();
    }


    public function getOptionNames() {
        return array_keys($this->getOptionMetaData());
    }


    protected function initOptions() {
    }


    protected function deleteSavedOptions() {
        $optionMetaData = $this->getOptionMetaData();
        if (is_array($optionMetaData)) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                $prefixedOptionName = $this->prefix($aOptionKey); // how it is stored in DB
                delete_option($prefixedOptionName);
            }
        }
    }


    public function getPluginDisplayName() {
        return get_class($this);
    }


    public function prefix($name) {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) { // 0 but not false
            return $name; // already prefixed
        }
        return $optionNamePrefix . $name;
    }


    public function &unPrefix($name) {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) {
            return substr($name, strlen($optionNamePrefix));
        }
        return $name;
    }


    public function getOption($optionName, $default = null) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB       
        $retVal = get_option($prefixedOptionName);
        if (!$retVal && $default) {
            $retVal = $default;
        }
        return $retVal;
    }


    public function deleteOption($optionName) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return delete_option($prefixedOptionName);
    }


    public function addOption($optionName, $value) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return add_option($prefixedOptionName, $value);
    }

    public function updateOption($optionName, $value) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return update_option($prefixedOptionName, $value);
    }

    public function getRoleOption($optionName) {
        $roleAllowed = $this->getOption($optionName);
        if (!$roleAllowed || $roleAllowed == '') {
            $roleAllowed = 'Administrator';
        }
        return $roleAllowed;
    }

    public function isUserRoleEqualOrBetterThan($roleName) {
        if ('Anyone' == $roleName) {
            return true;
        }
        $capability = $this->roleToCapability($roleName);
        return current_user_can($capability);
    }

    public function canUserDoRoleOption($optionName) {
        $roleAllowed = $this->getRoleOption($optionName);
        if ('Anyone' == $roleAllowed) {
            return true;
        }
        return $this->isUserRoleEqualOrBetterThan($roleAllowed);
    }

    public function registerSettings() {
        $settingsGroup = get_class($this) . '-settings-group';
        $optionMetaData = $this->getOptionMetaData();
        foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
            register_setting($settingsGroup, $aOptionMeta);
        }
    }
 

    protected function getMySqlVersion() {
        global $wpdb;
        $rows = $wpdb->get_results('select version() as mysqlversion');
        if (!empty($rows)) {
             return $rows[0]->mysqlversion;
        }
        return false;
    }

    protected function get_editor_cap(){
        return $this->getOption( self::EDITOR_CAP, false );
    }

    protected function set_editor_cap( $value ){
        if( ! $this->addOption( self::EDITOR_CAP,  $value ) )
            $this->updateOption( self::EDITOR_CAP,  $value );
    }

    protected function get_author_cap(){
        return $this->getOption( self::AUTHOR_CAP, false );
    }

    protected function set_author_cap( $value ){
        if( ! $this->addOption( self::AUTHOR_CAP,  $value ) )
            $this->updateOption( self::AUTHOR_CAP,  $value );  
    }

    protected function get_contributor_cap(){        
        return $this->getOption( self::CONTR_CAP, false );
    }

    protected function set_contributor_cap( $value ){
        if( ! $this->addOption( self::CONTR_CAP ,  $value ) )
            $this->updateOption( self::CONTR_CAP ,  $value );  
    }

    protected function get_hide_api_key(){        
        return $this->getOption( self::HIDE_KEY, false );
    }

    protected function set_hide_api_key( $value ){
        if( ! $this->addOption( self::HIDE_KEY ,  $value ) )
            $this->updateOption( self::HIDE_KEY ,  $value );  
    }
   


 
    public function getEmailDomain() {
        // Get the site domain and get rid of www.
        $sitename = strtolower($_SERVER['SERVER_NAME']);
        if (substr($sitename, 0, 4) == 'www.') {
            $sitename = substr($sitename, 4);
        }
        return $sitename;
    }

}