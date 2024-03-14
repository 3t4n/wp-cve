<?php
/**
 * @noinspection PhpPrivateFieldCanBeLocalVariableInspection  In the future,
 *   $address may be made a local variable, but probably we will need it as a
 *   property.
 */

declare(strict_types=1);

namespace Siel\Acumulus\Completors;

use Siel\Acumulus\Data\AcumulusObject;
use Siel\Acumulus\Data\Address;
use Siel\Acumulus\Helpers\MessageCollection;

/**
 * AddressCompletor completes an {@see \Siel\Acumulus\Data\Address}.
 *
 * After an invoice has been collected, the shop specific part, it needs to be
 * completed, also the customer part. Think of things like:
 * - Adding customer type based on a setting.
 * - Anonymising data.
 */
class AddressCompletor extends BaseCompletor
{
    private Address $address;

    /**
     * Completes an {@see \Siel\Acumulus\Data\Address}.
     *
     * This phase is executed after the collecting phase.
     *
     * @param \Siel\Acumulus\Data\Address $acumulusObject
     * @param \Siel\Acumulus\Helpers\MessageCollection $result
     */
    public function complete(AcumulusObject $acumulusObject, MessageCollection $result): void
    {
        $this->address = $acumulusObject;
        $this->getContainer()->getCompletorTask('Address', 'ByConfig')->complete($this->address);
    }
}
