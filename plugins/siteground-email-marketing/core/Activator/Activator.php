<?php
namespace SG_Email_Marketing\Activator;

class Activator {
	/**
	 * Run on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		if ( ! empty( get_option( 'sg_email_marketing_initial_activation', 0 ) ) ) {
			return;
		}

		wp_insert_post(
			array(
				'post_type' => 'sg_form',
				'post_status' => 'publish',
				'post_title' => 'My First Form',
				'post_content' => '{"title":[{"id":"0","type":"text","sg-form-type":"title","required":2,"placeholder":"Newsletter Subscription Form","css":"","fieldName":"Your Form Title"},{"id":"1","type":"text","sg-form-type":"description","required":0,"placeholder":"","css":"","fieldName":"Your Form Description"}],"fields":[{"id":"2","type":"email","sg-form-type":"email","label":"Email","required":2,"placeholder":"Your Email address","css":"","value":"","placeholderValue":"","fieldName":"Email"},{"id":"0","type":"name","sg-form-type":"first-name","label":"Name","required":0,"placeholder":"First Name","css":"","value":"","placeholderValue":"","fieldName":"First Name"},{"id":"1","type":"name","sg-form-type":"last-name","label":"Last Name","required":0,"placeholder":"Last Name","css":"","value":"","placeholderValue":"","fieldName":"Last Name"},{"id":"3","type":"button","sg-form-type":"button","label":"Sign up","required":2,"placeholder":"Submit button","css":"","value":"Submit","placeholderValue":"","fieldName":"Button"}],"settings":{"form_title":"My First Form","submit_text":"Submit","success_message":"Thank you for subscribing!","labels":[]}}',
			)
		);

		update_option( 'sg_email_marketing_initial_activation', 1 );
	}
}
