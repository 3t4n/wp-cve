<div id="fonts-com-ajax-notice" class="fonts-com-notice hide-if-js"></div>

<?php $errors = get_settings_errors(); if(!empty($errors)) { ?>
<div id="fonts-com-unset-project-notice" class="fonts-com-notice fonts-com-error"><?php $error = array_shift($errors); echo $error['message']; ?></div>
<?php } ?>


<img id="fonts-com-ajax-feedback" alt="" title="" class="ajax-feedback fonts-com-ajax-feedback hide-if-js" src="<?php esc_attr_e(admin_url('images/wpspin_light.gif')); ?>" style="visibility: visible;" />
<?php wp_nonce_field('fonts-com-action', 'fonts-com-action-nonce'); ?>