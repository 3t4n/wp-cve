<div v-if="action.action == '\\WunderAuto\\Types\\Actions\\RunWorkflow'">
    <table class="wp-list-table" style="width: 100%;">
        <tr>
            <td>
                <?php _e('Select workflow', 'wunderauto');?>
            </td>
            <td>
                <select v-model="action.value.workflowId">
                    <option v-for="workflow in sharedState.workflows" :value="workflow.code">
                        {{ workflow.label }}
                    </option>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <?php _e('Select main object', 'wunderauto');?>
            </td>
            <td>
                <select v-model="action.value.mainObject">
                    <option v-for="objectType in sharedState.objects" :value="objectType">
                        {{ objectType }}
                    </option>
                </select>
            </td>
        </tr>

    </table>
</div>



