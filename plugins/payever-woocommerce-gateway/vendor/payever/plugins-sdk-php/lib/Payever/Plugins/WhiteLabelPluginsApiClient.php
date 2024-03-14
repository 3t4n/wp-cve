<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  Plugins
 * @package   Payever\Plugins
 * @author    payever GmbH <service@payever.de>
 * @author    Igor.Siaryi <igor.siary@gmail.com>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Plugins;

use Payever\Sdk\Core\Base\HttpClientInterface;
use Payever\Sdk\Core\Http\Client\CurlClient;
use Payever\Sdk\Core\Http\RequestBuilder;
use Payever\Sdk\Plugins\Base\WhiteLabelPluginsApiClientInterface;
use Payever\Sdk\Plugins\Http\ResponseEntity\WhiteLabelPluginResponseEntity;
use Psr\Log\NullLogger;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WhiteLabelPluginsApiClient implements WhiteLabelPluginsApiClientInterface
{
    /**
     * @var HttpClientInterface|null
     */
    private $httpClient;

    const URL_LIVE = 'https://plugins-third-party.payever.org/';
    const SUB_URL_WL_PLUGIN = 'api/wl/plugin/%s/shopsystem/%s';

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function getWhiteLabelPlugin($code, $shopsystem)
    {
        $request = RequestBuilder::get($this->buildWhiteLabelPluginUrl($code, $shopsystem))
            ->setResponseEntity(new WhiteLabelPluginResponseEntity())
            ->build();

        return $this->getHttpClient()->execute($request);
    }

    /**
     * @return CurlClient
     */
    private function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new CurlClient();
        }

        $this->httpClient->setLogger(new NullLogger());

        return $this->httpClient;
    }

    /**
     * @param string $code
     * @param string $shopsystem
     *
     * @return string
     */
    private function buildWhiteLabelPluginUrl($code, $shopsystem)
    {
        return sprintf(
            '%s%s',
            static::URL_LIVE,
            sprintf(static::SUB_URL_WL_PLUGIN, $code, $shopsystem)
        );
    }
}
