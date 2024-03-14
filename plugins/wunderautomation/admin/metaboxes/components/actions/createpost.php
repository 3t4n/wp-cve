<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\CreatePost'">

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Post type', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.postType" class="tw-w-full">
                <option v-for="item in $root.shared.postTypes"
                        :value="item.value">{{ item.label }}</option>
            </select>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Post status', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.postStatus" class="tw-w-full">
                <option v-for="item in $root.shared.postStatuses"
                        :value="item.value">{{ item.label }}</option>
            </select>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Post title', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.title" class="tw-w-full"/><br>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Post name (slug)', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.name" class="tw-w-full"/><br>
            <br>
            <i>
                <?php _e(
                    'Needs to be unique. If needed, a numeric suffix is added ensure a unique slug',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Content', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <textarea v-model="step.action.value.content" rows="15" style="width: 100%;"></textarea>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Post owner / author', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.owner" class="tw-w-full"/><br>
            <br>
            <i>
                <?php _e('User name or id of post owner.', 'wunderauto');?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Post parent', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.parent" class="tw-w-full"/><br>
            <br>
            <i>
                <?php _e('Numeric id or slug of parent post', 'wunderauto');?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Comment status', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.commentStatus" class="tw-w-full">
                <option value="closed">
                    <?php _e('Closed', 'wunderauto')?>
                </option>
                <option value="open">
                    <?php _e('Open', 'wunderauto')?>
                </option>
            </select>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Ping status', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.pingStatus" class="tw-w-full">
                <option value="closed">
                    <?php _e('Closed', 'wunderauto')?>
                </option>
                <option value="open">
                    <?php _e('Open', 'wunderauto')?>
                </option>
            </select>
        </div>
    </div>
</div>
