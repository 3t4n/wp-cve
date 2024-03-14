<?php
namespace KhanhIceTea\Twigeval;

use Exception;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Source;

class Calculator
{
    private $twig;
    private $cacthException;

    // Be carefully !!! eval function will be runned if $cacheDir equals false
    public function __construct($cacheDir = null, $cacthException = true, array $twigOptions = [])
    {
        $this->cacthException = $cacthException;

        $twigOptions = array_merge(array(
            'strict_variables' => true,
            'cache' => is_null($cacheDir) ? sys_get_temp_dir() : $cacheDir,
        ), $twigOptions);

        $this->twig = new Environment(new ArrayLoader(), $twigOptions);
    }

    public function renderFromString(string $template, array $variables = [])
    {
        // md5 is enough for hashing key cache and performance
        $name = '__string_template__'.md5($template);
        $this->twig->getLoader()->setTemplate($name, $template);

        try {
            return $this->twig->loadTemplate($this->twig->getTemplateClass($name), $name)->render($variables);
        } catch (Exception $e) {
            if ($this->cacthException) {
                return null;
            }
            throw $e;
        }
    }

    protected function normalizeExpression(string $expression)
    {
        $expression = trim($expression);
        if (substr($expression, 0, 1) != "{" && substr($expression, -1) != "}") {
            $expression = '{{ '.$expression.' }}';
        }

        return $expression;
    }

    public function calculate(string $expression, array $variables = [])
    {
        return $this->renderFromString($this->normalizeExpression($expression), $variables);
    }

    public function number(string $expression, array $variables = [])
    {
        $result = $this->calculate($expression, $variables);

        return is_null($result) ? null : (is_int($result) ? (int) $result : (double) $result);
    }

    public function isTrue(string $expression, array $variables = [])
    {
        $expression = $expression." ? 1 : 0";
        $result = $this->calculate($expression, $variables);

        return is_null($result) ? null : ($result === "1");
    }

    public function isFalse(string $expression, array $variables = [])
    {
        $isTrue = $this->isTrue($expression, $variables);

        return is_null($isTrue) ? null : !$isTrue;
    }

    public function validate(string $expression)
    {
        try {
            $code = $this->normalizeExpression($expression);
            $source = new Source($code, md5($code));
            $this->twig->parse($this->twig->tokenize($source));
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}
