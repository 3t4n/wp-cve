<?php
/**
 * Blogsqode Main Page
 *
 * @package Blogsqode\Admin
 */

	$output = new Blogsqode_Admin_Settings();

	if($_POST){
		$output->save();
	}

	$output->output();


