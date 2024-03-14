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
mobx.on("beforeAppendDOM.modulobox",function(b){for(var a in this.buttons)this.buttons.hasOwnProperty(a)&&"undefined"!==typeof mobx_accessibility&&mobx_accessibility[a+"Label"]&&(this.buttons[a].setAttribute("aria-label",mobx_accessibility[a+"Label"]),mobx_accessibility.title&&this.buttons[a].setAttribute("title",mobx_accessibility[a+"Label"]))});
SCRIPT;
