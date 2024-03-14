<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dotdigital_WordPress_Vendor\Carbon\Doctrine;

use Dotdigital_WordPress_Vendor\Doctrine\DBAL\Platforms\AbstractPlatform;
interface CarbonDoctrineType
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform);
    public function convertToPHPValue($value, AbstractPlatform $platform);
    public function convertToDatabaseValue($value, AbstractPlatform $platform);
}
