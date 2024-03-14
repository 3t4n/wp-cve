<?php
if (!defined('ABSPATH')) {
    exit; // disable direct access
}

/**
 * Register Learndash Complete Topic Hook
 */
add_filter('mycred_setup_hooks', 'Learndash_Completing_Topic_myCRED_Hook');

function Learndash_Completing_Topic_myCRED_Hook($installed) {

    $installed['hook_completing_topic_learndash'] = array(
        'title' => __('Completing a Topic (Learndash) ', 'mycred'),
        'description' => __('Awards %_plural% for LearnDash actions.', 'mycred'),
        'callback' => array('myCRED_Hook_Learndash_Completing_Topic')
    );

    return $installed;
}


/**
 * Hook for LearnDash Complete Topic
 */

add_action('mycred_load_hooks', 'mycred_load_learndash_completing_topic_hook', 10);

function mycred_load_learndash_completing_topic_hook() {

     if (!class_exists('myCRED_Hook_Learndash_Completing_Topic') && class_exists('myCRED_Hook')) {


        class myCRED_Hook_Learndash_Completing_Topic extends myCRED_Hook {


             /**
             * Construct
             */
            function __construct($hook_prefs, $type = 'mycred_default') {
                parent::__construct(array(
                    'id' => 'hook_completing_topic_learndash',
                    'defaults' => array(
                        'creds' => 0,
                        'log' => __('%plural% for Completing a General Topic', 'mycred-learndash'), 
                        'limit' => '0/x',
                        'check_specific_hook' => 0, 
                        'specific_topic_completed' => array(
                        'creds' => array(),
                        'log' => array(),
                        'select_option' => array(), 
                        'select_topic' => array(), 
                        'select_tag' => array(),
                            
                        ),
                    )
                        ), $hook_prefs, $type);
            }

               /**
             * Run
             */
            public function run() {

                // Topic Completed
                
                add_action('learndash_topic_completed', array($this, 'topic_completed'), 40, 1);
                
                add_action( 'wp_ajax_mycred_specific_topic_for_users', array( $this, 'mycred_specific_topic_for_users' ) );

                add_action( 'wp_ajax_nopriv_mycred_specific_topic_for_users', array( $this, 'mycred_specific_topic_for_users' ) );

            }

                /**
             * Get Topic Name
             */

            public function topic_name() {
                $query_args = array( 
                    'post_type'         =>   'sfwd-topic',
                    'posts_per_page'    =>   -1,
                    'orderby'           =>   'title',
                    'order'             =>   'ASC',
                                                            
                );
         
                $query_results = new WP_Query( $query_args );

                if( !empty( $query_results->posts ) )
                    return $query_results->posts;

                return false;

            }


             /**
             * AJAX Specific Topic function
             */

            public function mycred_specific_topic_for_users(){

                $prefs = $this->prefs;
                $topic_complete_data = $this->mycred_learndash_arrange_data( $prefs['specific_topic_completed'] );

              


                 if(isset($_POST['topic']) && $_POST['topic'] == 'topic'  )  {
                     $topic_field = $this->topic_name();
                     echo json_encode($topic_field);
                     wp_die();

                 } 

                 elseif (isset($_POST['topic'])  && $_POST['topic'] == 'tags'){
                      $tags = get_terms([
                    'taxonomy'  => 'ld_topic_tag',
                    'hide_empty'    => false
                    ]);
                     echo json_encode($tags);
                     wp_die();
                    
                 } 

            }

            public function mycred_ld_get_term_ids( $post_id, $taxonomy ) {

            $terms = get_the_terms( $post_id, $taxonomy );

            return ( empty( $terms ) || is_wp_error( $terms ) ) ? array() : wp_list_pluck( $terms, 'term_id' );

           }


            /**
             * Specific Topic Completed
             */

            public function topic_completed($args) {


                $topic_id = $args['topic']->ID;
                $user_id = $args['user']->ID;
                $course_id = $args['course'] instanceof WP_Post ? absint( $args['course']->ID ) : 0;
                $lesson_id = $args['lesson'] instanceof WP_Post ? absint( $args['lesson']->ID ) : 0;

                $tags = get_terms([
                    'taxonomy'  => 'ld_topic_tag',
                    'hide_empty'    => false
                ]);

                $ref_type  = array( 'ref_type' => 'post' );
                $prefs = $this->prefs;
                $terms_ids = $this->mycred_ld_get_term_ids( $topic_id, 'ld_topic_tag' );
                
                $terms =  get_the_terms( $args['topic']->ID, 'ld_topic_tag');

                foreach ($terms_ids as $term_id) {
                if( in_array( $term_id , $prefs['specific_topic_completed']['select_topic'] ) ) {
                    $hook_index = array_search( $term_id, $prefs['specific_topic_completed']['select_topic'] );
                    
                }

              }



              if(  isset($prefs['check_specific_hook']) && $prefs['check_specific_hook'] == '1' && !empty( $prefs['specific_topic_completed']['select_topic'] ) && in_array( $args['topic']->ID, $prefs['specific_topic_completed']['select_topic'] )  ) {

                

                $hook_index = array_search( $args['topic']->ID, $prefs['specific_topic_completed']['select_topic'] );


                     if (  
                        !empty( $prefs['specific_topic_completed']['creds'] ) && isset( $prefs['specific_topic_completed']['creds'][$hook_index] ) &&
                        !empty( $prefs['specific_topic_completed']['log'] ) && !empty( $prefs['specific_topic_completed']['log'][$hook_index] ) &&
                        !empty( $prefs['specific_topic_completed']['select_topic'] ) && isset( $prefs['specific_topic_completed']['select_topic'][$hook_index] )
                     ){ 


                        if ($this->over_hook_limit('specific_topic_completed', 'learndash_topic_complete', $args['user']->ID))
                        return;


                        // Make sure this is unique event
                        if ( $this->core->has_entry( 'learndash_topic_complete', $args['topic']->ID, $args['user']->ID) ) return;


                        if( in_array( 'topic' , $prefs['specific_topic_completed']['select_option'] ) && !empty($prefs['specific_topic_completed']['creds'][$hook_index]) ){
                            
                                $this->core->add_creds(
                                    'learndash_topic_complete',
                                    $args['user']->ID,
                                    $prefs['specific_topic_completed']['creds'][$hook_index],
                                    $prefs['specific_topic_completed']['log'][$hook_index],
                                    $args['topic']->ID,
                                    array('ref_type' => 'post'),
                                    $this->mycred_type
                                );

                      }
                  
                   }

                }


                elseif ( isset($prefs['check_specific_hook']) && $prefs['check_specific_hook'] == '1' && !empty( $prefs['specific_topic_completed']['select_topic'] ) && in_array( 'tags' , $prefs['specific_topic_completed']['select_option'] ) && !empty( $prefs['specific_topic_completed']['creds'][$hook_index] ) ) {

              

                    if ($this->over_hook_limit('specific_topic_completed', 'learndash_topic_complete', $args['user']->ID))
                        return;

                     // Make sure this is unique event
                        if ( $this->core->has_entry( 'learndash_topic_complete', $args['topic']->ID, $args['user']->ID) ) return;

                    
                        $topic_tag_id = $prefs['specific_topic_completed']['select_topic'][$hook_index];

                        $topic_post = get_posts(array(
                        'post_type' => 'sfwd-topic',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                        array(
                          'taxonomy' => 'ld_topic_tag',
                          'field' => 'term_id', 
                          'terms' => $topic_tag_id, /// Where term_id of Term 1 is "1".
                          'include_children' => false
                        )
                    )
                  ));

                   foreach ( $topic_post as $topics ) {
                            if ( $topics->ID == $args['topic']->ID ) {
                                         $this->core->add_creds(
                                        'learndash_topic_complete',
                                        $args['user']->ID,
                                        $prefs['specific_topic_completed']['creds'][$hook_index],
                                        $prefs['specific_topic_completed']['log'][$hook_index],
                                        $args['topic']->ID,
                                        array('ref_type' => 'post'),
                                        $this->mycred_type
                                    );
                            }
                    }

                   }

                 
                   elseif ( isset($prefs['check_specific_hook']) && $prefs['check_specific_hook'] == '1' && !empty( $prefs['specific_topic_completed']['select_topic'] ) && in_array( 0 , $prefs['specific_topic_completed']['select_topic'] )   ) {


                       $hook_index = array_search( 0, $prefs['specific_topic_completed']['select_topic'] );

                       if ( $hook_index === false ) {
                        
                        foreach ( $this->prefs['specific_topic_completed']['select_topic'] as $key => $value ) {
                            
                            if( $this->prefs['specific_topic_completed']['select_topic'][$key] == $args['topic']->ID && $value == 0 ) {
                                $hook_index = $key;
                            }
                        }
                    }


                      if (  
                    !empty( $prefs['specific_topic_completed']['creds'] ) && isset( $prefs['specific_topic_completed']['creds'][$hook_index] ) &&
                    !empty( $prefs['specific_topic_completed']['log'] ) && !empty( $prefs['specific_topic_completed']['log'][$hook_index] ) &&
                    !empty( $prefs['specific_topic_completed']['select_topic'] ) && isset( $prefs['specific_topic_completed']['select_topic'][$hook_index] ) ) {
                 
                    
                    $terms_ids = $this->mycred_ld_get_term_ids( $topic_id, 'ld_topic_tag' );


                       if ($this->over_hook_limit('specific_topic_completed', 'learndash_topic_complete', $args['user']->ID))
                        return;

                       // Make sure this is unique event
                        if ( $this->core->has_entry( 'learndash_topic_complete', $args['topic']->ID, $args['user']->ID) ) return;

                        

                        if ( ! empty( $terms_ids )  ) {
                                     $this->core->add_creds(
                                    'learndash_topic_complete',
                                    $args['user']->ID,
                                    $prefs['specific_topic_completed']['creds'][$hook_index],
                                    $prefs['specific_topic_completed']['log'][$hook_index],
                                    $args['topic']->ID,
                                    array('ref_type' => 'post'),
                                    $this->mycred_type
                            );
                        }

                        else {

                             $this->core->add_creds(
                        'learndash_topic_complete',
                        $args['user']->ID,
                        $prefs['creds'],
                        $prefs['log'],
                        $args['topic']->ID,
                        array('ref_type' => 'post'),
                        $this->mycred_type
                    );
                            
                        }
                    }
                       
                  }


                else {


                       if ($this->over_hook_limit('', 'learndash_topic_complete', $args['user']->ID))
                        return;

                       if ( $this->core->has_entry( 'learndash_topic_complete', $args['topic']->ID, $args['user']->ID) ) return;
                        
                     $this->core->add_creds(
                        'learndash_topic_complete',
                        $args['user']->ID,
                        $prefs['creds'],
                        $prefs['log'],
                        $args['topic']->ID,
                        array('ref_type' => 'post'),
                        $this->mycred_type
                    );

                  }

            }

             public function specific_field_name( $field = '' ) {

                $hook_prefs_key = 'mycred_pref_hooks';

               if ( is_array( $field ) ) {
                   $array = array();
                   foreach ( $field as $parent => $child ) {
                       if ( ! is_numeric( $parent ) )
                           $array[] = $parent;
   
                       if ( ! empty( $child ) && !is_array( $child ) )
                           $array[] = $child;
                   }
                   $field = '[' . implode( '][', $array ) . ']';
               }
               else {
                   $field = '[' . $field . ']';
               }

               $option_id = 'mycred_pref_hooks';
           if ( ! $this->is_main_type )
               $option_id = $option_id . '_' . $this->mycred_type;
   
               return $option_id . '[hook_prefs]['. $this->id . ']'  . $field . '[]';
   
           }

             public function mycred_learndash_arrange_data( $specific_hook_data ){
              
                $hook_data = array();
                foreach ( $specific_hook_data['creds'] as $key => $value ) {
                    $hook_data[$key]['creds']      = $value;
                    $hook_data[$key]['log']        = $specific_hook_data['log'][$key];
                    $hook_data[$key]['select_topic'] = $specific_hook_data['select_topic'][$key] ?? '';
                    $hook_data[$key]['select_tag'] = $specific_hook_data['select_tag'][$key] ?? '';
                    $hook_data[$key]['select_option'] = $specific_hook_data['select_option'][$key];
    
                }
                return $hook_data;
            
            }

           /**
             * Preferences for LearnDash
             */
            public function preferences() {
                $prefs = $this->prefs;


                ?>

                <!-- General Topic Complete Starts -->

                <div class="hook-instance">
                    <h3><?php esc_html_e( 'General', 'mycred' ); ?></h3>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id( 'creds' )); ?>"><?php echo esc_html($this->core->plural()); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->field_name( 'creds' )); ?>" id="<?php echo esc_attr($this->field_id( 'creds' )); ?>" value="<?php echo esc_attr($this->core->number( $prefs['creds'] )); ?>" class="form-control" />
                            </div>
                        </div>

                       

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id('log' )); ?>"><?php esc_attr_e('Log Template', 'mycred'); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->field_name( 'log' )); ?>" id="<?php echo esc_attr($this->field_id( 'log' )); ?>" value="<?php echo esc_attr( $prefs['log'] ); ?>" class="form-control" />
                                <span class="description"><?php echo esc_html($this->available_template_tags(array('general', 'post'))); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General Topic Complete Ends -->

                <!-- Specific Topic Complete Starts -->

                <?php 


                     $topic_complete_data = array(
                     array(
                        'creds' => 0,
                        'log' => __('%plural% for Completing a Specific Topic', 'mycred-learndash'),
                        'limit' => '0/x',
                        'select_option' => 0,
                        'select_topic' => 0,
                        'select_tag' => 0,
                       
                        
                    ),
                );

                   if ( count( $prefs['specific_topic_completed']['creds'] ) > 0 ) {

                    $topic_complete_data = $this->mycred_learndash_arrange_data( $prefs['specific_topic_completed'] );
    
                }

                 $topic_field = $this->topic_name();

                   $tags = get_terms([
                    'taxonomy'  => 'ld_topic_tag',
                    'hide_empty'    => false,

                ]);

                $select_parm = array(
                    'div' => array(
                        'class' => array(),
                    ),
                    'input' => array(
                        'class' => array(),
                        'type' => array(),
                        'name' => array(),
                        'id' => array(),
                        'size' => array(),
                        'value' => array()
                    ),
                    'select' => array(
                        'name'	=> array(),
                        'class' => array(),
                        'id' => array(),
                    ),
                    'option' => array(
                        'value' => array()
                    ),
                );


                 ?>

                <div class="hook-instance" id="specific-hook">



                        <div class="row">
                            <div class="col-lg-12">
                                <div class="hook-title">
                                    <h3><?php esc_html_e( 'Specific', 'mycred' ); ?></h3>
                                </div>
                            </div>
                        </div>

                         <div class="checkbox" style="margin-bottom:14px;">
                            
                            <input type="checkbox" id="<?php echo esc_attr($this->field_id('check_specific_hook')); ?>" name="<?php echo esc_attr($this->field_name('check_specific_hook')); ?>" value="1" <?php if( $prefs['check_specific_hook'] == '1') echo "checked = 'checked'"; ?>>
                            <label for="specifichook"> Enable Specific Hook</label>
                        </div> 

                        


                        <?php 
                        foreach($topic_complete_data as $hook => $label) {


                            
  
                            ?>

                          
                    
                        <div class="topic_custom_hook_class">
                            <div class="row">


                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><?php esc_html_e( 'Select Option', 'select_option' ); ?></label>
                                        <select class="form-control  mycred-learndash-topic-options" id="user_selected" name="<?php echo esc_attr($this->specific_field_name(array('specific_topic_completed' => 'select_option'))); ?>" value=""  >
                                    
                                            <?php
                                               $array = ['topic' => 'Topics', 'tags' => 'Topic Having Tags'];



                                               foreach ($array as $key => $value)

                                                   {


                             
                                                                              
                                                $selected = isset($label['select_option']) && $label['select_option'] == esc_attr($key) ? 'selected' : '';

                                                echo '<option class="select-value" value="'.esc_attr($key).'" '. ( esc_attr($key) == $label['select_option'] && isset($label['select_option']) ? ' selected' : '') .' >'.esc_html($value).'</option>\n';

                                        

                                               
                                                   }
                                               ?>
                                        
                                            
                                        </select>
                                        
                                        
                                    </div>
                                </div>


                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><?php esc_html_e( 'User Selected Option', 'select_topic' ); ?></label>
                                        <select class="form-control user_select_topic " id="selected_option"  name="<?php echo esc_attr($this->specific_field_name(array('specific_topic_completed' => 'select_topic'))); ?>" value=""  >
                                    
                                        <?php 


                                        $selected = '';

                                        if($label['select_option'] == 'topic') {
                                            if(!empty($topic_field))
                                            foreach ($topic_field as $topic_name) {


                                            $topic_id = $topic_name->ID;
                            

                                                echo '<option class="select-value" value="'.esc_attr($topic_name->ID).'" '. ( $topic_name->ID == $label['select_topic'] ? ' selected' : '') .' >'.esc_html($topic_name->post_title).'</option>';
                                            }

                                        } elseif ($label['select_option'] == 'tags') {

                                            echo '<option value="0">Any Tag</option>';


                                        foreach($tags as $tag) {

                                            $topic_field = $this->topic_name();


                                             echo '<option class="select-value" value="'.esc_attr($tag->term_id).'" '. ( $tag->term_id == $label['select_topic'] ? ' selected' : '') .' >'.esc_html($tag->name).'</option>';

                                           
                                        }

                                    }

                                       
                                        
                                        ?>
                                            
                                        </select>
  
                                        
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($this->field_id(array('specific_topic_completed' => 'creds'))); ?>"><?php echo esc_html($this->core->plural()); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->specific_field_name(array('specific_topic_completed' => 'creds'))); ?>" id="<?php echo esc_attr($this->field_id(array('specific_topic_completed' => 'creds'))); ?>" value="<?php echo esc_attr($this->core->number( $label['creds'])); ?>" class="form-control mycred-learndash-topic-creds" />
                                    </div>
                                </div>


                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($this->field_id(array('specific_topic_completed' => 'log'))); ?>"><?php esc_html_e('Log Template', 'mycred'); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->specific_field_name(array('specific_topic_completed' => 'log'))); ?>" id="<?php echo esc_attr($this->field_id(array('specific_topic_completed' => 'log'))); ?>" value="<?php echo esc_attr($label['log']) ; ?>" class="form-control mycred-learndash-topic-log" />
                                        <span class="description"><?php echo esc_html($this->available_template_tags(array('general', 'post'))); ?></span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  field_wrapper">
                                        <div class="form-group specific-hook-actions textright" >
                                            <button class="button button-small mycred-add-specific-topic-learndash-hook add_button" id="clone_btn" type="button">Add More</button>
                                            <button class="button button-small mycred-remove-topic-specific-hook" type="button">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                    <?php 

                            }
                    
                    ?>
                </div>

                    <!-- Specific Topic Complete Ends -->

                   
                    <div class="hook-instance">
                        <h3><?php esc_html_e( 'Limit', 'mycred' ); ?></h3>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php add_filter('mycred_hook_limits', array($this, 'custom_limit')); ?>
                                    <label for="<?php echo $this->field_id( 'limit' ); ?>"><?php esc_html_e('', 'mycred'); ?></label>
                               

                                    <?php echo $this->hook_limit_setting( $this->field_name( 'limit' ), $this->field_id( 'limit' ), $prefs['limit'] ); ?>
                                </div>
                            </div>

                           
                        </div>
                   </div>
 


            <?php

            }


            function sanitise_preferences($data) {

              $data['creds'] = ( !empty( $data['creds'] ) ) ? floatval( $data['creds'] ) : $this->defaults['creds'];
              $data['check_specific_hook'] = ( !empty( $data['check_specific_hook'] ) ) ? sanitize_text_field( $data['check_specific_hook'] ) : $this->defaults['check_specific_hook'];
               $data['log'] = ( !empty( $data['log'] ) ) ? sanitize_text_field( $data['log'] ) : $this->defaults['log'];

               if ( isset( $data['limit'] ) && isset( $data['limit_by'] ) ) {
                $limit = sanitize_text_field( $data['limit'] );
                if ( $limit == '' ) $limit = 0;
                $data['limit'] = $limit . '/' . $data['limit_by'];
                unset( $data['limit_by'] );
                }

             
               foreach ( $data[ 'specific_topic_completed' ] as $data_key => $data_value ) {

                    foreach ( $data_value as $key => $value) {

                        if ( $data_key == 'creds' ) {
                            $data[ 'specific_topic_completed' ][$data_key][$key] = ( !empty( $value ) ) ? floatval( $value ) : 10;
                        }
                        else if ( $data_key == 'log' ) {
                            $data[ 'specific_topic_completed' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '%plural% for completing topic';
                        }
                        else if ( $data_key == 'select_option' ) {
                            $data[ 'specific_topic_completed' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '0';
                        }
                        else if ( $data_key == 'select_topic' ) {
                            $data[ 'specific_topic_completed' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '0';
                        }
                    }
                }

                return $data;


            }

              public function custom_limit() {
                return array(
                    'x' => __('No limit', 'mycred'),
                    'd' => __('/ Day', 'mycred'),
                    'w' => __('/ Week', 'mycred'),
                    'm' => __('/ Month', 'mycred'),
                );
            }

        }

    }

}