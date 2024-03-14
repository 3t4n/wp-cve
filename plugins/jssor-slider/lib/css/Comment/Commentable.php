<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

interface WjsslCssCommentable {

	/**
	 * @param array $aComments Array of comments.
	 */
	public function addComments(array $aComments);

	/**
	 * @return array
	 */
	public function getComments();

	/**
	 * @param array $aComments Array containing WjsslComment objects.
	 */
	public function setComments(array $aComments);


}
