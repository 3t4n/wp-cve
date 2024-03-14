<?php
$utm = "?utm_source=dashboard&utm_medium=workfloweditord&utm_campaign=installed_users";
?>

<div id="v-wunderauto-trigger" style="display: none" >
    <div class="wunderauto-trigger">
        <div class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Trigger', 'wunderauto')?>
            </div>

            <div class="tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <select v-model="trigger.trigger" class="wa-sel-triggere-name" @change="triggerChange()">
                    <option value=""><?php _e('[Select trigger...]', 'wunderauto')?></option>
                    <optgroup v-for="(group, groupKey) in atts.groups" :label="groupKey">
                        <option v-for="(item) in group"
                                :value="item.class" :selected="item.class == trigger.trigger"
                        >
                            {{item.title}}
                        </option>
                    </optgroup>
                </select>

                <div v-if="trigger.trigger && atts.triggers[trigger.trigger].supportsOnlyOnce"
                    class="tw-mt-2 md:tw-mt-1">
                        <input type="checkbox" v-model="trigger.value.onlyOnce">
                        <?php _e('Only run once per', 'wunderauto');?>
                        <span class="parameter-pill">
                    {{ atts.triggers[trigger.trigger].objects[0].id }}
                </div>

                <div v-if="trigger.trigger">
                    <p>
                        {{ atts.triggers[trigger.trigger].description }}
                    </p>
                </div>

                <hr>

                <div v-if="atts.triggers[trigger.trigger].objects.length > 0">
                    <br><strong><?php _e('Objects provided by this trigger', 'wundeauto');?>:</strong><br>
                    <div class="tw-flex tw-flex-col" v-for="(object, key) in atts.triggers[trigger.trigger].objects">
                        <div><span class="parameter-pill">{{object.id}}</span> : {{object.description}}</div>
                    </div>
                </div>

                <hr>

                <a href="<?php echo esc_url(wa_make_link('/docs/triggers/', $utm))?>"
                   target="_blank">
                    <?php _e('Triggers documentation', 'wunderauto');?>
                </a>
            </div>
        </div>
    </div>
</div>
