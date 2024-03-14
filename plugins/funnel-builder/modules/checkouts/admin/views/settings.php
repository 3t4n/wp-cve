<?php
defined( 'ABSPATH' ) || exit;

/** Registering Settings in top bar */
if ( class_exists( 'BWF_Admin_Breadcrumbs' ) ) {
	BWF_Admin_Breadcrumbs::register_node( [ 'text' => esc_html__( 'Settings', 'funnel-builder' ) ] );
}
 BWF_Admin_Breadcrumbs::render_sticky_bar();
?>
<style>
    .form-group.valid.wfacp_setting_heading.field-label label {
        /* font-size: 33px; */
        width: 100%;
        font-weight: 600;
    }

    .form-group.valid.wfacp_setting_heading.field-label {
        border-bottom: 1px solid #ddd;
    }

    .form-group.valid.wfacp_setting_track_and_events_start.field-label {
        width: 200px;
        float: left;
    }

    .form-group.valid.wfacp_checkbox_wrap.wfacp_setting_track_and_events_end.field-checkbox {
        width: 545px;
        display: inline-block;
        clear: both;
    }

    .form-group.valid.wfacp_checkbox_wrap.wfacp_setting_track_and_events_end.field-checkbox .field-wrap {
        padding-left: 0px;
    }

    .form-group.valid.wfacp_checkbox_wrap.wfacp_setting_track_and_events_end.field-checkbox .hint {
        padding-left: 0px;
    }
</style>
<div class="wfacp_global_settings">
    <div class="wrap wfacp_funnels_listing wfacp_global">
        <h1 class="wp-heading-inline"><?php _e( 'Settings' ); ?></h1>
		<hr class="wp-header-end">
		<?php
		$bwf_settings = BWF_Admin_Settings::get_instance();
		$bwf_settings->render_tab_html( 'wfacp' );
		?>
        <div class="wfacp_clear_10"></div>
        <div id="poststuff" class=" wfacp_global_settings_wrap wfacp_page_col2_wrap">
            <div class="wfacp_page_left_wrap" id="wfacp_global_settings">
                <div class="wfacp-product-tabs-view-vertical wfacp-product-widget-tabs">

                    <div class="wfacp-product-tabs-wrapper wfacp-tab-center">
                        <div v-for="(tab,index) in wfacp_data.global_settings.tabs"
                             class="wfacp-tab-title wfacp-tab-desktop-title wfacp_tracking_analytics" v-bind:id="tab.id"
                             v-bind:data-tab="index+1" role="tab" v-html="tab.title"></div>
                    </div>

                    <div class="wfacp-product-widget-container">
                        <div class="wfacp-product-tabs wfacp-tabs-style-line" role="tablist">

                            <div class="wfacp-product-tabs-content-wrapper">
                                <div class="wfacp_global_setting_inner">
                                    <div class="wfacp_global_container">
                                        <form id="modal-global-settings-form" class="wfacp_forms_global_settings"
                                              data-bwf-action="global_settings" v-on:submit.prevent="onSubmit">
                                            <div class="wfacp_vue_forms">
                                                <vue-form-generator :schema="schema" :model="model"
                                                                    :options="formOptions"></vue-form-generator>
                                                <fieldset>
                                                    <div class="bwf_form_submit" style="display: inline-block">
                                                        <input type="submit" class=" button button-primary"
                                                               value="<?php _e( 'Save Changes', 'woofunnels-aero-checkout' ); ?>"/>
                                                        <span class="wfacp_spinner spinner" style="float: left"></span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/global/model.php'; ?>
