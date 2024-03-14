<?php
/**
 * Plugin Name: Contact Form 7 - Infusionsoft Add-on
 * Description: An add-on for Contact Form 7 that provides a way to capture leads, tag customers, and send contact form data to InfusionSoft.
 * Version: 1.2.2
 * Author: Ryan Nevius
 * Author URI: http://www.ryannevius.com
 * License: GPLv3
 */

require_once('src/isdk.php');
require_once('cf7-infusionsoft-options.php');
require_once('cf7-infusionsoft-modules.php');

/**
 * Verify CF7 dependencies.
 */
function cf7_infusionsoft_admin_notice() {
	// Verify that CF7 is active and updated to the required version (currently 3.9.0)
	if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
		$wpcf7_path = plugin_dir_path( dirname(__FILE__) ) . 'contact-form-7/wp-contact-form-7.php';
		$wpcf7_plugin_data = get_plugin_data( $wpcf7_path, false, false);
		$wpcf7_version = (int)preg_replace('/[.]/', '', $wpcf7_plugin_data['Version']);
		// CF7 drops the ending ".0" for new major releases (e.g. Version 4.0 instead of 4.0.0...which would make the above version "40")
		// We need to make sure this value has a digit in the 100s place.
		if ( $wpcf7_version < 100 ) {
			$wpcf7_version = $wpcf7_version * 10;
		}
		// If CF7 version is < 3.9.0
		if ( $wpcf7_version < 390 ) {
			echo '<div class="update-nag"><p><strong>Warning: </strong>Contact Form 7 - InfusionSoft Add-on requires that you have the latest version of Contact Form 7 installed. Please upgrade now.</p></div>';
		}
	}
	// If it's not installed and activated, throw an error
    else {
	    echo '<div class="error"><p>Contact Form 7 is not activated. Contact Form 7 must be installed and activated before you can use the InfusionSoft Add-on.</p></div>';
	}
}
add_action( 'admin_notices', 'cf7_infusionsoft_admin_notice' );


/**
 * Enqueue Scripts with CF7 Dependencies
 */
function cf7_infusionsoft_enqueue_scripts() {
    if( !function_exists('wpcf7_add_meta_boxes') ) {
	   wp_enqueue_script( 'cf7-infusionsoft-scripts', plugin_dir_url(__FILE__) . 'cf7-infusionsoft-scripts.js', array('jquery', 'wpcf7-admin-taggenerator', 'wpcf7-admin'), null, true );
    }
}
add_action( 'admin_enqueue_scripts', 'cf7_infusionsoft_enqueue_scripts' );


/**
 * Enable the InfusionSoft tags in the tag generator
 */
function cf7_infusionsoft_add_tag_generator() {
	if( function_exists('wpcf7_add_tag_generator') ) {
        // Modify callback based on CF7 version
        $callback = function_exists('wpcf7_add_meta_boxes') ? 'wpcf7_tag_generator_infusionsoft_old' : 'wpcf7_tag_generator_infusionsoft';
		wpcf7_add_tag_generator( 'infusionsoft', 'Infusionsoft Fields', 'wpcf7-tg-pane-infusionsoft', $callback );
	}
}
add_action( 'admin_init', 'cf7_infusionsoft_add_tag_generator', 99 );


/**
 * Adds a box to the main column on the form edit page. 
 *
 * CF7 < 4.2
 */
function cf7_infusionsoft_tag_add_meta_boxes() {
	add_meta_box( 'cf7-infusionsoft-settings', 'InfusionSoft Settings', 'cf7_infusionsoft_addon_metaboxes', null, 'form', 'low');
}
add_action( 'wpcf7_add_meta_boxes', 'cf7_infusionsoft_tag_add_meta_boxes' );


/**
 * Adds a tab to the editor on the form edit page. 
 *
 * CF7 >= 4.2
 */
function cf7_infusionsoft_tag_page_panels($panels) {
    $panels['infusionsoft-panel'] = array( 'title' => 'InfusionSoft Options', 'callback' => 'cf7_infusionsoft_addon_panel_meta' );
    return $panels;
}
add_action( 'wpcf7_editor_panels', 'cf7_infusionsoft_tag_page_panels' );


// Create the meta boxes (CF7 < 4.2)
function cf7_infusionsoft_addon_metaboxes( $post ) {
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'cf7_infusionsoft_addon_metaboxes', 'cf7_infusionsoft_addon_metaboxes_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$infusionsoft_addon_tag_value = get_post_meta( $post->id(), '_cf7_infusionsoft_addon_tag_key', true );

	echo '<label for="cf7_infusionsoft_addon_tags"><strong>Contact Tags: </strong></label> ';
	echo '<input type="text" placeholder="infusionsoft_tag_name" id="cf7_infusionsoft_addon_tags" name="cf7_infusionsoft_addon_tags" value="' . esc_attr( $infusionsoft_addon_tag_value ) . '" size="25" />';
	echo '<p class="howto">Separate multiple tags with commas. These must already be defined in InfusionSoft.</p>';
}
// Create the panel inputs (CF7 >= 4.2)
function cf7_infusionsoft_addon_panel_meta( $post ) {
    wp_nonce_field( 'cf7_infusionsoft_addon_metaboxes', 'cf7_infusionsoft_addon_metaboxes_nonce' );
    $infusionsoft_addon_tag_value = get_post_meta( $post->id(), '_cf7_infusionsoft_addon_tag_key', true );

    // The meta box content
    echo '<h3>InfusionSoft Tags</h3>
          <fieldset>
            <legend>Enter tags exactly how they appear in InfusionSoft. Separate multiple tags with commas. <br>These must already be defined in InfusionSoft.</legend>
            <input type="text" placeholder="Tag 1, tag_2, etc." id="cf7_infusionsoft_addon_tags" name="cf7_infusionsoft_addon_tags" value="' . esc_attr( $infusionsoft_addon_tag_value ) . '" size="25" />' .
         '</fieldset>';
}

// Store InfusionSoft tag
function cf7_infusionsoft_addon_save_contact_form( $contact_form ) {
	$contact_form_id = $contact_form->id();
    if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7_infusionsoft_addon_tags'] ) || !isset( $_POST['cf7_infusionsoft_addon_metaboxes_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['cf7_infusionsoft_addon_metaboxes_nonce'], 'cf7_infusionsoft_addon_metaboxes' ) ) {
		return;
	}

	if ( isset( $_POST['cf7_infusionsoft_addon_tags'] ) ) {
        update_post_meta( $contact_form_id,
           '_cf7_infusionsoft_addon_tag_key',
            $_POST['cf7_infusionsoft_addon_tags']
        );
    }
}
add_action( 'wpcf7_after_save', 'cf7_infusionsoft_addon_save_contact_form' );


function cf7_infusionsoft_addon_signup_form_submitted( $contact_form ) {
	$contact_form_id = $contact_form->id();

	$submission = WPCF7_Submission::get_instance();
  	$posted_data = $submission->get_posted_data();	
  	
  	// If the email address is not set
  	if ( empty( $posted_data['infusionsoft-email'] ) ) {
  		return;
  	}
  	// If all looks good, let's try to add the user
	cf7_infusionsoft_addon_add_contact($contact_form_id, $posted_data);
}
add_action( 'wpcf7_mail_sent', 'cf7_infusionsoft_addon_signup_form_submitted' );

function cf7_infusionsoft_addon_add_contact($contact_form_id, $posted_data) {
	
	// Exit right away if the API credentials aren't entered
	$infusionsoft_app_name = get_option( 'infusionsoft_app_name');
	$infusionsoft_api_key = get_option( 'infusionsoft_api_key');

	if ( empty( $infusionsoft_app_name ) || empty( $infusionsoft_api_key ) ) {
		echo '<script>alert("You must configure the Contact Form 7 - InfusionSoft Add-on in the Admin.")</script>';
		return;
	}

	// Configure a new InfusionSoft connection
	$app = new iSDK();
    // If no connection is made, get out of here.
    if ( !( $app->cfgCon($infusionsoft_app_name) ) ) {
    	return;
    }

    // Assemble the contact data
	$contact_data = array(
		'FirstName' => ( !empty($posted_data['infusionsoft-first-name']) ) ? $posted_data['infusionsoft-first-name'] : '',
		'LastName' => ( !empty($posted_data['infusionsoft-last-name']) ) ? $posted_data['infusionsoft-last-name'] : '',
		'Company' => ( !empty($posted_data['infusionsoft-company']) ) ? $posted_data['infusionsoft-company'] : '',
		'Email' => $posted_data['infusionsoft-email'],
		'Phone1' => ( !empty($posted_data['infusionsoft-phone']) ) ? $posted_data['infusionsoft-phone'] : '',
        'ContactNotes' => ( !empty($posted_data['infusionsoft-notes']) ) ? $posted_data['infusionsoft-notes'] : '',
        'Website' => ( !empty($posted_data['infusionsoft-website']) ) ? $posted_data['infusionsoft-website'] : '',
	);
	// Add the contact to InfusionSoft, with a duplicate check
	$contact_id = $app->addWithDupCheck($contact_data, 'EmailAndName');

	// Set opt-in marketing status
	// InfusionSoft requires a "reason" for setting the opt-in marketing status
	$reason = get_bloginfo('name') . ' Website Signup Form';
	// And allow them to receive email marketing
	$set_optin_status = $app->optIn($posted_data['infusionsoft-email'], $reason);

	// Optionally tag the contact
	$user_tags = get_post_meta( $contact_form_id, '_cf7_infusionsoft_addon_tag_key', true );

  	if ( !empty( $user_tags ) ) {
		// Assemble the names into a list of strings (with leading/ending whitespace trimmed)
		$user_tags = array_map( 'trim', explode(',', $user_tags) );

		foreach ($user_tags as $tag_name) {
			// Search the ContactGroup table for each tag name
			$tag_data = $app->dsFind( 'ContactGroup', 1, 0, 'GroupName', $tag_name, array('Id') );
			// If the query returns a valid ID
			if ( !empty( $tag_data[0]['Id'] ) ) {
                $tag_the_user = $app->grpAssign( $contact_id, $tag_data[0]['Id'] );
            }
		}
  	} 	
}
