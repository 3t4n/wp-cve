<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\CssSelector\Parser\Handler;

use WPPayVendor\Symfony\Component\CssSelector\Parser\Reader;
use WPPayVendor\Symfony\Component\CssSelector\Parser\Token;
use WPPayVendor\Symfony\Component\CssSelector\Parser\Tokenizer\TokenizerEscaping;
use WPPayVendor\Symfony\Component\CssSelector\Parser\Tokenizer\TokenizerPatterns;
use WPPayVendor\Symfony\Component\CssSelector\Parser\TokenStream;
/**
 * CSS selector comment handler.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class HashHandler implements \WPPayVendor\Symfony\Component\CssSelector\Parser\Handler\HandlerInterface
{
    private $patterns;
    private $escaping;
    public function __construct(\WPPayVendor\Symfony\Component\CssSelector\Parser\Tokenizer\TokenizerPatterns $patterns, \WPPayVendor\Symfony\Component\CssSelector\Parser\Tokenizer\TokenizerEscaping $escaping)
    {
        $this->patterns = $patterns;
        $this->escaping = $escaping;
    }
    /**
     * {@inheritdoc}
     */
    public function handle(\WPPayVendor\Symfony\Component\CssSelector\Parser\Reader $reader, \WPPayVendor\Symfony\Component\CssSelector\Parser\TokenStream $stream) : bool
    {
        $match = $reader->findPattern($this->patterns->getHashPattern());
        if (!$match) {
            return \false;
        }
        $value = $this->escaping->escapeUnicode($match[1]);
        $stream->push(new \WPPayVendor\Symfony\Component\CssSelector\Parser\Token(\WPPayVendor\Symfony\Component\CssSelector\Parser\Token::TYPE_HASH, $value, $reader->getPosition()));
        $reader->moveForward(\strlen($match[0]));
        return \true;
    }
}
