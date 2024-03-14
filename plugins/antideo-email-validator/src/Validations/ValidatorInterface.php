<?php

declare(strict_types=1);

namespace ADEV_EmailValidation\Validations;

defined('ABSPATH') or die('Nope nope nope...');

interface ValidatorInterface
{
    /**
     * @return mixed
     */
    public function getResultResponse();

    /**
     * @return string
     */
    public function getValidatorName();
}
