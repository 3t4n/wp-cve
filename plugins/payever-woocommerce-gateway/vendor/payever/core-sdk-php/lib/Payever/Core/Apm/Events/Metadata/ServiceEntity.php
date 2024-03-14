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
use Payever\Sdk\Core\Apm\Events\Metadata\Service\AgentEntity;

/**
 * Class ServiceEntity
 * @method string      getName()
 * @method string      getVersion()
 * @method AgentEntity getAgent()
 * @method string      getEnvironment()
 * @method self        setName(string $name)
 * @method self        setVersion(string $version)
 * @method self        setEnvironment(string $version)
 */
class ServiceEntity extends ApmRequestEntity
{
    /** @var string $name */
    protected $name;

    /** @var string $version */
    protected $version;

    /** @var AgentEntity $agent */
    protected $agent;

    /** @var string $environment */
    protected $environment;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['agent'])) {
            $data['agent'] = new AgentEntity();
        }

        parent::__construct($data);
    }

    /**
     * Sets agent
     *
     * @param AgentEntity|string|array $agent
     *
     * @return $this
     */
    public function setAgent($agent)
    {
        $this->agent = $this->getClassInstance(AgentEntity::class, $agent);

        return $this;
    }
}
