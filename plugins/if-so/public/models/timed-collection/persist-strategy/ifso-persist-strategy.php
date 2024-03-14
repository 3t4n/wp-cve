<?php

/**
 * 
 * Defines the base persist strategy
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('IfSo_PersistStrategy')) {
	abstract class IfSo_PersistStrategy {
		abstract public function get_items();
		abstract public function persist( $items );
	}
}