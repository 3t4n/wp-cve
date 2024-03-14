<?php

// Exit if accessed directly.
defined('ABSPATH') or die();

if ( ! is_admin() )
	return;

class AMSPAPDMS_POST_AND_PAGE_DUPLICATOR_SETUP {

	function __construct() {
		add_action('admin_action_amspapdms_post_page_duplictor_copy_the_post', array($this, 'amspapdms_post_page_copy_the_post_via_amspapdms') );
		add_action('admin_action_amspapdms_post_page_duplictor_copy_the_post_as_new_draft', array($this, 'amspapdms_post_page_copy_the_post_via_amspapdms_as_new_draft') );
	}

	/*
	 * Creates a copy of selected post/page
	 * 
	 */
	function amspapdms_post_page_copy_the_post_via_amspapdms($status = ''){
		if ( ! ( isset( $_GET['action']) || ( isset($_REQUEST['action']) && 'amspapdms_post_page_duplictor_copy_the_post' !== $_REQUEST['action'] ) ) ) {
			wp_die(__('No post to duplicate has been supplied!', 'amspd-post-duplicator'));
		} elseif ( 'amspapdms_post_page_duplictor_copy_the_post' == $_REQUEST['action'] ) {
			
			check_admin_referer( 'amspapdms-duplicator-copy-post', 'amspapdms-dpl-ams-copy' );
			goto copy;
		}
		
		if ( ! ( isset( $_GET['action']) || ( isset($_REQUEST['action']) && 'amspapdms_post_page_duplictor_copy_the_post_as_new_draft' !== $_REQUEST['action'] ) ) ) {
			wp_die(__('No post to duplicate has been supplied!', 'amspd-post-duplicator'));
		} else {
			check_admin_referer( 'amspapdms-duplicator-copy-post', 'amspapdms-dpl-ams-draft' );
		}

	// Label
	copy:	
		
		// Get the original post
		$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
		$post = get_post($id);

		
		if ( isset( $post ) && $post != null ) {
		
			$new_id = $this->amspapdms_post_page_copy_to_new_via_amspapdms($post, $status);

			if ($status == ''){
				// Redirect to the post list screen
				wp_redirect( admin_url( 'edit.php?post_type='.$post->post_type) );
			} else {
				// Redirect to the edit screen for the new draft post
				wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_id ) );
			}
			exit;

		} else {
			$post_type_obj = get_post_type_object( $post->post_type );
			wp_die(esc_html_e('Copy creation failed, could not find original:', 'amspd-post-duplicator') . ' ' . htmlspecialchars($id));
		}
	}

	/*
	 * Creates a copy of selected post/page 
	 * then redirects to the edit post/page screen
	 */
	function amspapdms_post_page_copy_the_post_via_amspapdms_as_new_draft(){
		$this->amspapdms_post_page_copy_the_post_via_amspapdms('draft');
	}

	// Copy the post and insert it
	function amspapdms_post_page_copy_to_new_via_amspapdms($post, $status = '', $parent_id = '') {
		if ($post->post_type == 'revision') 
			return;

		if ($post->post_type != 'attachment')
			$status = 'draft';
		
		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		$new_post = array(
			'post_content' => $post->post_content,
			'page_template' => get_page_template_slug( $post->ID ),
			'menu_order' => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status' => $post->ping_status,
			'post_author' => $new_post_author,
			'post_type' => $post->post_type,
			'post_excerpt' =>  $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent' => $post->post_parent,
			'post_password' => $post->post_password,
			'post_status' => $status,
			'post_title' => $post->post_title,
		);

		$amspapdms_new_post_id = wp_insert_post($new_post);
		
		$amspapdms_old_post_id = $post->ID;
		$amspapdms_post_type = $post->post_type;
		
		// Copies meta info
		if (file_exists ( AMSPAPDMS_DIR .'/includes/amspapd-post-and-page-duplictor-post_meta_info.php' ) )
			include_once ( AMSPAPDMS_DIR .'/includes/amspapd-post-and-page-duplictor-post_meta_info.php' );
		
		$amspapdms_post_meta_info = new AMSPAPDMS_POST_DUPLICATOR_META_INFO_COPY();
		 
		$amspapdms_post_meta_info->amspapdms_post_and_page_duplicator_copy_meta_info_via_amspapdms( $amspapdms_new_post_id, $amspapdms_old_post_id, $amspapdms_post_type );
		
		return $amspapdms_new_post_id;
	}
}

$amspapdms_post_and_page_duplicator = new AMSPAPDMS_POST_AND_PAGE_DUPLICATOR_SETUP();
?>