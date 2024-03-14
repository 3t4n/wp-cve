<?php 
function bliss_flipbox_setting()
{
    ?>
        <div class="wrap">
        <h1>Flip Box Carousel Settings</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields("section");
                do_settings_sections("carousel-options");      
                submit_button(); 
            ?>          
        </form>
        <h2>Help</h2>
        <strong>ShortCode:<br> [flipcarousel category="Category Name" number="Number Flip boxes to show from lists"]</strong>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('.my-color-field').wpColorPicker();
            });
        </script>
    <?php
}
function bliss_flipbox_showitem()
{
    ?>
        <input type="number" name="flipnumber" value="<?php echo get_option('flipnumber'); ?>"  placeholder="4"/> 
    <?php
}
function bliss_flipbox_height()
{
    ?>
        <input type="number" name="flip_height" value="<?php echo get_option('flip_height'); ?>"  placeholder="200"/> 
    <?php
}
function bliss_flipbox_autoplay()
{
    ?>
        <input type="number" name="autoplay" value="<?php echo get_option('autoplay'); ?>"  placeholder="Set 3000 for 3 seconds"/> 
    <?php
}

function bliss_flipbox_nav()
{
    ?>
        <input type="checkbox" name="navigation" value="1" <?php checked(1, get_option('navigation'), true); ?> />
    <?php
}

function bliss_flipbox_pagination()
{
    ?>
        <input type="checkbox" name="pagination" value="1" <?php checked(1, get_option('pagination'), true); ?> /> 
    <?php
}
function bliss_flipbox_repeate_loop()
{
    ?>
        <input type="checkbox" name="repeate_loop" value="1" <?php checked(1, get_option('repeate_loop'), true); ?> /> 
    <?php
}
function bliss_flipbox_stop_hover()
{
    ?>
        <input type="checkbox" name="stop_hover" value="1" <?php checked(1, get_option('stop_hover'), true); ?> /> 
    <?php
}
function bliss_flipbox_speed()
{
    ?>
        <input type="number" name="flip_speed"  value="<?php echo get_option('flip_speed'); ?>" placeholder="300"/>
    <?php
}
function bliss_flipbox_flip_bg_color()
{
    ?>
        <input type="text" name="flip_bg_color"  value="<?php echo get_option('flip_bg_color'); ?>"  class="my-color-field"  data-default-color="#ffffff" />
    <?php
}
function bliss_flipbox_flip_border_color()
{
    ?>
        <input type="text" name="flip_border_color"  value="<?php echo get_option('flip_border_color'); ?>"  class="my-color-field"  data-default-color="#747474" />
    <?php
}
function bliss_flipbox_flip_border_width()
{
    ?>
        <input type="number" name="flip_border_width"  value="<?php echo get_option('flip_border_width'); ?>" placeholder="1" min="0" max="25"/><small>px</small>
    <?php
}
function bliss_flipbox_flip_border_radius()
{
    ?>
        <input type="number" name="flip_border_radius"  value="<?php echo get_option('flip_border_radius'); ?>" placeholder="1" min="0" max="25"/><small>px</small>
    <?php
}
function bliss_flipbox_title_color()
{
    ?>
        <input type="text" name="flip_title_color"  value="<?php echo get_option('flip_title_color'); ?>"  class="my-color-field"  data-default-color="#a0ce4e" />
    <?php
}
function bliss_flipbox_global_color()
{
    ?>
        <input type="checkbox" name="gobal_color" value="1" <?php checked(1, get_option('gobal_color'), true); ?> /> 
    <?php
}
function bliss_flipbox_button_bg_color()
{
    ?>
        <input type="text" name="flip_color"  value="<?php echo get_option('flip_color'); ?>"  class="my-color-field"  data-default-color="#a0ce4e" />
    <?php
}
function bliss_flipbox_txt_color()
{
    ?>
        <input type="text" name="flip_txt_color"  value="<?php echo get_option('flip_txt_color'); ?>"  class="my-color-field"  data-default-color="#ffffff" />
    <?php
}

function bliss_flipbox_fields()
{
    add_settings_section("section", "", null, "carousel-options");

    add_settings_field("flipnumber", "Number of FlipBoxes Dipaly Per Page", "bliss_flipbox_showitem", "carousel-options", "section");
    add_settings_field("flip_height", "Height of Flipbox", "bliss_flipbox_height", "carousel-options", "section");
    add_settings_field("autoplay", "Carousel Push Time", "bliss_flipbox_autoplay", "carousel-options", "section");
    add_settings_field("navigation", "Show Navigation Arrows", "bliss_flipbox_nav", "carousel-options", "section");
    add_settings_field("pagination", "Show Pagination", "bliss_flipbox_pagination", "carousel-options", "section");
    add_settings_field("repeate_loop", "Continue Loop?", "bliss_flipbox_repeate_loop", "carousel-options", "section");
    add_settings_field("stop_hover", "Stop on Hover?", "bliss_flipbox_stop_hover", "carousel-options", "section");
    add_settings_field("flip_speed", "Carousel Slide Speed", "bliss_flipbox_speed", "carousel-options", "section");
    add_settings_field("flip_bg_color", "FlipBox Background Color", "bliss_flipbox_flip_bg_color", "carousel-options", "section");
    add_settings_field("flip_border_color", "Flip Box Border Color", "bliss_flipbox_flip_border_color", "carousel-options", "section");
    add_settings_field("flip_border_width", "Flip Box Border Width", "bliss_flipbox_flip_border_width", "carousel-options", "section");
    add_settings_field("flip_border_radius", "Flip Box Border Radius", "bliss_flipbox_flip_border_radius", "carousel-options", "section");
    add_settings_field("flip_title_color", "Flip Box Title Color", "bliss_flipbox_title_color", "carousel-options", "section");
    add_settings_field("gobal_color", "Use This Button Style for All Buttons", "bliss_flipbox_global_color", "carousel-options", "section");
    add_settings_field("flip_color", "Carousel Backtside Button Background Color", "bliss_flipbox_button_bg_color", "carousel-options", "section");
    add_settings_field("flip_txt_color", "Carousel Backtside Button Text Color", "bliss_flipbox_txt_color", "carousel-options", "section");
    

    register_setting("section", "flipnumber");
    register_setting("section", "flip_height");
    register_setting("section", "autoplay");
    register_setting("section", "navigation");
    register_setting("section", "pagination");
    register_setting("section", "repeate_loop");
    register_setting("section", "stop_hover");
    register_setting("section", "flip_speed");
    register_setting("section", "flip_bg_color");
    register_setting("section", "flip_border_color");
    register_setting("section", "flip_border_width");
    register_setting("section", "flip_border_radius");
    register_setting("section", "gobal_color");
    register_setting("section", "flip_color");
    register_setting("section", "flip_txt_color");
    register_setting("section", "flip_title_color");
  
}

add_action("admin_init", "bliss_flipbox_fields");