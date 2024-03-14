<?php

class MPHB_Divi_Accommodation_Type_Featured_Image_Module extends MPHB_Divi_Abstract_Accommodation_Module {

    public $slug = 'mphb-divi-accommodation-type-featured-image';

    public function init() {
        $this->name = esc_html__( 'HB Acc. Type Featured Image', 'mphb-divi' );
    }

    public function mphb_get_fields() {
        return array(
            'link_to_post' => array(
                'label' => esc_html__('Link to post', 'mphb-divi'),
                'type' => 'yes_no_button',
                'options' => array(
                    'on' => esc_html__('Yes', 'mphb-divi'),
                    'off' => esc_html__('No', 'mphb-divi'),
                ),
                'default' => 'off',
                'computed_affects' => array(
                    '__html',
                ),
            ),
            'image_size' => array(
                'label' => esc_html__('Image size', 'mphb-divi'),
                'type' => 'select',
                'options' => $this->get_image_sizes_select(),
                'default' => 'large',
                'computed_affects' => array(
                    '__html',
                ),
            ),
        );
    }

    public function mphb_render_depends_on() {
        return array( 'link_to_post', 'image_size' );
    }

    public static function get_html( $attrs = array() ) {

        $id = isset( $attrs['accommodation_id'] ) && $attrs['accommodation_id'] !== 'current' ? (int) $attrs['accommodation_id'] : self::get_current_post_id();
        $link_to_post = isset( $attrs['link_to_post'] ) ? $attrs['link_to_post'] === 'on' : false;
        $size = isset( $attrs['image_size'] ) ? $attrs['image_size'] : 'large';

        if ( 'mphb_room_type' != get_post_type( $id ) ) {
            return '';
        }

        ob_start();
        ?>
        <div class="mphb-single-room-type-thumbnail">
            <?php if ($link_to_post) : ?>
                <a href="<?php echo esc_url(get_permalink($id)); ?>">
            <?php endif; ?>
                <?php echo mphb_tmpl_get_room_type_image($id, $size); ?>
            <?php if ($link_to_post) : ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

}

new MPHB_Divi_Accommodation_Type_Featured_Image_Module();
