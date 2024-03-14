<?php
namespace ZPOS\Structure;

class EmptyEntity {
	public function __call($name, $args)
	{
		return null;
	}
};
