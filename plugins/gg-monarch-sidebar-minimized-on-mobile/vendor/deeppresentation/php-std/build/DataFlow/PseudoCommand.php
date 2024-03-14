<?php namespace MSMoMDP\Std\DataFlow;

use MSMoMDP\Std\Core\Arr;


class PseudoCommand {

	private $commands;
	private $data;
	private $arguments;
	//[0] action:  hover content convert_[convertor-name]_style_[style-property]
	//[1] content: img h2 p
	//[2] link: file-name url

	public function __construct( $pseudoCode, $data, $pseudoCodeDelimiter = '_', $pseudoCodeArgsDelimiter = '|', $pseudoCodeArgsKeyValSeparator = ':' ) {
		$COMMAND_SEQUENCE = array( 'action', 'content', 'link', 'args' );
		$this->commands   = array();
		$this->data       = $data;
		$pseudoCodeItems  = explode( $pseudoCodeDelimiter, $pseudoCode );
		foreach ( $COMMAND_SEQUENCE as $command ) {
			$this->commands[ $command ] = array_shift( $pseudoCodeItems );
		}
		if ( $this->get_link() == 'url' && ( ! empty( $this->get_data() ) ) ) {
			$a = $this->get_data();
		}
		if ( isset( $this->commands['args'] ) ) {
			$this->arguments = array();
			$argsItems       = explode( $pseudoCodeArgsDelimiter, $this->commands['args'] );
			foreach ( $argsItems as $argsItem ) {
				$argsItemKeyVal = explode( $pseudoCodeArgsKeyValSeparator, $argsItem );
				if ( count( $argsItemKeyVal ) == 2 ) {
					$this->arguments[ $argsItemKeyVal[0] ] = $argsItemKeyVal[1];
				}
			}
		}
	}
	public function get_action() {
		return $this->get_command( 'action' );
	}
	public function get_content() {
		 return $this->get_command( 'content' );
	}
	public function get_link() {
		return $this->get_command( 'link' );
	}
	public function get_argVal( $key, $def = null ) {
		return Arr::get( $this->arguments, $key, $def );
	}
	public function get_command( $commandType ) {
		if ( array_key_exists( $commandType, $this->commands ) ) {
			return $this->commands[ $commandType ];
		}
		return null;
	}
	public function get_data() {
		return $this->data;
	}
	public static function generate_pseudo_command( $action, $content, $contentSrc, $delimiter = '_' ) {
		return $action . $delimiter . $content . $delimiter . $contentSrc;
	}
	public static function get_pseudo_action( $pseudoCode, $pseudoCodeDelimiter = '_' ) {
		$pseudoCodeItems = explode( $pseudoCodeDelimiter, $pseudoCode );
		if ( isset( $pseudoCodeItems ) && count( $pseudoCodeItems ) > 0 ) {
			return $pseudoCodeItems[0];
		}
		return null;
	}
}
