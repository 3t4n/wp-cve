<?php

class WPForms_Views_Lite_List_Table {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'disable_add_new' ) );
		add_filter( 'views_edit-wpforms-views', array( $this, 'wpforms_views_list_header' ) );
		add_filter( 'get_edit_post_link', array( $this, 'edit_view_link' ), 100, 2 );
		add_filter( 'post_row_actions', array( $this, 'remove_quick_edit' ), 100, 2 );
	}

	/**
	 * Add Add new button and logo on WPForms views page
	 *
	 * @param [type] $views
	 * @return void
	 */
	public function wpforms_views_list_header( $views ) {
		if ( function_exists( 'wpforms' ) ) {
			$forms      = wpforms()->form->get();
			$view_forms = array();
			if ( ! empty( $forms ) ) {
				foreach ( $forms as $form ) {
					$view_forms[ $form->ID ] = $form->post_title;
				}
			}
			?>
		<script>
			var view_forms = '<?php echo addslashes( json_encode( $view_forms ) ); ?>';
		</script>
			<?php
			echo '<div class="wpforms-views-header"><img  src="' . WPFORMS_VIEWS_URL_LITE . '/assets/images/logo.jpg" class="wpforms-views-logo" alt="logo" >
		<a class=" add_new_wpform_view">Add New</a></div>
	';
		}
		return $views;
	}

	/**
	 * Update Edit View link which dislays on View title hover in View Table
	 *
	 * @param [type] $link
	 * @param [type] $post_id
	 * @return void
	 */
	public function edit_view_link( $link, $post_id ) {
		$post_type = get_post_type( $post_id );

		if ( $post_type === 'wpforms-views' ) {
			return admin_url( 'admin.php?page=wpf-views&view_id=' . $post_id );
		}

		return $link;
	}

	/**
	 * Remove Quick Edit Link for WPForms Views
	 *
	 * @param [type] $actions
	 * @param [type] $post
	 * @return void
	 */
	public function remove_quick_edit( $actions, $post ) {
		if ( $post->post_type == 'wpforms-views' ) {
			// Remove "Quick Edit"
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	function disable_add_new() {
		// Hide sidebar link
		global $submenu;
			unset( $submenu['edit.php?post_type=wpforms-views'][10] );
		// $submenu['edit.php?post_type=wpforms-views'][10][2] = 'edit.php?post_type=wpforms-views?addnew=true';
	}



}
new WPForms_Views_Lite_List_Table();
