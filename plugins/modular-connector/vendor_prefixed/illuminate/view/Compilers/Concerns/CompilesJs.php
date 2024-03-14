<?php

namespace Modular\ConnectorDependencies\Illuminate\View\Compilers\Concerns;

use Modular\ConnectorDependencies\Illuminate\Support\Js;
/** @internal */
trait CompilesJs
{
    /**
     * Compile the "@js" directive into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileJs(string $expression)
    {
        return \sprintf("<?php echo \\%s::from(%s)->toHtml() ?>", Js::class, $this->stripParentheses($expression));
    }
}
