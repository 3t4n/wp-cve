<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\Composer\Installers;

class WHMCSInstaller extends BaseInstaller
{
    protected $locations = array(
        'addons' => 'modules/addons/{$vendor}_{$name}/',
        'fraud' => 'modules/fraud/{$vendor}_{$name}/',
        'gateways' => 'modules/gateways/{$vendor}_{$name}/',
        'notifications' => 'modules/notifications/{$vendor}_{$name}/',
        'registrars' => 'modules/registrars/{$vendor}_{$name}/',
        'reports' => 'modules/reports/{$vendor}_{$name}/',
        'security' => 'modules/security/{$vendor}_{$name}/',
        'servers' => 'modules/servers/{$vendor}_{$name}/',
        'social' => 'modules/social/{$vendor}_{$name}/',
        'support' => 'modules/support/{$vendor}_{$name}/',
        'templates' => 'templates/{$vendor}_{$name}/',
        'includes' => 'includes/{$vendor}_{$name}/'
    );
}
