<?php

class OfficeGuyWCFMMarketplace
{
    public static function InsertOfficeGuyAPIFields($GeneralFields, $VendorID)
    {
        //לא להוסיף את השדות בפעם הראשונה כי עדיין אינם נשמרים
        if ($VendorID == 99999)
            return $GeneralFields;

        $OfficeGuyValidCredentialsMsg = get_the_author_meta('OfficeGuyValidCredentials', $VendorID);
        if ($OfficeGuyValidCredentialsMsg != null && $OfficeGuyValidCredentialsMsg != 'Success')
        {
            //חסום זמנית עד שניתן יהיה להראות שגיאה מיד בשמירה
?>
            <!-- <div class="wcfm-message wcfm-error" tabindex="-1" style="display: block;"><?php echo 'SUMIT error: ' . $OfficeGuyValidCredentialsMsg; ?></div> -->
<?php
        }

        $VendorData = get_user_meta($VendorID, 'wcfmmp_profile_settings', true);
        $CompanyID = isset($VendorData['OfficeGuyCompanyID']) ? esc_attr($VendorData['OfficeGuyCompanyID']) : '';
        $APIKey = isset($VendorData['OfficeGuyAPIKey']) ? esc_attr($VendorData['OfficeGuyAPIKey']) : '';
        if (isset($GeneralFields['store_name']))
        {
            $GeneralFields['officeguycompanyid'] = array('label' => __('SUMIT Company ID', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $CompanyID);
            $GeneralFields['officeguyapikey'] = array('label' => __('SUMIT API Key', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $APIKey);
        }
        return $GeneralFields;
    }

    public static function SaveOfficeGuyAPIFields($UserID, $WCFMSettingsForm)
    {
        $WCFMSettingsFormDataNew = array();
        parse_str($_POST['wcfm_settings_form'], $WCFMSettingsFormDataNew);

        // $wcfmmp_profile_settings = get_the_author_meta('wcfmmp_profile_settings', $UserID);

        // if (($WCFMSettingsFormDataNew['officeguycompanyid'] == $wcfmmp_profile_settings['officeguycompanyid']) && ($WCFMSettingsFormDataNew['officeguyapikey'] == $wcfmmp_profile_settings['officeguyapikey']))
        //     return;

        $WCFMSettingsForm_data_storetype = array();
        if (isset($WCFMSettingsFormDataNew['officeguycompanyid']) && !empty($WCFMSettingsFormDataNew['officeguycompanyid']))
            $WCFMSettingsForm_data_storetype['OfficeGuyCompanyID'] = $WCFMSettingsFormDataNew['officeguycompanyid'];
        if (isset($WCFMSettingsFormDataNew['officeguyapikey']) && !empty($WCFMSettingsFormDataNew['officeguyapikey']))
            $WCFMSettingsForm_data_storetype['OfficeGuyAPIKey'] = $WCFMSettingsFormDataNew['officeguyapikey'];

        $WCFMSettingsForm = array_merge($WCFMSettingsForm, $WCFMSettingsForm_data_storetype);
        update_user_meta($UserID, 'wcfmmp_profile_settings', $WCFMSettingsForm);

        //חסום זמנית עד שניתן יהיה להראות שגיאה מיד בשמירה
        // if (!empty($WCFMSettingsFormDataNew['officeguycompanyid']) && !empty($WCFMSettingsFormDataNew['officeguyapikey']))
        // {
        // $Response = OfficeGuyAPI::CheckCredentials($WCFMSettingsFormDataNew['officeguycompanyid'], $WCFMSettingsFormDataNew['officeguyapikey']);
        // update_user_meta($UserID, 'OfficeGuyValidCredentials', $Response);

        // if ($Response != 'Success')   
        // WC_Admin_Settings::add_error('THIS WHENT BAD');
        // echo '<script>alert("THIS WHENT BAD")</script>';
        // echo '{"status": true, "message": "THIS WHENT BAD"}';
        //     add_action('admin_notices', 'custom_error_notice');
        // }
        // else
        //     delete_user_meta($UserID, 'OfficeGuyValidCredentials');
    }

    public static function GetProductVendorCredentials()
    {
        $ProductCredentials = array();
        $ProductIDs = OfficeGuySubscriptions::GetCartProductIDs();
        foreach ($ProductIDs as $ProductID)
        {
            $VendorID = get_post_field('post_author', $ProductID);
            $VendorData = get_user_meta($VendorID, 'wcfmmp_profile_settings', true);

            if (!empty($VendorData)
                && isset($VendorData['OfficeGuyCompanyID'])
                && !empty($VendorData['OfficeGuyCompanyID'])
                && isset($VendorData['OfficeGuyAPIKey'])
                && !empty($VendorData['OfficeGuyAPIKey']))
            {
                $ProductCredentials[$ProductID]['OfficeGuyCompanyID'] = $VendorData['OfficeGuyCompanyID'];
                $ProductCredentials[$ProductID]['OfficeGuyAPIKey'] = $VendorData['OfficeGuyAPIKey'];
            }
        }
        return $ProductCredentials;
    }

    public static function PluginIsActive()
    {
        return is_plugin_active('wc-multivendor-marketplace/wc-multivendor-marketplace.php');
    }

    public static function VendorsInCartCount()
    {
        $ProductCredentials = OfficeGuyWCFMMarketplace::GetProductVendorCredentials();
        $CompanyIDs = array();

        foreach ($ProductCredentials as $ProductCredntial)
        {
            $CompanyID = $ProductCredntial['OfficeGuyCompanyID'];
            if (is_numeric($CompanyID))
                $CompanyIDs[] = $CompanyID;
        }

        $CompanyIDs = array_unique($CompanyIDs);
        return count($CompanyIDs);
    }
}

add_filter('wcfm_marketplace_settings_fields_general', 'OfficeGuyWCFMMarketplace::InsertOfficeGuyAPIFields', 50, 2);
add_action('wcfm_wcfmmp_settings_update', 'OfficeGuyWCFMMarketplace::SaveOfficeGuyAPIFields', 50, 2);
add_action('wcfm_vendor_settings_update', 'OfficeGuyWCFMMarketplace::SaveOfficeGuyAPIFields', 50, 2);

?>