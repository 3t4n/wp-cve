<?php

namespace WilokeEmailCreator\Shared\Middleware\Middlewares;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;

class IsApplyBillingToMinOrderMiddleware implements IMiddleware
{
	use TraitHandleRulesTemplateEmail;

	/**
	 * $aAdditional{
	 * postID : int
	 * orderTotal: int
	 * }
	 */
	public function validation(array $aAdditional = []): array
	{
		try {
			if (!isset($aAdditional['postID'])) {
				throw new Exception(esc_html__('The postID is required', 'emailcreator'), 401);
			}
			if (empty($aAdditional['orderSubTotal'])) {
				throw new Exception(esc_html__('The orderSubTotal is required', 'emailcreator'), 401);
			}
			$ruleMinOrder = (int)$this->getRuleMinOrder($aAdditional['postID']);
			if (empty($ruleMinOrder)) {
				return MessageFactory::factory()->success(esc_html__('Passed', 'emailcreator'));

			}
			if ($aAdditional['orderSubTotal'] > $ruleMinOrder) {
				return MessageFactory::factory()->success(esc_html__('Passed', 'emailcreator'));
			}

			throw new Exception(esc_html__('Not passed', 'emailcreator'), 401);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}
}
