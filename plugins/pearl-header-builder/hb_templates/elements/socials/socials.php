<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
$socials = stm_hb_get_option('header_socials', false, $element['pearl_header_builder']);

$style = 'round';
$size = 'icon_16px';
if (!empty($element['data'])) {

    /*STYLE*/
    if (!empty($element['data']['style'])) {
        $style = $element['data']['style'];
    }

    /*SIZE*/
    if(!empty($element['data']['size'])) {
        $size = $element['data']['size'];
    }
}

$classes = array(
    'stm-socials__icon',
    $size,
    'stm-socials__icon_'. $style
);

if (!empty($socials)): ?>
    <?php if($style == 'icon_hidden') : ?>
        <div class="stm-socials-hidden">
            <div class="stm-socials-btn stc_h"><?php esc_html_e('Follow', 'pearl-header-builder'); ?></div>
            <div class="stm-socials">
                <?php foreach ($socials as $item):
                    if (!empty($item['social']) and !empty($item['url'])): ?>
                        <a href="<?php echo esc_attr($item['url']); ?>"
                           class="<?php echo esc_attr(implode(' ', $classes)); ?>"
                           target="_blank"
                           title="<?php esc_html_e('Social item', 'pearl-header-builder'); ?>">
                            <i class="<?php echo esc_attr($item['social']); ?>"></i>
                        </a>
                    <?php endif;
                endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="stm-socials">
            <?php foreach ($socials as $item):
                if (!empty($item['social']) and !empty($item['url'])): ?>
                    <a href="<?php echo esc_attr($item['url']); ?>"
                       class="<?php echo esc_attr(implode(' ', $classes)); ?>"
                       target="_blank"
                       title="<?php esc_html_e('Social item', 'pearl-header-builder'); ?>">
                        <i class="<?php echo esc_attr($item['social']); ?>"></i>
                    </a>
                <?php endif;
            endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>