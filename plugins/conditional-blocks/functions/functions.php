<?php
/**
 * Function used with the Condition API to check if a block should be rendered when using the BlockAction field.
 * 
 * @param mixed $has_match
 * @param mixed $condition
 * @return boolean
 */
function cb_check_block_action( $has_match, $condition ) {

	$block_action = ! empty( $condition['blockAction'] ) ? $condition['blockAction'] : 'showBlock';

	if ( $has_match && $block_action === 'showBlock' ) {
		return true;
	} elseif ( ! $has_match && $block_action === 'hideBlock' ) {
		return true;
	}

	return false;
}

