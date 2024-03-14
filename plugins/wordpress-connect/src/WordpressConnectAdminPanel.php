<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/admin/WordpressConnectAdminPanelComments.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/admin/WordpressConnectAdminPanelGeneral.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/admin/WordpressConnectAdminPanelLikeButton.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/admin/WordpressConnectAdminPanelLikeBox.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/admin/WordpressConnectEditorButtons.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 16 Apr 2011
 *
 * @file WordpressConnectAdminPanel.php
 *
 * This class provides functionality for the wordpress dashboard admin
 * panel for the Wordpress Connect wordpress plugin
 */
class WordpressConnectAdminPanel {

	/**
	 * Stores a pointer to the admin panel general settings class
	 * @var WordpressConnectAdminPanelGeneral
	 */
	private $general;

	/**
	 * Stores a pointer to the admin panel comments settings class
	 * @var WordpressConnectAdminPanelComments
	 */
	private $comments;

	/**
	 * Stores a pointer to the admin panel like box settings class
	 * @var WordpressConnectAdminPanelLikeBox
	 */
	private $likeBox;

	/**
	 * Stores a pointer to the admin panel like button settings class
	 * @var WordpressConnectAdminPanelLikeButton
	 */
	private $likeButton;

	/**
	 * Creates a new instance of WordpressConnectAdminPanel
	 *
	 * @since	1.0
	 */
	function WordpressConnectAdminPanel(){

		$this->general = new WordpressConnectAdminPanelGeneral();
		$this->likeButton = new WordpressConnectAdminPanelLikeButton();
		$this->comments = new WordpressConnectAdminPanelComments();
		$this->likeBox = new WordpressConnectAdminPanelLikeBox();

		$editor = new WordpressConnectEditorButtons();

	}

	/**
	 * Restores default configuration
	 */
	public static function restoreDefaults(){

		WordpressConnectAdminPanelGeneral::restoreDefaults();
		WordpressConnectAdminPanelComments::restoreDefaults();
		WordpressConnectAdminPanelLikeBox::restoreDefaults();
		WordpressConnectAdminPanelLikeButton::restoreDefaults();

	}
}
?>