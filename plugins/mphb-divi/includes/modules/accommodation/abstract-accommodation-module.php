<?php

abstract class MPHB_Divi_Abstract_Accommodation_Module extends ET_Builder_Module {

    public $vb_support = 'on';
    private $accommodations_select = array();

    abstract static function get_html( $attrs );

    public function mphb_get_fields() {
        return array();
    }

    public function mphb_render_depends_on() {
        return array();
    }

    public function get_fields() {

        return array_merge(
            array(
                'accommodation_id' => array(
                    'label' => esc_html__( 'Accommodation Type', 'mphb-divi' ),
                    'type' => 'select',
                    'default' => 'current',
                    'options' => $this->get_accommodations_to_select(),
                    'computed_affects' => array(
                        '__html',
                    ),
                ),
                'name' => array(
                    'type' => 'hidden',
                    'default' => $this->name,
                ),
                '__html' => array(
                    'type' => 'computed',
                    'computed_callback' => array( static::class, 'get_html' ),
                    'computed_depends_on' => array_merge(
                        array( 'accommodation_id' ),
                        $this->mphb_render_depends_on()
                    )
                )

            ),
            $this->mphb_get_fields()
        );

    }

    public function render( $attrs, $content, $render_slug ) {
        return static::get_html( $attrs );
    }

    protected function get_accommodations_to_select() {

        if ( count( $this->accommodations_select ) > 0 ) {
            return $this->accommodations_select;
        }

        $this->accommodations_select['current'] = esc_html__( 'Use current', 'mphb-divi' );

        $query = new \WP_Query(array(
            'post_type' => 'mphb_room_type',
            'posts_per_page' => -1
        ));

        if (!$query->have_posts()) {
            return;
        }

        while ($query->have_posts()) {
            $query->the_post();

            $this->accommodations_select[get_the_ID()] = get_the_title() . ' #' . get_the_ID();
        }

        wp_reset_postdata();

        return $this->accommodations_select;

    }

    protected function get_image_sizes_select() {

        global $_wp_additional_image_sizes;

        $intermediate_image_sizes = get_intermediate_image_sizes();

        $image_sizes = array();
        foreach ($intermediate_image_sizes as $size) {
            if (isset($_wp_additional_image_sizes[$size])) {
                $image_sizes[$size] = array(
                    'width'  => $_wp_additional_image_sizes[$size]['width'],
                    'height' => $_wp_additional_image_sizes[$size]['height']
                );
            } else {
                $image_sizes[$size] = array(
                    'width'  => intval(get_option("{$size}_size_w")),
                    'height' => intval(get_option("{$size}_size_h"))
                );
            }
        }

        $sizes_arr = [];
        foreach ($image_sizes as $key => $value) {
            $sizes_arr[$key] = ucwords(strtolower(preg_replace('/[-_]/', ' ', $key))) . " - {$value['width']} x {$value['height']}";
        }

        $sizes_arr['full'] = __('Full Size', 'mphb-divi');

        return $sizes_arr;
	}

    protected static function get_accommodations_attributes_to_select() {

        global $mphbAttributes;

        $attributes_select = array(
            'adults' =>  __('Adults', 'mphb-divi'),
            'children' =>  __('Children', 'mphb-divi'),
            'capacity' =>  __('Capacity', 'mphb-divi'),
            'amenities' =>  __('Amenities', 'mphb-divi'),
            'view' =>  __('View', 'mphb-divi'),
            'size' =>  __('Size', 'mphb-divi'),
            'bed-types' =>  __('Bed Types', 'mphb-divi'),
            'categories' =>  __('Categories', 'mphb-divi'),
        );

        foreach ($mphbAttributes as $customAttribute) {
            $attributes_select[$customAttribute['attributeName']] = $customAttribute['title'];
        }

        return $attributes_select;
    }

}