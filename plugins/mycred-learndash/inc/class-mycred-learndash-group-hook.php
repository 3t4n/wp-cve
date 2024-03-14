<?php
if (!defined('ABSPATH')) {
    exit; // disable direct access
}

/**
 * Register Learndash Join Group Hook
 */
add_filter('mycred_setup_hooks', 'Learndash_Join_Group_myCRED_Hook');

function Learndash_Join_Group_myCRED_Hook($installed) {

    $installed['hook_join_group_learndash'] = array(
        'title' => __('Join Group (LearnDash)', 'mycred'),
        'description' => __('Awards %_plural% for LearnDash actions.', 'mycred'),
        'callback' => array('myCRED_Hook_Learndash_Join_Group')
    );

    return $installed;
}

/**
 * Hook for LearnDash Join Group
 */
add_action('mycred_load_hooks', 'mycred_load_learndash_join_group_hook', 10);

function mycred_load_learndash_join_group_hook() {

    if (!class_exists('myCRED_Hook_Learndash_Join_Group') && class_exists('myCRED_Hook')) {

        class myCRED_Hook_Learndash_Join_Group extends myCRED_Hook {

             /**
             * Construct
             */
            function __construct($hook_prefs, $type = 'mycred_default') {
                parent::__construct(array(
                    'id' => 'hook_join_group_learndash',
                    'defaults' => array(
                        'creds' => 0,
                        'log' => __('%plural% for Joining a Group', 'mycred-learndash'), 
                        'limit' => '0/x',
                        'check_specific_hook' => 0,
                        'join_group' => array(
                        'creds' => array(),
                        'log' => array(),
                        'join_group_select_group' => array(), 
                            
                        ),
                    )
                        ), $hook_prefs, $type);
            }

             /**
             * Run
             */
            public function run() {
     

                // Join Group
                    
                add_action('ld_added_group_access', array($this, 'learndash_join_group'), 10, 2);

             
            }

            public function learndash_join_group($user_id, $group_id) {

                $ref_type  = array( 'ref_type' => 'post' );


                $prefs = $this->prefs;

                 if( $prefs['check_specific_hook'] == '1' && !empty( $prefs['join_group']['join_group_select_group'] ) && in_array( $group_id, $prefs['join_group']['join_group_select_group'] ) ) {
                   $hook_index = array_search( $group_id, $prefs['join_group']['join_group_select_group'] );


              if (  
                    !empty( $prefs['join_group']['creds'] ) && isset( $prefs['join_group']['creds'][$hook_index] ) &&
                    !empty( $prefs['join_group']['log'] ) && !empty( $prefs['join_group']['log'][$hook_index] ) &&
                    !empty( $prefs['join_group']['join_group_select_group'] ) && isset( $prefs['join_group']['join_group_select_group'][$hook_index] )
                 ){ 


                    // Make sure this is unique event
                    if ( $this->core->has_entry( 'learndash_join_group', $group_id, $user_id) ) return;


                    if(!empty($prefs['join_group']['creds'][$hook_index]) )
                            $this->core->add_creds(
                                'learndash_join_group',
                                $user_id,
                                $prefs['join_group']['creds'][$hook_index],
                                $prefs['join_group']['log'][$hook_index],
                                $group_id,
                                array('ref_type' => 'post'),
                                $this->mycred_type
                            );
                  }
            } 

        
                else {

                    $general_group = $hooks['hook_prefs']['hook_join_group_learndash'];

                    if ($this->over_hook_limit('join_group', 'learndash_join_group', $user_id)) {
                                    return;
                    }

                        $this->core->add_creds(
                            'learndash_join_group',
                            $user_id,
                            $prefs['creds'],
                            $prefs['log'],
                            $group_id,
                            array('ref_type' => 'post'),
                            $this->mycred_type
                        );
                }


            }

             

             public function group_name() {
                $query_args = array( 
                    'post_type'         =>   'groups',
                    'posts_per_page'    =>   -1,
                    'orderby'           =>   'title',
                    'order'             =>   'ASC',
                                           
                );
         
                $query_results = new WP_Query( $query_args );

                if( !empty( $query_results->posts ) )
                    return $query_results->posts;

                return false;

            }

             public function join_group_field_name( $field = '' ) {

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


            public function mycred_learndash_arrange_data( $specific_join_group_hook_data){
              
                $hook_data = array();
                foreach ( $specific_join_group_hook_data['creds'] as $key => $value ) {
                    $hook_data[$key]['creds']      = $value;
                    $hook_data[$key]['log']        = $specific_join_group_hook_data['log'][$key];
                   
                    $hook_data[$key]['select_group'] = $specific_join_group_hook_data['select_group'][$key] ?? '';
                    $hook_data[$key]['join_group_select_group'] = $specific_join_group_hook_data['join_group_select_group'][$key] ?? '';
                        
                }
                return $hook_data;
            
            }

           

            /**
             * Preferences for LearnDash
             */
            public function preferences() {
                $prefs = $this->prefs;
                ?>

                <!-- General Join Group Starts -->

                <div class="group-hook-instance">
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

                <!-- General Join Group Ends -->

                <!-- Specific Join Group Starts -->

                <?php 


                  $join_group_data = array(
                     array(
                        'creds' => 0,
                        'log' => __('%plural% for Joining Group', 'mycred-learndash'),
                        
                        'join_group_select_group' => 0,
                       
                    ),
                );

                  


                   if ( count( $prefs['join_group']['creds'] ) > 0 ) {

                     $join_group_data = $this->mycred_learndash_arrange_data( $prefs['join_group'] );
    
                   }

                 $join_group_field = $this->group_name();

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

                <div class="group-hook-instance" id="specific-hook">

                   


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="hook-title">
                                    <h3><?php esc_html_e( 'Specific', 'mycred' ); ?></h3>
                                </div>
                            </div>
                        </div>
                       
                         <div class="checkbox" style="margin-bottom:14px;">
                            
                            <input type="checkbox" id="<?php echo esc_attr($this->field_id('check_specific_hook')); ?>" name="<?php echo esc_attr($this->field_name('check_specific_hook')); ?>" value="1" <?php if( $prefs['check_specific_hook'] == '1') echo "checked = 'checked'"; ?>>
                            <label for="specifichook"> <?php esc_html_e( 'Enable Specific Hook', 'mycred' ); ?></label>
                        </div> 



                        <?php 
                        foreach($join_group_data as $hook => $label){


                            ?>

                        <div class="group_custom_hook_class">
                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($this->field_id(array('join_group' => 'creds'))); ?>"><?php echo esc_html($this->core->plural()); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->join_group_field_name(array('join_group' => 'creds'))); ?>" id="<?php echo esc_attr($this->field_id(array('join_group' => 'creds'))); ?>" value="<?php echo esc_attr($this->core->number( $label['creds'])); ?>" class="form-control mycred-learndash-group-creds" />
                                    </div>
                                </div>

                              

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><?php esc_html_e( 'Select Specific Group', 'join_group_select_group' ); ?></label>
                                        <select class="form-control mycred-learndash-group " name="<?php echo esc_attr($this->join_group_field_name(array('join_group' => 'join_group_select_group'))); ?>" value=""  >
                                    
                                        <?php 
                                        $selected = '';
                                        if (is_array($join_group_field) || is_object($join_group_field))
                                        foreach ($join_group_field as $group_name) {

                                        $group_id = $group_name->ID;

                                            echo '<option class="select-value" value="'.esc_attr($group_name->ID).'" '. ( $group_name->ID == $label['join_group_select_group'] ? ' selected' : '') .' >'.esc_html($group_name->post_title).'</option>';
                                        }
                                        
                                        ?>
                                            
                                        </select>
                                        
                                        
                                        
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($this->field_id(array('join_group' => 'log'))); ?>"><?php esc_html_e('Log Template', 'mycred'); ?></label>
                                        <input type="text" name="<?php echo esc_attr($this->join_group_field_name(array('join_group' => 'log'))); ?>" id="<?php echo esc_attr($this->field_id(array('join_group' => 'log'))); ?>" value="<?php echo esc_attr($label['log']) ; ?>" class="form-control mycred-learndash-group-log" />
                                        <span class="description"><?php echo esc_html($this->available_template_tags(array('general', 'post'))); ?></span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  field_wrapper">
                                        <div class="form-group group-specific-hook-actions textright" >
                                            <button class="button button-small mycred-add-specific-group-learndash-hook add_button" id="clone_btn" type="button">Add More</button>
                                            <button class="button button-small mycred-remove-group-specific-hook" type="button">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                    <?php 

                            }
                    
                    ?>
                </div>

                 <!-- Specific Group Join Ends -->

                 <div class="group-hook-instance">
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


                foreach ( $data[ 'join_group' ] as $data_key => $data_value ) {

                        foreach ( $data_value as $key => $value) {

                            if ( $data_key == 'creds' ) {
                                $data[ 'join_group' ][$data_key][$key] = ( !empty( $value ) ) ? floatval( $value ) : 10;
                            }
                            else if ( $data_key == 'log' ) {
                                $data[ 'join_group' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '%plural% for joining a group';
                            }
                            
                            else if ( $data_key == 'select_group' ) {
                                $data[ 'join_group' ][$data_key][$key] = ( !empty( $value ) ) ? sanitize_text_field( $value ) : '0';
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
