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

namespace Payever\Sdk\Core\Apm\Events\Metadata\Service;

use Payever\Sdk\Core\Enum\ApmAgent;
use Payever\Sdk\Core\Http\ApmRequestEntity;

/**
 * Class AgentEntity
 * @method string getName()
 * @method string getVersion()
 * @method self   setName(string $name)
 * @method self   setVersion(string $version)
 */
class AgentEntity extends ApmRequestEntity
{
    /** @var string $name */
    protected $name = ApmAgent::NAME;

    /** @var string $version */
    protected $version = ApmAgent::VERSION;
}
