<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
if ( false == apply_filters( 'wfacp_show_preview_field', true, $instance ) ) {
	return;
}
$count_increment = 0;

if ( 'single_step' != $step ) {

	$preview_heading    = $instance->get_preview_field_heading();
	$preview_subheading = $instance->get_preview_field_sub_heading();
	?>
    <div class="wfacp_preview_content_box" data-step="<?php echo $step; ?>">
		<?php
		if ( '' !== $preview_heading ) {
			?>
            <div class="wfacp-section">
                <div class="wfacp-comm-title none">
                    <h2 class="wfacp_section_heading wfacp_section_title wfacp-normal"><?php echo $preview_heading; ?> </h2>
					<?php
					if ( '' !== $preview_subheading ) {
						?>
                        <h4 class="wfacp-text-left wfacp-normal"><?php echo $preview_subheading; ?></h4>
						<?php
					}
					?>
                </div>
            </div>
			<?php
		}
		?>
        <div class="wfacp_step_preview"></div>
    </div>
	<?php
}
?>