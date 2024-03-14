<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  Apm Agent
 * @package   Payever\Core
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Core\Apm\Events\Context\Request;

use Payever\Sdk\Core\Http\ApmRequestEntity;

/**
 * Class HeadersEntity
 * @method string getCookie()
 * @method self setCookie(string $cookie)
 */
class HeadersEntity extends ApmRequestEntity
{
    /** @var string $cookie */
    protected $cookie = '';

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['cookie']) && function_exists('getallheaders')) {
            $headers = getallheaders();
            $data['cookie'] = isset($headers['Cookie']) ? $headers['Cookie'] : '';
        }

        parent::__construct($data);
    }
}
