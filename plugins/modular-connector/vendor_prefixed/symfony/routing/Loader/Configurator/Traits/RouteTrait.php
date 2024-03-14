<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Routing\Loader\Configurator\Traits;

use Modular\ConnectorDependencies\Symfony\Component\Routing\Route;
use Modular\ConnectorDependencies\Symfony\Component\Routing\RouteCollection;
/** @internal */
trait RouteTrait
{
    /**
     * @var RouteCollection|Route
     */
    protected $route;
    /**
     * Adds defaults.
     *
     * @return $this
     */
    public final function defaults(array $defaults) : self
    {
        $this->route->addDefaults($defaults);
        return $this;
    }
    /**
     * Adds requirements.
     *
     * @return $this
     */
    public final function requirements(array $requirements) : self
    {
        $this->route->addRequirements($requirements);
        return $this;
    }
    /**
     * Adds options.
     *
     * @return $this
     */
    public final function options(array $options) : self
    {
        $this->route->addOptions($options);
        return $this;
    }
    /**
     * Whether paths should accept utf8 encoding.
     *
     * @return $this
     */
    public final function utf8(bool $utf8 = \true) : self
    {
        $this->route->addOptions(['utf8' => $utf8]);
        return $this;
    }
    /**
     * Sets the condition.
     *
     * @return $this
     */
    public final function condition(string $condition) : self
    {
        $this->route->setCondition($condition);
        return $this;
    }
    /**
     * Sets the pattern for the host.
     *
     * @return $this
     */
    public final function host(string $pattern) : self
    {
        $this->route->setHost($pattern);
        return $this;
    }
    /**
     * Sets the schemes (e.g. 'https') this route is restricted to.
     * So an empty array means that any scheme is allowed.
     *
     * @param string[] $schemes
     *
     * @return $this
     */
    public final function schemes(array $schemes) : self
    {
        $this->route->setSchemes($schemes);
        return $this;
    }
    /**
     * Sets the HTTP methods (e.g. 'POST') this route is restricted to.
     * So an empty array means that any method is allowed.
     *
     * @param string[] $methods
     *
     * @return $this
     */
    public final function methods(array $methods) : self
    {
        $this->route->setMethods($methods);
        return $this;
    }
    /**
     * Adds the "_controller" entry to defaults.
     *
     * @param callable|string|array $controller a callable or parseable pseudo-callable
     *
     * @return $this
     */
    public final function controller($controller) : self
    {
        $this->route->addDefaults(['_controller' => $controller]);
        return $this;
    }
    /**
     * Adds the "_locale" entry to defaults.
     *
     * @return $this
     */
    public final function locale(string $locale) : self
    {
        $this->route->addDefaults(['_locale' => $locale]);
        return $this;
    }
    /**
     * Adds the "_format" entry to defaults.
     *
     * @return $this
     */
    public final function format(string $format) : self
    {
        $this->route->addDefaults(['_format' => $format]);
        return $this;
    }
    /**
     * Adds the "_stateless" entry to defaults.
     *
     * @return $this
     */
    public final function stateless(bool $stateless = \true) : self
    {
        $this->route->addDefaults(['_stateless' => $stateless]);
        return $this;
    }
}
