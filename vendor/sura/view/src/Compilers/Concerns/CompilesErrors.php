<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesErrors
{
    /**
     * @error('key')
     *
     * @param $expression
     * @return string
     */
    protected function compileError($expression): string
    {
        $key = $this->stripParentheses($expression);
        return $this->phpTag . '$message = call_user_func($this->errorCallBack,' . $key . '); if ($message): ?>';
    }

    /**
     * Compile the end-error statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndError(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

}