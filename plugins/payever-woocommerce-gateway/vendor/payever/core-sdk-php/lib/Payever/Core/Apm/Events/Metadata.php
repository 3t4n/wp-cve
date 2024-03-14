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

namespace Payever\Sdk\Core\Apm\Events;

use Payever\Sdk\Core\Http\ApmRequestEntity;
use Payever\Sdk\Core\Apm\Events\Metadata\ServiceEntity;
use Payever\Sdk\Core\Apm\Events\Metadata\SystemEntity;

/**
 * Class Metadata
 * @method ServiceEntity getService()
 * @method SystemEntity  getSystem()
 */
class Metadata extends ApmRequestEntity
{
    /** @var ServiceEntity $service */
    protected $service;

    /** @var SystemEntity $system */
    protected $system;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['service'])) {
            $data['service'] = new ServiceEntity();
        }
        if (!isset($data['system'])) {
            $data['system'] = new SystemEntity();
        }

        parent::__construct($data);
    }

    /**
     * Sets service entity
     *
     * @param ServiceEntity|array|string $service
     *
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $this->getClassInstance(ServiceEntity::class, $service);

        return $this;
    }

    /**
     * Sets system entity
     *
     * @param SystemEntity|array|string $system
     *
     * @return $this
     */
    public function setSystem($system)
    {
        $this->system = $this->getClassInstance(SystemEntity::class, $system);

        return $this;
    }
}
