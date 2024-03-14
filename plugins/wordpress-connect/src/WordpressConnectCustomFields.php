<?php

/**
 * Controlls Wordpress Custom Fields
 * @since 2.0
 */
class WordpressConnectCustomFields {

	/**
	* @var string $prefix	The prefix for storing custom fields in
	* 						the postmeta table
    */
	const PREFIX = "wordpress_connect_custom_fields_";

	/**
	 * Creates a new WordpressConnectCustomFields object
	 *
	 * @since	2.0
	 *
	 */
	function WordpressConnectCustomFields(){

		$this->addInitHooks();

	}


	/**
	 * Adds init wordpress hook.
	 *
	 * @private
	 * @since	2.0
	 */
	function addInitHooks(){

		// this required WP 3.0+
		add_action( 'add_meta_boxes', array( &$this, 'addCustomFields' ) );

		// backwards compatible
		//add_action( 'admin_init', rray( &$this, 'addCustomFields' ), 1 );

		/* save the data entered */
		add_action( 'save_post', array( &$this, 'saveCustomFieldData' ) );

	}

	/**
	 * Adds custom fields.
	 *
	 * @private
	 * @since	2.0
	 */
	function addCustomFields(){

	    add_meta_box(
			WordpressConnectCustomFields::PREFIX,
			__( 'Wordpress Connect', WPC_TEXT_DOMAIN ),
			array( &$this, 'renderCustomField' ),
			'page',
			'side', /* side doesn't exists before 2.7 */
			'high'
		);

	    add_meta_box(
			WordpressConnectCustomFields::PREFIX,
			__( 'Wordpress Connect', WPC_TEXT_DOMAIN ),
			array( &$this, 'renderCustomField' ),
			'post',
			'side', /* side doesn't exists before 2.7 */
			'high'
		);
	}

	/**
	 * Renders the wordpress connect custom fields.
	 *
	 * @private
	 * @since	2.0
	 */
	function renderCustomField(){

		global $post;

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), WordpressConnectCustomFields::PREFIX );

		// render the like custom field
		$this->renderCustomFieldForLikeButton();

		// render the comments custom field
		$this->renderCustomFieldForComments();

	}

	/**
	 * Renders a dropdown
	 *
	 * @param $label		The field label
	 * @param $options		Available options
	 * @param $fieldName	The field key/name
	 * @param $defaut		The default value
	 */
	function renderCustomFieldDropdown( $label, $options, $fieldName, $default ){

		global $post;

		$value = get_post_meta( $post->ID, WordpressConnectCustomFields::PREFIX . $fieldName, TRUE );

		if ( empty( $value ) ){ $value = $default; }

  		echo '<p><strong>', $label ,'</strong></p>';
  		echo '<label class="screen-reader-text" for="', WordpressConnectCustomFields::PREFIX . $fieldName ,'">', $label , '</label> ';

		echo '<select name="', WordpressConnectCustomFields::PREFIX , $fieldName ,'">';

		foreach( $options as $optionValue => $optionLabel ){

			$selected = ( $value == $optionValue ) ? ' selected="selected"' : '';
			echo '<option value="', $optionValue ,'"', $selected ,'>', $optionLabel , '</option>';

		}

		echo '</select></p>';


	}


	/**
	 * Renders the facebook like button custom field
	 *
	 * @private
	 * @since	2.0
	 */
	function renderCustomFieldForLikeButton(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$enabled_value = $options[ WPC_OPTIONS_LIKE_BUTTON_ENABLED ];
		$position_value = $options[ WPC_OPTIONS_LIKE_BUTTON_POSITION ];

		$this->renderCustomFieldDropdown(
			__( 'Like Button', WPC_TEXT_DOMAIN ),
			array(
				WPC_OPTION_ENABLED => __( 'Enabled', WPC_TEXT_DOMAIN ),
				WPC_OPTION_DISABLED => __( 'Disabled', WPC_TEXT_DOMAIN )
			),
			WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_ENABLE,
			$enabled_value
		);

		$this->renderCustomFieldDropdown(
			__( 'Like Button Position', WPC_TEXT_DOMAIN ),
			array(
				WPC_CUSTOM_FIELD_VALUE_POSITION_TOP => __( 'Top', WPC_TEXT_DOMAIN ),
				WPC_CUSTOM_FIELD_VALUE_POSITION_BOTTOM => __( 'Bottom', WPC_TEXT_DOMAIN ),
				WPC_CUSTOM_FIELD_VALUE_POSITION_CUSTOM => __( 'Custom', WPC_TEXT_DOMAIN )
			),
			WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_POSITION,
			$position_value
		);
	}

	/**
	 * Renders the facebook comments custom field
	 *
	 * @private
	 * @since	2.0
	 */
	function renderCustomFieldForComments(){

		$options = get_option( WPC_OPTIONS_COMMENTS );
		$enabled_value = $options[ WPC_OPTIONS_COMMENTS_ENABLED ];
		$position_value = $options[ WPC_OPTIONS_COMMENTS_POSITION ];

		$this->renderCustomFieldDropdown(
			__( 'Comments', WPC_TEXT_DOMAIN ),
			array(
				WPC_OPTION_ENABLED => __( 'Enabled', WPC_TEXT_DOMAIN ),
				WPC_OPTION_DISABLED => __( 'Disabled', WPC_TEXT_DOMAIN )
			),
			WPC_CUSTOM_FIELD_NAME_COMMENTS_ENABLE,
			$enabled_value
		);

		$this->renderCustomFieldDropdown(
			__( 'Comments Position', WPC_TEXT_DOMAIN ),
			array(
				WPC_CUSTOM_FIELD_VALUE_POSITION_TOP => __( 'Top', WPC_TEXT_DOMAIN ),
				WPC_CUSTOM_FIELD_VALUE_POSITION_BOTTOM => __( 'Bottom', WPC_TEXT_DOMAIN ),
				WPC_CUSTOM_FIELD_VALUE_POSITION_CUSTOM => __( 'Custom', WPC_TEXT_DOMAIN )
			),
			WPC_CUSTOM_FIELD_NAME_COMMENTS_POSITION,
			$position_value
		);
	}

	/**
	 * Saves the custom field data
	 *
	 * @private
	 * @since	1.0
	 */
	function saveCustomFieldData( $post_id ){

		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times

		if ( !wp_verify_nonce( $_POST[ WordpressConnectCustomFields::PREFIX ], plugin_basename(__FILE__) ) ){
			return $post_id;
		}

		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
		// to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return $post_id;
		}


		// Check permissions
//		if ( 'page' == $_POST['post_type'] ) {
//			if ( !current_user_can( 'edit_page', $post_id ) ){
//				return $post_id;
//			}
//		} else {
//			if ( !current_user_can( 'edit_post', $post_id ) ){
//				return $post_id;
//			}
//		}


  		// OK, we're authenticated: we need to find and save the data
		$fields = array(
			WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_ENABLE,
			WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_POSITION,
			WPC_CUSTOM_FIELD_NAME_COMMENTS_ENABLE,
			WPC_CUSTOM_FIELD_NAME_COMMENTS_POSITION
		);

		$result = array();

		foreach( $fields as $field ){

			$value = $_POST[ WordpressConnectCustomFields::PREFIX . $field ];

			update_post_meta(
				$post_id,
				WordpressConnectCustomFields::PREFIX . $field,
				"" . $value
			);

			$result[ $field ] = $value;
		}

		return $result;
	}
}