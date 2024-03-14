<div class="misc-pub-section misc-pub-section-last"><span id="timestamp">
    <div id="pctags_app">
        
        <div class="pctag-segment" style="margin-top: 10px">
            <div class="pctag-row">
                <div class="pctag-column col-4">
                    <div class="pctag-row">
                        <div class="pctag-column col-8">
                        <span class="pctag-label"><?php 
                            echo  esc_html__( 'Add Signup Event?', "add-pinterest-conversion-tags" ) ;?>
                        </span>
                        </div>
                        <div class="pctag-column col-4">
                        <label class="pctag-switch">
                            <input type="checkbox" name="enable_signup" value="enable_signup" v-model="enable_signup" />
                            <span class="pctag-slider"></span>
                        </label>
                        </div>
                    </div>
                </div>

                <div class="pctag-column col-8" v-if="enable_signup">
                    <button @click.prevent="addEvent('signup')" class="pctag-btn pctag-add">Add Custom Event Data (optional)</button>

                    <div class="pctag-row" v-for="(event, index) in signup_events" :key="index">

                        <div class="pctag-column col-5">
                            <input type="text" name="signup-type[]" pattern="^[a-za-z0-9_]+$" v-model="event.type" class="pctag-input"
                                placeholder="type" />
                        </div>

                        <div class="pctag-column col-5">
                            <input type="text" name="signup-value[]" v-model="event.value" class="pctag-input"
                                placeholder="value" />
                        </div>

                        <div class="pctag-column col-2">
                            <button @click.prevent="removeEvent(index, 'signup')" class="pctag-btn pctag-remove">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p v-if="signup_events.length > 0" style="color: #999"><?= __('Note: "type" input field only accepts letters (az-AZ), numbers(0-9), and underscores(_).', 'add-pinterest-conversion-tags') ?></p>
                </div>
            </div>
        </div>

        <div class="pctag-segment" style="margin-top: 10px">
            <div class="pctag-row">
                <div class="pctag-column col-4">
                    <div class="pctag-row">
                        <div class="pctag-column col-8">
                        <span class="pctag-label"><?php 
                            echo  esc_html__( 'Add Watch Video Event?', "add-pinterest-conversion-tags" ) ;?>
                        </span>
                        </div>
                        <div class="pctag-column col-4">
                        <label class="pctag-switch">
                            <input type="checkbox" name="enable_watchVideo" value="enable_watchVideo" v-model="enable_watchVideo" />
                            <span class="pctag-slider"></span>
                        </label>
                        </div>
                    </div>
                </div>

                <div class="pctag-column col-8" v-if="enable_watchVideo">
                    <button @click.prevent="addEvent('watchVideo')" class="pctag-btn pctag-add">Add Custom Event Data (optional)</button>

                    <div class="pctag-row" v-for="(event, index) in watchVideo_events" :key="index">

                        <div class="pctag-column col-5">
                            <input type="text" name="watchVideo-type[]" pattern="^[a-za-z0-9_-]+$" v-model="event.type" class="pctag-input"
                                placeholder="type" />
                        </div>

                        <div class="pctag-column col-5">
                            <input type="text" name="watchVideo-value[]" v-model="event.value" class="pctag-input"
                                placeholder="value" />
                        </div>

                        <div class="pctag-column col-2">
                            <button @click.prevent="removeEvent(index, 'watchVideo')" class="pctag-btn pctag-remove">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p v-if="watchVideo_events.length > 0" style="color: #999"><?= __('Note: "type" input field accepts letters (az-AZ), numbers(0-9), and underscores(_).', 'add-pinterest-conversion-tags') ?></p>
                </div>
            </div>
        </div>

        <div class="pctag-segment" style="margin-top: 10px">
            <div class="pctag-row">
                <div class="pctag-column col-4">
                    <div class="pctag-row">
                        <div class="pctag-column col-8">
                        <span class="pctag-label"><?php 
                            echo  esc_html__( 'Add Lead Event?', "add-pinterest-conversion-tags" ) ;?>
                        </span>
                        </div>
                        <div class="pctag-column col-4">
                        <label class="pctag-switch">
                            <input type="checkbox" name="enable_lead" value="enable_lead" v-model="enable_lead" />
                            <span class="pctag-slider"></span>
                        </label>
                        </div>
                    </div>
                </div>

                <div class="pctag-column col-8" v-if="enable_lead">
                    <button @click.prevent="addEvent('lead')" class="pctag-btn pctag-add">Add Custom Event Data (optional)</button>

                    <div class="pctag-row" v-for="(event, index) in lead_events" :key="index">

                        <div class="pctag-column col-5">
                            <input type="text" name="lead-type[]" pattern="^[a-za-z0-9_-]+$" v-model="event.type" class="pctag-input"
                                placeholder="type" />
                        </div>

                        <div class="pctag-column col-5">
                            <input type="text" name="lead-value[]" v-model="event.value" class="pctag-input"
                                placeholder="value" />
                        </div>

                        <div class="pctag-column col-2">
                            <button @click.prevent="removeEvent(index, 'lead')" class="pctag-btn pctag-remove">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p v-if="lead_events.length > 0" style="color: #999"><?= __('Note: "type" input field accepts letters (az-AZ), numbers(0-9), and underscores(_).', 'add-pinterest-conversion-tags') ?></p>
                </div>
            </div>
        </div>

        <div class="pctag-segment" style="margin-top: 10px">
            <div class="pctag-row">
                <div class="pctag-column col-4">
                    <div class="pctag-row">
                        <div class="pctag-column col-8">
                        <span class="pctag-label"><?php 
                            echo  esc_html__( 'Add Custom Event?', "add-pinterest-conversion-tags" ) ;?>
                        </span>
                        </div>
                        <div class="pctag-column col-4">
                        <label class="pctag-switch">
                            <input type="checkbox" name="enable_custom" value="enable_custom" v-model="enable_custom" />
                            <span class="pctag-slider"></span>
                        </label>
                        </div>
                    </div>
                </div>

                <div class="pctag-column col-8" v-if="enable_custom">

                    <button @click.prevent="addEvent('custom')" class="pctag-btn pctag-add">Add Custom Event Data (optional)</button>

                    <div class="pctag-row" v-for="(event, index) in custom_events" :key="index">

                        <div class="pctag-column col-5">
                            <input type="text" name="custom-type[]" pattern="^[a-za-z0-9_-]+$" v-model="event.type" class="pctag-input"
                                placeholder="type" />
                        </div>

                        <div class="pctag-column col-5">
                            <input type="text" name="custom-value[]" v-model="event.value" class="pctag-input"
                                placeholder="value" />
                        </div>

                        <div class="pctag-column col-2">
                            <button @click.prevent="removeEvent(index, 'custom')" class="pctag-btn pctag-remove">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p v-if="custom_events.length > 0" style="color: #999"><?= __('Note: "type" input field accepts letters (az-AZ), numbers(0-9), and underscores(_).', 'add-pinterest-conversion-tags') ?></p>
                </div>
            </div>
        </div>

    </div>

<p><?php echo  esc_html__( '* Please read more details about Pinterest Events in FAQ', "add-pinterest-conversion-tags" ); ?>
</p>
</div>