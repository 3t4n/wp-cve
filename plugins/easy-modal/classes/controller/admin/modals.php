<?php class EModal_Controller_Admin_Modals extends EModal_Controller {

	static function factory() {
		self::$instance = new EModal_Controller_Admin_Modals;
		if ( empty( $_GET['action'] ) ) {
			self::$instance->action_index();
		} else {
			switch ( $_GET['action'] ) {
				case 'new':
					self::$instance->action_edit();
					break;
				case 'edit':
					self::$instance->action_edit();
					break;
				case 'clone':
					self::$instance->action_clone();
					break;
				case 'delete':
					self::$instance->action_delete();
					break;
				case 'untrash':
					self::$instance->action_untrash();
					break;
				case 'empty_trash':
					self::$instance->action_trash();
					break;
			}
		}
	}

	public function action_index() {
		$view = new EModal_View_Admin_Modal_Index;
		$view->set( 'title', __( 'Modals', 'easy-modal' ) );
		$view->set( 'modal_new_url', $this->new_url() );
		self::$instance->view = $view;
	}

	public function action_edit() {
		global $current_modal;
		$current_modal = new EModal_Model_Modal( $this->check_id() );
		if ( $this->check_post_nonce() ) {
			$new_values = apply_filters( 'emodal_modal_pre_save', $current_modal->as_array() );
			$current_modal->set_fields( apply_filters( "emodal_model_modal_meta_defaults", $new_values ) );
			$current_modal->set_fields( $new_values );
			$current_modal->save();
			if ( ! $current_modal->id ) {
				do_action( 'emodal_modal_new', $current_modal );
			}
			do_action( 'emodal_modal_save', $current_modal );
			EModal_Admin_Notice::add( $current_modal->id ? __( 'Modal Updated.', 'easy-modal' ) : __( 'Modal Created.', 'easy-modal' ), 'updated' );
			$this->redirect_to_edit();
		}
		$view = new EModal_View_Admin_Modal_Form;
		$view->set( 'title', $current_modal->id ? __( 'Edit Modal', 'easy-modal' ) : __( 'Add New Modal', 'easy-modal' ) );
		$view->set( 'modal_new_url', $this->new_url() );
		$view->set( 'tabs', apply_filters( 'emodal_admin_modal_form_tabs', array() ) );
		self::$instance->view = $view;
	}

	public function action_clone() {
		if ( $this->check_get_nonce() ) {
			global $current_modal;
			$current_modal           = new EModal_Model_Modal( $this->check_id() );
			$current_modal->id       = null;
			$current_modal->meta->id = null;
			$current_modal->save();
			do_action( 'emodal_modal_clone', $current_modal );
			EModal_Admin_Notice::add( __( 'Modal cloned successfully', 'easy-modal' ), 'updated' );
			$this->redirect_to_edit();
		}
		wp_redirect( $this->index_url(), 302 );
		exit();
	}

	public function request_ids() {
		$ids = false;

		if ( ! empty( $_GET['id'] ) ) {
			$ids = is_array( $_GET['id'] ) ? $_GET['id'] : array( $_GET['id'] );
		} elseif ( ! empty( $_GET['ids'] ) ) {
			$ids = $_GET['ids'];
		} elseif ( ! empty( $_GET['modal'] ) ) {
			$ids = $_GET['modal'];
		}

		return wp_parse_id_list( $ids );
	}

	public function action_delete() {
		if ( $this->check_get_nonce() ) {
			$ids = $this->request_ids();

			if ( empty( $ids ) ) {
				EModal_Admin_Notice::add( __( 'No modals selected for deletion.', 'easy-modal' ), 'updated' );
				wp_redirect( $this->index_url(), 302 );
				exit();
			}
			global $wpdb;
			$wpdb->query( "UPDATE {$wpdb->prefix}em_modals SET is_trash = 1 WHERE id IN (" . implode( ',', $ids ) . ")" );
			do_action( 'emodal_modal_delete' );
			EModal_Admin_Notice::add( count( $ids ) . __( ' modals moved to the trash', 'easy-modal' ), 'updated' );
		}
		wp_redirect( $this->index_url(), 302 );
		exit();
	}

	public function action_untrash() {
		if ( $this->check_get_nonce() ) {
			$ids = $this->request_ids();

			if ( empty( $ids ) ) {
				EModal_Admin_Notice::add( __( 'No modals selected for undeletion.', 'easy-modal' ), 'updated' );
				wp_redirect( $this->index_url(), 302 );
				exit();
			}
			global $wpdb;
			$wpdb->query( "UPDATE {$wpdb->prefix}em_modals SET is_trash = 0 WHERE id IN ($ids)" );
			EModal_Admin_Notice::add( count( $ids ) . __( ' modal restored from trash.', 'easy-modal' ), 'updated' );
			do_action( 'emodal_modal_untrash' );
		}
		wp_redirect( $this->index_url(), 302 );
		exit();
	}

	public function action_trash() {
		if ( $this->check_get_nonce() ) {
			global $wpdb;
			$wpdb->delete( $wpdb->prefix . 'em_modals', array( 'is_trash' => 1 ) );
			EModal_Admin_Notice::add( __( 'Modal trash has been permanantly removed.', 'easy-modal' ), 'updated' );
			do_action( 'emodal_modal_trash' );
		}
		wp_redirect( $this->index_url(), 302 );
		exit();
	}

	public function new_url() {
		return emodal_admin_url() . '&action=new';
	}

	public function check_id() {
		if ( empty( $_GET['id'] ) && $_GET['action'] != 'new' ) {
			wp_redirect( $this->index_url(), 302 );
			exit();
		}

		return empty( $_GET['id'] ) ? null : $_GET['id'];
	}

	public function index_url() {
		return emodal_admin_url();
	}

	public function edit_url() {
		return emodal_admin_url() . '&action=edit&id=' . get_current_modal_id();
	}

}


add_filter( 'emodal_modal_pre_save', 'emodal_modal_pre_save', 1 );
function emodal_modal_pre_save( $new_values ) {
	$new_values['name']                                  = sanitize_text_field( empost( 'modal.name' ) );
	$new_values['theme_id']                              = 1;
	$new_values['title']                                 = sanitize_text_field( empost( 'modal.title' ) );
	$new_values['content']                               = balanceTags( empost( 'modal.content' ) );
	$new_values['is_sitewide']                           = empost( 'modal.load_type' ) == 'sitewide' ? 1 : 0;
	$new_values['meta']['display']['overlay_disabled']   = empost( 'modal.meta.display.overlay_disabled' ) ? 1 : 0;
	$new_values['meta']['display']['size']               = empost( 'modal.meta.display.size' );
	$new_values['meta']['display']['custom_width']       = floatval( empost( 'modal.meta.display.custom_width' ) );
	$new_values['meta']['display']['custom_width_unit']  = empost( 'modal.meta.display.custom_width_unit' );
	$new_values['meta']['display']['custom_height_auto'] = empost( 'modal.meta.display.custom_height_auto' ) ? 1 : 0;
	$new_values['meta']['display']['custom_height']      = floatval( empost( 'modal.meta.display.custom_height' ) );
	$new_values['meta']['display']['custom_height_unit'] = empost( 'modal.meta.display.custom_height_unit' );
	$new_values['meta']['display']['location']           = empost( 'modal.meta.display.location' );
	$new_values['meta']['display']['position']['top']    = floatval( empost( 'modal.meta.display.position.top' ) );
	$new_values['meta']['display']['position']['left']   = floatval( empost( 'modal.meta.display.position.left' ) );
	$new_values['meta']['display']['position']['bottom'] = floatval( empost( 'modal.meta.display.position.bottom' ) );
	$new_values['meta']['display']['position']['right']  = floatval( empost( 'modal.meta.display.position.right' ) );
	$new_values['meta']['display']['position']['fixed']  = empost( 'modal.meta.display.position.fixed' ) ? 1 : 0;


	$new_values['meta']['display']['animation']['type']   = empost( 'modal.meta.display.animation.type' );
	$new_values['meta']['display']['animation']['speed']  = empost( 'modal.meta.display.animation.speed' );
	$new_values['meta']['display']['animation']['origin'] = empost( 'modal.meta.display.animation.origin' );
	$new_values['meta']['close']['overlay_click']         = empost( 'modal.meta.close.overlay_click' ) ? 1 : 0;
	$new_values['meta']['close']['esc_press']             = empost( 'modal.meta.close.esc_press' ) ? 1 : 0;

	return $new_values;
}