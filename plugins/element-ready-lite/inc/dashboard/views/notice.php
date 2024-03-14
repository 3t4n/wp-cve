<?php
$notice = $_COOKIE['quomodo-notice-element-ready'] ?? false;
if ('is_dismissed' === $notice) {
    return '';
}
?>
<style>
    .element-ready-admin-notice-remote img {
        max-width: 100%;
    }

    .element-ready-admin-notice-remote .notice-dismiss:before {
        color: red;
        font-size: 20px;
    }
</style>
<div class="notice is-dismissible element-ready-admin-notice-remote"
    style="border:0; background:transparent;padding-left:0">
    <div class="notice-content">
        <?php
        echo wp_kses_post(base64_decode($_data['msg']));
        ?>
    </div>
    <button type="button" class="notice-dismiss" onclick="setCookie('quomodo-notice-element-ready','is_dismissed',3)">
        <span class="screen-reader-text">
            <?php echo esc_html__('Dismiss this notice.', 'element-ready-lite'); ?>
        </span>
    </button>
</div>
<script>
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }
</script>