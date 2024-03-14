<?php
$utm = '?utm_source=dashboard&utm_medium=workfloweditor&utm_campaign=installed_users';
?>

<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\Email'">
    <transition-group name="flip-list" tag="div">
        <div class="tw-flex tw-mt-2 td-flex-row">
            <div class="tw-w-28"><?php _e('To', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <div v-if="!step.action.value.useToRole">
                    <input :id="'to_' + stepKey" v-model="step.action.value.to" class="tw-w-full"/><br>
                    <i><?php _e('Separate multiple email addresses with comma (,)', 'wunderauto'); ?></i>
                </div>
                <div v-if="step.action.value.useToRole">
                    <multiselect v-model="step.action.value.toRole"
                                 mode="tags"
                                 :searchable="true"
                                 :createTag="true"
                                 :options="$root.shared.userRoles"
                                 class="tw-w-full">
                    </multiselect>
                </div>
                <input type="checkbox" v-model="step.action.value.useToRole">
                <?php _e('Send to users via WordPress role(s)', 'wunderauto'); ?>
            </div>
        </div>

        <a @click="step.action.value.showCC = !step.action.value.showCC" class="tw-mt-4">
            <span v-if="step.action.value.showCC" class="wa-fake-link">
                <?php _e('Hide Cc and Bcc fields', 'wunderauto') ?>
            </span>
            <span v-if="!step.action.value.showCC" class="wa-fake-link">
                <?php _e('Show Cc and Bcc fields', 'wunderauto') ?>
            </span>
        </a>

        <div class="tw-flex td-flex-row tw-mt-2" v-if="step.action.value.showCC">
            <div class="tw-w-28">
                <?php _e('Cc', 'wunderauto') ?>
            </div>
            <div class="tw-w-full">
                <div v-if="!step.action.value.useCcRole">
                    <input v-model="step.action.value.cc" class="tw-w-full"/><br>
                    <i><?php _e('Separate multiple email addresses with comma (,)', 'wunderauto'); ?></i>
                </div>
                <div v-if="step.action.value.useCcRole">
                    <multiselect v-model="step.action.value.ccRole"
                                 mode="tags"
                                 :searchable="true"
                                 :createTag="true"
                                 :options="$root.shared.userRoles"
                                 class="tw-w-full">
                    </multiselect>
                </div>
                <input type="checkbox" v-model="step.action.value.useCcRole">
                <?php _e('Send to users via WordPress role(s)', 'wunderauto'); ?>
            </div>
        </div>

        <div class="tw-flex td-flex-row tw-mt-2" v-if="step.action.value.showCC">
            <div class="tw-w-28">
                <?php _e('Bcc', 'wunderauto') ?>
            </div>
            <div class="tw-w-full">
                <div v-if="!step.action.value.useBccRole">
                    <input v-model="step.action.value.bcc" class="tw-w-full"/><br>
                    <i><?php _e('Separate multiple email addresses with comma (,)', 'wunderauto'); ?></i>
                </div>
                <div v-if="step.action.value.useBccRole">
                    <multiselect v-model="step.action.value.bccRole"
                                 mode="tags"
                                 :searchable="true"
                                 :createTag="true"
                                 :options="$root.shared.userRoles"
                                 class="tw-w-full">
                    </multiselect>
                </div>
                <input type="checkbox" v-model="step.action.value.useBccRole">
                <?php _e('Send to users via WordPress role(s)', 'wunderauto'); ?>
            </div>
        </div>

        <br>
        <a @click="step.action.value.showFrom = !step.action.value.showFrom" class="tw-mt-4">
        <span v-if="step.action.value.showFrom" class="wa-fake-link">
            <?php _e('Hide From and Reply-to fields', 'wunderauto') ?>
        </span>
            <span v-if="!step.action.value.showFrom" class="wa-fake-link">
            <?php _e('Show From and Reply-to fields', 'wunderauto') ?>
        </span>
        </a>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.showFrom">
            <div class="tw-w-28"><?php _e('From', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input v-model="step.action.value.from" class="tw-w-full"/><br>
                <?php _e('Optional, leave blank to use the WordPress default sender.', 'wunderauto');?>
                <br><a target="_blank"
                       href="<?php echo esc_url(wa_make_link('/docs/wa-custom-email-from-address/', $utm))?>">
                    <?php _e('Formatting email from address'); ?>
                </a>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row" v-if="step.action.value.showFrom">
            <div class="tw-w-28"><?php _e('Reply to', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input v-model="step.action.value.replyto" class="tw-w-full"/><br>
                <?php _e('Optional.', 'wunderauto');?>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row">
            <div class="tw-w-28"><?php _e('Subject', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <input v-model="step.action.value.subject" class="tw-w-full" :id="'subject_' + stepKey"/><br>
            </div>
        </div>

        <div class="tw-flex tw-mt-2 td-flex-row">
            <div class="tw-w-28"><?php _e('Content', 'wunderauto'); ?></div>
            <div class="tw-w-full">
                <textarea v-model="step.action.value.content"
                          rows="15"
                          style="width: 100%;"
                          :id="'content_' + stepKey">
                </textarea>
                <br>
                Convert line breaks to &lt;br&gt; :
                <input v-model="step.action.value.convertLineBreaks"
                       type="checkbox"/>
                <br>
                <i><?php _e(
                    'Note: In some configurations with plugins etc. WordPress always sends email as HTML. ' .
                    'To properly handle line breaks in those cases, use the above checkbox',
                    'wunderauto'
                );?>
                </i>
            </div>
        </div>
    </transition-group>
</div>
