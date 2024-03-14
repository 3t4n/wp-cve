<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital\Models;

abstract class AbstractModel
{
    /**
     * @param string|array<mixed> $content
     *
     * @return void
     * @throws \Exception
     */
    protected abstract function validate($content);
}
