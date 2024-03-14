<?php

GFForms::include_feed_addon_framework();

class CSR_FeedAddon_Class extends GFFeedAddOn {

    protected $_version = 1.0;
    protected $_min_gravityforms_version = '1.9.16';
    protected $_slug = 'csrfeedaddon';
    protected $_path = '';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms CSR Feed Add-On';
    protected $_short_title = 'CSR Reviews';
    private static $_instance = null;
   
    public static function get_instance() {
        if (self::$_instance == null) {
            self::$_instance = new CSR_FeedAddon_Class();
        }

        return self::$_instance;
    }
  
    public function init() {
        parent::init();
    }

    public function process_feed($feed, $entry, $form) {
        $feedName = $feed['meta']['feedName'];

        // Retrieve the name => value pairs for all fields mapped in the 'mappedFields' field map.
        $field_map = $this->get_field_map_fields($feed, 'mappedFields');
        $field_map_custom = $this->get_dynamic_field_map_fields($feed, 'review_custom');

       
        $merge_vars = array();
        foreach ($field_map as $name => $field_id) {
            
            $merge_vars[$name] = $this->get_field_value($form, $entry, $field_id);
        }
        
        $merge_vars_custom = array();
        foreach ($field_map_custom as $name => $field_id) {
            
            $merge_vars_custom[$name] = $this->get_field_value($form, $entry, $field_id);
        }

        $rating = $this->save_csr_rating($merge_vars, $merge_vars_custom);
    }

    public function save_csr_rating( $csr_values, $csr_values_custom ) {
        
        if (empty($csr_values)) {
            return NULL;
        }

        global $wpdb;
        $return = false;

        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $display_name = $current_user->display_name;
            $user_ID = $current_user->ID;
        } else {
            $user_ID = 1;
            $display_name = '';
        }

        $rating = array(
            'Terrible' => 1,
            'Not so great' => 2,
            'Neutral' => 3,
            'Pretty good' => 4,
            'Excellent' => 5
        );

        $reviewed_by = $csr_values['name'];
        $reviewed_message = $csr_values['review'];
        $customer_review = $csr_values['rating'];
        $review_image = $csr_values['image'];

        $review_val = 5;

        if (is_string($customer_review) && isset($rating[$customer_review])) {
            $review_val = $rating[$customer_review];
        } elseif (is_numeric($customer_review)) {
            $review_val = $customer_review;
        } else {
            $review_val = 5;
        }

        $review_val = intval($review_val);

        $customer_review_post = array(
            'post_title' => $reviewed_by,
            'post_content' => $reviewed_message,
            'post_status' => 'draft',
            'post_type' => 'reviews',
            'post_author' => $user_ID
        );

        $review_ID = wp_insert_post($customer_review_post);

        if ($review_ID) {
            //update_post_meta($review_ID, 'rating', $customer_review);
            if(!empty($review_image)){
                $media_id = GFFormsModel::media_handle_upload( $review_image, $review_ID );
                if($media_id){
                    update_post_meta($review_ID, '_thumbnail_id', $media_id);
                }
            }
            
            if(!empty($csr_values_custom) && is_array($csr_values_custom)){
                foreach ($csr_values_custom as $meta_key => $meta_value) {
                    if(!empty($meta_key)){
                        update_post_meta( $review_ID, $meta_key, $meta_value );
                    }                    
                }
            }
            
            $wpdb->insert($wpdb->prefix . 'csr_votes', array(
                        'post_id' => $review_ID,
                        'reviewer_id' => $user_ID,
                        'overall_rating' => number_format($review_val, 1),
                        'number_of_votes' => 0,
                        'sum_votes' => 0.0,
                        'review_type' => 'Other'
                    )
            );

            $return = $review_ID;
        }

        return $return;
    }
   
    public function feed_settings_fields() {
        return array(
            array(
                'title' => esc_html__('CSR Feed Settings', 'csr-feedaddon'),
                'fields' => array(
                    array(
                        'label' => esc_html__('Feed name', 'csr-feedaddon'),
                        'type' => 'text',
                        'name' => 'feedName',
                        'tooltip' => esc_html__('This is the tooltip', 'csr-feedaddon'),
                        'class' => 'small',
                    ),
                    array(
                        'name' => 'mappedFields',
                        'label' => esc_html__('Map Fields', 'csr-feedaddon'),
                        'type' => 'field_map',
                        'field_map' => array(
                            array(
                                'name' => 'name',
                                'label' => esc_html__('Name', 'csr-feedaddon'),
                                'required' => 1,
                            ),
                            array(
                                'name' => 'review',
                                'label' => esc_html__('Review', 'csr-feedaddon'),
                                'required' => 1,
                            ),
                            array(
                                'name' => 'rating',
                                'label' => esc_html__('Rating', 'csr-feedaddon'),
                                'required' => 1,
                            ),
                            array(
                                'name' => 'image',
                                'label' => esc_html__('Review Image', 'csr-feedaddon'),
                                'required' => 1,
                            ),
                        ),
                    ),
                    array(
                        'name' => 'review_custom',
                        'label' => esc_html__('Metadata'),
                        'type' => 'dynamic_field_map',
                        'limit' => 50,
                    )
                ),
            ),
        );
    }

    /**
     * Configures which columns should be displayed on the feed list page.
     *
     * @return array
     */
    public function feed_list_columns() {
        return array(
            'feedName' => esc_html__('Name', 'csr-feedaddon'),
                //'mytextbox' => esc_html__( 'My Textbox', 'csr-feedaddon' ),
        );
    }

    public function can_create_feed() {
        return true;
    }

}
