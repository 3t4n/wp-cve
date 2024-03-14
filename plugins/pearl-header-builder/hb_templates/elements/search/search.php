<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="stm-search stm-search_<?php echo esc_attr($element['value']); ?>">
    <div class="stm_widget_search">
        <?php get_search_form(); ?>
    </div>
</div>