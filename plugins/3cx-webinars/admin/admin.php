<?php

require_once WP3CXW_PLUGIN_DIR . '/admin/includes/admin-functions.php';
require_once WP3CXW_PLUGIN_DIR . '/admin/includes/help-tabs.php';
//require_once WP3CXW_PLUGIN_DIR . '/admin/includes/tag-generator.php';

add_action( 'admin_init', 'wp3cxw_admin_init' );

function wp3cxw_admin_init() {
	do_action( 'wp3cxw_admin_init' );
}

add_action( 'admin_menu', 'wp3cxw_admin_menu', 9 );

function wp3cxw_admin_menu() {
	global $_wp_last_object_menu;

	$_wp_last_object_menu++;

	add_menu_page( __( '3CX Webinars', '3cx-webinar' ),
		__( '3CX Webinars', '3cx-webinar' ),
		'wp3cxw_read_webinar_forms', 'wp3cxw',
		'wp3cxw_admin_management_page', ' data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMjU2LjAwMDAwMHB0IiBoZWlnaHQ9IjI1Ni4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDI1Ni4wMDAwMDAgMjU2LjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgcG90cmFjZSAxLjE1LCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxNwo8L21ldGFkYXRhPgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwyNTYuMDAwMDAwKSBzY2FsZSgwLjEwMDAwMCwtMC4xMDAwMDApIgpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPgo8cGF0aCBkPSJNNjk3IDIxMzkgYy05MSAtMjEgLTE2OSAtODcgLTIwOCAtMTc0IC0yNyAtNTggLTMxIC0xNjggLTEwIC0yMjggMjAKLTU4IDgyIC0xMjkgMTQxIC0xNjEgNzIgLTM5IDE4MCAtNDcgMjU1IC0xOSA0OCAxOCA1MSAyMSA3MiA4MSAyNSA2OSA3MyAxNDYKMTA0IDE2NSAzOCAyNCAxOCAxMzkgLTM4IDIyMCAtMzYgNTIgLTEwNSA5OCAtMTcyIDExNCAtNjMgMTQgLTkwIDE1IC0xNDQgMnoiLz4KPHBhdGggZD0iTTE3MTUgMjEzNyBjLTk3IC0yNSAtMTc1IC05OCAtMjEwIC0xOTYgLTI1IC03MyAtMjEgLTg5IDQxIC0xNDkgNTcKLTU2IDk2IC0xMjYgMTA5IC0xOTUgNiAtMzEgMTIgLTM2IDUxIC00NyA3MCAtMjAgMTcxIC04IDIzNCAyNiA1OSAzMiAxMjEgMTAzCjE0MSAxNjEgMjEgNjAgMTYgMTY5IC0xMCAyMjkgLTU4IDEzMyAtMjEzIDIwNyAtMzU2IDE3MXoiLz4KPHBhdGggZD0iTTEyMzMgMTgzMCBjLTU4IC0xMiAtMTEyIC00MiAtMTU1IC04NiAtMTQzIC0xNDMgLTEwMyAtMzkyIDc5IC00ODQKNDAgLTIxIDYyIC0yNSAxMzggLTI1IDc2IDAgOTggNCAxMzggMjUgMTgxIDkyIDIyMiAzMzkgODAgNDg1IC02NyA2OSAtMTg1CjEwNCAtMjgwIDg1eiIvPgo8cGF0aCBkPSJNNjA1IDE1MjMgYy0xMDYgLTEzIC0xODQgLTUyIC0yNTQgLTEyOSAtODMgLTkxIC05NCAtMTMzIC05OSAtMzc1CmwtNCAtMjA2IDk5IC0yNiBjMTIxIC0zMiAyNjMgLTU3IDMyNiAtNTcgbDQ3IDAgMCA4NSBjMCAxODggOTUgMzM5IDI2NSA0MjAKMzAgMTQgNTUgMjkgNTUgMzIgMCA0IC0xMSAxOCAtMjQgMzIgLTM1IDM4IC02NCA5NyAtNzggMTYwIC02IDMxIC0xNyA1OSAtMjIKNjMgLTExIDggLTI1MyA4IC0zMTEgMXoiLz4KPHBhdGggZD0iTTE2NTMgMTQ3MiBjLTEzIC03MCAtNDYgLTE0MiAtODAgLTE3NCAtMTMgLTEyIC0yMyAtMjUgLTIzIC0yOSAwIC01CjI1IC0yMCA1NSAtMzQgMTcxIC04MSAyNjUgLTIzMSAyNjUgLTQyNCBsMCAtODkgNjMgNSBjOTIgNyAyNTQgNDEgMzE3IDY2IGw1NQoyMiAwIDIwNSBjLTEgMTg5IC0zIDIwOSAtMjQgMjYyIC0yNiA2OCAtMTA2IDE2MCAtMTY1IDE5MSAtNzcgNDAgLTExOCA0OAotMjg2IDU0IGwtMTY1IDYgLTEyIC02MXoiLz4KPHBhdGggZD0iTTExMzUgMTIxNCBjLTE1MSAtMjIgLTI1OSAtOTcgLTMxOSAtMjI0IGwtMzEgLTY1IC0zIC0yMTEgLTMgLTIxMgo3OCAtMjAgYzIyMSAtNjAgNDIyIC04MSA1OTcgLTYzIDEyMyAxMiAyNDAgMzkgMzEyIDcwIGw0NSAyMCAtMyAyMDggLTMgMjA4Ci0zMSA2NSBjLTQ4IDEwMSAtMTI4IDE3MSAtMjM5IDIwOCAtNDAgMTMgLTMzNyAyNSAtNDAwIDE2eiIvPgo8L2c+Cjwvc3ZnPgo=',
		$_wp_last_object_menu );

	$edit = add_submenu_page( 'wp3cxw',
		__( 'Edit Webinar Form', '3cx-webinar' ),
		__( 'Webinar Forms', '3cx-webinar' ),
		'wp3cxw_read_webinar_forms', 'wp3cxw',
		'wp3cxw_admin_management_page' );

	add_action( 'load-' . $edit, 'wp3cxw_load_webinar_form_admin' );

	$addnew = add_submenu_page( 'wp3cxw',
		__( 'Add New Webinar Form', '3cx-webinar' ),
		__( 'Add New', '3cx-webinar' ),
		'wp3cxw_edit_webinar_forms', 'wp3cxw-new',
		'wp3cxw_admin_add_new_page' );

	add_action( 'load-' . $addnew, 'wp3cxw_load_webinar_form_admin' );

}

add_filter( 'set-screen-option', 'wp3cxw_set_screen_options', 10, 3 );

function wp3cxw_set_screen_options( $result, $option, $value ) {
	$wp3cxw_screens = array(
		'wp3cxw_webinar_forms_per_page' );

	if ( in_array( $option, $wp3cxw_screens ) ) {
		$result = $value;
	}

	return $result;
}

function wp3cxw_save_webinar_action() {
  $id = isset( $_POST['post_ID'] ) ? intval($_POST['post_ID']) : '-1';
  check_admin_referer( 'wp3cxw-save-webinar-form_' . $id );

  if ( ! current_user_can( 'wp3cxw_edit_webinar_form', $id ) ) {
    wp_die( __( 'You are not allowed to edit this item.', '3cx-webinar' ) );
  }

  $args = $_REQUEST;
  $args['id'] = $id;
  $args['title'] = isset( $_POST['post_title'] ) ? sanitize_title($_POST['post_title'],"Webinar form","save") : null;
  $args['config'] = isset( $_POST['wp3cxw-config'] ) ? wp3cxw_sanitize_config( $_POST['wp3cxw-config'] ) : array();

  $webinar_form = wp3cxw_save_webinar_form( $args );

  if ( $webinar_form) {
    $config_validator = new WP3CXW_ConfigValidator( $webinar_form );
    $config_validator->validate();
    $config_validator->save();
  }

  $query = array(
    'post' => $webinar_form ? $webinar_form->id() : 0,
    'active-tab' => isset( $_POST['active-tab'] ) ? intval($_POST['active-tab']) : 0
  );

  $result = true;
  if ( ! $webinar_form ) {
    $result = false;
    $query['message'] = 'failed';
  } elseif ( -1 == $id ) {
    $query['message'] = 'created';
  } else {
    $query['message'] = 'saved';
  }

  wp3cxw_delete_cache($id);
  return array('result'=>$result, 'query'=>$query, 'form'=>$webinar_form);
}

function wp3cxw_load_webinar_form_admin() {
	global $plugin_page;

	$action = wp3cxw_current_action();

	if ( 'save' == $action ) {
    $reply=wp3cxw_save_webinar_action();
		$redirect_to = add_query_arg( $reply['query'], menu_page_url( 'wp3cxw', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
	}
  
  if ( 'clear_cache' == $action ) {
		$id = isset( $_POST['post_ID'] ) ? intval($_POST['post_ID']) : '-1';
		check_admin_referer( 'wp3cxw-save-webinar-form_' . $id );

		if ( ! current_user_can( 'wp3cxw_edit_webinar_form', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', '3cx-webinar' ) );
		}

    wp3cxw_delete_cache($id);
		$query = array(
			'post' => $id,
			'active-tab' => isset( $_POST['active-tab'] ) ? intval($_POST['active-tab']) : 0
		);    
    $query['message'] = 'cache cleared';
		$redirect_to = add_query_arg( $query, menu_page_url( 'wp3cxw', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
  }

  if ( 'test_api' == $action ) {
    $res=wp3cxw_save_webinar_action();
    $webinar_form=$res['form'];
    $query = $res['query'];
		if ($res['result'] && $webinar_form) {
			$config_validator = new WP3CXW_ConfigValidator( $webinar_form );
      if ($config_validator->validate()) {
        $properties = $webinar_form->get_properties();
        $config = $properties['config'];

        $url = $config['portalfqdn'].'/webmeeting/api/v1/meetings?isWebinar=true&isScheduled=false';
        if ($config['extension']!=''){
          $url.='&extension='.$config['extension'];
        }
        if (!empty($config['subject'])) {
          $url.='&subjectContains='.$config['subject'];
        }
        if (!empty($config['days']) && intval($config['days'])>0) {
          $url.='&daysLimit='.intval($config['days']);
        }
        $reply = wp3cxw_send_api_request('get', $config['apitoken'], $url);
      }
      else {
        $reply['error']='One or more invalid parameters';
      }
      $query['message'] = 'test api sent';
      $query['apimessage'] = 'tcxwm_test_api_'.md5(mt_rand().time());
      set_transient($query['apimessage'], $reply, 10);
    } else {
      $query['message'] = 'test api failed';
    }
		$redirect_to = add_query_arg( $query, menu_page_url( 'wp3cxw', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
  }  

	if ( 'copy' == $action ) {
		$id = empty( $_POST['post_ID'] ) ? intval( $_REQUEST['post'] ) : intval( $_POST['post_ID'] );

		check_admin_referer( 'wp3cxw-copy-webinar-form_' . $id );

		if ( ! current_user_can( 'wp3cxw_edit_webinar_form', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', '3cx-webinar' ) );
		}

		$query = array();

		if ( $webinar_form = wp3cxw_webinar_form( $id ) ) {
			$new_webinar_form = $webinar_form->copy();
			$new_webinar_form->save();

			$query['post'] = $new_webinar_form->id();
			$query['message'] = 'created';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'wp3cxw', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'delete' == $action ) {
		$rawposts = (array) $_REQUEST['post'];
		$posts = array();
		foreach($rawposts as $v){
			$v=intval($v);
			if (!empty($v)) {
				$posts[]=$v;
			}
		}
		
		$id = empty( $_POST['post_ID'] ) ? intval( $_REQUEST['post'] ) : intval( $_POST['post_ID'] );
		check_admin_referer( 'wp3cxw-delete-webinar-form_' . $id );		
		
		$deleted = 0;

		foreach ( $posts as $id ) {
			$post = WP3CXW_WebinarForm::get_instance( $id );

			if ( empty( $post ) ) {
				continue;
			}

			if ( ! current_user_can( 'wp3cxw_delete_webinar_form', $post->id() ) ) {
				wp_die( __( 'You are not allowed to delete this item.', '3cx-webinar' ) );
			}

			if ( ! $post->delete() ) {
				wp_die( __( 'Error in deleting.', '3cx-webinar' ) );
			}

			$deleted += 1;
		}

		$query = array();

		if ( ! empty( $deleted ) ) {
			$query['message'] = 'deleted';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'wp3cxw', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	$_GET['post'] = isset( $_GET['post'] ) ? intval($_GET['post']) : '';

	$post = null;

	if ( 'wp3cxw-new' == $plugin_page ) {
			// TODO: add support for multilanguage
		$post = WP3CXW_WebinarForm::get_template( array(
			'locale' => null
		) );
	} elseif ( ! empty( $_GET['post'] ) ) {
		$post = WP3CXW_WebinarForm::get_instance( intval($_GET['post'])) ;
	}

	$current_screen = get_current_screen();

	$help_tabs = new WP3CXW_Help_Tabs( $current_screen );

	if ( $post && current_user_can( 'wp3cxw_edit_webinar_form', $post->id() ) ) {
		$help_tabs->set_help_tabs( 'edit' );
	} else {
		$help_tabs->set_help_tabs( 'list' );

		if ( ! class_exists( 'WP3CXW_Webinar_Form_List_Table' ) ) {
			require_once WP3CXW_PLUGIN_DIR . '/admin/includes/class-webinar-forms-list-table.php';
		}

		add_filter( 'manage_' . $current_screen->id . '_columns',
			array( 'WP3CXW_Webinar_Form_List_Table', 'define_columns' ) );

		add_screen_option( 'per_page', array(
			'default' => 20,
			'option' => 'wp3cxw_webinar_forms_per_page',
		) );
	}
}

add_action( 'admin_enqueue_scripts', 'wp3cxw_admin_enqueue_scripts' );

function wp3cxw_admin_enqueue_scripts( $hook_suffix ) {
	if ( false === strpos( $hook_suffix, 'wp3cxw' ) ) {
		return;
	}

	wp_enqueue_style( '3cx-webinar-admin', wp3cxw_plugin_url( 'admin/css/styles.css' ), array(), WP3CXW_VERSION, 'all' );
	wp_enqueue_style( '3cx-webinar-dd', wp3cxw_plugin_url( 'includes/css/dd.css' ), array(), WP3CXW_VERSION, 'all' );
	wp_enqueue_style( '3cx-webinar-flags', wp3cxw_plugin_url( 'includes/css/flags.css' ), array(), WP3CXW_VERSION, 'all' );

	if ( wp3cxw_is_rtl() ) {
		wp_enqueue_style( '3cx-webinar-admin-rtl',
			wp3cxw_plugin_url( 'admin/css/styles-rtl.css' ),
			array(), WP3CXW_VERSION, 'all' );
	}

	wp_enqueue_script( 'wp3cxw-admin', wp3cxw_plugin_url( 'admin/js/scripts.js' ), array( 'jquery', 'jquery-ui-tabs' ), WP3CXW_VERSION, true );
	wp_enqueue_script( 'wp3cxw-admin-dd', wp3cxw_plugin_url( 'includes/js/jquery.dd.js' ), array( 'jquery' ), WP3CXW_VERSION, false );

	$args = array(
		'apiSettings' => array(
			'root' => esc_url_raw( rest_url( '3cx-webinar/v1' ) ),
			'namespace' => '3cx-webinar/v1',
			'nonce' => ( wp_installing() && ! is_multisite() )
				? '' : wp_create_nonce( 'wp_rest' ),
		),
    'testApiUrl' => get_rest_url(null, '/3cx-webinar/testapi'),
		'pluginUrl' => wp3cxw_plugin_url(),
		'saveAlert' => __(
			"The changes you made will be lost if you navigate away from this page.",
			'3cx-webinar' ),
		'activeTab' => isset( $_GET['active-tab'] )
			? (int) $_GET['active-tab'] : 0,
		'configValidator' => array(
			'errors' => array(),
			'howToCorrect' => __( "How to resolve?", '3cx-webinar' ),
			'oneError' => __( '1 configuration error detected', '3cx-webinar' ),
			'manyErrors' => __( '%d configuration errors detected', '3cx-webinar' ),
			'oneErrorInTab' => __( '1 configuration error detected in this tab panel', '3cx-webinar' ),
			'manyErrorsInTab' => __( '%d configuration errors detected in this tab panel', '3cx-webinar' ),
			'docUrl' => '',
			/* translators: screen reader text */
			'iconAlt' => __( '(configuration error)', '3cx-webinar' ),
		),
	);

	if ( ( $post = wp3cxw_get_current_webinar_form() ) && current_user_can( 'wp3cxw_edit_webinar_form', $post->id() ) ) {
		$config_validator = new WP3CXW_ConfigValidator( $post );
		$config_validator->restore();
		$args['configValidator']['errors'] =
			$config_validator->collect_error_messages();
	}

	wp_localize_script( 'wp3cxw-admin', 'wp3cxw', $args );

}

function wp3cxw_admin_management_page() {
	if ( $post = wp3cxw_get_current_webinar_form() ) {
		$post_id = $post->initial() ? -1 : $post->id();

		require_once WP3CXW_PLUGIN_DIR . '/admin/includes/editor.php';
		require_once WP3CXW_PLUGIN_DIR . '/admin/edit-webinar-form.php';
		return;
	}

	$list_table = new WP3CXW_Webinar_Form_List_Table();
	$list_table->prepare_items();

?>
<div class="wrap">

<h1 class="wp-heading-inline"><?php
	echo esc_html( __( 'Webinar Forms', '3cx-webinar' ) );
?></h1>

<?php
	if ( current_user_can( 'wp3cxw_edit_webinar_forms' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
			esc_url( menu_page_url( 'wp3cxw-new', false ) ),
			esc_html( __( 'Add New', '3cx-webinar' ) ) );
	}
?>

<hr class="wp-header-end">

<?php do_action( 'wp3cxw_admin_warnings' ); ?>
<?php do_action( 'wp3cxw_admin_notices' ); ?>

<form method="get" action="">
	<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
	<?php $list_table->display(); ?>
</form>

</div>
<?php
}

function wp3cxw_admin_add_new_page() {
	$post = wp3cxw_get_current_webinar_form();

	if ( ! $post ) {
		$post = WP3CXW_WebinarForm::get_template();
	}

	$post_id = -1;

	require_once WP3CXW_PLUGIN_DIR . '/admin/includes/editor.php';
	require_once WP3CXW_PLUGIN_DIR . '/admin/edit-webinar-form.php';
}

/* Misc */

add_action( 'wp3cxw_admin_notices', 'wp3cxw_admin_updated_message' );

function wp3cxw_admin_updated_message() {
	if ( empty( $_REQUEST['message'] ) ) {
		return;
	}

  switch($_REQUEST['message']) {
    case 'created': $updated_message = __( "Webinar form created.", '3cx-webinar' ); break;
    case 'saved': $updated_message = __( "Webinar form saved.", '3cx-webinar' ); break;
    case 'deleted': $updated_message = __( "Webinar form deleted.", '3cx-webinar' ); break;
    case 'cleared': $updated_message = __( "Cache cleared for this webinar form.", '3cx-webinar' ); break;
    case 'test api sent': $updated_message = __( "Test API Request sent successfully.", '3cx-webinar' ); break;
  }

	if ( ! empty( $updated_message ) ) {
		echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
	}

	if ( 'failed' == $_REQUEST['message']) {
		$updated_message = __( "There was an error saving the webinar form.", '3cx-webinar' );
		echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
		return;
	}

  if ( 'test api failed' == $_REQUEST['message']) {
		$updated_message = __( "Test API Request error.", '3cx-webinar' );
		echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
		return;
	}

}

add_action( 'wp3cxw_admin_warnings', 'wp3cxw_old_wp_version_error' );

function wp3cxw_old_wp_version_error() {
	$wp_version = get_bloginfo( 'version' );

	if ( ! version_compare( $wp_version, WP3CXW_REQUIRED_WP_VERSION, '<' ) ) {
		return;
	}

?>
<div class="notice notice-warning">
<p><?php
/* translators: "3CX Webinars (VERSION) requires WordPress (VERSION) or higher" */
	echo '<strong>'.sprintf( __( '3CX Webinars %1$s requires WordPress %2$s or higher.'), WP3CXW_VERSION, WP3CXW_REQUIRED_WP_VERSION).'</strong>';
	/* translators: "Please update Wordpress first" */
  echo '&nbsp;'.sprintf(__('Please %s first','3cx-webinar'), sprintf('<a href="%s">', admin_url( 'update-core.php' )).__('update WordPress', '3cx-webinar').'</a>');
?></p>
</div>
<?php
}

add_action( 'wp3cxw_admin_warnings', 'wp3cxw_not_allowed_to_edit' );

function wp3cxw_not_allowed_to_edit() {
	if ( ! $webinar_form = wp3cxw_get_current_webinar_form() ) {
		return;
	}

	$post_id = $webinar_form->id();

	if ( current_user_can( 'wp3cxw_edit_webinar_form', $post_id ) ) {
		return;
	}

	$message = __( "You are not allowed to edit this Webinar form.",
		'3cx-webinar' );

	echo sprintf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html( $message ) );
}



