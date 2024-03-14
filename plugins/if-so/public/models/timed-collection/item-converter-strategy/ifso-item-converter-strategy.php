<?php

/**
 * 
 * 
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('IfSo_ItemConverterStrategy')) {
	abstract class IfSo_ItemConverterStrategy {
		abstract public function convert_to_model( $item );
		abstract public function convert_to_array( $item );
	}
}