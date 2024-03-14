<?php

declare(strict_types=1);

namespace ADEV_EmailValidation\Validations;

defined('ABSPATH') or die('Nope nope nope...');

class ValidFormatValidator extends Validator implements ValidatorInterface
{
    /**
     * @return string
     */
    public function getValidatorName()
    {
        return 'valid_format'; // @codeCoverageIgnore
    }

    /**
     * @return bool
     */
    public function getResultResponse()
    {
        return $this->getEmailAddress()->isValidEmailAddressFormat();
    }
}
