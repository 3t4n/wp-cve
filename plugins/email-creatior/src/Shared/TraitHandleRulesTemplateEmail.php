<?php

namespace WilokeEmailCreator\Shared;

use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

trait TraitHandleRulesTemplateEmail
{
	public function handleRulesTemplateEmail(int $postID, array $aData, $isActionCreate = true)
	{
		$aTemplateEmailUseRules = [];
		if (isset($aData['ruleCategories'])) {
			$aTemplateEmailUseRules[] = 'ruleCategories';
			update_post_meta($postID, AutoPrefix::namePrefix('ruleCategories'), json_encode($aData['ruleCategories']));
		}
		if (isset($aData['ruleCountries'])) {
			$aTemplateEmailUseRules[] = 'ruleCountries';
			update_post_meta($postID, AutoPrefix::namePrefix('ruleCountries'), json_encode($aData['ruleCountries']));
		}
		if (isset($aData['ruleMaxOrder'])) {
			$aTemplateEmailUseRules[] = 'ruleMaxOrder';
			update_post_meta($postID, AutoPrefix::namePrefix('ruleMaxOrder'), $aData['ruleMaxOrder']);
		}
		if (isset($aData['ruleMinOrder'])) {
			$aTemplateEmailUseRules[] = 'ruleMinOrder';
			update_post_meta($postID, AutoPrefix::namePrefix('ruleMinOrder'), $aData['ruleMinOrder']);
		}
		//Cart Abandonment
		if (isset($aData['addedToCartXMinutes'])) {
			update_post_meta($postID, AutoPrefix::namePrefix('addedToCartXMinutes'), $aData['addedToCartXMinutes']);
		}
		if (isset($aData['afterOrderStatusPending'])) {
			update_post_meta($postID, AutoPrefix::namePrefix('afterOrderStatusPending'),
				$aData['afterOrderStatusPending']);
		}
		if (isset($aData['afterOrderStatusFailed'])) {
			update_post_meta($postID, AutoPrefix::namePrefix('afterOrderStatusFailed'),
				$aData['afterOrderStatusFailed']);
		}

		if (!empty($aTemplateEmailUseRules)) {
			update_post_meta($postID, AutoPrefix::namePrefix('templateEmailUseRules'),
				json_encode($aTemplateEmailUseRules));
		} else {
			delete_post_meta($postID, AutoPrefix::namePrefix('templateEmailUseRules'));
		}
	}

	public function getTemplateEmailUseRules($postID): array
	{
		return json_decode(get_post_meta($postID, AutoPrefix::namePrefix('templateEmailUseRules'), true)) ?: [];
	}

	public function getRuleCategories(int $postID): array
	{
		return json_decode(get_post_meta($postID, AutoPrefix::namePrefix('ruleCategories'), true), true) ?: [];
	}

	public function getRuleCountries(int $postID): array
	{
		return json_decode(get_post_meta($postID, AutoPrefix::namePrefix('ruleCountries'), true), true) ?: [];
	}

	public function getRuleMaxOrder(int $postID)
	{
		return get_post_meta($postID, AutoPrefix::namePrefix('ruleMaxOrder'), true) ?? '';
	}

	public function getRuleMinOrder(int $postID)
	{
		return get_post_meta($postID, AutoPrefix::namePrefix('ruleMinOrder'), true) ?? '';
	}

	public function getAddedToCartXMinutes(int $postID): int
	{
		return (int)get_post_meta($postID, AutoPrefix::namePrefix('addedToCartXMinutes'), true) ?? 0;
	}

	public function getAfterOrderStatusPending(int $postID)
	{
		return get_post_meta($postID, AutoPrefix::namePrefix('afterOrderStatusPending'), true)?:'none';
	}
	public function getAfterOrderStatusFailed(int $postID)
	{
		return get_post_meta($postID, AutoPrefix::namePrefix('afterOrderStatusFailed'), true) ?:'none';
	}
	public function getIsAfterOrderStatusPending(int $postID): bool
	{
		return get_post_meta($postID, AutoPrefix::namePrefix('afterOrderStatusPending'), true) == 'active';
	}

	public function getIsAfterOrderStatusFailed(int $postID): bool
	{
		return get_post_meta($postID, AutoPrefix::namePrefix('afterOrderStatusFailed'), true) == 'active';
	}
}
