<?php

namespace WpLHLAdminUi\LicenseKeys;

interface LicenseKeyDataInterface {

    /**
     * Host URL where the licanse needs to be verifies
     */
    public function get_license_host();
    /**
     * Consumer Key for API call
     * to where the license can be verfied
     */
    public function get_consumer_key();
    /**
     * Consumer Key for Api call
     * To where the licsense can be verfied
     */
    public function get_consumer_secret();

    /**
     * Name of the option where license key is stored
     */
    public function get_name_for_options_licensekey_bundle();
    /**
     * Name of array key in the option where license key is stored
     */
    public function get_name_for_license_key();
    /**
     * Name of the option where license date is stored
     */
    public function get_name_for_options_licensedate_bundle();
    /**
     * Name of array key in the option where license date is stored
     */
    public function get_name_for_license_key_date();
    
    /**
     * Url where the license can be purchased
     */
    public function get_plugin_purchase_link_url();
    /**
     * Link text for Link where the license can be purchased
     */
    public function get_plugin_purchase_link_text();

    /**
     * Name of the plugin
     */
    public function get_plugin_name();
    /**
     * Version of the plugin
     */
    public function get_version();

}