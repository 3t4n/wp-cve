<?php

namespace TotalContest\Form\Fields;

use TotalContestVendors\TotalCore\Contracts\Form\Page;
use TotalContestVendors\TotalCore\Form\Field;
use TotalContestVendors\TotalCore\Form\Fields\FileField;
use TotalContestVendors\TotalCore\Helpers\Html;

/**
 * Class MediaField
 * @package TotalContest\Form\Fields
 */
abstract class MediaField extends FileField {
	/**
	 * @var Field $urlField
	 */
	protected $urlField = null;

	public function getInputHtmlElement() {
		$this->setOption( 'type', 'file' );
		$this->setOption( 'accept', $this->getType() . '/*' );

		return parent::getInputHtmlElement();
	}

	public function getLabelHtmlElement() {
		$label     = parent::getLabelHtmlElement();
		$labelText = current( $label->getInner() );
		$label->setInner( '' );

		$placeholder = $this->getOption( 'placeholder' ) ?: $labelText;
		$wrapper     = new Html(
			'div',
			[ 'class' => 'totalcontest-form-field-placeholder-wrapper' ],
			[
				new Html( 'div', [ 'class' => 'totalcontest-form-field-placeholder' ], $placeholder ),
				$label,
			]
		);

		return $wrapper;
	}

	public function render( $purgeCache = false ) {
		$this->template = str_replace( '{{type}}', "{{type}} {{slug}}-form-field-type-media {{slug}}-form-field-type-{$this->getType()}", $this->template );

		if ( $this->urlField && ! $this->getOption( 'validations.file.enabled' ) ):
			return '';
		endif;

		return parent::render( $purgeCache );
	}

	public function validate( $rules = [] ) {

		$customRules = $this->getOption( 'validations', [] );


		if ( $this->urlField ) {
			$validUrlField = $this->urlField && $this->urlField->getValue() && $this->urlField->validate() === true;

			if ( $validUrlField && $this->getValue() ):
				$this->errors['file_or_url'] = esc_html__( 'Either file or URL is accepted but not both.', 'totalcontest' );

				return $this->errors;
			endif;

			if ( $validUrlField || ! $this->getOption( 'validations.file.enabled' ) ):
				$customRules = [];
			endif;
		}

		$fileTypeValidationRule = [
			'fileType' => [
				'enabled' => true,
				'type'    => $this->getType(),
			],
		];

		if ( $this->getValue() === null && $this->getOption( 'validations.filled.enabled' ) === false ) {
			$customRules = [];
		}

		$customRules = array_merge( $fileTypeValidationRule, $customRules );

		return parent::validate( $customRules );
	}

	public function onAttach( Page $page ) {
		$rules = $this->getOption( 'validations', [] );

		if ( empty( $rules['services']['enabled'] ) ):
			return;
		endif;

		$acceptedServices = empty( $rules['services']['accepted'] ) ? [] : (array) $rules['services']['accepted'];
		$acceptedServices = array_filter( $acceptedServices );

		$this->urlField = \TotalContest( 'form.factory' )->makeTextField();
		$this->urlField->setName( "{$this->getName()}_url" );
		$this->urlField->setOptions(
			[
				'id'                => "{$this->getName()}-field-url",
				'name'              => sprintf( 'totalcontest[%s]', $this->urlField->getName() ),
				'label'             => esc_html__( 'Media link', 'totalcontest' ),
				'type'              => 'url',
				'placeholder'       => implode( ', ', array_map( 'ucfirst', array_keys( $acceptedServices ) ) ),
				'validations'       => $this->getValue() ? [] : [
					'filled'      => [ 'enabled' => (bool) $this->getOption( 'validations.filled.enabled', false ) ],
					'url'         => [ 'enabled' => true ],
					'uploadedVia' => [ 'enabled' => true, 'services' => $acceptedServices ],
				],
				'required-if-empty' => "#{$this->getName()}-field",
			]
		);
		$this->urlField->setValue( \TotalContest( 'http.request' )->post( 'totalcontest.' . $this->urlField->getName() ) );
		$page[] = $this->urlField;

		$this->template = str_replace( '{{type}}', '{{type}} totalcontest-form-field-with-url', $this->template );
		$or             = esc_html__( 'Or', 'totalcontest' );
		$this->template = "{$this->template}<div class=\"totalcontest-form-field-split\"><span>{$or}</span></div>";
	}

	public function getHtmlValidationAttributes() {
		$attributes = parent::getHtmlValidationAttributes();
		$rules      = $this->getOption( 'validations', [] );

		if ( ! empty( $rules['services']['enabled'] ) ):
			$attributes['required-if-empty'] = "#{$this->getName()}-field-url";
		endif;

		return $attributes;
	}
}
