<?php
/*
Plugin Name: Contact Form 7 - Submission id
Description: Adds a field for an unique submission id.
Version: 2.4.0
Author: Ewald Harmsen
Text Domain: cf7-submission-id
*/

require __DIR__ . '/includes/submission_id.php';

/**
 * Function init plugin
**/
function cf7_submission_id_init(){
    wpcf7_add_form_tag('submission_id','cf7_submission_id_uid_form_tag_handler', true );
    wpcf7_add_form_tag('submission_id_hidden','cf7_submission_id_uid_form_tag_handler', true );
    add_action( 'admin_notices', 'cf7_submission_id_admin_notice' );
}
add_action( 'plugins_loaded', 'cf7_submission_id_init' , 20 );

//Enqueue javascript
add_action('wp_enqueue_scripts', 'cf7_submission_id_js');
function cf7_submission_id_js() {
	global $post;
	//Only enque when needed
	if($post != null and has_shortcode( $post->post_content, 'contact-form-7')){
		wp_enqueue_script('cf7_submission_id_script',plugins_url('/includes/submission_id.js', __FILE__),"",'2.4.0', true);
		wp_localize_script( 'cf7_submission_id_script', 'cf7_submission_id_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
}

/* Tag generator */
add_action( 'wpcf7_admin_init', 'cf7_submission_id_add_tag_generator_id_field', 30 );
function cf7_submission_id_add_tag_generator_id_field() {
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add( 'submission_id', __( 'submission id', 'contact-form-7' ), 'cf7_submission_id_tag_uid_field' );
}

//Find the correct value to store in form data
add_filter( 'wpcf7_posted_data', 'cf7_submission_update_id', 1);
function cf7_submission_update_id( $posted_data ) {
	//error_log(print_r($posted_data,true));
	//Find the field in the posted data
	$fieldname = "";
	//loop over the data to find a submission field
	foreach ($posted_data as $key => $val) {
		//submission_id-716
		//If the fieldname starts with submission_id, store the full name
		if(substr($key, 0, strlen("submission_id")) === "submission_id"){
			$fieldname = $key;
		}
	}
	
	//there is a submission id found
	if($fieldname != ""){
		//get the current counter
		$val = get_post_meta( $_POST['_wpcf7'], "cf7_submission_id_COUNTER",true);
		
		//If there is no current counter
		if ($val == ""){
			//store the value as received from the form
			$val = intval($posted_data[$fieldname]);
		}else{
			$val += 1;
		}
		
		//Apply a filter to the number_format
		$val = apply_filters( 'cf7_submission_id_filter', $val);
		
		//Replace the data in the posted values
		$posted_data[$fieldname] = $val;
	}

    return $posted_data;
};

//only update the counter on succes
add_filter("wpcf7_submit", function() {
    $submission = WPCF7_Submission::get_instance();
    $invalid_fields = $submission->get_invalid_fields();

    if ( empty( $invalid_fields ) ) {
        //get the current counter
		$val = get_post_meta( $_POST['_wpcf7'], "cf7_submission_id_COUNTER",true);
		
		//If there is no current counter
		if ($val == ""){
			//store the value as received from the form
			$val = intval($posted_data[$fieldname]);
		}else{
			$val += 1;
		}
		//Save the value
		update_post_meta($_POST['_wpcf7'], "cf7_submission_id_COUNTER", $val );
    }
});

//Update the counter when a form is submitted, and send the value back to the form so the page doesn't have to be reloaded
add_action('wp_ajax_update_cf7_submission_id', 'cf7_submission_id_submit');
add_action('wp_ajax_nopriv_update_cf7_submission_id', 'cf7_submission_id_submit');
function cf7_submission_id_submit() { 
	if( isset($_POST['formid']) ){
		//Retrieve current value from database, and add 1
		$val = intval(get_post_meta( $_POST['formid'], "cf7_submission_id_COUNTER",true))+1;
		
		//Send value back to js, via AJAX
		wp_send_json(apply_filters( 'cf7_submission_id_filter', $val));
	}
};

/**
 * Verify Contact Form 7 dependencies.
 */
function cf7_submission_id_admin_notice() {
    if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
        $wpcf7_path = WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php';
        $wpcf7_data = get_plugin_data( $wpcf7_path, false, false );

        $version = $wpcf7_data['Version'];

        // If Contact Form 7 version is < 4.2.0.
        if ( $version < 4.2 ) {
            ?>

            <div class="error notice">
                <p>
                    <?php esc_html_e( "Error: Please update Contact Form 7.", 'cf7-mollie' );?>
                </p>
            </div>

            <?php
        }
    } else {
        // If Contact Form 7 isn't installed and activated, throw an error.
        $wpcf7_path = WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php';
        $wpcf7_data = get_plugin_data( $wpcf7_path, false, false );
        ?>

        <div class="error notice">
            <p>
                <?php esc_html_e( 'Error: Please install and activate Contact Form 7.', 'cf7-mollie' );?>
            </p>
        </div>

        <?php
    }
}