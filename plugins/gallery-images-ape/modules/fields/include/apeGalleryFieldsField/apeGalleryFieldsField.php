<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsField{

	protected $postId;
	protected $type;
	protected $view;
	protected $isLock;
	protected $isNew;
	protected $isHide;
	protected $help;
	protected $prefix;
	protected $id;
	protected $name;
	protected $default;
	protected $attributes;
	protected $label;
	protected $description;
	protected $contentBefore;
	protected $content;
	protected $contentAfter;
	protected $contentAfterBlock;
	protected $cbSanitize;
	protected $options;
	protected $dependents;
	protected $fields;
	protected $isSubField = false;


	final public function __construct($postId, array $settings){
		$this->postId = $postId;
		$this->setSettings($settings);
		$this->initSubFields();
	}


    private function setSettings(array $settings){
		// fill settings by default values
		$settings = array_merge(
			array(
				'type' => '',
				'view' => 'default',
				'is_lock' => false,
				'is_new' => false,
				'is_hide' => false,
				'is_sub_field' => false,
				'help' => '',
				'prefix' => null,
				'id' => null,
				'name' => '',
				'default' => null,
				'attributes' => array(),
				'label' => '',
				'description' => '',
				'contentBefore' => '',
				'content' => '',
				'contentAfter'  => '',
				'contentAfterBlock'  => '',
				'cb_sanitize'  => '',
				'options' => array(),
				'fields' => array(),
			),
			$settings
		);

		$this->type = is_string($settings['type']) ? $settings['type'] : null;
		$this->view = is_string($settings['view']) ? $settings['view'] : null;
		$this->isLock = (bool) $settings['is_lock'];
		$this->isNew = (bool) $settings['is_new'];
		$this->isHide = (bool) $settings['is_hide'];
		$this->help = is_string($settings['help']) ? $settings['help'] : null;
		$this->prefix = null == $settings['prefix']
			? apeGalleryFields::getInstance()->getConfig()->get('main/prefix')
			: (is_string($settings['prefix']) ? $settings['prefix'] : null);
		$this->id = $settings['id'] ? esc_attr($settings['id']) : self::randId();
		$this->name = is_string($settings['name']) ? $settings['name'] : null;
		$this->default = $settings['default'];
		$this->attributes = is_array($settings['attributes']) ? $settings['attributes'] : array();
		$this->label = is_string($settings['label']) ? $settings['label'] : null;
		$this->description = is_string($settings['description']) ? $settings['description'] : null;
		$this->contentBefore = is_string($settings['contentBefore']) ? $settings['contentBefore'] : null;
		$this->content = is_string($settings['content']) ? $settings['content'] : null;
		$this->contentAfter = is_string($settings['contentAfter']) ? $settings['contentAfter'] : null;
		$this->contentAfterBlock = is_string($settings['contentAfterBlock']) ? $settings['contentAfterBlock'] : null;
		$this->cbSanitize = is_string($settings['cb_sanitize']) ? $settings['cb_sanitize'] : null;
		$this->options = array_merge(
			$this->getDefaultOptions(),
			is_array($settings['options']) ? $settings['options'] : array()
		);
		$this->dependents = isset($settings['dependents']) && is_array($settings['dependents'])
			? $settings['dependents']
			: array();
		$this->fields = is_array($settings['fields']) && !empty($settings['fields']) ? $settings['fields'] : null;
		
	}


	private function initSubFields(){
		if ($this->fields) {
			foreach ($this->fields as $key => $subFieldSettings) {
				$subFieldSettings['is_lock'] = $this->isLock;
				$subFieldSettings['prefix'] = $this->prefix . $this->name;

				$field = apeGalleryFieldsFieldFactory::createField($this->postId, $subFieldSettings);
				$field->isSubField = true;
				$this->fields[$key] = $field;
			}
		}
	}


	final public function get($name){
		return isset($this->$name) ? $this->$name : null;
	}


	final public function content($value){
		return $this->fields ? $this->contentSubFields($value) : $this->contentField($value);
	}


	private function contentElements($data){
		$view = new apeGalleryFieldsView();
		if($data['label']){
			$data['label'] = $view->content("element/label".($data['help']?'.tooltip':''), $data);
		}
		return $data;
	}

	private function contentField($value){
		$view = new apeGalleryFieldsView();
		$data = $this->getData($value);

		$data = $this->contentElements($data);
	
		return $view->content("field/{$this->type}/{$this->view}", $data );
	}

	private function contentSubFields($values){
		$view = new apeGalleryFieldsView();
		$data = $this->getData($values);

		$data = $this->contentElements($data);

		if ($this->fields) {
			$data['fields'] = array();
			foreach ($this->fields as $key => $subField) {
				/** @var apeGalleryFieldsField $subField */
				$subFieldValue = isset($values[$subField->name]) ? $values[$subField->name] : null;

				$data['fields'][$key] = $subField->content($subFieldValue);
			}
			$data['fields'] = implode("\n", $data['fields']);
		}

		return $view->content("field/{$this->type}/{$this->view}", $data);
	}

	public function getData($value = null){
		$data = array(
			'type' => $this->type,
			'view' => $this->view,
			'is_lock' => $this->isLock,
			'is_new' => $this->isNew,
			'is_hide' => $this->isHide,
			'is_sub_field' => $this->isSubField,
			'help' => $this->help,
			'id' => $this->id,
			'name' => $this->isSubField
				? "{$this->prefix}[{$this->name}]"
				: "{$this->prefix}{$this->name}",
			'value' => null === $value ? $this->default : $this->normalize($value),
			'default' => $this->default,
			'attributes' => array(),
			'label' => $this->label,
			'description' => $this->description,
			'contentBefore' => $this->contentBefore,
			'content' => $this->content,
			'contentAfter'  => $this->contentAfter,
			'contentAfterBlock'  => $this->contentAfterBlock,
			'options' => $this->options,
			'dependents' => array(),
			'fields' => null,
		);

		if ($this->isLock) {
			//$data['name'] = null;
			//$data['value'] = null;
		}

		foreach ($this->attributes as $attrName => $attrValue) {
			$attrValue = is_array($attrValue)
				? implode(' ', array_map('esc_attr', $attrValue))
				: esc_attr($attrValue);

			if($attrValue!=null)
				$data['attributes'][$attrName] = sprintf('%s="%s"', $attrName, $attrValue);
		}
		$data['attributes'] = implode(' ', $data['attributes']);

		foreach ($this->dependents as $value => $actions) {
			if (!is_array($actions)) {
				$data['dependents'][$value] = array();
				continue;
			}

			foreach ($actions as $action => $selectors) {
				if (is_array($selectors)) {
					$data['dependents'][$value][$action] = array_map('esc_attr', $selectors);
				} else {
					$data['dependents'][$value][$action] = array();
				}
			}
		}
		$data['dependents'] = json_encode($data['dependents']);

		return $data;
	}


	final public function save($value){
		if (null === $this->name) {
			return;
		}

		if ($this->fields) {
			$normalizedValue = array();
			foreach( $this->fields as $subField ) {
				$subValue = isset($value[$subField->name]) ? $value[$subField->name] : null;
				$normalizedValue[$subField->name] = $subField->normalize($subValue);
			}
		} else {
			$normalizedValue = $this->normalize($value);
		}

		update_post_meta($this->postId, "{$this->prefix}{$this->name}", $normalizedValue);
	}


	final public static function randId(){
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet) - 1;
		$length = 10;
		$token = "";

		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[mt_rand(0, $max)];
		}

		return $token;
	}


	protected function getDefaultOptions(){
		return array();
	}


	protected function normalize($value){
		if ($this->cbSanitize && is_callable($this->cbSanitize)) {
			$value = call_user_func($this->cbSanitize, $value);
		}
		return $value;
	}
}
