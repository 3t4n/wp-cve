<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return <<<'SCRIPT'

mobx.on( 'beforeAppendDOM.modulobox', function( DOM ) {

	for ( var type in this.buttons ) {

		if ( this.buttons.hasOwnProperty( type ) ) {

			if ( typeof mobx_accessibility !== 'undefined' && mobx_accessibility[ type + 'Label'] ) {

				this.buttons[type].setAttribute( 'aria-label', mobx_accessibility[ type + 'Label'] );

				if ( mobx_accessibility.title ) {

					this.buttons[type].setAttribute( 'title', mobx_accessibility[ type + 'Label'] );

				}

			}

		}

	}

});

SCRIPT;
