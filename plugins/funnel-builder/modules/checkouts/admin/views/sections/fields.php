<?php
defined( 'ABSPATH' ) || exit;
$template_is_set = get_post_meta( $this->wfacp_id, '_wfacp_selected_design' );
$version         = WFACP_Common::get_checkout_page_version();

$is_old_version = false;
if ( version_compare( $version, WFACP_VERSION, '<=' ) ) {
	$is_old_version = true;
}
if ( false == $is_old_version && empty( $template_is_set ) ) {
	include __DIR__ . '/fields/no-template.php';

	return;
}
?>

<style>
    .wfacp_input_fields {
        margin-top: 25px;
    }
</style>
<?php
$section = filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW );;
?>
<div id="wfacp_layout_container">
    <div class="wfacp_p20_noside wfacp_box_size">
        <div class="wfacp_wrap_inner wfacp_wrap_inner_offers <?php echo ( ! is_null( $section ) ) ? 'wfacp_wrap_inner_' . $section : ''; ?>" style="margin-left: 0;">
            <div class="wfacp_wrap_r">
                <div class="template_field_holder" style="min-height: 500px">
                    <div class="template_steps_container" style="float: left;width:70%">
                        <div class="wfacp_fsetting_table_head">
                            <div class="wfacp_fsetting_table_head_in wfacp_clearfix">
                                <div class="wfacp_fsetting_table_title">
                                    <div class="wfacp_template_tabs_container clearfix">
                                        <div class="wfacp_step_actions">
                                            <div v-for="(step,slug) in steps" v-if="step.active=='yes'" class="wfacp_step_heading">
                                                <div v-bind:class="'wfacp_template_tabs '+(slug=='single_step'?'wfacp_active_tabs':'')" v-bind:data-slug="slug">{{step.name}}
                                                    <span class="dashicons dashicons-dismiss" v-if="(current_step==slug) && (current_step!='single_step')" v-on:click.prevent="deleteStep(slug)"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="wfacp_add_new_step" v-if="current_step!='third_step'">
                                            <div class="wfacp_step wfacp_modal_open wfacp_step_add_step_btn" v-on:click="wfacp.show_pro_message('add_new_step')">
                                                <i class="dashicons dashicons-plus"></i>
												<span class="wfacp_hide"><?php _e( 'Add New Step', 'funnel-builder' ); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bwf_ajax_save_buttons bwf_form_submit">
                                    <a href="javascript:void(0)" id="wfacp_save_form_layout" class="wfacp_save_btn_style" v-on:click="save_template()"><?php _e( 'Save Changes', 'funnel-builder' ); ?></a>
                                </div>
                            </div>
                        </div>
                        <div v-for="(d,m) in global_dependency_messages" v-if="d.show=='yes'" v-bind:class="'wfacp_field_dependency_messages '+d.type">
                            <div class="wfacp_dependency_alert_icon">
                                <img src="<?php echo WFACP_PLUGIN_URL . '/admin/assets/img/form-tab/danger.svg' ?>" alt="">
                            </div>
                            <div class="notice_msg_wrap">
                                <p v-html="d.message"></p>
                            </div>
                            <span v-if="d.dismissible==true" v-on:click="remove_dependency_messages(m)" class="wfacp_close_icon">x</span>
                        </div>

						<?php include_once __DIR__ . '/fields/field_container.php'; ?>

                    </div>
                    <div class="template_field_selecter" style="float: right; width:28%">

                        <div class="wfacp_fsetting_table_head">
                            <div class="wfacp_fsetting_table_head_in wfacp_clearfix">
                                <div class="wfacp_fsetting_table_title">
                                    <strong><span class="wfacp_template_friendly_name"><?php _e( 'Fields', 'funnel-builder' ); ?></span></strong>
                                </div>

                            </div>
                        </div>
						<?php include_once __DIR__ . '/fields/input_fields.php'; ?>
                    </div>
                    <div style="clear: both"></div>
                </div>


            </div>
            <div style="clear: both"></div>
        </div>
    </div>

</div>
<?php include_once __DIR__ . '/fields/models.php'; ?>
