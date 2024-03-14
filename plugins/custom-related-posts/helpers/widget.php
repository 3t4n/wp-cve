<?php

class CRP_Widget extends WP_Widget {

    public function __construct()
    {
        parent::__construct(
            'crp_widget',
            'Custom Related Posts',
            array(
                'description' => __( "List the related posts for the active post. Will only show for single pages of the selected post types.", 'custom-related-posts' )
            )
        );
    }


    public function widget( $args, $instance )
    {
        if( is_single() || is_page() ) {
            echo CustomRelatedPosts::get()->helper( 'output' )->output_list( get_the_ID(), $instance, $args );
        }
    }

    public function form( $instance )
    {
        // Parameters
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Related Posts', 'custom-related-posts' );
        $order_by = isset( $instance['order_by'] ) ? $instance['order_by'] : 'date';
        $order = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
        $none_text = isset( $instance['none_text'] ) ? $instance['none_text'] : __( 'None found', 'custom-related-posts' );

        // Options
        $order_by_options = array(
            'title' => __( 'Title', 'custom-related-posts' ),
            'date' => __( 'Date', 'custom-related-posts' ),
            'custom' => __( 'Custom', 'custom-related-posts' ),
            'rand' => __( 'Random', 'custom-related-posts' ),
        );
        $order_options = array(
            'ASC' => __( 'ascending', 'custom-related-posts' ),
            'DESC' => __( 'descending', 'custom-related-posts' ),
        );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'order_by' ); ?>"><?php _e( 'Order By', 'custom-related-posts' ); ?>:</label>
            <select name="<?php echo $this->get_field_name( 'order_by' ); ?>" id="<?php echo $this->get_field_id( 'order_by' ); ?>" class="widefat">
                <?php
                foreach ( $order_by_options as $value => $name ) {
                    $selected = $order_by == $value ? ' selected="selected"' : '';
                    echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>' . $name . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order', 'custom-related-posts' ); ?>:</label>
            <select name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>" class="widefat">
                <?php
                foreach ( $order_options as $value => $name ) {
                    $selected = $order == $value ? ' selected="selected"' : '';
                    echo '<option value="' . $value . '" id="' . $value . '"' . $selected . '>' . $name . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'none_text' ); ?>"><?php _e( 'No related posts text' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'none_text' ); ?>" name="<?php echo $this->get_field_name( 'none_text' ); ?>" type="text" value="<?php echo esc_attr( $none_text ); ?>">
            <?php _e( 'Leave blank to hide widget when there are no related posts.', 'custom-related-posts' ); ?>
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['order_by'] = ( !empty( $new_instance['order_by'] ) ) ? strip_tags( $new_instance['order_by'] ) : 'date';
        $instance['order'] = ( !empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : 'DESC';
        $instance['none_text'] = ( !empty( $new_instance['none_text'] ) ) ? strip_tags( $new_instance['none_text'] ) : '';

        return $instance;
    }
}

function crp_register_widget() {
    register_widget( 'CRP_Widget' );
}
add_action( 'widgets_init', 'crp_register_widget' );