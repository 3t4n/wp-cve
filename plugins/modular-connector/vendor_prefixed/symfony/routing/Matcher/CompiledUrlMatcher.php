<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Routing\Matcher;

use Modular\ConnectorDependencies\Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherTrait;
use Modular\ConnectorDependencies\Symfony\Component\Routing\RequestContext;
/**
 * Matches URLs based on rules dumped by CompiledUrlMatcherDumper.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @internal
 */
class CompiledUrlMatcher extends UrlMatcher
{
    use CompiledUrlMatcherTrait;
    public function __construct(array $compiledRoutes, RequestContext $context)
    {
        $this->context = $context;
        [$this->matchHost, $this->staticRoutes, $this->regexpList, $this->dynamicRoutes, $this->checkCondition] = $compiledRoutes;
    }
}
