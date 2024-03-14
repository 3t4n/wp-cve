<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!empty($element['data'])):
    $btn_c = array(
        'btn_extended',
        'stm_hb_mbc',
        'stm_hb_sbc_h'
    );

	if(!empty($element['data']['textColor'])) {
		$element_hash = sanitize_title($element['$$hashKey']);
		$text_color = $element['data']['textColor'];

		$style = "
		.stm-header__element.{$element_hash} .stm-button__icon,
		.stm-header__element.{$element_hash} .stm-button__text,
        .stm-header__element.{$element_hash} .stm-button__description {
            color: {$text_color} !important;
        }"; ?>
        <style type="text/css"><?php echo sanitize_text_field($style); ?></style>
	<?php }

    $icon = $text = $url = '';
    if(!empty($element['data']['icon'])) {
        $icon = $element['data']['icon'];
        $btn_c[] = 'stm-button_icon';
    }

    $url = (!empty($element['data']['url'])) ? $element['data']['url'] : '';

    $text = (!empty($element['data']['text'])) ? $element['data']['text'] : '';

    $description = (!empty($element['data']['description'])) ? $element['data']['description'] : '';

    ?>

    <a href="<?php echo esc_url($url); ?>" class="<?php echo implode(' ', $btn_c); ?>">
        <i class="stm-button__icon <?php echo esc_attr($icon); ?>"></i>
        <div class="stm-button__text"><?php echo esc_html($text); ?></div>
        <div class="stm-button__description"><?php echo esc_html($description); ?></div>
    </a>

<?php endif; ?>