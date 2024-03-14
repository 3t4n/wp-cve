<?php

namespace WilokeEmailCreator\Shared;

use WilokeEmailCreator\DataFactory\Shared\TraitGetProductsWC;
use WilokeEmailCreator\Email\Shared\TraitHandleReplaceSubjectEmail;
use WilokeEmailCreator\Shared\Twig\TwigTemplate;

class RenderHtmlTemplate
{
	use TraitHandleReplaceSubjectEmail, TraitGetProductsWC;

	private static ?RenderHtmlTemplate $oSelf = null;

	public static function init(): ?RenderHtmlTemplate
	{
		if (self::$oSelf == null) {
			self::$oSelf = new self();
		}
		return self::$oSelf;
	}

	/**
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Twig\Error\LoaderError
	 */
	public function renderHtmlTemplate(array $aSession, $aOrderProduct, $aArgs,$variables=[]): string
	{
		$html = '';
		$aKeyOrderProducts = [];
		$aKeyProducts = [];
		foreach ($aSession as $aItem) {
			$aSettings = $aItem['settings'];
			foreach ($aItem['fieldDefinitions'] as $aField) {
				if (!is_array($aField)) {
					continue;
				}
				if ($key = array_search('orderProducts', $aField)) {
					$aKeyOrderProducts[] = $key;
				}
				if ($key = array_search('products[products]!', $aField)) {
					$aKeyProducts[] = $key;
				}
			}
			if (!empty($aKeyOrderProducts)) {
				foreach ($aKeyOrderProducts as $key) {
					$aSettings[$key] = $aOrderProduct;
				}
			}

			if (!empty($aKeyProducts)) {
				foreach ($aKeyProducts as $key) {
					if (!isset($aSettings[$key])) {
						continue;
					}
					$aSettingsData = $aSettings[$key];
					$aSettingsData['nodes'] = BuilderQueryProducts::init()
						->setRawData([
							'orderProducts'      => $aOrderProduct,
							'productPlaceholder' => $aSettingsData['nodes'],
						])
						->setTypeProduct($aSettingsData['type'])
						->query();
					$aSettings[$key] = $aSettingsData;
				}
			}
			$aDataSettings = [];
			$aDataPlaceholder = !empty($variables)?$variables:$aArgs;
			$aFieldPlaceholderSubjectEmail = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($aDataPlaceholder);
			foreach ($aSettings as $key => $setting) {
				if (is_string($setting)) {
					$aDataSettings[$key] = str_replace(
						array_keys($aFieldPlaceholderSubjectEmail),
						array_values($aFieldPlaceholderSubjectEmail),
						$setting);
				} else {
					$aDataSettings[$key] = $setting;
				}
			}
			$twig = preg_replace('/\s*\|\s*format_currency/', '', $aItem['twig']);
			$html .= str_replace(
				array_keys($aFieldPlaceholderSubjectEmail),
				array_values($aFieldPlaceholderSubjectEmail),
				TwigTemplate::init()->renderTemplate($twig ?? '', $aDataSettings));
		}
		return $html;
	}
}
