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
use WPPayVendor\Symfony\Component\CssSelector\Node\FunctionNode;
use WPPayVendor\Symfony\Component\CssSelector\XPath\Translator;
use WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr;
/**
 * XPath expression translator HTML extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class HtmlExtension extends \WPPayVendor\Symfony\Component\CssSelector\XPath\Extension\AbstractExtension
{
    public function __construct(\WPPayVendor\Symfony\Component\CssSelector\XPath\Translator $translator)
    {
        $translator->getExtension('node')->setFlag(\WPPayVendor\Symfony\Component\CssSelector\XPath\Extension\NodeExtension::ELEMENT_NAME_IN_LOWER_CASE, \true)->setFlag(\WPPayVendor\Symfony\Component\CssSelector\XPath\Extension\NodeExtension::ATTRIBUTE_NAME_IN_LOWER_CASE, \true);
    }
    /**
     * {@inheritdoc}
     */
    public function getPseudoClassTranslators() : array
    {
        return ['checked' => [$this, 'translateChecked'], 'link' => [$this, 'translateLink'], 'disabled' => [$this, 'translateDisabled'], 'enabled' => [$this, 'translateEnabled'], 'selected' => [$this, 'translateSelected'], 'invalid' => [$this, 'translateInvalid'], 'hover' => [$this, 'translateHover'], 'visited' => [$this, 'translateVisited']];
    }
    /**
     * {@inheritdoc}
     */
    public function getFunctionTranslators() : array
    {
        return ['lang' => [$this, 'translateLang']];
    }
    public function translateChecked(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('(@checked ' . "and (name(.) = 'input' or name(.) = 'command')" . "and (@type = 'checkbox' or @type = 'radio'))");
    }
    public function translateLink(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition("@href and (name(.) = 'a' or name(.) = 'link' or name(.) = 'area')");
    }
    public function translateDisabled(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('(' . '@disabled and' . '(' . "(name(.) = 'input' and @type != 'hidden')" . " or name(.) = 'button'" . " or name(.) = 'select'" . " or name(.) = 'textarea'" . " or name(.) = 'command'" . " or name(.) = 'fieldset'" . " or name(.) = 'optgroup'" . " or name(.) = 'option'" . ')' . ') or (' . "(name(.) = 'input' and @type != 'hidden')" . " or name(.) = 'button'" . " or name(.) = 'select'" . " or name(.) = 'textarea'" . ')' . ' and ancestor::fieldset[@disabled]');
        // todo: in the second half, add "and is not a descendant of that fieldset element's first legend element child, if any."
    }
    public function translateEnabled(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('(' . '@href and (' . "name(.) = 'a'" . " or name(.) = 'link'" . " or name(.) = 'area'" . ')' . ') or (' . '(' . "name(.) = 'command'" . " or name(.) = 'fieldset'" . " or name(.) = 'optgroup'" . ')' . ' and not(@disabled)' . ') or (' . '(' . "(name(.) = 'input' and @type != 'hidden')" . " or name(.) = 'button'" . " or name(.) = 'select'" . " or name(.) = 'textarea'" . " or name(.) = 'keygen'" . ')' . ' and not (@disabled or ancestor::fieldset[@disabled])' . ') or (' . "name(.) = 'option' and not(" . '@disabled or ancestor::optgroup[@disabled]' . ')' . ')');
    }
    /**
     * @throws ExpressionErrorException
     */
    public function translateLang(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \WPPayVendor\Symfony\Component\CssSelector\Node\FunctionNode $function) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        $arguments = $function->getArguments();
        foreach ($arguments as $token) {
            if (!($token->isString() || $token->isIdentifier())) {
                throw new \WPPayVendor\Symfony\Component\CssSelector\Exception\ExpressionErrorException('Expected a single string or identifier for :lang(), got ' . \implode(', ', $arguments));
            }
        }
        return $xpath->addCondition(\sprintf('ancestor-or-self::*[@lang][1][starts-with(concat(' . "translate(@%s, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '-')" . ', %s)]', 'lang', \WPPayVendor\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral(\strtolower($arguments[0]->getValue()) . '-')));
    }
    public function translateSelected(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition("(@selected and name(.) = 'option')");
    }
    public function translateInvalid(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('0');
    }
    public function translateHover(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('0');
    }
    public function translateVisited(\WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr $xpath) : \WPPayVendor\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition('0');
    }
    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'html';
    }
}
