<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  Command
 * @package   Payever\Plugins
 * @author    payever GmbH <service@payever.de>
 * @author    Hennadii.Shymanskyi <gendosua@gmail.com>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Plugins\Command;

use Payever\Sdk\Plugins\Http\MessageEntity\PluginCommandEntity;

interface PluginCommandExecutorInterface
{
    /**
     * @param PluginCommandEntity $command
     *
     * @return bool
     *
     * @throws \Exception when command could not be executed at the moment
     */
    public function executeCommand(PluginCommandEntity $command);
}
