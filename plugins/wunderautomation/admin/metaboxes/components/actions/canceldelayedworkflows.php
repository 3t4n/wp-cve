<?php
$wunderAuto = wa_wa();

$triggers  = $triggers = $wunderAuto->getObjects('trigger');
$workflows = $wunderAuto->getWorkflows();

?>

<div v-if="action.action == '\\WunderAuto\\Types\\Actions\\CancelDelayedWorkflows'">

    <table class="wp-list-table" style="width: 100%;">

        <tr>
            <td>
                <?php _e('Workflow', 'wunderauto'); ?>
            </td>
            <td>
                <select v-model="action.value.workflowId">
                    <?php foreach ($workflows as $workflow) :
                        $class = $workflow->getTriggerClass();
                        if (!isset($triggers[$class])) {
                            continue;
                        }?>
                        <option value="<?php echo (int)$workflow->getPostId();?>">
                            <?php esc_html_e($workflow->getName());?>
                        </option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <?php _e('Cancel', 'wunderauto'); ?><br>
            </td>
            <td>
                <select v-model="action.value.scope">
                    <option value="all">All queued instances of this workflow</option>
                    <option value="hasTheSame">Queued instances that have the same...</option>
                </select>
                <template v-if="findOne(action.value.scope, ['hasTheSame'])">
                    <br>
                    <select v-model="action.value.objectType">
                        <option v-if="objectsEnabled('post', actionKey)" value="post">
                            <?php _e('Post', 'wunderauto')?>
                        </option>
                        <option v-if="objectsEnabled('user', actionKey)" value="post">
                            <?php _e('User', 'wunderauto')?>
                        </option>
                        <option v-if="objectsEnabled('comment', actionKey)" value="comment">
                            <?php _e('Comment', 'wunderauto')?>
                        </option>
                        <option v-if="objectsEnabled('order', actionKey)" value="order">
                            <?php _e('WooCommerce order', 'wunderauto')?>
                        </option>
                    </select>
                    as is provided to this workflow
                </template>
                <br>
            </td>
        </tr>



    </table>
</div>



