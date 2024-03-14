<?php

namespace LIBRARY;

final class CustomizerOption extends \WP_Customize_Setting {

	public function import( $value ) {
		$this->update( $value );
	}
}
