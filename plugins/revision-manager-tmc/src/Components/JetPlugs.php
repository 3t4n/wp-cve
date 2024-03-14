<?php
namespace tmc\revisionmanager\src\Components;

use shellpress\v1_4_0\src\Shared\Components\IComponent;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * @author jakubkuranda@gmail.com
 * Date: 22.02.2018
 * Time: 11:22
 */

class JetPlugs extends IComponent {
	
	const JETPLUGS_SERVER        = 'https://jetplugs.com';
	const JETPLUGS_SERVER_USER   = 'ck_1617ab566a52f44af850c563a76382d24fd3c36c';
	const JETPLUGS_SERVER_PASS   = 'cs_085d623c3cd7d9a756696b4171066cf7c22cbc70';
	
	protected function onSetUp() {
	
		add_action( 'rest_api_init', function(){
			
			register_rest_route( 'rm_tmc/v1', 'jetplugs/a/(?P<code>[\w-]+)', array(
				'methods'               =>  'POST',
				'callback'              =>  array( $this, '_a_ajax_aCode' ),
				'permission_callback'   =>  function(){
					return current_user_can( 'manage_options' );
				}
			) );
			
			register_rest_route( 'rm_tmc/v1', 'jetplugs/d/(?P<code>[\w-]+)', array(
				'methods'               =>  'POST',
				'callback'              =>  array( $this, '_a_ajax_dCode' ),
				'permission_callback'   =>  function(){
					return current_user_can( 'manage_options' );
				}
			) );
			
		} );
		
	}
	
	/**
	 * @return bool
	 */
	public function isCodeActive() {
		
		$code           = $this->getCode();
		$isDomainOk     = $this->_isCodeActiveForCurrentDomain();
		$isCorrect      = $this::s()->options->get( 'license/isKeyCorrect' );
		
		if( $code && $isDomainOk && $isCorrect ){
			
			$timeNow    = time();
			$timeExpire = $this::s()->options->get( 'license/keyExpiryDatetime' );
			
			if( $timeExpire === 'lifetime' ){
				return true;
			} else if( $timeNow < strtotime( $timeExpire ) ){
				return true;
			}
			
		}
		
		return false;
		
	}
	
	/**
	 * @return string
	 */
	public function getCode() {
		
		return $this::s()->options->get( 'license/key' );
		
	}
	
	/**
	 * Checks if current domain is the same as the one saved in options.
	 *
	 * @return bool
	 */
	private function _isCodeActiveForCurrentDomain() {
		
		return get_site_url() === $this::s()->options->get( 'license/domain' );
		
	}
	
	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function _a_ajax_aCode( $request ){
		
		$license        = $this::s()->get( $request->get_url_params(), 'code' );
		$result         = new WP_REST_Response();
		
		//  ----------------------------------------
		//  Make request
		//  ----------------------------------------
		
		$activationResponse = wp_remote_get( self::JETPLUGS_SERVER . '/wp-json/lmfwc/v2/licenses/activate/' . $license, array(
			'headers'           =>  array(
				'Authorization'     =>  'Basic ' . base64_encode( self::JETPLUGS_SERVER_USER . ':' . self::JETPLUGS_SERVER_PASS )
			)
		) );
		
		$activationData         = wp_remote_retrieve_body( $activationResponse );
		$activationData         = json_decode( $activationData, true );
		
		//  ----------------------------------------
		//  Logic
		//  ----------------------------------------
		
		if( $this::s()->get( $activationData, 'success' ) ){
			
			$this::s()->options->set( 'license', array(
				'key'                           =>  $license,
				'keyExpiryDatetime'             =>  'lifetime',
				'lastCheckDatetime'             =>  current_time( 'mysql' ),
				'keyStatus'                     =>  null,
				'isKeyCorrect'                  =>  true,
				'domain'                        =>  get_site_url()
			) );
			$this::s()->options->flush();
		
		} else {
			$message = $this::s()->get( $activationData, 'message', __( "Could not activate your license." ) );
			return new WP_Error( 'error', $message );
		}
		
		return $result;
		
	}
	
	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function _a_ajax_dCode( $request ){
		
		$license        = $this::s()->get( $request->get_url_params(), 'code' );
		$result         = new WP_REST_Response();
		
		//  ----------------------------------------
		//  Make request
		//  ----------------------------------------
		
		$deactivationResponse   = wp_remote_get( self::JETPLUGS_SERVER . '/wp-json/lmfwc/v2/licenses/deactivate/' . $license, array(
			'headers'           =>  array(
				'Authorization'     =>  'Basic ' . base64_encode( self::JETPLUGS_SERVER_USER . ':' . self::JETPLUGS_SERVER_PASS )
			)
		) );
		$deactivationData       = wp_remote_retrieve_body( $deactivationResponse );
		$deactivationData       = json_decode( $deactivationData, true );
		
		//  ----------------------------------------
		//  Logic
		//  ----------------------------------------
		
		if( $this::s()->get( $deactivationData, 'success' ) ){
			
			$this::s()->options->set( 'license', array(
				'key'                           =>  null,
				'keyExpiryDatetime'             =>  null,
				'lastCheckDatetime'             =>  current_time( 'mysql' ),
				'keyStatus'                     =>  null,
				'isKeyCorrect'                  =>  false,
				'domain'                        =>  null
			) );
			$this::s()->options->flush();
			
		} else {
			$message = $this::s()->get( $deactivationData, 'message', __( "Could not deactivate your license." ) );
			return new WP_Error( 'error', $message );
		}
		
		return $result;
		
	}
	
}