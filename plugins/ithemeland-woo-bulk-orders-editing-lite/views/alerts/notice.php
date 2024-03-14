<?php
if (!is_array($flush_message) || !isset($flush_message['message'])) {
    return false;
}
?>

<div class="wobel-alert <?php echo (isset($flush_message['type'])) ? 'wobel-alert-' . esc_attr($flush_message['type']) : 'wobel-alert-default' ?>">
    <span><?php echo esc_html($flush_message['message']); ?></span>
</div>