<?php

namespace TotalContestVendors\TotalCore\Form;

use TotalContestVendors\TotalCore\Contracts\Http\Request as RequestContract;

/**
 * Class Factory
 * @package TotalContestVendors\TotalCore\Form
 */
class Factory implements \TotalContestVendors\TotalCore\Contracts\Form\Factory {

	protected $map = [
		'form'     => '\TotalContestVendors\TotalCore\Form\Form',
		'page'     => '\TotalContestVendors\TotalCore\Form\Page',
		'text'     => '\TotalContestVendors\TotalCore\Form\Fields\TextField',
		'captcha'  => '\TotalContestVendors\TotalCore\Form\Fields\CaptchaField',
		'textarea' => '\TotalContestVendors\TotalCore\Form\Fields\TextareaField',
		'checkbox' => '\TotalContestVendors\TotalCore\Form\Fields\CheckboxField',
		'radio'    => '\TotalContestVendors\TotalCore\Form\Fields\RadioField',
		'select'   => '\TotalContestVendors\TotalCore\Form\Fields\SelectField',
		'file'     => '\TotalContestVendors\TotalCore\Form\Fields\FileField',
	];
	protected $request;

	public function __construct( RequestContract $request ) {
		$this->request = $request;
	}

	public function makeForm() {
		return new $this->map['form'];
	}

	public function makePage() {
		return new $this->map['page'];
	}

	public function makeCaptchaField() {
		return new $this->map['captcha'];
	}

	public function makeTextField() {
		return new $this->map['text'];
	}

	public function makeTextareaField() {
		return new $this->map['textarea'];
	}

	public function makeCheckboxField() {
		return new $this->map['checkbox'];
	}

	public function makeRadioField() {
		return new $this->map['radio'];
	}

	public function makeSelectField() {
		return new $this->map['select'];
	}

	public function makeFileField() {
		return new $this->map['file'];
	}

	public function setForm( $className ) {
		$this->map['form'] = (string) $className;
	}

	public function setPage( $className ) {
		$this->map['page'] = (string) $className;
	}

	public function setTextField( $className ) {
		$this->map['text'] = (string) $className;
	}

	public function setTextareaField( $className ) {
		$this->map['textarea'] = (string) $className;
	}

	public function setCheckboxField( $className ) {
		$this->map['checkbox'] = (string) $className;
	}

	public function setRadioField( $className ) {
		$this->map['radio'] = (string) $className;
	}

	public function setSelectField( $className ) {
		$this->map['select'] = (string) $className;
	}

	public function setFileField( $className ) {
		$this->map['file'] = (string) $className;
	}
}