<script type="text/template" id="editable-field-template">
    <tr class="alternate" v-if="action.task == field.task">
        <td>
            <label for="tablecell">
                {{field.title}}
            </label>
        </td>
        <td>
            <input type="text" ref="fieldValue" class="regular-text" v-model="fielddata[field.value]"  v-bind:required="field.required">
            <select @change="updateFieldValue" v-model="selected">
                <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
            </select>
        </td>
    </tr>
</script>