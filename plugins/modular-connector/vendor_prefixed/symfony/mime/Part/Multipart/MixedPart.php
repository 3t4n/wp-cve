<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Mime\Part\Multipart;

use Modular\ConnectorDependencies\Symfony\Component\Mime\Part\AbstractMultipartPart;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
final class MixedPart extends AbstractMultipartPart
{
    public function getMediaSubtype() : string
    {
        return 'mixed';
    }
}
