<?php
/**
 * @license MIT
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\Composer\Installers;

class LanManagementSystemInstaller extends BaseInstaller
{

    /** @var array<string, string> */
    protected $locations = array(
        'plugin' => 'plugins/{$name}/',
        'template' => 'templates/{$name}/',
        'document-template' => 'documents/templates/{$name}/',
        'userpanel-module' => 'userpanel/modules/{$name}/',
    );

    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars(array $vars): array
    {
        $vars['name'] = strtolower($this->pregReplace('/(?<=\\w)([A-Z])/', '_\\1', $vars['name']));
        $vars['name'] = str_replace(array('-', '_'), ' ', $vars['name']);
        $vars['name'] = str_replace(' ', '', ucwords($vars['name']));

        return $vars;
    }
}
