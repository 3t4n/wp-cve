<?php

if (!defined('ABSPATH')) {
    exit;
}
/*
 * product Countdown Template 
 */

$expire_date = $settings['expire_date'];
$expire_time = $settings['expire_time'];

?>

<div class="wooready_countdown_wrapper">
    <div class="wooready_countdown display:flex" data-date="<?php echo esc_attr($expire_date); ?>"
        data-time="<?php echo esc_attr($expire_time); ?>">
        <div class="day wooready_countdown_time"><span class="num"></span><span class="word"></span></div>
        <div class="hour wooready_countdown_time"><span class="num"></span><span class="word"></span></div>
        <div class="min wooready_countdown_time"><span class="num"></span><span class="word"></span></div>
        <div class="sec wooready_countdown_time"><span class="num"></span><span class="word"></span></div>
    </div>
</div>