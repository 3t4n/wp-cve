<?php

namespace shellpress\v1_4_0\demo\src\Components;

use shellpress\v1_4_0\demo\Demo;
use shellpress\v1_4_0\src\Shared\Components\IComponent;

/**
 * @author jakubkuranda@gmail.com
 * Date: 16.09.2019
 * Time: 11:21
 */
class AdminPageExample extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		add_action( 'admin_menu', array( $this, '_a_init' ) );

	}

	//  ================================================================================
	//  ACTIONS
	//  ================================================================================

	public function _a_init() {

		add_menu_page(
			'ShellPress Demo',
			'ShellPress Demo',
			'manage_options',
			$this::s()->getPrefix( '_settings' ),
			array( $this, '_a_menuPageDisplay' ),
			'',
			'75'
		);

	}

	public function _a_menuPageDisplay() {

		echo '<br/>';
		echo '<br/>';
		echo '<br/>';

		echo '<p>Example of file uploader.</p>';
		echo Demo::i()->fileUploaderExample->getDisplay();

		echo '<br/>';
		echo '<br/>';
		echo '<br/>';

		echo '<p>Example of two components.</p>';
		echo Demo::i()->eddLicenseManagerExample->getDisplay();

		echo '<br/>';
		echo '<br/>';
		echo '<br/>';
		echo '<br/>';

		echo '<p>Creating a shortcode inside shortcode.</p>';
		echo Demo::i()->universalFrontExample->getDisplay();

		echo '<br/>';
		echo '<br/>';
		echo '<br/>';
		echo '<br/>';

		echo $this::s()->getUrl( 'assets/css/Tooltip/SPTooltip.css' );
		
		echo '<br/>';
		echo '<br/>';
		echo '<br/>';
		
		echo sprintf( '<p>getUrl(): %1$s', $this::s()->getUrl() );
		echo sprintf( '<p>getShellUrl(): %1$s', $this::s()->getShellUrl() );

	}

}