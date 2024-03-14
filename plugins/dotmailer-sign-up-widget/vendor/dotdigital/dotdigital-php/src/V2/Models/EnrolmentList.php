<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital\V2\Models;

use Dotdigital_WordPress_Vendor\Dotdigital\Models\ListInterface;
class EnrolmentList extends AbstractListModel implements ListInterface
{
    /**
     * @param array<mixed> $listItem
     *
     * @return Enrolment
     * @throws \Exception
     */
    public function getOne(array $listItem)
    {
        return new Enrolment($listItem);
    }
}
