<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
do_action( 'wfacp_footer_before_print_scripts' );

$page_id_editable = WFACP_Core()->embed_forms->page_is_editable();
echo '<div class=wfacp_footer_sec_for_secript>';
if ( false == $page_id_editable || WFACP_Common::is_customizer() ) {
	wp_footer();
}
echo '</div>';
do_action( 'wfacp_footer_after_print_scripts' );
if ( false == $page_id_editable || WFACP_Common::is_customizer() ) {
	?>
    </body>
    </html>
	<?php
}
?>