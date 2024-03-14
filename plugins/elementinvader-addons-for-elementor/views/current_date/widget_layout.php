
<div class="widget-eli eli_currentdate" id="eli_<?php echo esc_html($this->get_id_int());?>">
    <div class="eli_container">
        <span class='prefix'><?php echo wp_kses_post($settings['prefix']);?></span><span class="current_date"><?php echo esc_html(date($settings['currentdate_format'], $baseTimestamp));?></span><span class='suffix'><?php echo wp_kses_post($settings['suffix']);?></span>
    </div>
</div>