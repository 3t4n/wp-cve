<?php

class Posts_Character_Count {
	var $text;
	var $characters = 0;

	public function __construct( $text = null ) {
		if ( $text ) {
			$this->set( $text );
		}
	}

	public function set( $text ) {
		$this->text       = strip_tags( $text );
		$this->characters = - 1;
	}

	public function get() {
		return $this->text;
	}

	public function count_characters() {
		if ( $this->characters != - 1 ) {
			return $this->characters;
		}

		$this->characters = strlen( utf8_decode( $this->text ) );

		return $this->characters;
	}
} // End class