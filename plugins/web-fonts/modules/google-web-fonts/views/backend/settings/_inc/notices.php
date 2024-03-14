<div id="google-web-fonts-ajax-notice" class="google-web-fonts-notice hide-if-js"></div>

<?php $errors = get_settings_errors(); if(!empty($errors)) { ?>
<div id="google-web-fonts-unset-project-notice" class="google-web-fonts-notice google-web-fonts-error"><?php $error = array_shift($errors); echo $error['message']; ?></div>
<?php } ?>


<img id="google-web-fonts-ajax-feedback" alt="" title="" class="ajax-feedback google-web-fonts-ajax-feedback hide-if-js" src="<?php esc_attr_e(admin_url('images/wpspin_light.gif')); ?>" style="visibility: visible;" />
<?php wp_nonce_field('google-web-fonts-action', 'google-web-fonts-action-nonce'); ?>