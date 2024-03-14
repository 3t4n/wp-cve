<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Edd.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Wp_Plugin_Update_Api_Edd extends IfwPsn_Wp_Plugin_Update_Api_Abstract
{
    /**
     * @var IfwPsn_Wp_Http_Request
     */
    protected $_request;

    /**
     * @var string
     */
    protected $_itemName;




    /**
     * Request for plugin information
     *
     * @param $def
     * @param $action
     * @param $args
     * @return mixed
     */
    public function getPluginInformation($def, $action, $args)
    {
        if ( $action != 'plugin_information' ) {
            return $def;
        }

        $plugin_slug = $this->_pm->getSlug();

        if (!isset($args->slug) || ($args->slug != $this->_pm->getSlug())) {
            // IMPORTANT:
            // this plugin is not responsible for this request
            // return def to not break other plugins
            return $def;
        }

        $pluginSlug = $this->_pm->getSlugFilenamePath();

        // Get the current version
        $plugin_info = get_site_transient('update_plugins');

        if (!empty($this->_pm->getConfig()->debug->update)) {
            $this->_pm->getLogger()->debug('Plugin info check:');
            $this->_pm->getLogger()->debug(var_export($plugin_info, true));
        }

        // create request
        $request = $this->_getRequest();

        $request
            ->addData('edd_action', 'get_version')
            ->addData('slug', $plugin_slug)
            ->setSendMethod('get')
            ->addData('fields', array(
                'banners' => false,
                'reviews' => false,
            ));
        ;

        $response = $request->send();

        if ($response->isSuccess()) {

            $responseBody = $response->getBody();
            $responseBody = json_decode($responseBody);

            if (is_object($responseBody)) {

                if (isset($responseBody->sections)) {
                    $responseBody->sections = maybe_unserialize($responseBody->sections);
                }
                if (isset($responseBody->banners) && is_object($responseBody->banners)) {
                    $responseBody->banners = json_decode(json_encode($responseBody->banners), true);
                }

                $result = $responseBody;

            } else {
                $result = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
            }

        } else {

            $result = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.', 'ifw') . '</p> <p><a href="javascript:void(0)" onclick="document.location.reload(); return false;">'. __('Try again', 'ifw') . '</a>', $response->getErrorMessage());
        }

        if (!empty($this->_pm->getConfig()->debug->update)) {
            $this->_pm->getLogger()->debug(' --- Plugin info check response --- ');
            $this->_pm->getLogger()->debug(var_export($response, true));
        }

        return $result;
    }

    /**
     * @param $updateData
     * @return mixed
     */
    public function getUpdateData($updateData)
    {
        $plugin_slug = $this->_pm->getSlug();

        if (!is_plugin_active($this->_pm->getSlugFilenamePath()) ||
            !$this->_pm->isPremium() ||
            !is_object($updateData) ||
            !property_exists($updateData, 'checked') ||
            empty($updateData->checked) ) {
            return $updateData;
        }

        if (!empty($this->_pm->getConfig()->debug->update)) {
            $this->_pm->getLogger()->debug(' --- Update check data '. $plugin_slug . ' --- ');
            $this->_pm->getLogger()->debug(var_export($updateData, true));
        }
        // create request
        $request = $this->_getRequest();

        $request
            ->addData('edd_action', 'get_version')
            ->addData('slug', $plugin_slug)
            ->setSendMethod('get')
        ;

        $response = $request->send();

        if ($this->_pm->isPremium() && $response->isSuccess()) {

            $responseBody = $response->getBody();
            $responseBody = json_decode($responseBody);

            if (!empty($this->_pm->getConfig()->debug->update)) {
                $this->_pm->getLogger()->debug('Update check response:');
                $this->_pm->getLogger()->debug(var_export($responseBody, true));
            }

            if (is_object($responseBody)) {

                if (isset($responseBody->sections)) {
                    $responseBody->sections = maybe_unserialize($responseBody->sections);
                }

                if (isset($responseBody->new_version)) {

                    $newVersion = new IfwPsn_Util_Version($responseBody->new_version);
                    $currentVersion = $this->_pm->getEnv()->getVersion();

                    if ($newVersion->isGreaterThan($currentVersion)) {
                        // Feed the update data into WP updater
                        $updateData->response[$this->_pm->getPathinfo()->getFilenamePath()] = $responseBody;

                        delete_transient($this->_pm->getAbbrLower() . '_auto_update');
                    }
                }

                $updateData->last_checked = time();
                $updateData->checked[$this->_pm->getPathinfo()->getFilenamePath()] = $this->_pm->getEnv()->getVersion();
            }
        }

        return $updateData;
    }

    /**
     * Fires at the end of the update message container in each row of the plugins list table.
     *
     * @param array $plugin_data An array of plugin data.
     * @param $meta_data
     */
    public function afterPluginRow($plugin_data, $meta_data)
    {
        $pluginSlug = $this->_pm->getSlugFilenamePath();

        do_action('ifw_after_plugin_row-' . $pluginSlug);
    }

    /**
     * @param array $plugin_data
     * @param $meta_data
     */
    public function getUpdateInlineMessage($plugin_data, $meta_data)
    {
        do_action('ifw_plugin_update_inline_message-' . $this->_pm->getSlugFilenamePath());
    }

    /**
     * @param $license
     * @param array $options
     * @return mixed
     */
    public function getLicenseStatus($license, array $options = array())
    {
        $result = '';
        $request = $this->_getRequest();

        if ($request instanceof IfwPsn_Wp_Http_Request) {
            $request
                ->addData('edd_action', 'check_license')
                ->addData('item_name', $this->getItemName())
                ->addData('license', $license)
            ;

            $response = $request->send();

            if ($response->isSuccess()) {

                $responseBody = trim($response->getBody());
                $result = json_decode($responseBody, true);

            }
        }

        return $result;
    }

    /**
     * @param $license
     * @param array $options
     * @return bool
     */
    public function isActiveLicense($license, array $options = array())
    {
        $status = $this->getLicenseStatus($license, $options);

        if (isset($status['license']) && $status['license'] == 'valid') {
            return true;
        }

        return false;
    }

    /**
     * @param $license
     * @param array $options
     * @return bool
     */
    public function getLicenseExpiryDate($license, array $options = array())
    {
        $expiryDate = '';

        $status = $this->getLicenseStatus($license, $options);

        if (isset($status['expires'])) {
            $expiryDate = $status['expires'];
        }

        if (!empty($expiryDate)) {
            $expiryDate = IfwPsn_Wp_Date::format($expiryDate);
        }

        return $expiryDate;
    }

    /**
     * @param $license
     * @param array $options
     * @return IfwPsn_Wp_Http_Response|string
     */
    public function activate($license, array $options = array())
    {
        $response = new IfwPsn_Wp_Plugin_Update_Api_Response_Activate(false);

        $request = $this->_getRequest();

        if ($request instanceof IfwPsn_Wp_Http_Request) {
            $request
                ->addData('edd_action', 'activate_license')
                ->addData('item_name', $this->getItemName())
                ->addData('license', $license)
            ;

            $result = $request->send();

            if ($result->isSuccess()) {
                // got response from license api
                $resultData = $result->getArray();

                if (!$this->_pm->getEnv()->isProduction()) {
                    // log result in dev mode
                    $this->_pm->getLogger()->debug('License activation remote response:');
                    $this->_pm->getLogger()->debug(var_export($resultData, true));
                }

                $msg = '';

                if (isset($resultData['success']) && $resultData['success'] == '1' &&
                    isset($resultData['license']) && $resultData['license'] == 'valid') {

                    $response->setSuccess(true);

                    if (isset($resultData['activations_left'])) {
                        $response->setActivationsLeft($resultData['activations_left']);
                    }
                    if (isset($resultData['license_limit'])) {
                        $response->setLicenseLimit($resultData['license_limit']);
                    }
                    if (isset($resultData['customer_email'])) {
                        $response->setCustomerEmail($resultData['customer_email']);
                    }
                    if (isset($resultData['customer_name'])) {
                        $response->setCustomerName($resultData['customer_name']);
                    }
                    if (isset($resultData['multisite']) && $resultData['multisite'] === true) {
                        $response->setMultisite(true);
                    }


                    if (isset($resultData['activations_left']) && isset($resultData['license_limit'])) {
                        $msg .= sprintf( __('Activations left: %d out of %d.', 'ifw'), (int)$resultData['activations_left'], (int)$resultData['license_limit']);
                    }

                } else {

                    // no expected API response

                    if (isset($resultData['error'])) {
                        switch ($resultData['error']) {
                            case 'missing':
                                $msg = apply_filters('ifw_plugin_update_api_edd_msg_license_not_exist', __('License does not exist.', 'ifw'), $license);
                                break;
                            case 'revoked':
                                $msg = __('License key revoked.', 'ifw');
                                break;
                            case 'no_activations_left':
                                $msg = apply_filters('ifw_plugin_update_api_edd_msg_no_activations_left', __('No activations left.', 'ifw'), $license);
                                break;
                            case 'expired':
                                $msg = apply_filters('ifw_plugin_update_api_edd_msg_license_expired', __('License expired.', 'ifw'), $license);
                                break;
                            default:
                                $msg = apply_filters('ifw_plugin_update_api_edd_msg_license_could_not_be_activated', __('License could not be activated.', 'ifw'), $license);
                        }

                    } else {
                        $msg = __('Unexpected result.', 'ifw') . ' ' . $this->getLicenseServerConnectionErrorMessage();
                    }
                }

            } else {

                $msg = __('Unable to connect to update API.', 'ifw') . ' ' . $this->getLicenseServerConnectionErrorMessage();
                $error = $result->getErrorMessage();
                if (!empty($error)) {
                    $msg .= ' ' . $result->getErrorMessage();
                }
            }

            $response->setMessage($msg);
        }

        return $response;
    }

    /**
     * @param $license
     * @param array $options
     * @return IfwPsn_Wp_Http_Response|string
     */
    public function deactivate($license, array $options = array())
    {
        $response = new IfwPsn_Wp_Plugin_Update_Api_Response_Deactivate(false);

        $request = $this->_getRequest();

        if ($request instanceof IfwPsn_Wp_Http_Request) {
            $request
                ->addData('edd_action', 'deactivate_license')
                ->addData('item_name', $this->getItemName())
                ->addData('license', $license)
            ;

            $result = $request->send();

            if ($result->isSuccess()) {
                // got response from license api
                $resultData = $result->getArray();

                if (!$this->_pm->getEnv()->isProduction()) {
                    // log result in dev mode
                    $this->_pm->getLogger()->debug('License deactivation remote response:');
                    $this->_pm->getLogger()->debug(var_export($resultData, true));
                }

                if (isset($resultData['success']) && $resultData['success'] == '1' &&
                    isset($resultData['license']) && $resultData['license'] == 'deactivated') {

                    // license has been deactivated
                    $response->setSuccess(true);

                } else {
                    $msg = __('License could not be deactivated.', 'ifw') . ' ' . __('Please try to delete it.', 'ifw');
                }

            } else {

                $msg = __('Unable to connect to update API.', 'ifw');
                $error = $result->getErrorMessage();
                if (!empty($error)) {
                    $msg .= ' ' . $result->getErrorMessage();
                }
            }

            if (isset($msg)) {
                $response->setMessage($msg);
            }
        }

        return $response;
    }

    /**
     * @return IfwPsn_Wp_Http_Request
     */
    protected function _getRequest()
    {
        if ($this->_request === null) {
            $this->_request = new IfwPsn_Wp_Http_Request($this->_pm);

            $this->_request->setUrl($this->_pm->getConfig()->plugin->updateServer);
//            $this->_request->setTimeout(15);
            $this->_request->addData('url', IfwPsn_Wp_Proxy_Blog::getUrl());
            $this->_request->addData('slug', $this->_pm->getSlug());
            if ($this->hasLicense()) {
                $this->_request->addData('license', $this->getLicense());
            }
            $this->_request->addData('item_name', $this->getItemName());
        }

        return $this->_request;
    }

    /**
     * @param string $itemName
     */
    public function setItemName($itemName)
    {
        $this->_itemName = $itemName;
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return urlencode($this->_itemName);
    }
}
