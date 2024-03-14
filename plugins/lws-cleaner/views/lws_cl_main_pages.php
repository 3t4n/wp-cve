<div class="lws_cl_tab">
    <?php foreach($plugin_lists as $key => $list) : ?>
    <div <?php if (in_array($key, $button_is_blue)) : ?>
        <?php $image = 'parametres'; ?>
        class="lws_cl_table_row lws_cl_blue_side"
        <?php else : ?>
        class="lws_cl_table_row <?php echo $list[4] == 0 ? esc_attr('lws_cl_green_side') :
                (in_array($key, $bottom_thumb_key) ? esc_attr('lws_cl_red_side') : esc_attr('lws_cl_orange_side'));?>"
        <?php endif ?>>
        <div <?php if (in_array($key, $button_is_blue)) : ?>
            <?php $image = 'parametres'; ?>
            class="lws_cl_table_left"
            <?php else : ?>
            <?php $image = $list[4] == 0 ? 'pouce' : (in_array($key, $bottom_thumb_key) ? 'pouce_bas' : 'warning');?>
            class="lws_cl_table_left"
            <?php endif ?>
            >

            <img class="lws_cl_images_left_table"
                id="lws_cl_left_<?php echo esc_html($key)?>"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/' . $image . '.svg')?>">
            <span>
                <?php printf($list[4] == 0 ? wp_kses($list[1], $arr): wp_kses($list[0], $arr), $list[4]); ?>
            </span>
            <span class="lws_cl_tooltip_content">
                <img class="lws_cl_images_left_table"
                    id="lws_cl_tooltip_<?php echo esc_attr($key);?>"
                    width="15px" height="15px" style="vertical-align:middle"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/infobulle.svg')?>">
                <span>
                    <?php echo esc_attr($list[5]);?>
                </span>
            </span>

        </div>
        <div class="lws_cl_table_right">
            <?php if ($key == 'deactivate_comments' || $key == 'hide_comments') : ?>
                <label class="switch">
                    <input type="checkbox"
                    id="<?php echo esc_attr('lws_cl_' . $key); ?>"
                    <?php echo get_option('lws_cl_' . $key) ? esc_attr('checked') : '' ?>>
                    <span class="slider round"></span>
                </label>
            <?php else : ?>
            <button <?php if (in_array($key, $button_is_blue)) : ?>
                <?php if ($list[4] == 0) : ?>
                class="lws_cl_button_blue noclick"
                <?php else : ?>
                class="lws_cl_button_blue"
                <?php endif ?>

                <?php else : ?>
                class="<?php echo $list[4] == "0" ? esc_attr("lws_cl_button_green lws_cl_bouton_no_pointer") : esc_attr("lws_cl_button_red")?>"
                <?php endif ?>
                id="<?php echo esc_attr('lws_cl_' . $key); ?>"
                onclick="" <?php echo $list[4] == "0" ? esc_attr('disabled') : '' ?>>
                <span class="" name="update">
                    <img class="lws_cl_images_button"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/' . ($list[4] == 0 ? esc_attr('securiser') : 'supprimer') . '.svg')?>">
                    <?php printf($list[4] == "0" ? wp_kses($list[3], $arr) : wp_kses($list[2], $arr), $list[4]) ?>
                </span>
                <span class="hidden" name="loading">
                    <img class="" width="15px" height="15px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">

                    <span id="loading_1"><?php esc_html_e("Deletion...", "lws-cleaner");?></span>
                </span>
                <span class="hidden" name="validated">
                    <img class="" width="18px" height="18px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                    <?php esc_html_e('Deleted', 'lws-cleaner'); ?>
                    &nbsp;
                </span>
            </button>
            <?php endif ?>
        </div>
    </div>
    <?php endforeach ?>
</div>

<script>
    <?php foreach ($plugin_lists as $key => $list) : ?>
    jQuery('#lws_cl_<?php echo esc_attr($key)?>').on('click',
        function() {
            <?php if ($key == "hide_comments" || $key == "deactivate_comments") : ?>
            var data = {
                action: "lws_cleaner_comments_ajax",
                data: "<?php echo esc_attr($key);?>",
                checked: this.checked,
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lws_cleaner_comments')); ?>',        
            };
            jQuery.post(ajaxurl, data);
            <?php else : ?>
            let button = this;
            let button_id = this.id;
            button.children[0].classList.add('hidden');
            button.children[2].classList.add('hidden');
            button.children[1].classList.remove('hidden');
            button.classList.remove('lws_cl_validated_button_tools');
            button.setAttribute('disabled', true);
            var data = {
                action: "lws_cleaner_<?php echo esc_attr($lws_cl_page_type);?>_ajax",
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lws_cleaner_' . esc_attr($lws_cl_page_type))); ?>',        
                data: "<?php echo esc_attr($key);?>",
            };
            jQuery.post(ajaxurl, data, function(response) {
                console.log(response);
                var button = jQuery('#' + button_id);
                button.children()[0].classList.add('hidden');
                button.children()[2].classList.remove('hidden');
                button.children()[1].classList.add('hidden');
                button.addClass('lws_cl_validated_button_tools');
            });
            <?php endif?>
        });
    <?php endforeach ?>
</script>