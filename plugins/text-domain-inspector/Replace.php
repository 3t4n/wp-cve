<?php

namespace TextDomainInspector;

require_once(__DIR__.'/html5lib-php/library/HTML5/Parser.php');

use DOMElement;
use DOMText;
use HTML5_Parser;

class Replace
{
    protected $pattern;
    protected $replacement;
    protected $bracketsReplacement;
    protected $showInBrackets;
    
    public function __construct($pattern, $replacement, $bracketsReplacement, $showInBrackets = false)
    {
        $this->pattern = $pattern;
        $this->replacement = $replacement;
        $this->bracketsReplacement = $bracketsReplacement;
        $this->showInBrackets = $showInBrackets;
    }

    public function inJson($string)
    {
        $json = json_decode($string);

        $this->executeOnIterableChilds($json, function (&$child) {
            if (Helpers::isHTMLDocument($child)) {
                $child = $this->inHTMLDocument($child);
                return;
            }

            if (Helpers::isHTMLFragment($child)) {
                $child = $this->inHTMLFragment($child);
                return;
            }

            $child = $this->inPlainText($child);
            return;
        });


        return json_encode($json);
    }

    public function inHTMLFragment(&$string)
    {
        $document = HTML5_Parser::parse($string);

        $this->body($document);

        $document = $this->shortcodes($document);

        $return = str_replace('<html><head></head><body>', '', $document);

        $return = str_replace('</body></html>', '', $return);

        return $return;
    }

    public function inPlainText(&$string)
    {
        if ($this->showInBrackets) {
            preg_match($this->pattern, $string, $matches);
            if (isset($matches[1])) {
                return preg_replace($this->pattern, sprintf($this->bracketsReplacement, $matches[1]), $string);
            }
        }

        return preg_replace($this->pattern, '', $string);
    }

    public function inHTMLDocument(&$string)
    {
        $document = HTML5_Parser::parse($string);

        $this->head($document);
        $this->scripts($document);
        $this->body($document);
        return $this->shortcodes($document);
    }
    
    protected function head(&$document)
    {
        $head = $document->getElementsByTagName('head')->item(0);

        $this->executeOnChildNodes($head, function (&$child = null) {
            if ($this->showInBrackets) {
                $this->addToBracketsInElementText($child);
                $this->addToBracketsInElementAttribute($child);
            } else {
                $this->removeFromElementAttribute($child);
                $this->removeFromElementText($child);
            }
        });
    }
    
    protected function scripts(&$document)
    {
        $scripts = $document->getElementsByTagName('script');

        array_map(function (&$script) {
            $this->executeOnChildNodes($script, function (&$child = null) {
                if ($this->showInBrackets) {
                    $this->addToBracketsInElementText($child);
                } else {
                    $this->removeFromElementText($child);
                }
            });
        }, iterator_to_array($scripts));
    }
    
    protected function body(&$document)
    {
        $body = $document->getElementsByTagName('body')->item(0);
        
        $this->executeOnChildNodes($body, function (&$child = null) {
            if ($this->showInBrackets) {
                $this->addToBracketsInElementAttribute($child);
            } else {
                $this->removeFromElementAttribute($child);
            }
        });
    }
    
    protected function shortcodes($document)
    {
        return preg_replace($this->pattern, $this->replacement, $document->saveHTML());
    }
    
    protected function removeFromElementAttribute(&$element)
    {
        if ($element instanceof DOMElement && $element->hasAttributes()) {
            foreach ($element->attributes as $attribute) {
                $attribute->value = preg_replace($this->pattern, '', $attribute->value);
            }
        }
    }
    
    protected function removeFromElementText(&$element)
    {
        if ($element != null && $element instanceof DOMText) {
            $element->nodeValue = preg_replace($this->pattern, '', $element->nodeValue);
        }
    }
    
    protected function executeOnChildNodes(&$node, $func)
    {
        $func($node);
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                $this->executeOnChildNodes($child, $func);
            }
        }
    }
    
    protected function addToBracketsInElementText(&$element)
    {
        if ($element != null && $element instanceof DOMText) {
            preg_match($this->pattern, $element->nodeValue, $matches);
            if (isset($matches[1])) {
                $element->nodeValue = preg_replace($this->pattern, sprintf($this->bracketsReplacement, $matches[1]), $element->nodeValue);
            }
        }
    }
    
    protected function addToBracketsInElementAttribute(&$element)
    {
        if ($element instanceof DOMElement && $element->hasAttributes()) {
            foreach ($element->attributes as $attribute) {
                preg_match($this->pattern, $attribute->value, $matches);
                if (isset($matches[1])) {
                    $attribute->value = preg_replace($this->pattern, sprintf($this->bracketsReplacement, $matches[1]), $attribute->value);
                }
            }
        }
    }

    protected function executeOnIterableChilds(&$value, $callable)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as &$prop) {
                $this->executeOnIterableChilds($prop, $callable);
            }
        } else {
            $callable($value);
        }
    }
}
