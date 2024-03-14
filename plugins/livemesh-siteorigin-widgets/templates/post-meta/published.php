<?php

if (empty($format))
$format = get_option('date_format');

?>

<span class="published">
    <abbr title="<?php echo sprintf(get_the_time(esc_html__('l, F, Y, g:i a', 'livemesh-so-widgets'))); ?>"><?php echo sprintf(get_the_time($format)); ?></abbr>
</span>
