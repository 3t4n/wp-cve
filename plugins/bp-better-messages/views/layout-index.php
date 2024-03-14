<?php
defined( 'ABSPATH' ) || exit;
$initialHeight = (int) apply_filters( 'bp_better_messages_max_height', Better_Messages()->settings['messagesHeight'] );
echo '<div class="bp-messages-wrap-main" style="height: ' . $initialHeight . 'px">' . Better_Messages()->functions->container_placeholder() . '</div>';
