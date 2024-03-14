<?php
/**
 * After submit form run actions
 *
 * @since      1.0.0
 * @package    Add-on Add-on Contact Form 7 - Mailpoet 3 Integration
 * @subpackage add-on-contact-form-7-mailpoet/includes
 * @author     Tikweb <kasper@tikjob.dk>
 */


// If access directly, die
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// use MailPoet\Models\Segment;
use MailPoet\Models\Subscriber;
use MailPoet\Models\CustomField;    // get all custom field info without value
use MailPoet\Settings\SettingsController; // get mailpoet settings

if ( ! class_exists( 'MailPoet_CF7_Submit_Form' ) ) {
	class MailPoet_CF7_Submit_Form {
		/**
		 * Initialize the class
		 */
		public static function init() {
			$_this_class = new self();

			return $_this_class;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wpcf7_before_send_mail', array( $this, 'wpcf7_before_send_mail' ) );

		}//end __construct()

		/**
		 * Run action just before the mail send
		 * Add user to list
		 */
		public function wpcf7_before_send_mail( $contact_form ) {
			if ( ! empty( $contactform->skip_mail ) ) {
				return;
			} //If need to skip

			if ( class_exists( 'WPCF7_Submission' ) && class_exists( 'WPCF7_FormTagsManager' ) ) {
				// Get submited form data
				$submission  = WPCF7_Submission::get_instance();
				$posted_data = ( $submission ) ? $submission->get_posted_data() : null;

				// Get the tags that are in the form
				$manager      = WPCF7_FormTagsManager::get_instance();
				$scanned_tags = $manager->get_scanned_tags();

				// unsubscribe first
				$unsubscribed = $this->unsubscribe_email( $posted_data );

				if ( $unsubscribed == false ) {
					// Add new subscriber
					$this->add_email_list( $posted_data, $scanned_tags );
				}
			} else {
				return;
			}
		}//end wpcf7_before_send_mail()


		/**
		 * Prepare form data
		 * Add data to the list
		 * your-email for email
		 * your-first-name for first name
		 * your-last-name for last name
		 */
		public function add_email_list( $form_data, $form_tags ) {

			// Get field name to get form value
			$field_names = $this->get_email_and_list_name( $form_tags );

			// Check this form has mailpoet tag and email field is not empty and also checking the tag name
			$has_mailpoet_tag       = false;
			$has_mailpoetsignup_tag = false;
			$has_mpconsent_tag      = false;
			$mailpoet_tag_name      = '';
			if ( isset( $field_names['mailpoetsignup'] ) && ! empty( $field_names['mailpoetsignup'] ) && is_array( $field_names['mailpoetsignup'] ) ) {
				$has_mailpoet_tag       = true;
				$has_mailpoetsignup_tag = true;
				$mailpoet_tag_name      = 'mailpoetsignup';

			}

			if ( isset( $field_names['mpconsent'] ) && ! empty( $field_names['mpconsent'] ) && is_array( $field_names['mpconsent'] ) ) {
				$has_mailpoet_tag  = true;
				$has_mpconsent_tag = true;
				$mailpoet_tag_name = 'mpconsent';
			}

			$has_email_tag = false;
			if ( isset( $field_names['email'] ) && ! empty( $field_names['email'] ) ) {
				$has_email_tag = true;
			}

			// If has email and mailpoet tag
			if ( $has_mailpoet_tag && $has_email_tag ) {

				// If use `your-email` name for email field
				if ( isset( $form_data['your-email'] ) ) {
					$email = trim( $form_data['your-email'] );
				} else {
					$email = trim( $form_data[ $field_names['email'] ] );
				}

				// First name
				$firstname = '';
				if ( isset( $form_data['your-first-name'] ) ) {
					$firstname = trim( $form_data['your-first-name'] );
				} elseif ( isset( $form_data['your-name'] ) ) {
					$firstname = trim( $form_data['your-name'] );
				} elseif ( isset( $form_data['your-firstname'] ) ) {
					$firstname = trim( $form_data['your-firstname'] );
				} elseif ( isset( $form_data['firstname'] ) ) {
					$firstname = trim( $form_data['firstname'] );
				} else {
					$firstname = '';
				}

				// Last name
				$lastname = '';
				if ( isset( $form_data['your-last-name'] ) ) {
					$lastname = trim( $form_data['your-last-name'] );
				} elseif ( isset( $form_data['your-lastname'] ) ) {
					$lastname = trim( $form_data['your-lastname'] );
				} elseif ( isset( $form_data['lastname'] ) ) {
					$lastname = trim( $form_data['lastname'] );
				} else {
					$lastname = '';
				}

				// Save all list
				$list_ids = array();

				// Checking the tag name
				if ( $mailpoet_tag_name == 'mailpoetsignup' || $has_mailpoetsignup_tag ) {
					// Get all the form tags
					foreach ( $form_tags as $FormTag ) {
						$get_tags[] = $FormTag->basetype;
						foreach ( $get_tags as $get_tag ) {
							if ( 'mailpoetsignup' == $get_tag ) {
								$mailpoetsignup_name = $FormTag->name;
								if ( ! empty( $form_data[ $mailpoetsignup_name ] ) && ( is_array( $form_data[ $mailpoetsignup_name ] ) || is_object( $form_data[ $mailpoetsignup_name ] ) ) ) {

									foreach ( $form_data[ $mailpoetsignup_name ] as $selected ) {
										if ( $selected ) {
											// Get the existing subscriber's email (if exist) and get the existing segments id
											$subscriber = Subscriber::findOne( $email );
											if ( $subscriber ) {
												$subscriber->withSubscriptions();
												$current_lists = $subscriber->subscriptions;

												foreach ( $current_lists as $key => $value ) {
													$list_ids[] = $value['segment_id'];
												}
											}
											// Get the new list ids from hidden form
											$new_list_ids = $form_data['fieldVal'];

											$current_list = explode( ',', $new_list_ids );

											$list_ids = array_merge( $list_ids, $current_list );

										}
									}
								}
							}
						}
					}
				} elseif ( $mailpoet_tag_name == 'mpconsent' || $has_mpconsent_tag ) {
					// collecting the lists
					$lists = \MailPoet\API\API::MP( 'v1' )->getLists();
					// storing only Id numbers
					foreach ( $lists as $list ) {
						$list_ids[] = $list['id'];
					}
				}

				// Get custom fields and fields type
				$fields       = CustomField::findMany();
				$results      = array();
				$results_type = array();
				foreach ( $fields as $field ) {
					$results[ 'cf_' . $field->id ]      = $field->name;
					$results_type[ 'cf_' . $field->id ] = $field->type;
				}

				// Check mailpoet sign-up confirmation
				// $mp_signup_settings = new SettingsController();//old mailpoet settings object. Doesn't work since MailPoet version 3.39.1
				$mp_signup_settings     = SettingsController::getInstance();  // New settings object. Fixed added by k4mrul
				$mp_signup_confirmation = $mp_signup_settings->get( 'signup_confirmation.enabled' );
				if ( $mp_signup_confirmation ) {
					$signup_confirm_state = 'unconfirmed';
				} else {
					$signup_confirm_state = 'subscribed';
				}

				if ( ! empty( $list_ids ) && is_array( $list_ids ) ) {
					$subscribe_data = array(
						'email'      => $email,
						'first_name' => $firstname,
						'last_name'  => $lastname,
						'status'     => $signup_confirm_state,
					);

					// If custom field presents, append the fields value to subscribe data
					if ( ! empty( $results ) ) {
						foreach ( $results as $key => $value ) {
							foreach ( $results_type as $key_type => $value_type ) {
								if ( $value_type == 'radio' ) {
									$subscribe_data[ $key_type ] = $form_data[ $key_type ][0];
								} elseif ( $value_type == 'checkbox' && ! ( empty( $form_data[ $key_type ][0] ) ) ) {
									$subscribe_data[ $key_type ] = 1;
								} elseif ( $value_type == 'checkbox' && ( empty( $form_data[ $key_type ][0] ) ) ) {
									$subscribe_data[ $key_type ] = 0;
								} else {
									$subscribe_data[ $key ] = $form_data[ $key ];
								}
							}
						}
					}

					$options = array(
						'send_confirmation_email'      => $signup_confirm_state === 'unconfirmed' ? true : false,
						'skip_subscriber_notification' => true, // fixing double "New subscriber" email
					);
					// Saving new subscriber
					try {
						$subscriber = \MailPoet\API\API::MP( 'v1' )->addSubscriber( $subscribe_data, array_unique( $list_ids ), $options );
					} catch ( Exception $exception ) {

						// If subscriber is already subscribed once and unsubscribed later, then again subscribed to any list, change the status to subscribed and add to the list
						if ( 'This subscriber already exists.' == $exception->getMessage() ) {
							// Change subscriber status to subscribed
							$subscribe_data['status'] = 'subscribed';
							// Update the status
							$subscriber = Subscriber::createOrUpdate( $subscribe_data );
							// Now subscribe to the new list
							try {
								// If 'mpconsent' form active it will add all lists.
								if ( $has_mpconsent_tag && ! $has_mailpoetsignup_tag ) {
									$current_list = $list_ids;
								}
								$subscriber = \MailPoet\API\API::MP( 'v1' )->subscribeToLists(
									$subscriber->id,
									array_unique( $current_list )
								);

							} catch ( Exception $exception ) {

							}
						} else {

						}
					}
				}
			} else {
				// If the dont have mailpoet tag then return
				return;
			}
		}//end add_email_list()

		// Unsubscribe a email address
		public function unsubscribe_email( $form_data ) {

			if ( isset( $form_data['unsubscribe-email'] ) ) {

				if ( isset( $form_data['your-email'] ) ) {

					$subscriber_email = $form_data['your-email'];
					$subscriber       = Subscriber::findOne( $subscriber_email );

					if ( $subscriber !== false ) {

						try {
							// Old style for API function unsubscribeFromList of mailPoet\\
							// $subscriber = \MailPoet\API\API::MP('v1')->unsubscribeFromList($subscriber,  \MailPoet\API\API::MP('v1')->getLists());

							// new api new to send subscriberId and listIds\\
							// collecting the lists
							$lists = \MailPoet\API\API::MP( 'v1' )->getLists();
							// storing only Id numbers
							$listsIds = array();
							foreach ( $lists as $list ) {
								$listsIds[] = $list['id'];
							}
							// calling unsubscribe function of api
							$apiResult = \MailPoet\API\API::MP( 'v1' )->unsubscribeFromLists( $subscriber->id, $listsIds );
							// updating status
							$subscriber = Subscriber::createOrUpdate(
								array(
									'email'  => $subscriber->email,
									'status' => 'unsubscribed',
								)
							);

						} catch ( Exception $exception ) {

						}

						return true;
					}
				}
			}

			return false;
		} // End of unsubscribe_email

		/**
		 * Find email and subscribe list id
		 */
		public function get_email_and_list_name( $form_tags ) {
			if ( is_array( $form_tags ) ) {
				$form_names = array();
				foreach ( $form_tags as $FormTag ) {
					// Find type for email and mailpoetsignup
					switch ( $FormTag->basetype ) {
						case 'email': // get email tag name
							$form_names['email'] = $FormTag->name;
							break;

						case 'mailpoetsignup': // get subscribe checkbox name
							$form_names['mailpoetsignup'][] = $FormTag->name;
							break;

						case 'mpconsent': // get subscribe checkbox name
							$form_names['mpconsent'][] = $FormTag->name;
							break;
					}
				}//End foreach

				return $form_names;
			}//End if
		}//end get_email_and_list_name()

		/**
		 * Get list ids
		 *
		 * @return  Array of list id
		 * This function is no longer used. Now lists ids are directly fetching from hidden form
		 */
		/*
			  public function get_list_ids($form_data, $mailpoetsignup)
				{
					$ids_string_array = array();
					$ids_string = '';

					foreach($mailpoetsignup as $mailpoet_name){
						if(isset($form_data[$mailpoet_name]) && !empty($form_data[$mailpoet_name])){

							if ( is_array($form_data[$mailpoet_name]) ){
								$ids_string_array = $form_data[$mailpoet_name];
							} else {
								$ids_string_array[] = $form_data[$mailpoet_name];
							}
						}
					}

					if(!empty($ids_string_array)){
						$ids_string = implode(",", $ids_string_array);
					}

					if ( !empty($ids_string) ){
						return explode(",", $ids_string);
					} else {
						return [];
					}

				}//get_list_ids*/

	}//end class

	/**
	 * Instentiate submit form class
	 */
	MailPoet_CF7_Submit_Form::init();

}//End if
