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
 * IfwPsn_Vendor_Twig_NodeVisitor_Sandbox implements sandboxing.
 *
 * @final
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IfwPsn_Vendor_Twig_NodeVisitor_Sandbox extends IfwPsn_Vendor_Twig_BaseNodeVisitor
{
    protected $inAModule = false;
    protected $tags;
    protected $filters;
    protected $functions;

    protected function doEnterNode(IfwPsn_Vendor_Twig_Node $node, IfwPsn_Vendor_Twig_Environment $env)
    {
        if ($node instanceof IfwPsn_Vendor_Twig_Node_Module) {
            $this->inAModule = true;
            $this->tags = [];
            $this->filters = [];
            $this->functions = [];

            return $node;
        } elseif ($this->inAModule) {
            // look for tags
            if ($node->getNodeTag() && !isset($this->tags[$node->getNodeTag()])) {
                $this->tags[$node->getNodeTag()] = $node;
            }

            // look for filters
            if ($node instanceof IfwPsn_Vendor_Twig_Node_Expression_Filter && !isset($this->filters[$node->getNode('filter')->getAttribute('value')])) {
                $this->filters[$node->getNode('filter')->getAttribute('value')] = $node;
            }

            // look for functions
            if ($node instanceof IfwPsn_Vendor_Twig_Node_Expression_Function && !isset($this->functions[$node->getAttribute('name')])) {
                $this->functions[$node->getAttribute('name')] = $node;
            }

            // the .. operator is equivalent to the range() function
            if ($node instanceof IfwPsn_Vendor_Twig_Node_Expression_Binary_Range && !isset($this->functions['range'])) {
                $this->functions['range'] = $node;
            }

            // wrap print to check __toString() calls
            if ($node instanceof IfwPsn_Vendor_Twig_Node_Print) {
                return new IfwPsn_Vendor_Twig_Node_SandboxedPrint($node->getNode('expr'), $node->getTemplateLine(), $node->getNodeTag());
            }
        }

        return $node;
    }

    protected function doLeaveNode(IfwPsn_Vendor_Twig_Node $node, IfwPsn_Vendor_Twig_Environment $env)
    {
        if ($node instanceof IfwPsn_Vendor_Twig_Node_Module) {
            $this->inAModule = false;

            $node->setNode('display_start', new IfwPsn_Vendor_Twig_Node([new IfwPsn_Vendor_Twig_Node_CheckSecurity($this->filters, $this->tags, $this->functions), $node->getNode('display_start')]));
        }

        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}

//class_alias('IfwPsn_Vendor_Twig_NodeVisitor_Sandbox', 'Twig\NodeVisitor\SandboxNodeVisitor', false);
