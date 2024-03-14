<?php
class tr_fs_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'tr_fs_widget',


            __('TR Post Feature Image Widget', 'tr_fs_widget_domain'),


            array( 'description' => __( 'TR Post Featured Image Widget', 'tr_fs_widget_domain' ), )
        );
    }

    public function widget( $args, $instance ) {
        $widgetIDS = $args['widget_id'];
        $widgetIDS = explode('-', $widgetIDS);
        $widgetID  = $widgetIDS[1];
        $title                          = apply_filters( 'widget_tr_fs_title', $instance[ 'tr_fs_title' ] );
        $details                        = apply_filters( 'widget_tr_fs_details', $instance[ 'tr_fs_details' ] );
        $tr_post_title                  = apply_filters( 'widget_tr_fs_post_title', $instance['tr_fs_post_title'] );
        $tr_post_number                 = apply_filters( 'widget_tr_fs_post_number', $instance['tr_fs_post_number'] );
        $tr_post_cat_slug               = apply_filters( 'widget_tr_fs_post_cat_slug', $instance['tr_fs_post_cat_slug'] );
        $tr_post_tag_slug               = apply_filters( 'widget_tr_fs_post_tag_slug', $instance['tr_fs_post_tag_slug'] );
        $tr_post_id                     = apply_filters( 'widget_tr_fs_post_id', $instance['tr_fs_post_id'] );
        $tr_post_sld_spd                = apply_filters( 'widget_tr_fs_post_sld_spd', $instance['tr_fs_post_sld_spd'] );
        $tr_post_sld_duration           = apply_filters( 'widget_tr_fs_post_sld_duration', $instance['tr_fs_post_sld_duration'] );
        $tr_post_sld_direction          = apply_filters( 'widget_tr_fs_post_sld_direction', $instance['tr_fs_post_sld_direction'] );
        $tr_post_navigation             = apply_filters( 'widget_tr_fs_navigation', $instance['tr_fs_navigation'] );
        $tr_post_pagination             = apply_filters( 'widget_tr_fs_pagination', $instance['tr_fs_pagination'] );

        $itemnum = ($tr_post_number) ? $tr_post_number : '-1';
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
        if ( ! empty( $details ) )
            echo '<p>'.$details.'</p>';
        
        $postID = '';
        if(!empty($tr_post_id)){
            $postID = explode(",", $tr_post_id);
            $postID = array_map(trim, $postID);
        }
        

        $postCat = '';
        if(!empty($tr_post_cat_slug)){
            $postCat = explode(",", $tr_post_cat_slug);
            $postCat = array_map(trim, $postCat);
            $postCat = implode(",", $postCat);
        }
        $postTag = '';
        if(!empty($tr_post_tag_slug)){
            $postTag = explode(",", $tr_post_tag_slug);
            $postTag = array_map(trim, $postTag);
            $postTag = implode(",", $postTag);
        }
        if(!empty($postID)){
            $postCat = '';
            $postTag = '';
        }

        $speed = '1000';
        if(!empty($tr_post_sld_spd)){
            $speed = $tr_post_sld_spd;
        }
        $duration = '2000';
        if(!empty($tr_post_sld_duration)){
            $duration = $tr_post_sld_duration;
        }
        if(empty($tr_post_sld_direction)){
            $tr_post_sld_direction = 'scrollLeft';
        }
        if(empty($tr_post_navigation)){
            $tr_post_navigation = 0;
        }
        if(empty($tr_post_pagination)){
            $tr_post_pagination = 0;
        }
        $loop = new WP_Query(
            array(
                'post_type'         => 'post',
                'posts_per_page'    => $itemnum,
                'post__in'          => $postID,
                'category_name'     => $postCat,
                'tag'               => $postTag
            )
        );
        $output = '<div class="tr-fs-post-slider"><div id="tr-fs-post-shuffle" class="tr-fs-post-shuffle-class">';
        if ( $loop ) :
            while ( $loop->have_posts() ) :
                $loop->the_post();

                    
                $output .= '<div class="tr-fs-single">';
                $output .= '<div class="tr-fs-post-content"><a href="'.get_permalink().'">';
                $url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );

                $output .= '<img src="'.$url[0].'"/>';
                if($tr_post_title){
                    $title_class = ($tr_post_title == 2) ? 'always': '';
                    $output .= '<p class="tr-fs-a-title '.$title_class.'">'.get_the_title().'</p>';
                }
                $output .= '</a></div>';
                $output .= '</div>';

            endwhile;
        endif;
        $output .= '</div>';
        $navigation_option = '';
        if($tr_post_navigation){
            $navigation_option = ",
                next:   '#tr-fs-next-".$widgetID."',
                prev:   '#tr-fs-prev-".$widgetID."'";

            $output .= '<div class="tr-fs-option"><div id="tr-fs-next-'.$widgetID.'" class="tr-fs-next"></div><div class="tr-fs-prev" id="tr-fs-prev-'.$widgetID.'"></div></div>';
        }
        $pagination_option = '';
        if($tr_post_pagination){
            $pagination_option = ",
                pager:  '#tr-fs-nav-".$widgetID."'";
            $output .= '<div id="tr-fs-nav-'.$widgetID.'" class="tr-fs-pagination"></div>';
        }
        $output .= '</div>';
        $output .= 
            "<script>
                jQuery(document).ready(function($){                    
                    $('.tr-fs-post-shuffle-class').cycle({ 
                        fx:      '".$tr_post_sld_direction."',
                        speed: ".$speed.",
                        timeout: ".$duration.",
                        pause: 1
                        ".$navigation_option."
                        ".$pagination_option."

                    });
                })
            </script>";
        echo $output;
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        if ($instance) {
            $title                          = $instance[ 'tr_fs_title' ];
            $details                        = esc_textarea($instance[ 'tr_fs_details' ]);
            $tr_post_title                  = $instance['tr_fs_post_title'];
            $tr_post_number                 = $instance['tr_fs_post_number'];
            $tr_post_cat_slug               = esc_textarea($instance['tr_fs_post_cat_slug']);
            $tr_post_tag_slug               = esc_textarea($instance['tr_fs_post_tag_slug']);
            $tr_post_id                     = esc_textarea($instance['tr_fs_post_id']);
            $tr_post_sld_spd                = $instance['tr_fs_post_sld_spd'];
            $tr_post_sld_duration           = $instance['tr_fs_post_sld_duration'];
            $tr_post_sld_direction          = $instance['tr_fs_post_sld_direction'];
            $tr_post_navigation             = $instance['tr_fs_navigation'];
            $tr_post_pagination             = $instance['tr_fs_pagination'];
        }
        else {

        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_title' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'tr_fs_details' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_details' ); ?>"><?php echo esc_attr( $details ); ?></textarea>
        <p><strong>Post Filtering Options</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_title' ); ?>"><?php _e( 'Show Post Title:' ); ?></label>
            <select  class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_post_title' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_title' ); ?>">
                <option value="0" <?php echo (esc_attr( $tr_post_title ) == 0) ? 'selected' : '' ?> >Hidden</option>
                <option value="1" <?php echo (esc_attr( $tr_post_title ) == 1) ? 'selected' : '' ?> >Mouseover</option>
                <option value="2" <?php echo (esc_attr( $tr_post_title ) == 2) ? 'selected' : '' ?> >Always</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_number' ); ?>"><?php _e( 'Number of Post:' ); ?></label>
            <input type="number" class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_post_number' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_number' ); ?>" value ="<?php echo esc_attr( $tr_post_number ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_cat_slug' ); ?>"><?php _e( 'Category Slug: (cat-1, cat-2, cat-3)' ); ?></label>
            <textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id( 'tr_fs_post_cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_cat_slug' ); ?>" ><?php echo esc_attr( $tr_post_cat_slug ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_tag_slug' ); ?>"><?php _e( 'Tag Slug: (tag-1, tag-2, tag-3)' ); ?></label>
            <textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id( 'tr_fs_post_tag_slug' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_tag_slug' ); ?>" ><?php echo esc_attr( $tr_post_tag_slug ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_id' ); ?>"><?php _e( 'Post ID: (1, 12, 15)' ); ?></label>
            <textarea class="widefat" rows="4" cols="20" id="<?php echo $this->get_field_id( 'tr_fs_post_id' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_id' ); ?>" ><?php echo esc_attr( $tr_post_id ); ?></textarea>
        </p>

        <p><strong>Slider Options</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_sld_spd' ); ?>"><?php _e( 'Speed(millisecond):' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_post_sld_spd' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_sld_spd' ); ?>" type="number" value="<?php echo esc_attr( $tr_post_sld_spd ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_sld_duration' ); ?>"><?php _e( 'Duration(millisecond):' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_post_sld_duration' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_sld_duration' ); ?>" type="number" value="<?php echo esc_attr( $tr_post_sld_duration ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_post_sld_direction' ); ?>"><?php _e( 'Slider Transition:' ); ?></label>
            <select  class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_post_sld_direction' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_post_sld_direction' ); ?>">
                <option value="scrollLeft" <?php echo (esc_attr( $tr_post_sld_direction ) == 'scrollLeft') ? 'selected' : '' ?> >Right to Left</option>
                <option value="scrollRight" <?php echo (esc_attr( $tr_post_sld_direction ) == 'scrollRight') ? 'selected' : '' ?> >Left to Right</option>
                <option value="scrollDown" <?php echo (esc_attr( $tr_post_sld_direction ) == 'scrollDown') ? 'selected' : '' ?> >Up to Down</option>
                <option value="scrollUp" <?php echo (esc_attr( $tr_post_sld_direction ) == 'scrollUp') ? 'selected' : '' ?> >Down to Up</option>
                <option value="scrollHorz" <?php echo ($tr_post_sld_direction == 'scrollHorz') ? 'selected' : '' ?> >Slide Horizontal</option>
                <option value="scrollVert" <?php echo ($tr_post_sld_direction == 'scrollVert') ? 'selected' : '' ?> >Slide Vertical</option>
                <option value="fade" <?php echo (esc_attr( $tr_post_sld_direction ) == 'fade') ? 'selected' : '' ?> >Fade</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_navigation' ); ?>"><?php _e( 'Show Navigation:' ); ?></label>
            <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_navigation' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_navigation' ); ?>" value ="1" <?php echo ($tr_post_navigation) ? 'checked' : ''; ?> />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_fs_pagination' ); ?>"><?php _e( 'Show Pagination:' ); ?></label>
            <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'tr_fs_pagination' ); ?>" name="<?php echo $this->get_field_name( 'tr_fs_pagination' ); ?>" value ="1" <?php echo ($tr_post_pagination) ? 'checked' : ''; ?> />
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['tr_fs_title']                  = ( ! empty( $new_instance['tr_fs_title'] ) ) ? strip_tags( $new_instance['tr_fs_title'] ) : '';
        $instance['tr_fs_details']                = ( ! empty( $new_instance['tr_fs_details'] ) ) ? $new_instance['tr_fs_details'] : '';
        $instance['tr_fs_post_title']             = ( ! empty( $new_instance['tr_fs_post_title'] ) ) ? strip_tags( $new_instance['tr_fs_post_title'] ) : '';
        $instance['tr_fs_post_number']            = ( ! empty( $new_instance['tr_fs_post_number'] ) ) ? strip_tags( $new_instance['tr_fs_post_number'] ) : '';
        $instance['tr_fs_post_cat_slug']          = ( ! empty( $new_instance['tr_fs_post_cat_slug'] ) ) ? $new_instance['tr_fs_post_cat_slug'] : '';
        $instance['tr_fs_post_tag_slug']          = ( ! empty( $new_instance['tr_fs_post_tag_slug'] ) ) ? $new_instance['tr_fs_post_tag_slug'] : '';
        $instance['tr_fs_post_id']                = ( ! empty( $new_instance['tr_fs_post_id'] ) ) ? $new_instance['tr_fs_post_id'] : '';
        $instance['tr_fs_post_sld_spd']           = ( ! empty( $new_instance['tr_fs_post_sld_spd'] ) ) ? strip_tags( $new_instance['tr_fs_post_sld_spd'] ) : '';
        $instance['tr_fs_post_sld_duration']      = ( ! empty( $new_instance['tr_fs_post_sld_duration'] ) ) ? strip_tags( $new_instance['tr_fs_post_sld_duration'] ) : '';
        $instance['tr_fs_post_sld_direction']     = ( ! empty( $new_instance['tr_fs_post_sld_direction'] ) ) ? strip_tags( $new_instance['tr_fs_post_sld_direction'] ) : '';
        $instance['tr_fs_navigation']             = ( ! empty( $new_instance['tr_fs_navigation'] ) ) ? strip_tags( $new_instance['tr_fs_navigation'] ) : '';
        $instance['tr_fs_pagination']             = ( ! empty( $new_instance['tr_fs_pagination'] ) ) ? strip_tags( $new_instance['tr_fs_pagination'] ) : '';

        return $instance;
    }
}

function tr_fs_load_widget() {
    register_widget( 'tr_fs_widget' );
}
add_action( 'widgets_init', 'tr_fs_load_widget' );
