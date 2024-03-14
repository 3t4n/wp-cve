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
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class IfwPsn_Vendor_Twig_Profiler_NodeVisitor_Profiler extends IfwPsn_Vendor_Twig_BaseNodeVisitor
{
    private $extensionName;

    public function __construct($extensionName)
    {
        $this->extensionName = $extensionName;
    }

    protected function doEnterNode(IfwPsn_Vendor_Twig_Node $node, IfwPsn_Vendor_Twig_Environment $env)
    {
        return $node;
    }

    protected function doLeaveNode(IfwPsn_Vendor_Twig_Node $node, IfwPsn_Vendor_Twig_Environment $env)
    {
        if ($node instanceof IfwPsn_Vendor_Twig_Node_Module) {
            $varName = $this->getVarName();
            $node->setNode('display_start', new IfwPsn_Vendor_Twig_Node([new IfwPsn_Vendor_Twig_Profiler_Node_EnterProfile($this->extensionName, IfwPsn_Vendor_Twig_Profiler_Profile::TEMPLATE, $node->getTemplateName(), $varName), $node->getNode('display_start')]));
            $node->setNode('display_end', new IfwPsn_Vendor_Twig_Node([new IfwPsn_Vendor_Twig_Profiler_Node_LeaveProfile($varName), $node->getNode('display_end')]));
        } elseif ($node instanceof IfwPsn_Vendor_Twig_Node_Block) {
            $varName = $this->getVarName();
            $node->setNode('body', new IfwPsn_Vendor_Twig_Node_Body([
                new IfwPsn_Vendor_Twig_Profiler_Node_EnterProfile($this->extensionName, IfwPsn_Vendor_Twig_Profiler_Profile::BLOCK, $node->getAttribute('name'), $varName),
                $node->getNode('body'),
                new IfwPsn_Vendor_Twig_Profiler_Node_LeaveProfile($varName),
            ]));
        } elseif ($node instanceof IfwPsn_Vendor_Twig_Node_Macro) {
            $varName = $this->getVarName();
            $node->setNode('body', new IfwPsn_Vendor_Twig_Node_Body([
                new IfwPsn_Vendor_Twig_Profiler_Node_EnterProfile($this->extensionName, IfwPsn_Vendor_Twig_Profiler_Profile::MACRO, $node->getAttribute('name'), $varName),
                $node->getNode('body'),
                new IfwPsn_Vendor_Twig_Profiler_Node_LeaveProfile($varName),
            ]));
        }

        return $node;
    }

    private function getVarName()
    {
        return sprintf('__internal_%s', hash('sha256', $this->extensionName));
    }

    public function getPriority()
    {
        return 0;
    }
}

//class_alias('IfwPsn_Vendor_Twig_Profiler_NodeVisitor_Profiler', 'Twig\Profiler\NodeVisitor\ProfilerNodeVisitor', false);
