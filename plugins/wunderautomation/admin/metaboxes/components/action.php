<div class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
    <div class="tw-w-32 tw-text-base">
        <?php esc_html_e('Action', 'wunderauto') ?>
    </div>

    <div class="wa-action tw-mt-2 tw-w-full md:tw-mt-0 tw-ml-0 md:tw-ml-2">
        <div class="tw-flex tw-flex-col md:tw-flex-row" :set="selectedAction=false">
            <div>
                <select v-model="step.action.action">
                    <option value="">[<?php _e('Select action', 'wunderauto') ?>...]</option>
                    <optgroup v-for="(group, groupKey) in actionGroups"
                              :label="groupKey">
                        <option v-for="item in group"
                                :value="item.class"
                                :selected="item.class == step.action.action">
                            {{ item.title }}
                        </option>
                    </optgroup>
                </select>
                <div v-if="step.action.action"
                     :set="selectedAction=actions[actionClass(stepKey)]"
                     class="tw-hidden"
                ></div>
                <br>
                <div class="tw-mt-1" v-if="step.action.action">
                    <span><i>{{ actions[step.action.action].description }}</i></span>
                    <br>
                    <span>Group: {{ actions[step.action.action].group }}</span>
                </div>
            </div>
            <div class="tw-mt-3 tw-pr-2 md:tw-mt-0 md:tw-ml-8 tw-w-full">
                <parameters :stepkey="stepKey"></parameters>
            </div>
        </div>
        <hr>
        <div class="wunderauto-action-fields tw-pr-2">
            <?php do_action('wunderauto_action_fields') ?>
        </div>
    </div>
</div>

<div v-if="selectedAction && selectedAction.emittedObjects.length > 0"
     class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
    <div class="tw-w-32 tw-text-base">
    </div>
    <div class="tw-mt-2 tw-w-full md:tw-mt-0 tw-ml-0 md:tw-ml-2">
        <hr/>
        <?php
        esc_html_e(
            'This action adds the following objects to the workflow:',
            'wunderauto'
        );
        ?>
        <br>
        <div v-for="object in selectedAction.emittedObjects"
             class="tw-flex tw-flex-col" >
            <div>
                <span class="object-pill">{{ object.id }}</span> :
                <span>{{ object.description }}</span>
            </div>
        </div>
        <br>
        <i>
            <?php
            esc_html_e(
                'These objects can be used in filters and to create parameters in steps following this one.',
                'wunderauto'
            );
            ?>
        </i>
    </div>
</div>