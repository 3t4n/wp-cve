<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRGRGM_Map_Widget extends WP_Widget {

    public function __construct() {
        
		parent::__construct( 
            'wrgrgm_map_widget', 
            __( 'RGM Maps', 'wrg_rgm' ), 
            array(
                'classname' => 'wrgrgm_map_widget',
                'description' => __( 'Display Google Map', 'wrg_rgm' )
            ),
            array(
                'id_base' => 'wrgrgm_map_widget',
            )
        );
    }

    public function widget( $args, $instance ) {

        echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        if ( ! empty( $instance['mapId'] ) ) {
            echo WRGRGM_Map::render( $instance['mapId'] );
        }
        else {
            echo esc_html__( 'Please select your map.', 'wrg_rgm' );
        }
		
		echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['mapId'] = ( ! empty( $new_instance['mapId'] ) ) ? sanitize_text_field( $new_instance['mapId'] ) : '';

        return $instance;
    }

    public function form( $instance ) {

        $maps = WRGRGM_Map::get_maps();

        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $mapId = ! empty( $instance['mapId'] ) ? $instance['mapId'] : '';
		?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'wrg_rgm' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'wrg_map' ) ); ?>"><?php esc_attr_e( 'Select Map:', 'wrg_rgm' ); ?></label> 
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wrg_map' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mapId' ) ); ?>">
                <option value="">- Select Map -</option>
                <?php if ($maps): ?>
                    <?php foreach ( $maps as $map ): ?>
                        <option <?php echo $map->ID == $mapId ? 'selected="selected"' : '' ?> value="<?php echo esc_attr($map->ID) ?>"><?php echo esc_attr($map->post_title) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
		</p>
		<?php 
    }
}