<button name="lws_cl_delete_attachment" id="lws_cl_delete_attachment" onclick="lws_cl_delete_element(this)"
    type="button"
    value="<?php echo esc_attr(absint($item['ID']));?>">
    <span class="" name="update">
        <img width="15px" height="15px" class="lws_cl_image_button"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer.svg')?>">
        <?php esc_html_e('Delete');?>
    </span>
    <span class="hidden" name="loading">
        <img width="15px" height="15px" class="lws_cl_image_button"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
        <span id="loading_1"><?php esc_html_e("Deletion...", "lws-cleaner");?></span>
    </span>
</button>