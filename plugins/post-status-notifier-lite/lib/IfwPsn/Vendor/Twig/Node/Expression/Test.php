<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IfwPsn_Vendor_Twig_Node_Expression_Test extends IfwPsn_Vendor_Twig_Node_Expression_Call
{
    public function __construct(IfwPsn_Vendor_Twig_NodeInterface $node, $name, IfwPsn_Vendor_Twig_NodeInterface $arguments = null, $lineno)
    {
        $nodes = ['node' => $node];
        if (null !== $arguments) {
            $nodes['arguments'] = $arguments;
        }

        parent::__construct($nodes, ['name' => $name], $lineno);
    }

    public function compile(IfwPsn_Vendor_Twig_Compiler $compiler)
    {
        $name = $this->getAttribute('name');
        $test = $compiler->getEnvironment()->getTest($name);

        $this->setAttribute('name', $name);
        $this->setAttribute('type', 'test');
        $this->setAttribute('thing', $test);
        if ($test instanceof IfwPsn_Vendor_Twig_SimpleTest) {
            $this->setAttribute('arguments', $test->getArguments());
        }
        if ($test instanceof IfwPsn_Vendor_Twig_TestCallableInterface || $test instanceof IfwPsn_Vendor_Twig_SimpleTest) {
            $this->setAttribute('callable', $test->getCallable());
        }
        if ($test instanceof IfwPsn_Vendor_Twig_SimpleTest) {
            $this->setAttribute('is_variadic', $test->isVariadic());
        }

        $this->compileCallable($compiler);
    }
}

//class_alias('IfwPsn_Vendor_Twig_Node_Expression_Test', 'Twig\Node\Expression\TestExpression', false);
