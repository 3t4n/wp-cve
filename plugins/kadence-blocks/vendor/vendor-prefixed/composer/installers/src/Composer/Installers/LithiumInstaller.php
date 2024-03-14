<?php
/**
 * @license MIT
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\Composer\Installers;

class LithiumInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'library' => 'libraries/{$name}/',
        'source'  => 'libraries/_source/{$name}/',
    );
}
