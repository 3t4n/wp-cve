<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact\Import;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\AbstractSingletonModel;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact\Identifiers;
class Failure extends AbstractSingletonModel
{
    /**
     * @var int
     */
    protected int $contactId;
    /**
     * @var Identifiers
     */
    protected Identifiers $identifiers;
    /**
     * @var FailureDetailCollection
     */
    protected FailureDetailCollection $failures;
    /**
     * @param array $failureDetailsData
     * @return FailureDetailCollection
     * @throws \Exception
     */
    private function createFailureDetails(array $failureDetailsData) : FailureDetailCollection
    {
        $failureDetails = new FailureDetailCollection();
        foreach ($failureDetailsData as $failureDetailData) {
            $failureDetail = new FailureDetail($failureDetailData);
            $failureDetails->add($failureDetail);
        }
        return $failureDetails;
    }
    /**
     * @param array $identifiers
     * @return void
     * @throws \Exception
     */
    public function setIdentifiers(array $identifiers) : void
    {
        $this->identifiers = new Identifiers($identifiers);
    }
    /**
     * @param array $failures
     * @return void
     * @throws \Exception
     */
    public function setFailures(array $failures) : void
    {
        $this->failures = $this->createFailureDetails($failures);
    }
}
