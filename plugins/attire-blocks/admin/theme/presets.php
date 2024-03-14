<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$current_theme = wp_get_theme(get_template());

if ($current_theme !== 'Attire') return;

add_action('customize_register', __NAMESPACE__ . '\atbs_customize_presets');
function atbs_customize_presets($wp_customize)
{
    class Attire_Preset_Choice_panel extends \WP_Customize_Control
    {
        public $type = 'preset';

        public function render_content()
        {
            ?>
            <div class="customize-control">
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif;
                if (is_array($this->description)) {
                    echo '<p>' . implode('</p><p>', $this->description) . '</p>';
                } else {
                    echo $this->description;
                } ?>
                <div class="card bg-dark text-white">
                    <img class="card-img" data-src="holder.js/100px270/#55595c:#373a3c/text:Card image" alt="100%x270" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%221576%22%20height%3D%22270%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%201576%20270%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_17b05bf3a54%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A79pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_17b05bf3a54%22%3E%3Crect%20width%3D%221576%22%20height%3D%22270%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22556.6328125%22%20y%3D%22170.10000000000002%22%3E1576x270%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true" style="height: 270px; width: 100%; display: block;">
                    <div class="card-img-overlay">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text">Last updated 3 mins ago</p>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    $wp_customize->add_section('attire_options', array(
        'title' => __('Color Scheme', 'attire-blocks'),
        'description' => '',
        'panel' => 'attire_color_panel',
        'priority' => 120,
    ));
}