<?php

// Exit if accessed directly
defined('ABSPATH') or die();

class AMSPAPDMS_POST_AND_PAGE_DUPLICATOR_SETUP_MENU {

	function __construct() {
		add_filter('post_row_actions', array($this, 'amspapdms_post_duplictor_admin_post_menu_list_link'),10,2);
		add_filter('page_row_actions', array($this, 'amspapdms_post_duplictor_admin_post_menu_list_link'),10,2);
		add_action( 'post_submitbox_misc_actions', array($this, 'amspapdms_post_duplictor_edit_post_screen_link') );
	}

	/**
	 * Add links to the post and page
	 */
	function amspapdms_post_duplictor_admin_post_menu_list_link($actions, $post) {
		if ( ! current_user_can( 'edit_posts' ) ) 
			return;
			
			$actions['clone'] = '<a href="' . $this->amspapdms_post_page_links_via_amspapdms( $post->ID , 'display', false).
			'">' .  __('Copy As New Draft', 'amspd-post-duplicator') . '</a>';
			
			$actions['edit_as_new_draft'] = '<a href="'. $this->amspapdms_post_page_links_via_amspapdms( $post->ID ) . 
			'">' .  __('Edit As New Draft', 'amspd-post-duplicator') . '</a>';
		
		return $actions;
	}

	/**
	 * Add a link in the post/page edit screen
	 */
	function amspapdms_post_duplictor_edit_post_screen_link() {
		if ( isset( $_GET['post'] ) && current_user_can( 'edit_posts' ) ) {
			?>
	<div id="duplicate-action" style="margin-bottom: 10px;">
		<a class="submitduplicate duplication misc-pub-section"
			href="<?php echo esc_url($this->amspapdms_post_page_links_via_amspapdms( $_GET['post']) ) ?>"><?php esc_html_e('Edit As New Draft', 'amspd-post-duplicator'); ?>
		</a>
	</div>
			<?php
		}
	}

	/**
	 * Get post links
	 *
	 * @param int $id Optional
	 * @param string $context
	 * @param string $draft
	 * @return string
	 */
	function amspapdms_post_page_links_via_amspapdms( $id = 0, $context = 'display', $draft = true ) {
		if ( ! current_user_can( 'edit_posts' ) )
			return;

		if ( ! $post = get_post( $id ) )
			return;

		if ($draft)
			$amspapdms_action_label = "amspapdms_post_page_duplictor_copy_the_post_as_new_draft";
		else
			$amspapdms_action_label = "amspapdms_post_page_duplictor_copy_the_post";

		if ( 'display' == $context )
			$amspapdms_action_name = '?action=' . $amspapdms_action_label . '&amp;post=' . $post->ID;
		else
			$amspapdms_action_name = '?action=' . $amspapdms_action_label . '&post=' . $post->ID;
		
		$post_type_object = get_post_type_object( $post->post_type );
		
		if ( ! $post_type_object )
			return;

		$amspapdms_action_name = admin_url( "admin.php". $amspapdms_action_name );
		
		if ($draft)
			$amspapdms_action_name = wp_nonce_url( $amspapdms_action_name, 'amspapdms-duplicator-copy-post', 'amspapdms-dpl-ams-draft' );
		else
			$amspapdms_action_name = wp_nonce_url( $amspapdms_action_name, 'amspapdms-duplicator-copy-post', 'amspapdms-dpl-ams-copy' );
		
		return apply_filters( 'amspapdms_post_page_links_via_amspapdms', $amspapdms_action_name, $post->ID, $context );
	}
}
new AMSPAPDMS_POST_AND_PAGE_DUPLICATOR_SETUP_MENU();