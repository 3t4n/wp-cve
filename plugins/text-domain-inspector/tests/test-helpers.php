<?php

use TextDomainInspector\Helpers;

class HelpersTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_recognize_json()
    {
        $this->assertFalse(Helpers::isJSON('not JSON'));
        
        $this->assertTrue(Helpers::isJSON('{ "json" : true }'));
    }

    /** @test */
    public function it_can_recognize_html_document()
    {
        $this->assertFalse(Helpers::isHTMLDocument('<a>not HTML document</a>'));
        
        $this->assertTrue(Helpers::isHTMLDocument('<html><head></head><body>is HTML document</body></html>'));
    }

    /** @test */
    public function it_can_recognize_html_fragment()
    {
        $this->assertFalse(Helpers::isHTMLFragment('not HTML fragment'));

        $this->assertTrue(Helpers::isHTMLFragment('is <span>HTML</span> fragment'));

        $this->assertTrue(Helpers::isHTMLFragment('<p>is HTML fragment</p>'));
    }
}
