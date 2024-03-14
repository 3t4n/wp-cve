<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * IfwPsn_Vendor_Twig_BaseNodeVisitor can be used to make node visitors compatible with Twig 1.x and 2.x.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class IfwPsn_Vendor_Twig_BaseNodeVisitor implements IfwPsn_Vendor_Twig_NodeVisitorInterface
{
    final public function enterNode(IfwPsn_Vendor_Twig_NodeInterface $node, IfwPsn_Vendor_Twig_Environment $env)
    {
        if (!$node instanceof IfwPsn_Vendor_Twig_Node) {
            throw new LogicException('IfwPsn_Vendor_Twig_BaseNodeVisitor only supports IfwPsn_Vendor_Twig_Node instances.');
        }

        return $this->doEnterNode($node, $env);
    }

    final public function leaveNode(IfwPsn_Vendor_Twig_NodeInterface $node, IfwPsn_Vendor_Twig_Environment $env)
    {
        if (!$node instanceof IfwPsn_Vendor_Twig_Node) {
            throw new LogicException('IfwPsn_Vendor_Twig_BaseNodeVisitor only supports IfwPsn_Vendor_Twig_Node instances.');
        }

        return $this->doLeaveNode($node, $env);
    }

    /**
     * Called before child nodes are visited.
     *
     * @return IfwPsn_Vendor_Twig_Node The modified node
     */
    abstract protected function doEnterNode(IfwPsn_Vendor_Twig_Node $node, IfwPsn_Vendor_Twig_Environment $env);

    /**
     * Called after child nodes are visited.
     *
     * @return IfwPsn_Vendor_Twig_Node|false The modified node or false if the node must be removed
     */
    abstract protected function doLeaveNode(IfwPsn_Vendor_Twig_Node $node, IfwPsn_Vendor_Twig_Environment $env);
}

//class_alias('IfwPsn_Vendor_Twig_BaseNodeVisitor', 'Twig\NodeVisitor\AbstractNodeVisitor', false);
class_exists('IfwPsn_Vendor_Twig_Environment');
class_exists('IfwPsn_Vendor_Twig_Node');
