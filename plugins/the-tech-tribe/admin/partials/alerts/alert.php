<div class="alert alert-<?php esc_html_e($args['alert']); ?> <?php esc_html_e($args['close']) ?? 'alert-dismissible fade show';?>" role="alert">

<h4 class="alert-heading ttt-show-alert-error-code"><?php echo esc_attr($args['msg-header'] != '') ? esc_attr($args['msg-header']) : esc_attr($args['code']); ?></h4>
  <?php esc_html_e($args['msg']); ?>
  <div class="msg-content">
    <?php esc_html_e($args['msg-content']); ?>
  </div>
  
  <?php if($args['close']) : ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  <?php endif; ?>
</div>