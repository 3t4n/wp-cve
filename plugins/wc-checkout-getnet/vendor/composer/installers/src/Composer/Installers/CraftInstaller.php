<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
namespace CoffeeCode\Composer\Installers;

/**
 * Installer for Craft Plugins
 */
class CraftInstaller extends BaseInstaller
{
    const NAME_PREFIX = 'craft';
    const NAME_SUFFIX = 'plugin';

    protected $locations = array(
        'plugin' => 'craft/plugins/{$name}/',
    );

    /**
     * Strip `craft-` prefix and/or `-plugin` suffix from package names
     *
     * @param  array $vars
     *
     * @return array
     */
    final public function inflectPackageVars($vars)
    {
        return $this->inflectPluginVars($vars);
    }

    private function inflectPluginVars($vars)
    {
        $vars['name'] = preg_replace('/-' . self::NAME_SUFFIX . '$/i', '', $vars['name']);
        $vars['name'] = preg_replace('/^' . self::NAME_PREFIX . '-/i', '', $vars['name']);

        return $vars;
    }
}
