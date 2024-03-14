<?php
$mrtext_font_direction = get_option("mrtext_font_direction");
    $mrtext_font_scroll_delay = get_option("mrtext_font_scroll_delay", "85");
    $mrtext_bg_color_option = get_option("mrtext_bg_color_option", "#000000");
    $mrtext_color_option = get_option("mrtext_color_option", "#ffffff");
    $mrtext_hover_color_option = get_option("mrtext_hover_color_option", "#ffffff");
    $mrtext_text_field_1 = get_option("mrtext_text_field_1");
    $mrtext_text_field_1_link = get_option("mrtext_text_field_1_link");
    $mrtext_text_field_2 = get_option("mrtext_text_field_2");
    $mrtext_text_field_2_link = get_option("mrtext_text_field_2_link");
    $mrtext_text_field_3 = get_option("mrtext_text_field_3");
    $mrtext_text_field_3_link = get_option("mrtext_text_field_3_link");
    $mrtext_text_field_4 = get_option("mrtext_text_field_4");
    $mrtext_text_field_4_link = get_option("mrtext_text_field_4_link");
    $mrtext_text_field_5 = get_option("mrtext_text_field_5");
    $mrtext_text_field_5_link = get_option("mrtext_text_field_5_link");
    $mrtext_font_size = get_option("mrtext_font_size", "16px");
    $mrtext_font_weight = get_option("mrtext_font_weight", "500");

    echo '<style> 
.runtext-container {
    background:' .
    esc_html($mrtext_bg_color_option) .
        ';
    border: 1px solid ' .
        esc_html($mrtext_bg_color_option) .
        ';
    }
.runtext-container .holder a{ 
    color: ' .
        esc_html($mrtext_color_option) .
        ';
    font-size: ' .
        esc_html($mrtext_font_size) .
        ';
    font-weight: ' .
        esc_html($mrtext_font_weight) .
        ';
}
.text-container a:before {
    background-color: ' .
        esc_html($mrtext_color_option) .
        ';
}
.runtext-container .holder a:hover{
	color:' .
        esc_html($mrtext_hover_color_option) .
        ';
}
.text-container a:hover::before {
    background-color: ' .
        esc_html($mrtext_hover_color_option) .
        ';
}
</style>';
    ?>
<div class="runtext-container">
    <div class="main-runtext">
        <marquee direction="<?php echo esc_attr($mrtext_font_direction); ?>" scrolldelay="<?php echo esc_attr($mrtext_font_scroll_delay); ?>" onmouseover="this.stop();"
            onmouseout="this.start();">

            <div class="holder">
                <?php
                if (!empty($mrtext_text_field_1)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_url($mrtext_text_field_1_link) .
                        '" >' .
                        esc_html($mrtext_text_field_1) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_2)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_url($mrtext_text_field_2_link) .
                        '" >' .
                        esc_html($mrtext_text_field_2) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_3)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_url($mrtext_text_field_3_link) .
                        '" >' .
                        esc_html($mrtext_text_field_3) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_4)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_url($mrtext_text_field_4_link) .
                        '" >' .
                        esc_html($mrtext_text_field_4) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_5)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_url($mrtext_text_field_5_link) .
                        '" >' .
                        esc_html($mrtext_text_field_5) .
                        '</a>
    </div>';
                }
                ?>
            </div>
        </marquee>
    </div>
</div>
