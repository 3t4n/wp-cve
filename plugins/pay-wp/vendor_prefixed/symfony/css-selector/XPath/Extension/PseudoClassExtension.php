<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\CssSelector\XPath\Extension;

use WPPayVendor\Symfony\Component\CssSelector\Exception\ExpressionErrorException;
use WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr;
/**
 * XPath expression translator pseudo-class extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class PseudoClassExtension extends \WPPayVendor\Symfony\Component\CssSelector\XPath\Extension\AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getPseudoClassTranslators() : array
    {
        return ['root' => [$this, 'translateRoot'], 'first-child' => [$this, 'translateFirstChild'], 'last-child' => [$this, 'translateLastChild'], 'first-of-type' => [$this, 'translateFirstOfType'], 'last-of-type' => [$this, 'translateLastOfType'], 'only-child' => [$this, 'translateOnlyChild'], 'only-of-type' => [$this, 'translateOnlyOfType'], 'empty' => [$this, 'translateEmpty']];
    }
    public function translateRoot(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('not(parent::*)');
    }
    public function translateFirstChild(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addStarPrefix()->addNameTest()->addCondition('position() = 1');
    }
    public function translateLastChild(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addStarPrefix()->addNameTest()->addCondition('position() = last()');
    }
    /**
     * @throws ExpressionErrorException
     */
    public function translateFirstOfType(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if ('*' === $xpath->getElement()) {
            throw new \WPPayVendor\Symfony\Component\CssSelector\Exception\ExpressionErrorException('"*:first-of-type" is not implemented.');
        }
        return $xpath->addStarPrefix()->addCondition('position() = 1');
    }
    /**
     * @throws ExpressionErrorException
     */
    public function translateLastOfType(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if ('*' === $xpath->getElement()) {
            throw new \WPPayVendor\Symfony\Component\CssSelector\Exception\ExpressionErrorException('"*:last-of-type" is not implemented.');
        }
        return $xpath->addStarPrefix()->addCondition('position() = last()');
    }
    public function translateOnlyChild(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addStarPrefix()->addNameTest()->addCondition('last() = 1');
    }
    public function translateOnlyOfType(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        $element = $xpath->getElement();
        return $xpath->addCondition(\sprintf('count(preceding-sibling::%s)=0 and count(following-sibling::%s)=0', $element, $element));
    }
    public function translateEmpty(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('not(*) and not(string-length())');
    }
    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'pseudo-class';
    }
}
