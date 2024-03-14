<?php
if (!defined('ABSPATH')) {
    exit; // disable direct access
}

/**
 * Register Learndash Course Enrollment Hook
 */
add_filter('mycred_setup_hooks', 'Learndash_Course_Enrollment_myCRED_Hook');

function Learndash_Course_Enrollment_myCRED_Hook($installed) {

    $installed['hook_course_enrollment_learndash'] = array(
        'title' => __('Enrolling in a Course (LearnDash)', 'mycred'),
        'description' => __('Awards %_plural% for LearnDash actions.', 'mycred'),
        'callback' => array('myCRED_Hook_Learndash_Course_Enrollment')
    );

    return $installed;
}


/**
 * Hook for LearnDash Course Enrollment
 */
add_action('mycred_load_hooks', 'mycred_load_learndash_course_enrollment_hook', 10);

function mycred_load_learndash_course_enrollment_hook() {

     if (!class_exists('myCRED_Hook_Learndash_Course_Enrollment') && class_exists('myCRED_Hook')) {

        class myCRED_Hook_Learndash_Course_Enrollment extends myCRED_Hook {

            /**
             * Construct
             */
            function __construct($hook_prefs, $type = 'mycred_default') {
                parent::__construct(array(
                    'id' => 'hook_course_enrollment_learndash',
                    'defaults' => array(
                        'creds' => 0,
                        'log' => __('%plural% for Enrolling in a General Course', 'mycred-learndash'), 
                        'limit' => '0/x',
                        'check_specific_hook' => 0,
                        'course_enrollment' => array(
                        'creds' => array(),
                        'log' => array(),
                        'course_enrollment_select_course' => array(), 
                            
                        ),
                    )
                        ), $hook_prefs, $type);
            }

            /**
             * Run
             */
            public function run() {
     
                // Course Enrollment
                
                add_action('learndash_update_course_access', array($this, 'learndash_enroll_course'), 10, 4);
             
            }

             public function learndash_enroll_course($user_id, $course_id, $course_access_list, $remove){


                $ref_type  = array( 'ref_type' => 'post' );
                $prefs = $this->prefs;


                 if( $prefs['check_specific_hook'] == '1' && !empty( $prefs['course_enrollment']['course_enrollment_select_course'] ) && in_array( $course_id, $prefs['course_enrollment']['course_enrollment_select_course'] ) ) {
                       $hook_index = array_search( $course_id, $prefs['course_enrollment']['course_enrollment_select_course'] );

              if (  
                    !empty( $prefs['course_enrollment']['creds'] ) && isset( $prefs['course_enrollment']['creds'][$hook_index] ) &&
                    !empty( $prefs['course_enrollment']['log'] ) && !empty( $prefs['course_enrollment']['log'][$hook_index] ) &&
                    !empty( $prefs['course_enrollment']['course_enrollment_select_course'] ) && isset( $prefs['course_enrollment']['course_enrollment_select_course'][$hook_index] )
                 ){ 


                    if ($this->over_hook_limit('course_enrollment', 'learndash_course_enrollment', $user_id))
                        return;


                    // Make sure this is unique event
                    if ( $this->core->has_entry( 'learndash_course_enrollment', $course_id, $user_id) ) return;


                    if(!empty($prefs['course_enrollment']['creds'][$hook_index]) )
                            $this->core->add_creds(
                                'learndash_course_enrollment',
                                $user_id,
                                $prefs['course_enrollment']['creds'][$hook_index],
                                $prefs['course_enrollment']['log'][$hook_index],
                                $course_id,
                                array('ref_type' => 'post'),
                                $this->mycred_type
                            );

                  }
            } 

            else {

                $general_course = $hooks['hook_prefs']['hook_course_enrollment_learndash'];

                if ($this->over_hook_limit('course_enrollment', 'learndash_course_enrollment', $user_id)) {
                                return;
                }

                    $this->core->add_creds(
                        'learndash_course_enrollment',
                        $user_id,
                        $prefs['creds'],
                        $prefs['log'],
                        $course_id,
                        array('ref_type' => 'post'),
                        $this->mycred_type
                    );
            }

            }


             public function course_name() {
                $query_args = array( 
                    'post_type'         =>   'sfwd-courses',
                    'posts_per_page'    =>   -1,
                    'orderby'           =>   'title',
                    'order'             =>   'ASC',
                                        
                );
         
                $query_results = new WP_Query( $query_args );

                if( !empty( $query_results->posts ) )
                    return $query_results->posts;

                return false;

            }

            public function enroll_course_field_name( $field = '' ) {

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


             public function mycred_learndash_arrange_data( $specific_enroll_course_hook_data){
              
                $hook_data = array();
                foreach ( $specific_enroll_course_hook_data['creds'] as $key => $value ) {
                    $hook_data[$key]['creds']      = $value;
                    $hook_data[$key]['log']        = $specific_enroll_course_hook_data['log'][$key];
                   
                    $hook_data[$key]['select_course'] = $specific_enroll_course_hook_data['select_course'][$key] ?? '';
                    $hook_data[$key]['course_enrollment_select_course'] = $specific_enroll_course_hook_data['course_enrollment_select_course'][$key] ?? '';
                   
                    
                }
                return $hook_data;
            
            }

            /**
             * Preferences for LearnDash
             */
            public function preferences() {
                $prefs = $this->prefs;
                ?>

                <!-- General Course Enrollment Starts -->

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
                                <label for="<?php echo esc_attr($this->field_id('log' )); ?>"><?php esc_html_e('Log Template', 'mycred'); ?></label>
                                <input type="text" name="<?php echo esc_attr($this->field_name( 'log' )); ?>" id="<?php echo esc_attr($this->field_id( 'log' )); ?>" value="<?php echo esc_attr( $prefs['log'] ); ?>" class="form-control" />
                                <span class="description"><?php echo esc_html($this->available_template_tags(array('general', 'post'))); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General Course Enrollment Ends -->

                <!-- Specific Course Enrollment Starts -->

                <?php 


                  $course_enroll_data = array(
                     array(
                        'creds' => 0,
                        'log' => __('%plural% for Enrolling in Specific Course', 'mycred-learndash'),
                        
                        'course_enrollment_select_course' => 0,
                       
                    ),
                );

                  


                   if ( count( $prefs['course_enrollment']['creds'] ) > 0 ) {

                      $course_enroll_data = $this->mycred_learndash_arrange_data( $prefs['course_enrollment'] );
    
                   }

                  $course_enrolled_field = $this->course_name();

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
                        foreach($course_enroll_data as $hook => $label){


                            ?>

                        <div class="course_custom_hook_class">
                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($this->field_id(array('course_enrollment' => 'creds'))); ?>"><?php echo esc_html($this->core->plural()); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->enroll_course_field_name(array('course_enrollment' => 'creds'))); ?>" id="<?php echo esc_attr($this->field_id(array('course_enrollment' => 'creds'))); ?>" value="<?php echo esc_attr($this->core->number( $label['creds'])); ?>" class="form-control mycred-learndash-course-creds" />
                                    </div>
                                </div>

                              

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><?php esc_html_e( 'Select Specific Course', 'course_enrollment_select_course' ); ?></label>
                                        <select class="form-control mycred-learndash-course " name="<?php echo esc_attr($this->enroll_course_field_name(array('course_enrollment' => 'course_enrollment_select_course'))); ?>" value=""  >
                                    
                                        <?php 
                                        $selected = '';
                                        if (is_array($course_enrolled_field) || is_object($course_enrolled_field))
                                        foreach ($course_enrolled_field as $course_name) {

                                        $course_id = $course_name->ID;

                                            echo '<option class="select-value" value="'.esc_attr($course_name->ID).'" '. ( $course_name->ID == $label['course_enrollment_select_course'] ? ' selected' : '') .' >'.esc_html($course_name->post_title).'</option>';
                                        }
                                        
                                        ?>
                                            
                                        </select>
                                        
                                        
                                        
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($this->field_id(array('course_enrollment' => 'log'))); ?>"><?php esc_html_e('Log Template', 'mycred'); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->enroll_course_field_name(array('course_enrollment' => 'log'))); ?>" id="<?php echo esc_attr($this->field_id(array('course_enrollment' => 'log'))); ?>" value="<?php echo esc_attr($label['log']) ; ?>" class="form-control mycred-learndash-course-log" />
                                        <span class="description"><?php echo esc_html($this->available_template_tags(array('general', 'post'))); ?></span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  field_wrapper">
                                        <div class="form-group specific-hook-actions textright" >
                                            <button class="button button-small mycred-add-specific-course-learndash-hook add_button" id="clone_btn" type="button">Add More</button>
                                            <button class="button button-small mycred-remove-course-specific-hook" type="button">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                    <?php 

                            }
                    
                    ?>
                </div>

                 <!-- Specific Course Enrollment Ends -->

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


                foreach ( $data[ 'course_enrollment' ] as $data_key => $data_value ) {

                        foreach ( $data_value as $key => $value) {

                            if ( $data_key == 'creds' ) {
                                $data[ 'course_enrollment' ][$data_key][$key] = ( !empty( $value ) ) ? floatval( $value ) : 10;
                            }
                            else if ( $data_key == 'log' ) {
                                $data[ 'course_enrollment' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '%plural% for enrolling in a course.';
                            }
                            
                            else if ( $data_key == 'select_course' ) {
                                $data[ 'course_enrollment' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '0';
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