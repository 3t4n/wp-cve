<?php

declare (strict_types=1);
namespace LassoLiteVendor\Bamarni\Composer\Bin;

use LassoLiteVendor\Bamarni\Composer\Bin\Command\BinCommand;
use LassoLiteVendor\Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
/**
 * @final Will be final in 2.x.
 */
class CommandProvider implements CommandProviderCapability
{
    public function getCommands() : array
    {
        return [new BinCommand()];
    }
}
