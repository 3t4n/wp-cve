<?php
defined( 'ABSPATH' ) || exit;
$initialHeight = (int) apply_filters( 'bp_better_messages_max_height', Better_Messages()->settings['messagesHeight'] );
?><div style="height:<?php echo $initialHeight; ?>px" class="bp-messages-wrap-group bp-messages-group-thread <?php Better_Messages()->functions->messages_classes($thread_id, 'group'); ?>" data-thread-id="<?php esc_attr_e($thread_id); ?>">
<?php echo Better_Messages()->functions->container_placeholder(); ?>
</div><?php
