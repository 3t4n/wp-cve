<?php
declare( strict_types=1 );

namespace Pontet_Labs\Hide_Admin_Notices;

class Context {
	public array $vars;

	public function __construct( array $vars = array() ) {
		if ( $options = get_option( Hide_Admin_Notices::OPTIONS_NAME ) ) {
			$this->vars = array_merge( $vars, $options );
		} else if ( add_option( Hide_Admin_Notices::OPTIONS_NAME, $vars ) ) {
			$this->vars = $vars;
		} else {
			//error
		}
	}

	public function init() {
		add_action( 'update_option_' . Hide_Admin_Notices::OPTIONS_NAME, array( $this, 'update_option' ) );
	}

	public function update_option( $value ): void {
		$this->vars = array_merge( $this->vars, $value );
	}

	public function get( $name, $default = null ) {
		return $this->vars[ $name ] ?? $default;
	}

	public function set( $name, $value ) {
		$this->vars[ $name ] = $value;
		update_option( Hide_Admin_Notices::OPTIONS_NAME, $this->vars );
	}
}