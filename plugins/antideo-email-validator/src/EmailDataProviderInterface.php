<?php

namespace ADEV_EmailValidation;

defined('ABSPATH') or die('Nope nope nope...');

interface EmailDataProviderInterface
{
    /**
     * @return array
     */
    public function getEmailProviders();

    /**
     * @return array
     */
    public function getTopLevelDomains();

    /**
     * @return array
     */
    public function getDisposableEmailProviders();

    /**
     * @return array
     */
    public function getRoleEmailPrefixes();
}
