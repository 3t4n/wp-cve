<?php namespace MSMoMDP\Std\Html;

use MSMoMDP\Std\Html\Attr;
use League\ISO3166\Exception\InvalidArgumentException;

class Element {

	protected $breakToNewBEMModule = false;
	private $element               = null;
	private $hasClosing            = true;
	private $BEMBase               = null;
	private $BEMMod                = array();
	protected $content             = array();
	/** @var Element */
	private $parent = null;
	/** @var Attr */
	public $attributes = null;

	// SECTION Public
	//@param string|bool $content
	//@param array|MSMoMDP\Std\Html\Attr $attributes
	public function __construct( string $element, string $BEMBase = null, $attributes = null, $content = null, $BEMMod = array(), $hasClosing = true ) {
		if ( ! $element ) {
			throw new InvalidArgumentException( 'Argument $element must be defined. ' );
		}
		$this->parent = null;

		//$this->bemClassPart = $bemClassPart;
		$this->BEMBase = $BEMBase;
		$this->BEMMod  = is_string( $BEMMod ) ? array( $BEMMod ) : $BEMMod;
		$this->element = $element;
		$this->add_content( $content );
		$this->attributes = new Attr();
		if ( $attributes ) {
			if ( is_array( $attributes ) ) {
				$this->attributes = new Attr( $attributes );
			} elseif ( is_a( $attributes, 'MSMoMDP\Std\Html\Attr' ) ) {
				$this->attributes = $attributes;
			}
		}
		$this->hasClosing = $hasClosing;
	}

	//@param string|Element|array $content
	public function add_content( $content ) {
		if ( ! $this->hasClosing ) {
			throw new \BadMethodCallException( "Can't add content into inline not-closing element" );
		}
		/*  if (count($this->BEMMod) > 0) {
			throw new InvalidArgumentException("Can't add content into element with BEM modifiers, only last child level is allowed to have an BEM modifiers");
		}*/
		if ( $content ) {
			if ( is_array( $content ) ) {
				foreach ( $content as $contentItem ) {
					$this->_add_one_content( $contentItem );
				}
			} else {
				$this->_add_one_content( $content );
			}
		}
	}

	private function _add_one_content( $content ) {
		if ( is_string( $content ) || is_a( $content, 'MSMoMDP\Std\Html\Element' ) ) {
			if ( is_a( $content, 'MSMoMDP\Std\Html\Element' ) ) {
				$content->parent = $this;
			}
			$this->content[] = $content;
		}
	}

	public function get_BEM_full_classes() {
		$identifier = $this->BEMBase;
		$parent     = $this->parent;

		while ( $parent && $parent->BEMBase ) {
			$identifier = $parent->BEMBase . '__' . $identifier;
			if ( $parent->breakToNewBEMModule ) {
				break;
			}
			$parent = $parent->parent;
		}

		$BEMClases = array( $identifier );
		if ( $this->breakToNewBEMModule ) {
			$BEMClases[] = $this->BEMBase;
		}
		if ( $this->BEMMod ) {
			foreach ( $this->BEMMod as $mod ) {
				$BEMClases[] = $identifier . '--' . $mod;
			}
		}
		return $BEMClases;
	}

	public function to_str() {
		$htmlString = '<' . $this->element;
		if ( $this->BEMBase ) {

			$this->attributes->apend_attr( array( 'class' => $this->get_BEM_full_classes() ) );
		}
		$htmlString .= $this->attributes->to_str();
		$htmlString .= '>';

		foreach ( $this->content as $content ) {
			if ( is_a( $content, 'MSMoMDP\Std\Html\Element' ) ) {
				$htmlString .= $content->to_str();
			} else {
				$htmlString .= $content;
			}
		}
		if ( ! $this->hasClosing ) {
			return $htmlString;
		}
		$htmlString .= '</' . $this->element . '>';
		return $htmlString;
	}

	public function render() {
		echo $this->to_str();
	}

	public function renderOpened() {
		$tmpClosing       = $this->hasClosing;
		$this->hasClosing = false;
		echo $this->to_str();
		$this->hasClosing = $tmpClosing;
	}
	public function renderClosing() {
		echo '</' . $this->element . '>';
	}



	// !SECTION End - Public

}
