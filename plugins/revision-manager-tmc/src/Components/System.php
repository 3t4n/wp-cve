<?php
namespace tmc\revisionmanager\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 29.10.2018
 * Time: 12:21
 */

use shellpress\v1_4_0\src\Shared\Components\IComponent;

class System extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {
		// TODO: Implement onSetUp() method.
	}

	/**
	 * @return string
	 */
	public function getAllSystemInfoDump() {

		$html = '';

		//  TODO - section titles and more info.

		$html .= $this->_wrapTitle( 'Blog info' );
		$html .= $this->_getBlogInfo();

		$html .= $this->_wrapTitle( 'Wordpress plugins' );
		$html .= $this->_getWordpressPlugins();

		$html .= $this->_wrapTitle( 'Plugin settings' );
		$html .= $this->_getPluginSettings();


		$html .= $this->_wrapTitle( 'Constants' );
		$html .= $this->_getConstants();

		return $html;

	}

	/**
	 * @return string
	 */
	private function _getConstants() {

		return print_r( get_defined_constants( true ), true ) . PHP_EOL;

	}

	/**
	 * @return string
	 */
	private function _getBlogInfo() {

		$html = '';

		if( function_exists( 'get_bloginfo' ) ){

			$fields = array( 'name', 'description', 'wpurl', 'url', 'admin_email', 'charset', 'version', 'html_type', 'language' );

			foreach( $fields as $field ){
				$html .= str_pad( $field, 100 ) . get_bloginfo( $field ) . PHP_EOL;
			}

		}

		return $html;

	}

	/**
	 * @return string
	 */
	private function _getWordpressPlugins() {

		$html = '';

		if( function_exists( 'get_plugins' ) ){

			$plugins = get_plugins();

			foreach( $plugins as $pluginSlug => $pluginInfo ){  /** @var array $pluginInfo */

				$html .= sprintf( '%1$s(%2$s) - %3$s',
							str_pad( $pluginSlug, 100 ),
							$pluginInfo['Name'],
							$pluginInfo['Version']
				         ) . PHP_EOL;

			}

		}

		return $html;

	}

	/**
	 * @return string
	 */
	private function _getPluginSettings() {

		$html = '';

		$html .= print_r( $this::s()->options->get(), true ) . PHP_EOL;

		return $html;

	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	private function _wrapTitle( $text ) {

		$html = '' . PHP_EOL;
		$html .= '//  ----------------------------------------' . PHP_EOL;
		$html .= '//  ' . $text . PHP_EOL;
		$html .= '//  ----------------------------------------' . PHP_EOL . PHP_EOL;

		return $html;

	}

}