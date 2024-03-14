<?php
$wp_roles = wp_roles();

$allRoles      = $wp_roles->roles;
$editableRoles = apply_filters('editable_roles', $allRoles);
?>

<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\AddObjects'">

    <div class="tw-flex tw-mt-2 td-flex-row" >
        <div class="tw-w-28"><?php _e('Objects', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <div class="tw-flex tw-flex-row">
                <div class="tw-w-28"><strong><?php _e('Object type', 'wunderauto');?></strong></div>
                <div class="tw-w-40"><strong><?php _e('Name', 'wunderauto');?></strong></div>
                <div class="tw-flex-grow"><strong><?php _e('Identify by expression', 'wunderauto');?></strong></div>
                <div class="tw-w-6">&nbsp;</div>
            </div>
        </div>
    </div>

    <div v-for="(object, index) in step.action.value.objectRows"
         :key="'param-'+ index"
         class="tw-flex tw-mt-2 td-flex-row ">
        <div class="tw-w-28"></div>
        <div class="tw-w-full">
            <div class="tw-flex tw-flex-row">
                <div class="tw-w-28">
                    <select v-model="object.type" class="webhook-object" @change="updateProvidedObjects()">
                        <template v-for="(object, index) in $root.shared.objectTypes">
                            <option v-if="object.transfer" :value="object.type">
                                {{ object.type }}
                            </option>
                        </template>
                    </select>
                </div>
                <div class="tw-pr-1 tw-w-40">
                    <input @change="updateProvidedObjects()" v-model="object.name" class="tw-w-full"/>
                </div>
                <div class="tw-flex-grow tw-pr-3">
                    <input v-if="!object.multiLine" v-model="object.expression" class="tw-w-full"/>
                    <textarea v-if="object.multiLine" v-model="object.expression" class="tw-w-full" rows="4">
                    </textarea>
                    <input type="checkbox" v-model="object.multiLine"/><?php _e('Multi line', 'wunderauto') ?>
                </div>
                <div class="tw-w-6">
                    <button class="wunder-small-button"
                            @click.prevent="removeActionValueObjectRow(stepKey, index)">
                        -
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28">&nbsp;</div>
        <div class="tw-w-full">
            <p v-if="step.action.value.objectRows && step.action.value.objectRows.length > 0">
                <i><?php _e('Toggle checkbox to switch between single/multi line input', 'wunderauto');?></i>
            </p>
            <button class="button button-primary"
                    @click.prevent="addActionValueObjectRow(stepKey, {type: '', expression:'', name:''})">
                <?php _e('Add object', 'wunderauto');?>
            </button>
        </div>
    </div>

</div>



