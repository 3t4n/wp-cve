<button name="lws_cl_ignore_attachment" id="lws_cl_ignore_attachment" onclick="lws_cl_ignore_element(this)"
    type="button"
    value="<?php echo esc_attr(absint($item['ID']));?>">
    <span class="" name="update"><?php esc_html_e('Ignore', 'lws-cleaner');?></span>
    <span class="hidden" name="loading">
        <img width="15px" height="15px" class="lws_cl_image_button"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
        <span id="loading_1"><?php esc_html_e("Ignoring...", "lws-cleaner");?></span>
    </span>
</button>
</button>