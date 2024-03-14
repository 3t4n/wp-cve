<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  MessageEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Http\MessageEntity\ResultEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CompanyAddressEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CompanyIdentifierEntity;

/**
 * This class represents CompanySearch Entity
 *
 * @method string                    getId()
 * @method string                    getName()
 * @method string                    getPhoneNumber()
 * @method string                    getLegalFormCode()
 * @method CompanyAddressEntity      getAddress()
 * @method CompanyIdentifierEntity[] getCompanyIdentifiers()
 * @method self                      setId(string $value)
 * @method self                      setName(string $value)
 * @method self                      setPhoneNumber(string $value)
 * @method self                      setLegalFormCode(string $value)
 */
class CompanySearchResultEntity extends ResultEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var string
     */
    protected $legalFormCode;

    /**
     * @var string
     */
    protected $companyStatus;

    /**
     * @var CompanyAddressEntity
     */
    protected $address;

    /**
     * @var CompanyIdentifierEntity[]
     */
    protected $companyIdentifiers;

    /**
     * @var string
     */
    protected $vatId;

    /**
     * Set Address.
     *
     * @param array $address
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = new CompanyAddressEntity($address);

        return $this;
    }

    /**
     * Set Company Identifiers.
     *
     * @param $identifiers
     * @return $this
     */
    public function setCompanyIdentifiers($identifiers)
    {
        $this->companyIdentifiers = [];

        if (is_array($identifiers)) {
            foreach ($identifiers as $identifier) {
                $result = new CompanyIdentifierEntity();
                $result->setIdValue($identifier->idValue)
                    ->setIdTypeCode($identifier->idTypeCode)
                    ->setIsPrincipal($identifier->isPrincipal);

                $this->companyIdentifiers[] = $result;
            }
        }

        return $this;
    }
}
