<?php
/**
 * Gravity Form Support.
 *
 * @package User Activity Log
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ual_gforms_save_entry' ) ) {
	/**
	 * Fires once the admin request has been validated or not.
	 *
	 * @param string    $action_type The nonce action.
	 * @param false|int $result False if the nonce is invalid,
	 *                  1 if the nonce is valid and generated between 0-12 hours ago,
	 *                  2 if the nonce is valid and generated between 12-24 hours ago.
	 */
	function ual_gforms_save_entry( $action_type, $result ) {
		$obj_type = 'Gravity Form';
		$post_id  = '';
		$hook     = $action_type;
		if ( 'gf_export_forms' == $action_type ) {
			$action = 'Form exported';
			if ( isset( $_POST['gf_export_forms_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gf_export_forms_nonce'] ) ), 'gf_export_forms' ) ) {
				if ( isset( $_POST['export_forms'] ) ) {
					$selected_forms = rgpost( 'gf_form_id' );
					if ( is_array( $selected_forms ) ) {
						foreach ( $selected_forms as $selected_form ) {
							$form       = GFFormsModel::get_form_meta( $selected_form );
							$post_title = $form['title'] . ' ' . $action;
							ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
						}
					}
				}
			}
		}
	}
}

add_action( 'check_admin_referer', 'ual_gforms_save_entry', 10, 2 );

if ( ! function_exists( 'ual_gforms_duplicate_notify' ) ) {
	/**
	 * Fires once Notification duplicated.
	 *
	 * @param string    $action The nonce action.
	 * @param false|int $result False if the nonce is invalid,
	 *                  1 if the nonce is valid and generated between 0-12 hours ago,
	 *                  2 if the nonce is valid and generated between 12-24 hours ago.
	 */
	function ual_gforms_duplicate_notify( $action, $result ) {
		if ( 'gform_notification_list_action' == $action ) {
			$actions = rgpost( 'action' );
			if ( 'duplicate' == $actions ) {
				$form_id            = rgget( 'id' );
				$notification_id    = rgpost( 'action_argument' );
				$form               = GFFormsModel::get_form_meta( $form_id );
				$notification_title = $form['notifications'][ $notification_id ]['name'];
				$post_title         = $notification_title . ' notification duplicated for' . $form['title'];
				$obj_type           = 'Gravity Form';
				$post_id            = '';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
		}
	}
}

add_action( 'check_admin_referer', 'ual_gforms_duplicate_notify', 10, 2 );

if ( ! function_exists( 'ual_gform_entry_created' ) ) {
	/**
	 * Fired after an entry is created.
	 *
	 * @param array $lead The Entry object.
	 * @param array $form The Form object.
	 */
	function ual_gform_entry_created( $lead, $form ) {
		$lead_id = ( isset( $lead ) ) ? $lead['id'] : '';
		if ( isset( $lead_id ) && '' != $lead_id ) {
			$lead_title = $lead[1];
			$form_title = '';
			if ( isset( $form['title'] ) && ! empty( $form['title'] ) ) {
				$form_title = $form['title'];
			}
			$action     = 'created';
			$obj_type   = 'Gravity Form';
			$post_id    = '';
			$post_title = 'New entry ' . $lead_title . ' created in ' . $form_title . ' Form';
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}

add_action( 'gform_entry_created', 'ual_gform_entry_created', 20, 2 );

if ( ! function_exists( 'ual_wp_ajax_gf_resend_notifications' ) ) {
	/**
	 * Fires when resend email notifications.
	 */
	function ual_wp_ajax_gf_resend_notifications() {
		$form_id = absint( rgpost( 'formId' ) );
		$leads   = rgpost( 'leadIds' );
		if ( ! empty( $leads ) && ! empty( $form_id ) ) {
			$form     = GFFormsModel::get_form_meta( $form_id );
			$action   = '';
			$obj_type = 'Gravity Form';
			$post_id  = '';
			$hook     = 'gf_resend_notifications';
			foreach ( $leads as $single_lead ) {
				$lead       = GFFormsModel::get_lead( $single_lead );
				$post_title = 'Notifications for ' . $lead[1] . ' entry resent for ' . $form['title'] . ' form';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
		}
	}
}

add_action( 'wp_ajax_gf_resend_notifications', 'ual_wp_ajax_gf_resend_notifications' );

if ( ! function_exists( 'ualgform_pre_note_deleted' ) ) {
	/**
	 * Fires before a note is deleted.
	 *
	 * @param int $note_id The current note ID.
	 * @param int $lead_id The current lead ID.
	 */
	function ualgform_pre_note_deleted( $note_id, $lead_id ) {
		$lead       = GFFormsModel::get_lead( $lead_id );
		$form       = GFFormsModel::get_form_meta( $lead['form_id'] );
		$action     = 'deleted';
		$post_id    = '';
		$obj_type   = 'Gravity Form';
		$post_title = $note_id . ' note deleted from lead ' . $lead_id . ' on ' . $form['title'] . ' form';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_pre_note_deleted', 'ualgform_pre_note_deleted', 10, 2 );

if ( ! function_exists( 'ual_garvity_form_updated_option' ) ) {
	/**
	 * Fires when settings updated
	 *
	 * @param string $option Optin.
	 * @param string $oldvalue Old Value.
	 * @param string $_newvalue New Value.
	 */
	function ual_garvity_form_updated_option( $option, $oldvalue = null, $_newvalue = null ) {
		$whitelist_options = array(
			'gform_enable_noconflict',
			'gform_enable_toolbar_menu',
			'gform_enable_background_updates',
			'gform_enable_logging',
			'rg_gforms_currency',
			'rg_gforms_key',
			'rg_gforms_enable_html5',
			'rg_gforms_disable_css',
			'rg_gforms_captcha_public_key',
			'rg_gforms_captcha_private_key',
		);

		if ( ! in_array( $option, $whitelist_options ) ) {
			return;
		}

			$action     = 'updated';
			$obj_type   = 'Settings';
			$post_id    = '';
			$post_title = $option;
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}

add_action( 'updated_option', 'ual_garvity_form_updated_option', 20, 3 );
add_action( 'delete_option', 'ual_garvity_form_updated_option', 20, 3 );
add_action( 'add_option', 'ual_garvity_form_updated_option', 20, 3 );

if ( ! function_exists( 'ualgform_post_export_entries' ) ) {
	/**
	 * Fires after exporting all the entries in form.
	 *
	 * @param array  $form       The Form object to get the entries from.
	 * @param string $start_date The start date for when the export of entries should take place.
	 * @param string $end_date   The end date for when the export of entries should stop.
	 * @param array  $fields     The specified fields where the entries should be exported from.
	 */
	function ualgform_post_export_entries( $form, $start_date, $end_date, $fields ) {
		$action     = 'form entries exported';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = $form['title'] . ' form entries exported';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_post_export_entries', 'ualgform_post_export_entries', 10, 4 );

if ( ! function_exists( 'ualgform_post_note_added' ) ) {
	/**
	 * Fires after a note has been added to an entry.
	 *
	 * @param int    $note_id         The row ID of this note in the database.
	 * @param int    $lead_id         The ID of the entry that the note was added to.
	 * @param int    $user_id         The ID of the current user adding the note.
	 * @param string $user_name       The user name of the current user.
	 * @param string $note            The content of the note being added.
	 * @param string $note_type       The type of note being added.  Defaults to 'note'.
	 */
	function ualgform_post_note_added( $note_id, $lead_id, $user_id, $user_name, $note, $note_type ) {
		$lead       = GFFormsModel::get_lead( $lead_id );
		$form       = GFFormsModel::get_form_meta( $lead['form_id'] );
		$action     = 'added';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = $note_id . ' note added to lead ' . $lead_id . ' on ' . $form['title'] . ' form';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_post_note_added', 'ualgform_post_note_added', 10, 6 );

if ( ! function_exists( 'ualgform_delete_entries' ) ) {
	/**
	 * Fires when you delete entries for a specific form.
	 *
	 * @param int    $form_id The form ID to specify from which form to delete entries.
	 * @param string $status  Allows you to set the form entries to a deleted status.
	 */
	function ualgform_delete_entries( $form_id, $status ) {
		$form       = GFFormsModel::get_form_meta( $form_id );
		$form_title = $form['title'];
		$action     = 'deleted';
		$post_id    = '';
		$obj_type   = 'Gravity Form';
		$post_title = 'Entry deleted from ' . $form_title;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_delete_entries', 'ualgform_delete_entries', 10, 2 );

if ( ! function_exists( 'ualgform_post_form_views_deleted' ) ) {
	/**
	 * Fires after form views are deleted.
	 *
	 * @param int $form_id The ID of the form that views were deleted from.
	 */
	function ualgform_post_form_views_deleted( $form_id ) {
		$action     = 'view Reset';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$form       = GFFormsModel::get_form_meta( $form_id );
		$post_title = $form['title'] . ' Form view Reset';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_post_form_views_deleted', 'ualgform_post_form_views_deleted', 10, 1 );

if ( ! function_exists( 'ualgform_post_form_activated' ) ) {
	/**
	 * Fires after an inactive form gets marked as active.
	 *
	 * @param int $form_id The Form ID used to specify which form to activate.
	 */
	function ualgform_post_form_activated( $form_id ) {
		$action     = 'activated';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$form       = GFFormsModel::get_form_meta( $form_id );
		$post_title = $form['title'] . ' Form activated';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_post_form_activated', 'ualgform_post_form_activated', 10, 1 );

if ( ! function_exists( 'ualgform_post_form_deactivated' ) ) {
	/**
	 * Fires after an active form gets marked as inactive.
	 *
	 * @param int $form_id The Form ID used to specify which form to activate.
	 */
	function ualgform_post_form_deactivated( $form_id ) {
		$action     = 'deactivated';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$form       = GFFormsModel::get_form_meta( $form_id );
		$post_title = $form['title'] . ' Form deactivated';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_post_form_deactivated', 'ualgform_post_form_deactivated', 10, 1 );

if ( ! function_exists( 'ualgform_before_delete_form' ) ) {
	/**
	 * Fires before a form is deleted.
	 *
	 * @param int $form_id The ID of the form being deleted.
	 */
	function ualgform_before_delete_form( $form_id ) {
		$action     = 'deleted';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$form       = GFFormsModel::get_form_meta( $form_id );
		$post_title = $form['title'] . ' Form deleted';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_before_delete_form', 'ualgform_before_delete_form', 10, 1 );

if ( ! function_exists( 'ualgform_post_form_trashed' ) ) {
	/**
	 * Fires after a form is trashed.
	 *
	 * @param int $form_id The ID of the form that was trashed.
	 */
	function ualgform_post_form_trashed( $form_id ) {
		$form       = GFFormsModel::get_form_meta( $form_id );
		$action     = 'trashed';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = $form['title'] . ' Form trashed';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );

	}
}
add_action( 'gform_post_form_trashed', 'ualgform_post_form_trashed', 10, 1 );

if ( ! function_exists( 'ualgform_post_form_restored' ) ) {
	/**
	 * Fires after a form is restored from trash.
	 *
	 * @param int $form_id The ID of the form that was restored.
	 */
	function ualgform_post_form_restored( $form_id ) {
		$action     = 'restored';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$form       = GFFormsModel::get_form_meta( $form_id );
		$post_title = $form['title'] . ' Form restored';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_post_form_restored', 'ualgform_post_form_restored', 10, 1 );

if ( ! function_exists( 'ualgform_post_form_duplicated' ) ) {
	/**
	 * Fires after a form is duplicated.
	 *
	 * @param int $form_id The original form's ID.
	 * @param int $new_id  The ID of the new, duplicated form.
	 */
	function ualgform_post_form_duplicated( $form_id, $new_id ) {
		$action     = 'duplicated';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$form       = GFFormsModel::get_form_meta( $form_id );
		$post_title = $form['title'] . ' Form duplicated';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_post_form_duplicated', 'ualgform_post_form_duplicated', 10, 2 );

if ( ! function_exists( 'ualgform_after_save_form' ) ) {
	/**
	 * Fires after a form is saved.
	 *
	 * Used to run additional actions after the form is saved.
	 *
	 * @param array $form_meta The form meta.
	 * @param bool  $flag Returns true if this is a new form.
	 */
	function ualgform_after_save_form( $form_meta, $flag ) {
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$action     = $flag ? 'created' : 'updated';
		$post_title = $form_meta['title'] . ' form ' . $action;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_after_save_form', 'ualgform_after_save_form', 10, 2 );

if ( ! function_exists( 'ualgform_forms_post_import' ) ) {
	/**
	 * Fires after forms have been imported.
	 *
	 * Used to perform additional actions after import.
	 *
	 * @param array $forms An array imported form objects.
	 */
	function ualgform_forms_post_import( $forms ) {
		$forms_total  = count( $forms );
		$forms_label  = ( 1 === $forms_total ) ? 'form' : 'forms';
		$forms_ids    = wp_list_pluck( $forms, 'id' );
		$forms_titles = wp_list_pluck( $forms, 'title' );
		$forms_title  = implode( ',', $forms_titles );
		$action       = 'imported';
		$obj_type     = 'Gravity Form';
		$post_id      = '';
		$post_title   = $forms_title . ' ' . $forms_total . ' ' . $forms_label . ' ' . $action;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_forms_post_import', 'ualgform_forms_post_import', 10, 1 );

if ( ! function_exists( 'ualgform_pre_notification_deleted' ) ) {
	/**
	 * Fires before a notification is deleted.
	 *
	 * @param array $notification_id    The notification being deleted.
	 * @param array $form               The Form Object that the notification is being deleted from.
	 */
	function ualgform_pre_notification_deleted( $notification_id, $form ) {
		$action     = '';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$hook       = 'gform_pre_notification_deleted';
		$post_title = $notification_id['name'] . ' Notification deleted from ' . $form['title'];
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_pre_notification_deleted', 'ualgform_pre_notification_deleted', 10, 2 );

if ( ! function_exists( 'ualgform_pre_confirmation_deleted' ) ) {
	/**
	 * Fires right before a confirmation is deleted.
	 *
	 * @param int   $confirmation_id The ID of the confirmation being deleted.
	 * @param array $form                                    The Form object.
	 */
	function ualgform_pre_confirmation_deleted( $confirmation_id, $form ) {
		$action     = 'confirmation deleted';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = $confirmation_id['name'] . ' ' . $action . ' from ' . $form['title'];
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_pre_confirmation_deleted', 'ualgform_pre_confirmation_deleted', 10, 2 );

if ( ! function_exists( 'ualwp_ajax_rg_update_notification_active' ) ) {
	/**
	 * Fires when update notification saved.
	 */
	function ualwp_ajax_rg_update_notification_active() {
		if ( isset( $_POST['rg_update_notification_active'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rg_update_notification_active'] ) ), 'rg_update_notification_active' ) ) {
			if ( isset( $_POST['is_active'] ) && 1 == $_POST['is_active'] ) {
				$action = 'active';
			} else {
				$action = 'inactive';
			}
			$form_id            = ( isset( $_POST['form_id'] ) && ! empty( $_POST['form_id'] ) ) ? intval( $_POST['form_id'] ) : '';
			$form               = GFFormsModel::get_form_meta( $form_id );
			$notification_id    = ( isset( $_POST['notification_id'] ) && ! empty( $_POST['notification_id'] ) ) ? intval( $_POST['notification_id'] ) : '';
			$notification_title = $form['notifications'][ $notification_id ]['name'];
			$obj_type           = 'Gravity Form';
			$post_id            = '';
			$post_title         = $notification_title . ' notification ' . $action . ' for ' . $form['title'];
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}
add_action( 'wp_ajax_rg_update_notification_active', 'ualwp_ajax_rg_update_notification_active' );

if ( ! function_exists( 'ual_ajax_rg_update_confirmation_active' ) ) {
	/**
	 * Fires when confirmation activated.
	 */
	function ual_ajax_rg_update_confirmation_active() {
		if ( isset( $_POST['rg_update_confirmation_active'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rg_update_confirmation_active'] ) ), 'rg_update_confirmation_active' ) ) {
			if ( isset( $_POST['is_active'] ) && 1 == $_POST['is_active'] ) {
				$action = 'active';
			} else {
				$action = 'inactive';
			}

			$form_id         = ( isset( $_POST['form_id'] ) && ! empty( $_POST['form_id'] ) ) ? intval( $_POST['form_id'] ) : '';
			$form            = GFFormsModel::get_form_meta( $form_id );
			$confirmation_id = ( isset( $_POST['confirmation_id'] ) && ! empty( $_POST['confirmation_id'] ) ) ? intval( $_POST['confirmation_id'] ) : '';
			$c_title         = $form['confirmations'][ $confirmation_id ]['name'];
			$post_id         = '';
			$obj_type        = 'Gravity Form';
			$post_title      = $c_title . ' confirmation ' . $action . ' for ' . $form['title'];
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}
add_action( 'wp_ajax_rg_update_confirmation_active', 'ual_ajax_rg_update_confirmation_active' );

if ( ! function_exists( 'ualgform_confirmation_save' ) ) {
	/**
	 * Fires when saving form confirmations.
	 *
	 * @param array $confirmation Confirmation.
	 * @param array $form Form.
	 * @param bool  $is_new Is New.
	 * @return array
	 */
	function ualgform_confirmation_save( $confirmation, $form, $is_new = true ) {
		$confirmation_name = $confirmation['name'];
		$action            = $is_new ? 'created' : 'updated';
		$obj_type          = 'Gravity Form';
		$post_id           = '';
		$post_title        = $confirmation_name . ' confirmation ' . $action . ' for ' . $form['title'];
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		return $confirmation;
	}
}
add_filter( 'gform_pre_confirmation_save', 'ualgform_confirmation_save', 10, 3 );

if ( ! function_exists( 'ualgform_notification_save' ) ) {
	/**
	 * Fires when saving form notification.
	 *
	 * @param array $notification Notification.
	 * @param array $form Form.
	 * @param bool  $is_new Is New.
	 * @return array
	 */
	function ualgform_notification_save( $notification, $form, $is_new = true ) {
		$action     = $is_new ? 'created' : 'updated';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = $notification['name'] . ' notification' . $action . ' for ' . $form['title'];
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		return $notification;
	}
}
add_filter( 'gform_pre_notification_save', 'ualgform_notification_save', 10, 3 );

if ( ! function_exists( 'ualgform_post_lead_deleted' ) ) {
	/**
	 * Fires after form lead is deleted
	 *
	 * @param int $lead_id The ID of the lead.
	 */
	function ualgform_post_lead_deleted( $lead_id ) {
		$lead       = GFFormsModel::get_lead( $lead_id );
		$form       = GFFormsModel::get_form_meta( $lead['form_id'] );
		$action     = 'deleted';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = 'Entry ' . $lead['id'] . ' from ' . $form['title'] . ' ' . $action;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_delete_entry', 'ualgform_post_lead_deleted', 10, 1 );

if ( ! function_exists( 'ualgform_update_status' ) ) {
	/**
	 * Fires before form status updated.
	 *
	 * @param int    $lead_id The current lead ID.
	 * @param string $status Current Status.
	 * @param string $prev Previous Status.
	 */
	function ualgform_update_status( $lead_id, $status, $prev = '' ) {

		$lead = GFFormsModel::get_lead( $lead_id );
		$form = GFFormsModel::get_form_meta( $lead['form_id'] );

		if ( 'active' === $status && 'trash' === $prev ) {
			$action = 'restore';
		} elseif ( 'active' == $status ) {
			$action = 'active';
		} elseif ( 'spam' == $status ) {
			$action = 'spam';
		} elseif ( 'trash' == $status ) {
			$action = 'trash';
		} elseif ( 'restore' == $status ) {
			$action = 'restore';
		} else {
			$action = $status;
		}
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = "Entry '$lead[1]' $action on '" . $form['title'] . "' form";
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_update_status', 'ualgform_update_status', 10, 3 );

if ( ! function_exists( 'ualgform_read_entry' ) ) {
	/**
	 * Fires before read entry.
	 *
	 * @param int $lead_id The current lead ID.
	 * @param int $status Current status.
	 */
	function ualgform_read_entry( $lead_id, $status ) {

		$lead       = GFFormsModel::get_lead( $lead_id );
		$form       = GFFormsModel::get_form_meta( $lead['form_id'] );
		$action     = ( ! empty( $status ) ) ? 'read' : 'unread';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = "Entry '$lead[1]' marked as $action on '" . $form['title'] . "' form";
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_update_is_read', 'ualgform_read_entry', 10, 2 );

if ( ! function_exists( 'ualgform_mark_starred' ) ) {
	/**
	 * Fires before entry marked as starred.
	 *
	 * @param int $lead_id The current lead ID.
	 * @param int $status Current status.
	 */
	function ualgform_mark_starred( $lead_id, $status ) {

		$lead       = GFFormsModel::get_lead( $lead_id );
		$form       = GFFormsModel::get_form_meta( $lead['form_id'] );
		$action     = ( ! empty( $status ) ) ? 'starred' : 'unstarred';
		$obj_type   = 'Gravity Form';
		$post_id    = '';
		$post_title = "Entry '$lead[1]' $action on '" . $form['title'] . "' form";
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'gform_update_is_starred', 'ualgform_mark_starred', 10, 2 );

if ( ! function_exists( 'ual_garvity_form_licence_updated' ) ) {
	/**
	 * Fires when settings updated.
	 *
	 * @param string $option Option.
	 * @param string $oldvalue Old Value.
	 * @param string $_newvalue New Value.
	 */
	function ual_garvity_form_licence_updated( $option, $oldvalue, $_newvalue ) {
		if ( 'rg_gforms_key' == $option ) {
			$is_update = ( $_newvalue && strlen( $_newvalue ) );

			$action     = $is_update ? 'updated' : 'deleted';
			$obj_type   = 'Gravity Form';
			$post_id    = '';
			$post_title = 'Gravity Forms license key ' . $action;
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}
add_action( 'updated_option', 'ual_garvity_form_licence_updated', 20, 3 );
