<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $instance WFACP_template_layout4
 */

$next_action = 'single_step';
if ( $current_action == 'third_step' ) {
	$next_action = 'two_step';
}
$change_back_btn = apply_filters( 'wfacp_change_back_btn', 'Previous Step', $next_action, $current_action );

if ( $change_back_btn != '' ) {
	?>
    <div class="wfacp-back-btn-wrap wfacp_back_wrap">
        <a class='wfacp_back_page_button' data-next-step="<?php echo $next_action; ?>" data-current-step='<?php echo $current_action; ?>' href='javascript:void(0)'>
			<?php esc_html_e( $change_back_btn ); ?>
        </a>
    </div>
	<?php
}
?>
