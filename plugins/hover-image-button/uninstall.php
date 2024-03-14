<?php
/**
 * Hover Image Button
 *
 * Actions executed when the plugin is uninstalled.
 *
 * @link https://github.com/igpremuo/hover-image-button
 *
 * @package   Hover_Image_Button
 * @author    Ignacio Pérez
 * @license   GPLv2 or later
 *
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Delete all the plugin options.
delete_option( HOVER_IMAGE_BUTTON_SETTINGS );