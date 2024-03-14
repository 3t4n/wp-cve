<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_job_bm_post_meta_application{
	
	public function __construct(){

		//meta box action for "job"
		add_action('add_meta_boxes', array($this, 'job_bm_post_meta_application'));
		add_action('save_post', array($this, 'meta_boxes_application_save'));



		}

	

	
	
	
	public function job_bm_post_meta_application($post_type){

            add_meta_box('metabox-application-data', __('Application data', 'job-board-manager'), array($this, 'meta_box_application_data'), 'application', 'normal', 'high');

		}






	public function meta_box_application_data($post) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field('application_nonce_check', 'application_nonce_check_value');
 
        // Use get_post_meta to retrieve an existing value from the database.
       // $job_bm_data = get_post_meta($post -> ID, 'job_bm_data', true);



        $settings_tabs_field = new settings_tabs_field();

		$user_id = get_post_meta($post->ID, 'user_id', true);
        $applicant_name = get_post_meta($post->ID, 'applicant_name', true);

        $job_bm_am_user_email = get_post_meta($post->ID, 'job_bm_am_user_email', true);
		$job_bm_am_apply_method = get_post_meta($post->ID, 'job_bm_am_apply_method', true);
		$job_bm_am_attachment = get_post_meta($post->ID, 'job_bm_am_attachment', true);
		$job_bm_am_resume_id = get_post_meta($post->ID, 'job_bm_am_resume_id', true);
        $application_trash = get_post_meta($post->ID, 'application_trash', true);
        $job_bm_am_job_id = get_post_meta($post->ID, 'job_bm_am_job_id', true);





        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_style( 'font-awesome-5' );
        wp_enqueue_style( 'settings-tabs' );
        wp_enqueue_script( 'settings-tabs' );





		?>
		<div class="settings-tabs vertical">

			<?php



            $args = array(
                'id'		=> 'application_trash',
                //'parent'		=> '',
                'title'		=> __('Trash','job-board-manager'),
                'details'	=> __('Application trash','job-board-manager'),
                'type'		=> 'select',
                'value'		=> $application_trash,
                'default'		=> '',
                'args'		=> array(''=>'No','yes'=>'Yes'),
            );

            $settings_tabs_field->generate_field($args);


			$args = array(
				'id'		=> 'user_id',
				//'parent'		=> '',
				'title'		=> __('User id','job-board-manager'),
				'details'	=> __('User id','job-board-manager'),
				'type'		=> 'text',
				'value'		=> $user_id,
				'default'		=> '',
				'placeholder'		=> '',
			);

			$settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'applicant_name',
                //'parent'		=> '',
                'title'		=> __('Applicant name','job-board-manager'),
                'details'	=> __('Applicant name','job-board-manager'),
                'type'		=> 'text',
                'value'		=> $applicant_name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

			$args = array(
				'id'		=> 'job_bm_am_user_email',
				//'parent'		=> '',
				'title'		=> __('Applicant email','job-board-manager'),
				'details'	=> __('Applicant email address','job-board-manager'),
				'type'		=> 'text',
				'value'		=> $job_bm_am_user_email,
				'default'		=> '',
				'placeholder'		=> '',
			);

			$settings_tabs_field->generate_field($args);


			$args = array(
				'id'		=> 'job_bm_am_job_id',
				//'parent'		=> '',
				'title'		=> __('Job id','job-board-manager'),
				'details'	=> __('Application job id','job-board-manager'),
				'type'		=> 'text',
				'value'		=> $job_bm_am_job_id,
				'default'		=> '',
				'placeholder'		=> '',
			);

			$settings_tabs_field->generate_field($args);


			$args = array(
				'id'		=> 'job_bm_am_apply_method',
				//'parent'		=> '',
				'title'		=> __('Application method','job-board-manager'),
				'details'	=> __('Application method used by user.','job-board-manager'),
				'type'		=> 'text',
				'value'		=> $job_bm_am_apply_method,
				'default'		=> '',
				'placeholder'		=> '',
			);

			$settings_tabs_field->generate_field($args);

			$args = array(
				'id'		=> 'job_bm_am_attachment',
				//'parent'		=> '',
				'title'		=> __('Attachment url','job-board-manager'),
				'details'	=> __('Application method used by user.','job-board-manager'),
				'type'		=> 'text',
				'value'		=> $job_bm_am_attachment,
				'default'		=> '',
				'placeholder'		=> '',
			);

			$settings_tabs_field->generate_field($args);


			$args = array(
				'id'		=> 'job_bm_am_resume_id',
				//'parent'		=> '',
				'title'		=> __('Resume id ','job-board-manager'),
				'details'	=> __('Application method used by user.','job-board-manager'),
				'type'		=> 'text',
				'value'		=> $job_bm_am_resume_id,
				'default'		=> '',
				'placeholder'		=> '',
			);

			$settings_tabs_field->generate_field($args);



			?>


		</div>
		<div class="clear clearfix"></div>
<?php






        //do_action('job_bm_metabox_job_data', $post);


   		}




	public function meta_boxes_application_save($post_id){

        /*
         * We need to verify this came from the our screen and with
         * proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if (!isset($_POST['application_nonce_check_value']))
            return $post_id;

        $nonce = sanitize_text_field($_POST['application_nonce_check_value']);

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'application_nonce_check'))
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        // Check the user's permissions.
        if ('page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id))
                return $post_id;

        } else {

            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        // Sanitize the user input.
        //$job_bm_am_user_email = job_bm_recursive_sanitize_arr($_POST['job_bm_am_user_email']);


        // Update the meta field.
        //update_post_meta($post_id, 'job_bm_data', $job_bm_data);

        $application_trash = isset($_POST['application_trash']) ? sanitize_text_field($_POST['application_trash']) : '';
		$user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
        $applicant_name = isset($_POST['applicant_name']) ? sanitize_text_field($_POST['applicant_name']) : '';
		$job_bm_am_user_email = isset($_POST['job_bm_am_user_email']) ? sanitize_email($_POST['job_bm_am_user_email']) : '';
		$job_bm_am_job_id = isset($_POST['job_bm_am_job_id']) ? sanitize_text_field($_POST['job_bm_am_job_id']) : '';
		$job_bm_am_apply_method = isset($_POST['job_bm_am_apply_method']) ? sanitize_text_field($_POST['job_bm_am_apply_method']) : '';
		$job_bm_am_attachment = isset($_POST['job_bm_am_attachment']) ?  sanitize_text_field($_POST['job_bm_am_attachment']) : '';
		$job_bm_am_resume_id = isset($_POST['job_bm_am_resume_id']) ? sanitize_text_field($_POST['job_bm_am_resume_id']) : '';


		if($application_trash =='yes'){
            update_post_meta($post_id, 'application_trash', $application_trash);
        }else{
            delete_post_meta($post_id, 'application_trash');
        }


		update_post_meta($post_id, 'user_id', $user_id);
        update_post_meta($post_id, 'applicant_name', $applicant_name);
		update_post_meta($post_id, 'job_bm_am_user_email', $job_bm_am_user_email);
		update_post_meta($post_id, 'job_bm_am_job_id', $job_bm_am_job_id);
		update_post_meta($post_id, 'job_bm_am_apply_method', $job_bm_am_apply_method);
		update_post_meta($post_id, 'job_bm_am_attachment', $job_bm_am_attachment);
		update_post_meta($post_id, 'job_bm_am_resume_id', $job_bm_am_resume_id);






			
					
		}
	
	}


new class_job_bm_post_meta_application();