<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2021 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity\CompanySearch;

use Payever\Sdk\Core\Http\RequestEntity;

/**
 * This class represents Address Entity
 *
 * @method string getCountry()
 * @method self   setCountry(string $country)
 */
class AddressEntity extends RequestEntity
{
    /**
     * @var string
     */
    protected $country;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'country'
        ];
    }
}
