<?php

class ICWP_APP_Api_Internal_Plugin_Install extends ICWP_APP_Api_Internal_Base {

	/**
	 * @inheritDoc
	 */
	public function process() {
		$params = $this->getActionParams();

		if ( empty( $params[ 'url' ] ) ) {
			return $this->fail(
				[],
				'The URL was empty.'
			);
		}

		$installURL = wp_http_validate_url( $params[ 'url' ] );
		if ( !$installURL ) {
			return $this->fail(
				'The URL did not pass the WordPress HTTP URL Validation.'
			);
		}

		$WPP = $this->loadWpPlugins();

		$result = $WPP->install( $installURL, $params[ 'overwrite' ] );
		if ( empty( $result[ 'successful' ] ) ) {
			return $this->fail( implode( ' | ', $result[ 'errors' ] ), -1, $result );
		}

		//activate as required
		$pluginFile = $result[ 'plugin_info' ];
		if ( !empty( $pluginFile ) && isset( $params[ 'activate' ] ) && $params[ 'activate' ] == 1 ) {
			$WPP->activate( $pluginFile, $params[ 'network_wide' ] );
		}

		wp_cache_flush(); // since we've added a plugin

		return $this->success( [
			'result'            => $result,
			'wordpress-plugins' => $this->getWpCollector()->collectWordpressPlugins()
		] );
	}
}