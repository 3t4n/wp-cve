<?php
if (!defined('ABSPATH')) {
    exit; // disable direct access
}


/**
 * Register Learndash Complete Quiz between grades
 * 
 */


add_filter('mycred_setup_hooks', 'Learndash_Complete_Quiz_Percentage_Range_myCRED_Hook');


function Learndash_Complete_Quiz_Percentage_Range_myCRED_Hook($installed) {

    $installed['hook_complete_quiz_percentage_range_learndash'] = array(
        'title' => __('Complete Quiz Between Grades (LearnDash)', 'mycred'),
        'description' => __('Awards %_plural% for LearnDash actions.', 'mycred'),
        'callback' => array('myCRED_Hook_Learndash_Complete_Quiz_Percentage_Range')
    );

    return $installed;
}


/**
 * Hook for LearnDash Complete Quiz between grades
 */
add_action('mycred_load_hooks', 'mycred_load_learndash_complete_quiz_percentage_range_hook', 10);


function mycred_load_learndash_complete_quiz_percentage_range_hook() {

    if (!class_exists('myCRED_Hook_Learndash_Complete_Quiz_Percentage_Range') && class_exists('myCRED_Hook')) {


        class myCRED_Hook_Learndash_Complete_Quiz_Percentage_Range extends myCRED_Hook {  

                /**
             * Construct
             */
            function __construct($hook_prefs, $type = 'mycred_default') {
                parent::__construct(array(
                    'id' => 'hook_complete_quiz_percentage_range_learndash',
                    'defaults' => array(
                      'creds' => 0,
                      'log' => __('%plural% for Completing a Quiz between grades', 'mycred-learndash'),
                      'minimum_grade_percentage' => 0,
                      'maximum_grade_percentage' => 0,
                      'limit' => '0/x',
                      'check_specific_hook' => 0,  
                      'quiz_range_percent_grade' => array(
                       'creds' => array(),
                        'log' => array(),
                        'select_option' => array(),
                        'min_percentage_range' => array(),
                        'max_percentage_range' => array(),
                        'range_select_quiz' => array(),
                        'select_tag' => array(),
                            
                        ),
                         
                    )
                        ), $hook_prefs, $type);
            } 

            public function run() {
                 $quiz_submitted_hook = ( defined( 'LEARNDASH_VERSION' ) && version_compare( LEARNDASH_VERSION, '3.0.0', '>=' ) ? 'learndash_quiz_submitted' : 'learndash_quiz_completed' );
                 add_action( $quiz_submitted_hook, array($this,'mycred_ld_complete_quiz_between_grade_range'), 40, 2 );
                 add_action( 'wp_ajax_mycred_specific_quiz_percent_range_for_users', array( $this, 'mycred_specific_quiz_percent_range_for_users' ) );
                add_action( 'wp_ajax_nopriv_mycred_specific_quiz_percent_range_for_users', array( $this, 'mycred_specific_quiz_percent_range_for_users' ) );
            }

            public function quiz_name() {
                $query_args = array( 
                    'post_type'         =>   'sfwd-quiz', 
                    'posts_per_page'    =>   -1,
                    'orderby'           =>   'title',
                    'order'             =>   'ASC',                                        
                );
         
                $query_results = new WP_Query( $query_args );

                if( !empty( $query_results->posts ) )
                    return $query_results->posts;

                return false;

            }

            public function mycred_specific_quiz_percent_range_for_users() {
                 $prefs = $this->prefs;

                 $quiz_range_percent_grade = $this->mycred_learndash_quiz_range_arrange_data( $prefs['quiz_range_percent_grade'] );

                 if( isset($_POST['quiz']) && $_POST['quiz'] == 'quiz')  {


                     $quiz_percent_grade_range = $this->quiz_name();

                     echo json_encode($quiz_percent_grade_range);
             
                     wp_die();

                 } 

                 elseif ( isset($_POST['quiz']) && $_POST['quiz'] == 'tags'){

                      $tags = get_terms([
                      'taxonomy'  => 'ld_quiz_tag',
                      'hide_empty'    => false
                      ]);

                   echo json_encode($tags);

                    wp_die();
                    
                 } 
            }

             /**
             * Retrieves post term ids for a taxonomy
             */
            public function mycred_ld_get_term_ids( $post_id, $taxonomy ) {

            $terms = get_the_terms( $post_id, $taxonomy );

            return ( empty( $terms ) || is_wp_error( $terms ) ) ? array() : wp_list_pluck( $terms, 'term_id' );

           }

            public function mycred_ld_complete_quiz_between_grade_range($quiz_data, $current_user){


                $course_id = $quiz_data['course']->ID;
                $quiz_id = $quiz_data['quiz'];
                $score = absint( $quiz_data['percentage'] );


                 $tags = get_terms([
                    'taxonomy'  => 'ld_quiz_tag',
                    'hide_empty'    => false
                ]);

              $ref_type  = array( 'ref_type' => 'post' );

              $prefs = $this->prefs;
              $terms_ids = $this->mycred_ld_get_term_ids( $quiz_id, 'ld_quiz_tag' );

              $terms =  get_the_terms( $quiz_id, 'ld_quiz_tag');

                foreach ($terms_ids as $term_id) {
                    if( in_array( $term_id , $prefs['quiz_range_percent_grade']['range_select_quiz'] ) ) {
                        $hook_index = array_search( $term_id, $prefs['quiz_range_percent_grade']['range_select_quiz'] );     
                    }
                }

            

              if(  isset($prefs['check_specific_hook']) && $prefs['check_specific_hook'] == '1' && !empty( $prefs['quiz_range_percent_grade']['range_select_quiz'] ) && in_array( $quiz_id, $prefs['quiz_range_percent_grade']['range_select_quiz'] )  ) {


                    $hook_index = array_search( $quiz_id, $prefs['quiz_range_percent_grade']['range_select_quiz'] );


                     if (  
                        !empty( $prefs['quiz_range_percent_grade']['creds'] ) && isset( $prefs['quiz_range_percent_grade']['creds'][$hook_index] ) &&
                        !empty( $prefs['quiz_range_percent_grade']['log'] ) && !empty( $prefs['quiz_range_percent_grade']['log'][$hook_index] ) &&
                        !empty( $prefs['quiz_range_percent_grade']['range_select_quiz'] ) && isset( $prefs['quiz_range_percent_grade']['range_select_quiz'][$hook_index] )
                     ){ 


                        if ($this->over_hook_limit('quiz_range_percent_grade', 'learndash_quiz_range_percent_grade', $current_user->ID))
                        return;




                          if ( $this->core->has_entry( 'learndash_quiz_range_percent_grade',  $quiz_id, $current_user->ID) ) return;




                        if( in_array( 'quiz' , $prefs['quiz_range_percent_grade']['select_option'] ) && $score>= $prefs['quiz_range_percent_grade']['min_percentage_range'][$hook_index] && $score<= $prefs['quiz_range_percent_grade']['max_percentage_range'][$hook_index] && !empty($prefs['quiz_range_percent_grade']['creds'][$hook_index]) && $quiz_data['pass'] ){
                            
                                $this->core->add_creds(
                                    'learndash_quiz_range_percent_grade',
                                    $current_user->ID,
                                    $prefs['quiz_range_percent_grade']['creds'][$hook_index],
                                    $prefs['quiz_range_percent_grade']['log'][$hook_index],
                                    $quiz_id,
                                    array('ref_type' => 'post'),
                                    $this->mycred_type
                                );

                      }
                  
                   }

                }


                 elseif ( isset($prefs['check_specific_hook']) && $prefs['check_specific_hook'] == '1' && !empty( $prefs['quiz_range_percent_grade']['range_select_quiz'] ) && in_array( 'tags' , $prefs['quiz_range_percent_grade']['select_option'] ) && !empty( $prefs['quiz_range_percent_grade']['creds'][$hook_index] ) && $quiz_data['pass'] ) {


                    if ($this->over_hook_limit('quiz_range_percent_grade', 'learndash_quiz_range_percent_grade', $current_user->ID))
                        return;
                    
                        $quiz_tag_id = $prefs['quiz_range_percent_grade']['range_select_quiz'][$hook_index];

                        $quiz_post = get_posts(array(
                        'post_type' => 'sfwd-quiz',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                        array(
                          'taxonomy' => 'ld_quiz_tag',
                          'field' => 'term_id', 
                          'terms' => $quiz_tag_id, /// Where term_id of Term 1 is "1".
                          'include_children' => false
                        )
                    )
                  ));

                    foreach ( $quiz_post as $quizzes ) {
                            if ( $quizzes->ID == $quiz_id && $score>= $prefs['quiz_range_percent_grade']['min_percentage_range'][$hook_index] && $score<= $prefs['quiz_range_percent_grade']['max_percentage_range'][$hook_index] && $quiz_data['pass'] ) {
                                 $this->core->add_creds(
                                'learndash_quiz_range_percent_grade',
                                $current_user->ID,
                                $prefs['quiz_range_percent_grade']['creds'][$hook_index],
                                $prefs['quiz_range_percent_grade']['log'][$hook_index],
                                $quiz_id,
                                array('ref_type' => 'post'),
                                $this->mycred_type
                            );
                            }
                    }
                  }


                  elseif ( isset($prefs['check_specific_hook']) && $prefs['check_specific_hook'] == '1' && !empty( $prefs['quiz_range_percent_grade']['range_select_quiz'] ) && in_array( 0 , $prefs['quiz_range_percent_grade']['range_select_quiz'] ) && $quiz_data['pass'] ) {

                    

                     $hook_index = array_search( 0, $prefs['quiz_range_percent_grade']['range_select_quiz'] );



                       if ( $hook_index === false ) {
                        
                        foreach ( $this->prefs['quiz_range_percent_grade']['range_select_quiz'] as $key => $value ) {
                            
                            if( $this->prefs['quiz_range_percent_grade']['range_select_quiz'][$key] == $$quiz_id && $value == 0 ) {
                                $hook_index = $key;
                            }
                        }
                    }


                      if (  
                    !empty( $prefs['quiz_range_percent_grade']['creds'] ) && isset( $prefs['quiz_range_percent_grade']['creds'][$hook_index] ) &&
                    !empty( $prefs['quiz_range_percent_grade']['log'] ) && !empty( $prefs['quiz_range_percent_grade']['log'][$hook_index] ) &&
                    !empty( $prefs['quiz_range_percent_grade']['range_select_quiz'] ) && isset( $prefs['quiz_range_percent_grade']['range_select_quiz'][$hook_index] ) ) {
                 
                    
                    $terms_ids = $this->mycred_ld_get_term_ids( $quiz_id, 'ld_quiz_tag' );


                        if ($this->over_hook_limit('quiz_range_percent_grade', 'learndash_quiz_range_percent_grade', $current_user->ID))
                        return;



                          if ( $this->core->has_entry( 'learndash_quiz_range_percent_grade',  $quiz_id, $current_user->ID) ) return;

                        

                        if ( ! empty( $terms_ids ) && $quiz_data['pass'] && $score>= $prefs['quiz_range_percent_grade']['min_percentage_range'][$hook_index] && $score<= $prefs['quiz_range_percent_grade']['max_percentage_range'][$hook_index] ) {
                                     $this->core->add_creds(
                                    'learndash_quiz_complete',
                                    $current_user->ID,
                                    $prefs['quiz_range_percent_grade']['creds'][$hook_index],
                                    $prefs['quiz_range_percent_grade']['log'][$hook_index],
                                    $quiz_id,
                                    array('ref_type' => 'post'),
                                    $this->mycred_type
                            );
                        }

                        else {

                            if($quiz_data['pass'] && $score>=$prefs['minimum_grade_percentage'] && $score<=$prefs['maximum_grade_percentage']) {

                             $this->core->add_creds(
                        'learndash_quiz_range_percent_grade',
                        $current_user->ID,
                        $prefs['creds'],
                        $prefs['log'],
                        $quiz_id,
                        array('ref_type' => 'post'),
                        $this->mycred_type
                       );
                    }

                            
                        }
                     }
                  }
 

                     else {

                       

                      if ($this->over_hook_limit('', 'learndash_quiz_range_percent_grade', $args['user']->ID))
                        return;

                      if($quiz_data['pass'] && $score>=$prefs['minimum_grade_percentage'] && $score<=$prefs['maximum_grade_percentage']) {
                        
                         $this->core->add_creds(
                            'learndash_quiz_range_percent_grade',
                            $current_user->ID,
                            $prefs['creds'],
                            $prefs['log'],
                            $quiz_id,
                            array('ref_type' => 'post'),
                            $this->mycred_type
                        );

                     }
                  }

            }


            public function mycred_learndash_quiz_range_arrange_data( $specific_quiz_range_hook_data ){
              
                $hook_data = array();
                foreach ( $specific_quiz_range_hook_data['creds'] as $key => $value ) {
                    $hook_data[$key]['creds']      = $value;
                    $hook_data[$key]['log']        = $specific_quiz_range_hook_data['log'][$key];
                  
                    $hook_data[$key]['range_select_quiz'] = $specific_quiz_range_hook_data['range_select_quiz'][$key] ?? '';
                     $hook_data[$key]['min_percentage_range'] = $specific_quiz_range_hook_data['min_percentage_range'][$key];
                    $hook_data[$key]['max_percentage_range'] = $specific_quiz_range_hook_data['max_percentage_range'][$key];
                    $hook_data[$key]['select_tag'] = $specific_quiz_range_hook_data['select_tag'][$key] ?? '';
                    $hook_data[$key]['select_option'] = $specific_quiz_range_hook_data['select_option'][$key]; 
                    
                }
                return $hook_data;
            
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

             /**
             * Preferences for LearnDash
             */
            public function preferences( ) {

                $prefs = $this->prefs;

                ?>

                 <!-- general starts -->

                 <div class="hook-instance">
                    <h3><?php esc_html_e( 'General', 'mycred' ); ?></h3>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id( 'creds' )); ?>"><?php echo esc_html($this->core->plural()); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->field_name( 'creds' )); ?>" id="<?php echo esc_attr($this->field_id( 'creds' )); ?>" value="<?php echo esc_attr($this->core->number( $prefs['creds'] )); ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id( 'minimum_grade_percentage' )); ?>"><?php esc_html_e('Minimum Percent', 'minimum_grade_percentage'); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->field_name( 'minimum_grade_percentage' )); ?>" id="<?php echo esc_attr($this->field_id( 'minimum_grade_percentage' )); ?>" value="<?php echo esc_attr($this->core->number( $prefs['minimum_grade_percentage'] )); ?>" class="form-control" />  
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id( 'maximum_grade_percentage' )); ?>"><?php esc_html_e('Maximum Percent', 'maximum_grade_percentage'); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->field_name( 'maximum_grade_percentage' )); ?>" id="<?php echo esc_attr($this->field_id( 'maximum_grade_percentage' )); ?>" value="<?php echo esc_attr($this->core->number( $prefs['maximum_grade_percentage'] )); ?>" class="form-control" />  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                     <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                           <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id('log' )); ?>"><?php esc_html_e('Log Template', 'mycred'); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->field_name( 'log' )); ?>" id="<?php echo esc_attr($this->field_id( 'log' )); ?>" value="<?php echo esc_attr( $prefs['log'] ); ?>" class="form-control" />
                                <span class="description"><?php echo esc_html($this->available_template_tags(array('general', 'post'))); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                 <!-- general ends -->

                <?php

                  $quiz_range_percent_grade = array(
                     array(
                        'creds' => 0,
                        'log' => __('%plural% for Completing a Specific Quiz between grades', 'mycred-learndash'),
                        'limit' => '0/x',
                         'select_option' => 0,
                        'range_select_quiz' => 0,
                        'min_percentage_range' => 0,
                        'max_percentage_range' => 0,
                        'select_tag' => 0,
                       
                    ),
                  );

                   if ( count( $prefs['quiz_range_percent_grade']['creds'] ) > 0 ) {
                    $quiz_range_percent_grade = $this->mycred_learndash_quiz_range_arrange_data( $prefs['quiz_range_percent_grade'] );
                   }

                
                $quiz_percent_grade_range = $this->quiz_name();
                $tags = get_terms([
                    'taxonomy'  => 'ld_quiz_tag',
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
                            <label for="specifichook"><?php esc_html_e( 'Enable Specific Hook', 'mycred' ); ?></label>
                        </div> 
                    <?php 
                    foreach($quiz_range_percent_grade as $hook => $label){
                        ?>
                <div class="quiz_percent_grade_custom_hook_class">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><?php esc_html_e( 'Select Option', 'select_option' ); ?></label>
                                        <select class="form-control  mycred-learndash-quiz-percent-grade-options" id="user_selected" name="<?php echo esc_attr($this->specific_field_name(array('quiz_range_percent_grade' => 'select_option'))); ?>" value=""  >
                                            <?php
                                               $array = ['quiz' => 'Quizzes', 'tags' => 'Quiz Having Tag'];
                                               foreach ($array as $key => $value)
                                                   {
                                                   $selected = isset($label['select_option']) && $label['select_option'] == $key ? 'selected' : '';
                                             

                                                   echo '<option class="select-value" value="'.esc_attr($key).'" '. ( esc_attr($key) == $label['select_option'] && isset($label['select_option']) ? ' selected' : '') .' >'.esc_html($value).'</option>\n';
                                                   }
                                               ?>  
                                        </select>   
                                    </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><?php esc_html_e( 'User Option', 'range_select_quiz' ); ?></label>
                                        <select class="form-control user_select_percent_grade_quiz mycred-learndash-percent-grade-quiz " id="selected_option"  name="<?php echo esc_attr($this->specific_field_name(array('quiz_range_percent_grade' => 'range_select_quiz'))); ?>" value=""  >
                                    
                                        <?php 
                                        $selected = '';
                                        if($label['select_option'] == 'quiz') {
                                             if (is_array($quiz_percent_grade_range) || is_object($quiz_percent_grade_range))
                                            foreach ($quiz_percent_grade_range as $quiz_name) {
                                            $quiz_id = $quiz_name->ID;
                                                echo '<option class="select-value" value="'.esc_attr($quiz_name->ID).'" '. ( $quiz_name->ID == $label['range_select_quiz'] ? ' selected' : '') .' >'.esc_html($quiz_name->post_title).'</option>';
                                            }
                                        }

                                         elseif ($label['select_option'] == 'tags') {
                                           echo '<option value="0">Any Tag</option>';
                                        foreach($tags as $tag) {
                                            $quiz_percent_grade_range = $this->quiz_name();
                                             echo '<option class="select-value" value="'.esc_attr($tag->term_id).'" '. ( $tag->term_id == $label['range_select_quiz'] ? ' selected' : '') .' >'.esc_html($tag->name).'</option>';   
                                        }
                                    }   
                                        ?>  
                                        </select>   
                                    </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'creds'))); ?>"><?php echo esc_html($this->core->plural()); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->specific_field_name(array('quiz_range_percent_grade' => 'creds'))); ?>" id="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'creds'))); ?>" value="<?php echo esc_attr($this->core->number( $label['creds'])); ?>" class="form-control mycred-learndash-percent-grade-quiz-percent-grade-creds" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'min_percentage_range'))); ?>"><?php esc_html_e('Min Percent', 'min_percentage_range'); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->specific_field_name(array('quiz_range_percent_grade' => 'min_percentage_range'))); ?>" id="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'min_percentage_range'))); ?>" value="<?php echo esc_attr($this->core->number( $label['min_percentage_range'])); ?>" class="form-control mycred-learndash-min-grade-quiz-min-grade-creds" />  
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label  for="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'max_percentage_range'))); ?>"><?php esc_html_e('Max Percent', 'max_percentage_range'); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->specific_field_name(array('quiz_range_percent_grade' => 'max_percentage_range'))); ?>" id="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'max_percentage_range'))); ?>" value="<?php echo esc_attr($this->core->number( $label['max_percentage_range'])); ?>" class="form-control mycred-learndash-max-grade-quiz-max-grade-creds" />

                               
                                
                            </div>
                         </div>
                          
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'log'))); ?>"><?php esc_html_e('Log Template', 'mycred'); ?></label>
                                    <input type="text" name="<?php echo esc_attr($this->specific_field_name(array('quiz_range_percent_grade' => 'log'))); ?>" id="<?php echo esc_attr($this->field_id(array('quiz_range_percent_grade' => 'log'))); ?>" value="<?php echo esc_attr($label['log']) ; ?>" class="form-control mycred-learndash-quiz-percent-grade-log" />
                                    <span class="description"><?php echo esc_html($this->available_template_tags(array('general', 'post'))); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  field_wrapper">
                                    <div class="form-group specific-hook-actions textright" >
                                        <button class="button button-small mycred-add-specific-percent-grade-quiz-learndash-hook add_button" id="clone_btn" type="button">Add More</button>
                                        <button class="button button-small mycred-remove-percent-grade-quiz-specific-hook" type="button">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                </div>
                    <?php 

                            }
                    
                    ?>
            </div>
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

             
               foreach ( $data[ 'quiz_range_percent_grade' ] as $data_key => $data_value ) {

                    foreach ( $data_value as $key => $value) {

                        if ( $data_key == 'creds' ) {
                            $data[ 'quiz_range_percent_grade' ][$data_key][$key] = ( !empty( $value ) ) ? floatval( $value ) : 10;
                        }
                        else if ( $data_key == 'log' ) {
                            $data[ 'quiz_range_percent_grade' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '%plural% for completing course between grades.';
                        }
                       
                        else if ( $data_key == 'min_percentage_range' ) {
                            $data[ 'quiz_range_percent_grade' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '0';
                        }

                          else if ( $data_key == 'max_percentage_range' ) {
                            $data[ 'quiz_range_percent_grade' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '0';
                        }

                        else if ( $data_key == 'range_select_quiz' ) {
                            $data[ 'quiz_range_percent_grade' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '0';
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
