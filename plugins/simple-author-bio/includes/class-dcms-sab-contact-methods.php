<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dcms_Contact_Methods{

	public $dcms_sab_social =[
		'url'			=> 'Web',
        'twitter'       => 'Twitter',
        'googleplus'    => 'Google Plus',
        'facebook'      => 'Facebook',
        'github'        => 'Github',
        'linkedin'      => 'Linkedin',
        'pinterest'     => 'Pinterest',   
      	'youtube'		=> 'YouTube',
        'instagram'     => 'Instagram',
	];


	/*
	* Additional social networks
	*/
	public function dcms_sab_add_social_fields( $user_contact ){
		
		foreach ( $this->dcms_sab_social as $key => $value ) {
			
			if ( $key != 'url' ){
				$user_contact[$key] = $value;
			}

		}
		
		return $user_contact;
	}


}