<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Expression;

/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
interface CompilableExpressionEvaluatorInterface
{
    public function parse(string $expression, array $names = []) : \WPPayVendor\JMS\Serializer\Expression\Expression;
    /**
     * @return mixed
     */
    public function evaluateParsed(\WPPayVendor\JMS\Serializer\Expression\Expression $expression, array $data = []);
}
