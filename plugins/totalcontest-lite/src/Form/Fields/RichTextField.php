<?php

namespace TotalContest\Form\Fields;

use TotalContestVendors\TotalCore\Form\Field;
use TotalContestVendors\TotalCore\Helpers\Html;
use TotalContestVendors\TotalCore\Helpers\Misc;

/**
 * Class RichTextField
 * @package TotalContest\Form\Fields
 */
class RichTextField extends Field {

	/**
	 * @return Html
	 */
	public function getInputHtmlElement() {
		$field = new Html( 'div', $this->getAttributes() );
		ob_start();
		wp_editor( $this->getValue(), md5( $this->getName() ), [
			'wpautop'       => false,
			'textarea_name' => $this->getOption( 'name' ),
			'textarea_rows' => 10,
			'teeny'         => true,
			'quicktags'     => false,
			'media_buttons' => false,
			'tinymce'       => [
				'setup' => 'init_totalcontest_richtext',
			],
		] );
		$tinyMce = ob_get_clean();

		$js = '<script type="text/javascript">function init_totalcontest_richtext(ed) {ed.on("change", function() {tinyMCE.triggerSave();})};</script>';

		if ( Misc::isDoingAjax() ):
			ob_start();
			$js .= '<script>jQuery("body > .mce-toolbar-grp").remove();</script>';
			//do_action( 'admin_print_footer_scripts' );
			$js .= ob_get_clean();
			$js .= '<script type="text/javascript">TotalContest.Utils.refreshTinyMCE();</script>';

		endif;

		$field->appendToInner( $tinyMce );
		$field->appendToInner( $js );

		return $field;
	}

	public function getValue() {
		$value = $this->value === null ? $this->default : $this->value;

		return wp_kses_stripslashes( wp_filter_post_kses( $value ) );
	}

	public function getAttributes() {
		return [];
	}
}
