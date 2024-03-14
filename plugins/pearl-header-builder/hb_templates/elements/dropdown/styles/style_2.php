<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
if(empty($element)) return;

if($element['value'] == 'wpml') {
    $element['dropdown'] = pearl_get_wpml_langs();
}

$element_id = stm_hb_random();

if(!empty($element['dropdown'])) {
    $dropdown = stm_hb_get_dropdown($element['dropdown']);
}

if(!empty($dropdown)): ?>
    <div class="dropdown">
        <?php if(!empty($dropdown['first'])): ?>
            <div class="dropdown-toggle"
                 id="<?php echo sanitize_text_field($element_id); ?>"
                 data-toggle="dropdown"
                 aria-haspopup="true"
                 aria-expanded="true"
                 type="button">
                <?php echo sanitize_text_field($dropdown['first']['label']); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($dropdown['others'])): ?>
            <ul class="dropdown-list stm_hb_tbc"
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