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
 * IfwPsn_Vendor_Twig_NodeTraverser is a node traverser.
 *
 * It visits all nodes and their children and calls the given visitor for each.
 *
 * @final
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IfwPsn_Vendor_Twig_NodeTraverser
{
    protected $env;
    protected $visitors = [];

    /**
     * @param IfwPsn_Vendor_Twig_Environment            $env
     * @param IfwPsn_Vendor_Twig_NodeVisitorInterface[] $visitors
     */
    public function __construct(IfwPsn_Vendor_Twig_Environment $env, array $visitors = [])
    {
        $this->env = $env;
        foreach ($visitors as $visitor) {
            $this->addVisitor($visitor);
        }
    }

    public function addVisitor(IfwPsn_Vendor_Twig_NodeVisitorInterface $visitor)
    {
        if (!isset($this->visitors[$visitor->getPriority()])) {
            $this->visitors[$visitor->getPriority()] = [];
        }

        $this->visitors[$visitor->getPriority()][] = $visitor;
    }

    /**
     * Traverses a node and calls the registered visitors.
     *
     * @return IfwPsn_Vendor_Twig_NodeInterface
     */
    public function traverse(IfwPsn_Vendor_Twig_NodeInterface $node)
    {
        ksort($this->visitors);
        foreach ($this->visitors as $visitors) {
            foreach ($visitors as $visitor) {
                $node = $this->traverseForVisitor($visitor, $node);
            }
        }

        return $node;
    }

    protected function traverseForVisitor(IfwPsn_Vendor_Twig_NodeVisitorInterface $visitor, IfwPsn_Vendor_Twig_NodeInterface $node = null)
    {
        if (null === $node) {
            return;
        }

        $node = $visitor->enterNode($node, $this->env);

        foreach ($node as $k => $n) {
            if (false !== $m = $this->traverseForVisitor($visitor, $n)) {
                if ($m !== $n) {
                    $node->setNode($k, $m);
                }
            } else {
                $node->removeNode($k);
            }
        }

        return $visitor->leaveNode($node, $this->env);
    }
}

//class_alias('IfwPsn_Vendor_Twig_NodeTraverser', 'Twig\NodeTraverser', false);
