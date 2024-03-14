<div class="single_step_template" v-for="(step,slug) in steps" v-if="step.active=='yes'" v-bind:data-slug="slug" v-bind:style="slug=='single_step'?'':'display:none'">
    <div v-bind:class="'wfacp_sections_holder '+slug">
        <div v-for="(fieldset,f_index) in fieldsets[slug]" class="wfacp_field_container" v-bind:field-index="f_index" v-bind:step-name="slug">
            <div class="wfacp_fields_border_div">
                <div class="wfacp_field_container_head clearfix">
                    <div class="wfacp_field_container_heading">
                        <h4 v-html="fieldset.name">{{fieldset.name}}</h4>
                        <h5>{{fieldset.sub_heading}}</h5>
                    </div>
                    <div class="wfacp_field_container_action">
                        <a href="#" v-on:click.prevent="editSection(slug,f_index)">
                            <?php echo file_get_contents(  plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/icons/edit.svg'  ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </a>
                        <a href="#" v-on:click.prevent="deleteSection(slug,f_index)">
                            <?php echo file_get_contents(  plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/icons/delete.svg'  ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </a>
                    </div>
                </div>
                <div v-bind:class="'template_field_container '+slug" v-bind:field-index="f_index" v-on:drop="drop($event,slug,f_index)" v-on:dragover="allowDrop($event)" v-on:dragenter="dragEnter($event)" v-on:dragleave="dragLeave($event)" v-bind:step-name="slug">
                    <div v-if="wfacp.tools.ol(fieldset.fields)>0" v-for="(data,index) in fieldset.fields" v-bind:data-id="data.id" class="wfacp_save_btn_style wfacp_item_drag" v-if="data.label" v-bind:data-input-section="data.field_type" v-on:click="editField(slug,f_index,index,$event)">
                        <span class="wfacp_remove_fields dashicons dashicons-no" v-on:click="removeField(slug,data.id,data.field_type,f_index,$event)" v-if="data.id!='payment_method'"></span>
                        <span class="wfacp_tooltip"><?php _e( 'Click to edit. Drag to re-order.', 'funnel-builder' ) ?></span>
                        <span v-if="undefined!=data.data_label">{{data.data_label}}</span>
                        <span v-else="">{{data.label}}</span>
                    </div>
                    <div v-if="wfacp.tools.ol(fieldset.fields)==0" class="template_field_placeholder_tbl">
                        <div class="template_field_placeholder_tbl_cel"><?php _e( 'Drag new fields here to populate the section', 'funnel-builder' ); ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="wfacp_form_note_sec wfacp_field_container" v-if="slug==current_step">
        <div class="wfacp_field_container_head clearfix">
            <div class="wfacp_field_container_heading"><h4><?php _e( 'Payment Gateways', 'funnel-builder' ) ?></h4></div>
        </div>
        <p>
			<?php _e( 'Note: Payment Information containing gateways will be automatically added to the end of order form.', 'funnel-builder' ); ?>
        </p>
    </div>
    <div class="wfacp_input_fields_btn">
        <p v-if="wfacp.tools.ol(fieldsets[slug])==0"><?php _e( 'Create a new section to add fields in this step.', 'funnel-builder' ); ?></p>
        <button href="#" class="button" v-on:click="addSection(slug)"><?php _e( 'Add Section', 'funnel-builder' ); ?></button>
    </div>
</div>