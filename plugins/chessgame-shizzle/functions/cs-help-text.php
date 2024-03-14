<?php


/*
 * Help text for admin editor.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_admin_help_text() {
	$html = '
	<div class="cs_chessgame_admin_help">
		<span class="cs_chessgame_admin_help_header">' .
			esc_html__('(Help &raquo;)', 'chessgame-shizzle' ) .
		'</span><br />
		<div class="cs_chessgame_admin_help_inside">
			<span>' . /* translators: Do not translate the K. */ esc_html__('K: King', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the Q. */ esc_html__('Q: Queen', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the R. */ esc_html__('R: Rook', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the B. */ esc_html__('B: Bishop', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the N. */ esc_html__('N: Knight', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the (...) */ esc_html__('( ... ): Variation', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the {...} */ esc_html__('{ ... }: Comment', 'chessgame-shizzle' ) . '</span><br />
		</div>
	</div>';
	return $html;
}


/*
 * Help text for frontend form.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_form_help_text() {
	$html = '
	<div class="cs_chessgame_form_help">
		<span class="cs_chessgame_form_help_header">' .
			esc_html__('(Help &raquo;)', 'chessgame-shizzle' ) .
		'</span><br />
		<div class="cs_chessgame_form_help_inside">
			<span>' . /* translators: Do not translate the K. */ esc_html__('K: King', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the Q. */ esc_html__('Q: Queen', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the R. */ esc_html__('R: Rook', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the B. */ esc_html__('B: Bishop', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the N. */ esc_html__('N: Knight', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the (...). */ esc_html__('( ... ): Variation', 'chessgame-shizzle' ) . '</span><br />
			<span>' . /* translators: Do not translate the {...}. */ esc_html__('{ ... }: Comment', 'chessgame-shizzle' ) . '</span><br />
		</div>
	</div>';
	return $html;
}
