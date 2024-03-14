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

use WPPayVendor\Symfony\Component\CssSelector\XPath\Translator;
use WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr;
/**
 * XPath expression translator attribute extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class AttributeMatchingExtension extends \WPPayVendor\Symfony\Component\CssSelector\XPath\Extension\AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAttributeMatchingTranslators() : array
    {
        return ['exists' => [$this, 'translateExists'], '=' => [$this, 'translateEquals'], '~=' => [$this, 'translateIncludes'], '|=' => [$this, 'translateDashMatch'], '^=' => [$this, 'translatePrefixMatch'], '$=' => [$this, 'translateSuffixMatch'], '*=' => [$this, 'translateSubstringMatch'], '!=' => [$this, 'translateDifferent']];
    }
    public function translateExists(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($attribute);
    }
    public function translateEquals(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition(\sprintf('%s = %s', $attribute, \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)));
    }
    public function translateIncludes(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and contains(concat(\' \', normalize-space(%1$s), \' \'), %2$s)', $attribute, \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral(' ' . $value . ' ')) : '0');
    }
    public function translateDashMatch(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition(\sprintf('%1$s and (%1$s = %2$s or starts-with(%1$s, %3$s))', $attribute, \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value), \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value . '-')));
    }
    public function translatePrefixMatch(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and starts-with(%1$s, %2$s)', $attribute, \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)) : '0');
    }
    public function translateSuffixMatch(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and substring(%1$s, string-length(%1$s)-%2$s) = %3$s', $attribute, \strlen($value) - 1, \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)) : '0');
    }
    public function translateSubstringMatch(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and contains(%1$s, %2$s)', $attribute, \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)) : '0');
    }
    public function translateDifferent(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition(\sprintf($value ? 'not(%1$s) or %1$s != %2$s' : '%s != %s', $attribute, \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)));
    }
    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'attribute-matching';
    }
}
