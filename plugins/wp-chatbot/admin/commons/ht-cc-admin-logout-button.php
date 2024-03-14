<?php

if (!defined('ABSPATH')) exit;
/**
 * @param $logout_path 
 */
?>

<div class="logout-mobilemonkey" style="position: relative;">
     <div class="disabled-notise hidden">
        <p class="disabled-notise__message">Log out, before switching to the manual option</p>
    </div>
    <a href="<?php echo $logout_path; ?>">Logout WP-Chatbot</a>   
</div>