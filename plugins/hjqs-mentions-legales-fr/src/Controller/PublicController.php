<?php

namespace Controller;

use Form\LegalNoticeForm;
use Form\PrivacyPolicyForm;
use Form\TermsOfSalesForm;

class PublicController extends BaseController {

	public array $forms;

	public function __construct() {
		$legal_notice = new LegalNoticeForm();
		$privacy_policy = new PrivacyPolicyForm();
		$terms_of_sales = new TermsOfSalesForm();

		add_shortcode('hjqs_ml', [$legal_notice, 'prepare_render']);
		add_shortcode('hjqs_pdc', [$privacy_policy, 'prepare_render']);
		add_shortcode('hjqs_cgv', [$terms_of_sales, 'prepare_render']);

	}

}