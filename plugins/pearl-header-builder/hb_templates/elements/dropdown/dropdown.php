<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php $style = (!empty($element['style'])) ? $element['style'] : 'style_1'; ?>

<div class="stm-dropdown stm-dropdown_<?php echo esc_attr($style); ?>">
    <?php
    if(empty($element)) return;

    if($element['value'] == 'wpml') {
        $element['dropdown'] = stm_hb_get_wpml_langs();
    }

    $element_id = stm_hb_random();

    if(!empty($element['dropdown'])) {
        $dropdown = stm_hb_get_dropdown($element['dropdown']);
    }

	if(!empty($element['textColor'])) {
		$element_hash = sanitize_title($element['$$hashKey']);
		$text_color = $element['textColor'];

		$style = "
		.stm-header__element.{$element_hash} .dropdown-toggle:after {
		    border-top-color: {$text_color} !important;
		}
		.stm-header__element.{$element_hash} .dropdown-toggle,
		.stm-header__element.{$element_hash} .stm-switcher__option {
            color: {$text_color} !important;
        }"; ?>
        <style type="text/css"><?php echo sanitize_text_field($style); ?></style>
	<?php }

    if(!empty($dropdown)): ?>
        <div class="dropdown">
            <?php if(!empty($dropdown['first'])): ?>
                <div class="dropdown-toggle stm_hb_mbc"
                     id="<?php echo sanitize_text_field($element_id); ?>"
                     data-toggle="dropdown"
                     aria-haspopup="true"
                     aria-expanded="true"
                     type="button">
                    <?php echo sanitize_text_field($dropdown['first']['label']); ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($dropdown['others'])): ?>
                <ul class="dropdown-list stm_hb_mbc"
                    aria-labelledby="<?php echo sanitize_text_field($element_id); ?>">
                    <?php foreach($dropdown['others'] as $key => $value): ?>
                        <li>
                            <a href="<?php echo esc_url($value['url']) ?>" class="stm-switcher__option">
                                <?php echo sanitize_text_field($value['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php
    $styles = 'partials/header/elements/dropdown/styles/style_1.php';

    $located = locate_template($styles);

    if ($located) {
        load_template($located);
    }

    ?>
</div>
