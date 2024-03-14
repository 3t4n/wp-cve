<?php
/**
 * Adds Ultimate_Subscribe_Form_Widget widget.
 */
class Ultimate_Subscribe_Form_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        
        $args = array( 
            'description' => __( 'Ultimate Subscribe Form', 'ultimate-subscribe' ), 
        );
        parent::__construct('ultimate_subscribe_form_widget', __( 'Ultimate Subscribe Form', 'ultimate-subscribe' ), $args);
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $form_id            = absint($instance['form_id']);
        if($form_id):
        ?>
        <div class="uswidget">
            <?php echo do_shortcode('[ultimate_subscribe_from id="'.$form_id.'"]'); ?>
        </div>
        <?php
        else:
            echo _e('Please Select a Form in Widget', 'ultimate-subscribe');
        endif;
        echo $args['after_widget'];

    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title   =  isset( $instance['title'] ) ? $instance['title'] : __( 'Subscribe', 'ultimate-subscribe' );
        $form_id = ! empty($instance['form_id'])?absint($instance['form_id']):0;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'form_id' ) ); ?>"><?php _e( esc_attr( 'Select Form:' ) ); ?></label>
            <?php 
                $args = array( 'post_type' => 'u_subscribe_forms', 'posts_per_page' => -1);
                $wp_query = new WP_Query( $args );
            if($wp_query->have_posts()): ?>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_id' ) ); ?>">
                <?php
                while($wp_query->have_posts()){
                    $wp_query->the_post();
                ?>
                <option <?php selected($form_id, get_the_ID(), true);?> value="<?php the_ID(); ?>"> <?php the_title(); ?> </option>
                <?php } ?>
            </select>
            <?php else: ?>
                <p>
                <?php _e('Please create a form first to select in Widget.') ?>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=u_subscribe_forms')); ?>"><?php _e('Click Here To Create New Form') ?></a>
                </p>
            <?php endif; ?>
        </p>
        <?php 
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['form_id'] = ! empty( $new_instance['form_id'] ) ? absint($new_instance['form_id']) : 0;
        return $instance;
    }

} // class Ultimate_Subscribe_Form_Widget


// register Ultimate_Subscribe_Form_Widget widget
function ultimate_subscribe_form_register_widget() {
    register_widget( 'Ultimate_Subscribe_Form_Widget' );
}
add_action( 'widgets_init', 'ultimate_subscribe_form_register_widget' );
