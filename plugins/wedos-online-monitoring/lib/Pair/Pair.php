<?php
namespace WEDOS\Mon\WP\Pair;

use PHPF\WP\Page\Notices;

/**
 * Pair website with check
 *
 * @author    Petr Stastny <petr@stastny.eu>
 * @copyright WEDOS Internet, a.s.
 * @license   GPLv3
 */
class Pair
{
    public static function performPair($pairToken)
    {
        update_option('won_pair_token', $pairToken, false);

        $apiClient = new \WEDOS\Mon\WP\ApiClient\MonApiClient('pair', 'pair', $pairToken);
        $result = $apiClient->send();
        $outputData = $apiClient->getResponseData();

        //var_dump($result);
        //var_dump($outputData);
        //exit;

        if (is_object($result) && get_class($result) == 'WP_Error') {
            if (isset($result->errors['http_request_failed'])) {
                Notices::add('notice-error', __('Error when connecting to WEDOS OnLine:', 'wedos-online-monitoring').' '.$result->errors['http_request_failed'][0]);

            } else {
                Notices::add('notice-error', __('Unknown error when connection to WEDOS OnLine', 'wedos-online-monitoring'));
            }

        } elseif ($apiClient->getHttpCode() != 200) {
            if (is_object($outputData)) {
                if ($outputData->error->code == 'C507') {
                    Notices::add('notice-error', __('Request failed. It seems that pair request is no more valid. Please try to create new request from check detail.', 'wedos-online-monitoring').' (C507)');

                } else {
                    Notices::add('notice-error', $outputData->error->error.'. '.__('Please try to create new request from check detail.', 'wedos-online-monitoring').' ('.$outputData->error->code.')');
                }

            } else {
                Notices::add('notice-error', __('Error when connecting to WEDOS OnLine:', 'wedos-online-monitoring').' '.$apiClient->getHttpMessage().' ('.$apiClient->getHttpCode().')');
            }

        } elseif (empty($outputData->checkId)) {
            Notices::add('notice-error', __('Response error from WEDOS OnLine. Please try again.', 'wedos-online-monitoring'));

        } else {
            // all success

            delete_option('won_pair_token');

            update_option('won_pair_checkId', $outputData->checkId, false);
            update_option('won_pair_apiToken', $outputData->apiToken, false);
            update_option('won_pair_publicToken', $outputData->publicToken, false);

            wp_redirect($outputData->redir);
            exit;
        }

        // error, redirect back to dashboard
        wp_safe_redirect(admin_url('admin.php?page=won-dashboard&pair='.$pairToken));
        exit;
    }


    /**
     * Check that we are still paired (API token is valid)
     *
     * @param bool $force force API call
     * @return bool
     */
    public static function connectionCheck($force = false)
    {
        $lastCheck = get_option('won_pair_connectionCheck');

        if ($lastCheck && time() - $lastCheck < 6 * 60 * 60 && !$force) {
            // no need to perform the check too often
            return true;
        }

        $apiClient = new \WEDOS\Mon\WP\ApiClient\MonApiClient('connectionCheck');
        $result = $apiClient->send();
        $outputData = $apiClient->getResponseData();
        $code = $apiClient->getHttpCode();

        if ($code == 200 && !empty($outputData->success)) {
            update_option('won_pair_connectionCheck', time(), false);
            return true;
        }

        if ($code == 403 && !empty($outputData->error) && $outputData->error->code == 'C507') {
            // API token seems to be no more valid
            return false;
        }

        // other error, maybe connection issue or application error,
        // we do nothing

        //var_dump($result);
        //var_dump($outputData);
        //exit;

        return true;
    }
}
