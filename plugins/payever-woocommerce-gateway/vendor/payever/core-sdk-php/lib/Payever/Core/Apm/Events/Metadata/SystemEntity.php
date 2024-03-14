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

namespace Payever\Sdk\Core\Apm\Events\Metadata;

use Payever\Sdk\Core\Http\ApmRequestEntity;

/**
 * Class SystemEntity
 * @method string getName()
 * @method string getArchitecture()
 * @method string getPlatform()
 * @method self   setHostname(string $hostname)
 * @method self   setArchitecture(string $architecture)
 * @method self   setPlatform(string $platform)
 */
class SystemEntity extends ApmRequestEntity
{
    /** @var string $hostname */
    protected $hostname;

    /** @var string $architecture */
    protected $architecture;

    /** @var string $platform */
    protected $platform;

    /**
     * @param array|null $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['architecture'])) {
            $data['architecture'] = php_uname('m');
        }

        if (!isset($data['platform'])) {
            $data['platform'] = php_uname('s');
        }

        parent::__construct($data);
    }
}
