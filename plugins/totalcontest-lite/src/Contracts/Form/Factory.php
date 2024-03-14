<?php

namespace TotalContest\Contracts\Form;

interface Factory {
	public function makeForm();

	public function makeParticipateForm( $contest );

	public function makeVoteForm( $submission );

	public function makeRateForm( $submission );

	public function makePage();

	public function makeCaptchaField();

	public function makeTextField();

	public function makeTextareaField();

	public function makeCheckboxField();

	public function makeRadioField();

	public function makeSelectField();

	public function makeFileField();

	public function makeImageField();

	public function makeVideoField();

	public function makeAudioField();

	public function makeCategoryField();

	public function makeRichtextField();

	public function setForm( $className );

	public function setParticipateForm( $className );

	public function setVoteForm( $className );

	public function setRateForm( $className );

	public function setPage( $className );

	public function setTextField( $className );

	public function setTextareaField( $className );

	public function setCheckboxField( $className );

	public function setRadioField( $className );

	public function setSelectField( $className );

	public function setFileField( $className );

	public function setRichtextField( $className );

}