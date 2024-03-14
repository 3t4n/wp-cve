<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\RestApiCall'">
    <transition-group name="flip-list" tag="div">
        <div class="tw-flex tw-mt-2 td-flex-row">
            <div class="tw-w-28"><?php _e('Method', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <select v-model="step.action.value.method" class="tw-w-full">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                </select>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.method === 'POST'">
            <div class="tw-w-28"><?php _e('Data format', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <select v-model="step.action.value.format" class="tw-w-full">
                    <option value="default"><?php _e('Default (x-www-form/urlencoded)', 'wunderauto');?></option>
                    <option value="json"><?php _e('JSON (application/json)', 'wunderauto');?></option>
                </select>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row">
            <div class="tw-w-28"><?php _e('Url', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input v-model="step.action.value.url" class="tw-w-full"/>
            </div>
        </div>

        <hr>
        <div class="tw-flex tw-mt-2 td-flex-row">
            <div class="tw-w-28"><?php _e('Basic authentication', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input type="checkbox" v-model="step.action.value.useBasicAuth" class="tw-w-full"/>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.useBasicAuth">
            <div class="tw-w-28"><?php _e('User', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input v-model="step.action.value.basicAuthUser" class="tw-w-full"/>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.useBasicAuth">
            <div class="tw-w-28"><?php _e('Password', 'wunderauto'); ?></div>
            <div class="tw-w-full tw-pr-2 tw-ml-2">
                <input v-model="step.action.value.basicAuthPass"
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
            <div class="tw-w-full">
                <input type="checkbox" v-model="step.action.value.useHeaderKey" class="tw-w-full"/>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.useHeaderKey">
            <div class="tw-w-28"><?php _e('Header', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input v-model="step.action.value.headerAPIKey" class="tw-w-full"/>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.useHeaderKey">
            <div class="tw-w-28"><?php _e('Secret', 'wunderauto'); ?></div>
            <div class="tw-w-full tw-pr-2 tw-ml-2">
                <input v-model="step.action.value.headerAPISecret"
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
            <div class="tw-w-full">
                <input type="checkbox" v-model="step.action.value.useHMACSignedPayload" class="tw-w-full"/>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.useHMACSignedPayload">
            <div class="tw-w-28"><?php _e('Header', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input v-model="step.action.value.HMACSignatureHeader" class="tw-w-full"/>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.useHMACSignedPayload">
            <div class="tw-w-28"><?php _e('Secret', 'wunderauto'); ?></div>
            <div class="tw-w-full tw-pr-2 tw-ml-2">
                <input v-model="step.action.value.HMACSignatureSecret"
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
            <div class="tw-w-28"><?php _e('Request body', 'wunderauto'); ?></div>
            <div class="tw-w-full">
            <textarea v-model="step.action.value.body"
                      rows="10" style="width: 100%;">
            </textarea>
            </div>
        </div>

    </transition-group>
</div>






