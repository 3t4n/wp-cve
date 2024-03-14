<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  ResponseEntity
 * @package   Payever\Plugins
 * @author    payever GmbH <service@payever.de>
 * @author    Hennadii.Shymanskyi <gendosua@gmail.com>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Plugins\Http\ResponseEntity;

use Payever\Sdk\Core\Http\ResponseEntity;

/**
 * @method string getId()
 * @method string getPluginVersion()
 * @method string getCmsVersion()
 * @method string getChannel()
 * @method string getHost()
 * @method string[] getSupportedCommands()
 * @method string|null getCommandEndpoint()
 * @method string[] getBusinessIds()
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PluginRegistryResponseEntity extends ResponseEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $pluginVersion;

    /** @var string */
    protected $cmsVersion;

    /**
     * @var string
     * @see ChannelSet
     */
    protected $channel;

    /** @var string */
    protected $host;

    /**
     * @var array
     * @see PluginCommandNameEnum
     */
    protected $supportedCommands;

    /** @var string */
    protected $commandEndpoint;

    /** @var string[] */
    protected $businessIds;

    /**
     * @return array
     */
    public function getRequired()
    {
        return [
            'pluginVersion',
            'cmsVersion',
            'host',
            'channel',
        ];
    }
}
