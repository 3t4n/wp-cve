<?php

namespace TalentlmsIntegration\Helpers;

use TalentlmsIntegration\Pages\Errors;
use Exception;
use TalentLMS;
use TalentLMS_ApiError;

trait TalentLMSApiIntegrationHelper
{

    /**
     * @throws TalentLMS_ApiError
     */
    public function enableTalentLMSLib(): void
    {
        try {
            if (empty(get_option('tlms-domain')) || empty(get_option('tlms-apikey'))) {
                throw new TalentLMS_ApiError(
                    esc_html__('You need to specify a TalentLMS domain and a TalentLMS API key.', 'talentlms')
                );
            }

            TalentLMS::setDomain(esc_html(get_option('tlms-domain')));
            TalentLMS::setApiKey(esc_html(get_option('tlms-apikey')));

            // Sample call to siteinfo to test whether API is enabled for the domain
            $siteInfo = \TalentLMS_Siteinfo::get();
        } catch (Exception $e) {
            $err = new Errors();
            $err->tlms_logError($e->getMessage());
            $err->tlms_ManualShowWarnings();
        }
    }
}
