<?php
$utm = '?utm_source=dashboard&utm_medium=workfloweditor&utm_campaign=installed_users';
?>

<div id="v-wunderauto-action" style="display: none">
    <div class="wunderauto-action" @keydown.enter="onEnterKeyDown">

        <div v-if="actions.length == 0">
            <?php
            _e('Add one or more action to this Workflow. Click <b>Add action</b> to get started.', 'wunderauto');
            ?>
        </div>

        <table border="0" width="100%" class="wunderauto-table form-table" :refreshCount="sharedState.refreshCount">
            <template v-for="(action, actionKey) in actions">
                <tr v-on:click="setCurrentAction(actionKey)">
                    <td scope="row" class="wa-col-first">
                        <select v-model="action.action" class="wa-sel-action-name" @change="actionChange(actionKey)">
                            <option value=""><?php _e('[Select action...]', 'wunderauto')?></option>
                            <optgroup v-for="(group, groupKey) in atts.groups" :label="groupKey">
                                <option v-for="(item) in group"
                                        :value="item.class" :selected="item.class == action"
                                >
                                    {{item.title}}
                                </option>
                            </optgroup>
                        </select>
                        <p v-if="action.action">
                            <i>
                                {{ atts.actions[action.action].description }}
                            </i>
                        <p v-if="action.action && atts.actions[action.action].docLink" >
                            <a :href="atts.actions[action.action].docLink"
                               target="_blank">
                                {{ atts.actions[action.action].docLinkText }}
                            </a>
                        </p>
                        </p>
                    </td>
                    <td>
                        <div class="wa-col-second-small">
                            <?php do_action('wunderauto_action_fields') ?>
                        </div>
                        <p v-if="objectsEmitted(actionKey) > 0">
                            <br><strong><?php _e('Objects provided by this action', 'wundeauto');?>:</strong><br>
                            <span v-for="(object, key) in sharedState.actionObjects">
                                    <span class="parameter-pill">{{ object.id }}</span>
                                        <br>
                                    </span>
                            </span>
                        </p>
                    </td>
                    <td class="wa-col-buttons">
                        <p v-if="actions.length > 1" style="font-size: 0px;">
                            <button class="button button-primary button-small" title="Move up"
                                    v-on:click.prevent="reorderAction(actionKey, -1)"
                                    style="padding: 0 2px; margin: 1px; background-color: #5b9dd9"
                                    v-if="actionKey > 0">
                                <span class="dashicons dashicons-arrow-up"></span>
                            </button>
                            <button class="button button-primary button-small" title="Move down"
                                    v-on:click.prevent="reorderAction(actionKey, 1)"
                                    style="padding: 0 2px; margin: 1px; background-color: #5b9dd9"
                                    v-if="actionKey < (actions.length - 1)">
                                <span class="dashicons dashicons-arrow-down"></span>
                            </button>
                        </p>
                        <button class="button button-primary" title="<?php _e('Remove action', 'wunderauto');?>"
                                v-on:click.prevent="removeAction(actionKey)">-</button>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
            </template>
            <tr>
                <td align="right" colspan="2">
                    <button v-on:click.prevent="addAction()"><?php _e('Add action', 'wunderauto')?></button>
                </td>
            </tr>
        </table>
        <br>
        <a href="<?php echo esc_url(wa_make_link('/docs/actions/', $utm))?>"
           target="_blank">
            <?php _e('Actions documentation', 'wunderauto');?>
        </a>
    </div>
</div>
