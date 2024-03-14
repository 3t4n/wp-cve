<?php

namespace WilokeEmailCreator\Shared\Middleware\Middlewares;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;

class IsApplyToBillingCountriesMiddleware implements IMiddleware
{
	use TraitHandleRulesTemplateEmail;

	public function validation(array $aAdditional = []): array
	{
		try {
			if (!isset($aAdditional['postID'])) {
				throw new Exception(esc_html__('The postID is required', 'emailcreator'), 401);
			}
			$aRuleCountries = $this->getRuleCountries($aAdditional['postID']);
			if (empty($aRuleCountries)) {
				return MessageFactory::factory()->success(esc_html__('Passed', 'emailcreator'));
			}
			if (empty($aAdditional['orderCountry'])) {
				throw new Exception(esc_html__('The orderCountry is required', 'emailcreator'), 401);
			}
			if (in_array($aAdditional['orderCountry'], $aRuleCountries)) {
				return MessageFactory::factory()->success(esc_html__('Passed', 'emailcreator'));
			}

			throw new Exception(esc_html__('Not passed', 'emailcreator'), 401);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}
}
