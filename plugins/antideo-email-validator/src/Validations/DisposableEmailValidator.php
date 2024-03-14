<?php
declare(strict_types=1);

namespace ADEV_EmailValidation\Validations;

defined('ABSPATH') or die('Nope nope nope...');

class DisposableEmailValidator extends Validator implements ValidatorInterface
{
    /**
     * @return string
     */
    public function getValidatorName()
    {
        return 'disposable_email_provider'; // @codeCoverageIgnore
    }

    /**
     * @return bool
     */
    public function getResultResponse()
    {
        return in_array(
            $this->getEmailAddress()->getHostPart(),
            $this->getEmailDataProvider()->getDisposableEmailProviders()
        );
    }
}
