<?php
/**
 * @license MIT
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\Composer\Installers;

class StarbugInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'module' => 'modules/{$name}/',
        'theme' => 'themes/{$name}/',
        'custom-module' => 'app/modules/{$name}/',
        'custom-theme' => 'app/themes/{$name}/'
    );
}
