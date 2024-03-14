<?php 
if (isset($_GET["message"])){
    $message = sanitize_text_field($_GET["message"]);
    echo esc_attr($message);
}
?>