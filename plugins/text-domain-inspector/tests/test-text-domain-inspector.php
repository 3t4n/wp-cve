<?php

class TextDomainInspectorTest extends WP_UnitTestCase
{
    /** @test */
    public function it_transforms_html_document()
    {
        $instance = new TextDomainInspector($showInBrackets = false);

        $result = file_get_contents(__DIR__ . '/results/document.html');
        $result = HTML5_Parser::parse($result)->saveHTML();

        $document = file_get_contents(__DIR__ . '/stubs/document.html');
        
        $this->assertEquals($result, $instance->replaceShortcodes($document));
    }

    /** @test */
    public function it_can_transform_html_document_replacing_shortcode_with_domain_in_the_brackets()
    {
        $instance = new TextDomainInspector($showInBrackets = true);

        $result = file_get_contents(__DIR__ . '/results/document-brackets.html');
        $result = HTML5_Parser::parse($result)->saveHTML();

        $document = file_get_contents(__DIR__ . '/stubs/document.html');
        
        $this->assertEquals($result, $instance->replaceShortcodes($document));
    }

    /** @test */
    public function it_can_transform_plain_text()
    {
        $instance = new TextDomainInspector($showInBrackets = false);

        $this->assertEquals('Test', $instance->replaceShortcodes('Test[textdomain=test;]'));
    }

    /** @test */
    public function it_can_transform_plain_text_replacing_shortcode_with_domain_in_brackets()
    {
        $instance = new TextDomainInspector($showInBrackets = true);

        $this->assertEquals('Test(text-domain: test)', $instance->replaceShortcodes('Test[textdomain=test;]'));
    }

    /** @test */
    public function it_can_transform_html_fragment()
    {
        $instance = new TextDomainInspector($showInBrackets = false);

        $result = file_get_contents(__DIR__ . '/results/fragment.html');
        
        $fragment = file_get_contents(__DIR__ . '/stubs/fragment.html');

        $this->assertEquals($result, $instance->replaceShortcodes($fragment));
    }

    /** @test */
    public function it_can_transform_html_fragment_replacing_shortcode_with_domain_in_brackets()
    {
        $instance = new TextDomainInspector($showInBrackets = true);

        $result = file_get_contents(__DIR__ . '/results/fragment-brackets.html');

        $fragment = file_get_contents(__DIR__ . '/stubs/fragment.html');

        $this->assertEquals($result, $instance->replaceShortcodes($fragment));
    }

    /** @test */
    public function it_can_transform_json()
    {
        $instance = new TextDomainInspector($showInBrackets = false);

        $result = file_get_contents(__DIR__ . '/results/json.json');

        $fragment = file_get_contents(__DIR__ . '/stubs/json.json');

        $this->assertEquals($result, $instance->replaceShortcodes($fragment));
    }
}
