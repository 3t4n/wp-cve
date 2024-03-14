<div class="ti-box ti-rate-us-box">
<div class="ti-box-header"><?php echo __("How's experience with Trustindex?", 'trustindex-plugin'); ?></div>
<p><?php echo __('Rate us by clicking on the stars', 'trustindex-plugin'); ?></p>
<div class="ti-quick-rating" data-nonce="<?php echo wp_create_nonce('ti-rate-us'); ?>">
<?php for ($i = 5; $i >= 1; $i--): ?><div class="ti-star-check" data-value="<?php echo $i; ?>"></div><?php endfor; ?>
</div>
</div>
<div class="ti-modal ti-rateus-modal" id="ti-rateus-modal-feedback">
<div class="ti-modal-dialog">
<div class="ti-modal-content">
<span class="ti-close-icon btn-modal-close"></span>
<div class="ti-modal-body">
<div class="ti-rating-textbox">
<div class="ti-quick-rating">
<?php for ($i = 5; $i >= 1; $i--): ?><div class="ti-star-check" data-value="<?php echo $i; ?>"></div><?php endfor; ?>
<div class="clear"></div>
</div>
</div>
<div class="ti-rateus-title"><?php echo __('Thanks for your feedback!<br />Let us know how we can improve.', 'trustindex-plugin') ;?></div>
<input type="text" class="ti-form-control" placeholder="<?php echo __('Contact e-mail', 'trustindex-plugin') ;?>" value="<?php echo $current_user->user_email; ?>" />
<textarea class="ti-form-control" placeholder="<?php echo __('Describe your experience', 'trustindex-plugin') ;?>"></textarea>
</div>
<div class="ti-modal-footer">
<a href="#" class="ti-btn ti-btn-default btn-modal-close"><?php echo __('Cancel', 'trustindex-plugin') ;?></a>
<a href="#" data-nonce="<?php echo wp_create_nonce('ti-rate-us'); ?>" class="ti-btn btn-rateus-support"><?php echo __('Contact our support', 'trustindex-plugin') ;?></a>
</div>
</div>
</div>
</div>