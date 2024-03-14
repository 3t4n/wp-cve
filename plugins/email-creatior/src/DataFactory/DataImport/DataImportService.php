<?php

namespace WilokeEmailCreator\DataFactory\DataImport;

use WilokeEmailCreator\DataFactory\Interfaces\IDataFactory;
use WilokeEmailCreator\DataFactory\Shared\TraitHandlePosts;
use WilokeEmailCreator\DataFactory\Shared\TraitHandleProducts;
use WilokeEmailCreator\Shared\Helper;
use WilokeEmailCreator\Shared\TraitHandleCheckSectionsDataObjects;

class DataImportService implements IDataFactory
{
	use TraitHandlePosts, TraitHandleProducts, TraitHandleCheckSectionsDataObjects;

	public array $aCategoriesOrder
		= ['order-number', 'order-products', 'pricings', 'products', 'total-table', 'address'];

	public function getTemplateDetail($templateId): array
	{
		$templateId = $templateId ?: 'template1';
		$fileName = str_replace('id', 'template', $templateId);
		$jData = file_get_contents(plugin_dir_path(__FILE__) . 'Configs/DetailTemplate/' . $fileName . '.json');
		if (empty($jData)) {
			$fileName = str_replace('id-', 'template-id-', $templateId);
			$jData = file_get_contents(plugin_dir_path(__FILE__) . 'Configs/DetailTemplate/' . $fileName . '.json');
		}
		$aRawData = json_decode($jData, true);
		$aData = $aRawData;
		$aSectionData = [];
		if (!empty($aRawData['sections'])) {
			foreach ($aRawData['sections'] as $aSection) {
				$aSectionData[] = $this->replaceUrlImageSection($aSection);
			}
		}
		$aData['sections'] = $this->handleCheckSectionsDataObjects($aSectionData);
		return $aData;
	}

	public function replaceUrlImageSection(array $aSection): array
	{
		$aSettingSectionData = $aSection['settings'] ?? [];
		$aSettingSectionRaw = $aSection['settings'] ?? [];
		if (isset($aSettingSectionRaw['sectionBackgroundImage'])) {
			$aSettingSectionData['sectionBackgroundImage']
				= $this->handleReplaceUrlImage($aSettingSectionRaw['sectionBackgroundImage']);
		}
		if (isset($aSettingSectionRaw['imageLeftImage'])) {
			$aSettingSectionData['imageLeftImage']
				= $this->handleReplaceUrlImage($aSettingSectionRaw['imageLeftImage']);
		}
		if (isset($aSettingSectionRaw['image'])) {
			$aSettingSectionData['image']
				= $this->handleReplaceUrlImage($aSettingSectionRaw['image']);
		}
		if (isset($aSettingSectionRaw['imageRightImage'])) {
			$aSettingSectionData['imageRightImage']
				= $this->handleReplaceUrlImage($aSettingSectionRaw['imageRightImage']);
		}
		if (isset($aSettingSectionRaw['imageUrl'])) {
			$aSettingSectionData['imageUrl'] = $this->handleReplaceUrlImage($aSettingSectionRaw['imageUrl']);
		}
		if (isset($aSettingSectionRaw['logo'])) {
			$aSettingSectionData['logo'] = $this->handleReplaceUrlLogo($aSettingSectionRaw['logo']);
		}
		if (isset($aSettingSectionRaw['bannerImage'])) {
			$aSettingSectionData['bannerImage'] = $this->handleReplaceUrlImage($aSettingSectionRaw['bannerImage']);
		}
		if (isset($aSettingSectionRaw['socialsData']) && is_array($aSettingSectionRaw['socialsData']) &&
			!empty($aSettingSectionRaw['socialsData'])) {
			$aSettingSectionData['bannerImage'] = array_map(function ($aItem) {
				$aData = $aItem;
				$aData['icon'] = $this->handleReplaceUrlImage($aItem['icon']);
				return $aData;
			}, $aSettingSectionRaw['socialsData']);
		}
		if (isset($aSettingSectionRaw['orderProducts']) && is_array($aSettingSectionRaw['orderProducts']) &&
			!empty($aSettingSectionRaw['orderProducts'])) {
			$aSettingSectionData['orderProducts'] = array_map(function ($aItem) {
				$aData = $aItem;
				$aData['image'] = $this->handleReplaceUrlImage($aItem['image']);
				return $aData;
			}, $aSettingSectionRaw['orderProducts']);
		}
		if (isset($aSettingSectionRaw['socialsData']) && is_array($aSettingSectionRaw['socialsData']) &&
			!empty($aSettingSectionRaw['socialsData'])) {
			$aSettingSectionData['socialsData'] = array_map(function ($aItem) {
				$aData = $aItem;
				$aData['icon'] = $this->handleReplaceUrlImage($aItem['icon']);
				return $aData;
			}, $aSettingSectionRaw['socialsData']);
		}
		if (isset($aSettingSectionRaw['productsData']) && is_array($aSettingSectionRaw['productsData']) &&
			!empty($aSettingSectionRaw['productsData'])) {
			$aProductData['type'] = $aSettingSectionRaw['productsData']['type'] ?? '';
			$aProductData['nodes'] = array_map(function ($aItem) {
				$aData = $aItem;
				$aData['image'] = $this->handleReplaceUrlImage($aItem['image'], true);
				return $aData;
			}, $aSettingSectionRaw['productsData']['nodes'] ?? []);
			$aSettingSectionData['productsData'] = $aProductData;
		}
		$aSection['settings'] = $aSettingSectionData;
		return $aSection;
	}

	public function handleReplaceUrlImage($value, $isProductPlaceholder = false)
	{
		if ($isProductPlaceholder) {
			return WILOKE_EMAIL_CREATOR_IMAGE_URL . 'images/placeholder/9x10.png';
		}
		return str_replace('https://wiloke-storage.netlify.app/', WILOKE_EMAIL_CREATOR_IMAGE_URL, $value);
	}

	public function handleReplaceUrlLogo($value)
	{
		return str_replace('http://emailcreator.app/', WILOKE_EMAIL_CREATOR_IMAGE_URL.'images/logo/',
			$value);

	}

	public function getTemplates()
	{
		$aTemplateFree = [];
		$aTemplatePro = [];
		$dirTemplates = plugin_dir_path(__FILE__) . 'Configs/DetailTemplate/';
		$aDirTemplates = $this->getRawTemplates($dirTemplates);
		if (!empty($aDirTemplates)) {
			foreach ($aDirTemplates as $nameTemplate) {
				$aTemplateData = json_decode(file_get_contents($dirTemplates . $nameTemplate), true);
				$package = $aTemplateData['package'] ?? 'free';
				if ($package == 'free') {
					$aTemplateFree[] = [
						'id'        => $aTemplateData['id'] ?? '',
						'label'     => $aTemplateData['label'] ?? '',
						'emailType' => $aTemplateData['emailType'] ?? [],
						'package'   => $package,
						'image'     => $aTemplateData['image'] ?? 'free',
					];
				} else {
					$aTemplatePro[] = [
						'id'        => $aTemplateData['id'] ?? '',
						'label'     => $aTemplateData['label'] ?? '',
						'emailType' => $aTemplateData['emailType'] ?? [],
						'package'   => $package,
						'image'     => $aTemplateData['image'] ?? 'free',
					];
				}
			}
		}
		return array_merge($aTemplateFree, $aTemplatePro);
	}

	public function getCategories(): array
	{
		$aData = [];
		$dirCategories = plugin_dir_path(__FILE__) . 'Configs/Sections/';
		$aDirCategories = $this->getRawCategories($dirCategories);
		if (!empty($aDirCategories)) {
			foreach ($aDirCategories as $dirCategory) {
				$aCategory = [];
				$nameCategory = str_replace($dirCategories, '', $dirCategory);
				$aCategory['count'] = count(list_files($dirCategory));
				$aCategory['id'] = strtolower($nameCategory);
				$aCategory['label'] = Helper::convertName($nameCategory);
				if (in_array($aCategory['id'], $this->aCategoriesOrder)) {
					$aCategory['type'] = 'order';
				}
				$aData[] = $aCategory;
			}
		}
		return $aData;
	}

	public function getRawCategories($dirCategories)
	{
		return glob($dirCategories . '*', GLOB_ONLYDIR);
	}

	public function getRawTemplates($dirTemplates)
	{
		return array_slice(scandir($dirTemplates), 2);
	}

	public function getCustomerTemplates()
	{
		$aResponse = apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
			'src/DataFactory/Config/DataImportService/getCustomerTemplates', $aArgs = []);
		return $aResponse['data']['items'];
	}

	public function getSections()
	{
		$jData = file_get_contents(plugin_dir_path(__FILE__) . 'Configs/Sections.json');
		return json_decode($jData, true);
	}

	public function getSection($categoryId): array
	{
		$aDirCategories = $this->getRawCategories(plugin_dir_path(__FILE__) . 'Configs/Sections/*');
		$aSortCategories = [];
		foreach ($aDirCategories as $dirCategory) {
			$nameCategory = str_replace(plugin_dir_path(__FILE__) . 'Configs/Sections/', '', $dirCategory);
			$aSortCategories[strtolower($nameCategory)] = $nameCategory;
		}
		if (!in_array($categoryId, array_keys($aSortCategories))) {
			$categoryId = 'header';
		}
		$aListFilesInFolder = list_files(plugin_dir_path(__FILE__) . 'Configs/Sections/' .
			$aSortCategories[$categoryId]);
		$aData = [];
		if (!empty($aListFilesInFolder)) {
			$aSectionFree = [];
			$aSectionPro = [];
			foreach ($aListFilesInFolder as $pathFile) {
				$aRawData = json_decode(file_get_contents($pathFile), true);
				$aValidateSection = $this->replaceUrlImageSection($aRawData);
				if (isset($aValidateSection['package']) && ($aValidateSection['package'] == 'pro')) {
					$aSectionPro[] = $aValidateSection;
				} else {
					$aSectionFree[] = $aValidateSection;
				}
			}
			$aData = array_merge($aSectionFree, $aSectionPro);
		}
		return $aData;
	}
}
