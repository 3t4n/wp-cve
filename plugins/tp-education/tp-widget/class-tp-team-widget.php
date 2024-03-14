<?php
/**
 * Team Widget
 *
 * @class       TP_Education_Team_Widget
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'Tp_Education_Team_Widget' ) ) :

    class Tp_Education_Team_Widget extends WP_Widget {

        /**
         * Sets up the widgets name etc
         */
        public function __construct() 
        {
            $tp_widget_team_list = array(
                'classname'   => 'widget_tp_team_widget widget_counselors',
                'description' => __( 'Retrive Team.', 'tp-education' ),
            );
            parent::__construct( 'tp_education_team_widget', __( 'TP : Team', 'tp-education' ), $tp_widget_team_list );
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

            $tp_title  = ( ! empty( $instance['title'] ) ) ? ( $instance['title'] ) : __( 'Latest Posts', 'tp-education' );
            $tp_number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
            $tp_category = isset( $instance['team_category'] ) ? $instance['team_category'] : '';

            echo $args['before_widget'];
            if ( ! empty( $tp_title ) ) {
                echo $args['before_title'] . esc_html( $tp_title ) . $args['after_title'];
            }
            echo '<ul>';
            $post_args = array(
                'post_type'         => 'tp-team',
                'posts_per_page'    => $tp_number,
                'tax_query'         => array(
                    array(
                        'taxonomy'  => 'tp-team-category',
                        'field'     => 'id',
                        'terms'     => $tp_category
                    )
                )
            );

            $wp_query = get_posts( $post_args );
            foreach ( $wp_query as $post ) :
            ?>
				<li class="has-post-thumbnail">
					<div class="image-wrapper">
						<?php 
                        if ( has_post_thumbnail( $post->ID ) ) :
                            $image = get_the_post_thumbnail( $post->ID, $size = 'thumbnail', array( 'alt' => esc_attr( get_the_title( $post->ID ) ) ) );
                            echo $image;
                        else :
                            echo '<img src="' . esc_url( TP_EDUCATION_URL_PATH ) . '/assets/images/demo-200x200.jpg" alt="' . esc_attr( get_the_title( $post->ID ) ) . '" >';
                        endif; 
                        ?>
					</div><!--.image-wrapper-->
					<div class="text">
						<h6><a href="<?php the_permalink( $post->ID ); ?>"><?php echo esc_html( $post->post_title ); ?></a></h6>
						<span><?php tp_team_designation( $post->ID ); ?></span>
					</div><!--.text-->
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
            $tp_title      = isset( $instance['title'] ) ? ( $instance['title'] ) : __( 'Team Members', 'tp-education' );
            $tp_number     = isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
            $tp_category   = isset( $instance['team_category'] ) ? $instance['team_category'] : '';
           ?>

           <p>
               <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tp-education' ); ?></label>
               <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $tp_title ); ?>" />
           </p>

           <p>
            	<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of Team member to show:', 'tp-education' ); ?></label>
            	<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" max="7" value="<?php echo absint( $tp_number ); ?>" size="3" />
           </p>

           <p>
	            <label for="<?php echo esc_attr( $this->get_field_id( 'team_category' ) ); ?>"><?php _e( 'Select Team Category', 'tp-education' ); ?></label>
	            <?php
	            $taxonomy = 'tp-team-category';
	            $args = array(
	            	'show_option_none'   => __( 'Select Team Member', 'tp-education' ),
	                'hide_empty'         => 0,              
	                'hierarchical'       => 1,
	                'name'               => $this->get_field_name( 'team_category' ),
	                'class'              => 'widefat',              
	                'taxonomy'           => $taxonomy,
	                'selected'           => $tp_category,
	                'value_field'        => 'id'
	            );

	            wp_dropdown_categories( $args, $taxonomy );
	            ?>
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
            $instance['number'] 		= absint( $new_instance['number'] );
            $instance['team_category'] 	= (int) $new_instance['team_category'];
           
            return $instance;
        }
    }

endif;
