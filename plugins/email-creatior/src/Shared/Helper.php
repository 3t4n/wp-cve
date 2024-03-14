<?php

namespace WilokeEmailCreator\Shared;

use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

class Helper
{
	public static function getServerToken()
	{
		return get_option(AutoPrefix::namePrefix('updateServerToken')) ?? "";
	}

	public static function updateServerToken($token): bool
	{
		return update_option(AutoPrefix::namePrefix('updateServerToken'), $token);
	}

	public static function getPackagePlan()
	{
		return get_option(AutoPrefix::namePrefix('PackagePlan')) ?: 'free';
	}

	public static function updatePackagePlan($packagePlan): bool
	{
		return update_option(AutoPrefix::namePrefix('PackagePlan'), $packagePlan);
	}

	public static function getLicenseSourcePlan()
	{
		return get_option(AutoPrefix::namePrefix('licenseSource'));
	}

	public static function updateLicenseSourcePlan($licenseSource): bool
	{
		return update_option(AutoPrefix::namePrefix('licenseSource'), $licenseSource);
	}
	public static function isLicenseSourceEnvato(): bool
	{
		return self::getLicenseSourcePlan()=='envato';
	}
	public static function updatePurchaseCode($purchaseCode): bool
	{
		return update_option(AutoPrefix::namePrefix('purchaseCode'), $purchaseCode);
	}

	public static function getPurchaseCode()
	{
		return get_option(AutoPrefix::namePrefix('purchaseCode'));
	}

	public static function isPro(): bool
	{
		return !empty(self::getPurchaseCode());
	}

	public static function snakeToCamel($input): string
	{
		return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
	}

	public static function convertName($input): string
	{
		$output = '';
		if (!empty($input)) {
			$output = array_reduce(explode('-', $input), function ($carry, $item) {
				if (empty($carry)) {
					$carry = ucfirst($item);
				} else {
					$carry .= " " . ucfirst($item);
				}
				return $carry;
			});
		}
		return $output;
	}
}
