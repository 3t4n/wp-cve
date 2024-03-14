<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Factory.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Wp_Plugin_Update_Api_Factory 
{
    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @return IfwPsn_Wp_Plugin_Update_Api_Envato|IfwPsn_Wp_Plugin_Update_Api_WooCommerce|IfwPsn_Wp_Plugin_Update_Api_Edd
     */
    public static function get(IfwPsn_Wp_Plugin_Manager $pm)
    {
        $updateApiName = $pm->getConfig()->plugin->updateApi;

        switch ($updateApiName) {

            case 'woocommerce':

                require_once $pm->getPathinfo()->getRootLib() . 'IfwPsn/Wp/Plugin/Update/Api/WooCommerce.php';
                $updateApi = new IfwPsn_Wp_Plugin_Update_Api_WooCommerce($pm, $pm->getConfig()->plugin->id);
                break;

            case 'edd':

                require_once $pm->getPathinfo()->getRootLib() . 'IfwPsn/Wp/Plugin/Update/Api/Edd.php';

                $updateApi = new IfwPsn_Wp_Plugin_Update_Api_Edd($pm, $pm->getConfig()->plugin->id);
                $itemName = $pm->getEnv()->getName();
                $itemName = str_replace('ASA2', 'ASA 2', $itemName);
                $updateApi->setItemName($itemName);

                $activationData = apply_filters('ifw_update_api_get_activation_data-'. $pm->getSlugFilenamePath(), array());
                if (isset($activationData['license']) && !empty($activationData['license'])) {
                    $updateApi->setLicense($activationData['license']);
                }
                break;

            default:
                require_once $pm->getPathinfo()->getRootLib() . 'IfwPsn/Wp/Plugin/Update/Api/Envato.php';
                $updateApi = new IfwPsn_Wp_Plugin_Update_Api_Envato($pm);
        }

        return $updateApi;
    }
}
