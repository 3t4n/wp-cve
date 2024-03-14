<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
namespace CoffeeCode\Composer\Installers;

class Redaxo5Installer extends BaseInstaller
{
    protected $locations = array(
        'addon'          => 'redaxo/src/addons/{$name}/',
        'bestyle-plugin' => 'redaxo/src/addons/be_style/plugins/{$name}/'
    );
}
