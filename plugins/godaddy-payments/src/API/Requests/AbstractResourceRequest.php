<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Requests;

defined('ABSPATH') or exit;
/**
 * The base request for all Resource-based API requests. This class can be
 * extended by concrete implementations of resource-based API requests that
 * require an id, e.g. PUT, as well as those that don't, e.g. POST.
 */
abstract class AbstractResourceRequest extends AbstractBusinessRequest
{
    /** @var string the resource ID */
    protected $resourceId;

    /** @var string the plural name of the resource, e.g. 'orders' */
    protected $resourcePlural;

    /**
     * AbstractResourceRequest constructor.
     *
     * @param string $businessId the configured business ID
     * @param string the plural name of the resource, e.g. 'orders'
     * @param string|null $resourceId
     * @param string the resource action, e.g. 'cancel,complete..'
     *
     * @throws Exception
     */
    public function __construct(string $businessId, string $resourcePlural, string $resourceId = null, string $resourceAction = '')
    {
        $this->resourcePlural = $resourcePlural;
        $this->resourceId = $resourceId;
        $this->resourceAction = $resourceAction;

        parent::__construct($businessId);
        $this->setPath();
    }

    /**
     * Sets the route.
     * @since 1.3.0
     */
    protected function setPath()
    {
        $this->path = sprintf('%s/%s/%s%s', $this->path, $this->resourcePlural, ! empty($this->resourceId) ? $this->resourceId.'/' : '', $this->resourceAction);
    }
}
