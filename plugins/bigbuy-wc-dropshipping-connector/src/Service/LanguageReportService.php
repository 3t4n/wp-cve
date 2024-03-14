<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\LanguageReportFactory;
use WcMipConnector\Manager\LanguageReportManager;

class LanguageReportService
{
    protected $languageManager;

    /**
     * LanguageReportService constructor.
     */
    public function __construct()
    {
        $this->languageManager = new LanguageReportManager();
    }

    /**
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function getDefaultLanguageIsoCode(): array
    {
        $language = explode('_', $this->languageManager->getDefaultLanguageIsoCode());
        $languageReportModel = LanguageReportFactory::create($language[0]);

        return \json_decode(\json_encode($languageReportModel), true);
    }

    /**
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function getShopLanguages(): array
    {
        $language = explode('_', $this->languageManager->getDefaultLanguageIsoCode());
        $languages[] = $language[0];

        return $languages;
    }
}