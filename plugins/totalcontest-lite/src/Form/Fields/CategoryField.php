<?php

namespace TotalContest\Form\Fields;

use TotalContestVendors\TotalCore\Form\Fields\SelectField;

class CategoryField extends SelectField {
	public function getInputHtmlElement() {
		$categories = (array) $this->getOption( 'options', [] );

		$categoriesTerms = get_terms( [
			'taxonomy'   => TC_SUBMISSION_CATEGORY_TAX_NAME,
			'fields'     => 'id=>name',
			'hide_empty' => false,
			'include'    => $categories,
		] );

		$this->setOption( 'options', $categoriesTerms );

		return parent::getInputHtmlElement();
	}
}