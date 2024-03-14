<?php
namespace ZPOS\Admin\Setting\Sanitize;

trait Boolean
{
	public function sanitizeBoolean($data)
	{
		return filter_var($data, FILTER_VALIDATE_BOOLEAN);
	}
}
