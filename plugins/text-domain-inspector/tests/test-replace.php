<?php

use TextDomainInspector\Replace;

class ReplaceTest extends PHPUnit_Framework_TestCase
{
    const PATTERN = '/\[textdomain=(.+?);\]?/';
    const REPLACEMENT = '<span title="$1" style="border-radius:50%; background:red; color: white; min-width: 10px; max-width: 10px; width: 10px; min-height: 10px; height: 10px; max-height:10px; display: inline-block;"></span>';
    const REPLACEMENT_BRACKETS = '(text-domain: %s)';

    /** @test */
    public function it_can_transform_html_document()
    {
        $result = file_get_contents(__DIR__ . '/results/document.html');
        $result = HTML5_Parser::parse($result)->saveHTML();
        
        $document = file_get_contents(__DIR__ . '/stubs/document.html');

        $replace = new Replace(static::PATTERN, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets = false);

        $this->assertEquals($result, $replace->inHTMLDocument($document));
    }

    /** @test */
    public function it_can_transform_html_document_replacing_shortcode_with_domain_in_the_brackets()
    {
        $result = file_get_contents(__DIR__ . '/results/document-brackets.html');
        $result = HTML5_Parser::parse($result)->saveHTML();
        
        $document = file_get_contents(__DIR__ . '/stubs/document.html');

        $replace = new Replace(static::PATTERN, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets = true);

        $this->assertEquals($result, $replace->inHTMLDocument($document));
    }

    /** @test */
    public function it_can_transform_plain_text()
    {
        $text = 'Test[textdomain=test;]';

        $replace = new Replace(static::PATTERN, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets = false);

        $this->assertEquals('Test', $replace->inPlainText($text));
    }

    /** @test */
    public function it_can_transform_plain_text_replacing_shortcode_with_domain_in_brackets()
    {
        $text = 'Test[textdomain=test;]';

        $replace = new Replace(static::PATTERN, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets = true);

        $this->assertEquals('Test(text-domain: test)', $replace->inPlainText($text));
    }

    /** @test */
    public function it_can_transform_html_fragment()
    {
        $result = file_get_contents(__DIR__ . '/results/fragment.html');
        $result = HTML5_Parser::parse($result)->saveHTML();

        $result = str_replace("<html><head></head><body>", '', $result);
        $result = str_replace("</body></html>", '', $result);

        $fragment = file_get_contents(__DIR__ . '/stubs/fragment.html');

        $replace = new Replace(static::PATTERN, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets = false);

        $this->assertEquals(trim($result), trim($replace->inHTMLFragment($fragment)));
    }

    /** @test */
    public function it_can_transform_html_fragment_replacing_shortcode_with_domain_in_brackets()
    {
        $result = file_get_contents(__DIR__ . '/results/fragment-brackets.html');
        
        $fragment = file_get_contents(__DIR__ . '/stubs/fragment.html');

        $replace = new Replace(static::PATTERN, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets = true);

        $this->assertEquals($result, $replace->inHTMLFragment($fragment));
    }

    /** @test */
    public function it_can_transform_json()
    {
        $result = file_get_contents(__DIR__ . '/results/json.json');

        $json = file_get_contents(__DIR__ . '/stubs/json.json');

        $replace = new Replace(static::PATTERN, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets = false);

        $this->assertEquals($result, $replace->inJson($json));
    }
}
