<div v-if="trigger.trigger == '\\WunderAuto\\Types\\Triggers\\Custom'">

    <p>
        <b><?php _e('Objects', 'wunderauto');?></b><br>
        <i>
            <?php
            _e(
                'Define the main object type that this workflow will work with. Note that when this workflow is ' .
                'started, the main object MUST be provided/defined by the caller. The secondary objects (if any) are ' .
                'fetched and added to the context automatically at runtime. The caller may define (force) the ' .
                'objects manually to override the below default definitions.',
                'wunderauto'
            );
            ?>
        </i>
        <br>

        <select v-model="trigger.value.mainObject"
                @change="handleEvent('customTriggerUpdate', trigger, atts.triggers[trigger.trigger])">
            <option v-for="(object, key) in atts.triggers[trigger.trigger].requiredObjectTypes" :value="key">
                {{ object.name }}
            </option>
        </select>


        <p v-if="trigger.value.mainObject">
            <strong><?php _e('Objects provided by this trigger', 'wundeauto');?>:</strong>
            <br>
            <span class="parameter-pill">{{ trigger.value.mainObject }}</span> : (Main object)
                {{ atts.triggers[trigger.trigger].requiredObjectTypes[trigger.value.mainObject].description }}
            <br>

            <span v-for="(object, key) in
                    atts.triggers[trigger.trigger].requiredObjectTypes[trigger.value.mainObject].secondary">
                <span class="parameter-pill">{{ key }}</span> : (Defaults to) {{ object.description }}
                    <br>
                </span>
            </span>
        </p>

    </p>
</div>

