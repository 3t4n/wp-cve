<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( $this->admin_path . '/view/script-ui.php' );
$event               = BWFAN_Core()->sources->get_event( $trigger_events );
$automation_migrated = isset( $automation_meta['meta'] ) && isset( $automation_meta['meta']['v1_migrate'] ) && $automation_meta['meta']['v1_migrate'] == 1 ? true : false;
?>
<div class="bwfan_funnel_setting" id="bwfan_funnel_setting">
    <div class="bwfan_automation_setting_inner bwfan-page-loading">
        <div class="bwfan-show-page-loading"></div>
		<?php
		/** Don't show the automation ui if saved event is not available */
		if ( ! empty( $trigger_events ) && ! $event instanceof BWFAN_Event ) {
			/** Checking the status of 'requires_update' argument */
			if ( isset( $automation_meta['meta']['requires_update'] ) && 1 !== absint( $automation_meta['meta']['requires_update'] ) ) {
				BWFAN_Common::mark_automation_require_update( $automation_id );
			}
			?>
            <div class="bwfan_content_wrap">
                <div class="bwfan_p20">
                    <div class="bwfan-up-padding">
						<?php echo '<h3>' . esc_html__( 'Automation not available', 'wp-marketing-automations' ) . '</h3>'; ?>
						<?php esc_html_e( 'Please check dependent Plugins/ Connectors to see if they are active. Unable to figure out? Contact Support.', 'wp-marketing-automations' ); ?>
                    </div>
                </div>
            </div>
			<?php
		} else {
			?>
            <div class="bwfan_content_wrap" style="<?php echo ( $automation_migrated ) ? 'pointer-events: none;' : ''; ?>">
				<?php
				if ( $automation_migrated ) {
					echo '<div class="bwfan_p20">
                            <div class="update-message notice inline notice-warning notice-alt" style="display: block!important;padding: 10px;">' . esc_html__( "This automation is migrated hence not editable.", "wp-marketing-automations" ) . '</div>
                        </div>';
				}
				?>
                <div class="wl-wrap">
                    <div class="wl-sidebar-overlay"></div>
                    <div class="wr">
                        <div class="wr_t_wrap">
                            <div class="wr_tw"></div>
                            <div class="wr_tw_save"><a class="wr-form-btn" href="javascript:void(0)">Save</a></div>
                        </div>
                        <span class="wr-resizer"></span>
                        <div class="wr_wrap_form"></div>
                    </div>
                    <form action="" method="post" class="bwfan-form-wrapper bwfan-manage-automation-form" data-bwf-action="automation_submit">
						<?php
						include_once( $this->admin_path . '/view/trigger-events.php' );
						include_once( $this->admin_path . '/view/integrations.php' );
						?>
                        <div class="wl">
							<?php /** Build via template engine */ ?>
                        </div>

                        <input type="hidden" id="a_track_id" name="a_track_id" value="<?php echo $a_track_id ? esc_attr__( $a_track_id ) : 0; ?>"/>
                        <input type="hidden" id="t_to_delete" name="t_to_delete" value=""/>
                        <input type="hidden" name="automation_id" value="<?php esc_attr_e( $automation_id ); ?>"/>
                        <input type="submit" name="test_sub" value=<?php esc_attr_e( 'Submit', 'wp-marketing-automations' ); ?> class="bwfan-display-none">
                    </form>
                    <div class="bwfan_copied_action_msg"></div>
                </div>
                <div class="clearfix"></div>
            </div>
			<?php
		}
		?>
    </div>
</div>