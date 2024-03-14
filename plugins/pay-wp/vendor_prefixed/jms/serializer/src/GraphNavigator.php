<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer;

use WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface;
/**
 * Handles traversal along the object graph.
 *
 * This class handles traversal along the graph, and calls different methods
 * on visitors, or custom handlers to process its nodes.
 *
 * @internal
 *
 * @author Asmir Mustafic <goetas@gmail.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class GraphNavigator implements \WPPayVendor\JMS\Serializer\GraphNavigatorInterface
{
    /**
     * @var VisitorInterface
     */
    protected $visitor;
    /**
     * @var Context
     */
    protected $context;
    /***
     * @var string
     */
    protected $format;
    /**
     * @var ExclusionStrategyInterface
     */
    protected $exclusionStrategy;
    public function initialize(\WPPayVendor\JMS\Serializer\VisitorInterface $visitor, \WPPayVendor\JMS\Serializer\Context $context) : void
    {
        $this->visitor = $visitor;
        $this->context = $context;
        // cache value
        $this->format = $context->getFormat();
        $this->exclusionStrategy = $context->getExclusionStrategy();
    }
}
