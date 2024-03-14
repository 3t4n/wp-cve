<?php
defined( 'ABSPATH' ) || exit;
?>
<div v-for="(fields,section) in available_fields" class="wfacp_input_fields" v-if="wfacp.tools.ol(fields)>0">
    <div class="wfacp_input_fields_list_wrap">
        <div class="wfacp_input_fields_title" v-if="section=='billing'"><b><?php _e( 'Basic ', 'woofunnels-aero-checkout' ); ?></b></div>
        <div class="wfacp_input_fields_title" v-if="section!='billing'"><b>{{section}} </b></div>
        <hr/>
        <div class="wfacp_input_fields_list" v-bind:id="'input_field_'+section+'_container'">
            <div class="wfacp_input_field_btn_holder" v-for="(data,index) in fields">
                <div class="wfacp_locked_field" v-if="'yes'==data.is_locked">
                    <div class="wfacp_save_btn_style wfacp_input_field_place_holder wfacp_locked" v-on:click="wfacp.show_pro_message('pro_fields')">
                        <span class="wfacp_lock_icon"><img src="<?php echo esc_url( WFACP_PLUGIN_URL . '/admin/assets/img/lock.svg' ); ?>"></span><span>{{data.label}}</span>
                    </div>
                </div>
                <div v-else="">
                    <div v-if="true==wfacp.tools.hp(input_fields[section],index)" v-bind:id="index" class="wfacp_save_btn_style wfacp_item_drag" v-bind:data-input-section="section" draggable="true" v-on:dragstart="dragStart($event)" v-on:dragend="dragEnd($event)">
                        <span class="dashicons dashicons-no-alt" v-on:click="deleteCustomField(section,index,data.label)" v-if="data.is_wfacp_field"></span>
                        <span>{{''!==data.label?data.label:data.data_label}}</span>
                    </div>
                    <div v-if="false==wfacp.tools.hp(input_fields[section],index)" class="wfacp_save_btn_style wfacp_input_field_place_holder">
                        <span>{{''!==data.label?data.label:data.data_label}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wfacp_input_fields_btn">
    <button class="button" v-on:click="wfacp.show_pro_message('add_custom_field')">
        <img src="<?php echo esc_url( WFACP_PLUGIN_URL . '/admin/assets/img/lock.svg' ); ?>"><?php esc_html_e( 'Add New Form Field', 'woofunnels-aero-checkout' ); ?>
    </button>
</div>