<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wensolutions.com/
 * @since      1.0.0
 *
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/public
 */

/**
 *
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/public
 * @author     WEN Solutions <info@wensolutions.com>
 */
class Cf7_Gr_Ext_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Function to replace tags.
	 * @param  string  $pattern     Replace pattern.
	 * @param  string  $subject     Subject to replace.
	 * @param  array  $posted_data  Array of posted data.
	 * @param  boolean $html        Get in html format.
	 * @return string               Replaced subject.
	 */
	private function tag_replace( $pattern, $subject, $posted_data, $html = false ) {

    	if( preg_match($pattern,$subject,$matches) > 0)
    	{

    		if ( isset( $posted_data[$matches[1]] ) ) {
    			$submitted = $posted_data[$matches[1]];

    			if ( is_array( $submitted ) )
    				$replaced = join( ', ', $submitted );
    			else
    				$replaced = $submitted;

    			if ( $html ) {
    				$replaced = strip_tags( $replaced );
    				$replaced = wptexturize( $replaced );
    			}

    			$replaced = apply_filters( 'wpcf7_mail_tag_replaced', $replaced, $submitted );

    			return stripslashes( $replaced );
    		}

    		if ( $special = apply_filters( 'wpcf7_special_mail_tags', '', $matches[1] ) )
    			return $special;

    		return $matches[0];
    	}
    	return $subject;

    }

    /**
     * Subscribe callback function.
     * @param  object $cf7 Cf7 data.
     * @return void      
     */
    function subscribe( $cf7 ) {
    	$cf7_gr = get_post_meta( $cf7->id(), 'cf7_gs_settings', true );
    	$submission = WPCF7_Submission::get_instance();

    	if( $cf7_gr ) {

			$callback = array( &$cf7, 'cf7_mch_callback' );
			$regex = '/\[\s*([a-zA-Z_][0-9a-zA-Z:._-]*)\s*\]/';
			$subscribe = false;

	        if( isset($cf7_gr['accept']) && strlen($cf7_gr['accept']) != 0 )
	        {
	          $accept = $this->tag_replace( $regex, $cf7_gr['accept'], $submission->get_posted_data() );
	          if($accept != $cf7_gr['accept'])
	          {
	            if(strlen($accept) > 0)
	              $subscribe = true;
	          }
	        }
	        else {
	          $subscribe = true;
	        }

	        if( !$subscribe )
	          return;


    		$email = $this->tag_replace( $regex, $cf7_gr['email'], $submission->get_posted_data() );
    		$name = $this->tag_replace( $regex, $cf7_gr['name'], $submission->get_posted_data() );

    		$lists = $this->tag_replace( $regex, $cf7_gr['list'], $submission->get_posted_data() );

	        $cf7_gr_ext_basics_options = get_option( 'cf7_gs_ext_basics_options' );
	        if( !isset( $cf7_gr_ext_basics_options['gs_con'] ) || 1 !== $cf7_gr_ext_basics_options['gs_con'] ){
	          error_log( 'Get response connection failed.' );
	          return;
	        }

        	$gs_api = new GetResponse( $cf7_gr_ext_basics_options['gs_key'] );

	        // Test GS connection
	        $account = $gs_api->accounts();

	        if( !isset( $account->accountId ) || '' == $account->accountId ){

	          error_log( 'Get response connection failed.' );
	          return;

	        }

	        // Campaigns
	        $campaign 	 = $gs_api->getCampaign( $lists );

	        if( !isset( $campaign->campaignId ) || '' == $campaign->campaignId ){
	          error_log( 'List not found.' );
	          return;
	        }

	        $gs_custom_fields = array();

	        $custom_values = array_filter($cf7_gr['custom_value']);
	        $custom_keys = array_filter($cf7_gr['custom_key']);

	        if ( ! empty( $custom_values ) || ! empty( $custom_keys ) ) {

				foreach ( $custom_values as $key => $value ) {
					if ( '' != $value && isset( $custom_keys[ $key ] ) && '' != $custom_keys[ $key ] ) {

						$gs_custom_fields['customFieldValues'][] = array(
						'customFieldId' => $custom_keys[ $key ],
						'value' => array( $this->tag_replace( $regex, trim( $value ), $submission->get_posted_data() ) )
						);
					}
				}

				$gs_custom_fields = array_filter( $gs_custom_fields );
	        }

			$gs_custom_fields['name'] = $name;
			$gs_custom_fields['email'] = $email;
			$gs_custom_fields['campaign'] = (object) array( 'campaignId' => $lists );
			$gs_custom_fields['dayOfCycle'] = apply_filters( 'wpcf7_autoresponder_day_cycle', 0 );
			$addContact = $gs_api->addContact( $gs_custom_fields );
		}

	}

}
