<?php
/**
 * @license MIT
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\Composer\Installers;

class MagentoInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'theme'   => 'app/design/frontend/{$name}/',
        'skin'    => 'skin/frontend/default/{$name}/',
        'library' => 'lib/{$name}/',
    );
}
