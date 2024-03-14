<div class="ff-opacity"></div>
<div class="ff-deactivate-popup">
    <div class="ff-deactivate-popup-opacity">
        <img src="<?php echo $this->context['plugin_url'] . $this->context['plugin_dir_name'] . '/' . 'assets/spinner.gif'; ?>"
             class="ff-img-loader">
    </div>
    <div class="ff-deactivate-popup-header">
        <?php _e("Please kindly let us know why you are deactivating. Your answer will help us to serve you better", $this->context['slug']); ?>:
    </div>
    <div class="ff-deactivate-popup-body">
        <?php foreach ($deactivate_reasons as $deactivate_reason_slug => $deactivate_reason) { ?>
            <div class="ff-reasons">
                <input type="radio" value="<?php echo $deactivate_reason["id"]; ?>"
                       id="ff-<?php echo $deactivate_reason["id"]; ?>" name="ff_reasons">
                <label for="ff-<?php echo $deactivate_reason["id"]; ?>"><?php echo $deactivate_reason["text"]; ?></label>
            </div>
        <?php } ?>
        <div class="ff-additional-details-wrap"></div>
    </div>
    <div class="ff-btns">
        <a href="<?php echo $deactivate_url; ?>" data-val="1"
           class="button button-primary button-close ff-deactivate"
           id="ff-deactivate"><?php _e("Deactivate", $this->context['slug']); ?></a>
        <a href="<?php echo $deactivate_url; ?>" data-val="2"
           class="button button-primary button-close ff-deactivate" id="ff-submit-and-deactivate"
           style="display:none;"><?php _e("Submit and deactivate", $this->context['slug']); ?></a>
        <a href="<?php echo admin_url('plugins.php'); ?>"
           class="button button-secondary ff-cancel"><?php _e("Cancel", $this->context['slug']); ?></a>
    </div>

</div>