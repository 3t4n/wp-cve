<?php
/**
 * @license MIT
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\Composer\Installers;

/**
 * An installer to handle MODX specifics when installing packages.
 */
class ModxInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'extra' => 'core/packages/{$name}/'
    );
}
