<?php

/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*                                                                   */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Condition;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\ProductTaxonomy')):

class ProductTaxonomy extends AbstractItemCondition
{
	public function getType()
	{
		return 'product_taxonomy';
	}

	public function getAvailableOptions()
	{
		$allTaxonomiesAsArray = array();
		$taxonomies = get_object_taxonomies('product', 'objects');
		foreach ($taxonomies as $taxonomy) {
			$allTaxonomiesAsArray = array_merge($allTaxonomiesAsArray, $this->getTaxonomyAsArray($taxonomy->name, $taxonomy->label . ': '));
		}

		return $allTaxonomiesAsArray;
	}

	private function getTaxonomyAsArray($taxonomy, $namePrefix = '')
	{
		$taxArray = array();

		$terms = get_terms(array('taxonomy' => $taxonomy));
		foreach ($terms as $term) {
			if (is_object($term) && property_exists($term, 'slug') && property_exists($term, 'name')) {
				$taxArray[$taxonomy . '|' . $term->slug] = $namePrefix . $term->name;
			}
		}

		return $taxArray;
	}

	protected function matchItem(array $item, array $options)
	{
		$numberOfMatches = 0;

		foreach ($options as $option) {
			if ($this->matchItemCondition($item, $option)) {
				$numberOfMatches++;

				if ($this->optionsOperator == 'or') {
					break;
				}
			}
		}

		return $this->isMatched($this->optionsOperator, $numberOfMatches, count($options));
	}

	protected function matchItemCondition(array $item, $option)
	{
		$taxonomyTermPair = explode('|', $option, 2);
		$taxonomy = $taxonomyTermPair[0];
		$term = $taxonomyTermPair[1];
	
		$product = $item['data'];

		// may need to add product conditions to reduce this code duplication
		$isMatched = has_term($term, $taxonomy, $product->get_id());
		if (!$isMatched && method_exists($product, 'get_parent_id') && $product->get_parent_id() != 0) {
			$isMatched = has_term($term, $taxonomy, $product->get_parent_id());
		}

		return $isMatched;
	}
}

endif;