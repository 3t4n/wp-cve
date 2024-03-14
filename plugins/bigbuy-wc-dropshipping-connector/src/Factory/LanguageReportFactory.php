<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Model\LanguageReportModel;

class LanguageReportFactory
{
    /**
     * @param string $languageIsoCode
     * @return LanguageReportModel
     */
    public static function create(string $languageIsoCode): LanguageReportModel
    {
        $language = [];
        $language['LanguageID'] = 1;
        $language['Name'] = $languageIsoCode;
        $language['IsoCode'] = $languageIsoCode;
        $language['Active'] = true;
        $language['Default'] = true;

        $languageReportModel = new LanguageReportModel();
        $languageReportModel->Languages[] = $language;

        return $languageReportModel;
    }
}