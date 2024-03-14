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
use Payever\Sdk\Core\Apm\Events\Context\ContextRequestEntity;

/**
 * Class ContextEntity
 * @method ContextRequestEntity getRequest()
 */
class ContextEntity extends ApmRequestEntity
{
    /** @var ContextRequestEntity $request */
    protected $request;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['request'])) {
            $data['request'] = new ContextRequestEntity();
        }

        parent::__construct($data);
    }

    /**
     * @param ContextRequestEntity|array|string $request
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $this->getClassInstance(ContextRequestEntity::class, $request);

        return $this;
    }
}
