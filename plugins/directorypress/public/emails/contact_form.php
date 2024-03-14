<?php _e('Listing title:', 'DIRECTORYPRESS'); ?> <?php echo esc_html($listing_title); ?>

<?php _e('Listing URL:', 'DIRECTORYPRESS'); ?> <?php echo esc_url($listing_url); ?>

<?php _e('Name:', 'DIRECTORYPRESS'); ?> <?php echo esc_html($name); ?>

<?php _e('Email:', 'DIRECTORYPRESS'); ?> <?php echo esc_html($email); ?>

<?php _e('Message:', 'DIRECTORYPRESS'); ?>


<?php echo wp_kses_post($message); ?>