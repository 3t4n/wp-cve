<?php

use PHPUnit\Framework\TestCase;
use KhanhIceTea\Twigeval\Calculator;

class CalculatorTest extends TestCase
{
    private $calculator;

    public function setUp()
    {
        $this->calculator = new Calculator();
    }
    
    public function testMath()
    {
        $exp = "{{ (14 + 7 * 8) ** (49 / 7 - 5)  }}";
        $result = $this->calculator->calculate($exp);

        $this->assertEquals($result, "4900");
    }

    public function testMathWithoutBrackets()
    {
        $exp = "(12 + 58) * (49 / 7 - 5)";
        $result = $this->calculator->calculate($exp);

        $this->assertEquals($result, "140");
    }

    public function testMathVariables()
    {
        $exp = "{{ a * 3 + b / 5 }}";
        $result = $this->calculator->calculate($exp, ['a' => 9, 'b' => 40]);

        $this->assertEquals($result, "35");
    }

    public function testMathVariablesWithoutBrackets()
    {
        $exp = "a * 3 + b / 5";
        $result = $this->calculator->calculate($exp, ['a' => 9, 'b' => 40]);

        $this->assertEquals($result, "35");
    }

    public function testStringVariables()
    {
        $exp = "{{ name|reverse }}@{{ domain|upper }}";
        $result = $this->calculator->calculate($exp, ['name' => 'hello', 'domain' => 'GMAIL.com']);

        $this->assertEquals($result, "olleh@GMAIL.COM");
    }

    public function testBooleanVariables()
    {
        $exp1 = "(a or b) and c";
        $result1 = $this->calculator->isTrue($exp1, ['a' => true, 'b' => false, 'c' => true]);

        $exp2 = "(a and b) or c";
        $result2 = $this->calculator->isFalse($exp2, ['a' => false, 'b' => false, 'c' => false]);

        $this->assertEquals($result1, true);
        $this->assertEquals($result2, true);
    }

    public function testNumberVariables()
    {
        $exp1 = "a * 3 + b / 5";
        $result1 = $this->calculator->number($exp1, ['a' => 9, 'b' => 40]);

        $exp2 = "a * 3.222 + 88.55 / 5";
        $result2 = $this->calculator->number($exp2, ['a' => 9, 'b' => 40]);

        $this->assertEquals($result1, 35);
        $this->assertEquals($result2, 46.708);
    }

    public function testExceptionCatchVariables()
    {
        $exp1 = "a * 3 + b / 5";
        $result1 = $this->calculator->number($exp1, ['a' => 9, 'd' => 40]);

        $exp2 = "(a and b) or c";
        $result2 = $this->calculator->isFalse($exp2, ['a' => false, 'b' => false, 'd' => false]);

        $this->assertEquals($result1, null);
        $this->assertEquals($result2, null);
    }

    public function testNonStrictVariables()
    {
        $calculator = new Calculator(null, true, [
            'strict_variables' => false,
        ]);

        $exp1 = "a * 3 + b / 5";
        $result1 = $calculator->number($exp1, ['a' => 9, 'd' => 40]);

        $exp2 = "(a and b) or c";
        $result2 = $calculator->isFalse($exp2, ['a' => false, 'b' => false, 'd' => false]);

        $this->assertEquals($result1, 27.0);
        $this->assertEquals($result2, true);
    }

    public function testValidate()
    {
        $exp1 = "a === b";
        $result1 = $this->calculator->validate($exp1);

        $exp2 = "a == b";
        $result2 = $this->calculator->validate($exp2);

        $exp3 = "{{ a === b }}";
        $result3 = $this->calculator->validate($exp3);

        $exp4 = "{{ a == b }}";
        $result4 = $this->calculator->validate($exp4);

        $this->assertEquals($result1, false);
        $this->assertEquals($result2, true);
        $this->assertEquals($result3, false);
        $this->assertEquals($result4, true);
    }
}
