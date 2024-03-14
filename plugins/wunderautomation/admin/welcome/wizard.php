<?php

// phpcs:ignoreFile
$options = get_option('wunderauto-general');

$data = json_encode((object)[
    'enableWebhooks' => isset($options['enable_webhook_trigger']) ?
        (bool)$options['enable_webhook_trigger'] :
        false,
    'webhookSlug'    => isset($options['webhookslug']) ?
        $options['webhookslug'] :
        'wa-hook',
    'signUp'         => false,
    'email'          => '',
    'autoshow'       => (bool)get_transient('wunderauto_welcome_wizard_autoshow')
]);

delete_transient('wunderauto_welcome_wizard_autoshow');

?>

<div id="welcome-wizard-app">
    <div>
        <p style="padding-left: 10px;">
            <button id="start-wizard" class="button button-primary" @click="toggleModal()">
                <?php _e('Run introduction wizard', 'wunderauto');?>
            </button>
        </p>
        <!--  The Modal -->
        <boardal v-if="modal.isOpen" :has-mask="modal.hasMask" :can-click-mask="modal.canClickMask"
                 :has-x="modal.hasX" @toggle="toggleModal">
            <article v-cloak>
                <section>
                    <img class="topimage"
                         src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/welcome_top.png')?>">
                    <div class="wrap">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
                            <div style="text-align: center">
                                <img
                                    width="100%"
                                    src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/welcome_left.png')?>"
                                >
                            </div>
                            <div>
                                <p>
                                    Thank you for installing WunderAutomation!
                                </p>
                                <p>
                                    To help you get started, we'd like to take you through a few steps that will
                                    help you find you way around and get the most out of WunderAutomation.
                                </p>
                                <p>
                                    &nbsp;
                                </p>
                                <p>
                                    <button class="btn-primary next" @click="toggleModal">
                                        Naah, skip this. I know what I'm doing
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <img class="topimage" src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/navigation_top.png')?>">
                    <div class="wrap">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
                            <div style="text-align: center">
                                <img
                                    src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/navigation_left.png')?>"
                                >
                            </div>
                            <div>
                                <p>
                                    We've added a new menu called Automation on the left hand side of your
                                    WordPress admin dashboard. It's pretty far down the list, we don't want to get
                                    in your way more than we need.
                                </p>
                                <p>
                                    This is where you access the list of workflows, categories, settings etc. Everything
                                    coded to the standard WordPress way of handling things. If you dealt with posts or
                                    pages before you'll feel right at home.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <img class="topimage" src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/edit_workflow_top.png')?>">
                    <div class="wrap">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
                            <div style="text-align: center">
                                <img
                                    _width="100%"
                                    src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/edit_workflow_left.png')?>"
                                >
                            </div>
                            <div>
                                <p>
                                    A workflow is managed just like any other WordPress post or page. Click 'Add Workflow'
                                    to start creating a new one. Or click on an existing workflow in the list to edit it
                                    in the workflow editor.
                                </p>
                                <p>
                                    The workflow itself consists of a trigger and a bunch of steps. A step is either
                                    a filter that that decides if the execution should continue or not, or an action
                                    that can do things, like sending an email or updating a custom field.
                                </p>
                                <p>
                                    Saving / updating and assigning a category works just as you're used to from
                                    working with WordPress posts and pages.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <img class="topimage" src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/documentation_top.png')?>">
                    <div class="wrap">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
                            <div style="text-align: center">
                                <img
                                    _width="100%"
                                    src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/documentation_left.png')?>"
                                >
                            </div>
                            <div>
                                <p>
                                    We're constantly working to extend and improve our
                                    <a target="_blank" href="https://www.wundermatics.com/docs-category/wunderautomation/">documentation.</a>
                                    We've added links in the workflow editor to help you easily get the correct section.
                                </p>
                                <p>
                                    We're also publishing tutorials and other articles on our blog that we think are
                                    useful for getting the most out of WunderAutomation.
                                    <a target="_blank" href="https://www.wundermatics.com/blog/">Visit our blog.</a>.
                                </p>
                                <p>
                                    Our primary support channel is via our support portal at
                                    <a target="_blank" href="https://www.wundermatics.com/support/">
                                        https://www.wundermatics.com/support/
                                    </a>
                                </p>
                                <p>
                                    We suggest you bookmark these pages, they may come in handy.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <img class="topimage" src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/webhooks_top.png')?>">
                    <div class="wrap">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
                            <div style="text-align: center">
                                <p style="text-align: left">
                                    WunderAutomation supports both inbound and outbound webhooks. Inbound webhooks enable
                                    receiving triggers from external services like Zapier or Trello. For security reasons
                                    incoming webhooks are disabled by default. If you're planning to use external services
                                    to trigger workflows, you need to enable them.
                                </p>
                                <img
                                    height="120px"
                                    src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/webhooks_left.png')?>"
                                >
                            </div>
                            <div>
                                <h3>Enable webhooks?</h3>
                                <p>
                                    <input type="checkbox" v-model="state.enableWebhooks"> Yes! Enable inbound webhooks <br>
                                    <br>
                                    <div v-if="state.enableWebhooks">
                                    Webhook URL base <input v-model="state.webhookSlug" name="state.webhookSlug"><br>
                                    </div>

                                </p>
                                <div style="position: relative">
                                    <p style="position: fixed;bottom: 0;">
                                        <i>Go to Automation >> Settings >> General to change this later</i>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <img class="topimage" src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/connect_top.png')?>">
                    <div class="wrap">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
                            <div style="text-align: center">
                                <img
                                    src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/connect_left.png')?>"
                                >
                            </div>
                            <div>
                                <p>
                                    <b>Newsletter</b><br>
                                    Once a month or so we send out a newsletter to our premium users with news,
                                    promotions and other relevant updates from us. Join the mailing list today
                                    to get a 15% discount on any premium add-on or other service from us.
                                </p>
                                <p>
                                    Also, as a subscriber you'll get priority in the support queue.
                                </p>
                                <p>
                                    <label for="state.signUp">
                                        <input type="checkbox" v-model="state.signUp" name="state.signUp">
                                        Yes! sign me up!
                                    </label>
                                    <br>
                                    Email: <input size="40" v-model="state.email" name="state.email"><br>
                                </p>
                                <p>

                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <img class="topimage" src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/finish_top.png')?>">
                    <div class="wrap">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
                            <div style="text-align: center">
                                <img
                                        src="<?php esc_attr_e(WUNDERAUTO_URLBASE . '/admin/assets/images/wizard/finish_left.png')?>"
                                >
                            </div>
                            <div>
                                <p>
                                    <b>That's it!</b><br>
                                </p>
                                <p>
                                    You're ready to get started with your first workflow. Don't hesitate
                                    to reach out to us if you need help.
                                </p>
                                <p>
                                    <a target="_blank" href="https://www.wundermatics.com/support/">
                                        https://www.wundermatics.com/support/
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

            </article>
            <footer>
                <div class="forward-actions">
                    <button class="secondary cancel prev"
                            id="wizard-back"
                            :disabled="isFirstStep"
                            v-show="!isFirstStep"
                            @click="skip(-1)">&lt; Back</button>
                    <button class="primary next"
                            id="wizard-next"
                            :disabled="isLastStep"
                            v-show="!isLastStep"
                            @click="skip(1)">Next &gt;</button>
                    <button class="accent save"
                            id="wizard-finish"
                            :disabled="!isLastStep"
                            v-show="isLastStep"
                            @click="finish">
                        Finish
                    </button>
                </div>
                <div class="step-dots" v-if="hasDots">
                    <div class="step-dot" v-for="n in max" :class="{active: n == step}" @click="goToStep(n)"></div>
                </div>
                <div class="back-actions">
                    <button class="primary next" :disabled="isLastStep" v-show="!isLastStep" @click="toggleModal">
                        Exit
                    </button>
                </div>
            </footer>
        </boardal>
    </div>
</div>

<script type="text/x-template" id="wizard-boardal">
    <transition name="boardal">
        <div class="boardal" style="z-index: 1;">
            <div class="boardal__wrapper">
                <slot></slot>
            </div>
        </div>
    </transition>
</script>

<div id="welcome-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>

