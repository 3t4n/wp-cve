<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Feature extends \Fab\Controller\Controller {

	/**
	 * Feature key
	 *
	 * @var     string
	 */
	protected $key;

	/**
	 * Feature name
	 *
	 * @var     string
	 */
	protected $name;

	/**
	 * Feature description
	 *
	 * @var     string
	 */
	protected $description;

	/**
	 * Feature options
	 *
	 * @var     object
	 */
	protected $options;

	/**
	 * Feature params
	 *
	 * @var     object
	 */
	protected $params;

	/**
	 * Feature construect
	 *
	 * @return void
	 * @var    object   $plugin     Feature configuration
	 * @pattern prototype
	 */
	public function __construct() {
        parent::__construct(\Fab\Plugin::getInstance());
		$this->options            = (object) array();
		$this->params             = (object) array();
		$this->hide_on_production = false;
		$this->Form               = $this->Plugin->getForm();
	}

    /** Generate Options HTML in Backend */
    public function generateOptionsHTML($options, $parentKey = array()){
        foreach($options as $key => $option):
            if(isset( $option['children'] )) {
                $args = array();
                if(isset($option['info'])) $args['info'] = $option['info'];
                $this->Form->Heading( $option['text'], $args);
                $parentKey[] = $key;
                $parentKey[] = 'children';
                $this->generateOptionsHTML( $option['children'], $parentKey );
            } else {
                /** Option */
                $optionContainer = array( 'id' => sprintf('module_option_%s', $key) );
                ob_start();
                $singleKey = $parentKey;
                $singleKey[] = $key; $singleKey[] = 'value';
                $name = sprintf('fab_%s%s', $this->getKey(), sprintf('[%s]', implode('][', $singleKey)) );
                $args = $option;
                $args['id'] = $optionContainer['id'];
                if( isset($option['class']) ) $args['class'] = $option['class'];
                if( $option['type']==='number' ){ $this->Form->number( $name, $args ); }
                elseif( $option['type']==='switch' ){ $this->Form->switch( $name, $args ); }
                elseif( $option['type']==='select' ){ $this->Form->select( $name, $option['options'], $args ); }
                elseif( $option['type']==='text' ){ $this->Form->text( $name, $args ); }
                /** Container */
                $args = array( 'label' => array( 'id' => $optionContainer['id'], 'text' => $option['text'] ) );
                if( isset($option['info']) ) $args['info'] = $option['info'];
                $this->Form->container( 'setting', ob_get_clean(), $args);
            }
        endforeach;
    }

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @param string $key
	 */
	public function setKey( $key ) {
		$this->key = $key;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 * @return object
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @param object $options
	 */
	public function setOptions( $options ): void {
		$this->options = $options;
	}

	/**
	 * @return object
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @param object $params
	 */
	public function setParams( $params ): void {
		$this->params = $params;
	}

    /** Grab All Assigned Variables */
    public function getVars() {
        return get_object_vars( $this );
    }

}
