<?php

class OfficeGuyDokanMarketplace
{
    public static function Init()
    {
        if (!OfficeGuyDokanMarketplace::PluginIsActive())
            return;

        add_action('show_user_profile', 'OfficeGuyDokanMarketplace::OfficeGuyUserAPIKeyFields');
        add_action('edit_user_profile', 'OfficeGuyDokanMarketplace::OfficeGuyUserAPIKeyFields');
        // add_action( 'user_new_form', 'OfficeGuyDokanMarketplace::OfficeGuyUserAPIKeyFields' );

        add_action('personal_options_update', 'OfficeGuyDokanMarketplace::SaveOfficeGuyUserAPIKeyFields');
        add_action('edit_user_profile_update', 'OfficeGuyDokanMarketplace::SaveOfficeGuyUserAPIKeyFields');
    }

    public static function OfficeGuyUserAPIKeyFields($User)
    {
        if (!dokan_is_user_seller($User->ID))
            return;

        $OfficeGuyValidCredentialsMsg = get_the_author_meta('OfficeGuyValidCredentials', $User->ID);
        if ($OfficeGuyValidCredentialsMsg != null)
        {
?>
            <div class="error">
                <p><?php echo 'SUMIT error: ' . $OfficeGuyValidCredentialsMsg; ?></p>
            </div>
        <?php
        }

        ?>
        <h3>SUMIT API</h3>

        <table class="form-table">
            <tr>
                <th><label for="officeguycompanyid">Company ID</label></th>
                <td>
                    <input type="text" name="officeguycompanyid" id="officeguycompanyid" value="<?php echo esc_attr(get_the_author_meta('OfficeGuyCompanyID', $User->ID)); ?>" class="regular-text" /><br />
                    <span class="description">SUMIT Company ID for vender</span>
                </td>
            </tr>
            <tr>
                <th><label for="officeguyapikey">API Private Key</label></th>
                <td>
                    <input type="text" name="officeguyapikey" id="officeguyapikey" value="<?php echo esc_attr(get_the_author_meta('OfficeGuyAPIKey', $User->ID)); ?>" class="regular-text" /><br />
                    <span class="description">SUMIT API Key for vender</span>
                </td>
            </tr>
        </table>
<?php
    }

    public static function SaveOfficeGuyUserAPIKeyFields($UserID)
    {
        if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-user_' . $UserID))
            return;

        if (!current_user_can('edit_user', $UserID))
            return false;

        update_user_meta($UserID, 'OfficeGuyCompanyID', $_POST['officeguycompanyid']);
        update_user_meta($UserID, 'OfficeGuyAPIKey', $_POST['officeguyapikey']);

        if (!empty($_POST['officeguycompanyid']) && !empty($_POST['officeguyapikey']))
        {
            $Response = OfficeGuyAPI::CheckCredentials($_POST['officeguycompanyid'], $_POST['officeguyapikey']);
            update_user_meta($UserID, 'OfficeGuyValidCredentials', $Response);
        }
        else
            delete_user_meta($UserID, 'OfficeGuyValidCredentials');
    }

    public static function GetProductVendorCredentials()
    {
        $ProductCredentials = array();
        $ProductIDs = OfficeGuySubscriptions::GetCartProductIDs();
        foreach ($ProductIDs as $ProductID)
        {
            $VendorID = get_post_field('post_author', $ProductID);
            $ProductCredentials[$ProductID]['OfficeGuyCompanyID'] = get_the_author_meta('OfficeGuyCompanyID', $VendorID);
            $ProductCredentials[$ProductID]['OfficeGuyAPIKey'] = get_the_author_meta('OfficeGuyAPIKey', $VendorID);
        }
        return $ProductCredentials;
    }

    public static function PluginIsActive()
    {
        return is_plugin_active('dokan-lite/dokan.php') 
            || is_plugin_active('dokan/dokan.php') 
            || is_plugin_active('dokan-pro/dokan-pro.php');
    }

    public static function VendorsInCartCount()
    {
        $ProductCredentials = OfficeGuyDokanMarketplace::GetProductVendorCredentials();
        $CompanyIDs = array();

        foreach ($ProductCredentials as $ProductCredential)
        {
            $CompanyID = $ProductCredential['OfficeGuyCompanyID'];
            if (is_numeric($CompanyID))
                $CompanyIDs[] = $CompanyID;
        }

        $CompanyIDs = array_unique($CompanyIDs);
        return count($CompanyIDs);
    }
}

add_action('admin_init', 'OfficeGuyDokanMarketplace::Init');

//debug
// add_action('woocommerce_cart_loaded_from_session', 'OfficeGuyDokanMarketplace::GetProductVendorCredentials');

?>