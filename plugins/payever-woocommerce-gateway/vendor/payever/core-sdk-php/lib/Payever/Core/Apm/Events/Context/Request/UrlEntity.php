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
 * Class UrlEntity
 * @method string getProtocol()
 * @method string getHostname()
 * @method string getPort()
 * @method string getPathname()
 * @method string getSearch()
 * @method string getFull()
 * @method self setProtocol(string $protocol)
 * @method self setHostname(string $hostname)
 * @method self setPort(string $port)
 * @method self setPathname(string $pathname)
 * @method self setSearch(string $search)
 * @method self setFull(string $full)
 */
class UrlEntity extends ApmRequestEntity
{
    /** @var string $protocol */
    protected $protocol;

    /** @var string $hostname */
    protected $hostname;

    /** @var string $port */
    protected $port;

    /** @var string $pathname */
    protected $pathname;

    /** @var string $search */
    protected $search;

    /** @var string $full */
    protected $full;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['protocol'])) {
            $data['protocol'] = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        }

        if (!isset($data['hostname'])) {
            $data['hostname'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
        }

        if (!isset($data['port'])) {
            $data['port'] = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 0;
        }

        if (!isset($data['pathname'])) {
            $data['pathname'] = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        }

        if (!isset($data['search'])) {
            $data['search'] = '?' . (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
        }

        parent::__construct($data);

        if (!isset($data['full'])) {
            $this->setFull($this->getFullRequestUri());
        }
    }

    /**
     * @return string
     */
    private function getFullRequestUri()
    {
        return $this->getProtocol() . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') .
            (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
    }
}
