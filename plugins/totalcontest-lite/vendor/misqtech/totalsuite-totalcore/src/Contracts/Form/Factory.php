<?php

namespace TotalContestVendors\TotalCore\Contracts\Form;

interface Factory {
	public function makeForm();

	public function makePage();

	public function makeCaptchaField();

	public function makeTextField();

	public function makeTextareaField();

	public function makeCheckboxField();

	public function makeRadioField();

	public function makeSelectField();

	public function makeFileField();

	public function setForm( $className );

	public function setPage( $className );

	public function setTextField( $className );

	public function setTextareaField( $className );

	public function setCheckboxField( $className );

	public function setRadioField( $className );

	public function setSelectField( $className );

	public function setFileField( $className );

}