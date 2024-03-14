<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
namespace CoffeeCode\Composer\Installers;

class JoomlaInstaller extends BaseInstaller
{
    protected $locations = array(
        'component'    => 'components/{$name}/',
        'module'       => 'modules/{$name}/',
        'template'     => 'templates/{$name}/',
        'plugin'       => 'plugins/{$name}/',
        'library'      => 'libraries/{$name}/',
    );

    // TODO: Add inflector for mod_ and com_ names
}
