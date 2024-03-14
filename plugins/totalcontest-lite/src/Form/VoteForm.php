<?php

namespace TotalContest\Form;

use TotalContest\Contracts\Form\Factory as FormFactory;
use TotalContest\Contracts\Submission\Model as SubmissionModel;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Form\Field;
use TotalContestVendors\TotalCore\Form\Fields\FileField;
use TotalContestVendors\TotalCore\Form\Form;

/**
 * Class VoteForm
 * @package TotalContest\Form
 */
class VoteForm extends Form {
	protected $submission;
	protected $request;
	protected $formFactory;

	/**
	 * VoteForm constructor.
	 *
	 * @param             $submission
	 * @param Request     $request
	 * @param FormFactory $formFactory
	 */
	public function __construct( SubmissionModel $submission, Request $request, FormFactory $formFactory ) {
		parent::__construct();

		$this->submission  = $submission;
		$this->request     = $request;
		$this->formFactory = $formFactory;

		$hiddenFieldsPage = $this->formFactory->makePage();
		$voteFieldsPage   = $this->formFactory->makePage();

		$actionField = $this->formFactory->makeTextField();
		$actionField->setName( 'action' );
		$actionField->setOptions(
			[
				'type'  => 'hidden',
				'name'  => 'totalcontest[action]',
				'label' => false,
			]
		);
		$actionField->setValue( 'vote' );

		$submissionIdField = $this->formFactory->makeTextField();
		$submissionIdField->setName( 'submissionId' );
		$submissionIdField->setOptions(
			[
				'type'  => 'hidden',
				'name'  => 'totalcontest[submissionId]',
				'label' => false,
			]
		);
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

		$fields = $this->submission->getContest()->getSettingsItem( 'contest.vote.fields', [] );
		$fields = apply_filters( 'totalcontest/filters/form/vote/fields', (array) $fields );

		foreach ( $fields as $fieldSettings ):
			$factoryCallable = [ $this->formFactory, 'make' . ucfirst( $fieldSettings['type'] ) . 'Field' ];
			if ( is_callable( $factoryCallable ) ):
				$field = call_user_func( [ $this->formFactory, 'make' . ucfirst( $fieldSettings['type'] ) . 'Field' ] );
			else:
				$field = apply_filters( "totalcontest/filters/form/custom-field-type/{$fieldSettings['type']}",
				                        $fieldSettings,
				                        'vote' );
			endif;

			$field = apply_filters( "totalcontest/filters/form/field/{$fieldSettings['type']}",
			                        $field,
			                        $fieldSettings,
			                        'vote' );

			if ( ! $field instanceof Field ) :
				continue;
			endif;

			if ( ! empty( $fieldSettings['validations']['unique']['enabled'] ) ):
				$fieldSettings['validations']['unique']['contestId'] = $this->submission->getContest()->getId();
				$fieldSettings['validations']['unique']['callback']  = TotalContest( 'validators.unique' );
			endif;

			if ( $fieldSettings['type'] == 'checkbox' ) {
				$fieldSettings['attributes']['multiple'] = true;
			}

			$field->setName( $fieldSettings['name'] );
			$field->setOptions(
				[
					'id'          => "{$fieldSettings['name']}-field",
					'name'        => "totalcontest[{$fieldSettings['name']}]" . ( empty( $fieldSettings['attributes']['multiple'] ) ? '' : '[]' ),
					'default'     => empty( $fieldSettings['default'] ) ? null : $fieldSettings['default'],
					'placeholder' => isset( $fieldSettings['placeholder'] ) ? $fieldSettings['placeholder'] : '',
					'label'       => isset( $fieldSettings['label'] ) ? $fieldSettings['label'] : false,
					'validations' => isset( $fieldSettings['validations'] ) ? $fieldSettings['validations'] : [],
					'options'     => isset( $fieldSettings['options'] ) ? $fieldSettings['options'] : [],
					'attributes'  => isset( $fieldSettings['attributes'] ) ? $fieldSettings['attributes'] : [],
					'template'    => isset( $fieldSettings['template'] ) ? $fieldSettings['template'] : false,
				]
			);

			if ( $field instanceof FileField ):
				$field->setValue( $this->request->file( "totalcontest.{$fieldSettings['name']}", null ) );
			else:
				$field->setValue(
					$this->request->request(
						"totalcontest.{$fieldSettings['name']}",
						empty( $_POST ) ? null : ''
					)
				);
			endif;

			$voteFieldsPage[] = $field;
		endforeach;

		// Captcha
		$captchaSettings = TotalContest()->option( 'services.recaptcha' );
		if ( ! empty( $captchaSettings['enabled'] ) && ! empty( $captchaSettings['key'] ) && ! empty( $captchaSettings['secret'] ) ):
			$captchaField = $this->formFactory->makeCaptchaField();
			$captchaField->setOptions(
				[
					'type'      => 'captcha',
					'name'      => 'captcha',
					'key'       => $captchaSettings['key'],
					'secret'    => $captchaSettings['secret'],
					'invisible' => ! empty( $captchaSettings['invisible'] ),
				]
			);
			$voteFieldsPage[] = $captchaField;
		endif;

		$this->pages['hiddenFields'] = $hiddenFieldsPage;
		$this->pages['fields']       = $voteFieldsPage;

		/**
		 * Filters the form pages.
		 *
		 * @param array                                              $pages      Form pages.
		 * @param \TotalContest\Submission\Model           $submission Submission model object.
		 * @param string                                             $context    Form context.
		 * @param \TotalContestVendors\TotalCore\Contracts\Form\Form $form       Form object.
		 *
		 * @since 2.0.0
		 * @return array
		 */
		$this->pages = apply_filters( 'totalcontest/filters/form/pages', $this->pages, $this->submission, 'vote', $this );
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
		 * @since 4.0.0
		 * @return array
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
		     ->appendToAttribute( 'class', 'totalcontest-form-vote' );

		return $form;
	}

	/**
	 * @return \TotalContestVendors\TotalCore\Helpers\Html
	 */
	public function getSubmitButtonElement() {
		$submit = parent::getSubmitButtonElement();
		$submit->setAttribute( 'class', 'totalcontest-button totalcontest-button-primary totalcontest-button-vote' );
		$submit->setInner( esc_html__( 'Vote', 'totalcontest' ) );

		return $submit;
	}
}
