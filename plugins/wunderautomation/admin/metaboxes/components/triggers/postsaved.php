<div v-if="trigger.trigger == '\\WunderAuto\\Types\\Triggers\\Post\\Saved'">

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Post meta', 'wunderauto'); ?></div>
        <div>
            <input type="checkbox" v-model="trigger.value.detectPostMeta" class="tw-w-full"/>
            <?php _e('Also detect changes to post meta data', 'wunderauto')?>
        </div>
    </div>
</div>
