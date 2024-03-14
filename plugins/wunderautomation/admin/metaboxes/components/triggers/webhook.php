<div v-if="trigger.trigger == '\\WunderAuto\\Types\\Triggers\\Webhook\\Webhook'">

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Identifier', 'wunderauto'); ?></div>
        <div>
            <input v-model="trigger.value.code" class="tw-w-full"/>
            <br>
            <i><?php _e('Allowed characters: a-z, 0-9, - and _', 'wunderauto')?></i><br>
            <br>
            URL: {{ triggers[trigger.trigger].urlBase }}{{ trigger.value.code }}
        </div>
    </div>

    <hr>
    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Basic authentication', 'wunderauto'); ?></div>
        <div>
            <input type="checkbox" v-model="trigger.value.useBasicAuth" class="tw-w-full"/>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row" v-if="trigger.value.useBasicAuth">
        <div class="tw-w-28"><?php _e('User', 'wunderauto'); ?></div>
        <div>
            <input v-model="trigger.value.basicAuthUser" class="tw-w-full"/>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row" v-if="trigger.value.useBasicAuth">
        <div class="tw-w-28"><?php _e('Password', 'wunderauto'); ?></div>
        <div class="tw-pr-2">
            <input v-model="trigger.value.basicAuthPass"
                   class="tw-w-full"
                   :type="pwdField ? 'password' : 'text'"/>
        </div>
        <div>
                <span class="dashicons dashicons-visibility wa-fake-link"
                      aria-hidden="true"
                      @click="pwdField = !pwdField"
                ></span>
        </div>
    </div>
    <hr>
    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('HTTP key in header', 'wunderauto'); ?></div>
        <div>
            <input type="checkbox" v-model="trigger.value.useHeaderKey" class="tw-w-full"/>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row" v-if="trigger.value.useHeaderKey">
        <div class="tw-w-28"><?php _e('Header', 'wunderauto'); ?></div>
        <div>
            <input v-model="trigger.value.headerAPIKey" class="tw-w-full"/>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row" v-if="trigger.value.useHeaderKey">
        <div class="tw-w-28"><?php _e('Secret', 'wunderauto'); ?></div>
        <div class="tw-pr-2">
            <input v-model="trigger.value.headerAPISecret"
                   class="tw-w-full"
                   :type="pwdField ? 'password' : 'text'"/>
        </div>
        <div>
                <span class="dashicons dashicons-visibility wa-fake-link"
                      aria-hidden="true"
                      @click="pwdField = !pwdField"
                ></span>
        </div>
    </div>
    <hr>
    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('HMAC signed payload', 'wunderauto'); ?></div>
        <div>
            <input type="checkbox" v-model="trigger.value.useHMACSignedPayload" class="tw-w-full"/>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row" v-if="trigger.value.useHMACSignedPayload">
        <div class="tw-w-28"><?php _e('Header', 'wunderauto'); ?></div>
        <div>
            <input v-model="trigger.value.HMACSignatureHeader" class="tw-w-full"/>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row" v-if="trigger.value.useHMACSignedPayload">
        <div class="tw-w-28"><?php _e('Secret', 'wunderauto'); ?></div>
        <div class="tw-pr-2">
            <input v-model="trigger.value.HMACSignatureSecret"
                   class="tw-w-full"
                   :type="pwdField ? 'password' : 'text'"/>
        </div>
        <div>
                <span class="dashicons dashicons-visibility wa-fake-link"
                      aria-hidden="true"
                      @click="pwdField = !pwdField"
                ></span>
        </div>
    </div>
    <hr>

    <div class="tw-flex tw-mt-2 td-flex-row" >
        <div class="tw-w-28"><?php _e('Objects', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <div class="tw-flex tw-flex-row">
                <div class="tw-w-28"><strong><?php _e('Object type', 'wunderauto');?></strong></div>
                <div class="tw-flex-grow tw--mr-4"><strong><?php _e('Parameter', 'wunderauto');?></strong></div>
                <div class="tw-flex-grow"><strong><?php _e('Name', 'wunderauto');?></strong></div>
                <div class="tw-w-16"><strong><?php _e('Required', 'wunderauto');?></strong></div>
                <div class="tw-w-6">&nbsp;</div>
            </div>

        </div>
    </div>

    <div v-for="(object, index) in trigger.value.objects"
         :key="'param-'+ index"
         class="tw-flex tw-mt-2 td-flex-row ">
        <div class="tw-w-28"></div>
        <div class="tw-w-full">
            <div class="tw-flex tw-flex-row">
                <div class="tw-w-28">
                    <select v-model="object.type" class="webhook-object">
                        <option v-for="(object, key) in triggers[trigger.trigger].objectTypes" :value="key">
                            {{ object }}
                        </option>
                    </select>
                </div>
                <div class="tw-flex-grow tw-pr-3">
                    <input v-model="object.parameter" class="tw-w-full"/>
                </div>
                <div class="tw-flex-grow tw-pr-1">
                    <input v-model="object.name" class="tw-w-full"/>
                </div>
                <div class="tw-w-16">
                    <input v-model="object.required" type="checkbox" class="tw-w-full"/>
                </div>
                <div class="tw-w-6">
                    <button class="wunder-small-button"
                            @click.prevent="removeTriggerValueObject(index)">
                        -
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28">&nbsp;</div>
        <div class="tw-w-full">
            <button class="button button-primary"
                    @click.prevent="addTriggerValueObject({type: '', required: true, parameter:'', name:''})">
                <?php _e('Add object', 'wunderauto');?>
            </button>
        </div>
    </div>
</div>

