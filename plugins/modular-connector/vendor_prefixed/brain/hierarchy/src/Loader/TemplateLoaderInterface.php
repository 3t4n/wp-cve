<?php

/*
 * This file is part of the Hierarchy package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Brain\Hierarchy\Loader;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @internal
 */
interface TemplateLoaderInterface
{
    /**
     * Load a template and return the rendered content.
     *
     * @param string $templatePath
     *
     * @return string
     */
    public function load($templatePath);
}
