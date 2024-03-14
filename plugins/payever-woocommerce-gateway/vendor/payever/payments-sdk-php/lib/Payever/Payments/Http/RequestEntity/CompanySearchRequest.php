<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity;

use Payever\Sdk\Core\Http\RequestEntity;
use Payever\Sdk\Payments\Http\RequestEntity\CompanySearch\CompanyEntity;
use Payever\Sdk\Payments\Http\RequestEntity\CompanySearch\AddressEntity;

/**
 * This class represents Company Search Entity
 *
 * @method CompanyEntity getCompany()
 * @method AddressEntity getAddress()
 */
class CompanySearchRequest extends RequestEntity
{
    /**
     * @var CompanyEntity
     */
    protected $company;

    /**
     * @var AddressEntity
     */
    protected $address;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'company'
        ];
    }

    /**
     * Set Company.
     *
     * @param CompanyEntity|array|string $company
     *
     * @return $this
     */
    public function setCompany($company)
    {
        if (!$company) {
            return $this;
        }

        if (is_string($company)) {
            $company = json_decode($company);
        }

        if (!is_array($company) && !is_object($company)) {
            return $this;
        }

        $this->company = new CompanyEntity($company);

        return $this;
    }

    /**
     * Set Address.
     *
     * @param AddressEntity|array|string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        if (!$address) {
            return $this;
        }

        if (is_string($address)) {
            $company = json_decode($address);
        }

        if (!is_array($address) && !is_object($address)) {
            return $this;
        }

        $this->address = new AddressEntity($address);

        return $this;
    }
}
