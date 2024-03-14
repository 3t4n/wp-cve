<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!empty($element['data'])):
    $btn_c = array(
        'stm_btn',
    );

    $icon = $text = $url = '';
    if(!empty($element['data']['icon'])) {
        $icon = $element['data']['icon'];
        $btn_c[] = (empty($element['data']['icon_position'])) ? 'btn_icon-left' : $element['data']['icon_position'];
    }

    $style = (!empty($element['data']['style'])) ? $element['data']['style'] : 'btn_outline';
    $btn_c[] = $style;

    $url = (!empty($element['data']['url'])) ? $element['data']['url'] : '';

    $text = (!empty($element['data']['text'])) ? $element['data']['text'] : '';

    $icon_pos = (!empty($element['data']['icon_position'])) ? $element['data']['icon_position'] : 'btn_icon-left';


    if($style === 'btn_outline') {
        $btn_c[] = 'stm_hb_mbdc stm_hb_sbdc_h';
    } else {
        $btn_c[] = 'stm_hb_mbc stm_hb_sbc_h';
    }

    ?>

    <a href="<?php echo esc_url($url); ?>" class="<?php echo implode(' ', $btn_c); ?>">
        <?php if($icon_pos === 'btn_icon-left'): ?>
            <i class="btn__icon <?php echo esc_attr($icon); ?>"></i>
        <?php endif; ?>
        <span class="btn__text"><?php echo esc_html($text); ?></span>
        <?php if($icon_pos === 'btn_icon-right'): ?>
            <i class="btn__icon <?php echo esc_attr($icon); ?>"></i>
        <?php endif; ?>
    </a>

<?php endif; ?>