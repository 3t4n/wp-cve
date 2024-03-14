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

namespace Payever\Sdk\Plugins\Base;

use Payever\Sdk\Core\Http\Response;

interface WhiteLabelPluginsApiClientInterface
{
    /**
     * @param string $code
     * @param string $shopsystem
     * @return Response
     */
    public function getWhiteLabelPlugin($code, $shopsystem);
}
