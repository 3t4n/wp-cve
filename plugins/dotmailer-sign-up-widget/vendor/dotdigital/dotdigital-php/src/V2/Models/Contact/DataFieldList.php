<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V2\Models\Contact;

use Dotdigital_WordPress_Vendor\Dotdigital\Models\ListInterface;
use Dotdigital_WordPress_Vendor\Dotdigital\V2\Models\AbstractListModel;
class DataFieldList extends AbstractListModel implements ListInterface
{
    /**
     * @param array<mixed> $listItem
     *
     * @return DataField
     * @throws \Exception
     */
    public function getOne(array $listItem)
    {
        return new DataField($listItem);
    }
}
