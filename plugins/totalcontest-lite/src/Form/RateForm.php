<?php

namespace TotalContest\Form;

use TotalContest\Contracts\Submission\Model as SubmissionModel;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Form\Form;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Class RateForm
 * @package TotalContest\Form
 */
class RateForm extends Form {
	protected $submission;
	protected $request;
	protected $formFactory;

	/**
	 * RateForm constructor.
	 *
	 * @param SubmissionModel $submission
	 * @param Request         $request
	 * @param Factory         $formFactory
	 */
	public function __construct( SubmissionModel $submission, Request $request, Factory $formFactory ) {
		parent::__construct();

		$this->submission  = $submission;
		$this->request     = $request;
		$this->formFactory = $formFactory;

		$hiddenFieldsPage = $this->formFactory->makePage();
		$rateFieldsPage   = $this->formFactory->makePage();

		$actionField = $this->formFactory->makeTextField();
		$actionField->setName( 'action' );
		$actionField->setOptions( [
			'type'  => 'hidden',
			'name'  => 'totalcontest[action]',
			'label' => false,
		] );
		$actionField->setValue( 'vote' );

		$submissionIdField = $this->formFactory->makeTextField();
		$submissionIdField->setName( 'submissionId' );
		$submissionIdField->setOptions( [
			'type'  => 'hidden',
			'name'  => 'totalcontest[submissionId]',
			'label' => false,
		] );
		$submissionIdField->setValue( $this->submission->getId() );

		$contestContextField = $this->formFactory->makeTextField();
		$contestContextField->setName( 'context' );
		$contestContextField->setOptions( [
			'type'  => 'hidden',
			'name'  => 'totalcontest[context]',
			'label' => false,
		] );
		$contestContextField->setValue( $this->submission->getContest()->getContext() );

		$hiddenFieldsPage[] = $actionField;
		$hiddenFieldsPage[] = $submissionIdField;
		$hiddenFieldsPage[] = $contestContextField;

		$voteSettings = Arrays::parse( $this->submission->getContest()->getSettingsItem( 'vote' ), [ 'type' => 'count' ] );

		$criteria = (array) $voteSettings['criteria'];
		if ( empty( $criteria ) ):
			$criteria[] = [ 'name' => esc_html__( 'Overall', 'totalcontest' ) ];
		endif;

		$scale = (int) $voteSettings['scale'];

		$uniqueIdPerContest = wp_generate_uuid4();

		foreach ( $criteria as $criterionIndex => $criterion ):

			$field = $this->formFactory->makeRadioField();

			$field->setName( "criterion-{$criterionIndex}-{$uniqueIdPerContest}" );
			$field->setOptions(
				[
					'id'          => "criterion-{$criterionIndex}-field-{$uniqueIdPerContest}",
					'type'        => 'radio',
					'name'        => "totalcontest[criterion][$criterionIndex]",
					'label'       => $criterion['name'],
					'validations' => [ 'filled' => [ 'enabled' => true ], 'in' => [ 'enabled' => true, 'values' => range( 1, $scale ) ] ],
					'options'     => array_reverse( array_combine( range( 1, $scale ), range( 1, $scale ) ), true ),
				]
			);

			$field->setValue( $this->request->request( "totalcontest.criterion.$criterionIndex", null ) );

			$rateFieldsPage[] = $field;
		endforeach;

		// Captcha
		$captchaSettings = TotalContest()->option( 'services.recaptcha' );
		if ( ! empty( $captchaSettings['enabled'] ) && ! empty( $captchaSettings['key'] ) && ! empty( $captchaSettings['secret'] ) ):
			$captchaField = $this->formFactory->makeCaptchaField();
			$captchaField->setOptions( [
				'type'      => 'captcha',
				'name'      => 'captcha',
				'key'       => $captchaSettings['key'],
				'secret'    => $captchaSettings['secret'],
				'invisible' => ! empty( $captchaSettings['invisible'] ),
			] );
			$rateFieldsPage[] = $captchaField;
		endif;

		$this->pages['hiddenFields'] = $hiddenFieldsPage;
		$this->pages['fields']       = $rateFieldsPage;

		/**
		 * Filters the form pages.
		 *
		 * @param array                                              $pages      Form pages.
		 * @param \TotalContest\Submission\Model           $submission Submission model object.
		 * @param string                                             $context    Form context.
		 * @param \TotalContestVendors\TotalCore\Contracts\Form\Form $form       Form object.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$this->pages = apply_filters( 'totalcontest/filters/form/pages', $this->pages, $this->submission, 'rate', $this );
	}


	/**
	 * Open tag.
	 *
	 * @return string
	 */
	public function open() {
		return $this->getFormElement()
		            ->getOpenTag();
	}

	/**
	 * Close tag.
	 *
	 * @return string
	 */
	public function close() {
		return $this->getFormElement()->getCloseTag();
	}

	/**
	 * Hidden fields.
	 *
	 * @return mixed
	 */
	public function hiddenFields() {
		return $this->pages['hiddenFields']->render();
	}

	/**
	 * Fields.
	 *
	 * @return null
	 */
	public function fields() {
		return $this->pages['fields']->render();
	}

	/**
	 * Buttons.
	 *
	 * @return string
	 */
	public function buttons() {
		$buttons = [];

		$buttons[] = $this->getSubmitButtonElement();

		/**
		 * Filters the form buttons.
		 *
		 * @param array                                              $buttons    Form buttons.
		 * @param \TotalContest\Submission\Model           $submission Submission model object.
		 * @param \TotalContestVendors\TotalCore\Contracts\Form\Form $form       Form object.
		 *
		 * @return array
		 * @since 4.0.0
		 */
		$buttons = apply_filters( 'totalcontest/filters/form/buttons', $buttons, $this->submission, $this );

		return implode( '', $buttons );
	}

	/**
	 * @return \TotalContestVendors\TotalCore\Helpers\Html
	 */
	public function getFormElement() {
		$form = parent::getFormElement();
		$form->appendToAttribute( 'novalidate', 'novalidate' )
		     ->appendToAttribute( 'class', 'totalcontest-form-rate' );

		return $form;
	}

	/**
	 * @return \TotalContestVendors\TotalCore\Helpers\Html
	 */
	public function getSubmitButtonElement() {
		$submit = parent::getSubmitButtonElement();
		$submit->setAttribute( 'class', 'totalcontest-button totalcontest-button-primary totalcontest-button-rate' );
		$submit->setInner( esc_html__( 'Rate', 'totalcontest' ) );

		return $submit;
	}
}
