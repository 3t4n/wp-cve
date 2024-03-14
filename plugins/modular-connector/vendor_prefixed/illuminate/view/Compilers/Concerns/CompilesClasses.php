<?php

namespace Modular\ConnectorDependencies\Illuminate\View\Compilers\Concerns;

/** @internal */
trait CompilesClasses
{
    /**
     * Compile the conditional class statement into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileClass($expression)
    {
        $expression = \is_null($expression) ? '([])' : $expression;
        return "class=\"<?php echo \\Modular\\ConnectorDependencies\\Illuminate\\Support\\Arr::toCssClasses{$expression} ?>\"";
    }
}
