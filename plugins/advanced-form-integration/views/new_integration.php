<?php

global $wpdb;

$integration_table = $wpdb->prefix . 'adfoin_integration';
$last_id           = $wpdb->get_var( "SELECT MAX(id) FROM {$integration_table}" );
$last_id           = empty( $last_id ) ? 0 : $last_id;
$integration_title = "Integration #" . ( $last_id + 1 );
$nonce             = wp_create_nonce( 'adfoin-integration' );
$field_data        = array();
$form_providers_html = adfoin_get_form_providers_html();
?>
<script type="text/javascript">
    var integrationTitle = <?php echo json_encode( $integration_title, true ) ; ?> ;
</script>

<?php do_action( "adfoin_add_js_fields", $field_data ); ?>

<div class="wrap">

    <div id="icon-options-general" class="icon32">  </div>
    <h1> <?php esc_attr_e( 'New Integration', 'advanced-form-integration' ); ?></h1>

    <div id="adfoin-new-integration" v-cloak>

        <div id="post-body" class="metabox-holder ">

            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="new-integration" >

               <input type="hidden" name="action" value="adfoin_save_integration">

                <input type="hidden" name="type" value="new_integration" />

                <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />

                <input type="hidden" name="form_name" :value="trigger.formName" />

                <input type="hidden" name="triggerData" :value="JSON.stringify( trigger )" />

                <input type="hidden" name="actionData" :value="JSON.stringify( action )" />

                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">
                            <?php esc_attr_e( 'Integration Title', 'advanced-form-integration' ); ?>
                        </th>
                        <td scope="row">

                        </td>
                    </tr>

                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_attr_e( 'Title', 'advanced-form-integration' ); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" class="regular-text" v-model="trigger.integrationTitle" name="integration_title" placeholder="<?php _e( 'Enter title here', 'advanced-form-integration'); ?>" required="required">
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <?php esc_attr_e( 'Trigger', 'advanced-form-integration' ); ?>
                        </th>
                        <td scope="row">

                        </td>
                    </tr>

                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_attr_e( 'Form/Data Provider', 'advanced-form-integration' ); ?>
                            </label>
                        </td>
                        <td>
                            <select name="form_provider_id" v-model="trigger.formProviderId" @change="changeFormProvider" required="required">
                                <option value=""> <?php _e( 'Select...', 'advanced-form-integration' ); ?> </option>
                                <?php
                                echo $form_providers_html;
                                ?>
                            </select>
                            <!-- <a v-if="trigger.formProviderId" class="help-doc-link" :href="'https://advancedformintegration.com/docs/afi/sender-platforms/' + trigger.formProviderId + '/'" title="Help Doc" target="__blank" rel="noopener noreferrer"><span class="dashicons dashicons-admin-links"></span></a> -->
                            <div class="spinner" v-bind:class="{'is-active': formLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;">
                        </td>
                    </tr>

                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_attr_e( 'Form/Task Name', 'advanced-form-integration' ); ?>
                            </label>
                        </td>
                        <td>
                            <select name="form_id" v-model="trigger.formId" :disabled="formValidated == 1" @change="changedForm"  required="required">
                                <option value=""> <?php _e( 'Select...', 'advanced-form-integration' ); ?> </option>
                                <option v-for="(item, index) in trigger.forms" :value="index" > {{ item }}  </option>
                            </select>
                            <div class="spinner" v-bind:class="{'is-active': fieldLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;">
                        </td>
                    </tr>
                    
                    <?php do_action( "adfoin_trigger_extra_fields", $field_data ); ?>

                    <tr valign="top">
                        <th scope="row">
                            <?php esc_attr_e( 'Action', 'advanced-form-integration' ); ?>
                        </th>
                        <td scope="row">

                        </td>
                    </tr>

                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_attr_e( 'Platform', 'advanced-form-integration' ); ?>
                            </label>
                        </td>
                        <td>
                            <select name="action_provider" v-model="action.actionProviderId" @change="changeActionProvider"  required="required">
                                <option value=""> <?php _e( 'Select...', 'advanced-form-integration' ); ?> </option>
                                <?php
                                foreach ( $action_providers as $key => $value ) {
                                    echo "<option value='" . $key . "'> " . $value . " </option>";
                                } ?>

                            </select>
                            
                            <div class="spinner" v-bind:class="{'is-active': actionLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;">
                        </td>
                    </tr>

                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_attr_e( 'Task', 'advanced-form-integration' ); ?>
                            </label>
                        </td>
                        <td>
                            <select name="task" v-model="action.task" :disabled="actionValidated == 1"  required="required">
                                <option value=""> <?php _e( 'Select...', 'advanced-form-integration' ); ?> </option>
                                <option v-for="(task, index) in action.tasks" :value="index" > {{ task }}  </option>
                            </select>
                        </td>
                    </tr>


                </table>

                <component v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData" v-bind:is="action.actionProviderId"></component>
                <cl-main v-if="action.task" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></cl-main>

                <br>
                <!-- Save intigartion Starts -->
                <div>
                    <p>
                        <input class="button-primary" type="submit" name="save_integration" value="<?php esc_attr_e( 'Save Integration', 'advanced-form-integration' ); ?>" />
                        <a class="button-secondary" style="color: red" href="<?php echo admin_url('admin.php?page=advanced-form-integration')?>" class="button-secondary"> <?php esc_attr_e( 'Cancel', 'advanced-form-integration' ); ?></a>
                    </p>
                </div>
                <!-- Save intigartion Ends -->
            </form>

        </div>
        <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">

    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->

<?php do_action( 'adfoin_action_fields' ); ?>

<script type="text/template" id="editable-field-template">
    <tr class="alternate" v-if="inArray(action.task, field.task)">
        <td>
            <label for="tablecell">
                {{field.title}}
            </label>
        </td>
        <td>
            <input v-if="field.type == 'text'" type="text" ref="fieldValue" class="regular-text" v-model="fielddata[field.value]" :name="'fieldData['+field.value+']'" v-bind:required="field.required">
            <textarea rows="5" cols="46" v-if="field.type == 'textarea'" type="text" ref="fieldValue" v-model="fielddata[field.value]" :name="'fieldData['+field.value+']'"  v-bind:required="field.required"></textarea>
            <select v-if="trigger.formProviderId !== 'webhooksinbound'" @change="updateFieldValue" v-model="selected">
                <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
            </select>
            <p v-if="field.description" class="description">{{field.description}}</p>
        </td>
    </tr>
</script>

<script type="text/template" id="cl-main-template">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php esc_attr_e( 'Continue If (conditional logic)', 'advanced-form-integration' ); ?>
            </th>
            <td scope="row">
                <input type="checkbox" v-model="action.cl.active" true-value="yes" false-value="no"><input class="button-secondary" style="margin-left:10px;" v-if="action.cl.active == 'yes'" type="submit" value="<?php esc_attr_e( 'Add', 'advanced-form-integration' ); ?>" @click.prevent="clAddCondition" />
                <!-- <a style="margin-left:10px;" href="https://advancedformintegration.com/docs/conditional-logics/" target="__blank" rel="noopener noreferrer" v-if="action.cl.active == 'yes'">Doc</a> -->
            </td>
        </tr>

        <conditional-logic  v-for="condition in action.cl.conditions" v-bind:condition="condition" v-bind:key="condition.id" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></conditional-logic>

        <tr valign="top" class="alternate" v-if="action.cl.active == 'yes'">
            <td scope="row-title">
                <label for="tablecell">
                    <?php esc_attr_e( 'Must match condition', 'advanced-form-integration' ); ?>
                </label>
            </td>
            <td>
                <select v-model="action.cl.match">
                    <option value="any"> <?php _e( 'Any', 'advanced-form-integration' ); ?> </option>
                    <option value="all"> <?php _e( 'All', 'advanced-form-integration' ); ?> </option>
                </select>
            </td>
        </tr>
    </table>
</script>

<script type="text/template" id="conditional-logic-template">
    <tr valign="top" class="alternate" v-if="action.cl.active == 'yes'">
        <td scope="row-title">
            <label for="tablecell">
                <?php esc_attr_e( 'Condition ', 'advanced-form-integration' ); ?>{{condition.id}}
            </label>
        </td>
        <td>
            <select v-model="selected2" @change="updateFieldValue">
                <option value=""><?php _e( 'Form Field...', 'advanced-form-integration' ); ?></option>
                <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
            </select>
            <input type="text" v-model="condition.field">
            <select v-model="condition.operator">
                <?php
                $operators = adfoin_get_cl_conditions();

                foreach ( $operators as $key => $value ) {
                    echo "<option value='" . $key . "'> " . $value . " </option>";
                }
                ?>
            </select>
            <input type="text"  v-model="condition.value">
            <input class="button-secondary" style="margin-left:10px;" type="submit" value="x" @click.prevent="clRemoveCondition(condition)" />
        </td>
    </tr>
</script>

<?php do_action( 'adfoin_trigger_templates' ); ?>