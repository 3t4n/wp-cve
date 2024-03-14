<?php
/**
 * Course Widget
 *
 * @class       TP_Education_Featured_Course_Widget
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'Tp_Education_Featured_Course_Widget' ) ) :

    class Tp_Education_Featured_Course_Widget extends WP_Widget {

        /**
         * Sets up the widgets name etc
         */
        public function __construct() 
        {
            $tp_widget_featured_course_list = array(
                'classname'   => 'widget_tp_featured_course_widget widget_featured_courses',
                'description' => __( 'Retrive Featured Courses.', 'tp-education' ),
            );
            parent::__construct( 'widget_tp_featured_course_widget', __( 'TP : Featured Course', 'tp-education' ), $tp_widget_featured_course_list );
        }


        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) 
        {
            extract( $args , EXTR_SKIP );

            // outputs the content of the widget
            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }

            $tp_title  = ( ! empty( $instance['title'] ) ) ? ( $instance['title'] ) : __( 'Featured Courses', 'tp-education' );
            $featured_course = ! empty( $instance['featured_course'] ) ? ( array ) $instance['featured_course'] : array();

            echo $args['before_widget'];
            if ( ! empty( $tp_title ) ) {
                echo $args['before_title'] . esc_html( $tp_title ) . $args['after_title'];
            }
            echo '<ul>';
            $post_args = array(
                'post_type'     => 'tp-course',
                'post__in'      => $featured_course,
                'order'         => 'post__in'
            );

            $wp_query = get_posts( $post_args );
            foreach ( $wp_query as $post ) :
            ?>
                <li class="has-post-thumbnail">
                    <div class="image-wrapper">
                        <a href="<?php the_permalink( $post->ID ); ?>">
                            <?php 
                            if ( has_post_thumbnail( $post->ID ) ) :
                                $image = get_the_post_thumbnail( $post->ID, $size = 'thumbnail', array( 'alt' => esc_attr( get_the_title( $post->ID ) ) ) );
                                echo $image;
                            else :
                                echo '<img src="' . esc_url( TP_EDUCATION_URL_PATH ) . '/assets/images/demo-200x200.jpg" alt="' . esc_attr( get_the_title( $post->ID ) ) . '" >';
                            endif; 
                            ?>
                        </a>
                    </div><!-- .image-wrapper -->
                    <div class="course-wrapper">
                        <div class="course-title">
                            <h5><a href="<?php the_permalink( $post->ID ); ?>"><?php echo esc_html( get_the_title( $post->ID) ); ?></a></h5>
                        </div><!-- .course-title -->
                        <?php 
                        $course_price_value = get_post_meta( $post->ID, 'tp_course_price_value', true );
                        if ( ! empty( $course_price_value ) ): ?>
                            <div class="course-price clear">
                                <div class="pull-left">
                                    <p class="price-tag">
                                        <?php tp_course_price( $post->ID ); ?>
                                    </p>
                                </div><!-- .pull-left -->
                            </div><!-- .course-price -->
                        <?php endif; ?>
                    </div><!-- .course-wrapper -->
                </li>
            <?php
            endforeach;
            wp_reset_postdata();
            echo '</ul>';
            echo $args['after_widget'];
        }


        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) 
        {
            $tp_title      = isset( $instance['title'] ) ? ( $instance['title'] ) : __( 'Featured Course', 'tp-education' );
            $featured_course   = ! empty( $instance['featured_course'] ) ? ( array ) $instance['featured_course'] : array();
           ?>

           <p>
               <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tp-education' ); ?></label>
               <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $tp_title ); ?>" />
           </p>

           <p>
	            <label for="<?php echo esc_attr( $this->get_field_id( 'featured_course' ) ); ?>"><?php _e( 'Select Courses', 'tp-education' ); ?></label>
                <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'featured_course' ) ); ?>[]" multiple>
                    <?php 
                    $args = array(
                        'post_type' => 'tp-course',
                        'order'     => 'ASC',
                        'orderby'   => 'name'
                        );
                    $posts = get_posts( $args );
                    foreach ( $posts as $post ) : 
                        $selected = in_array( absint( $post->ID ), $featured_course ) ? 'selected' : '';
                        ?>
                        <option value="<?php echo absint( $post->ID ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $post->post_title ); ?></option>
                    <?php endforeach; ?>
                </select>
           </p>
           <?php
        }

        /**
        * Processing widget options on save
        *
        * @param array $new_instance The new options
        * @param array $old_instance The previous options
        */
        public function update( $new_instance, $old_instance ) {
            // processes widget options to be saved
            $instance           		= $old_instance;
            $instance['title']  		= sanitize_text_field( $new_instance['title'] );
            $instance['featured_course'] 	= ! empty( $new_instance['featured_course'] ) ? array_map( 'absint', $new_instance['featured_course'] ) : array();
           
            return $instance;
        }
    }

endif;
