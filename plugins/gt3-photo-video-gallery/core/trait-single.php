<?php

namespace GT3\PhotoVideoGallery;


trait Single_Trait {
	final public static function instance(){
		$instance = Single::get(get_called_class());
		if (false === $instance) {
			$instance = new static();
			Single::set($instance);
		}

		return $instance;
	}
}
