<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if(!empty($element['value'])):
    $fwn = (!empty($element['data']['fwn'])) ? $element['data']['fwn'] : 'fwn'; ?>
    <div class="stm-shortcode <?php echo esc_attr($fwn); ?>">
        <?php echo do_shortcode($element['value']); ?>
    </div>
<?php endif; ?>