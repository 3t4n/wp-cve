<?php
/**
 *	Copyright 2013-2015 Renzo Johnson (email: renzojohnson at gmail.com)
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *  *1: By default the key label for the name must be FNAME
 *  *2: parse first & last name
 *  *3: ensure we set first and last name exist
 *  *4: otherwise user provided just one name
 *  *5: By default the key label for the name must be FNAME
 *  *6: check if subscribed
 *  *bh: email_type
 *  *aw: double_optin
 *  *xz: update_existing
 *  *rd: replace_interests
 *  *gr: send_welcome
 * Tool Aweber for WordPress
 *
 * @author    Renzo Johnson (email: renzo.johnson at gmail.com)
 * @link      http://renzojohnson.com/
 * @copyright 2015 Renzo Johnson (email: renzo.johnson at gmail.com)
 *
 * @package AweberMail
 */


/**
 * Function Comment *
 * @since   0.1
 */
function awb_author() {

	$author_pre = 'Contact form 7 Aweber extension by ';
	$author_name = 'Renzo Johnson';
	$author_url = 'http://renzojohnson.com';
	$author_title = 'Renzo Johnson - Web Developer';

	$awb_author = '<p style="display: none !important">';
	$awb_author .= $author_pre;
	$awb_author .= '<a href="'.$author_url.'" ';
	$awb_author .= 'title="'.$author_title.'" ';
	$awb_author .= 'target="_blank">';
	$awb_author .= ''.$author_title.'';
	$awb_author .= '</a>';
	$awb_author .= '</p>'. "\n";

	return $awb_author;
}

/**
 * Function Comment *
 * @since   0.1
 */
function awb_referer() {

	// $awb_referer_url = $THE_REFER=strval(isset($_SERVER['HTTP_REFERER']));
	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {

		$awb_referer_url = $_SERVER['HTTP_REFERER'];

	} else {

		$awb_referer_url = 'direct visit';

	}

	$awb_referer = '<p style="display: none !important"><span class="wpcf7-form-control-wrap referer-page">';
	$awb_referer .= '<input type="hidden" name="referer-page" ';
	$awb_referer .= 'value="'.$awb_referer_url.'" ';
	$awb_referer .= 'size="40" class="wpcf7-form-control wpcf7-text referer-page" aria-invalid="false">';
	$awb_referer .= '</span></p>'. "\n";

	return $awb_referer;
}

/**
 * Missing function doc comment
 * @param string $form_tag Missing parameter comment.
 */
function awb_getRefererPage( $form_tag ) {

	if ( $form_tag['name'] == 'referer-page' ) {
		  $form_tag['values'][] = $_SERVER['HTTP_REFERER'];
	}
	return $form_tag;
}

if ( ! is_admin() ) {
		add_filter( 'wpcf7_form_tag', 'awb_getRefererPage' );
}

define( 'AWB_URL', 'http://renzojohnson.com/contributions/contact-form-7-Aweber-extension' );
define( 'AWB_AUTH', 'http://renzojohnson.com' );
define( 'AWB_AUTH_COMM', '<!-- Aweber extension by Renzo Johnson -->' );
define( 'SPARTAN_AWB_NAME', 'Aweber Monitor Extension' );
define( 'AWBPL_URL', '//chimpmatic.com' );
define( 'AWBHELP_URL', '//chimpmatic.com/help' );

/**
 * Missing function doc comment
 * @param string $awb_tool_ref Missing parameter comment.
 * @param string $aweb_tool_unpolluted Missing parameter comment.
 * @param string $msgerror Missing parameter comment.
 * @param string $logfileEnabled Missing parameter comment.
 */
function wpcf7_awb_tool_management_page( $awb_tool_ref, $aweb_tool_unpolluted, &$msgerror, $logfileEnabled ) {
	$sss = base64_decode( 'NTY0MTkwMjczZGRkMDAuMTc0Mjc0NjU=' );
	$vvv = base64_decode( 'aHR0cDovL2NoaW1wbWFpbC5jbw==' );
	$hhh = base64_decode( 'cGx1Z2luIGF3Ym1haWw=' );

	$awb_debug_logger = new awb_Debug_Logger();

	$variderr = 1;

	define( 'AWB_TOOL_MAX_LENGTH', $sss );
	define( 'AWB_TOOL_MAX_MEMORY', $vvv );
	define( 'AWB_TOOL_REFERENCE', $hhh );
	if ( 1 == $aweb_tool_unpolluted ) {
		$api_params = array(
		'slm_action' => 'slm_activate',
		'secret_key' => AWB_TOOL_MAX_LENGTH,
		'license_key' => $awb_tool_ref,
					  'registered_domain' => $_SERVER['SERVER_NAME'],
		'item_reference' => urlencode( AWB_TOOL_REFERENCE ),
		);

		$query = esc_url_raw( add_query_arg( $api_params, AWB_TOOL_MAX_MEMORY ) );
		$response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

		if ( is_wp_error( $response ) ) {
			$msgerror = 'Not load Tool';
			$variderr = 4 ;
			$awb_debug_logger->log_awb_debug( 'Tool Key Response - Result: ' .$msgerror,$variderr,$logfileEnabled );
			return -1;

		}
		$awb_tool_data = json_decode( wp_remote_retrieve_body( $response ) );
		if ( $awb_tool_data->result == 'success' ) {
			$msgerror = $awb_tool_data->message;
			$awb_debug_logger->log_awb_debug( 'Tool Key Response - Result: ' .$msgerror,$variderr,$logfileEnabled );
			return 1;
		} else {
			$msgerror = $awb_tool_data->message;
			if ( 'Invalid license key' == $msgerror ) {
				$variderr = 4 ;
				$awb_debug_logger->log_awb_debug( 'Tool Key Response - Result: ' .$msgerror,$variderr,$logfileEnabled );
				return -2;
			} else {
				$awb_debug_logger->log_awb_debug( 'Tool Key Response - Result: ' .$msgerror,$variderr,$logfileEnabled );
				return 2;
			}
		}
	}
	if ( 2 == $aweb_tool_unpolluted ) {
		$api_params = array(
		  'slm_action' => 'slm_deactivate',
		  'secret_key' => AWB_TOOL_MAX_LENGTH,
		  'license_key' => $awb_tool_ref,
		  'registered_domain' => $_SERVER['SERVER_NAME'],
		  'item_reference' => urlencode( AWB_TOOL_REFERENCE ),
			);

		$query = esc_url_raw( add_query_arg( $api_params, AWB_TOOL_MAX_MEMORY ) );
		$response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );
		if ( is_wp_error( $response ) ) {
			$variderr = 4 ;
			$awb_debug_logger->log_awb_debug( 'Tool Key Response - Result: ' .$msgerror,$variderr,$logfileEnabled );
			return -1;
		}
		$awb_tool_data = json_decode( wp_remote_retrieve_body( $response ) );
		if ( $awb_tool_data->result == 'success' ) {
			$msgerror = $awb_tool_data->message;
			$awb_debug_logger->log_awb_debug( 'Tool Key Response - Result: ' .$msgerror,$variderr,$logfileEnabled );
			return 1;
		} else {
			$msgerror = $awb_tool_data->message;
			$variderr = 4 ;
			$awb_debug_logger->log_awb_debug( 'Tool Key Response - Result: ' .$msgerror,$variderr,$logfileEnabled );
			return -2;
		}
	}

}

/** Funciones Agregadas */

function awb_save_date_activation() {
	$option_name = 'awb_loyalty' ;
	$new_value = getdate() ;
  
	$valorvar = get_option( $option_name );
  
	if ( $valorvar !== false ) {
  
	  if (empty($valorvar)) {
		  update_option( $option_name, $new_value );
	  }
  
	} else {
  
		$deprecated = null;
		$autoload = 'no';
		add_option( $option_name, $new_value, $deprecated, $autoload );
	}
  
}

function awb_difer_dateact_date() {
  $option_name = 'awb_loyalty' ;
	$today = getdate() ;
	awb_save_date_activation();
  
	$date_act = get_option( $option_name );
  
	$datetime_ini = new DateTime("now");
	$datetime_fin = new DateTime($date_act['year'].'-'.$date_act['mon'].'-'.$date_act['wday']);
  
	$fechaF = date_diff($datetime_ini,$datetime_fin);
  
  
	if ($fechaF->y > 0 ) {
	   if ($fechaF->m > 0 ) {
		  $differenceFormat = '%y Years %m Months %d Days ';
	   } else {
		 $differenceFormat = '%y Years %d Days ';
	   }
	} else {
	  if ($fechaF->m > 0 ) {
		  $differenceFormat = '%m Months %d Days ';
	   } else {
		 $differenceFormat = '%d Days ';
	   }
	}
  
	$resultf = $fechaF->format($differenceFormat);
  
  
	return $resultf;
  
}


function awb_mail_tags() {

	$listatags = wpcf7_form_awb_tags();
	$tag_submit = array_pop($listatags);
	$tagInfo = '';
  
	  foreach($listatags as $tag){
  
		$tagInfo .= '<span class="mailtag code used">[' . $tag['name'].']</span>';
  
	  }
  
	return $tagInfo;
  
}

function pluginaw_activation( $plugin ) {
    if( ! function_exists('activate_plugin') ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if( ! is_plugin_active( $plugin ) ) {
        activate_plugin( $plugin );
    }
}