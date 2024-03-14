<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\CssSelector\Node;

use WPPayVendor\Symfony\Component\CssSelector\Parser\Token;
/**
 * Represents a "<selector>:<name>(<arguments>)" node.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class FunctionNode extends \WPPayVendor\Symfony\Component\CssSelector\Node\AbstractNode
{
    private $selector;
    private $name;
    private $arguments;
    /**
     * @param Token[] $arguments
     */
    public function __construct(\WPPayVendor\Symfony\Component\CssSelector\Node\NodeInterface $selector, string $name, array $arguments = [])
    {
        $this->selector = $selector;
        $this->name = \strtolower($name);
        $this->arguments = $arguments;
    }
    public function getSelector() : \WPPayVendor\Symfony\Component\CssSelector\Node\NodeInterface
    {
        return $this->selector;
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @return Token[]
     */
    public function getArguments() : array
    {
        return $this->arguments;
    }
    /**
     * {@inheritdoc}
     */
    public function getSpecificity() : \WPPayVendor\Symfony\Component\CssSelector\Node\Specificity
    {
        return $this->selector->getSpecificity()->plus(new \WPPayVendor\Symfony\Component\CssSelector\Node\Specificity(0, 1, 0));
    }
    public function __toString() : string
    {
        $arguments = \implode(', ', \array_map(function (\WPPayVendor\Symfony\Component\CssSelector\Parser\Token $token) {
            return "'" . $token->getValue() . "'";
        }, $this->arguments));
        return \sprintf('%s[%s:%s(%s)]', $this->getNodeName(), $this->selector, $this->name, $arguments ? '[' . $arguments . ']' : '');
    }
}
