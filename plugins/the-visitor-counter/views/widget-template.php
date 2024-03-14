<?php

global $post;

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (is_front_page() == true) {
    $id = 0;
} else {
    $id = $post->ID;
}

$obj         = WTVCP_Visitors::WTVCP_get_settings();
$onlineUsers = WTVCP_Visitors::WTVCP_get_users_online();

$border_radius = $obj->border_radius;
$width         = $obj->width;
if($width < 90) {
    $width = 90;
}

?>

    <div class="tvcp-container" style="background: <?= $obj->background; ?>; max-width: <?= $width . 'px'; ?>; font-family:arial; border-radius: <?= $border_radius . 'px'; ?>">
        <?php if ($obj->show_title === 'true'): ?>
            <h2 class="tvcp-title" style="color: <?= $obj->color; ?>;">
                <?= $obj->title ?>
            </h2>
        <?php endif; ?>

        <h3 class="tvcp-size-of" style="color: <?= $obj->color; ?>; text-align: center !important;">
            <?= $onlineUsers ?>
        </h3>
    </div>

<?php
