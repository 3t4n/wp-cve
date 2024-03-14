<?php
/**
 * @license MIT
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\Composer\Installers;

/**
 * Composer installer for 3rd party Tusk utilities
 * @author Drew Ewing <drew@phenocode.com>
 */
class TuskInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'task'    => '.tusk/tasks/{$name}/',
        'command' => '.tusk/commands/{$name}/',
        'asset'   => 'assets/tusk/{$name}/',
    );
}
