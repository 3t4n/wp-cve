<div class="lws_tk_tab_line lws_tk_tab_border lws_tk_tab_border_blue">
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/parametres.svg')?>">
        <span class="lws_tk_basic"><?php echo esc_html_e('Disconnect the session of every user excepted yourself.', 'lws-tools')?></span>
    </div>

    <fieldset>
        <div class="lws_tk_tab_button">
            <button class="lws_tk_update_button_blue" name="lws_tk_disconnect_all" id="lws_tk_disconnect_all"
                type="button">
                <span class="" name="update">
                    <img class="lws_tk_image" width="19px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/deconnexion_blanc.svg')?>">
                    <?php esc_html_e('Disconnect everyone', 'lws-tools') ?>
                </span>
                <span class="hidden" name="loading">
                    <img class="lws_tk_image" width="15px" height="15px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                    <span id="loading_1"><?php esc_html_e("Disconnexion...", "lws-tools");?></span>
                </span>
                <span class="hidden" name="validated">
                    <img class="lws_tk_image" width="18px" height="18px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                    <?php esc_html_e('Disconnected', 'lws-tools'); ?>
                </span>
            </button>
        </div>
    </fieldset>
</div>

<!-- Revision -->
<div
    class="lws_tk_tab_line lws_tk_tab_border <?php echo $revisions_amount == 0 ? esc_attr("lws_tk_tab_border_green") : esc_attr("lws_tk_tab_border_orange");?>">
    <?php if ($revisions_amount > 0) : ?>
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/warning.svg')?>">
        <span class="lws_tk_basic">
            <?php printf(wp_kses(__('<strong>%d revision(s).</strong>', 'lws-tools'), $arr), $revisions_amount); ?>
            <?php esc_html_e('Remove every revisions older than', 'lws-tools'); ?>
            <input style="width:120px" id="lws_tk_select_days_revision" type="number" min="0" value="1" />
            <?php esc_html_e(' day(s).', 'lws-tools'); ?>
        </span>
    </div>

    <div class="lws_tk_tab_button">
        <button class="lws_tk_update_button" name="lws_tk_delete_revisions" id="lws_tk_delete_revisions">
            <span class="" name="update">
                <img class="lws_tk_image" width="19px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer.svg')?>">
                <?php printf(esc_html__('Delete revisions (%d)', 'lws-tools'), $revisions_amount); ?>
            </span>
            <span class="hidden" name="loading">
                <img class="lws_tk_image" width="15px" height="15px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                <span id="loading_1"><?php esc_html_e("Deletion...", "lws-tools");?></span>
            </span>
            <span class="hidden" name="validated">
                <img class="lws_tk_image" width="18px" height="18px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                <?php esc_html_e('Deleted', 'lws-tools'); ?>
            </span>
        </button>
    </div>
    <?php else : ?>
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
        <span class="lws_tk_uptodate"><?php echo wp_kses(__('<strong>No revisions</strong> to delete', 'lws-tools'), $arr) ?></span>
    </div>

    <div class="lws_tk_tab_button">
        <button class="lws_tk_green_update_button" name="">
            <span class="" name="">
                <img class="lws_tk_image" width="17px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                <?php esc_html_e('No revisions', 'lws-tools') ?>
            </span>
        </button>
    </div>
    <?php endif ?>
</div>

<!-- Comments Trashed -->
<div
    class="lws_tk_tab_line lws_tk_tab_border <?php echo $trashed_comments == 0 ? esc_attr("lws_tk_tab_border_green") : esc_attr("lws_tk_tab_border_red");?>">
    <?php if ($trashed_comments > 0) : ?>
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce_bas.svg')?>">
        <span class="lws_tk_basic">
            <?php printf(wp_kses(__('<strong>%d comment(s)</strong> in the trash.', 'lws-tools'), $arr), $trashed_comments); ?>
        </span>
    </div>

    <div class="lws_tk_tab_button">
        <button class="lws_tk_update_button" name="lws_tk_delete_trashed_comments" id="lws_tk_delete_trashed_comments">
            <span class="" name="update">
                <img class="lws_tk_image" width="19px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer.svg')?>">
                <?php printf(esc_html__('Delete comments in the trash (%d)', 'lws-tools'), $trashed_comments); ?>
            </span>
            <span class="hidden" name="loading">
                <img class="lws_tk_image" width="15px" height="15px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                <span id="loading_1"><?php esc_html_e("Deletion...", "lws-tools");?></span>
            </span>
            <span class="hidden" name="validated">
                <img class="lws_tk_image" width="18px" height="18px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                <?php esc_html_e('Deleted', 'lws-tools'); ?>
            </span>
        </button>
    </div>
    <?php else : ?>
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
        <span class="lws_tk_uptodate"><?php echo wp_kses(__('<strong>No comments</strong> in the trash', 'lws-tools'), $arr) ?></span>
    </div>

    <div class="lws_tk_tab_button">
        <button class="lws_tk_green_update_button" name="">
            <span class="" name="">
                <img class="lws_tk_image" width="17px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                <?php esc_html_e('No trashed comments', 'lws-tools') ?>
            </span>
        </button>
    </div>
    <?php endif ?>
</div>

<!-- Comments Spam -->
<div
    class="lws_tk_tab_line lws_tk_tab_border <?php echo $spam_comments == 0 ? esc_attr("lws_tk_tab_border_green") : esc_attr("lws_tk_tab_border_red");?>">
    <?php if ($spam_comments > 0) : ?>
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce_bas.svg')?>">
        <span class="lws_tk_basic">
            <?php printf(wp_kses(__('<strong>%d spam comment(s)</strong>.', 'lws-tools'), $arr), $spam_comments); ?>
        </span>
    </div>

    <div class="lws_tk_tab_button">
        <button class="lws_tk_update_button" name="lws_tk_delete_spam_comments" id="lws_tk_delete_spam_comments">
            <span class="" name="update">
                <img class="lws_tk_image" width="19px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer.svg')?>">
                <?php printf(esc_html__('Delete spam comments (%d)', 'lws-tools'), $spam_comments); ?>
            </span>
            <span class="hidden" name="loading">
                <img class="lws_tk_image" width="15px" height="15px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                <span id="loading_1"><?php esc_html_e("Deletion...", "lws-tools");?></span>
            </span>
            <span class="hidden" name="validated">
                <img class="lws_tk_image" width="18px" height="18px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                <?php esc_html_e('Deleted', 'lws-tools'); ?>
            </span>
        </button>
    </div>
    <?php else : ?>
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
        <span class="lws_tk_uptodate"><?php echo wp_kses(__('<strong>No spam comments</strong>', 'lws-tools'), $arr) ?></span>
    </div>

    <div class="lws_tk_tab_button">
        <button class="lws_tk_green_update_button" name="">
            <span class="" name="">
                <img class="lws_tk_image" width="17px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                <?php esc_html_e('No spam comments', 'lws-tools') ?>
            </span>
        </button>
    </div>
    <?php endif ?>
</div>

<!-- Transients -->
<div class="lws_tk_tab_line lws_tk_tab_border lws_tk_tab_border_blue">
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/parametres.svg')?>">
        <span class="lws_tk_basic"><?php echo esc_html_e('Remove old temporary data (transients) from cache', 'lws-tools')?></span>
    </div>

    <fieldset>
        <div class="lws_tk_tab_button">
            <button class="lws_tk_update_button_blue" name="lws_tk_delete_transients" id="lws_tk_delete_transients"
                type="button">
                <span class="" name="update">
                    <img class="lws_tk_image" width="19px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer.svg')?>">
                    <?php esc_html_e('Remove cache', 'lws-tools') ?>
                </span>
                <span class="hidden" name="loading">
                    <img class="lws_tk_image" width="15px" height="15px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                    <span id="loading_1"><?php esc_html_e("Removing...", "lws-tools");?></span>
                </span>
                <span class="hidden" name="validated">
                    <img class="lws_tk_image" width="18px" height="18px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                    <?php esc_html_e('Removed', 'lws-tools'); ?>
                </span>
            </button>
        </div>
    </fieldset>
</div>

<!-- Reset -->
<div class="lws_tk_tab_line lws_tk_tab_border lws_tk_tab_border_blue">
    <div class="lws_tk_tab">
        <img class="lws_tk_image" width="25px" height="22px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/parametres.svg')?>">
        <span class="lws_tk_basic"><?php echo esc_html_e('Reset the plugin configuration', 'lws-tools')?></span>
    </div>

    <form method="POST">
        <?php wp_nonce_field( 'lws_tk_reset_plugin', 'nonce_security_reset_nonce' ); ?>
        <div class="lws_tk_tab_button">
            <button class="lws_tk_update_button" name="lws_tk_reset_plugin" id="lws_tk_reset_plugin" type="submit">
                <span class="" name="update">
                    <img class="lws_tk_image" width="19px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj.svg')?>">
                    <?php esc_html_e('Reset', 'lws-tools') ?>
                </span>
                <span class="hidden" name="loading">
                    <img class="lws_tk_image" width="15px" height="15px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                    <span id="loading_1"><?php esc_html_e("Resetting...", "lws-tools");?></span>
                </span>
                <span class="hidden" name="validated">
                    <img class="lws_tk_image" width="18px" height="18px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                    <?php esc_html_e('Reset', 'lws-tools'); ?>
                </span>
            </button>
        </div>
    </form>
</div>

<div class="lws_tk_tab_line">
    <div class="lws_tk_tab">
        <label for='lws_tk_keep_changes'>
            <input type="checkbox" class="lws_tk_checkboxes" id="lws_tk_keep_changes" name="lws_tk_keep_changes" <?php echo get_option('lws_tk_keep_data_on_delete') ? esc_attr('checked') : '';?>>
            <?php esc_html_e('Keep configuration even after deleting the plugin', 'lws-tools'); ?>
        </label>
    </div>
</div>

<script>
    jQuery('#lws_tk_disconnect_all').on('click', function() {
        let button = this;
        let button_id = this.id;
        console.log(this.children);
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button_tools');
        button.setAttribute('disabled', true);
        var data = {
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('disconnect_all_and_everyone')); ?>',
            action: "lwstools_disconnectall",
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            button.addClass('lws_tk_validated_button_tools');
            button.prop('disabled', false);
        });
    });

    jQuery('#lws_tk_delete_revisions').on('click', function() {
        let button = this;
        let button_id = this.id;
        console.log(this.children);
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button_tools');
        button.setAttribute('disabled', true);
        var days = jQuery('#lws_tk_select_days_revision').val();
        var data = {
            lws_tk_days_revisions: days,
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('delete_all_revisions')); ?>',
            action: "lwstools_delete_revisions",
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            button.addClass('lws_tk_validated_button_tools');
            button.prop('disabled', false);
        });
    });


    jQuery('#lws_tk_reset_plugin').on('click', function() {
        let button = this;
        button.children[0].classList.add('hidden');
        button.children[1].classList.remove('hidden');
    });

    jQuery('#lws_tk_delete_trashed_comments').on('click', function() {
        let button = this;
        let button_id = this.id;
        console.log(this.children);
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button_tools');
        button.setAttribute('disabled', true);
        var data = {
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('delete_all_trash_comments')); ?>',
            action: "lwstools_delete_trash_comments",
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            button.addClass('lws_tk_validated_button_tools');
            button.prop('disabled', false);
        });
    });

    jQuery('#lws_tk_delete_spam_comments').on('click', function() {
        let button = this;
        let button_id = this.id;
        console.log(this.children);
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button_tools');
        button.setAttribute('disabled', true);
        var data = {
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('delete_all_spam_comms')); ?>',
            action: "lwstools_delete_spam_comments",
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            button.addClass('lws_tk_validated_button_tools');
            button.prop('disabled', false);
        });
    });

    jQuery('#lws_tk_delete_transients').on('click', function() {
        let button = this;
        let button_id = this.id;
        console.log(this.children);
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button_tools');
        button.setAttribute('disabled', true);
        var data = {
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('delete_all_transients')); ?>',
            action: "lwstools_delete_transients",
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            button.addClass('lws_tk_validated_button_tools');
            button.prop('disabled', false);
        });
    });

    jQuery('#lws_tk_keep_changes').on('change', function() {
        var data = {
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('keep_on_delete_change')); ?>',
            action: "lwstools_keep_changes",
            state: this.checked,
        };
        jQuery.post(ajaxurl, data);

    });
</script>