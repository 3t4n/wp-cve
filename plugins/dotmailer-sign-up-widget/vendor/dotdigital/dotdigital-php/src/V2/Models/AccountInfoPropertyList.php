<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital\V2\Models;

use Dotdigital_WordPress_Vendor\Dotdigital\Models\ListInterface;
class AccountInfoPropertyList extends AbstractListModel implements ListInterface
{
    /**
     * @param array<mixed> $listItem
     *
     * @return AccountInfoProperty
     * @throws \Exception
     */
    public function getOne(array $listItem)
    {
        return new AccountInfoProperty($listItem);
    }
}
