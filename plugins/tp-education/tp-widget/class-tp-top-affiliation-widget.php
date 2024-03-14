<?php
/**
 * Affiliation Widget
 *
 * @class       TP_Education_Top_Affiliation_Widget
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'Tp_Education_Top_Affiliation_Widget' ) ) :

    class Tp_Education_Top_Affiliation_Widget extends WP_Widget {

        /**
         * Sets up the widgets name etc
         */
        public function __construct() 
        {
            $tp_widget_top_affiliation_list = array(
                'classname'   => 'widget_tp_top_affiliation_widget widget_top_university',
                'description' => __( 'Retrive Top Affiliation.', 'tp-education' ),
            );
            parent::__construct( 'widget_tp_top_affiliation_widget', __( 'TP : Top Affiliation', 'tp-education' ), $tp_widget_top_affiliation_list );
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

            $tp_title  = ( ! empty( $instance['title'] ) ) ? ( $instance['title'] ) : __( 'Top Universities', 'tp-education' );
            $top_affiliation = ! empty( $instance['top_affiliation'] ) ? ( array ) $instance['top_affiliation'] : array();

            echo $args['before_widget'];
            if ( ! empty( $tp_title ) ) {
                echo $args['before_title'] . esc_html( $tp_title ) . $args['after_title'];
            }
            echo '<ul>';
            $post_args = array(
                'post_type'     => 'tp-affiliation',
                'post__in'      => $top_affiliation,
                'order'         => 'post__in'
            );

            $wp_query = get_posts( $post_args );
            foreach ( $wp_query as $post ) :
            ?>
                <li>
                    <span class="university-logo">
                        <a href="<?php the_permalink( $post->ID ); ?>">
                            <?php 
                            if ( has_post_thumbnail( $post->ID ) ) :
                                $image = get_the_post_thumbnail( $post->ID, $size = 'large', array( 'alt' => esc_attr( get_the_title( $post->ID ) ) ) );
                                echo $image;
                            else :
                                echo '<img src="' . esc_url( TP_EDUCATION_URL_PATH ) . '/assets/images/demo-200x200.jpg" alt="' . esc_attr( get_the_title( $post->ID ) ) . '" >';
                            endif; 
                            ?>
                        </a>
                    </span>
                    <span class="university-name">
                        <a href="<?php the_permalink( $post->ID ); ?>">
                            <?php echo esc_html( get_the_title( $post->ID) ); ?>
                        </a>
                    </span>
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
            $tp_title      = isset( $instance['title'] ) ? ( $instance['title'] ) : __( 'Top Universities', 'tp-education' );
            $top_affiliation   = ! empty( $instance['top_affiliation'] ) ? ( array ) $instance['top_affiliation'] : array();
           ?>

           <p>
               <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tp-education' ); ?></label>
               <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $tp_title ); ?>" />
           </p>

           <p>
	            <label for="<?php echo esc_attr( $this->get_field_id( 'top_affiliation' ) ); ?>"><?php _e( 'Select Affiliations', 'tp-education' ); ?></label>
                <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'top_affiliation' ) ); ?>[]" multiple>
                    <?php 
                    $args = array(
                        'post_type' => 'tp-affiliation',
                        'order'     => 'ASC',
                        'orderby'   => 'name'
                        );
                    $posts = get_posts( $args );
                    foreach ( $posts as $post ) : 
                        $selected = in_array( absint( $post->ID ), $top_affiliation ) ? 'selected' : '';
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
            $instance['top_affiliation'] 	= ! empty( $new_instance['top_affiliation'] ) ? array_map( 'absint', $new_instance['top_affiliation'] ) : array();
           
            return $instance;
        }
    }

endif;
