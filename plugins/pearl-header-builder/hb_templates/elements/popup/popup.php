<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if (!empty($element['data'])):

    $btn_c = array(
		'btn btn_primary btn_solid stm_hb_mbc stm_hb_sbc_h'
    );

    $icon = $text = '';
    if (!empty($element['data']['icon'])) {
        $icon = $element['data']['icon'];
        $btn_c[] = 'stm-button_icon';
    }

    $text = (!empty($element['data']['text'])) ? $element['data']['text'] : '';

    $target = 'headerModal' . $element['value'];
    ?>

    <a href="#"
       data-toggle="modal"
       data-target="#<?php echo esc_attr($target); ?>"
       class="stm-header-popup__button <?php echo implode(' ', $btn_c); ?>">
        <i class="stm-button__icon <?php echo esc_attr($icon); ?>"></i>
        <span class="stm-button__text"><?php echo esc_html($text); ?></span>
    </a>


    <!--Popup itself-->
    <?php if (!empty($element['value'])): ?>
        <?php stm_hb_load_element('popup', array('page_id' => $element['value']), 'modal'); ?>
		<script type="text/javascript">
			(function($){
				$(document).ready(function(){
					var popup = $('#headerModal<?php echo esc_js($element['value']) ?>');
					popup.appendTo('body');
				})
			})(jQuery)
		</script>
    <?php endif;

endif; ?>
