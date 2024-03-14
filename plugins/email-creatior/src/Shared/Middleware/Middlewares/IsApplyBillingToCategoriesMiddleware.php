<?php

namespace WilokeEmailCreator\Shared\Middleware\Middlewares;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;

class IsApplyBillingToCategoriesMiddleware implements IMiddleware
{
	use TraitHandleRulesTemplateEmail;

	public function validation(array $aAdditional = []): array
	{
		try {

			if (!isset($aAdditional['postID'])) {
				throw new Exception(esc_html__('The postID is required', 'emailcreator'), 401);
			}
			if (empty($aAdditional['aProductIds'])) {
				throw new Exception(esc_html__('The productIds is required', 'emailcreator'), 401);
			}
			$aRulesCategories = $this->getRuleCategories($aAdditional['postID']);
			if (empty($aRulesCategories)) {
				return MessageFactory::factory()->success(esc_html__('Passed', 'emailcreator'));
			}
			$aCategoriesIds = [];
			foreach ($aAdditional['aProductIds'] as $productId) {
				$terms = get_the_terms($productId, 'product_cat');
				foreach ($terms as $term) {
					if (!in_array($term->term_id, $aCategoriesIds)) {
						$aCategoriesIds[] = $term->term_id;
					}
				}
			}
			foreach ($aRulesCategories as $rulesCategoryID) {
				if (in_array($rulesCategoryID, $aCategoriesIds)) {
					return MessageFactory::factory()->success(esc_html__('Passed', 'emailcreator'));
				}
			}

			throw new Exception(esc_html__('Not passed', 'emailcreator'), 401);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}
}
