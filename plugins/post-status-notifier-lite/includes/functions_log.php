<?php
function psn_log_debug($title, $message = null) {
    do_action('psn_log_debug', $title, $message);
}