<?php
class tr_ps_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'tr_ps_widget',


            __('TR Post Slider Widget', 'tr_ps_widget_domain'),


            array( 'description' => __( 'TR Post Slider Widget', 'tr_ps_widget_domain' ), )
        );
    }

    public function widget( $args, $instance ) {
        $widgetIDS = $args['widget_id'];
        $widgetIDS = explode('-', $widgetIDS);
        $widgetID  = $widgetIDS[1];

        $title                          = apply_filters( 'widget_title', $instance[ 'title' ] );
        $details                        = apply_filters( 'widget_details', $instance[ 'details' ] );
        $tr_post_title                  = apply_filters( 'widget_tr_post_title', $instance['tr_post_title'] );
        $tr_post_number                 = apply_filters( 'widget_tr_post_number', $instance['tr_post_number'] );
        $tr_post_content_num            = apply_filters( 'widget_tr_post_content_num', $instance['tr_post_content_num'] );
        $tr_rm_option                   = apply_filters( 'widget_tr_rm_option', $instance['tr_rm_option'] );
        $tr_post_cat_slug               = apply_filters( 'widget_tr_post_cat_slug', $instance['tr_post_cat_slug'] );
        $tr_post_tag_slug               = apply_filters( 'widget_tr_post_tag_slug', $instance['tr_post_tag_slug'] );
        $tr_post_id                     = apply_filters( 'widget_tr_post_id', $instance['tr_post_id'] );
        $tr_post_sld_spd                = apply_filters( 'widget_tr_post_sld_spd', $instance['tr_post_sld_spd'] );
        $tr_post_sld_duration           = apply_filters( 'widget_tr_post_sld_duration', $instance['tr_post_sld_duration'] );
        $tr_post_sld_direction          = apply_filters( 'widget_tr_post_sld_direction', $instance['tr_post_sld_direction'] );
        $tr_post_navigation             = apply_filters( 'widget_tr_navigation', $instance['tr_navigation'] );
        $tr_post_pagination             = apply_filters( 'widget_tr_pagination', $instance['tr_pagination'] );


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
        $output = '<div class="tr-post-slider"><div id="tr-post-shuffle" class="tr-post-shuffle-class">';
        if ( $loop ) :
            while ( $loop->have_posts() ) :
                $loop->the_post();

                    
                $output .= '<div class="tr-ps-single">';
                if($tr_post_title){
                    $output .= '<a class="tr-ps-a-title" href="'.get_permalink().'">'.get_the_title().'</a>';                    
                }
                $output .= '<div class="tr-post-content">';
                if(has_excerpt()){
                    if(!empty($tr_post_content_num)){
                        $output .= substr(get_the_excerpt(),0,$tr_post_content_num).'...';
                    }
                    else{
                        $output .= get_the_excerpt();
                    }
                } else if(get_the_content() != '') { 
                    if(!empty($tr_post_content_num)){
                        $output .= substr(get_the_content(),0,$tr_post_content_num).'...';
                    }
                    else{
                        $output .= get_the_content();
                    } 
                }
                $output .= '</div>';
                if($tr_rm_option == 1){
                    $output .= '<a class="tr-read-more" href="'.get_permalink().'">Read More</a>';
                }
                $output .= '</div>';

            endwhile;
        endif;
        $output .= '</div>';
        $navigation_option = '';
        if($tr_post_navigation){
            $navigation_option = ",
                next:   '#tr-ps-next-".$widgetID."',
                prev:   '#tr-ps-prev-".$widgetID."'";

            $output .= '<div class="tr-ps-option"><div id="tr-ps-next-'.$widgetID.'" class="tr-ps-next"></div><div class="tr-ps-prev" id="tr-ps-prev-'.$widgetID.'"></div></div>';
        }
        $pagination_option = '';
        if($tr_post_pagination){
            $pagination_option = ",
            pager:  '#tr-ps-nav-".$widgetID."'";
            $output .= '<div id="tr-ps-nav-'.$widgetID.'" class="tr-ps-pagination"></div>';
        }
        $output .= '</div>';
        $output .= 
            "<script>
                jQuery(document).ready(function($){                    
                    $('.tr-post-shuffle-class').cycle({ 
                        fx:      '".$tr_post_sld_direction."',
                        speed: ".$speed.",
                        timeout: ".$duration."
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
            $title                          = $instance[ 'title' ];
            $details                        = esc_textarea($instance[ 'details' ]);
            $tr_post_title                  = $instance['tr_post_title'];
            $tr_post_number                 = $instance['tr_post_number'];
            $tr_post_content_num            = $instance['tr_post_content_num'];
            $tr_rm_option                   = $instance['tr_rm_option'];
            $tr_post_cat_slug               = esc_textarea($instance['tr_post_cat_slug']);
            $tr_post_tag_slug               = esc_textarea($instance['tr_post_tag_slug']);
            $tr_post_id                     = esc_textarea($instance['tr_post_id']);
            $tr_post_sld_spd                = $instance['tr_post_sld_spd'];
            $tr_post_sld_duration           = $instance['tr_post_sld_duration'];
            $tr_post_sld_direction          = $instance['tr_post_sld_direction'];
            $tr_post_navigation             = $instance['tr_navigation'];
            $tr_post_pagination             = $instance['tr_pagination'];
        }
        else {

        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'details' ); ?>" name="<?php echo $this->get_field_name( 'details' ); ?>"><?php echo esc_attr( $details ); ?></textarea>
        <p><strong>Post Filtering Options</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_title' ); ?>"><?php _e( 'Show Post Title:' ); ?></label>
            <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'tr_post_title' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_title' ); ?>" value ="1" <?php echo ($tr_post_title) ? 'checked' : ''; ?> />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_number' ); ?>"><?php _e( 'Number of Post:' ); ?></label>
            <input type="number" class="widefat" id="<?php echo $this->get_field_id( 'tr_post_number' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_number' ); ?>" value ="<?php echo esc_attr( $tr_post_number ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_content_num' ); ?>"><?php _e( 'Number of Content Letters:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tr_post_content_num' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_content_num' ); ?>" type="number" value="<?php echo esc_attr( $tr_post_content_num ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_rm_option' ); ?>"><?php _e( 'Show "Read More" Option:' ); ?></label>
            <select  class="widefat" id="<?php echo $this->get_field_id( 'tr_rm_option' ); ?>" name="<?php echo $this->get_field_name( 'tr_rm_option' ); ?>">
                <option value="1" <?php echo (esc_attr( $tr_rm_option ) == 1) ? 'selected' : '' ?> >Yes</option>
                <option value="2" <?php echo (esc_attr( $tr_rm_option ) == 2) ? 'selected' : '' ?> >No</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_cat_slug' ); ?>"><?php _e( 'Category Slug: (cat-1, cat-2, cat-3)' ); ?></label>
            <textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id( 'tr_post_cat_slug' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_cat_slug' ); ?>" ><?php echo esc_attr( $tr_post_cat_slug ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_tag_slug' ); ?>"><?php _e( 'Tag Slug: (tag-1, tag-2, tag-3)' ); ?></label>
            <textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id( 'tr_post_tag_slug' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_tag_slug' ); ?>" ><?php echo esc_attr( $tr_post_tag_slug ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_id' ); ?>"><?php _e( 'Post ID: (1, 12, 15)' ); ?></label>
            <textarea class="widefat" rows="4" cols="20" id="<?php echo $this->get_field_id( 'tr_post_id' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_id' ); ?>" ><?php echo esc_attr( $tr_post_id ); ?></textarea>
        </p>

        <p><strong>Slider Options</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_sld_spd' ); ?>"><?php _e( 'Speed(millisecond):' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tr_post_sld_spd' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_sld_spd' ); ?>" type="number" value="<?php echo esc_attr( $tr_post_sld_spd ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_sld_duration' ); ?>"><?php _e( 'Duration(millisecond):' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'tr_post_sld_duration' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_sld_duration' ); ?>" type="number" value="<?php echo esc_attr( $tr_post_sld_duration ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_post_sld_direction' ); ?>"><?php _e( 'Slider Transition:' ); ?></label>
            <select  class="widefat" id="<?php echo $this->get_field_id( 'tr_post_sld_direction' ); ?>" name="<?php echo $this->get_field_name( 'tr_post_sld_direction' ); ?>">
                <option value="scrollLeft" <?php echo ($tr_post_sld_direction == 'scrollLeft') ? 'selected' : '' ?> >Right to Left</option>
                <option value="scrollRight" <?php echo ($tr_post_sld_direction == 'scrollRight') ? 'selected' : '' ?> >Left to Right</option>
                <option value="scrollDown" <?php echo ($tr_post_sld_direction == 'scrollDown') ? 'selected' : '' ?> >Up to Down</option>
                <option value="scrollUp" <?php echo ($tr_post_sld_direction == 'scrollUp') ? 'selected' : '' ?> >Down to Up</option>
                <option value="scrollHorz" <?php echo ($tr_post_sld_direction == 'scrollHorz') ? 'selected' : '' ?> >Slide Horizontal</option>
                <option value="scrollVert" <?php echo ($tr_post_sld_direction == 'scrollVert') ? 'selected' : '' ?> >Slide Vertical</option>
                <option value="fade" <?php echo ($tr_post_sld_direction == 'fade') ? 'selected' : '' ?> >Fade</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'tr_navigation' ); ?>"><?php _e( 'Show Navigation:' ); ?></label>
            <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'tr_navigation' ); ?>" name="<?php echo $this->get_field_name( 'tr_navigation' ); ?>" value ="1" <?php echo ($tr_post_navigation) ? 'checked' : ''; ?> />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tr_pagination' ); ?>"><?php _e( 'Show Pagination:' ); ?></label>
            <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'tr_pagination' ); ?>" name="<?php echo $this->get_field_name( 'tr_pagination' ); ?>" value ="1" <?php echo ($tr_post_pagination) ? 'checked' : ''; ?> />
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']                  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['details']                = ( ! empty( $new_instance['details'] ) ) ? $new_instance['details'] : '';
        $instance['tr_post_title']          = ( ! empty( $new_instance['tr_post_title'] ) ) ? strip_tags( $new_instance['tr_post_title'] ) : '';
        $instance['tr_post_number']          = ( ! empty( $new_instance['tr_post_number'] ) ) ? strip_tags( $new_instance['tr_post_number'] ) : '';
        $instance['tr_post_content_num']    = ( ! empty( $new_instance['tr_post_content_num'] ) ) ? strip_tags( $new_instance['tr_post_content_num'] ) : '';
        $instance['tr_rm_option']           = ( ! empty( $new_instance['tr_rm_option'] ) ) ? strip_tags( $new_instance['tr_rm_option'] ) : '';
        $instance['tr_post_cat_slug']       = ( ! empty( $new_instance['tr_post_cat_slug'] ) ) ? $new_instance['tr_post_cat_slug'] : '';
        $instance['tr_post_tag_slug']       = ( ! empty( $new_instance['tr_post_tag_slug'] ) ) ? $new_instance['tr_post_tag_slug'] : '';
        $instance['tr_post_id']             = ( ! empty( $new_instance['tr_post_id'] ) ) ? $new_instance['tr_post_id'] : '';
        $instance['tr_post_sld_spd']        = ( ! empty( $new_instance['tr_post_sld_spd'] ) ) ? strip_tags( $new_instance['tr_post_sld_spd'] ) : '';
        $instance['tr_post_sld_duration']   = ( ! empty( $new_instance['tr_post_sld_duration'] ) ) ? strip_tags( $new_instance['tr_post_sld_duration'] ) : '';
        $instance['tr_post_sld_direction']   = ( ! empty( $new_instance['tr_post_sld_direction'] ) ) ? $new_instance['tr_post_sld_direction'] : '';
        $instance['tr_navigation']           = ( ! empty( $new_instance['tr_navigation'] ) ) ? strip_tags( $new_instance['tr_navigation'] ) : '';
        $instance['tr_pagination']           = ( ! empty( $new_instance['tr_pagination'] ) ) ? strip_tags( $new_instance['tr_pagination'] ) : '';

        return $instance;
    }
}

function tr_ps_load_widget() {
    register_widget( 'tr_ps_widget' );
}
add_action( 'widgets_init', 'tr_ps_load_widget' );
