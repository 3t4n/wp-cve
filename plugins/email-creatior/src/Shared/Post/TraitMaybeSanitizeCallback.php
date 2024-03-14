<?php


namespace WilokeEmailCreator\Shared\Post;


trait TraitMaybeSanitizeCallback {
	/**
	 * @throws \Exception
	 */
	protected function maybeSanitizeCallback( $aField, $value ) {
		if ( ! isset( $aField['sanitizeCallback'] ) ) {
			return $value;
		}
		if (is_array($aField['sanitizeCallback'])){
            if (!method_exists($aField['sanitizeCallback'][0],$aField['sanitizeCallback'][1])) {
                throw new \Exception( esc_html__( 'The sanitize callback for handling %s is not exists.', 'myshopkit' ), $aField['key'] );
            }
        }else{
            if ( ! function_exists( $aField['sanitizeCallback'] ) && ! is_callable($aField['sanitizeCallback'] )) {
                throw new \Exception( esc_html__( 'The sanitize callback for handling %s is not exists.', 'myshopkit' ), $aField['key'] );
            }
        }
		return call_user_func_array( $aField['sanitizeCallback'], [ $value] );
	}
}
