<?php
/**
 *  Initialize and Register Csr Reviews Widget
 */
add_action('widgets_init', 'csr_register_reviews_widget', 100);

function csr_register_reviews_widget() {
    register_widget('CsrReviews_Widget');
}

class CsrReviews_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                'csr_reviews_widget', 
                __('CSR Recent Reviews', 'csr_starrating'), 
                array('description' => __('Widget to display latest CSR Reviews', 'csr_starrating'),) 
        );
    }

    public function widget($args, $instance) {
        global $wpdb;
        global $post;
        
        echo $args['before_widget'];        
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Reviews' );        
        $title = apply_filters( 'widget_title', $title );        
        
        echo $args['before_title'] . $title . $args['after_title'];        
               
        $posts_per_page = ( ! empty( $instance['no_reviews'] ) ) ? absint( $instance['no_reviews'] ) : 2;
        if ( ! $posts_per_page )
            $posts_per_page = 2;
                
        $no_words_per_review = ( ! empty( $instance['no_words'] ) ) ? absint( $instance['no_words'] ) : 25;
        if ( ! $no_words_per_review )
            $no_words_per_review = 25;
                
        $reviews_args = array(
                    'posts_per_page'   => $posts_per_page,                        
                    'post_type'        => 'reviews'
                );
        $reviews = get_posts( $reviews_args );
        
        foreach ( $reviews as $post ) : setup_postdata( $post ); 
            $review_id = get_the_ID();
            
            $result = $wpdb->get_row("SELECT * FROM " . CSRVOTESTBL . " WHERE post_id=$review_id");                       
            $overall_rating = $result->overall_rating;
        ?>
            <div class="csr-row review-body">
                <h3>
                    <a href="<?php echo home_url('reviews'); ?>">
                        <?php the_title(); ?>
                    </a>                    
                </h3>
                <p><?php $the_content = get_the_content(); echo wp_trim_words( $the_content, $no_words_per_review, '… ' ); ?></p>
                <div class="review-meta">
                    <div class="reviewed-rating">
                        <div class="csr-ratings" id="csr_rate_<?php echo $review_id; ?>"  data-rating="<?php echo $overall_rating; ?>"></div>
                    </div>
                </div>
            </div>
        <?php 
        endforeach; 
        wp_reset_postdata();
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Reviews', 'csr_starrating');
        $no_reviews = !empty($instance['no_reviews']) ? $instance['no_reviews'] : 2;
        $no_words = !empty($instance['no_words']) ? $instance['no_words'] : 25;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('no_reviews'); ?>"><?php _e('Number of posts to show:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('no_reviews'); ?>" name="<?php echo $this->get_field_name('no_reviews'); ?>" type="text" value="<?php echo esc_attr($no_reviews); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('no_words'); ?>"><?php _e('Limit number of words:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('no_words'); ?>" name="<?php echo $this->get_field_name('no_words'); ?>" type="text" value="<?php echo esc_attr($no_words); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['no_reviews'] = (int) $new_instance['no_reviews'];        
        $instance['no_words'] = (int) $new_instance['no_words'];        
        
        return $instance;
    }    

}
