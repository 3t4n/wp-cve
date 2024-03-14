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

use Payever\Sdk\Core\Base\MessageEntity;

/**
 * This class represents Options entity
 *
 * @method string getAllowSeparateShippingAddress()
 * @method array getAllowCustomerTypes()
 * @method array getAllowPaymentMethods()
 * @method bool getAllowCartStep()
 * @method bool getAllowBillingStep()
 * @method bool getAllowShippingStep()
 * @method bool getUseDefaultVariant()
 * @method bool getUseInventory()
 * @method bool getUseStyles()
 * @method bool getUseIframe()
 * @method bool getSalutationMandatory()
 * @method bool getPhoneMandatory()
 * @method bool getBirthdateMandatory()
 * @method bool getTestMode()
 * @method self setAllowSeparateShippingAddress(string $value)
 * @method self setAllowCustomerTypes(array $value)
 * @method self setAllowPaymentMethods(array $value)
 * @method self setAllowCartStep(bool $value)
 * @method self setAllowBillingStep(bool $value)
 * @method self setAllowShippingStep(bool $value)
 * @method self setUseDefaultVariant(bool $value)
 * @method self setUseInventory(bool $value)
 * @method self setUseStyles(bool $value)
 * @method self setUseIframe(bool $value)
 * @method self setSalutationMandatory(bool $value)
 * @method self setPhoneMandatory(bool $value)
 * @method self setBirthdateMandatory(bool $value)
 * @method self setTestMode(bool $value)
 */
class OptionsEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $allowSeparateShippingAddress;

    /**
     * @var array
     */
    protected $allowCustomerTypes;

    /**
     * @var array
     */
    protected $allowPaymentMethods;

    /**
     * @var bool
     */
    protected $allowCartStep;

    /**
     * @var bool
     */
    protected $allowBillingStep;

    /**
     * @var bool
     */
    protected $allowShippingStep;

    /**
     * @var bool
     */
    protected $useDefaultVariant;

    /**
     * @var bool
     */
    protected $useInventory;

    /**
     * @var bool
     */
    protected $useStyles;

    /**
     * @var bool
     */
    protected $useIframe;

    /**
     * @var bool
     */
    protected $salutationMandatory;

    /**
     * @var bool
     */
    protected $phoneMandatory;

    /**
     * @var bool
     */
    protected $birthdateMandatory;

    /**
     * @var bool
     */
    protected $testMode;
}
