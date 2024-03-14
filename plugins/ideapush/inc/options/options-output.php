<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

//define all the settings in the plugin
function idea_push_settings_init() { 
    
    //start authorisation section
	register_setting( 'ip_notifications', 'idea_push_settings' );
    
    //notification settings
	add_settings_section(
		'idea_push_notifications','', 
		'idea_push_notifications_callback', 
		'ip_notifications'
	);
    
    add_settings_field( 
		'idea_push_hide_admin_notice','', 
		'idea_push_hide_admin_notice_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);

	add_settings_field( 
		'idea_push_notification_email','', 
		'idea_push_notification_email_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    
    //admin notification when idea is submitted
    add_settings_field( 
		'idea_push_notification_idea_submitted','', 
		'idea_push_notification_idea_submitted_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_idea_submitted_subject','', 
		'idea_push_notification_idea_submitted_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_idea_submitted_content','', 
		'idea_push_notification_idea_submitted_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    
    //admin notification when idea is ready for review
    add_settings_field( 
		'idea_push_notification_idea_review','', 
		'idea_push_notification_idea_review_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_idea_review_subject','', 
		'idea_push_notification_idea_review_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_idea_review_content','', 
		'idea_push_notification_idea_review_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    
    //author idea created published
    add_settings_field( 
		'idea_push_notification_author_idea_created_published_enable','', 
		'idea_push_notification_author_idea_created_published_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_created_published_subject','', 
		'idea_push_notification_author_idea_created_published_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_created_published_content','', 
		'idea_push_notification_author_idea_created_published_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    
    //author idea created reviewed
    add_settings_field( 
		'idea_push_notification_author_idea_created_reviewed_enable','', 
		'idea_push_notification_author_idea_created_reviewed_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_created_reviewed_subject','', 
		'idea_push_notification_author_idea_created_reviewed_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_created_reviewed_content','', 
		'idea_push_notification_author_idea_created_reviewed_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
    );
    
    //author review published
    add_settings_field( 
		'idea_push_notification_author_idea_published_enable','', 
		'idea_push_notification_author_idea_published_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_published_subject','', 
		'idea_push_notification_author_idea_published_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_published_content','', 
		'idea_push_notification_author_idea_published_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    
    
    //author review
    add_settings_field( 
		'idea_push_notification_author_idea_change_review_enable','', 
		'idea_push_notification_author_idea_change_review_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_review_subject','', 
		'idea_push_notification_author_idea_change_review_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_review_content','', 
		'idea_push_notification_author_idea_change_review_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    //author approved
    add_settings_field( 
		'idea_push_notification_author_idea_change_approved_enable','', 
		'idea_push_notification_author_idea_change_approved_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_approved_subject','', 
		'idea_push_notification_author_idea_change_approved_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_approved_content','', 
		'idea_push_notification_author_idea_change_approved_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    //author declined
    add_settings_field( 
		'idea_push_notification_author_idea_change_declined_enable','', 
		'idea_push_notification_author_idea_change_declined_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_declined_subject','', 
		'idea_push_notification_author_idea_change_declined_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_declined_content','', 
		'idea_push_notification_author_idea_change_declined_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    //author in progress
    add_settings_field( 
		'idea_push_notification_author_idea_change_in_progress_enable','', 
		'idea_push_notification_author_idea_change_in_progress_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_in_progress_subject','', 
		'idea_push_notification_author_idea_change_in_progress_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_in_progress_content','', 
		'idea_push_notification_author_idea_change_in_progress_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    //author completed
    add_settings_field( 
		'idea_push_notification_author_idea_change_completed_enable','', 
		'idea_push_notification_author_idea_change_completed_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_completed_subject','', 
		'idea_push_notification_author_idea_change_completed_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_completed_content','', 
		'idea_push_notification_author_idea_change_completed_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
    );
    
    //author duplicate
    add_settings_field( 
		'idea_push_notification_author_idea_change_duplicate_enable','', 
		'idea_push_notification_author_idea_change_duplicate_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_duplicate_subject','', 
		'idea_push_notification_author_idea_change_duplicate_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_idea_change_duplicate_content','', 
		'idea_push_notification_author_idea_change_duplicate_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    //author notification someone voted
    add_settings_field( 
		'idea_push_notification_author_voter_voted_enable','', 
		'idea_push_notification_author_voter_voted_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_voter_voted_subject','', 
		'idea_push_notification_author_voter_voted_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_author_voter_voted_content','', 
		'idea_push_notification_author_voter_voted_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    
    //voter review
    add_settings_field( 
		'idea_push_notification_voter_idea_change_reviewed_enable','', 
		'idea_push_notification_voter_idea_change_reviewed_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_reviewed_subject','', 
		'idea_push_notification_voter_idea_change_reviewed_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_reviewed_content','', 
		'idea_push_notification_voter_idea_change_reviewed_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    //voter approved
    add_settings_field( 
		'idea_push_notification_voter_idea_change_approved_enable','', 
		'idea_push_notification_voter_idea_change_approved_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_approved_subject','', 
		'idea_push_notification_voter_idea_change_approved_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_approved_content','', 
		'idea_push_notification_voter_idea_change_approved_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    //voter declined
    add_settings_field( 
		'idea_push_notification_voter_idea_change_declined_enable','', 
		'idea_push_notification_voter_idea_change_declined_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_declined_subject','', 
		'idea_push_notification_voter_idea_change_declined_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_declined_content','', 
		'idea_push_notification_voter_idea_change_declined_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    //voter in progress
    add_settings_field( 
		'idea_push_notification_voter_idea_change_in_progress_enable','', 
		'idea_push_notification_voter_idea_change_in_progress_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_in_progress_subject','', 
		'idea_push_notification_voter_idea_change_in_progress_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_in_progress_content','', 
		'idea_push_notification_voter_idea_change_in_progress_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    //voter completed
    add_settings_field( 
		'idea_push_notification_voter_idea_change_completed_enable','', 
		'idea_push_notification_voter_idea_change_completed_enable_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_completed_subject','', 
		'idea_push_notification_voter_idea_change_completed_subject_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    add_settings_field( 
		'idea_push_notification_voter_idea_change_completed_content','', 
		'idea_push_notification_voter_idea_change_completed_content_render', 
		'ip_notifications', 
		'idea_push_notifications' 
	);
    
    
    
    //status settings
    register_setting( 'ip_statuses', 'idea_push_settings' );
    
	add_settings_section(
		'idea_push_statuses','', 
		'idea_push_statuses_callback', 
		'ip_statuses'
	);

    //translate statuses
	add_settings_field( 
		'idea_push_change_open_status','', 
		'idea_push_change_open_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    add_settings_field( 
		'idea_push_change_reviewed_status','', 
		'idea_push_change_reviewed_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    add_settings_field( 
		'idea_push_change_approved_status','', 
		'idea_push_change_approved_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    add_settings_field( 
		'idea_push_change_declined_status','', 
		'idea_push_change_declined_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    add_settings_field( 
		'idea_push_change_in_progress_status','', 
		'idea_push_change_in_progress_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    add_settings_field( 
		'idea_push_change_completed_status','', 
		'idea_push_change_completed_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
    );

    add_settings_field( 
		'idea_push_change_all_statuses_status','', 
		'idea_push_change_all_statuses_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
    );

    add_settings_field( 
		'idea_push_change_duplicate_status','', 
		'idea_push_change_duplicate_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
    );
    


    //diable statuses
    add_settings_field( 
		'idea_push_disable_approved_status','', 
		'idea_push_disable_approved_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    add_settings_field( 
		'idea_push_disable_declined_status','', 
		'idea_push_disable_declined_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    add_settings_field( 
		'idea_push_disable_in_progress_status','', 
		'idea_push_disable_in_progress_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    add_settings_field( 
		'idea_push_disable_completed_status','', 
		'idea_push_disable_completed_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
    );
    
    add_settings_field( 
		'idea_push_disable_all_statuses_status','', 
		'idea_push_disable_all_statuses_status_render', 
		'ip_statuses', 
		'idea_push_statuses' 
	);
    
    
    //design settings
    register_setting( 'ip_design', 'idea_push_settings' );
    
	add_settings_section(
		'idea_push_design','', 
		'idea_push_design_callback', 
		'ip_design'
	);

	add_settings_field( 
		'idea_push_primary_link_colour','', 
		'idea_push_primary_link_colour_render', 
		'ip_design', 
		'idea_push_design' 
	);
    
    add_settings_field( 
		'idea_push_custom_css','', 
		'idea_push_custom_css_render', 
		'ip_design', 
		'idea_push_design' 
	);
    
    
    //idea form settings
    register_setting( 'ip_idea_form', 'idea_push_settings' );
    
	add_settings_section(
		'idea_push_idea_form','', 
		'idea_push_idea_form_callback', 
		'ip_idea_form'
	);

	add_settings_field( 
		'idea_push_form_title','', 
		'idea_push_form_title_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
	);
    
    add_settings_field( 
		'idea_push_idea_title','', 
		'idea_push_idea_title_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
	);
    
    add_settings_field( 
		'idea_push_idea_description','', 
		'idea_push_idea_description_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
	);
    
    add_settings_field( 
		'idea_push_idea_tags','', 
		'idea_push_idea_tags_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
	);
    
    add_settings_field( 
		'idea_push_attachment_text','', 
		'idea_push_attachment_text_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
	);
    
    add_settings_field( 
		'idea_push_submit_button','', 
		'idea_push_submit_button_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
    );
    
    add_settings_field( 
		'idea_push_submit_idea_button','', 
		'idea_push_submit_idea_button_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
    );
    
    add_settings_field( 
		'idea_push_form_settings','', 
		'idea_push_form_settings_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
	);
    
    add_settings_field( 
		'idea_push_enable_bot_protection','', 
		'idea_push_enable_bot_protection_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
    );
    
    add_settings_field( 
		'idea_push_disable_profile_edit','', 
		'idea_push_disable_profile_edit_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
    );
    
    add_settings_field( 
		'idea_push_privacy_confirmation','', 
		'idea_push_privacy_confirmation_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
    );

    add_settings_field( 
		'idea_push_max_file_size','', 
		'idea_push_max_file_size_render', 
		'ip_idea_form', 
		'idea_push_idea_form' 
    );
    

    //tag page settings
    register_setting( 'ip_tag_page', 'idea_push_settings' );
    
	add_settings_section(
		'idea_push_tage_page','', 
		'idea_push_tage_page_callback', 
		'ip_tag_page'
	);

	add_settings_field( 
		'idea_push_tag_pagination_number','', 
		'idea_push_tag_pagination_number_render', 
		'ip_tag_page', 
		'idea_push_tage_page' 
	);
    
    // add_settings_field( 
	// 	'idea_push_tag_multiple_ips','', 
	// 	'idea_push_tag_multiple_ips_render', 
	// 	'ip_tag_page', 
	// 	'idea_push_tage_page' 
	// );
    
    

    
    
    
    
    //board settings
    register_setting( 'ip_boards', 'idea_push_settings' );
    
	add_settings_section(
		'idea_push_boards','', 
		'idea_push_boards_callback', 
		'ip_boards'
	);

	add_settings_field( 
		'idea_push_board_configuration','', 
		'idea_push_board_configuration_render', 
		'ip_boards', 
		'idea_push_boards' 
	);
    

    //locked
    register_setting( 'ip_licence', 'idea_push_settings' );
    
	add_settings_section(
		'idea_push_locked','', 
		'idea_push_locked_callback', 
		'ip_licence'
	);
    
    //support
    register_setting( 'ip_ideapush_support', 'idea_push_settings' );
    
	add_settings_section(
		'idea_push_ideapush_support','', 
		'idea_push_ideapush_support_callback', 
		'ip_ideapush_support'
	);
    
 
 
}

/**
* 
*
*
* The following functions output the callback of the sections
*/
function idea_push_notifications_callback(){}
function idea_push_design_callback(){}
function idea_push_tage_page_callback(){}
function idea_push_statuses_callback(){
    ?>
    <tr class="ideapush_settings_row" valign="top">
        <td scope="row" colspan="2">
            <div class="inside">
                
                
                <div style="font-weight: 600;" class="notice notice-info inline">
                    <p><?php _e( 'These settings update the status names - this only changes the status names in the front end of idea board and not in the admin area. You can also remove some of the default statuses.', 'ideapush' ); ?></p>
                </div>
            </div>
        </td>
    </tr>
    <?php
    
}

function idea_push_ideapush_support_callback(){

    global $ideapush_is_pro;

    ?>
    <tr class="ideapush_settings_row" valign="top">
        <td scope="row" colspan="2">
            <div class="inside">
                
                
                
                
                
                <h2><?php _e( 'Frequently Asked Questions', 'ideapush' ); ?></h2>
                
                <iframe style="margin-top: 20px; margin-bottom: 20px;" width="560" height="315" src="https://www.youtube-nocookie.com/embed/yFaGNbYUiIw?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                
                
                <div id="accordion">
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'Why do you recommend putting the shortcode onto a page and not a post?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'You can definitely put the board shortcode onto a post without any issues, however if you put it onto a page, on the single post we will show a back button/breadcrumb so people can go back to the main board page.', 'ideapush' ); ?>
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'Can I put more then 1 board on a post or page?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'No, the plugin has only been designed for a single board on a page because of the complicated javascript going on and to maintain performance.', 'ideapush' ); ?>
                    </div>
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'I am a developer, can I hook into some of the events created by the plugin?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'Yes we have developed a variety of action hooks so you can hook into some of the events created by IdeaPush. Please use the below table to see the action details:', 'ideapush' ); ?>
                        
                        <br></br>
                        <h3>Admin Notification Actions</h3>
                        
                        <table class="action-reference-table">
                            <colgroup>
                                <col class="action-hook-column">
                                <col class="action-name-column">
                                <col class="action-parameters-column">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>Action Hook Purpose</th>
                                    <th>Action Name</th>
                                    <th>Action Parameters</th>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Admin notification when idea is created</td>
                                    <td class="action-name">idea_push_idea_created_admin_notification</td>
                                    <td class="action-parameters">$newIdeaId, $content</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Admin notification when vote threshold is reached</td>
                                    <td class="action-name">idea_push_idea_review_admin_notification</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>

                            </tbody>
                        </table>
                        
                        <br></br>
                        <h3>Author Notification Actions</h3>
                        
                        <table class="action-reference-table">
                            <colgroup>
                                <col class="action-hook-column">
                                <col class="action-name-column">
                                <col class="action-parameters-column">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>Action Hook Purpose</th>
                                    <th>Action Name</th>
                                    <th>Action Parameters</th>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Author notification when an idea is published</td>
                                    <td class="action-name">idea_push_idea_created_published_author_notification</td>
                                    <td class="action-parameters">$newIdeaId, $content</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Author notification when an idea is pending review for admin approval</td>
                                    <td class="action-name">idea_push_idea_created_reviewed_author_notification</td>
                                    <td class="action-parameters">$newIdeaId, $content</td>
                                </tr>

                                <tr class="action-item">
                                    <td class="action-purpose">Author notification when an idea is published afetr being on hold/pending</td>
                                    <td class="action-name">idea_push_idea_published_after_pending_author_notification</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>
                                
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Author notification when an idea has been voted on</td>
                                    <td class="action-name">idea_push_idea_vote_author_notification</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>
                                
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Author notification when a vote has been cast and it has now changed the status from open to reviewed</td>
                                    <td class="action-name">idea_push_idea_vote_author_notification_review</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Author notification when a status has changed</td>
                                    <td class="action-name">idea_push_idea_status_change_author_notification</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>
                                
                            </tbody>
                        </table>
                
                
                
                
                        <br></br>
                        <h3>Voter Notification Actions</h3>
                        
                        <table class="action-reference-table">
                            <colgroup>
                                <col class="action-hook-column">
                                <col class="action-name-column">
                                <col class="action-parameters-column">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>Action Hook Purpose</th>
                                    <th>Action Name</th>
                                    <th>Action Parameters</th>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Voter notification when they cast a vote for something that has reached the review stage</td>
                                    <td class="action-name">idea_push_idea_vote_voter_notification</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">Voter notification when the idea they voted on changes status</td>
                                    <td class="action-name">idea_push_idea_status_change_voter_notification</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>

     
                            </tbody>
                        </table>
            
            
                        <br></br>
                        <h3>Other Actions</h3>
                        
                        <table class="action-reference-table">
                            <colgroup>
                                <col class="action-hook-column">
                                <col class="action-name-column">
                                <col class="action-parameters-column">
                            </colgroup>
                            
                            <tbody>
                                <tr>
                                    <th>Action Hook Purpose</th>
                                    <th>Action Name</th>
                                    <th>Action Parameters</th>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">When status has changed</td>
                                    <td class="action-name">idea_push_idea_status_change</td>
                                    <td class="action-parameters">$ideaId, $currentUser, $content</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">When vote threshold has been achieved</td>
                                    <td class="action-name">idea_push_idea_vote_threshold</td>
                                    <td class="action-parameters">$ideaId, $content</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">When a vote has been cast</td>
                                    <td class="action-name">idea_push_vote_cast</td>
                                    <td class="action-parameters">$ideaId, $userId, $voteIntent, $ideaScoreNow, $voteThreshold</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">When an idea has been published</td>
                                    <td class="action-name">idea_push_after_idea_created</td>
                                    <td class="action-parameters">$newIdeaId, $userId, $title, $description</td>
                                </tr>
                                
            
                            </tbody>
                        </table>
                        <br></br>

                        <?php _e( 'Below is an example of how to send an email when someone casts a vote using the above specification:', 'ideapush' ); ?> 
                        <br><br>
                        <code>
                            function send_an_email_to_vote($ideaId, $userId, $voteIntent, $ideaScoreNow, $voteThreshold){<br>
                                <br>
                                $voter = get_user_by('id',$userId);<br>
                                $voter_email = $voter->user_email;<br>
                                $voter_first_name = $voter->first_name;<br>
                                $idea_title = get_the_title($ideaId);<br>
                                <br>
                                $message = 'Hey '.$voter_first_name.', you just vote successfully for '.$idea_title;<br>
                                <br>
                                //send an email wordpress<br>
                                $to = $voter_email;<br>
                                $subject = 'My subject';<br>
                                $body = $message;<br>
                                $headers = array('Content-Type: text/html; charset=UTF-8');<br>
                                wp_mail( $to, $subject, $body, $headers );<br>
                                <br>
                            }<br>
                            add_action('idea_push_vote_cast','send_an_email_to_vote',10,5);
                        </code><br><br>
                        

                        <h3>jQuery Events</h3>
                        
                        <p><?php _e( 'We also have the following javascript events:', 'ideapush' ); ?></p>

                        <table class="action-reference-table">
                            <colgroup>
                                <col class="action-hook-column">
                                <col class="action-name-column">
                            </colgroup>
                            
                            <tbody>
                                <tr>
                                    <th>Event Purpose</th>
                                    <th>Event Name</th>
                                </tr>
                                
                                
                                <tr class="action-item">
                                    <td class="action-purpose">When the form is rendered</td>
                                    <td class="action-name">formRendered</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">When the header is rendered</td>
                                    <td class="action-name">headerRendered</td>
                                </tr>
                                
                                <tr class="action-item">
                                    <td class="action-purpose">When the main ideas of the board are rendered</td>
                                    <td class="action-name">ideasRendered</td>
                                </tr>
                                
            
                            </tbody>
                        </table>
                        <br></br>
                        <p><?php _e( 'This can be ran like:', 'ideapush' ); ?></p>

                        <code>
                        $('body').on('ideasRendered headerRendered formRendered', function(){ //remove any events needed for your purpose<br>
                            console.log('I WAS RAN');<br>
                        });
                        </code>
                        

                        <br></br>
                        <?php _e( 'This specification is still being worked on so please expect changes to occur. Your feedback on this specification is welcome.', 'ideapush' ); ?>
            
 
                        
                        
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'I am a developer, do you provide any filters?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'Yes, we currently provide 3 filters:

                        <br></br><strong>Modify Users Name</strong></br>
                        <em>Modifies the users name from their first name to something else.</em></br>
                        <code>add_filter( "idea_push_change_user_name", "idea_push_change_user_name_callback", 10, 1 );
                        function idea_push_change_user_name_callback( $userId ) {
                        
                            $userObject = get_user_by("id",$userId);  
                            return $userObject->display_name;
                        
                        }</code></br>


                        <br></br><strong>Modify Users Link</strong></br>
                        <em>Modifies the link when clicking on the users name, by default it goes to the author page showing all their ideas. The below example removes the link.</em></br>
                        <code>add_filter( "idea_push_change_author_link", "idea_push_change_author_link_callback", 10, 1 );
                        function idea_push_change_author_link_callback( $userId ) {
                        
                            return "#";
                        
                        }</code></br>

                        <br></br><strong>Modify Users Image</strong></br>
                        <em>Modifies the user image. The below example changes the users image to a cow. By default we try and get the users IdeaPush image, and if this does not exist we try and get the users WordPress avatar.</em></br>
                        <code>add_filter( "idea_push_change_user_image", "idea_push_change_user_image_callback", 10, 1 );
                        function idea_push_change_user_image_callback( $userId ) {
                        
                            //do something awesome with $userId
                            return "https://cdn.britannica.com/55/174255-004-9A4971E9.jpg";
                        
                        }</code></br>

                        <br></br><strong>Add Content Before Idea Title</strong></br>
                        <em>Adds content before idea title on the board and single idea page</em></br>
                        <code>add_filter( "idea_push_before_idea_title", "idea_push_before_idea_title_callback", 10, 1 );
                        function idea_push_before_idea_title_callback( $ideaId ) {
                        
                            //do something awesome with $ideaId
                            return "Hello World";
                        
                        }</code></br>

                        <br></br><strong>Add Content After Idea Title</strong></br>
                        <em>Adds content after idea title on the board and single idea page</em></br>
                        <code>add_filter( "idea_push_after_idea_title", "idea_push_after_idea_title_callback", 10, 1 );
                        function idea_push_after_idea_title_callback( $ideaId ) {
                        
                            //do something awesome with $ideaId
                            return "Hello World";
                        
                        }</code></br>



                        <br></br><strong>Add Content before Idea</strong></br>
                        <em>Adds content before the idea on the board and single idea page</em></br>
                        <code>add_filter( "idea_push_before_idea", "idea_push_before_idea_callback", 10, 1 );
                        function idea_push_before_idea_callback( $ideaId ) {
                        
                            //do something awesome with $ideaId
                            return "Hello World";
                        
                        }</code></br>


                        <br></br><strong>Add Content After Idea</strong></br>
                        <em>Adds content after the idea on the board and single idea page</em></br>
                        <code>add_filter( "idea_push_after_idea", "idea_push_after_idea_callback", 10, 1 );
                        function idea_push_after_idea_callback( $ideaId ) {
                        
                            //do something awesome with $ideaId
                            return "Hello World";
                        
                        }</code></br>


                        




                        <br></br><strong>Change login link</strong></br>
                        <em>Changes the login link on the idea form shown on the board page</em></br>
                        <code>add_filter( "idea_push_change_login_link", "idea_push_change_login_link_callback", 10, 1 );
                        function idea_push_change_login_link_callback( $link ) {
                        
                            //do something awesome with $link
                            return "https://mycustomloginlink.com";
                        
                        }</code></br>


                        <br></br><strong>Change Vote Amount</strong></br>
                        <em>Changes the vote amount when a vote is cast. Typically this should return an integer value between -2 or 2 but this can be modified with this filter</em></br>
                        <code>add_filter( "idea_push_change_vote_amount", "idea_push_change_vote_amount_callback", 10, 1 );
                        function idea_push_change_vote_amount_callback( $int ) {
                        
                            return 1;
                        
                        }</code></br>


                        <br></br><strong>Change Vote Render</strong></br>
                        <em>This one is a bit complicated but it is used to change how the vote icons render on the frontend after a vote has been cast. It can be used in conjunction with the above filter for certain use cases.</em></br>
                        <code>add_filter( "idea_push_change_vote_render", "idea_push_change_vote_render_callback", 10, 1 );
                        function idea_push_change_vote_render_callback( $string ) {
                        
                            //explode the string
                            $string_exploded = explode("|",$string);

                            //set values in the array i.e. force them
                            $string_exploded[0] = 1;
                            $string_exploded[1] = 3;

                            //turn back to string
                            $string_imploded = implode("|",$string_exploded);

                            return $string_imploded;
                        
                        }</code></br>

                        <br></br><strong>Disable Single Ideas</strong></br>
                        <em>With this filter you can fully disable single ideas.</em></br>
                        <code>add_filter( "ideapush_display_single_ideas", "ideapush_display_single_ideas_callback", 10, 1 );
                        function ideapush_display_single_ideas_callback( $bool ) {
                        
                            //turn to false to disable single ideas
                            $bool = false;

                            return $bool;
                        
                        }</code></br>


                        <br></br><strong>Change Seperator Between Custom Fields</strong></br>
                        <em>You can change the separator between custom fields, this is usually a comma</em></br>
                        <code>add_filter( "idea_push_custom_field_separator", "idea_push_custom_field_separator_callback", 10, 1 );
                        function idea_push_custom_field_separator_callback( $separator ) {
                        
                            $separator = \'| \';

                            return $separator;
                        
                        }</code></br>

                        <br></br><strong>Allowed Idea Statuses for Voting</strong></br>
                        <em>The only allowed statuses for voting are open ideas. You can use the filter to expand this to other statuses.</em></br>
                        <code>add_filter( "idea_push_voting_statuses", "idea_push_voting_statuses_callback", 10, 1 );
                        function idea_push_voting_statuses_callback( $statuses ) {
                            //this will allow voting for the reviewed status as well
                            array_push($statuses,\'reviewed\');

                            return $statuses;
                        
                        }</code></br>


                        <br></br><strong>Additional File Types for Custom Fields</strong></br>
                        <em>By default we only allow jpg, .jpeg, .png, .gif files to be uploaded via custom fields, but with this filter it can be extended to cover other file types.</em></br>
                        <code>add_filter("idea_push_allowed_file_types","idea_push_allowed_file_types_callback", 10, 1);

                        function idea_push_allowed_file_types_callback($file_types){
                        
                            $file_types .= ", .pdf";
                        
                            return $file_types;
                        
                        }</code></br>


                        <br></br><strong>Change max length of idea description</strong></br>
                        <em>By default we cap the idea description to 2000 characters, but you can change this to a different number by using this filter</em></br>
                        <code>add_filter("idea_push_max_characters_for_description","idea_push_max_characters_for_description_callback", 10, 1);

                        function idea_push_max_characters_for_description_callback($number){
                        
                            $number = 3000;
                        
                            return $number;
                        
                        }</code></br>

                        <br></br><strong>Show the board to multiple roles</strong></br>
                        <em>In the board setting "Show Board To" it allows you to select a specific user role the board should be shown to. With this filter you can extend this so that administrtaors can view the board if editors is selected for example.</em></br>
                        <code>add_filter("idea_push_extend_role_permissions", "idea_push_extend_role_permissions_callback", 10, 1);

                        function idea_push_extend_role_permissions_callback($roles) {
                        
                            if( in_array("administrator", $roles) ){
                                array_push($roles, "editor");
                            }
                        
                            return $roles;
                        
                        }</code></br>


                        <br></br><strong>Prevent certain roles from creating ideas</strong></br>
                        <em>Prevent specific roles from creating an idea, that is, the idea form won\'t show</em></br>
                        <code>add_filter("idea_push_deny_roles_from_creating_ideas", "idea_push_deny_roles_from_creating_ideas_callback", 10, 1);

                        function idea_push_deny_roles_from_creating_ideas_callback($roles) {
                        
                            array_push($roles,"editor");

	                        return $roles;
                        
                        }</code></br>

                        
                        
                        ', 'ideapush' ); ?>
                    </div>


                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'How do idea statuses work?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'When an idea is first created by a user on the frontend the idea is given an “Open” status. In the <a class="open-tab" href="#boards">boards tab</a> you can set a vote threshold for the board. You may set this to 10 for example. Once enough votes have accumulated on an idea and the idea reaches the vote threshold amount the idea will automatically change status from the “Open” status to the “Reviewed” status. On your <a href="'.get_admin_url(null, 'edit.php?post_type=idea' ).'">idea listing page</a> you will see a notification at the top “Ideas Needing Review” which you can click on which will show you all ideas that need your review. On the <a class="open-tab" href="#notifications">notifications</a> tab you can also activate the admin notification “Receive admin notifications when an idea is ready for review” to receive an email to get immidiately notified that an idea has reached this limit and has changed statuses. 

You can now change this ideas status depending on your business needs or requirements. You might mark the idea as “Approved” or “Declined”, to signify to the public that an idea has made it or has not made it past your internal review (because hey maybe the idea is crazy or not feasible). Or you can mark the idea as “In Progress” to signify that you are working on the implementation of this idea. Finally, you can mark the idea as “Completed” to signify that the idea has been implemented or resolved. Changing the status of an idea can be done from the idea edit page in the backend (please see the status metabox), or from the actual idea page itself on the frontend using the quick status edit buttons which are only available to the admin. People use IdeaPush in different ways so how you use these statuses may vary, and you can translate the names of these statuses or turn some of these statuses off completely from the <a class="open-tab" href="#statuses">stauses tab</a>.', 'ideapush' ); ?> 
                    </div>


                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'Things don\'t look great, how I can change that?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'Because the plugin has quite a lot of frontend design your existing theme or other plugins may change the style of IdeaPush. The plugin tries to strike a balance between respecting your existing styles whilst trying to make things look and work ok. Whilst it\'s possible to have settings for every possible thing on the frontend, the plugin would turn into settings overload which can be quite overwhelming to new users of the plugin who may just want to get things up and running quickly. To best resolve styling issues it requires you or someone you know to have <a href="https://www.w3schools.com/css/css_intro.asp" target="_blank">CSS knowledge</a>. You can then enter in custom CSS code into the Custom CSS field on the <a class="open-tab" href="#design">Design</a> tab. To help you with some common CSS changes I have put some quick buttons which implement the CSS code for you beside this field. If you want to override the styles of theme in your child theme for example you should list our style as a dependency that way you don\'t need to use the <code>!important</code> tag in your CSS, this can be done like this for example:<code>wp_enqueue_style( \'my-custom-style\', get_stylesheet_directory_uri() . \'/style.css\', array(\'custom-frontend-style-ideapush\') );</code>. You can learn more about this <a target="_blank" href="https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts">here</a>.', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'Can I have a board show in a fixed height container?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'Whilst it is recommended to have a dedicated page for the board page it is possible to put the board in the middle of other content on your page. To achieve this we need to put the board shortcode inside a div container. For example: <xmp><div class="ideapush-scrollable-container" style="height: 800px;">[ideapush board="###"]</div></xmp></br>You can replace the 800px with whatever height you want.', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'How can I change the status or filter shown on the board by default?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'It is possible to change this by adding a query string to your URL. For example: <strong>https://yourwebsites.com/yourpage/?showing=popular&status=reviewed&tag=All</strong>. Just replace "reviewed" with your desired status name or "popular" with your desired filter and obviously change the domain and page to your actual domain and board page. You can now put this URL into your WordPress menu if you wanted to by adding a custom URL to the menu or you can update your page slug to this URL. Also to get this customised URL more easily when you change any of the filters on your board display you will notice the URL will change so you can simply copy and paste this value into your menu or page slug.', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'Links appear to be broken for some reason, how can I fix this?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'This could happen for a couple of reasons. The first thing you should do is resave the <a href="'.get_admin_url(null, 'options-permalink.php' ).'">permalinks</a> of your site - this can fix most issues. It is possible you might have accidently deleted a board and recreated it again and this dissociates ideas with the board. If this occurs ideas need to be manually re-assigned to their respective board from the <a href="'.get_admin_url(null, 'edit.php?post_type=idea' ).'">ideas</a> page. It is possible to bulk reassign ideas using the WordPress bulk select and edit functionality.', 'ideapush' ); ?> 
                    </div>


                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'How does guest voting work?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'When someone creates an idea and isn\'t logged in, IdeaPush creates a user account in WordPress for them that is tied to their IP address. It is important we do this because it prevents people gaming the voting. Because otherwise a guest could create 50 different guest accounts and vote up their idea 50 times! 

The downside of this though is that if you have enabled guest voting and then someone who has already created a vote goes to a computer with a different IP address and tries to create an idea with the same email they won\'t be able to create a new idea as the email has already been associated to another IP address.

If this doesn\'t work for you, have no fear, because we can get around this by just using the built-in WordPress user registration system. So firstly in the board settings in the plugin settings you want to turn: GUEST VOTES/IDEAS to No. This will prevent guests creating ideas. Now you need to provide an outlet for users to create an account on your site - this can be done in many many different ways, it depends on what works for you. But you could have some text like, "What to create an idea, click here to create an account on our site first", and this goes to a user registration form - there are hundreds of plugins that can do this kind of thing, check out this page <a target="_blank" href="https://wordpress.org/plugins/tags/user-registration/">here</a> for just some of them. 

Now once someone is registered with your site they will be able to log in and out of your site and create ideas on the idea board without being impeded by their IP address. ', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'How do I have the idea form and the idea listing on separate pages?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'At this point in time we don\'t provide a coded solution to achieve this because currently there\'s crossover functionality between the form and the idea listing - we recommend keeping them together for best functionality. However by using CSS you can hide the form or listing on your page to make this scenario work. For example, create a page called "Idea Form" and put your shortcode on the page. Then add the following CSS to hide the idea listing:</br>
                        <code>.ideapush-container-idea-header {display: none;} .ideapush-container-ideas {display: none;} .ideapush-container-header {display: none;} @media screen and (min-width: 1050px){.ideapush-form-inner {margin-left: 0px !important;}}</code></br> 
                        and if you want to make the form full width please add the following code:</br>
                        <code>@media screen and (min-width: 1050px){.ideapush-container-form {width: 100% !important;}}</code></br>
                        then create a page for your idea listing called "Idea Listing" and put the same shortcode on this page and then add the following CSS to this page:</br>
                        <code>.ideapush-container-form {display: none !important;} @media screen and (min-width: 1050px){.ideapush-container-ideas {width: 100% !important;}}</code></br>
                        Not sure how to add custom CSS to a specific page? A lot of page builders have this functionality built-in but you can always check out this plugin <a href="https://wordpress.org/plugins/wp-add-custom-css/">here</a>.
                            
                        
                            
                            ', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'I don\'t need the reviewed status how do I get rid of it?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'It\'s not possible to remove the reviewed status completely from the plugin unlike some of the other statuses because the reviewed status is where graduated ideas live. But it\'s possible to hide it which effectively removes it. This can be done by setting the vote threshold limit so high like 9999 let\'s say that no idea can graduate. You can then use CSS to hide the Reviewed option in the status dropdown with the following code:</br><code>.ideapush-status-filter option[value=reviewed] {display: none !important;}</code> ', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'When I put the shortcode on the page nothing shows!', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'This is likely due to a plugin conflict. Please disable all plugins and see if IdeaPush works, then activate the plugins one by one and see which one is causing the issue. For example there is a known plugin conflict with the plugin called "Open Graph Protocol". This conflict is not due to any fault of IdeaPush and I am extremely doubtful that any plugin conflict would be caused by IdeaPush because the shortcode is fairly straight forward and does not rely on anything external to work. You should also try using a stock standard theme like Twenty Sixteen and see if this resolves the issue. Another issue could be a javascript error caused by your theme or another plugin. IdeaPush needs javascript to carry out a lot of the interactivity of the plugin. If there is a javascript error some of this functionality may not work and some things may not even render/show correctly. Please open your browser console and see if there are any errors.', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'How does idea duplicate merging work?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'When you click the mark as duplicate icon next to an idea you will be prompted to select the original idea you want to merge with. Once you click submit, the duplicate idea will be given the status "Duplicate", a notification will be sent to the duplicate idea author (if enabled in the plugin settings), and the votes from the duplicate idea will be transferred over to the original idea. How this transfer works is that a check is done to ensure that if someone voted on both the original and duplicate idea this vote will not transfer as this person has already declared their position for the idea. Only votes where the person did not vote for the original idea will be transferred across.', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'How do I hide the description field?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'Use the CSS code: <code>.ideapush-form-idea-description {display: none;}</code>', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'What roles are available/made by the plugin?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'The plugin creates 2 roles: "IdeaPush Guest" and "IdeaPush Manager". When users vote or create an idea they are given a role of "IdeaPush Guest" which is basically a role of no importance or capability. This role is created as WordPress requires posts to be assigned to an author, so when idea "posts" are created we need to create a WordPress user for them so we can assign the post to the user. Also by creating a WordPress user it means the user can log in and and out of your site to maintain their IdeaPush history. The other role we provide "IdeaPush Manager" is a role which you can manually assign to a user which enables them to create/edit/delete ideas from the backend and also change the IdeaPush settings but it gives them no other permissions. Note: IdeaPush Manager\'s don\'t have the ability to access IdeaPush Reports.', 'ideapush' ); ?> 
                    </div>


                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'I do not see comments on the single idea page?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'The plugin uses the standard WordPress comment system, so the display of these comments is dependant on your theme and WordPress comment settings. Here are some things you can try: 1) From the main WordPress admin menu go to Settings > Discussion and then check the checkbox "Allow people to submit comments on new posts". 2) Some themes disable comments or have an option to enable/disable comments, so make comments are enabled for single posts. 3) If you have any custom code or plugins mucking around with comments perhaps try turning this off for now. 4) It\'s important in your theme that the single.php file has the comments_template(); tag called. You can also create a custom template file for ideapush called single-idea.php and include the comment tag in there. See this article <a href="https://developer.wordpress.org/reference/functions/comments_template/" target="_blank">here</a> for more information on this.', 'ideapush' ); ?> 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'How can I translate the plugin?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'A lot of the plugins text can be translated into your language. To do this you need to get the <a target="_blank" href="https://poedit.net/">Poedit</a> program which is free and you can load the plugins files into Poedit and it enables you to translate all the strings. You can then export the .mo and .po files and place that into the plugins language folder and if you send it to me I can put it into the core of the plugin.', 'ideapush' ); ?> 
                    </div>
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'I purchased the pro version and entered my purchase email and order ID in the plugin settings but on the plugin page I get a red error: "The Order ID and Purchase ID you entered is not correct"?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'WordPress caches plugin updates every several hours. So please <a href="'.get_admin_url().'update-core.php">click here</a> to manually check for updates by clicking the button at the top of the page and this should clear the error message.', 'ideapush' ); ?> 
                    </div>
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> <?php _e( 'When I try and vote I get a NAN error where the vote numbers/icons are supposed to be?', 'ideapush' ); ?></h3>
                    <div>
                        <?php _e( 'This is likely because you have wrapped your shortcode in additional code or formatted the shortcode itself. So on your post/page where you have the board shortcode, just make sure it isn\'t bold or italic (or some other formatting) and make sure you don\'t have the shortcode wrapped in any other div elements which may have an HTML error.', 'ideapush' ); ?> 
                    </div>

                    






                    
                    
                </div>  
                <br></br>
                <h2><?php _e( 'Support', 'ideapush' ); ?></h2>
                
                <p style="font-weight: 900; color: red;"><?php _e( 'Before making a support request please read the above frequently asked questions. When submitting a request it\'s very important you include the following information:', 'ideapush' ); ?></p>
                
        

                <p><code><?php echo 'PHP Version: <strong>'.phpversion().'</strong>'; ?></br>
                <?php echo 'Wordpress Version: <strong>'.get_bloginfo('version').'</strong>'; ?></br>
                Plugin Version: <strong><?php echo idea_push_plugin_get_version(); ?></strong></br>
                Is Pro User: <strong><?php echo $ideapush_is_pro; ?></strong></br>
                Current Theme: <strong><?php 
                $user_theme = wp_get_theme();    
                echo esc_html( $user_theme->get( 'Name' ) );
                ?></strong></br>
                <?php echo 'Website address: <strong>'.home_url().'</strong>'; ?></br>
                
                </code></p>
                
                <p><?php _e( 'URL\'s and Screenshots of issues can also be extremely helpful in diagnosing things so please include this where possible.', 'ideapush' ); ?></p> 
                
                
                

                <?php
                    if($ideapush_is_pro == 'YES'){
                        ?>
                            <a class="button-secondary" target="_blank" href="https://northernbeacheswebsites.com.au/support/" >Create a priority support request</a>
                        <?php
                    } else {
                        ?>
                            <p><?php _e( 'To show your appreciation of our support we would be grateful if you could give us a <a target="_blank" href="https://wordpress.org/support/plugin/ideapush/reviews/?rate=5#new-post">positive review <i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i></a>.', 'ideapush' ); ?></p>

                            <a class="button-secondary" target="_blank" href="https://wordpress.org/support/plugin/ideapush" >Create a support request on the forum</a>
                            <p>We can only provide limited support through the WordPress forums because of the rules enforced by WordPress. For priority and more comprehensive support please <a target="_blank" href="https://northernbeacheswebsites.com.au/ideapush-pro/">upgrade to the pro version of the plugin</a>.</p>
                        <?php
                    }
                ?>

                
                
                
            </div>
        </td>
    </tr>
    <?php
    
}


function idea_push_idea_form_callback(){
    
    ?>
    <tr class="ideapush_settings_row" valign="top">
        <td scope="row" colspan="2">
            <div class="inside">
                
                
                <div style="font-weight: 600;" class="notice notice-info inline">


                    <p><?php 
                    
                    //we will customise this message depending if they are using the pro version or not
                    global $ideapush_is_pro;

                    if($ideapush_is_pro == "YES"){
                        $message = 'These options change the settings of the idea submission form used in the front end area of the idea board. You can create multiple form settings and assign them to different boards on the <a class="open-tab" href="#boards">Boards</a> tab under the "Field Setting" option for the board.';

                    } else {
                        $message = 'These options change the settings of the idea submission form used in the front end area of the idea board.';   
                    }
                    
                    _e($message, 'ideapush' ); 
                    

                    ?></p>
                </div>
            </div>
        </td>
    </tr>
    <?php
    
}




function idea_push_boards_callback(){
    
    global $ideapush_is_pro;

    //get options
    $options = get_option('idea_push_settings');
    
    ?>

    <tr class="ideapush_settings_row" valign="top">
        <td scope="row" colspan="2">
            <div class="inside">
                <label for="idea_push_create_board"><?php echo __('Create Board','ideapush'); ?></label>
                <input type="text" class="regular-text" style="margin-left:10px;" id="idea_push_create_board">
                    <button data-nonce="<?php echo wp_create_nonce( 'ideapush_create_board' ); ?>" class="button-secondary" style="margin-left:5px;" id="idea_push_create_board_button"><?php echo __('Add','ideapush'); ?></button>
                    
                    <br>
                
                    <ul id="board-settings">
                    
                        <?php
                            //lets re-write these settings so it makes it easier to edit in the future
                            //get the board settings

                            if(get_option('idea_push_settings')){

                                $options = get_option('idea_push_settings');
                                $board_settings = $options['idea_push_board_configuration'];

                                //explode to get each board
                                $boards = explode('^^^',$board_settings);

                                //loop through the boards
                                foreach($boards as $board){
                                    if(strlen($board)>0){
                                        echo idea_push_render_board($board);
                                    }
                                }

                                
                            }
                            
                        ?>
                    
                    </ul>

                


            </div>
        </td>
    </tr>        
            
    <?php
    
}

function idea_push_locked_callback(){
    ?>
    <tr class="ideapush_settings_row" valign="top">
        <td scope="row" colspan="2">
            <div class="inside">
                You need to purchase the pro version of the plugin to be able to enter in your purchase information which will enable automatic updates.
            </div>
        </td>
    </tr>
    <?php
    
}


//notification settings
function idea_push_notification_email_render(){ 
    
    echo '<tr class="ideapush_settings_row" valign="top">
            <td scope="row" colspan="2">
            <div class="inside">
                <div >
                    <h3>'.__( 'Admin Notifications', 'ideapush' ).'</h3>
                </div>
            </div>
        </td>
    </tr>';
    
    idea_push_settings_code_generator('idea_push_notification_email',__('Admin Notification Email','ideapush'),'','text','','','','');  
}

// function idea_push_tab_memory_render() {                                     idea_push_settings_code_generator('idea_push_tab_memory','Tab Memory','Remembers the last settings tab','text','','','','hidden-row');   
// }













//administrator notification idea submitted

function idea_push_notification_idea_submitted_render() {                                     
    idea_push_settings_code_generator('idea_push_notification_idea_submitted',__('Receive admin notifications when any idea is submitted','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}

   
function idea_push_notification_idea_submitted_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_idea_submitted_subject',__(__('Email subject','ideapush'),'ideapush'),'','shortcode','A new idea has been created: [Idea Title]',$values,'','email-subject');  
}

function idea_push_notification_idea_submitted_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name','Idea Edit Link');
    
    idea_push_settings_code_generator('idea_push_notification_idea_submitted_content',__(__('Email content','ideapush'),'ideapush'),'','textarea-advanced','A new idea has been created by [Author First Name] [Author Last Name]: [Idea Title] and can be edited here: [Idea Edit Link]. Thank you',$values,'','email-content');  
}

    
    
    
    

//administrator notification idea ready for review

function idea_push_notification_idea_review_render() {                                     
    idea_push_settings_code_generator('idea_push_notification_idea_review',__('Receive admin notifications when an idea is ready for review','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_idea_review_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Vote Count','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_idea_review_subject',__(__('Email subject','ideapush'),'ideapush'),'','shortcode','[Idea Title] has reached the vote threshold',$values,'','email-subject');  
}

function idea_push_notification_idea_review_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Vote Count','Board Name','Idea Edit Link');
    
    idea_push_settings_code_generator('idea_push_notification_idea_review_content',__(__('Email content','ideapush'),'ideapush'),'','textarea-advanced','The idea [Idea Title] submitted by [Author First Name] [Author Last Name] has reached [Vote Count] so it\'s now ready to be reviewed: [Idea Edit Link]',$values,'','email-content');  
}








//author notifications
//author idea created published
function idea_push_notification_author_idea_created_published_enable_render() {  
    
    //($id,$label,$description,$type,$default,$parameter,$importantNote,$rowClass)
    echo '<tr class="ideapush_settings_row" valign="top">
            <td scope="row" colspan="2">
            <div class="inside">
                <div >
                    <h3>'.__( 'Author Notifications', 'ideapush' ).'</h3>
                </div>
            </div>
        </td>
    </tr>';
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_created_published_enable',__('Author notification when idea is created and published','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}



function idea_push_notification_author_idea_created_published_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_created_published_subject',__(__('Email subject','ideapush'),'ideapush'),'','shortcode','Your idea - [Idea Title] - has been published',$values,'','email-subject');  
}

function idea_push_notification_author_idea_created_published_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_created_published_content',__(__('Email content','ideapush'),'ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has been published and is accessible here: [Idea Link]. Thank you',$values,'','email-content');  
}



//author idea created ready for review
function idea_push_notification_author_idea_created_reviewed_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_created_reviewed_enable',__('Author notification when idea is created and under review','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}



function idea_push_notification_author_idea_created_reviewed_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_created_reviewed_subject',__(__('Email subject','ideapush'),'ideapush'),'','shortcode','Your idea - [Idea Title] - has been received and is currently undergoing review',$values,'','email-subject');  
}

function idea_push_notification_author_idea_created_reviewed_content_render() { 
    
    $values = array('Idea Title','Idea Content','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_created_reviewed_content',__(__('Email content','ideapush'),'ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has been received and is currently undergoing review. Thank you',$values,'','email-content');  
}









//author idea published after being on hold
function idea_push_notification_author_idea_published_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_published_enable',__('Author notification when idea is published after being under review','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}



function idea_push_notification_author_idea_published_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_published_subject',__('Email subject','ideapush'),'','shortcode','Your idea - [Idea Title] - has been approved and is now published',$values,'','email-subject');  
}

function idea_push_notification_author_idea_published_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_published_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has been approved and is now published. Thank you',$values,'','email-content');  
}







//author review
function idea_push_notification_author_idea_change_review_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_review_enable',__('Author notification when status changed to review','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_author_idea_change_review_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Vote Count','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_review_subject',__('Email subject','ideapush'),'','shortcode','Your idea is now undergoing review as it has now reached [Vote Count] votes',$values,'','email-subject');  
}

function idea_push_notification_author_idea_change_review_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Vote Count','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_review_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has now reached [Vote Count] votes so it will now be reviewed. Thank you',$values,'','email-content');  
}



//author approved
function idea_push_notification_author_idea_change_approved_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_approved_enable',__('Author notification when status changed to approved','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_author_idea_change_approved_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_approved_subject',__('Email subject','ideapush'),'','shortcode','Your idea - [Idea Title] - has been approved',$values,'','email-subject');  
}

function idea_push_notification_author_idea_change_approved_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_approved_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has been approved. Thank you',$values,'','email-content');  
}





//author declined
function idea_push_notification_author_idea_change_declined_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_declined_enable',__('Author notification when status changed to declined','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_author_idea_change_declined_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_declined_subject',__('Email subject','ideapush'),'','shortcode','Your idea - [Idea Title] - has been declined',$values,'','email-subject');  
}

function idea_push_notification_author_idea_change_declined_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_declined_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has been declined. Thank you',$values,'','email-content');  
}


//author inprogress
function idea_push_notification_author_idea_change_in_progress_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_in_progress_enable',__('Author notification when status changed to in progress','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_author_idea_change_in_progress_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_in_progress_subject',__('Email subject','ideapush'),'','shortcode','Your idea - [Idea Title] - is currently being worked on',$values,'','email-subject');  
}

function idea_push_notification_author_idea_change_in_progress_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_in_progress_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] is currently being worked on. Thank you',$values,'','email-content');  
}



//author completed
function idea_push_notification_author_idea_change_completed_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_completed_enable',__('Author notification when status changed to completed','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_author_idea_change_completed_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_completed_subject',__('Email subject','ideapush'),'','shortcode','Your idea - [Idea Title] - has been completed',$values,'','email-subject');  
}

function idea_push_notification_author_idea_change_completed_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_completed_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has been completed. Thank you',$values,'','email-content');  
}

//author duplicate
function idea_push_notification_author_idea_change_duplicate_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_duplicate_enable',__('Author notification when status changed to duplicate','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_author_idea_change_duplicate_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_duplicate_subject',__('Email subject','ideapush'),'','shortcode','Your idea - [Idea Title] - has been marked as a duplicate and it has been merged with another idea.',$values,'','email-subject');  
}

function idea_push_notification_author_idea_change_duplicate_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_idea_change_duplicate_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], your idea: [Idea Title] has been marked as a duplicate and it has been merged with another idea. Thank you',$values,'','email-content');  
}




//author notification when voter voted on their idea
function idea_push_notification_author_voter_voted_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_author_voter_voted_enable',__('Author notification when someone votes on their idea','ideapush'),'This notification is only sent upon a positive vote.','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_author_voter_voted_subject_render() { 
    
    $values = array('Idea Title','Author First Name','Author Last Name','Board Name','Voter First Name','Voter Last Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_voter_voted_subject',__('Email subject','ideapush'),'','shortcode','Someone just voted on your idea - [Idea Title]',$values,'','email-subject');  
}

function idea_push_notification_author_voter_voted_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Author First Name','Author Last Name','Board Name','Voter First Name','Voter Last Name');
    
    idea_push_settings_code_generator('idea_push_notification_author_voter_voted_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Author First Name], [Voter First Name] just voted on your idea: [Idea Title]. Thank you',$values,'','email-content');  
}








   












//voter review
function idea_push_notification_voter_idea_change_reviewed_enable_render() {  
    
    echo '<tr class="ideapush_settings_row" valign="top">
            <td scope="row" colspan="2">
            <div class="inside">
                <div >
                    <h3>'.__( 'Positive Voter Notifications', 'ideapush' ).'</h3>
                </div>
            </div>
        </td>
    </tr>';
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_reviewed_enable',__('Voter notification when status changed to review','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_voter_idea_change_reviewed_subject_render() { 
    
    $values = array('Idea Title','Voter First Name','Voter Last Name','Vote Count','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_reviewed_subject',__('Email subject','ideapush'),'','shortcode','An idea you voted on is now being reviewed',$values,'','email-subject');  
}

function idea_push_notification_voter_idea_change_reviewed_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Voter First Name','Voter Last Name','Author First Name','Author Last Name','Vote Count','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_reviewed_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Voter First Name], an idea you voted on: [Idea Title] has now reached [Vote Count] votes and is now being reviewed. Thank you',$values,'','email-content');  
}



//voter approved
function idea_push_notification_voter_idea_change_approved_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_approved_enable',__('Voter notification when status changed to approved','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_voter_idea_change_approved_subject_render() { 
    
    $values = array('Idea Title','Voter First Name','Voter Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_approved_subject',__('Email subject','ideapush'),'','shortcode','An idea you voted on is now approved',$values,'','email-subject');  
}

function idea_push_notification_voter_idea_change_approved_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Voter First Name','Voter Last Name','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_approved_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Voter First Name], an idea you voted on: [Idea Title] is now approved. Thank you',$values,'','email-content');  
}





//voter declined
function idea_push_notification_voter_idea_change_declined_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_declined_enable',__('Voter notification when status changed to declined','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_voter_idea_change_declined_subject_render() { 
    
    $values = array('Idea Title','Voter First Name','Voter Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_declined_subject',__('Email subject','ideapush'),'','shortcode','An idea you voted on has been declined',$values,'','email-subject');  
}

function idea_push_notification_voter_idea_change_declined_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Voter First Name','Voter Last Name','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_declined_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Voter First Name], an idea you voted on: [Idea Title] has been declined. Thank you',$values,'','email-content');  
}


//voter inprogress
function idea_push_notification_voter_idea_change_in_progress_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_in_progress_enable',__('Voter notification when status changed to in progress','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_voter_idea_change_in_progress_subject_render() { 
    
    $values = array('Idea Title','Voter First Name','Voter Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_in_progress_subject',__('Email subject','ideapush'),'','shortcode','An idea you voted on is now being worked on',$values,'','email-subject');  
}

function idea_push_notification_voter_idea_change_in_progress_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Voter First Name','Voter Last Name','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_in_progress_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Voter First Name], an idea you voted on: [Idea Title] is now being worked on. Thank you',$values,'','email-content');  
}



//voter completed
function idea_push_notification_voter_idea_change_completed_enable_render() {  
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_completed_enable',__('Voter notification when status changed to completed','ideapush'),'','checkbox','','','','enable-email-notification-checkbox');   
}


function idea_push_notification_voter_idea_change_completed_subject_render() { 
    
    $values = array('Idea Title','Voter First Name','Voter Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_completed_subject',__('Email subject','ideapush'),'','shortcode','An idea you voted on has now been completed',$values,'','email-subject');  
}

function idea_push_notification_voter_idea_change_completed_content_render() { 
    
    $values = array('Idea Title','Idea Content','Idea Link','Voter First Name','Voter Last Name','Author First Name','Author Last Name','Board Name');
    
    idea_push_settings_code_generator('idea_push_notification_voter_idea_change_completed_content',__('Email content','ideapush'),'','textarea-advanced','Hi [Voter First Name], an idea you voted on: [Idea Title] has now been completed. Thank you',$values,'','email-content');  
}





//status settings
function idea_push_change_open_status_render() { 
    idea_push_settings_code_generator('idea_push_change_open_status',__('Change Open Status Name','ideapush'),'If you leave this blank the default "Open" status will be used.','text','','','','');  
}

function idea_push_change_reviewed_status_render() { 
    idea_push_settings_code_generator('idea_push_change_reviewed_status',__('Change Reviewed Status Name','ideapush'),'If you leave this blank the default "Reviewed" status will be used.','text','','','','');  
}

function idea_push_change_approved_status_render() { 
    idea_push_settings_code_generator('idea_push_change_approved_status',__('Change Approved Status Name','ideapush'),'If you leave this blank the default "Approved" status will be used.','text','','','','');  
}

function idea_push_change_declined_status_render() { 
    idea_push_settings_code_generator('idea_push_change_declined_status',__('Change Declined Status Name','ideapush'),'If you leave this blank the default "Declined" status will be used.','text','','','','');  
}

function idea_push_change_in_progress_status_render() { 
    idea_push_settings_code_generator('idea_push_change_in_progress_status',__('Change In Progress Status Name','ideapush'),'If you leave this blank the default "In Progress" status will be used.','text','','','','');  
}

function idea_push_change_completed_status_render() { 
    idea_push_settings_code_generator('idea_push_change_completed_status',__('Change Completed Status Name','ideapush'),'If you leave this blank the default "Completed" status will be used.','text','','','','');  
}

function idea_push_change_all_statuses_status_render() { 
    idea_push_settings_code_generator('idea_push_change_all_statuses_status',__('Change All Statuses Name','ideapush'),'If you leave this blank the default "All Statuses" status will be used.','text','','','','');  
}

function idea_push_change_duplicate_status_render() { 
    idea_push_settings_code_generator('idea_push_change_duplicate_status',__('Change Duplicate Status Name','ideapush'),'If you leave this blank the default "Duplicate" status will be used. This is only used in the pro version of the plugin.','text','','','','');  
}


//design settings
function idea_push_primary_link_colour_render() {                                     
    idea_push_settings_code_generator('idea_push_primary_link_colour',__('Primary Colour of Links','ideapush'),'It is advised not to pick white as otherwise certain user interface elements will be hidden!','color','#dd3333','','','tooltipcolors');   
}


function idea_push_custom_css_render() {     
    
    $values = array(array('Remove arrow from idea submit buttom','.submit-new-idea i {display: none !important;}'),array('Hide empty related images','.no-related-image {display: none !important;}'),array('Hide breadcrumbs on single idea page','.idea-item-breadcrumbs {display: none !important;}'),array('Prevent growing search bar','.ideapush-search-input {width: 125px !important;}'),array('Remove search bar','.ideapush-idea-search {display: none !important;}'),array('Remove description field','.ideapush-form-idea-description {display: none !important;}'));
    
    idea_push_settings_code_generator('idea_push_custom_css',__('Custom CSS','ideapush'),'Click on shortcodes or enter your custom CSS to change the look of the plugin.','shortcode-advanced','',$values,'','');   
    
}



//idea form settings

function idea_push_form_title_render() { 
    idea_push_settings_code_generator('idea_push_form_title',__('Form Title','ideapush'),'Change the text which appears at the top of the form. If left blank this will be "Push your idea".','text','','','','hidden-row');  
}

function idea_push_idea_title_render() { 
    idea_push_settings_code_generator('idea_push_idea_title',__('Idea Title','ideapush'),'Change the placeholder text of the idea title field. If left blank this will be "Idea title".','text','','','','hidden-row');  
}

function idea_push_idea_description_render() { 
    idea_push_settings_code_generator('idea_push_idea_description',__('Idea Description','ideapush'),'Change the placeholder text of the idea description field. If left blank this will be "Add additional details".','text','','','','hidden-row');  
}

function idea_push_idea_tags_render() { 
    idea_push_settings_code_generator('idea_push_idea_tags',__('Tags Text','ideapush'),'Change the placeholder text of the tags field. If left blank this will be "Tags".','text','','','','hidden-row');  
}

function idea_push_attachment_text_render() { 
    idea_push_settings_code_generator('idea_push_attachment_text',__('Idea Attachment Text','ideapush'),'If left blank this will be "Attach image".','text','','','','hidden-row');  
}

function idea_push_allowed_file_types_render() { 
    idea_push_settings_code_generator('idea_push_allowed_file_types',__('Allowed File Types','ideapush'),'Please enter file types separated by comma.','text','jpg,png','','','');  
}

function idea_push_submit_button_render() { 
    idea_push_settings_code_generator('idea_push_submit_button',__('Submit Button','ideapush'),'If left blank this will be "Push".','text','','','','hidden-row');  
}

function idea_push_submit_idea_button_render() { 
    idea_push_settings_code_generator('idea_push_submit_idea_button',__('Submit Idea Button','ideapush'),'This button appears on the mobile display and when clicked it displays the new idea form. If left blank this will be "Submit new idea".','text','','','','hidden-row');  
}

function idea_push_recaptcha_site_key_render() { 
    idea_push_settings_code_generator('idea_push_recaptcha_site_key',__('reCAPTCHA Site Key','ideapush'),'If you would like to enable Google reCAPTCHA on the registration form please enter in your reCAPTCHA Site Key in this setting. You can get your reCAPTCHA V2 key from <a target="_blank" href="https://www.google.com/recaptcha/admin#list">here</a>. If left blank no reCAPTCHA will be used.','text','','','','');  
}

function idea_push_form_settings_render() { 

    //get options
    $options = get_option('idea_push_settings');

    //get current option
    $currentOption = $options['idea_push_form_settings'];

    // print_r($currentOption);

    //creat container
    $html = '<tr class="ideapush_settings_row" valign="top"><td scope="row" colspan="2">';
    $html .= '<ul id="form-settings-container">';


    function idea_push_form_setting_builder($currentOption){

        $html = '';

        //get the options and split it into chunks
        if(strpos($currentOption, '^^^^') !== false){
            $explodedOptions = explode('^^^^',$currentOption);
        } else {
            $explodedOptions = explode('||||',$currentOption);   
        }

        //if the values are blank actually make blank
        function idea_push_replace_blank_value($input){
            if($input == ' '){
                return '';    
            } else {
                return $input;  
            }
        }


        foreach($explodedOptions as $formSetting){

            $explodedSubOptions = explode('|||',$formSetting);

            $settingName = $explodedSubOptions[0];

            // print_r($explodedSubOptions);

            if(strlen($settingName)>0){

                if($settingName == 'Default'){
                    $formSettingClass = 'default-form-setting';
                } else {
                    $formSettingClass = '';    
                }

                $html .= '<li class="form-setting '.$formSettingClass.'">';
                    $html .= '<div class="form-setting-inner">';

                        if($settingName == 'Default'){
                            $formSettingNameClass = 'readonly';
                        } else {
                            $formSettingNameClass = '';    
                        }

                        $html .= '<input class="form-setting-name" type="text" value="'.$settingName.'" '.$formSettingNameClass.'>';

                        $html .= '<div class="form-setting-tools">';
                            $html .= '<i name="Edit Form Setting" class="fa fa-pencil-square-o edit-form-settings" aria-hidden="true"></i>';

                            global $ideapush_is_pro;

                            if($ideapush_is_pro == "YES"){
                                $html .= '<i name="Duplicate Form Setting" class="fa fa-clone duplicate-form-settings" aria-hidden="true"></i>';
                            }    
                            
                            $html .= '<i name="Delete Form Setting" class="fa fa-trash-o delete-form-settings" aria-hidden="true"></i>';
                            


                        $html .= '</div>';

                $html .= '</div>';
                $html .= '<div class="form-setting-inner form-setting-inner-expanded-setting">';

                    //standard options
                    $standardOptions = $explodedSubOptions[1];
                    $standardOptionsExploded = explode('||',$standardOptions);

                    

                    
                    $html .= '<strong>Standard Field Labels</strong>';

                    $html .= '<table>';

                        //form title
                        $html .= '<tr><td>';
                            $html .= '<label>'.__( 'Form Title', 'ideapush' );
                            $html .= ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
                            $html .= '<p class="hidden"><em>'.__( 'Change the text which appears at the top of the form. If left blank this will be "Push your idea".', 'ideapush' ).'</em></p>';
                            $html .= '</label>';
                        $html .= '</td>';

                        $html .= '<td>';
                            $html .= '<input class="form-title" type="text" value="'.idea_push_replace_blank_value($standardOptionsExploded[0]).'">';  
                        $html .= '</td></tr>';


                        //idea title
                        $html .= '<tr><td>';
                            $html .= '<label>'.__( 'Idea Title', 'ideapush' );
                            $html .= ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
                            $html .= '<p class="hidden"><em>'.__( 'Change the placeholder text of the idea title field. If left blank this will be "Idea title".', 'ideapush' ).'</em></p>';
                            $html .= '</label>';
                        $html .= '</td>';

                        $html .= '<td>';
                            $html .= '<input class="idea-title" type="text" value="'.idea_push_replace_blank_value($standardOptionsExploded[1]).'">';  
                        $html .= '</td></tr>';



                        //idea description
                        $html .= '<tr><td>';
                            $html .= '<label>'.__( 'Idea Description', 'ideapush' );
                            $html .= ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
                            $html .= '<p class="hidden"><em>'.__( 'Change the placeholder text of the idea description field. If left blank this will be "Add additional details".', 'ideapush' ).'</em></p>';
                            $html .= '</label>';
                        $html .= '</td>';

                        $html .= '<td>';
                            $html .= '<input class="idea-description" type="text" value="'.idea_push_replace_blank_value($standardOptionsExploded[2]).'">';  
                        $html .= '</td></tr>';

                        //idea tags
                        $html .= '<tr><td>';
                            $html .= '<label>'.__( 'Tags Text', 'ideapush' );
                            $html .= ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
                            $html .= '<p class="hidden"><em>'.__( 'Change the placeholder text of the tags field. If left blank this will be "Tags".', 'ideapush' ).'</em></p>';
                            $html .= '</label>';
                        $html .= '</td>';

                        $html .= '<td>';
                            $html .= '<input class="idea-tags" type="text" value="'.idea_push_replace_blank_value($standardOptionsExploded[3]).'">';  
                        $html .= '</td></tr>';

                        //idea attachment text
                        $html .= '<tr><td>';
                            $html .= '<label>'.__( 'Idea Attachment Text', 'ideapush' );
                            $html .= ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
                            $html .= '<p class="hidden"><em>'.__( 'If left blank this will be "Attach image".', 'ideapush' ).'</em></p>';
                            $html .= '</label>';
                        $html .= '</td>';

                        $html .= '<td>';
                            $html .= '<input class="idea-attachment" type="text" value="'.idea_push_replace_blank_value($standardOptionsExploded[4]).'">';  
                        $html .= '</td></tr>';

                        //submit button
                        $html .= '<tr><td>';
                            $html .= '<label>'.__( 'Submit Button', 'ideapush' );
                            $html .= ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
                            $html .= '<p class="hidden"><em>'.__( 'If left blank this will be "Push".', 'ideapush' ).'</em></p>';
                            $html .= '</label>';
                        $html .= '</td>';

                        $html .= '<td>';
                            $html .= '<input class="submit-button" type="text" value="'.idea_push_replace_blank_value($standardOptionsExploded[5]).'">';  
                        $html .= '</td></tr>';

                        //submit idea button
                        $html .= '<tr><td>';
                            $html .= '<label>'.__( 'Submit Idea Button', 'ideapush' );
                            $html .= ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
                            $html .= '<p class="hidden"><em>'.__( 'This button appears on the mobile display and when clicked it displays the new idea form. If left blank this will be "Submit new idea".', 'ideapush' ).'</em></p>';
                            $html .= '</label>';
                        $html .= '</td>';

                        $html .= '<td>';
                            $html .= '<input class="submit-idea-button" type="text" value="'.idea_push_replace_blank_value($standardOptionsExploded[6]).'">';  
                        $html .= '</td></tr>';



                    $html .= '</table>';
                            

                $html .= '</div>';

                //pro options
                global $ideapush_is_pro;

                if($ideapush_is_pro == "YES"){

                    $proOptions = $explodedSubOptions[2];

                    $html .= '<div class="form-setting-inner form-setting-inner-expanded-setting">';
                        $html .= '<strong>Custom Fields</strong>';
                        $html .= '<ul class="form-setting-pro-options">';
                           $html .= idea_push_form_settings_pro_options($proOptions);
                        $html .= '</ul>';
                    $html .= '</div>';
                }
                
            $html .= '</li>';

            }

        }

        return $html;
    }


    //here we run check whether setting exists or not, if it does use the option and send to function, otherwise create a new string using the existing settings
    if(isset($currentOption) && strlen($currentOption)>0){
        $html .= idea_push_form_setting_builder($currentOption);
    } elseif(isset($options['idea_push_form_title']) && strlen($options['idea_push_form_title'])>0) {
        //build the option from existing settings
        $oldSettings = '||||Default|||'.$options['idea_push_form_title'].'||'.$options['idea_push_idea_title'].'||'.$options['idea_push_idea_description'].'||'.$options['idea_push_idea_tags'].'||'.$options['idea_push_attachment_text'].'||'.$options['idea_push_submit_button'].'||'.$options['idea_push_submit_idea_button'].'||';
        
        $html .= idea_push_form_setting_builder($oldSettings);

    } else {
        $noSettings = '||||Default||| || || || || || || ||';
        $html .= idea_push_form_setting_builder($noSettings);
    }

    



    $html .= '</ul>';
    $html .= '</td></tr>';

    echo $html;

    idea_push_settings_code_generator('idea_push_form_settings',__('Form Settings','ideapush'),'','text','','','','hidden-row');  
}

function idea_push_enable_bot_protection_render() { 
    idea_push_settings_code_generator('idea_push_enable_bot_protection',__('Enable Bot Protection','ideapush'),'By checking this option we will add a math problem and honeypot to the form to reduce spam.','checkbox','','','','');  
}

function idea_push_disable_profile_edit_render() { 
    idea_push_settings_code_generator('idea_push_disable_profile_edit',__('Disable Profile Editing','ideapush'),'By checking this option users won\'t be able to edit their profile by clicking Edit under their name.','checkbox','','','','');  
}


function idea_push_privacy_confirmation_render() { 
    idea_push_settings_code_generator('idea_push_privacy_confirmation',__('Privacy Confirmation Message','ideapush'),'Please enter a privacy confirmation message. Please leave blank for no confirmation. The checkbox will be unchecked by default and the form will not submit unless the checkbox is checked.','textarea-advanced','','','','');  
}

function idea_push_max_file_size_render() { 
    idea_push_settings_code_generator('idea_push_max_file_size',__('File Upload Max Size (MB)','ideapush'),'','number','5','','','');  
}




//tag page settings
function idea_push_tag_pagination_number_render() {     
    idea_push_settings_code_generator('idea_push_tag_pagination_number',__('Pagination number','ideapush'),'Choose how many ideas to show per a page. Please set to -1 or 0 if you want to show all ideas in an endless scroll.','number','','','','');   
}

function idea_push_tag_multiple_ips_render() {     

    $values = array("No"=>"No", "Yes"=>"Yes");


    idea_push_settings_code_generator('idea_push_tag_multiple_ips',__('Multiple IPs','ideapush'),'By default IdeaPush prevents people creating multiple user accounts with the same IP address to prevent vote rigging. However this may not work for you especially if you have a work network with a shared IP address where employees vote for example. So in this case please set this setting to yes.','select','',$values,'','');   
}



//board settings
function idea_push_board_configuration_render(){         
    idea_push_settings_code_generator('idea_push_board_configuration',__('Board Configuration','ideapush'),'','text','','','','hidden-row'); 
    
}


//hide admin notice
function idea_push_hide_admin_notice_render() {     
    idea_push_settings_code_generator('idea_push_hide_admin_notice',__('Hide admin notice','ideapush'),'','checkbox','','','','hidden-row');   
}




function idea_push_disable_approved_status_render() {                                     
    idea_push_settings_code_generator('idea_push_disable_approved_status',__('Disable the approved status','ideapush'),'','checkbox','','','','');   
}

function idea_push_disable_declined_status_render() {                                     
    idea_push_settings_code_generator('idea_push_disable_declined_status',__('Disable the declined status','ideapush'),'','checkbox','','','','');   
}

function idea_push_disable_in_progress_status_render() {                                     
    idea_push_settings_code_generator('idea_push_disable_in_progress_status',__('Disable the in progress status','ideapush'),'','checkbox','','','','');   
}

function idea_push_disable_completed_status_render() {                                     
    idea_push_settings_code_generator('idea_push_disable_completed_status',__('Disable the completed status','ideapush'),'','checkbox','','','','');   
}

function idea_push_disable_all_statuses_status_render() {                                     
    idea_push_settings_code_generator('idea_push_disable_all_statuses_status',__('Disable the all statuses status','ideapush'),'','checkbox','','','','');   
}












//function to generate settings rows
function idea_push_settings_code_generator($id,$label,$description,$type,$default,$parameter,$importantNote,$rowClass) {
    
    //get options
    $options = get_option('idea_push_settings');
    

    //if it is a checkbox we need to slightly different value logic
    if($type == 'checkbox'){
        if(isset($options[$id])){  
            $value = intval($options[$id]);    
        } else {
            $value = 0;
        }
    } else {
        //value
        if(isset($options[$id])){  
            $value = $options[$id];    
        } elseif(strlen($default)>0) {
            $value = $default;   
        } else {
            $value = '';
        }
    }

    
    
    
    //the label
    echo '<tr class="ideapush_settings_row '.$rowClass.'" valign="top">';
    echo '<td scope="row">';
    echo '<label for="'.$id.'">'.$label;
    if(strlen($description)>0){
        echo ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
        echo '<p class="hidden"><em>'.$description.'</em></p>';
    }
    if(strlen($importantNote)>0){
        echo '</br><span style="color: #CC0000;">';
        echo $importantNote;
        echo '</span>';
    } 
    echo '</label>';
    
    
    
    if($type == 'shortcode') {
        echo '</br>';
        
        if(is_array($parameter)){
            foreach($parameter as $shortcodevalue){
                echo '<a value="['.$shortcodevalue.']" class="ideapush_append_buttons">['.$shortcodevalue.']</a>';
            }    
        }   
    }
    
    if($type == 'textarea-advanced') {
        echo '</br>';
        
        //only loop through the values if paramter is an array 

        if(is_array($parameter)){
            foreach($parameter as $shortcodevalue){
                echo '<a value="['.$shortcodevalue.']" data="'.$id.'" class="ideapush_append_buttons_advanced">['.$shortcodevalue.']</a>';
            }  
        }

             
    }
    
    
    if($type == 'shortcode-advanced') {
        echo '</br>';
        if(is_array($parameter)){
            foreach($parameter as $shortcodevalue){
                echo '<a value="'.$shortcodevalue[1].'" class="ideapush_append_buttons">'.$shortcodevalue[0].'</a>';
            } 
        }       
    }
    
    

    //the setting    
    echo '</td><td>';
    
    //text
    if($type == "text"){
        echo '<input type="text" class="regular-text" name="idea_push_settings['.$id.']" id="'.$id.'" value="'.$value.'">';     
    }
    
    //select
    if($type == "select"){
        echo '<select name="idea_push_settings['.$id.']" id="'.$id.'">';
        
        if(is_array($parameter)){
            foreach($parameter as $x => $xvalue){
                echo '<option value="'.$x.'" ';
                if($x == $value) {
                    echo 'selected="selected"';    
                }
                echo '>'.$xvalue.'</option>';
            }
        }
        echo '</select>';
    }
    
    
    //checkbox
    if($type == "checkbox"){
        echo '<label class="switch">';
        echo '<input type="checkbox" id="'.$id.'" name="idea_push_settings['.$id.']" ';
        echo checked($value,1,false);
        echo 'value="1">';
        echo '<span class="slider round"></span></label>';
    }
        
    //color
    if($type == "color"){ 
        echo '<input name="idea_push_settings['.$id.']" id="'.$id.'" type="text" value="'.$value.'" class="my-color-field" data-default-color="'.$default.'"/>';    
    }
    
    //page
    if($type == "page"){
        $args = array(
            'echo' => 0,
            'selected' => $value,
            'name' => 'idea_push_settings['.$id.']',
            'id' => $id,
            'show_option_none' => $default,
            'option_none_value' => "default",
            'sort_column'  => 'post_title',
            );
        
            echo wp_dropdown_pages($args);     
    }
    
    //textarea
    if($type == "textarea" || $type == "shortcode" || $type == "shortcode-advanced"){
        echo '<textarea cols="46" rows="3" name="idea_push_settings['.$id.']" id="'.$id.'">'.$value.'</textarea>';
    }
    
    
    //textarea-advanced
//    if($type == "textarea-advanced"){
//        wp_editor(html_entity_decode(stripslashes($value)), $id, $settings = array(
//            'textarea_name' => 'idea_push_settings['.$id.']',
//            'drag_drop_upload' => true,
//            'textarea_rows' => 7,  
//            )
//        );
//    }  
    
    
    if($type == "textarea-advanced"){
        if(isset($value)){    
            wp_editor(html_entity_decode(stripslashes($value)), $id, $settings = array(
            'wpautop' => false,    
            'textarea_name' => 'idea_push_settings['.$id.']',
            'drag_drop_upload' => true,
            'textarea_rows' => 7, 
            ));    
        } else {
            wp_editor("", $id, $settings = array(
            'wpautop' => false,    
            'textarea_name' => 'idea_push_settings['.$id.']',
            'drag_drop_upload' => true,
            'textarea_rows' => 7,
            ));         
        }
    }
    
    //number
    if($type == "number"){
        echo '<input type="number" class="regular-text" name="idea_push_settings['.$id.']" id="'.$id.'" value="'.$value.'">';     
    }

    echo '</td></tr>';

}

?>