<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V2\Models;

use Dotdigital_WordPress_Vendor\Dotdigital\Models\ListInterface;
class ContactList extends AbstractListModel implements ListInterface
{
    /**
     * @var string[]
     */
    protected $ignore = ['status'];
    /**
     * @param array<mixed> $listItem
     *
     * @return Contact
     * @throws \Exception
     */
    public function getOne(array $listItem)
    {
        return new Contact($listItem);
    }
}
