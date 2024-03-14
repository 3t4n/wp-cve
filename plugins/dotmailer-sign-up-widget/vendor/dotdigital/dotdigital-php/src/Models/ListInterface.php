<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\Models;

interface ListInterface
{
    /**
     * @param array<mixed> $listItem
     *
     * @return mixed
     */
    public function getOne(array $listItem);
}
