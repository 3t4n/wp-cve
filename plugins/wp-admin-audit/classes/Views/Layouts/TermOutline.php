<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_TermOutline implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-term-outline';
    public $term;
    public $termId;
    public $event;
    public $taxonomy;

    /**
     * @param WP_Term $term
     * @param object $event
     */
    public function __construct($term, $event){
        $this->term = $term;
        $this->event = $event;
        $this->termId = $event->object_id;

        $this->taxonomy = WADA_TermUtils::getTaxonomyNameBySensor($event->sensor_id);
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->term):
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            $parentName = sprintf(__('ID %d', 'wp-admin-audit'), $this->term->parent);
            if($this->term->parent > 0){
                $parentTerm = get_term($this->term->parent);
                if($parentTerm){
                    $parentName = $parentTerm->name.' ('.$parentName.')';
                }
            }
            $taxonomy = $this->taxonomy;
            switch($this->term->taxonomy){
                case 'post_tag':
                    $taxonomy = __('Tag', 'wp-admin-audit');
                    break;
                case 'category':
                    $taxonomy = __('Category', 'wp-admin-audit');
                    break;
                case 'menu':
                case 'nav_menu':
                    $taxonomy = __('Menu', 'wp-admin-audit');
                    break;
                default:
                    $taxonomy = $this->term->taxonomy;
            }
            ?>
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->termId); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Name', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->term->name); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Slug', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->term->slug); ?></td>
                </tr>
                <?php if($this->term->description): ?>
                <tr>
                    <td class="label"><?php _e('Description', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->term->description); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($this->term->parent > 0): ?>
                <tr>
                    <td class="label"><?php _e('Parent', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($parentName); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="label"><?php _e('Taxonomy', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($taxonomy); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        else: ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->termId); ?></td>
                </tr>
                <tr>
                    <td class="value" colspan="2">
                        <strong>
                            <?php echo sprintf(__('%s subject no longer existing', 'wp-admin-audit'), $this->taxonomy); ?>
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        endif;

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}