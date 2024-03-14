<?php
namespace SG_Email_Marketing\Install_Service;

/**
 * The instalation package version class.
 */
class Install_1_1_1 extends Install {

	/**
	 * The default install version. Overridden by the installation packages.
	 *
	 * @since 1.1.1
	 *
	 * @access protected
	 *
	 * @var string $version The install version.
	 */
	protected static $version = '1.1.1';

	/**
	 * Run the install procedure.
	 *
	 * @since 1.1.1
	 */
	public function install() {
		$this->maybe_update_post_content();
	}

	/**
	 * Updates the first post's content if needed.
	 *
	 * @since 1.1.1
	 */
	public function maybe_update_post_content() {
		// Get the first post of the specified post type.
		$posts = get_posts( array(
			'post_type'      => 'sg_form',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		) );

		if ( empty( $posts ) ) {
			return;
		}

		// Check if the string "fieldName" exists in the post content.
		if ( strpos( $posts[0]->post_content, 'fieldName' ) === false ) {
			// If "fieldName" doesn't exist, update the entire post content.
			wp_update_post( array(
				'ID'           => $posts[0]->ID,
				'post_content' => '{"title":[{"id":"0","type":"text","sg-form-type":"title","required":2,"placeholder":"Newsletter Subscription Form","css":"","fieldName":"Your Form Title"},{"id":"1","type":"text","sg-form-type":"description","required":0,"placeholder":"","css":"","fieldName":"Your Form Description"}],"fields":[{"id":"2","type":"email","sg-form-type":"email","label":"Email","required":2,"placeholder":"Your Email address","css":"","value":"","placeholderValue":"","fieldName":"Email"},{"id":"0","type":"name","sg-form-type":"first-name","label":"Name","required":0,"placeholder":"First Name","css":"","value":"","placeholderValue":"","fieldName":"First Name"},{"id":"1","type":"name","sg-form-type":"last-name","label":"Last Name","required":0,"placeholder":"Last Name","css":"","value":"","placeholderValue":"","fieldName":"Last Name"},{"id":"3","type":"button","sg-form-type":"button","label":"Sign up","required":2,"placeholder":"Submit button","css":"","value":"Submit","placeholderValue":"","fieldName":"Button"}],"settings":{"form_title":"My First Form","submit_text":"Submit","success_message":"Thank you for subscribing!","labels":[]}}',
			) );
		}
	}
}
