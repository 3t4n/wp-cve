<?php
class YcfForm {

	private $formId;
	private $formElementsData;

	public function __call($name, $args) {

		$methodPrefix = substr($name, 0, 3);
		$methodProperty = lcfirst(substr($name,3));

		if ($methodPrefix=='get') {
			return $this->$methodProperty;
		}
		else if ($methodPrefix=='set') {
			$this->$methodProperty = $args[0];
		}
	}

	public function defaultFormObjectData() {

		$formData = array(
			1 => array('id'=> 5512,'type'=>'text','name'=>'ycf-name','label'=>'Name','value'=>'','options' => '', ),
			2 => array('id'=> 1248,'type'=>'email','name'=>'ycf-email','label'=>'Email','value'=>'','options' => ''),
			3 => array('id'=> 9517,'type'=>'textarea','name'=>'ycf-message','label'=>'Message','value'=>'','options' => ''),
			4 => array('id'=> 'ycf-submit-wrapper','type'=>'submit','name'=>'ycf-submit','label'=>'Submit','value'=>'Submit','options' => '')
		);

		return $formData;
	}
}
