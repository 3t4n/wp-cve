<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class LanguageReportManager
{
	private const ISO_CODE_SHOP_TO_ISO_CODE_MIP = [
		'sl_SI' => 'si_SI',
		'nb_NO' => 'no_NO',
	];

    /** @var WoocommerceApiAdapterService */
    protected $woocommerceApiAdapterService;

    public function __construct()
    {
        $this->woocommerceApiAdapterService = new WoocommerceApiAdapterService();
    }

    /**
     * @return string
     * @throws WooCommerceApiExceptionInterface
     */
    public function getDefaultLanguageIsoCode(): string
    {
        $systemStatusReport = $this->woocommerceApiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_SYSTEM_STATUS);
        $languageIsoCode = $systemStatusReport['environment']['language'];

	    if (\array_key_exists($languageIsoCode, self::ISO_CODE_SHOP_TO_ISO_CODE_MIP)) {
		    return self::ISO_CODE_SHOP_TO_ISO_CODE_MIP[$languageIsoCode];
	    }

	    return $languageIsoCode;
    }
}