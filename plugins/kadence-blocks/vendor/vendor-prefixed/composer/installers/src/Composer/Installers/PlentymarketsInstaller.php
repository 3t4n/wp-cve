<?php
/**
 * @license MIT
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\Composer\Installers;

class PlentymarketsInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'plugin'   => '{$name}/'
    );

    /**
     * Remove hyphen, "plugin" and format to camelcase
     */
    public function inflectPackageVars(array $vars): array
    {
        $nameBits = explode("-", $vars['name']);
        foreach ($nameBits as $key => $name) {
            $nameBits[$key] = ucfirst($name);
            if (strcasecmp($name, "Plugin") == 0) {
                unset($nameBits[$key]);
            }
        }
        $vars['name'] = implode('', $nameBits);

        return $vars;
    }
}
