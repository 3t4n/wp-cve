<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\HttpKernel\Fragment;

use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Request;
use Modular\ConnectorDependencies\Symfony\Component\HttpKernel\Controller\ControllerReference;
/**
 * Interface implemented by rendering strategies able to generate an URL for a fragment.
 *
 * @author Kévin Dunglas <kevin@dunglas.fr>
 * @internal
 */
interface FragmentUriGeneratorInterface
{
    /**
     * Generates a fragment URI for a given controller.
     *
     * @param bool $absolute Whether to generate an absolute URL or not
     * @param bool $strict   Whether to allow non-scalar attributes or not
     * @param bool $sign     Whether to sign the URL or not
     */
    public function generate(ControllerReference $controller, ?Request $request = null, bool $absolute = \false, bool $strict = \true, bool $sign = \true) : string;
}
